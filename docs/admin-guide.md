# Administration Guide – Alias Manager

This guide is intended for WordPress administrators who want to install, configure and maintain the plugin.

---

## System requirements

| Component | Minimum version |
|---|---|
| PHP | 8.1 |
| WordPress | 5.9 |
| MySQL | 5.7 |
| MariaDB | 10.3 |

The plugin has no external PHP dependencies (no Composer packages required in production).

---

## Installation

### Method 1: Manual via FTP / SFTP

1. Upload the `alias-manager` folder (or the extracted contents of `alias-manager.zip`) to the `wp-content/plugins/` directory on your server.
2. In the WordPress admin under **Plugins → Installed Plugins**, activate the **Alias Manager** plugin.

### Method 2: Upload via WordPress admin

1. In the WordPress admin, click **Plugins → Add New**.
2. Select the **Upload Plugin** tab.
3. Choose the `alias-manager.zip` file and click **Install Now**.
4. Then click **Activate Plugin**.

### What happens on activation

When activated, the plugin automatically creates the database table `{prefix}_aliases` (default: `wp_aliases`). Existing tables are not overwritten thanks to `dbDelta`.

---

## Permissions

The plugin checks the WordPress capability `manage_options` for all admin pages. Only users with the Administrator role (or a role with `manage_options` explicitly assigned) can manage aliases.

---

## Database table

```sql
CREATE TABLE {prefix}_aliases (
    id         mediumint(9)  NOT NULL AUTO_INCREMENT,
    alias      varchar(255)  NOT NULL,
    target_url varchar(2000) NOT NULL,
    created_at datetime      NOT NULL DEFAULT CURRENT_TIMESTAMP,
    PRIMARY KEY  (id),
    UNIQUE KEY alias (alias)
);
```

- The `alias` column has a `UNIQUE KEY` — duplicate alias paths are prevented at the database level.
- `target_url` supports URLs up to 2000 characters, which covers all common URL lengths.

---

## Deactivating and uninstalling

### Deactivate

The plugin can be deactivated at any time (**Plugins → Alias Manager → Deactivate**). The database table and all stored aliases are preserved, but redirects will be inactive.

### Uninstall / Remove table

The plugin does **not** automatically delete the database table (to prevent accidental data loss). To remove the table manually:

```sql
DROP TABLE IF EXISTS wp_aliases;
```

Or delete the table `wp_aliases` via phpMyAdmin / Adminer.

---

## WordPress in a subdirectory

If WordPress is installed in a subdirectory (e.g. `https://example.com/blog`), the plugin automatically detects the base path via `home_url()` and strips it from the request path. No additional configuration is needed.

---

## Multisite

The plugin is **not** optimized for WordPress Multisite networks. On network activation, the table is only created for the main site. Multisite support requires developer customization (see [Developer Guide](developer-guide.md)).

---

## Caching

Since redirects are executed via `wp_redirect()` + `exit` before template rendering, cached page responses (e.g. from WP Super Cache, W3 Total Cache, LiteSpeed Cache) are **not** served — the alias check always runs. Page caches are therefore compatible with this plugin.

If you use a reverse proxy cache (e.g. Varnish, Cloudflare), note:
- 301 responses are cached by proxies by default. Changes to an alias will not be visible until the cache entry expires or is manually invalidated.

---

## Troubleshooting

### Alias is not redirecting

- Check that the alias path exactly matches the requested URL path (no leading/trailing slash).
- Make sure the plugin is activated.
- Check whether another plugin or `.htaccess` is intercepting the request before WordPress.

### Error message "Alias slug already in use"

The entered path already exists in the database. Use a different slug or edit the existing alias.

### Database table is missing

If the table was not created on activation (e.g. due to insufficient database permissions), you can trigger creation by deactivating and reactivating the plugin. Alternatively, run the SQL from the [Database table](#database-table) section directly.

### PHP errors after update

Make sure PHP 8.1+ is active. The `str_starts_with()` function (used in the Redirector) is available from PHP 8.0, but PHPUnit 10 requires PHP 8.1.

---

## Security notes

- All form input is sanitized with `sanitize_text_field()` and `esc_url_raw()`.
- All admin actions (save, edit, delete) are protected by WordPress nonces (CSRF protection).
- The admin page is protected behind the `manage_options` capability.
