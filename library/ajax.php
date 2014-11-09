<?php 
/**
 * Apocrypha Theme Minimal AJAX Handler
 * Andrew Clayton
 * Version 2.0
 * 9-20-2014
 */

// Imitate AJAX Functionality
define('DOING_AJAX', true);

// Reject non POST actions
if( !isset( $_POST['action'] ) )
	exit;

// Load WordPress
require_once( '../../../../wp-load.php' ); 

//Typical headers
header('Content-Type: text/html');
send_nosniff_header();

//Disable caching
header('Cache-Control: no-cache');
header('Pragma: no-cache');

// Determine the requested action
$action = esc_attr( trim( $_POST['action'] ) );

// Is the action allowed for any user?
if ( has_action( 'apoc_ajax_nopriv_'.$action ) )
	do_action( 'apoc_ajax_nopriv_'.$action );

// Is action allowed for logged-in users?
elseif ( is_user_logged_in() && has_action( 'apoc_ajax_'.$action ) )
	do_action( 'apoc_ajax_'.$action );

// Otherwise AJAX is not allowed
else die( 'AJAX action ' . $action . ' does not exist or is missing in action!' );
exit; ?>
