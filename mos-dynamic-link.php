<?php declare(strict_types=1);

/**
 * Plugin Name: MOS Dynamic Link
 * Description: Generate a dynamic link based on URL parameters. [mos_dynamic_link link_template='' link_text='CLICK HERE' wrap_html=true new_tab=true encode=true]. use %%var_name%% in link template and var_name=something in url.
 */

namespace MOS\Dynamic_Link;

defined( 'ABSPATH' ) or die;

// Plugin constants
define( __NAMESPACE__ . '\NS', __NAMESPACE__ . '\\' );
define( NS . 'PLUGIN_NAME', 'mos-dynamic-link' );
define( NS . 'PLUGIN_VERSION', '1.0.0' );
define( NS . 'PLUGIN_DIR', plugin_dir_path( __FILE__ ) );
define( NS . 'PLUGIN_URL', plugin_dir_url( __FILE__ ) );
define( NS . 'PLUGIN_BASENAME', plugin_basename( __FILE__ ) );

\add_shortcode( 'mos_dynamic_link', NS . 'shortcode_dynamic_link' );
function shortcode_dynamic_link( $passed_atts ) {
  // Extract shortcode attributes
  $default_atts = [
    'link_template' => '',
    'link_text' => 'CLICK HERE',
    'wrap_html' => 'true',
    'encode' => 'true',
    'new_tab' => 'true',
    'class' => '',
  ];
  $atts = array_replace( $default_atts, $passed_atts );

  // Return nothing if link_template not provided
  if ( empty( $atts['link_template'] ) ) {
    return '';
  }

  // Convert true/false from string to bool
  $encode = $atts['encode'] == 'false' ? false : true;
  $wrap_html = $atts['wrap_html'] == 'false' ? false : true;
  $new_tab = $atts['new_tab'] == 'false' ? false : true;

  // Get merge tags from link template
  preg_match_all( '/%%[a-zA-Z0-9_]+%%/', $atts['link_template'], $matches );
  $matches = $matches[0]; // Account for weird preg_match return
  $param_names = array_map( function( $element ) {
    return trim( $element, '%' );
  }, $matches );

  // Apply merge tags
  $url = $atts['link_template'];
  foreach ( $param_names as $param_name ) {
    if ( isset( $_GET[$param_name] ) ) {
      $find = "%%$param_name%%";
      $replace = $_GET[$param_name];
      $replace = $encode ? urlencode( $replace ) : $replace;
      $url = str_replace( $find, $replace, $url );
    }
  }

  // #HACK: if http is not provided in url, use https
  if ( strpos( $url, 'http' ) !== 0 ) {
    $url = "https://" . $url;
  }

  // If wrap_html set to false, return the url as plain text
  if ( !$wrap_html ) {
    return $url;
  }

  // Wrap the link in an <a> element
  $target_attribute = $new_tab ? "target='_blank'" : '';
  $class_attribute = $atts['class'] ? "class='$atts[class]'" : '';
  $link = "<a $class_attribute $target_attribute href='$url'>$atts[link_text]</a>";

  return $link;
}