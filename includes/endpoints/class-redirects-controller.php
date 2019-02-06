<?php

class RedirectsController {

  public function register_routes() {
    register_rest_route( 'core/v2', '/redirects', array(
      'methods' => WP_REST_Server::READABLE,
      'callback' => [$this, 'get_redirects'],
    ) );
  }

  public function get_redirects( WP_REST_Request $request ){
    if( class_exists( 'WPSEO_Redirect_Manager' ) ){
      $redirect_manager = new WPSEO_Redirect_Manager();
      $redirects        = $redirect_manager->get_all_redirects();
      $formattedRedirects = [];
      foreach($redirects as $redirect){
        $target = $redirect->get_target();
        $targetInfo = pathinfo( $target );
        $origin = $redirect->get_origin();
        $originInfo = pathinfo( $origin );
        $targetIsFile = isset($targetInfo['extension']) ? true : false;
        $originIsFile = isset($originInfo['extension']) ? true : false;
        if(strpos($origin, '/') !== 0){
          $origin = '/' . untrailingslashit($origin);
        }
        if(strpos($target, '/') !== 0){
          $target = '/' . $target;
        }
        if($originIsFile){
          $from = "^$origin";
        } else {
          $from = "^" . str_replace("/", "\/", $origin) . "(\/|\?)?[^\/]*$";
        }
              
        $formattedRedirects[] = [
          'from' => $from,
          'to' => $targetIsFile ? $target : trailingslashit( $target ),
          'statusCode' => $redirect->get_type()
        ];
      }
      return new WP_REST_Response( $formattedRedirects, 200 );
    }
    return new WP_REST_Response( [
      'success' => false,
    ], 200 );
  }
}

add_action('rest_api_init', 'register_redirects_routes');

function register_redirects_routes(){
  $controller = new RedirectsController;
  $controller->register_routes();
}

?>