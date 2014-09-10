<?php 
/**
 * Apocrypha Theme AJAX Library
 * Andrew Clayton
 * Version 2.0
 * 5-6-2014
 */
 
// Exit if accessed directly
if ( !defined( 'ABSPATH' ) ) exit;
 
add_action( 'wp_ajax_nopriv_apoc_login', 'apoc_login' );
add_action( 'wp_ajax_apoc_login', 'apoc_login' );
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