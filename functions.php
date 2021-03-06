<?php 
/**
 * Apocrypha Theme Functions
 * Andrew Clayton
 * Version 2.1
 * 6-22-2015
 *
 * ----------------------------------------------------------------
 * >>> TABLE OF CONTENTS:
 * ----------------------------------------------------------------
 * 1.0 - Load Core Framework
 * 2.0 - Scripts and Styles
 * --------------------------------------------------------------*/

// Exit if accessed directly
if ( !defined( 'ABSPATH' ) ) exit;

/*--------------------------------------------------------------
	1.0 - LOAD CORE FRAMEWORK
--------------------------------------------------------------*/

// Define theme version
define( 'THEMEVER' , 2.0 );

/**
 * This function initializes the Apocrypha theme framework.
 * It runs immediately after WordPress loads core libraries.
 * Most "run once per pageload" things are done here also.
 *
 * @since 2.0
 * Returns Apocrpyha theme object.
 */
add_action( 'after_setup_theme' , 'apoc_setup' , 1 );
function apoc_setup() {

	// Load the Apocrypha class
	require_once( trailingslashit( TEMPLATEPATH ) . 'library/apocrypha.php' );	
	
	// Initialize the Apocrypha theme object
	if ( class_exists( 'Apocrypha' ) ) apoc();
}

/**
 * Initiates "Maintenance Mode"
 * @version 2.0
 */
//add_action( 'after_setup_theme' , 'maintenance_redirect' , 0 );
function maintenance_redirect() {

	// Get the current page
	global $pagenow;

	// Get the current user
	$user_id = get_current_user_id();

	// Allowed users
	$allowed = array( 1 , 2 , 8 , 24496 );

	// Redirect non-allowed users except on wp-login.php
	if ( $pagenow != 'wp-login.php' && !in_array( $user_id , $allowed ) ) {
		header( 'Location: http://tamrielfoundry.com/maintenance.html' );
		die();
	}
}

/*--------------------------------------------------------------
	2.0 - SCRIPTS AND STYLES
--------------------------------------------------------------*/

/**
 * Load stylesheets and JavaScript based on context
 * @since 2.0
 */
add_action( 'wp_enqueue_scripts' , 'apoc_scripts' );
function apoc_scripts() {

	// Deregister Styles
	add_filter( 'use_default_gallery_style' , '__return_false' );

	// Register Styles
	wp_register_style( 'primary' , THEME_URI . '/style.css' , false , $ver=filemtime( THEME_DIR . "/style.css" ) );
	wp_register_style( 'font-awesome' , 'http://netdna.bootstrapcdn.com/font-awesome/4.2.0/css/font-awesome.min.css' , false , $ver='4.1.0' );
	wp_register_style( 'cinzel' , 'http://fonts.googleapis.com/css?family=Cinzel:400,700' , false  );
	wp_register_style( 'open-sans' , 'http://fonts.googleapis.com/css?family=Open+Sans:400italic,400,700' , false );
	
	// Enqueue Styles
	wp_enqueue_style( 'font-awesome' );
	wp_enqueue_style( 'cinzel' );
	wp_enqueue_style( 'open-sans' );
	wp_enqueue_style( 'primary' );
	
	// Deregister Scripts
	wp_deregister_script( 'jquery' );
	
	// Register Scripts
	wp_register_script( 'jquery' , 		'http://ajax.googleapis.com/ajax/libs/jquery/2.1.1/jquery.min.js' ,'jquery' , $ver ='2.1.1' , true );
	wp_register_script( 'foundry' , 	THEME_URI.'/library/scripts/foundry.js' , 'jquery' , $ver='0.6' , true	);
	wp_register_script( 'buddypress' , 	THEME_URI.'/library/scripts/buddypress.js' , 'jquery' , $ver='0.2' 	, true 	);
	wp_register_script( 'googletag'	, 	THEME_URI.'/library/scripts/gpt.js' , 'gpt' , $ver='0.42' , false );	
	
	// Enqueue Scripts
	wp_enqueue_script( 'jquery' );
	wp_enqueue_script( 'foundry' );
	wp_enqueue_script( 'buddypress' );
	//wp_enqueue_script( 'googletag' );

	// FlexSlider
	if ( is_home() || is_page( 'entropy-rising' ) ) {
		wp_register_script( 'flexslider' 	, THEME_URI.'/library/scripts/flexslider.js' , 'jquery' , $ver='0.1' , true );
		wp_enqueue_script( 'flexslider' );
	}

	// Enchanting Guide
	elseif ( is_page( 'enchanting' )) {
		wp_register_script( 'enchanting'	, THEME_URI.'/library/scripts/enchanting.js' , 'jquery' , $ver='0.8' , true );
		wp_enqueue_script( 'enchanting' );
		
		// Enqueue CSS
		wp_register_style( 'enchanting' 	, THEME_URI.'/library/css/enchanting.css'  , 'foundry' , $ver='0.7' );
		wp_enqueue_style( 'enchanting' );
	}
}

/*--------------------------------------------------------------
3.0 - TINYMCE CUSTOMIZATION
--------------------------------------------------------------*/
/**
 * Set some TinyMCE options
 * @version 1.0.0
 */
add_filter( 'tiny_mce_before_init' , 'apoc_mce_options' );
add_filter( 'teeny_mce_before_init' , 'apoc_mce_options' );
function apoc_mce_options( $init ) {

	// Set an easily-incrementable editor version
	$editor_ver	= 1.0;
    
	// Get the proper URL format
	$stylesheet = substr( THEME_URI . '/library/css/' , strpos( THEME_URI . '/library/css/' , '/' , 7 ) );
	
	// TinyMce initialization options
	if( !is_admin() )
			$init['content_css']				= $stylesheet . 'editor-content.css?v=1.4';
	$init['wordpress_adv_hidden'] 				= false;
	$init['height']								= 250;
	$init['theme_advanced_resizing_use_cookie'] = false;
    return $init;
}

add_filter( 'mce_buttons' , 'apoc_mce_buttons' );
add_filter( 'mce_buttons_2' , 'apoc_mce_buttons_2' );
function apoc_mce_buttons( $buttons ) {

	// Add buttons
	array_splice($buttons, 2, 0, 'underline');
	
	// Only remove buttons for frontend users
	if ( is_admin() ) return $buttons;
	
	// Remove buttons and return
	$remove = array('wp_more','wp_adv','fullscreen');
	return array_diff($buttons,$remove);

}
function apoc_mce_buttons_2( $buttons ) {

	// Only remove buttons for frontend users
	if ( is_admin() ) return $buttons;
	
	// Remove buttons and return
	$remove = array('wp_help','underline','pasteword','pastetext');
	return array_diff($buttons,$remove);
}
?>