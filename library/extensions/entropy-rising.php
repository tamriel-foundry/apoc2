<?php 
/**
 * Entropy Rising Guild Functions
 * Andrew Clayton
 * Version 2.0
 * 11-14-2014
 */
 
// Exit if accessed directly
if ( !defined( 'ABSPATH' ) ) exit;

/*---------------------------------------------
	1.0 - ENTROPY_RISING CLASS
----------------------------------------------*/

/**
 * Configures login, authentication, and security features
 *
 * @author Andrew Clayton
 * @version 1.0.1
 */
class Entropy_Rising {

	// Assign the Entropy Rising group ID
	public $group_id 	= 1;

	// Assign recruitment status
	public $recruiting 	= true;

	// Assign recruitment priorities
	public $priorities = array(
		'dragonknight' 	=> 'low',
		'templar' 		=> 'high',
		'sorcerer' 		=> 'medium',
		'nightblade' 	=> 'high',
	);

 	/**
	 * Construct the class
	 * @version 2.0
	 */	
	function __construct() {
	
		// Get the Entropy Rising group from BuddyPress
		$this->group = groups_get_group( array( 'group_id' => $this->group_id , 'populate_extras' => true ) );

		// Remap membership flag
		$this->is_member = $this->group->is_member;

	}
}

/*---------------------------------------------
	2.0 - HELPER FUNCTIONS
----------------------------------------------*/

function er_is_member() {
	global $er;
	return $er->is_member;
}

function er_is_recruiting() {
	global $er;
	return $er->recruiting;
}

function er_recruitment_priorities() {
	global $er;
	return $er->priorities;
}


/**
 * Entropy Rising homepage have_posts query
 * @version 2.0
 */
function er_have_posts() {

	// Get the page and offset
	$posts_per_page = 6;
	$paged 			= ( get_query_var( 'paged' ) ) ? get_query_var( 'paged' ) : 1;
	$offset 		= ( $posts_per_page * $paged ) - $posts_per_page;
	
	// Format query arguments
	$args = array( 
		'paged'=> $paged, 
		'posts_per_page'=> $posts_per_page,
		'offset' => $offset,
		'cat' => get_cat_ID( 'guild news' ) . ',' . get_cat_ID('entropy rising'),
		);
		
	// Retrieve posts
	query_posts( $args );

	// Return whether there are posts
	return have_posts();
}

function er_guild_sidebar() {
	include( THEME_DIR . '/erguild/er-sidebar.php' );
}
function er_guild_menu() {
	include( THEME_DIR . '/erguild/er-menu.php' );
}
