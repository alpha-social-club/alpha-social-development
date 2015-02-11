<?php
/**
 * Plugin Name: BuddyBoss Invitation
 * Plugin URI:  http://alphasss.com/
 * Description: BuddyBoss Invitation
 * Author:      AlphaSSS
 * Author URI:  http://alphasss.com
 * Version:     0.0.1
 */
 
 // Exit if accessed directly
if ( ! defined( 'ABSPATH' ) ) exit;

// Directory
if ( ! defined( 'BUDDYBOSS_INVITATION_PLUGIN_DIR' ) ) {
  define( 'BUDDYBOSS_INVITATION_PLUGIN_DIR', trailingslashit( plugin_dir_path( __FILE__ ) ) );
}

// Url
if ( ! defined( 'BUDDYBOSS_INVITATION_PLUGIN_URL' ) ) {
  $plugin_url = plugin_dir_url( __FILE__ );

  // If we're using https, update the protocol. Workaround for WP13941, WP15928, WP19037.
  if ( is_ssl() )
    $plugin_url = str_replace( 'http://', 'https://', $plugin_url );

  define( 'BUDDYBOSS_INVITATION_PLUGIN_URL', $plugin_url );
}

// File
if ( ! defined( 'BUDDYBOSS_INVITATION_PLUGIN_FILE' ) ) {
  define( 'BUDDYBOSS_INVITATION_PLUGIN_FILE', __FILE__ );
}

add_action( 'plugins_loaded', function(){

	try {

		$main_include = BUDDYBOSS_INVITATION_PLUGIN_DIR  . 'includes/main-class.php';

		if ( ! file_exists( $main_include ) ) {
			$msg = sprintf( __( "Couldn't load main class at:<br/>%s", 'buddyboss-invitation' ), $main_include );
			throw new Exception( $msg, 404 );
		}

		require( $main_include );

		// Declare global access scope to the to BuddyBoss_Invitation_Plugin instance
		global $buddyboss_invitation;
		$buddyboss_invitation = BuddyBoss_Invitation_Plugin::instance();

	} catch (Exception $e) {

		$msg = sprintf( __( "<h1>Fatal error:</h1><hr/><pre>%s</pre>", 'buddyboss-invitation' ), $e->getMessage() );
    	echo $msg;
	}

} );

/**
 * Must be called after hook 'plugins_loaded'
 * @return BuddyBoss_Invitation_Plugin
 */
function buddyboss_invitation()
{
  global $buddyboss_invitation;

  return $buddyboss_invitation;
}

?>
