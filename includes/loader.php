<?php

// Exit if accessed directly
if ( ! defined( 'ABSPATH' ) ) {
    exit;
}

include_once THEME_PATH . '/includes/acf.php';
include_once THEME_PATH . '/includes/endpoints/class-menu-controller.php';
include_once THEME_PATH . '/includes/endpoints/class-page-controller.php';
include_once THEME_PATH . '/includes/endpoints/class-redirects-controller.php';
include_once THEME_PATH . '/includes/endpoints/class-seo-controller.php';
include_once THEME_PATH . '/includes/endpoints/class-sitemap-controller.php';

add_action( 'rest_api_init', function() {
    
  remove_filter( 'rest_pre_serve_request', 'rest_send_cors_headers' );
  add_filter( 'rest_pre_serve_request', function( $value ) {
    
    header( 'Access-Control-Allow-Origin: *' );
    header( 'Access-Control-Allow-Methods: POST, GET, OPTIONS, PUT, DELETE' );
    header( 'Access-Control-Allow-Credentials: true' );

    return $value;

  });
}, 15 );

/**
 * Modify url base from wp-json to 'api'
 */
add_filter( 'rest_url_prefix', 'core_api_slug');
 
function core_api_slug( $slug ) {
    return 'api';
}

add_action( 'init', 'register_my_menu' );

function register_my_menu() {
    register_nav_menu( 'primary_menu', __( 'Primary Menu' ) );
}

add_theme_support( 'title-tag' );

?>