<?php 
/**
 * Apocrypha Theme Context Functions
 * Andrew Clayton
 * Version 2.0
 * 4-29-2014
 */
 
// Exit if accessed directly
if ( !defined( 'ABSPATH' ) ) exit;

/**
 * Determines page context and generates SEO.
 * @since 2.0
 */
class Apoc_Context {

	// Declare context
	public $body_class;
	public $title;
	public $description;

	// Construct context class
	function __construct() {
	
		// Get the current object
		$this->queried_object		= get_queried_object();
		$this->queried_object_id	= isset( $this->queried_object->ID ) ? $this->queried_object->ID : NULL ;	

		// Get the currently requested url
		$this->get_url();
		
		// Is it a paged request?
		$this->page					= ( get_query_var( 'paged' ) ) ? get_query_var( 'paged' ) : 1;
		
		// Is it a search request
		$this->search				= is_search() ? get_search_query() : "";
		
		// Generate the page context
		$this->get_context();
	}
	
	
	function get_context() {
	
		// Defaults
		$classes 	= array();
		$title		= SITENAME;
		$desc		= get_bloginfo( 'description' );
		$sep		= " &bull; ";
		
		// Data
		$id			= $this->queried_object_id;
		$object		= $this->queried_object;
	
		// Homepage
		if ( is_home() ) {
			$classes[]	= 'home';
			$classes[]	= 'sidebar';
			$classes[] 	= 'archive';
			$title		= $title . $sep . 'Home';
		}
	
		// bbPress
		elseif ( class_exists( 'bbPress' ) && is_bbpress() ) {
			$classes[]	= 'bbpress';
			$classes[]	= 'forums';
		
			// Main Forum Archive
			if ( bbp_is_forum_archive() ) :
				$title 			= SITENAME . " Forums";
				$desc 			= "Get involved in the community on the " . SITENAME . " forums.";
				
			// Recent Topics
			elseif ( bbp_is_topic_archive() ) :
				//$doctitle 		= "Recent Topics in the {$sitename} Forums";
				//$description 	= "Browse a list of the most recent forum topics on {$sitename}.";
				
			// Single Forum
			elseif ( bbp_is_single_forum() ) :
				//$doctitle 		= $object->post_title;
				//$description 	= $object->post_content;
				
			// Single Topic
			elseif ( bbp_is_single_topic() ) :
				$doctitle 		= $object->post_title;
				$description	= bbp_get_topic_excerpt( $id );				
				
			// Edit Topic
			elseif ( bbp_is_topic_edit() ) :
				//$doctitle 		= 'Edit Topic' . $separator . $object->post_title;
				//$description	= bbp_get_topic_excerpt( $id );				
			
			// Edit Reply
			elseif ( bbp_is_reply_edit() ) :
				//$doctitle 		= str_replace( 'To: ' , $separator , 'Edit ' . $object->post_title );
				//$description	= bbp_get_reply_excerpt( $id );				
			endif;
		}	
		
		// BuddyPress
		elseif ( class_exists( 'BuddyPress' ) && is_buddypress() ) {
			$classes[]	= 'buddypress';
			$title		= "BuddyPress Page";
			$desc		= "This is a BuddyPress page.";
			
			// Registration and activation
			if ( bp_is_register_page() || bp_is_activation_page() ) :
				$title	= get_the_title();
				$desc 	= "Register to join the " . SITENAME . " community.";
			endif;
		}
		
		// Singular Posts and Pages
		elseif ( is_singular() ) {
			$classes[] 	= 'singular';
			$classes[] 	= 'sidebar';
			$title		= $object->post_title;
			$desc		= get_post_meta( $id , 'description' , true );
			
			// If no description is found, use an excerpt
			if ( empty( $desc ) )
				$desc	= get_post_field( 'post_excerpt' , $id );
				
			// If no excerpt is found, just use the title
			if ( empty( $desc ) )
				$desc	= $title;
			
			// Check for custom template
			$template = get_post_meta( $id , "_wp_{$object->post_type}_template", true );
			if ( '' != $template ) {
				$template 	= str_replace( array ( "{$object->post_type}-template-", "{$object->post_type}-" ), '', basename( $template , '.php' ) );
				$classes[] 	= "{$template}-template";
			}
		}
		
		// Archives
		elseif ( is_archive() ) {
			
		}
		
		// Is user not logged in?
		$classes[]	= ( 0 == get_current_user_id() ) ? 'logged-out' : '';		
		
		// Merge body classes with WordPress defaults
		$body_class = implode( ' ' , array_unique( get_body_class( $classes ) ) );
		
		// Return data
		$this->body_class 	= $body_class;
		$this->title		= html_entity_decode($title);
		$this->description	= html_entity_decode($desc);
	}
	
	
	function get_url() {
		$url  = ( isset( $_SERVER['HTTPS']) && $_SERVER['HTTPS'] == 'on' ) ? 'https://'.$_SERVER['SERVER_NAME'] :  'http://'.$_SERVER['SERVER_NAME'];
		$url .= $_SERVER["REQUEST_URI"];
		$this->url = $url;
	}
}


/*--------------------------------------------------------------
	STANDALONE FUNCTIONS
--------------------------------------------------------------*/
function apoc_title() {
	echo apoc()->title;
}

function apoc_description() {
	echo apoc()->description;
}

function apoc_body_class() {
	echo apoc()->body_class;
}
 