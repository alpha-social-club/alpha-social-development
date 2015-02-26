<?php
/**
 * Plugin Name: BuddyBoss Members
 * Plugin URI:  http://alphasss.com/
 * Description: BuddyBoss Members
 * Author:      AlphaSSS
 * Author URI:  http://alphasss.com
 * Version:     0.0.1
 */

 // Exit if accessed directly
if ( ! defined( 'ABSPATH' ) ) exit;

// Url
if ( ! defined( 'BUDDYBOSS_MEMBERS_PLUGIN_URL' ) ) {
  $plugin_url = plugin_dir_url( __FILE__ );

  // If we're using https, update the protocol. Workaround for WP13941, WP15928, WP19037.
  if ( is_ssl() )
    $plugin_url = str_replace( 'http://', 'https://', $plugin_url );

  define( 'BUDDYBOSS_MEMBERS_PLUGIN_URL', $plugin_url );
}

add_action( 'plugins_loaded', function(){

	add_action('wp_logout', function(){
		$user = (array)wp_get_current_user()->data;

		$uuid = md5($user['display_name']);
	});


	wp_enqueue_script( 'pubnub', 'http://cdn.pubnub.com/pubnub.min.js',array('jquery') );

	if ( ! is_user_logged_in() ) {
		wp_enqueue_script( 'buddyboss-members', BUDDYBOSS_MEMBERS_PLUGIN_URL . '/assets/js/non-member.js' );

		add_action( 'bp_directory_members_actions', function(){
			echo bp_get_button( array(
				'id'                => 'request_invitation',
				'component'         => 'members',
				'must_be_logged_in' => false,
				'block_self'        => false,
				'link_href'         => wp_nonce_url( bp_get_group_permalink( $group ) . 'request-invitation', 'groups_request_membership' ),
				'link_text'         => __( 'Request Invitation', 'buddypress' ),
				'link_title'        => __( 'Request Invitation', 'buddypress' ),
				'link_class'        => 'group-button request-invitation',
			) );
		} );
	} else {
		wp_enqueue_script( 'buddyboss-members', BUDDYBOSS_MEMBERS_PLUGIN_URL . 'assets/js/member.js',array('jquery') );

		/**
		 * This action returns uuid
		 */
		add_action( 'wp_ajax_get_uuid', function(){
			header('Content-Type: application/json');

			$user = (array)wp_get_current_user()->data;

			// Prepare data
			$data = array(
				'data' => array(
					'user' => array(
						'username' => md5($user['display_name'])
					)
				)
			);
			//--

			echo json_encode($data);

			wp_die();
		});
	}
});