<?php

// Exit if accessed directly
if ( ! defined( 'ABSPATH' ) ) {
    exit;
}

if( function_exists('acf_add_options_page') ) {
  acf_add_options_page([
    'page_title' => 'Core Options',
  ]);
}

?>