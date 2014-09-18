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


?>
