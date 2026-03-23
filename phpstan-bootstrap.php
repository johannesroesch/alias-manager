<?php
/**
 * PHPStan bootstrap — stubs WordPress constants so the analyser
 * can process plugin files without a full WordPress installation.
 *
 * @package   Alias_Manager
 * @copyright 2025 Johannes Rösch
 * @license   GPL-2.0+
 */
declare(strict_types=1);

if ( ! defined( 'ABSPATH' ) ) {
    define( 'ABSPATH', __DIR__ . '/' );
}
