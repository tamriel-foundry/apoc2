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
		remove_action( 'wp_head' , 'bp_core_add_ajax_url_js' );		
		
		// Remove scripts and styles
		remove_action( 'wp_enqueue_scripts' , 'bp_core_confirmation_js' );
	}
	
	
	/**
	 * Modify global BuddyPress filters
	 */
	function filters() {
			
		
	
	}	
	
	
}

// Automatically invoke the class
new Apoc_BuddyPress();