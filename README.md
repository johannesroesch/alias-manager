# Alias Manager

WordPress plugin for managing URL aliases with automatic 301 redirects.

## Overview

Alias Manager lets you define any number of alternative paths (aliases) for existing WordPress pages. When a visitor opens an alias path, they are transparently redirected to the stored target page via HTTP 301 — SEO-friendly and without any visible detour.

**Example:**
`https://example.com/summer-sale` → `https://example.com/shop/offers/summer-2024`

## Documentation

| Audience | Document |
|---|---|
| Editors / End users | [User Guide](docs/user-guide.md) |
| Administrators | [Administration Guide](docs/admin-guide.md) |
| Developers | [Developer Guide](docs/developer-guide.md) |

## Quick start

1. Copy the `alias-manager` folder to `wp-content/plugins/`
2. Activate the plugin under **Plugins** in the WordPress admin
3. Go to **Settings → Alias Manager** and start adding aliases

## Requirements

- PHP 8.1 or higher
- WordPress 5.9 or higher
- MySQL 5.7 / MariaDB 10.3 or higher

## License

GPL-2.0+
