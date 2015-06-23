<?php
/**
 * Plugin Name: AlphaSSS GF Finances
 * Plugin URI:  http://alphasss.com/
 * Description: Alphasss GF Finances
 * Author:      AlphaSSS
 * Author URI:  http://alphasss.com
 * Version:     0.0.1
 * Text Domain: alphasss-gf-finances
 */

// Exit if accessed directly
if ( ! defined( 'ABSPATH' ) ) exit;

// Directory
if ( ! defined( 'ALPHASSS_GF_FINANCES_PLUGIN_DIR' ) ) {
	define( 'ALPHASSS_GF_FINANCES_PLUGIN_DIR', trailingslashit( plugin_dir_path( __FILE__ ) ) );
}

// Url
if ( ! defined( 'ALPHASSS_GF_FINANCES_PLUGIN_URL' ) ) {
  $plugin_url = plugin_dir_url( __FILE__ );

  // If we're using https, update the protocol. Workaround for WP13941, WP15928, WP19037.
  if ( is_ssl() )
    $plugin_url = str_replace( 'http://', 'https://', $plugin_url );

  define( 'ALPHASSS_GF_FINANCES_PLUGIN_URL', $plugin_url );
}

// File
if ( ! defined( 'ALPHASSS_GF_FINANCES_PLUGIN_FILE' ) ) {
  define( 'ALPHASSS_GF_FINANCES_PLUGIN_FILE', __FILE__ );
}

add_action( 'plugins_loaded', function(){

	try {

		$main_include = ALPHASSS_GF_FINANCES_PLUGIN_DIR  . 'includes/main-class.php';

		if ( ! file_exists( $main_include ) ) {
			$msg = sprintf( __( "Couldn't load main class at:<br/>%s", 'alphasss' ), $main_include );
			throw new Exception( $msg, 404 );
		}

		require( $main_include );

		// Declare global access scope to the to Alphasss_Gf_Finances_Plugin instance
		global $alphasss_gf_finances;
		$alphasss_gf_finances = Alphasss_Gf_Finances_Plugin::instance();

	} catch (Exception $e) {

		$msg = sprintf( __( "<h1>Fatal error:</h1><hr/><pre>%s</pre>", 'alphasss' ), $e->getMessage() );
    	echo $msg;
	}

});

/**
 * Must be called after hook 'plugins_loaded'
 * @return Alphasss_Gf_Finances_Plugin
 */
function alphasss_gf_finances()
{
  global $alphasss_gf_finances;

  return $alphasss_gf_finances;
}

?>
