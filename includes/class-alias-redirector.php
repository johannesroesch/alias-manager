<?php
if ( ! defined( 'ABSPATH' ) ) {
    exit;
}

class WP_Alias_Redirector {

    public static function maybe_redirect() {
        if ( is_admin() || wp_doing_ajax() || wp_doing_cron() ) {
            return;
        }

        $request_uri  = $_SERVER['REQUEST_URI'] ?? '';
        $request_path = trim( parse_url( $request_uri, PHP_URL_PATH ), '/' );

        // WordPress in Unterverzeichnis: Basis-Pfad entfernen
        $home_path = trim( parse_url( home_url(), PHP_URL_PATH ), '/' );
        if ( $home_path && str_starts_with( $request_path, $home_path ) ) {
            $request_path = trim( substr( $request_path, strlen( $home_path ) ), '/' );
        }

        if ( $request_path === '' ) {
            return;
        }

        $target = WP_Alias_DB::find_by_alias( $request_path );

        if ( $target ) {
            wp_redirect( $target, 301 );
            exit;
        }
    }
}
