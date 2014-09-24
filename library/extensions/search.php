<?php
/**
 * Apocrypha Advanced Search Class
 * Andrew Clayton
 * Version 2.0
 * 9-24-2014
*/

/*---------------------------------------------
	1.0 - CUSTOM SEARCH PAGE
----------------------------------------------*/

// Add the necessary rewrite rules
add_action( 'init', 'apoc_search_rewrite' ); 
add_action( 'template_redirect', 'apoc_search_template' );

// Define the advanced search URL
function apoc_search_rewrite() {
	$rule = 'advsearch/?$';
	$query	= 'index.php?name=advsearch';
	add_rewrite_rule( $rule , $query , 'top' );
}

// Redirect the template to use comment edit
function apoc_search_template() {
	global $wp_query;
	if ( ( isset( $wp_query->query['pagename'] ) && $wp_query->query['pagename'] == 'advsearch' ) || $wp_query->is_search ) {
		
		$wp_query->is_search 	= true;
		$wp_query->is_archive 	= true;
		$wp_query->is_404 		= false;

		// Grab the search template
		include ( THEME_DIR . '/archive-search.php' );
		exit();
	}
}

/*---------------------------------------------
	2.0 - SEARCH CLASS
----------------------------------------------*/

/**
 * Advanced Search Class
 * @version 2.0
 */
class Apoc_Search {

	// Declare properties
	public $submitted;
	public $context;
	public $search;
	public $paged;
	public $query;
	public $notice;


	// Construct the class
	function __construct() {

		// Determine search context
		$this->context 	= isset( $_REQUEST['type'] ) ? $_REQUEST['type'] : "";
		$this->search	= isset( $_REQUEST['s'] ) ? trim( $_REQUEST['s'] ) : "";
		$this->paged	= isset( $_REQUEST['page'] ) ? $_REQUEST['page'] : 1;

		// Get the current search request
		if ( isset( $_POST['submitted'] ) || $this->search !== "" )
			$this->submitted = true;
			$this->get_search();
	}

	// Retrieve search data
	function get_search() {
		$context = $this->context;

		// Get results
		if 		( "posts" === $context ) :
			$this->get_posts();
		elseif 	( "pages" === $context ) :
			$this->get_pages();
		elseif 	( "topics" === $context ) :
			$this->get_topics();
		elseif 	( "members" === $context ) :
			$this->get_members();
		elseif 	( "groups" === $context ) :
			$this->get_groups();
		endif;
	}

	// Search for posts
	function get_posts() {

		// Get extra fields
		$this->author	= isset( $_REQUEST['author'] ) && $_REQUEST['author'] != -1 ? $_REQUEST['author'] : NULL;
		$this->cat		= isset( $_REQUEST['cat'] ) && $_REQUEST['cat'] != -1 ? $_REQUEST['cat'] : NULL;

		// Construct a query
		$args 			= array( 
			'post_type'	=> 'post',
			's'			=> $this->search,
			'paged'		=> $this->paged,
			'author'	=> $this->author,
			'cat' 		=> $this->cat,
		);
		$this->query 	= new WP_Query( $args );

		// Set the notice
		$author			= isset( $this->author ) ? " by " . bp_core_get_user_displayname( $this->author ) : "";
		$category		= isset( $this->cat ) ? " in " . get_cat_name( $this->cat ) : "";
		$this->notice 	= sprintf( 'Viewing articles %1$s %2$s matching "%3$s"' , $author , $category , $this->search );
	}

	// Search for pages
	function get_pages() {
			
		// Construct a query
		$args = array( 
			'post_type'	=> 'page',
			's'			=> $this->search,
			'paged'		=> $this->paged,
		);
		$this->query 	= new WP_Query( $args );

		// Set the notice
		$this->notice 	= sprintf( 'Viewing ' . SITENAME . ' pages matching "%1$s"' , $this->search );
	}


	// Search for topics
	function get_topics() {
	
		// Search for topics
		$this->forum		= ( isset( $_POST['forum'] ) && $_POST['forum'] != '' ) ? $_POST['forum'] : 'any';

		// Construct a query		
		$this->query 		= array(
			'post_type'		=> 'topic',
			'post_parent'	=> $this->forum,
			'meta_key'       => '_bbp_last_active_time', 
			'orderby'       => 'meta_value',
			'order'			=> 'DESC',
			's'				=> $this->search,
			'paged'			=> $this->paged,
			'show_stickies'	=> false,
			'max_num_pages'	=> false,
		);

		// Set the notice
		$this->notice 		= sprintf( 'Viewing forum topics matching "%1$s"' , $this->search );
	}


	// Search for members
	function get_members() {

		// Get extra fields
		$this->faction		= ( isset( $_POST['faction'] ) ) ? $_POST['faction'] : 'any';

		// Construct a query			
		$this->query 		= array(
			'type'			=> 'active',
			's'				=> $this->search,
			'paged'			=> $this->paged,
			'per_page'		=> 12,
			'meta_key'		=> 'faction',
			'meta_value'	=> $this->faction,	
		);

		// Set the notice
		$this->notice 		= sprintf( 'Viewing ' . SITENAME . ' members matching "%1$s"' , $this->search );
	}


	// Search for groups
	function get_groups() {

		// Get extra fields
		$this->faction		= ( isset( $_POST['faction'] ) ) ? $_POST['faction'] : 'any';

		// Construct a query			
		$this->query 		= array(
			'type'			=> 'active',
			's'				=> $this->search,
			'paged'			=> $this->paged,
			'per_page'		=> 12,
			'meta_key'		=> 'faction',
			'meta_value'	=> $this->faction,	
		);

		// Set the notice
		$this->notice 		= sprintf( 'Viewing guilds matching "%1$s"' , $this->search );
	}

}