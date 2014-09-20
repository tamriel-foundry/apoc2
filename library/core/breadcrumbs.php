<?php
/**
 * Apocrypha Theme Breadcrumb Class
 * Andrew Clayton
 * Version 2.0
 * 7-22-2014
 */
 
// Exit if accessed directly
if ( !defined( 'ABSPATH' ) ) exit;

/*---------------------------------------------
	1.0 - BREADCRUMB CLASS
----------------------------------------------*/

/**
 * Generates breadcrumb links for Tamriel Foundry pages.
 * Gives users a handy reference for where they are within the site.
 *
 * @author Andrew Clayton
 * @version 2.0
 */
class Apoc_Breadcrumbs {

	// Breadcrumb arguments
	public $args;
	
	// Resulting links
	public $crumbs;

	/**
	 * Constructor function
	 */
	function __construct( $args = array() ) {
	
		// Setup default arguments
		$defaults = array(
			'container' 	=> 'nav',
			'separator' 	=> ' &raquo; ',
			'before' 		=> 'Viewing:',
			'home' 			=> 'Home',
		);
		
		// Extract arguments
		$this->args			= wp_parse_args( $args , $defaults );;
		
		// Generate links
		$this->crumbs		= $this->generate_crumbs();	
	}
	
	/**
	 * Create the breadcrumb trail
	 */
	function generate_crumbs() {
	
		// Declare variables
		$breadcrumbs = '';
		extract( $this->args , EXTR_SKIP );
		
		// Get the items based on page context
		$trail = apoc()->crumbs;
		
		// If we have items, build the trail
		if ( !empty($trail) && is_array( $trail ) ) {
		
			// Wrap the trail and add the 'Before' element
			$breadcrumbs = '<' .$container . ' class="breadcrumbs">';
			$breadcrumbs .= ( !empty( $before ) ? '<span class="trail-before">' . $before . '</span> ' : '' );
			
			// Add 'trail-end' class around last item 
			array_push( $trail, '<span class="trail-end">' . array_pop( $trail ) . '</span>' );

			// Join the individual trail items into a single string 
			$breadcrumbs .= join( "{$separator}", $trail );

			// Close the breadcrumb trail containers 
			$breadcrumbs .= '</' .  $container . '>';			
		}
		
		// Return the breadcrumbs
		return $breadcrumbs;	
	}
}
 
/*---------------------------------------------
	2.0 - STANDALONE FUNCTIONS
----------------------------------------------*/

/**
 * Helper function that displays the breadcrumbs in templates
 * @version 2.0
 */
function apoc_breadcrumbs( $args = array() ) {
	$crumbs = new Apoc_Breadcrumbs( $args );
	echo $crumbs->crumbs;
}

?>