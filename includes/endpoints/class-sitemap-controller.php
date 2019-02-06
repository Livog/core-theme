<?php

add_action( 'rest_api_init', function () {
  register_rest_route( 'core/v2', '/sitemap', array(
    'methods' => 'GET',
    'callback' => 'generate_sitemap_links',
  ) );
} );

function generate_sitemap_links($request){
    
    $routes = array();
    
    $frontpage_id = get_option( 'page_on_front' );
    if (class_exists('SitePress')) {
      global $sitepress;
      $lang = $request->get_param('lang');
      $default_lang = $sitepress->get_default_language();
      if(empty($lang)){
        $lang = $default_lang;
      } else {
        $langIsset = true;
      }
      $active_languages = apply_filters( 'wpml_active_languages', NULL, 'orderby=id&order=desc' );
      $active_languages_locales = array_keys($active_languages);
    }
    $query = new WP_Query( array(  
      'post_type' => 'any',  
      'post_status'    => 'publish',  
      'posts_per_page' => -1, ) ); 
    if ( $query->have_posts() ) {  
      while ( $query->have_posts() ) {
        $query->the_post();   
        $permalink = get_permalink();
        $path = wp_make_link_relative($permalink);
        
        $links = [];
        if (class_exists('SitePress')) {
          foreach($active_languages_locales as $locale){
            $locale_id = icl_object_id( get_the_ID() , get_post_type(), true, $locale );
            $wpml_path = wp_make_link_relative( get_the_permalink( $locale_id ) );
            if(get_the_ID() == $frontpage_id ){
              $wpml_path = '/' . $locale . '/';
            } else {
              $wpml_path = '/' . $locale . '/' . $wpml_path;
            }
            $wpml_path = str_replace('//', '/', $wpml_path);
            $links[] = [
              'lang' => $locale,
              'url' => trailingslashit($wpml_path),
              'id' => $locale_id
            ];
            if($locale == $default_lang){
              $links[] = [
                'lang' => 'x-default',
                'url' => trailingslashit($wpml_path),
                'id' => $locale_id
              ];
            }
          }
          foreach($links as $link){
            if($langIsset == true && $link['lang'] !== $lang)
              continue;
            if($link['lang'] == 'x-default')
              continue;
            $routes[] = array( 
              'url' => $link['url'],
              'changefreq' => 'weekly',
              'lastmodISO' => date('c', strtotime( get_the_modified_date( 'Y-m-d H:i:s', $link['id'] ) ) ),
              'links' => $links,
            );
          }
        } else {
          $routes[] = array( 
            'url' => $path,
            'changefreq' => 'weekly',
            'lastmodISO' => date('c', strtotime( get_the_modified_date( 'Y-m-d H:i:s', $link['id'] ) ) ),
          );
        }
      }
    } 

  wp_reset_postdata(); 

  $response = new WP_REST_Response( $routes );
  $response->header('Cache-Control', 'no-cache');
  $response->set_status( 200 );
  return $response;
}

?>