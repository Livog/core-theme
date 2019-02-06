<?php
class PageController {
  
  protected $post_type;
    
  public function register_routes()
  {
    register_rest_route( 'core/v2', 'post', array(
      'methods' => WP_REST_Server::READABLE,
      'callback' => [$this, 'get_sections'],
    ));
    register_rest_route( 'core/v2', 'post/(?P<id>.*)', array(
      'methods' => WP_REST_Server::READABLE,
      'callback' => [$this, 'get_sections'],
    ));
    register_rest_route( 'core/v2', 'page', array(
      'methods' => WP_REST_Server::READABLE,
      'callback' => [$this, 'get_sections'],
    ));
    register_rest_route( 'core/v2', 'page/(?P<id>.*)', array(
      'methods' => WP_REST_Server::READABLE,
      'callback' => [$this, 'get_sections'],
    ));
    
  }

  public function get_sections(WP_REST_Request $request)
  {
    global $service_restApiCache;
    $maybeID = $request->get_param( 'id' );
    $lang = $request->get_param( 'lang' );
    if(empty($maybeID)){
      $id = get_option( 'page_on_front' );
    } else {
      $id = $this->getPageByRoute( $maybeID, $lang );
    }

    $post = get_post($id);
    $last_modified = get_gmt_from_date(get_the_modified_date('D, d M Y H:i:s', $id), 'D, d M Y H:i:s') . ' GMT';
    $startTime = microtime(true);

    if( ! class_exists('acf') )
      return new WP_Error( 'acf_not_activated', __( 'ACF is not activated.' ), ['status' => 501] );

    $sections = get_field('flexible_sections', $id);
    
    if( class_exists('WPSEO_Frontend') )
      $post_seo = get_post_seo($id);
    
    $data = [
      'ID' => $id,
      'path' => str_replace(site_url(), '', get_the_permalink( $id )),
      'sections' => $sections,
      'seo' => $post_seo,
      'post_type' => $post->post_type
    ];
    $endTime = microtime(true);
    $data['took'] = $endTime - $startTime;
    $response = new WP_REST_Response( $data );
    $response->header( 'Last-Modified', $last_modified );
    $response->header( 'Access-Control-Allow-Origin', '*');
    $response->header( 'Cache-Control', 'public, max-age=600, s-maxage=600');
    $response->set_status( 200 );
    return $response;
    die;
  }

  public function getPageByRoute( $maybeID, $lang ) {
    if (class_exists('SitePress')) {
      global $sitepress;
      $default_lang = $sitepress->get_default_language();
      if(empty($lang))
        $lang = $default_lang;

      $active_languages = apply_filters( 'wpml_active_languages', NULL, 'orderby=id&order=desc' );
      $active_languages_locales = array_keys($active_languages);
      if(in_array($maybeID, $active_languages_locales))
        $maybeID = get_option( 'page_on_front' );

      $id = absint( $maybeID );

      if( $id === 0 ){
        $maybeID = sanitize_text_field( $maybeID );
        foreach($active_languages_locales as $locale){
          if(startsWith($maybeID, $locale . '/')){
            $maybeID = '/' . substr($maybeID, strlen($locale . '/'));
          }
        }

        $page = get_page_by_path($maybeID, ARRAY_A);

        if(empty($page)){
          $page = get_page_by_path($maybeID, ARRAY_A, 'post');
          if(!empty($page)){
            $isPost = true;
          }
        }

        if(empty($page)){
          return new WP_Error( 'not_found', __( 'Could not find page.' ), [ 'status' => 404] );
        }

        $id = $page['ID'];

      }

      if($lang != $default_lang){
        if($isPost){
          $id = apply_filters( 'wpml_object_id', $id, 'post', true, $lang);
        } else {
          $id = apply_filters( 'wpml_object_id', $id, 'page', true, $lang);
        }
      }

    } else {
      $id = absint( $maybeID );
      if( $id === 0 ){
        $maybeID = sanitize_text_field( $maybeID );

        $page = get_page_by_path($maybeID, ARRAY_A);

        if(empty($page)){
          $page = get_page_by_path($maybeID, ARRAY_A, 'post');
          if(!empty($page)){
            $isPost = true;
          }
        }

        if(empty($page)){
          return new WP_Error( 'not_found', __( 'Could not find page.' ), [ 'status' => 404] );
        }

        $id = $page['ID'];

      }
    }

    return $id;
  }
    
}
add_action('rest_api_init', 'register_core_post_routes'); 
function register_core_post_routes(){
    $controller = new PageController;
    $controller->register_routes();
}