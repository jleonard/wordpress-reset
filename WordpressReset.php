<?php

/* 
Plugin Name: Frog Wordpress Reset
Plugin URI: https://github.com/jleonard/wordpress-reset
Description: Boilerplate for Wordpress Reset.
Author: John Leonard
Version: 1.0 
Author URI: https://github.com/jleonard/wordpress-reset
*/

/*
 * html editor by default
 */
add_filter('wp_default_editor', create_function('', 'return "html";'));

add_post_type_support( "page", "excerpt" );

/*
* remove color picker from user screen
*/
remove_action("admin_color_scheme_picker", "admin_color_scheme_picker");

/*
* Hide superfluous contact methods from user create/edit screen
*/
add_filter('user_contactmethods','hide_user_fields',10,1);
function hide_user_fields( $contactmethods ) {
  unset($contactmethods['aim']);
  unset($contactmethods['jabber']);
  unset($contactmethods['yim']);
  return $contactmethods;
}


function WPR_admin_scripts_method(){

  /*
  *
  * include a .js file from the plugin's directory.
  * 
  wp_register_script('NAMESPACE_js',plugins_url("NAMESPACE.js",__FILE__ ));
  wp_enqueue_script("NAMESPACE_js");
  */
  
  wp_register_style( 'WPR_css', plugins_url("WordpressReset.css",__FILE__ ), false, '1.0.0' );
  wp_enqueue_style( 'WPR_css' );
  
}
add_action('admin_enqueue_scripts', 'WPR_admin_scripts_method');


function WPR_remove_menus () {
  global $menu;
  //$restricted = array(__('Dashboard'), __('Posts'), __('Media'), __('Links'), __('Pages'), __('Appearance'), __('Tools'), __('Users'), __('Settings'), __('Comments'), __('Plugins'));
  $restricted = array( __('Appearance'), __('Comments'),__("Dashboard") );
  end ($menu);
  while (prev($menu)){
    $value = explode(' ',$menu[key($menu)][0]);
    if(in_array($value[0] != NULL?$value[0]:"" , $restricted)){unset($menu[key($menu)]);}
  }
}
add_action('admin_menu', 'WPR_remove_menus');

function WPR_remove_metaboxes(){

  $all_post_types = get_post_types('','objects');
  
  foreach($all_post_types as $post_type){
    remove_meta_box( 'pageparentdiv', $post_type->name,"normal");
    remove_meta_box( 'formatdiv', $post_type->name,"normal");
    remove_meta_box( 'commentsdiv', $post_type->name,"normal");
    remove_meta_box( 'commentstatusdiv', $post_type->name,"normal");
  }
  
  register_taxonomy_for_object_type('post_tag', 'page');
  add_meta_box( 'tagsdiv-post_tag', __('Page Tags'), 'post_tags_meta_box', 
      'page', 'side', 'low');

  register_taxonomy_for_object_type('category', 'page');
  add_meta_box( 'categorydiv', __('Categories'), 'post_categories_meta_box', 
        'page', 'side', 'core');
}
add_action("admin_init","WPR_remove_metaboxes");

?>