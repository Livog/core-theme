<?php
class MenuController {

  public function register_routes()
  {
    register_rest_route( 'core/v2', '/menus', array(
      'methods' => WP_REST_Server::READABLE,
      'callback' => [$this, 'get_menus'],
    ));
    register_rest_route( 'core/v2', '/menus/(?P<location>.*)', array(
      'methods' => WP_REST_Server::READABLE,
      'callback' => [$this, 'get_menu'],
    ));
  }

  public function get_menus($request)
  {

    $locations = get_nav_menu_locations();

    $response = new WP_REST_Response( $locations );
    $response->header( 'Last-Modified', $last_modified );
    $response->header( 'Access-Control-Allow-Origin', '*');
    $response->header( 'Cache-Control', 'public, max-age=600, s-maxage=600');
    $response->set_status( 200 );
    return $response;
    die;
  }

  public function get_menu($request)
  {

    $menu_location = $request->get_param('location');
    $menu_items = [];
    $menu = wp_get_nav_menu_object($menu_location);
    if (class_exists('SitePress') && $menu) {
      $menu_id = $menu->term_id;
      $menu_id = apply_filters('wpml_object_id', $menu_id, 'nav_menu', true, $lang);
    }
    if($menu)
      $menu_items = $this->get_menu_items($menu);

    $response = new WP_REST_Response( $menu_items );
    $response->header( 'Access-Control-Allow-Origin', '*');
    $response->header( 'Cache-Control', 'public, max-age=600, s-maxage=600');
    $response->set_status( 200 );
    return $response;
    die;
  }

  public function get_menu_items($menu)
  {
    $menuItems = wp_get_nav_menu_items($menu);
    $menuitemsFormatted = [];
    $base_url = untrailingslashit(get_field('base_url', 'option'));
    $home_url = get_home_url();
    if($menuItems){
      foreach($menuItems as $menuItem){
        if($menuItem->menu_item_parent){
          $parentIx = array_search($menuItem->menu_item_parent, array_column($menuitemsFormatted, 'ID'));
          $menuitemsFormatted[$parentIx]['subMenuItems'][] = [
            'ID' => $menuItem->object_id,
            'title' => $menuItem->title,
            'slug' => get_post_field('post_name', $menuItem->object_id),
            'permalink' => wp_make_link_relative($menuItem->url),
            'url' => str_replace($home_url, $base_url, $menuItem->url),
            'type' => $menuItem->object
          ];
        } else{
          $menuitemsFormatted[] = [
            'ID' => $menuItem->object_id,
            'title' => $menuItem->title,
            'slug' => get_post_field('post_name', $menuItem->object_id),
            'permalink' => wp_make_link_relative($menuItem->url),
            'url' => str_replace($home_url, $base_url, $menuItem->url),
            'type' => $menuItem->object
          ];
        }
      }
    }
    return $menuitemsFormatted;
    die;
  }
    
}
add_action('rest_api_init', 'register_core_menu_routes'); 
function register_core_menu_routes(){
    $controller = new MenuController;
    $controller->register_routes();
}