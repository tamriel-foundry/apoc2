<?php 
/**
 * Apocrypha Theme Context Functions
 * Andrew Clayton
 * Version 2.0
 * 4-30-2014
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
function apoc_primary_sidebar() {
	include( THEME_DIR . '/library/templates/primary-sidebar.php' );
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
?>
