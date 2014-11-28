<?php 
/**
 * Apocrypha Theme Context Functions
 * Andrew Clayton
 * Version 2.0
 * 11-28-2014
 */
 
// Exit if accessed directly
if ( !defined( 'ABSPATH' ) ) exit;


/*--------------------------------------------------------------
	INCLUDE TEMPLATE ELEMENTS
--------------------------------------------------------------*/

// Admin Bar
function apoc_admin_bar() {
	include( THEME_DIR . '/library/templates/admin-bar.php' );
}

// Navigation Menu
function apoc_primary_menu() {
	include( THEME_DIR . '/library/templates/primary-menu.php' );
}

// Sidebar
function apoc_primary_sidebar( $group = '' ) {
	if ( 'er' == $group ) er_guild_sidebar();
	else include( THEME_DIR . '/library/templates/primary-sidebar.php' );
}

// Single Post
function apoc_single_post() {
	include( THEME_DIR . '/library/templates/single-post.php' );
}

// Comment Respond Form
function apoc_comment_form() {
	include( THEME_DIR . '/library/templates/respond.php' );
}

// Entropy Rising Components
function entropy_rising_menu() {
	locate_template( array( 'erguild/er-menu.php' ), true );
}
function entropy_rising_sidebar() {
	locate_template( array( 'erguild/er-sidebar.php' ), true );
}


/**
 * Adds additional supported tags to the allowed kses tags, giving users more freedom in comments and forum posts
 * @version 2.0
 */	
// Support more allowed tags in KSES
add_action( 'init' , 'apoc_extra_kses' );
function apoc_extra_kses() {

	// Define the newly allowed tags
	global $allowedtags;	
	$newtags = array( 'div' , 'ol' , 'ul' , 'li' , 'p' , 'h1' , 'h2' , 'h3' , 'h4' , 'h5' , 'h6' , 'span' , 'pre' , 'img' );
	
	// Register each tag with style and class properties
	foreach ( $newtags as $tag )
	$allowedtags[$tag] = array(
		'style'	=> true,
		'class'	=> true,
	);
	
	// Register extra properties for certain tags
	$allowedtags['a']['target'] = true;
	$allowedtags['img']['src'] = true;
	$allowedtags['img']['height'] = true;
	$allowedtags['img']['width'] = true;
	$allowedtags['img']['alt'] = true;
}


/** 
 * Custom text sanitization and filtering
 * @version 2.0
 */
function apoc_custom_kses( $content ) {
	$content = wp_filter_post_kses( $content );
	$content = wptexturize( $content );
	$content = wpautop( $content );
	$content = convert_chars( $content );
	$content = force_balance_tags( $content );
	return $content;
}

/** 
 * Quick helper function to register a user's donation
 * @version 2.0
 */
function apoc_register_donation( $user_id , $amount ) {

	// Get the user
	$user 		= get_user_by( 'id' , $user_id ); 
	$name		= $user->data->display_name;
	
	// Get the current donation level
	$current 	= intval( get_user_meta( $user_id , 'donation_amount' , true ) );
	
	// Get the new donation level
	$new		= $current + $amount;
	
	// Update the user meta
	update_user_meta( $user_id , 'donation_amount' , $new , $current );
	
	// Send a private message
	$subject	= "Thank you for contributing to Tamriel Foundry!";
	$content	= '<p>Hey ' . $name . '</p>';
	$content	.= '<p>I wanted to personally thank you for your generous donation to help support Tamriel Foundry. It&apos;s a lot of work sustaining a community of this size, but we wouldn&apos;t have the community we do without members like yourself. Thanks for the vote of confidence in what we&apos;re doing and for helping to keep Tamriel Foundry moving in the right direction. You rock!</p>';
	$content	.= '<p>Best Regards,</p>';
	$content	.= '<p>Atropos</p>';
	
	$message 	= array(
		'sender_id'		=> 1,
		'thread_id'		=> false,
		'recipients'	=> $user_id,
		'subject'		=> $subject,
		'content'		=> $content	
	);
	messages_new_message( $message );
	
	// Display success
	return "Donation successfully registered for " . $name . ". New donor level is $" . $new . ".";
}

/** 
 * Quick helper function to change a username
 * @version 2.0
 */
function apoc_change_username( $old_slug , $new_display ) {

	// Get the current user by the login name
	$user 		= get_user_by( 'slug' , $old_slug );
	if ( empty( $user ) )	return 'Invalid user slug!';
	$user_id	= $user->data->ID;
	
	// Determine the new login name
	$new_login	= sanitize_user( $new_display , true );
	if ( get_user_by( 'login' , $new_login )  )
		return 'Conflict detected for login name ' . $new_login;
		
	// Determine the new slug
	$new_slug	= strtolower( $new_login );
	if ( get_user_by( 'slug' , $new_slug ) )
		return 'Conflict detected for slug ' . $new_slug;
		
	// Update the user table
	global $wpdb;
	$wpdb->update($wpdb->users, array('user_login' => $new_login), array('ID' => $user_id));
	wp_update_user( array ( 
		'ID' 			=> $user_id, 
		'user_nicename' => $new_slug, 
		'display_name'	=> $new_display,
	) );
	
	// Update the usermeta
	update_user_meta( $user_id , 'nickname' , $new_display );
	
	// Update xProfile
	xprofile_set_field_data( 1 , $user_id , $new_display );
	
	// Send a private message
	$subject	= "Tamriel Foundry Username Changed";
	$content	= '<p>Hi ' . $new_display . '</p>';
	$content	.= '<p>Your Tamriel Foundry username has been successfully changed, so you may now log into the site as ' . $new_login . '. Please email admin@tamrielfoundry.com if you have any trouble or questions!</p>';
	$content	.= '<p>Best Regards,</p>';
	$content	.= '<p>Atropos</p>';
	
	$message 	= array(
		'sender_id'		=> 1,
		'thread_id'		=> false,
		'recipients'	=> $user_id,
		'subject'		=> $subject,
		'content'		=> $content	
	);
	messages_new_message( $message );
	
	// Return successful
	return 'Username for ' . $old_slug . ' successfully updated to ' . $new_slug . '!';
}
?>