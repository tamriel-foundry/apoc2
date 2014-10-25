<?php 
/**
 * Apocrypha Theme Context Functions
 * Andrew Clayton
 * Version 2.0
 * 9-20-2014
 */
 
// Exit if accessed directly
if ( !defined( 'ABSPATH' ) ) exit;

/**
 * Determines page context, generates breadcrumbs and SEO title/description
 * @version 2.0
 */
class Apoc_Context {

	// Declare context
	public $title;
	public $description;
	public $url;
	public $classes;
	public $crumbs;

	// Construct context class
	function __construct() {
	
		// Get the current object
		$this->queried_object_id	= get_queried_object_id();
		$this->queried_object		= get_queried_object();

		// Get the currently requested url
		$this->get_url();
		
		// Is it a paged request?
		$this->page					= ( get_query_var( 'paged' ) ) ? get_query_var( 'paged' ) : 1;
		
		// Is it a search request
		$this->search				= is_search() ? get_search_query() : "";
		
		// Generate the page context
		$this->get_context();
	}
	
	/**
	 * Get the page context
	 * @version 2.0
	 */	
	function get_context() {

		// Setup placeholders
		$title		= SITENAME;
		$desc		= get_bloginfo( 'description' );
		$classes	= get_body_class();
		$crumbs		= array();
	
		// Get some data
		$id					= $this->queried_object_id;
		$object				= $this->queried_object;
		$sep				= " &bull; ";

		/*--------------------------------------------
			DEFAULT CONTEXT
		---------------------------------------------*/
		$classes[]			= ( 0 == get_current_user_id() ) ? 'logged-out' : 'logged-in';	
		$crumbs[] 			= '<a href="' . SITEURL . '" title="' . SITENAME . '" rel="home" class="trail-home">Home</a>';	
	
		// Homepage
		if ( is_home() ) :
			$title			= SITENAME . $sep . 'Home';
			$classes[]		= 'home';
			$classes[]		= 'sidebar';
			$classes[] 		= 'archive';

		/*--------------------------------------------
			BUDDYPRESS CONTEXT
		---------------------------------------------*/
		elseif ( class_exists( 'BuddyPress' ) && is_buddypress() ) :

			// BuddyPress Defaults
			$title			= "BuddyPress Page";
			$desc			= "This is a BuddyPress page.";
			$classes[]		= 'buddypress';

			// User Profiles
			if ( bp_is_user() ) :
				
				$title		= bp_get_displayed_user_fullname() . $sep . "User Profile";				
				$desc		= SITENAME . " user profile for member " . bp_get_displayed_user_fullname();

				// Your own profile
				if ( bp_is_my_profile() ) :
					$crumbs[] 	= 'Your Profile';
				else :
					$crumbs[] 	= '<a href="'. bp_get_members_directory_permalink() .'" title="Members Directory">Members</a>';
					$crumbs[] 	= '<a href="'.bp_displayed_user_domain().'" title="'.bp_get_displayed_user_fullname(). '">' . bp_get_displayed_user_fullname() . '</a>';
				endif; 

				// Display the profile component if it isnt the profile home
				if ( !bp_is_user_profile() ) :
					$crumbs[] = ucfirst( bp_current_component() );
				endif; 

				// Display the current action if it is not the default public profile
				if ( !in_array( bp_current_action() , array( 'public' , 'just-me' , 'my-friends' ) ) ) :
					$crumbs[] = ucfirst( bp_current_action() );
				endif;

			// Single Group
			elseif ( bp_is_group() || bp_is_group_create() ) :

				// Group Creation
				if ( bp_is_group_create() ) : 
					$title 		= 'Submit New Group';
					$desc		= 'Submit a new user group for listing on the ' . SITENAME . ' community groups directory.';
					$crumbs[] 	= '<a href="' . SITEURL . '/' . bp_get_groups_root_slug() . '" title="Groups Directory">Groups</a>';
					$crumbs[] 	= 'Create Group';

				elseif ( bp_is_group() ) :

					// Default entries
					$title 		=  bp_get_group_name();
					$desc		= SITENAME . ' guild profile for ' . bp_get_group_name();
					$classes	= array_diff( $classes , array( 'page' , 'page-template-default' ) );
					$crumbs[] 	= '<a href="'. bp_get_groups_directory_permalink() .'" title="Groups Directory">Groups</a>';

					// Group Profile Home
					if ( bp_is_group_home() ) :
						$title		= $title . $sep . 'Profile';
						$crumbs[] 	= bp_get_group_name();

					// Advanced Component
					else :

						// Link back to group profile
						$crumbs[] 	= '<a href="' . bp_get_group_permalink() . '" title="Return to Group Profile">' . bp_get_group_name() . '</a>';
							
						// Members
						if ( bp_is_group_members() ) :
							$title		= $title . $sep . 'Members';
							$crumbs[]	= 'Members';

						// Activity
						elseif ( bp_is_group_activity() ) :
							$title		= $title . $sep . 'Activity';
							$crumbs[]	= 'Activity';

						// Invites
						elseif ( bp_is_group_invites() ) : 
							$title		= $title . $sep . 'Invitations';
							$crumbs[]	= 'Invitations';

						// Admin
						elseif ( bp_is_group_admin_page() ) :
							$title		= $title . $sep . 'Admin';
							$crumbs[]	= 'Admin';
						
						// Forum
						else :

							// Forum Root
							if ( NULL == bp_action_variable() ) :
								$title		= $title . $sep . 'Forum';
								$crumbs[]	= 'Forum';


							// Sub-Component
							else :
								$crumbs[] = '<a href="'. bp_get_group_permalink() .'forum/" title="Group Forum">Forum</a>';
							
								// Retrieve topic information from the database
								global $bp;
								global $wpdb;

								// Single Topic
								if ( bp_is_action_variable( 'topic' , 0 ) ) :
									
									// Get the topic
									$topic = $wpdb->get_row( $wpdb->prepare( 
										"SELECT post_title AS title, post_name AS url
										FROM $wpdb->posts 
										WHERE post_name = %s",
										$bp->action_variables[1] 
									));

									$title		= $topic->title;
									$crumbs[] 	= $topic->title;


								// Replies
								elseif ( bp_is_action_variable( 'reply' , 0 ) ) :

									// Get the reply parent topic	
									$topic = $wpdb->get_row( $wpdb->prepare( 
										"SELECT post_title AS title, post_name AS url
										FROM $wpdb->posts 
										WHERE ID = ( 
											SELECT post_parent
											FROM $wpdb->posts
											WHERE post_name = %s 
										)", $bp->action_variables[1] 
									));

									$title		= $topic->title;
									$crumbs[] 	= $topic->title;
								endif;

								// Topic and Reply Edits
								if ( bp_is_action_variable( 'edit' , 2 ) ) :
									$crumbs[] = 'Edit';
								endif;
							endif;

						endif;
					endif;
				endif;


			// Directories
			elseif ( bp_is_directory() ) :	
				
				// Sitewide Activity
				if ( bp_is_activity_component() ) :
					$title		= SITENAME . ' Sitewide Activity Feed';
					$desc		= 'A listing of all recent activity happening throughout the ' . SITENAME . ' community.';
					$crumbs[] 	= 'Sitewide Activity';

				// Members Directory
				elseif ( bp_is_members_component() ) :
					$title		= SITENAME . ' Members Directory';
					$desc		= 'A listing of all registered members in the ' . SITENAME . ' community.';
					$crumbs[] 	= 'Members Directory';

				// Groups Directory
				elseif ( bp_is_groups_component() ) :
					$title		= SITENAME . ' Guilds Directory';
					$desc		= 'A directory listing of guilds active within in the ' . SITENAME . ' community.';
					$crumbs[] 	= 'Guilds Directory';
				endif;

			// Registration
			elseif ( bp_is_register_page() ) :
				$title		= SITENAME . ' User Registration';
				$desc 		= "Register to join the " . SITENAME . " community.";
				$crumbs[] 	= "User Registration";

			// Activation
			elseif ( bp_is_activation_page() ) :
				$title		= SITENAME . ' Account Activation';
				$desc 		= "Activate a pending " . SITENAME . " user account.";
				$crumbs[] 	= "Account Activation";

			endif;

		/*--------------------------------------------
			BBPRESS CONTEXT
		---------------------------------------------*/
		elseif ( class_exists( 'bbPress' ) && is_bbpress() ) :
			
			// bbPress Defaults
			$classes[]		= 'bbpress';
			$classes[]		= 'forums';
			$crumbs[] 		= bbp_is_forum_archive() ? "Forums" : '<a href="' . get_post_type_archive_link( 'forum' ) . '">Forums</a>';
		
			
			// Main Forum Archive
			if ( bbp_is_forum_archive() ) :
				$title 		= SITENAME . " Forums";
				$desc 		= "Get involved in the community on the " . SITENAME . " forums.";
				
			
			// Recent Topics
			elseif ( bbp_is_topic_archive() ) :
				$title 		= "Recent Topics in the " . SITENAME . " Forums";
				$desc 		= "Browse a list of the most recent topics in the " . SITENAME . " Forums.";
				$crumbs[] 	= "Recent Topics";
				
			
			// Single Forum
			elseif ( bbp_is_single_forum() ) :
				$title 		= $object->post_title;
				$desc 		= $object->post_content;
				
				// Loop through parent forums
				$parent_id 	= bbp_get_forum_parent_id( $id );	
				if ( 0 != $parent_id ) $crumbs = array_merge( $crumbs, $this->parent_crumbs( $parent_id ) );
				$crumbs[]	= $object->post_title;


			// Single Topic
			elseif ( bbp_is_single_topic() ) :
				$title 		= $object->post_title;
				$desc		= bbp_get_topic_excerpt( $id );
				$crumbs 	= array_merge( $crumbs, $this->parent_crumbs( bbp_get_topic_forum_id( $id ) ) );
				$crumbs[] 	= $object->post_title;
				
			// Edit Topic
			elseif ( bbp_is_topic_split() || bbp_is_topic_merge() || bbp_is_topic_edit() ) :
				$title 		= 'Edit Topic' . $sep . $object->post_title;
				$desc		= bbp_get_topic_excerpt( $id );
				$crumbs 	= array_merge( $crumbs, $this->parent_crumbs( $id ) );

				// Tag the specific task
				if ( bbp_is_topic_split() )	:		$crumbs[] = 'Split Topic';
				elseif ( bbp_is_topic_merge() )	:	$crumbs[] = 'Merge Topic';
				elseif ( bbp_is_topic_edit() )	:	$crumbs[] = 'Edit Topic';
				endif;
			
			// Edit Reply
			elseif ( bbp_is_reply_edit() ) :
				$title 		= 'Edit Reply' . $sep . bbp_get_reply_topic_title( $id );
				$desc		= bbp_get_reply_excerpt( $id );		
				$crumbs 	= array_merge( $crumbs, $this->parent_crumbs( bbp_get_reply_topic_id( $id ) ) );	
				$crumbs[] 	= 'Edit Reply';
			endif;	

		/*--------------------------------------------
			WORDPRESS CONTEXT
		---------------------------------------------*/
		else :

			// Singular Posts and Pages
			if ( is_singular() ) :
				$title		= $object->post_title;
				$desc		= get_post_meta( $id , 'description' , true );
				
				// If no description is found, use an excerpt
				if ( empty( $desc ) )
					$desc	= get_post_field( 'post_excerpt' , $id );
				
				// Check for custom template
				$template = get_post_meta( $id , "_wp_{$object->post_type}_template", true );
				if ( '' != $template ) {
					$template 	= str_replace( array ( "{$object->post_type}-template-", "{$object->post_type}-" ), '', basename( $template , '.php' ) );
					$classes[] 	= "{$template}-template";
				}

				// Generate breadcrumbs by post type
				switch( $object->post_type ) {
				
					// Single Posts 
					case 'post' :
						
						// Is the post in a category?
						$categories = get_the_category();
						if ( $categories ) {

							// Start with the first category
							$term = $categories[0];
					
							// If the category has a parent, add it to the trail. 
							if ( 0 != $term->parent ) 
								$crumbs = array_merge( $crumbs, $this->parent_crumbs( $term->parent, 'category' ) );

							// Add the category archive link to the trail. 
							$crumbs[] = '<a href="' . get_term_link( $term ) . '" title="' . esc_attr( $term->name ) . '">' . $term->name . '</a>';
						}

						// Does the post have an ancestor?
						if ( $object->post_parent ) 
							$crumbs = array_merge( $crumbs, $this->parent_crumbs( $object->post_parent ) );

						// Editing a comment on this post
						if ( is_comment_edit() ) :
							$crumbs[] = '<a href="' . get_permalink() . '" title="Return to article">' . get_the_title() . '</a>';
							$crumbs[] = 'Edit Comment';	
					
						// Reading the post
						else :
							$crumbs[] = get_the_title();
						endif; 
					break;


					// Pages
					case 'page' :
						
						// Does the page have an ancestor?
						if ( $object->post_parent ) 
							$crumbs = array_merge( $crumbs, $this->parent_crumbs( $object->post_parent ) );
						
						// Otherwise, viewing the page
						$crumbs[] = get_the_title();
					break;
				}
			
			// Archives
			elseif ( is_archive() ) :

				// Category Archives
				if ( is_category() ) :
					$crumbs[] 	= 'Category';
				
					// If the category has a parent, add it to the trail. 
					if ( $object->parent != 0 ) 
						$crumbs = array_merge( $crumbs, $this->trail_parents( $object->parent ) );
					
					// Finish up with the term name
					$crumbs[] = $object->name;
				
				// Author Archive 
				elseif ( is_author() ) :
					$title		= 'Author Archive' . $sep . $object->display_name;
					$desc		= 'An archive of articles written by ' . $object->display_name;
					$crumbs[] 	= 'Author';
					$crumbs[] 	= $object->display_name;

				// Advanced Search Page
				elseif ( is_search() ) : 
					$title 		= SITENAME . " Advanced Search";
					$desc		= "Search for a variety of content types throughout " . SITENAME;
					$crumbs[] 	= 'Advanced Search';
					$classes[] 	= 'page';
				endif;

			// 404
			elseif ( is_404() ) :
				$title 			= "Error" . $sep . "Page Not Found";
				$desc 			= "Sorry, but this page does not exist, or is not accessible at this time.";
				$classes[] 		= 'page';
				$crumbs[] 		= '404 Page Not Found';	
			endif;

		endif;

		/*--------------------------------------------
			RETURN DATA
		---------------------------------------------*/
		$this->title		= html_entity_decode( $title );
		$this->description	= html_entity_decode( $desc );
		$this->classes 		= $classes;
		$this->crumbs		= $crumbs;
	}

	
	/**
	 * Get the URL of the currently viewed page
	 * @version 2.0
	 */
	function get_url() {
		$url  = ( isset( $_SERVER['HTTPS']) && $_SERVER['HTTPS'] == 'on' ) ? 'https://'.$_SERVER['SERVER_NAME'] :  'http://'.$_SERVER['SERVER_NAME'];
		$url .= $_SERVER["REQUEST_URI"];
		$this->url = $url;
	}

	/**
	 * Parent breadcrumb recursor
	 */
	function parent_crumbs( $post_id ) {
	
		// Setup empty trail
		$crumbs 	= array();
		
		// Verify we have something to work with 
		if ( empty( $post_id ) ) return $crumbs;
		
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
			$crumbs = array_reverse( $parents );
			
		// Return the trail
		return $crumbs;
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
	echo implode( ' ' , array_unique( apoc()->classes ) );
}

 