# Alias Manager

[![CI](https://github.com/johannesroesch/alias-manager/actions/workflows/ci.yml/badge.svg)](https://github.com/johannesroesch/alias-manager/actions/workflows/ci.yml)
[![codecov](https://codecov.io/gh/johannesroesch/alias-manager/branch/main/graph/badge.svg)](https://codecov.io/gh/johannesroesch/alias-manager)
[![Quality Gate Status](https://sonarcloud.io/api/project_badges/measure?project=johannesroesch_alias-manager&metric=alert_status)](https://sonarcloud.io/summary/new_code?id=johannesroesch_alias-manager)
[![Bugs](https://sonarcloud.io/api/project_badges/measure?project=johannesroesch_alias-manager&metric=bugs)](https://sonarcloud.io/summary/new_code?id=johannesroesch_alias-manager)
[![Vulnerabilities](https://sonarcloud.io/api/project_badges/measure?project=johannesroesch_alias-manager&metric=vulnerabilities)](https://sonarcloud.io/summary/new_code?id=johannesroesch_alias-manager)
[![Latest Release](https://img.shields.io/github/v/release/johannesroesch/alias-manager)](https://github.com/johannesroesch/alias-manager/releases/latest)
[![License: GPL v2](https://img.shields.io/badge/license-GPL--2.0%2B-blue)](https://www.gnu.org/licenses/gpl-2.0.html)
[![PHP](https://img.shields.io/badge/PHP-8.1%2B-777bb4)](https://www.php.net)
[![WordPress](https://img.shields.io/badge/WordPress-5.9%2B-21759b)](https://wordpress.org)

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
