<?php
/**
 * Plugin Name: WP Alias
 * Description: Verwalte Aliase für WordPress-Seiten – jeder Alias leitet per 301-Redirect auf eine hinterlegte Zielseite weiter.
 * Version:     1.0.0
 * Author:      Johannes Rösch
 * Text Domain: wp-alias
 * License:     GPL-2.0+
 */

if ( ! defined( 'ABSPATH' ) ) {
    exit;
}

define( 'WP_ALIAS_VERSION', '1.0.0' );
define( 'WP_ALIAS_DIR', plugin_dir_path( __FILE__ ) );

require_once WP_ALIAS_DIR . 'includes/class-alias-db.php';
require_once WP_ALIAS_DIR . 'includes/class-alias-redirector.php';
require_once WP_ALIAS_DIR . 'admin/class-alias-admin.php';

register_activation_hook( __FILE__, array( 'WP_Alias_DB', 'create_table' ) );

add_action( 'init', array( 'WP_Alias_Redirector', 'maybe_redirect' ) );

if ( is_admin() ) {
    add_action( 'plugins_loaded', array( 'WP_Alias_Admin', 'init' ) );
}
