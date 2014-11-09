<?php 
/**
 * Apocrypha Theme AJAX Library
 * Andrew Clayton
 * Version 2.0
 * 5-6-2014
 */
 
// Exit if accessed directly
if ( !defined( 'ABSPATH' ) ) exit;
 
/*---------------------------------------------
	1.0 - LOGIN
----------------------------------------------*/
add_action( 'wp_ajax_nopriv_apoc_login', 'apoc_login' );
add_action( 'wp_ajax_apoc_login', 'apoc_login' );

/**
 * Login Handler
 * @version 2.0
 */
function apoc_login() {

	// Check security token
	check_ajax_referer( 'top-login-nonce' , 'security' );
	
	// Get the credentials
	$credentials = array();
	$credentials['user_login'] 		= $_POST['username'];
	$credentials['user_password'] 	= $_POST['password'];
	$credentials['remember']		= $_POST['remember'];
	
	// Attempt to log in
	$login = wp_signon( $credentials , false );
	 if ( is_wp_error($login) ) {
		$results = array(
			'loggedin' 	=> false,
			'message' 	=> $login->get_error_message(),
		);
	} else {
		$results = array(
			'loggedin' 	=> true, 
			'message'	=> 'Login successful, redirecting!',
		);
	}
	
	// Return results
	echo json_encode( $results );
	die();
}

/*---------------------------------------------
	2.0 - NOTIFICATIONS
----------------------------------------------*/

/**
 * Remove frontend BuddyPress notifications with AJAX
 * @version 2.0
 */
add_action( 'apoc_ajax_apoc_clear_notification' , 'apoc_clear_notification' );
function apoc_clear_notification() {
	
	// Get required global objects
	global $bp;
	global $wpdb;

	// Get data
	$user_id 	= get_current_user_id();
	$id 		= $_POST['id'];
	$type		= $_POST['type'];
	
	// Clear all mentions at once
	if ( $type == "new_at_mention" ) :
		$wpdb->query( $wpdb->prepare( "DELETE FROM " . $bp->core->table_name_notifications . " WHERE user_id = %d AND component_action = %s", $user_id , $type ) );	
	
	// Delete all reply notifications for a single topic
	elseif ( $type == "bbp_new_reply" ) :
		$wpdb->query( $wpdb->prepare( "DELETE FROM " . $bp->core->table_name_notifications . " WHERE user_id = %d AND item_id = %d", $user_id , $id ) );	
	
	// Otherwise, delete the single notification	
	else :
		$wpdb->query( $wpdb->prepare( "DELETE FROM " . $bp->core->table_name_notifications . " WHERE user_id = %d AND id = %d", $user_id , $id ) );
	endif;
	
	// Send a response
	die("Notifications Cleared!");
}

