# Developer Guide – Alias Manager

This guide is intended for PHP developers who want to understand, extend or integrate the plugin into their own projects.

---

## Project structure

```
alias-manager/
├── alias-manager.php               # Plugin header, entry point, hook registration
├── composer.json                   # Dev dependencies (PHPUnit, Brain\Monkey)
├── phpunit.xml                     # PHPUnit configuration
├── README.md
├── docs/
│   ├── user-guide.md
│   ├── admin-guide.md
│   └── developer-guide.md          # This file
├── includes/
│   ├── class-alias-db.php          # Database layer (CRUD)
│   └── class-alias-redirector.php  # Request interception and redirect
├── admin/
│   └── class-alias-admin.php       # Admin UI (menu, form, table)
├── languages/
│   ├── alias-manager.pot           # Translation template
│   ├── alias-manager-de_DE.po/.mo  # German
│   ├── alias-manager-en_US.po/.mo  # English
│   ├── alias-manager-fr_FR.po/.mo  # French
│   ├── alias-manager-es_ES.po/.mo  # Spanish
│   └── alias-manager-sv_SE.po/.mo  # Swedish
└── tests/
    ├── bootstrap.php               # PHPUnit bootstrap
    └── Unit/
        ├── AliasDBTest.php
        ├── AliasRedirectorTest.php
        ├── AliasAdminTest.php
        └── LanguageFilesTest.php
```

---

## Architecture

### Layer model

```
┌─────────────────────────────────────────────┐
│            alias-manager.php                │  Entry point
│  register_activation_hook / add_action      │  Hook wiring
└──────────┬───────────────────┬──────────────┘
           │                   │
  ┌────────▼──────┐   ┌────────▼────────┐
  │  Redirector   │   │   Admin UI      │
  │  (init hook)  │   │ (admin_menu)    │
  └────────┬──────┘   └────────┬────────┘
           │                   │
           └─────────┬─────────┘
                ┌────▼──────┐
                │  DB Layer  │
                │Alias_Mgr_DB│
                └─────┬──────┘
                      │
               ┌──────▼──────┐
               │  $wpdb / DB  │
               └─────────────┘
```

### Classes

#### `Alias_Manager_DB` (`includes/class-alias-db.php`)

Static utility class. Encapsulates all database operations. No state — all methods are `static`.

| Method | Description |
|---|---|
| `table()` | Returns the full table name with the WordPress prefix |
| `create_table()` | Creates the table via `dbDelta` (idempotent) |
| `all()` | Returns all aliases sorted by `alias ASC` |
| `get(int $id)` | Returns a single alias record |
| `find_by_alias(string $alias)` | Returns `target_url` for an alias path or `null` |
| `insert(string $alias, string $target_url)` | Creates a new record |
| `update(int $id, string $alias, string $target_url)` | Updates an existing record |
| `delete(int $id)` | Deletes a record |

#### `Alias_Manager_Redirector` (`includes/class-alias-redirector.php`)

Runs on the `init` hook. Checks whether the current request path matches an alias and performs a 301 redirect if so.

**Flow in `maybe_redirect()`:**

1. Early return for admin, Ajax and cron requests
2. Extract request path from `$_SERVER['REQUEST_URI']`
3. Strip WordPress base path (subdirectory installations)
4. Empty path → no redirect
5. Call `Alias_Manager_DB::find_by_alias()`
6. On match: `wp_redirect($target, 301)` + `exit`

#### `Alias_Manager_Admin` (`admin/class-alias-admin.php`)

Registers a subpage under **Settings** and provides the full CRUD UI.

| Method | Hook | Description |
|---|---|---|
| `init()` | `plugins_loaded` | Registers the `admin_menu` hook |
| `register_menu()` | `admin_menu` | Adds the menu item under Settings |
| `render_page()` | Callback | Renders the complete admin page |

---

## Database schema

```sql
CREATE TABLE {prefix}_aliases (
    id         mediumint(9)  NOT NULL AUTO_INCREMENT,
    alias      varchar(255)  NOT NULL,        -- Alias path (slug), e.g. "summer-sale"
    target_url varchar(2000) NOT NULL,        -- Full target URL
    created_at datetime      NOT NULL DEFAULT CURRENT_TIMESTAMP,
    PRIMARY KEY  (id),
    UNIQUE KEY alias (alias)                  -- Unique constraint prevents duplicates
);
```

---

## Hooks & Filters

The plugin provides the following WordPress hooks to customise its behaviour.

### Filter: `alias_manager_redirect_status`

Changes the HTTP status code of the redirect (default: 301).

```php
add_filter( 'alias_manager_redirect_status', function ( int $status, string $alias, string $target ): int {
    // Temporary redirect for certain aliases
    if ( str_starts_with( $alias, 'temp-' ) ) {
        return 302;
    }
    return $status;
}, 10, 3 );
```

> **Note:** This filter must be added inside the plugin itself (see [Extensions](#extensions)).

### Filter: `alias_manager_target_url`

Allows manipulation of the target URL before the redirect.

```php
add_filter( 'alias_manager_target_url', function ( string $target_url, string $alias ): string {
    // Append UTM parameters
    return add_query_arg( 'utm_source', 'alias', $target_url );
}, 10, 2 );
```

### Action: `alias_manager_before_redirect`

Fired just before the redirect is executed.

```php
add_action( 'alias_manager_before_redirect', function ( string $alias, string $target_url ): void {
    // Log the redirect
    error_log( "Alias Manager: {$alias} → {$target_url}" );
}, 10, 2 );
```

> **Note:** The filters and actions above are examples of possible extensions. They must be implemented inside the `Alias_Manager_Redirector` class (see below).

---

## Extensions

### Adding filters to the Redirector

To support custom filters, update `maybe_redirect()` in `class-alias-redirector.php` as follows:

```php
if ( $target ) {
    $target = apply_filters( 'alias_manager_target_url', $target, $request_path );
    $status = apply_filters( 'alias_manager_redirect_status', 301, $request_path, $target );
    do_action( 'alias_manager_before_redirect', $request_path, $target );
    wp_redirect( $target, $status );
    exit;
}
```

### Adding custom admin columns

The table in `render_page()` can be migrated to the `WP_List_Table` class for a standard WordPress UI with sortable columns, pagination and bulk actions.

### Multisite support

For Multisite networks, `create_table()` must be run for each sub-site separately:

```php
// In alias-manager.php instead of register_activation_hook:
add_action( 'wpmu_new_blog', function ( int $blog_id ): void {
    switch_to_blog( $blog_id );
    Alias_Manager_DB::create_table();
    restore_current_blog();
} );
```

---

## Running tests

### Prerequisites

```bash
composer install
```

### Run unit tests

```bash
composer test
# or directly:
./vendor/bin/phpunit
```

### Run a single test

```bash
./vendor/bin/phpunit tests/Unit/AliasDBTest.php
./vendor/bin/phpunit --filter test_redirect_called_with_correct_url_and_status
```

### Test coverage (HTML report)

```bash
composer test-coverage
# Report is in: coverage/index.html
```

Requires Xdebug or PCOV as a PHP extension.

---

## Test architecture

The unit tests use [Brain\Monkey](https://brain-wp.github.io/BrainMonkey/) to mock WordPress functions and [Mockery](http://docs.mockery.io/) for object mocks (in particular `$wpdb`).

**The `exit` challenge:** The Redirector calls `exit` after `wp_redirect()`. In tests, `wp_redirect` is registered as a stub that throws a `RuntimeException` instead. This way the `exit` call is never reached and PHPUnit can catch and verify the exception.

```php
Functions\expect('wp_redirect')
    ->once()
    ->with('https://example.com/pageA', 301)
    ->andReturnUsing(function () {
        throw new \RuntimeException('redirect_called');
    });

$this->expectException(\RuntimeException::class);
Alias_Manager_Redirector::maybe_redirect();
```

---

## Coding conventions

- WordPress Coding Standards (WPCS) are recommended.
- All database values are sanitized with `sanitize_text_field()` / `esc_url_raw()`.
- Prepared statements via `$wpdb->prepare()` for all parameterized queries.
- Nonces for all write admin actions.
- No direct access without a `defined('ABSPATH')` guard.

---

## Contributing

1. Fork the repository
2. Create a feature branch: `git checkout -b feature/my-feature`
3. Write tests and keep all existing tests green: `composer test`
4. Open a pull request

Before submitting a PR, make sure there are no PHPUnit errors and the code follows the WordPress Coding Standards.
