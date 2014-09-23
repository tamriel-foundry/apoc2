<?php
/**
 * Apocrypha Theme BuddyPress Functions
 * Andrew Clayton
 * Version 2.0
 * 5-5-2014
*/

// Exit if accessed directly
if ( !defined( 'ABSPATH' ) ) exit;


class Apoc_BuddyPress {


	/**
	 * Construct the BuddyPress Class
	 * @since 2.0
	 */
	function __construct() {
	
		// Constants
		$this->constants();
		
		// Includes
		$this->includes();
		
		// Actions
		$this->actions();
		
		// Filters
		$this->filters();
	}
	
	/**
	 * Define additional BuddyPress constants
	 */
	function constants() {
	
		// Avatar Uploads
		define( 'BP_AVATAR_THUMB_WIDTH'		, 100 );
		define( 'BP_AVATAR_THUMB_HEIGHT'	, 100 );
		define( 'BP_AVATAR_FULL_WIDTH'		, 200 ); 
		define( 'BP_AVATAR_FULL_HEIGHT'		, 200 ); 
		define( 'BP_AVATAR_DEFAULT'			, THEME_URI . '/images/avatars/neutral-200.jpg' );
		define( 'BP_AVATAR_DEFAULT_THUMB'	, THEME_URI . '/images/avatars/neutral-100.jpg' );
		
		// Profile Components
		define( 'BP_DEFAULT_COMPONENT' 		, 'profile' );

		// Define Registration Check
		apoc()->humanity = "khajiit";
	}
	
	/**
	 * Include required BuddyPress functions
	 */	
	function includes() {
	
		// BuddyPress bundled AJAX library
		require_once( BP_PLUGIN_DIR . '/bp-themes/bp-default/_inc/ajax.php' );
	}
	
	
	/**
	 * Modify global BuddyPress actions
	 */
	function actions() {
	
		// Unhook default actions
		remove_action( 'wp_head' 			, 'bp_core_add_ajax_url_js' );		
		
		// Remove scripts and styles
		remove_action( 'wp_enqueue_scripts' , 'bp_core_confirmation_js' );

		// User registration
		add_action( 'bp_signup_pre_validate', array( $this , 'pre_registration' ) 	);
		add_action( 'bp_signup_validate'	, array( $this , 'post_registration' ) );
	}
	
	
	/**
	 * Modify global BuddyPress filters
	 */
	function filters() {

		// Activity delete link
		add_filter( 'bp_get_activity_delete_link'	, array( $this , 'activity_delete_button' ) );

		// Add-Remove friend button
		add_filter( 'bp_get_add_friend_button'		, array( $this , 'friend_button' ) );
	}



	/*------------------------------------------
		ACTIVITY
	------------------------------------------*/
	function activity_delete_button( $link ) {
		$link = str_replace( array( 'class="button' , 'Delete</a>') , array( 'class="button-dark' , '<i class="fa fa-remove"></i>Delete</a>' ) , $link ); 
		return $link;
	}	

	/*------------------------------------------
		MEMBERS
	------------------------------------------*/
	function friend_button( $button ) {
		
		// Remove the div wrapper
		$button['wrapper'] = false;
		$button['link_class'] = 'button-dark ' . $button['link_class'];

		// Not friends
		if ( in_array( $button['id'] , array( 'pending' , 'awaiting_response' , 'not_friends' ) ) )
			$button['link_text'] = '<i class="fa fa-check"></i>' . $button['link_text'];

		// Friends
		else if ( 'is_friend' === $button['id'] )
			$button['link_text'] = '<i class="fa fa-remove"></i>' . $button['link_text'];

		// Return the button
		return $button;
	}


	/*------------------------------------------
		USER REGISTRATION
	------------------------------------------*/
	/*
	 * Check that custom registration fields have been successfully completed.
	 */
	function pre_registration() {

		// Force the display name and login name to match
		$_POST['field_1'] = $_POST['signup_username'];	
	}

	function post_registration() {

		// Get the BuddyPress object
		global $bp;

		// Prevent special characters or spaces in usernames
		$_POST['signup_username'] = str_replace( " " , "-" , trim( $_POST['signup_username'] ) );

		if ( strpos( $_POST['signup_username'] , "@" ) || strpos( $_POST['signup_username'] , "." ) )
			$bp->signup->errors['signup_username'] = 'Your username may not contain special characters like "@" or "."';

		// Check extra fields
		if ( empty( $_POST['confirm_tos_box'] ) )
			$bp->signup->errors['confirm_tos_box'] = 'You must indicate that you understand the fundamental purpose of the Tamriel Foundry website and community.';

		if ( empty( $_POST['confirm_coc_box'] ) )
			$bp->signup->errors['confirm_coc_box'] = 'You must indicate your acknowledgement of the Tamriel Foundry code of conduct.';
			
		if ( apoc()->humanity != trim( strtolower ( $_POST['confirm_humanity'] ) ) )
			$bp->signup->errors['confirm_humanity'] = 'That is incorrect. Hover on the image if you require a hint.';
	}

}

// Automatically invoke the class
new Apoc_BuddyPress();




function apoc_registration_humanity_image() {

	// Get the current check
	$race = apoc()->humanity;

	// Return the image HTML
	echo '<img id="humanity-image" class="noborder" src="' . THEME_URI .'/registration/humanity.png" alt="HINT: This is a ' . ucfirst($race) . '" title="HINT: This is a ' . ucfirst($race) . '!" width="200" height="230" />';
}