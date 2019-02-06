<?php

if( class_exists('WPSEO_Frontend') ){
  function get_post_seo($post_id = null){
    $wpseo_frontend = WPSEO_Frontend::get_instance();
    $wpseo_frontend->reset();

    $post = get_post($post_id);
    $yoast_meta = [];

    $seo_title = $wpseo_frontend->get_content_title( $post );
    $seo_description = get_post_meta( $post->ID, '_yoast_wpseo_metadesc', true );
    $seo_canonical = get_post_meta( $post->ID, '_yoast_wpseo_canonical', true );

    $site_url = site_url();
    $base_url = get_field('base_url', 'option');
    if(!empty($base_url)){
      $site_url = $base_url;
    }
    $sitename = get_bloginfo('name');

    $og_title = get_post_meta( $post_id, '_yoast_wpseo_opengraph-title', true );
    $og_desc = get_post_meta( $post_id, '_yoast_wpseo_opengraph-description', true );
    $og_image = get_post_meta( $post_id, '_yoast_wpseo_opengraph-image', true );

    if(empty($og_title)){
      $og_title = $seo_title;
    }
    if(empty($og_desc)){
      $og_desc = $seo_description;
    }

    $twitter_title = get_post_meta( $post_id, '_yoast_wpseo_twitter-title', true );
    $twitter_desc = get_post_meta( $post_id, '_yoast_wpseo_twitter-description', true );
    $twitter_image = get_post_meta( $post_id, '_yoast_wpseo_twitter-image', true );

    if(empty($twitter_title)){
      $twitter_title = $og_title;
    }
    if(empty($twitter_desc)){
      $twitter_desc = $og_desc;
    }
    if(empty($twitter_image)){
      $twitter_image = $og_image;
    }

    $data = [
      'title' => $seo_title,
      'meta' => [
        [
          'name' => 'description',
          'content' => $seo_description,
          'hid' => 'description',
        ],
        //Facebook
        [
          'property' => 'og:title',
          'content' => $og_title,
          'hid' => 'og:title',
        ],
        [
          'property' => 'og:description',
          'content' => $og_desc,
          'hid' => 'og:description',
        ],
        [
          'property' => 'og:image',
          'content' => $og_image,
          'hid' => 'og:image',
        ],
        [
          'property' => 'og:url',
          'content' => $site_url,
          'hid' => 'og:url',
        ],
        [
          'property' => 'og:site_name',
          'content' => $sitename,
          'hid' => 'og:site_name',
        ],
        //Twitter
        [
          'name' => 'twitter:title',
          'content' => $twitter_title,
          'hid' => 'twitter:title',
        ],
        [
          'name' => 'twitter:description',
          'content' => $twitter_desc,
          'hid' => 'twitter:description',
        ],
        [
          'name' => 'twitter:image',
          'content' => $twitter_image,
          'hid' => 'twitter:image',
        ],
      ]
    ];
    return $data;
  }
}
?>