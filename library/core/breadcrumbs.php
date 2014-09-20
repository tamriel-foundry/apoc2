<?php
/**
 * Apocrypha Theme Breadcrumb Class
 * Andrew Clayton
 * Version 1.0.0
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
 * @version 1.0.0
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
		$trail = $this->get_trail();
		
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
	
	/**
	 * Get the breadcrumb trail
	 */
	function get_trail() {
	
		// Declare placeholder
		$trail = array();
		
		// Always start with a link to the homepage
		$trail[] = '<a href="' . SITEURL . '" title="' . SITENAME . '" rel="home" class="trail-home">' . $this->args['home'] . '</a>';
		
		// Are we viewing BuddyPress?
		if ( class_exists( 'BuddyPress' ) && is_buddypress() ) :
			$trail = array_merge( $trail , $this->bp_crumbs() );
		
		// Are we viewing bbPress?
		elseif ( class_exists( 'bbPress' ) && is_bbpress() ) :
			$trail = array_merge( $trail , $this->bbp_crumbs() );
		
		// Otherwise try WordPress
		else :
			$trail = array_merge( $trail , $this->wp_crumbs() );
		endif;
		
		// Return the trail
		return $trail;	
	}
	
	/**
	 * WordPress breadcrumb items
	 */
	function wp_crumbs() {
	
		// Setup empty trail
		$trail 	= array();	
		
		// Singular Views
		if ( is_singular() ) :
			switch( get_post_type() ) {
			
				// Single Posts 
				case 'post' :
					
					// Is the post in a category?
					$categories = get_the_category();
					if ( $categories ) {

						// Start with the first category
						$term = $categories[0];
				
						// If the category has a parent, add it to the trail. 
						if ( 0 != $term->parent ) 
							$trail = array_merge( $trail, $this->parent_crumbs( $term->parent, 'category' ) );

						// Add the category archive link to the trail. 
						$trail[] = '<a href="' . get_term_link( $term ) . '" title="' . esc_attr( $term->name ) . '">' . $term->name . '</a>';
					}

					// Does the post have an ancestor?
					if ( wp_get_post_parent_id( get_the_ID() ) ) 
						$trail = array_merge( $trail, $this->parent_crumbs( wp_get_post_parent_id( get_the_ID() ) ) );

					// Editing a comment on this post
					if ( is_comment_edit() ) :
						$trail[] = '<a href="' . get_permalink() . '" title="Return to article">' . get_the_title() . '</a>';
						$trail[] = 'Edit Comment';	
				
					// Reading the post
					else :
						$trail[] = get_the_title();
					endif; 
				break;


				// Pages
				case 'page' :
					
					// Does the page have an ancestor?
					if ( wp_get_post_parent_id( get_the_ID() )  ) 
						$trail = array_merge( $trail, $this->parent_crumbs( wp_get_post_parent_id( get_the_ID() ) ) );
					
					// Otherwise, viewing the page
					$trail[] = get_the_title();
				break;

				case 'event' :
					$trail[] = 'Event';
					$trail[] = get_the_title();
				break;
			}

		// Page Not Found
		elseif ( is_404() ) : 
			$trail[] = '404 Page Not Found';		
		endif;

		// Return the items
		return $trail;
	}
	
	/**
	 * bbPress breadcrumb items
	 */
	function bbp_crumbs() {
	
		// Setup empty trail
		$trail 	= array();
		
		// Forums home
		if ( bbp_is_forum_archive() ) {
			$trail[] = 'Forums';
			return $trail;
		}
		
		// Otherwise link to the forum home
		$trail[] = '<a href="' . get_post_type_archive_link( 'forum' ) . '">Forums</a>';
		
		// Recent topics page
		if ( bbp_is_topic_archive() ) :
			$trail[] = 'Recent Topics';
			
		// Single forum
		elseif ( bbp_is_single_forum() ) :
		
			// Get the forum
			$forum_id 	= get_queried_object_id();
			$parent_id 	= bbp_get_forum_parent_id( $forum_id );	

			// Get the forum parents
			if ( 0 != $parent_id ) 
				$trail = array_merge( $trail, $this->parent_crumbs( $parent_id ) );
				
			// Give the forum title
			$trail[] = bbp_get_forum_title( $forum_id );			
			
		// Single topic
		elseif ( bbp_is_single_topic() ) :
			$topic_id = get_queried_object_id();
			$trail = array_merge( $trail, $this->parent_crumbs( bbp_get_topic_forum_id( $topic_id ) ) );
			$trail[] = bbp_get_topic_title( $topic_id );
		
		// Split, merge, or edit topic
		elseif ( bbp_is_topic_split() || bbp_is_topic_merge() || bbp_is_topic_edit() ) :
			$topic_id = get_queried_object_id();
			$trail = array_merge( $trail, $this->parent_crumbs( $topic_id ) );
		
			// Tag the specific task
			if ( bbp_is_topic_split() ) :		$trail[] = 'Split Topic';
			elseif ( bbp_is_topic_merge() ) :	$trail[] = 'Merge Topic';
			elseif ( bbp_is_topic_edit() )	:	$trail[] = 'Edit Topic';
			endif;
			
		// Edit reply
		elseif ( bbp_is_reply_edit() ) :
			$reply_id = get_queried_object_id();
			$trail = array_merge( $trail, $this->parent_crumbs( bbp_get_reply_topic_id( $reply_id ) ) );
			$trail[] = 'Edit Reply';
		endif; 
		
		// Return the items
		return $trail;		
	}
	
	/**
	 * BuddyPress breadcrumb items
	 */
	function bp_crumbs() {
	
		// Setup empty trail
		$trail 	= array();
		
			
		// Registration
		if ( bp_is_register_page() ) :
			$trail[] = "User Registration";

		// Activation
		elseif ( bp_is_activation_page() ) :
			$trail[] = "Account Activation";


		// Temporary placeholder
		else :
			$trail[] = 'BuddyPress';
		endif;
		
		// Return the items
		return $trail;
	}
	
	/**
	 * Parent breadcrumb recursor
	 */
	function parent_crumbs( $post_id ) {
	
		// Setup empty trail
		$trail 	= array();
		
		// Verify we have something to work with 
		if ( empty( $post_id ) ) return $trail;
		
		// Loop through post IDs until we run out of parents 
		$parents = array();
		while ( $post_id ) {
		
			// Get the parent page
			$page 		= get_page( $post_id );
			$parents[]  = '<a href="' . get_permalink( $post_id ) . '" title="' . esc_attr( get_the_title( $post_id ) ) . '">' . get_the_title( $post_id ) . '</a>';
			
			// Load the grandparent page if one exists
			$post_id	 = $page->post_parent;
		}
	
		// If parents were found, reverse their order
		if ( !empty( $parents ) ) 
			$trail = array_reverse( $parents );
			
		// Return the trail
		return $trail;
	}

}
 
/*---------------------------------------------
	2.0 - STANDALONE FUNCTIONS
----------------------------------------------*/

/**
 * Helper function that displays the breadcrumbs in templates
 * @version 1.0.0
 */
function apoc_breadcrumbs( $args = array() ) {
	$crumbs = new Apoc_Breadcrumbs( $args );
	echo $crumbs->crumbs;
}

?>