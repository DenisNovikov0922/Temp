<?php

add_filter('themes_api', 'woofood_theme_update_info', 20, 3);
/*
 * $res empty at this step
 * $action 'plugin_information'
 * $args stdClass Object ( [slug] => woocommerce [is_ssl] => [fields] => Array ( [banners] => 1 [reviews] => 1 [downloaded] => [active_installs] => 1 ) [per_page] => 24 [locale] => en_US )
 */
function woofood_theme_update_info( $res, $action, $args ){
 $woofood_options = get_option( 'woofood_options' );
 $woofood_license_number = $woofood_options["woofood_license_number"];
  // do nothing if this is not about getting plugin information
  if( 'theme_information' !== $action ) {
    return false;
  }
 
  $plugin_slug = 'woofood'; // we are going to use it in many places in this function
 
  // do nothing if it is not our plugin
  if( $plugin_slug !== $args->slug ) {
    return false;
  }
 
  // trying to get from cache first
  if( false == $remote = get_transient( 'wpslash_update_' . $plugin_slug ) ) {
 
    // info.json is the file with the actual plugin information on your server
    $remote = wp_remote_get( 'https://update.wpslash.com/?purchase_code='.$woofood_license_number."&domain=".home_url( '/')."&slug=".$plugin_slug, array(
      'timeout' => 2,
      'headers' => array(
        'Accept' => 'application/json'
      ) )
    );
 
    if ( ! is_wp_error( $remote ) && isset( $remote['response']['code'] ) && $remote['response']['code'] == 200 && ! empty( $remote['body'] ) ) {
      //set_transient( 'wpslash_update_' . $plugin_slug, $remote, 43200 ); // 12 hours cache
            set_transient( 'wpslash_update_' . $plugin_slug, $remote, 43200 ); // 12 hours cache

    }
 
  }
 
  if( ! is_wp_error( $remote ) && isset( $remote['response']['code'] ) && $remote['response']['code'] == 200 && ! empty( $remote['body'] ) ) {
 
    $remote = json_decode( $remote['body'] );
    $res = new stdClass();
 
    $res->name = "WooFood for WooCommerce";
    $res->slug = $plugin_slug;
    $res->version = $remote->version;
    $res->tested = $remote->tested;
    $res->requires = $remote->requires;
    $res->author = '<a href="https://www.wpslash.com">WPSlash</a>';
    $res->author_profile = 'https://profiles.wordpress.org/wpslash';
    $res->download_link = $remote->download_url;
    $res->trunk = $remote->download_url;
    $res->changelog_url = $remote->changelog_url;
    $res->requires_php = '5.3';
    $res->last_updated = $remote->last_updated;
    $res->sections = array(
      'description' => $remote->sections->description,
      'installation' => $remote->sections->installation,
      'changelog' => $remote->sections->changelog
      // you can add your custom sections (tabs) here
    );
 
    // in case you want the screenshots tab, use the following HTML format for its content:
    // <ol><li><a href="IMG_URL" target="_blank"><img src="IMG_URL" alt="CAPTION" /></a><p>CAPTION</p></li></ol>
    if( !empty( $remote->sections->screenshots ) ) {
      $res->sections['screenshots'] = $remote->sections->screenshots;
    }
 
    return $res;
 
  }
 
  return false;
 
}





add_filter('site_transient_update_themes', 'wpslash_woofood_push_theme_update' );
 
function wpslash_woofood_push_theme_update( $transient ){

  $woofood_options = get_option( 'woofood_options' );
 $woofood_license_number = $woofood_options["woofood_license_number"];
   $plugin_slug = 'woofood'; // we are going to use it in many places in this function

  if ( empty($transient->checked ) ) {
            return $transient;
        }
 
  // trying to get from cache first, to disable cache comment 10,20,21,22,24
    if( false == $remote = get_transient( 'wpslash_update_woofood' ) ) {
 
    // info.json is the file with the actual plugin information on your server
    $remote = wp_remote_get( 'https://update.wpslash.com/?purchase_code='.$woofood_license_number."&domain=".home_url( '/')."&slug=".$plugin_slug, array(
      'timeout' => 10,
      'headers' => array(
        'Accept' => 'application/json'
      ) )
    );
 
    if ( !is_wp_error( $remote ) && isset( $remote['response']['code'] ) && $remote['response']['code'] == 200 && !empty( $remote['body'] ) ) {
      
      set_transient( 'wpslash_update_woofood', $remote, 43200 ); // 12 hours cache


    }
 
  }
 
  if( $remote && !is_wp_error( $remote )   ) {
 
    $remote = json_decode( $remote['body'] );
 
    // your installed plugin version should be on the line below! You can obtain it dynamically of course 
    if( $remote && version_compare( WOOFOOD_THEME_VERSION, $remote->version, '<' ) && version_compare($remote->requires, get_bloginfo('version'), '<' ) ) {
      $res = array();

      $res["theme"] = 'woofood';
     // $res->plugin = 'woofood-plugin/woofood-connection.php'; // it could be just YOUR_PLUGIN_SLUG.php if your plugin doesn't have its own directory
      $res["new_version"] = $remote->version;
      $res["tested"] = $remote->tested;
      $res["package"] = $remote->download_url;
      $res["url"] = $remote->changelog_url;

      $transient->response[$res["theme"]] = $res;
            }
 
  }
/*  echo "<pre>";
  print_r($transient);
    echo "</pre>";*/

        return $transient;
}
?>