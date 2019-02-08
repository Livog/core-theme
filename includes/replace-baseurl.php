<?php

$base_url = get_field('base_url', 'option');

add_filter( 'preview_post_link', 'filter_preview_link', 9999); 
add_filter( 'preview_page_link', 'filter_preview_link', 9999); 
add_filter( 'get_sample_permalink', 'filter_preview_link', 9999); 
add_filter( 'get_sample_permalink_html', 'filter_replace_base_url_html', 9999); 
add_action( 'admin_bar_menu', 'customize_wp_admin_bar_preview_links', 9999 );

function filter_preview_link( $permalink ) { 
  $base_url = get_field('base_url', 'option'); // Front-end URL

  if(is_array($permalink)){
    $url_data = wp_parse_url($permalink[0]);
    $permalink[0] = str_replace($url_data['scheme'] . '://' . $url_data['host'], untrailingslashit($base_url), $permalink[0]);
    return $permalink; 
  } else {
    $url_data = wp_parse_url($permalink);
    $permalink = untrailingslashit($base_url) . $url_data['path'] . (isset($url_data['query']) ? '?' . $url_data['query'] : '');
    return $permalink; 
  }
  
}; 

function filter_replace_base_url_html($html){
  global $base_url;
  $html = str_replace(untrailingslashit(site_url()), untrailingslashit($base_url), $html);
  return $html;
}

function customize_wp_admin_bar_preview_links( $wp_admin_bar ){
  global $base_url;
  $view_site = $wp_admin_bar->get_node('view-site');
  $view_site->href = $base_url;

  $view = $wp_admin_bar->get_node('view');
  $url_data = wp_parse_url($view->href);
  $view->href = untrailingslashit($base_url) . $url_data['path'] . (isset($url_data['query']) ? '?' . $url_data['query'] : '');

  $wp_admin_bar->add_node($view_site);
  $wp_admin_bar->add_node($view);
}

function change_base_url_view_link( $actions, $post ) {
  global $base_url;
  $actions['view'] = filter_replace_base_url_html($actions['view']);
  return $actions;
}
add_filter( 'post_row_actions', 'change_base_url_view_link', 9999, 2 );
add_filter( 'page_row_actions', 'change_base_url_view_link', 9999, 2 );

?>