<?php 
/**
 * Apocrypha Theme User Functions
 * Andrew Clayton
 * Version 2.0
 * 9-12-2014
 */
 
// Exit if accessed directly
if ( !defined( 'ABSPATH' ) ) exit;

/*--------------------------------------------------------------
1.0 - USER CLASS
--------------------------------------------------------------*/
class Apoc_User {

	// The context in which this user is being displayed
	public $context;
	
	// The HTML member block
	public $block;

	/**
	 * Constructs and computes user information
	 */	
	function __construct( $user_id = 0 , $context = 'reply' , $avatar_size = 100 ) {
	
		// Set the context
		$this->context = $context;
		$this->size = $avatar_size;
		
		// Get data for the user
		$this->get_data( $user_id );
		
		// Format data depending on the context
		$this->format_data( $context );
	}
	
	/**
	 * Gets user data regarding the particular user
	 */	
	function get_data( $user_id ) {
		
		// Get all meta entries for a user
		$meta = array_map( function( $a ){ return $a[0]; }, get_user_meta( $user_id ) );
		
		// The table prefix is needed to obtain some of the meta
		global $wpdb;
		$prefix = $wpdb->prefix;
		
		// Add meta to the class
		$this->id		= $user_id;
		$this->fullname = $meta['nickname'];
		$this->roles	= array_keys( unserialize( $meta[ $prefix . 'capabilities' ] ) );
		$this->status	= isset( $meta['bp_latest_update'] ) 	? maybe_unserialize( $meta['bp_latest_update'] ) : NULL;
		$this->faction	= isset( $meta['faction'] ) 			? $meta['faction'] : NULL;
		$this->race		= isset( $meta['race'] ) 				? $meta['race'] : NULL;
		$this->class	= isset( $meta['playerclass'] ) 		? $meta['playerclass'] : NULL;
		$this->posts	= isset( $meta['post_count'] ) 			? maybe_unserialize( $meta['post_count'] ) : array( 'total' => 0 );
		$this->guild	= isset( $meta['guild'] ) 				? $meta['guild'] : NULL ;
		$this->bio		= isset( $meta['description'] ) 		? do_shortcode( $meta['description'] ) : NULL;
		$this->sig		= isset( $meta['signature'] ) 			? $meta['signature'] : NULL;
		$this->donor	= isset( $meta['donation_amount'] ) 	? $meta['donation_amount'] : NULL;
		
		// If the post count is not yet in the database, build it
		if ( $user_id > 0 && empty( $this->posts ) ) apoc_update_post_count( $user_id );
		
		// Get some derived data
		$this->rank		= $this->user_rank( $this->posts );
		$this->title	= $this->user_title( $user_id );

		// Get the user profile
		$this->profile	= bp_core_get_user_domain( $user_id );
		$grammar		= ( substr( $this->fullname , -1) == "s" ) ? $this->fullname . '\'' : $this->fullname . '\'s';
		$this->link 	= '<a href="' . $this->profile . '" title="Visit ' . $grammar . ' user profile" target="_blank">' . $this->fullname . '</a>';
	}
	
	/**
	 * Format the output user block
	 */	
	function format_data( $context ) {
	
		// Setup the basic info block
		$block		= '<a class="member-name" href="' . $this->profile . '" title="View ' . $this->fullname . ' User Profile">' . $this->fullname . '</a>';
		$block		.= $this->title;	
		//$block		.= $this->allegiance();
		$block		.= ( isset( $this->guild ) ) ? '<p class="user-guild ' . strtolower( str_replace( ' ' , '-' , $this->guild ) ) . '">' . $this->guild . '</p>' : '' ;
		
		// Prepare to fetch an avatar
		$avatar_type = $this->size > 100 ? 'full' : 'thumb';
		$avatar_args = array( 'user_id' => $this->id , 'alliance' => $this->faction , 'race' => $this->race , 'type' => $avatar_type , 'size' => $this->size );
		
		// Do some things differently depending on context
		switch( $context ) {
		
			case 'directory' :
				$block		= '<div class="directory-member-meta">' . $block . '</div>';
				break;
		
			case 'reply' :
				$block		.= '<p class="user-post-count">Total Posts: ' . $this->posts['total'] . '</p>';
				$block		.= $this->expbar();
				break;
					
			case 'profile' :
				break;
		}
		
		// Prepend the avatar
		$avatar			= new Apoc_Avatar( $avatar_args );
		$avatar			= '<a class="member-avatar" href="' . $this->profile . '" title="View ' . $this->fullname . '&apos;s Profile">' . $avatar->avatar . '</a>';
		$this->avatar 	= $avatar;
		$block			= $avatar . $block;
		
		// Add the html to the object
		$this->block 	= $block;
	}
	
	/** 
	 * Assign default ranks based on total post count
	 */
	function user_rank( $posts ) {
	
		// Get the userid
		$user_id = $this->id;
		
		// Make sure it's a valid user
		if ( 0 == $user_id ) return false;
		
		// Set up the array of ranks
		$ranks = array(
			0 => array(	'min_posts' => 0 	, 'next_rank' => 10 	, 'title' => 'Scamp' 		),
			1 => array(	'min_posts' => 10 	, 'next_rank' => 25 	, 'title' => 'Novice' 		),
			2 => array(	'min_posts' => 25	, 'next_rank' => 50 	, 'title' => 'Apprentice' 	),
			3 => array(	'min_posts' => 50	, 'next_rank' => 100	, 'title' => 'Journeyman' 	),	
			4 => array(	'min_posts' => 100	, 'next_rank' => 250	, 'title' => 'Adept' 		),
			5 => array(	'min_posts' => 250	, 'next_rank' => 500	, 'title' => 'Expert'		),
			6 => array( 'min_posts' => 500	, 'next_rank' => 1000	, 'title' => 'Master' 		),
			7 => array( 'min_posts' => 1000	, 'next_rank' => 2500	, 'title' => 'Grandmaster' 	),
			8 => array( 'min_posts' => 2500	, 'next_rank' => 5000	, 'title' => 'Hero' 		),
			9 => array( 'min_posts' => 5000	, 'next_rank' => 10000	, 'title' => 'Legend' 		),
			10 => array( 'min_posts' => 10000, 'next_rank' => 20000	, 'title' => 'Divine' 		),
		);
		
		// Iterate through the ranks, determining where the user's postcount falls
		$i=0;
		while ( $posts['total'] >= $ranks[$i]['next_rank'] ) { 
			$i++; 
		}
		
		// Return a rank array
		$user_rank = array(
			'current_rank' 	=> $ranks[$i]['min_posts'],
			'next_rank' 	=> $ranks[$i]['next_rank'],
			'rank_title'	=> $ranks[$i]['title']
		);
		return $user_rank;
	}
	
	
	/** 
	 * Determine the user's title
	 */
	function user_title( $user_id ) {
				
		// Bail if it's a guest
		if ( 0 == $user_id ) {
			return '<p class="user-title guest">Guest</p>';
		}
			
		// Otherwise ,get the user's site roles
		$site_role 	= $this->roles[0];
		$forum_role = $this->roles[1];
			
		// Assign special titles
		if ( 'administrator' == $site_role ) :
			$title = 'Administrator';
		elseif ( 'bbp_moderator' == $forum_role || 'bbp_keymaster' == $forum_role ) :
			$title = 'Moderator'; 
		elseif ( 'banned' == $site_role ) :
			$title = 'Banned';
		
		// Otherwise, use the rank title
		else :
			$title = $this->rank['rank_title'];
		endif;
		
		// Display the title
		$role_class = strtolower( str_replace( " " , "-" , $title ) );
		return '<p class="user-title ' . $role_class . '">' . $title . '</p>';
	}
	
	/**
	 * Display user post experience bar
	 */
	function expbar() {
	
		// Get the counts
		$current	= $this->rank['current_rank'];
		$next		= $this->rank['next_rank'];
		$total		= $this->posts['total'];
		
		// Calculate the exp
		$percent 	= ( $total - $current ) / ( $next - $current );
		$percent 	= round( $percent , 2) * 100;
		$to_ding 	= $next - $total;
		$tip 		= $to_ding . ' more until next rank!';		

		// Display the bar
		$bar = '<div class="user-exp" title="' . $tip . '"><div class="user-expbar" style="width:' . $percent . '%;"></div></div>';
		return $bar;
	}
}

/*--------------------------------------------------------------
2.0 - AVATAR CLASS
--------------------------------------------------------------*/
class Apoc_Avatar {

	// Declare properties
	public $avatar;
	
	/** 
	 * Constructor function for Apoc Avatar class
	 */	
	function __construct( $args = array() ) {
	
		// Setup default arguments
		$defaults = array(
			'user_id'		=> 0,
			'alliance'		=> '',
			'race'			=> '',
			'type'			=> 'thumb',
			'size'			=> 100,
			'link'			=> false,
			'url'			=> '',
			);
		
		// Parse with supplied params
		$args = wp_parse_args( $args , $defaults );

		// Extract arguments as class properties
		foreach ( $args as $prop => $attr ) {
			$this->$prop = $attr;
		}
		
		// Get the avatar
		$this->get_avatar();	
	}
	
	/**
	 * Build the avatar using available data
	 */
	function get_avatar() {
	
		// If BuddyPress does not exist, fallback to WordPress
		if ( !class_exists( 'BuddyPress' ) ) {
			
			// Define our own default
			$avsize		= ( "thumb" == $this->type ) ? 100 : 200;
			$default 	= THEME_URI . "/images/avatars/neutral-" . $avsize . ".jpg";
			
			// Get the avatar
			$this->avatar = get_avatar( $this->user_id , $this->size , $default , false );
			return;
		}
		
		// Display a default for guests
		if( $this->user_id == 0 ) {
			$default		= ( "thumb" == $this->type ) ? BP_AVATAR_DEFAULT_THUMB : BP_AVATAR_DEFAULT;
			$this->avatar	= '<img src="' . $default . '" alt="Guest Avatar" class="avatar" width="' . $this->size . '" height="' . $this->size . '">';
		}
		
		// Use BuddyPress avatar functionality for known users
		else {
	
			// Retrieve the avatar from BuddyPress
			$avatar	= bp_core_fetch_avatar( $args = array (
				'item_id' 		=> $this->user_id,
				'type'			=> $this->type,
				'height'		=> $this->size,
				'width'			=> $this->size,
				'no_grav'		=> true,
			));
			
			// If the user has not uploaded an avatar, choose one using their profile settings
			if ( strrpos( $avatar , BP_AVATAR_DEFAULT ) || strpos( $avatar , BP_AVATAR_DEFAULT_THUMB ) ) {
				$avatar = $this->dynamic_avatar();
			}
			
			// Maybe wrap the image in a profile link
			if ( true === $this->link ) {


				
				// Retrieve the profile URL
				if ( "" == $this->url ) $this->url = bp_core_get_user_domain( $this->user_id );
				
				// Wrap the avatar
				$avatar = '<a class="member-avatar" href="' . $this->url . '" title="View User Profile">' . $avatar . '</a>';
			}
			
			// Return it to the class
			$this->avatar = $avatar;
		}	
	}
	
	/**
	 * Choose an avatar dynamically based on profile settings
	 */	
	function dynamic_avatar() {
		
		// Pick based on race and alliance
		$race 			= $this->race;
		$alliance		= $this->alliance;
		
		// If nothing was passed, try race first
		if ( '' == $race && '' == $alliance ) {
			$race 		= get_user_meta( $this->user_id , 'race' , true );
			$this->race = $race;
		}
		
		// If it's still unset, try alliance next
		if ( '' == $race && '' == $alliance ) {
			$alliance 	= get_user_meta( $this->user_id , 'faction' , true );
			$this->alliance	= $alliance;
		}
		
		// Did anything take?
		$type = ( '' != $race ) ? $race : $alliance;		
		if ( '' == $type ) $type = 'neutral';
		
		// Return the avatar
		$avsize		= ( "thumb" == $this->type ) ? 100 : 200;
		$src 		= trailingslashit( THEME_URI ) . "images/avatars/" . $type . "-" . $avsize . ".jpg";
		$avatar 	= '<img src="' . $src . '" alt="Member Avatar" class="avatar" width="' . $this->size . '" height="' . $this->size . '">';
		return $avatar;		
	}
}

/* Invoker function */
function apoc_get_avatar( $args = array() ) {
	$avatar = new Apoc_Avatar( $args );
	return $avatar->avatar;
}


/*--------------------------------------------------------------
	3.0 - POST COUNTS
--------------------------------------------------------------*/

/** 
 * Update a user's total post count
 * @version 1.0.0
 */
function apoc_update_post_count( $user_id , $type = 'all' ) {

	// Only do this for registered users
	if ( 0 == $user_id ) return false;
	
	// Get existing post count
	$posts = get_user_meta( $user_id , 'post_count' , true );
	
	// If the meta does not yet exist, do a full update
	if ( empty( $posts ) ) {
		$posts = array();
		$type == 'all';
	}

	// Articles
	if ( 'all' == $type || 'articles' == $type ) {
		$articles			= get_user_article_count( $user_id );
		$posts['articles']	= $articles > 0 ? $articles : 0;
	}
		
	// Comments
	if ( 'all' == $type || 'comments' == $type ) {
		$comments 			= get_user_comment_count( $user_id );
		$posts['comments']  = $comments > 0 ? $comments : 0;
	}
	
	// Forum topics and replies
	if ( 'all' == $type || 'forums' == $type ) {
		$topics				= bbp_get_user_topic_count_raw( $user_id ) ;
		$posts['topics'] 	= $topics > 0 ? $topics : 0;
		
		$replies 			= bbp_get_user_reply_count_raw( $user_id ) ;
		$posts['replies'] 	= $replies > 0 ? $replies : 0;
	}
	
	// Compute the total
	$posts['total'] = $posts['articles'] + $posts['topics'] + $posts['replies'] + $posts['comments'];
	
	// Save it
	update_user_meta( $user_id , 'post_count' , $posts );
}

/** 
 * Update the user's post count when a front-page article is published
 * @version 1.0.0
 */
add_action( 'save_post'			, 'apoc_update_article_count' , 10 , 2 );
function apoc_update_article_count( $post_ID , $post ) {
	if ( 'post' == $post->post_type )
		apoc_update_post_count( $post->post_author , $type = 'articles' );
	return;
}
 
/** 
 * Update the user's post count after a topic or reply is trashed or untrashed
 * @version 1.0.0
 */
add_action( 'bbp_new_topic' 	, 'apoc_update_forum_count' );
add_action( 'bbp_new_reply' 	, 'apoc_update_forum_count' );
add_action( 'bbp_trash_reply' 	, 'apoc_update_forum_count' );
add_action( 'bbp_trash_topic' 	, 'apoc_update_forum_count' );
add_action( 'bbp_untrash_reply' , 'apoc_update_forum_count' );
add_action( 'bbp_untrash_topic' , 'apoc_update_forum_count' );
function apoc_update_forum_count( $post_id ) {
	$post 		= get_post( $post_id );
	$user_id 	= $post->post_author;
	apoc_update_post_count( $user_id , $type = 'forums' );
}

/** 
 * Update the user's post count after they submit a new comment
 * @version 1.0.0
 */
add_action( 'comment_post' 		, 'apoc_update_comment_count' );
add_action( 'trashed_comment' 	, 'apoc_update_comment_count' );
add_action( 'untrashed_comment' , 'apoc_update_comment_count' );
function apoc_update_comment_count( $comment_ID ) {
	$comment	= get_comment( $comment_ID );
	$user_id 	= $comment->user_id;
	apoc_update_post_count( $user_id , $type = 'comments' );
}

/** 
 * Count a user's total comments
 * @since 0.1
 */
function get_user_comment_count( $user_id ) {
	global $wpdb;
    $count = $wpdb->get_var('SELECT COUNT(comment_ID) FROM ' . $wpdb->comments . ' WHERE user_id = ' . $user_id . ' AND comment_approved = 1' );
    return $count;
}

/** 
 * Count a user's total articles
 * @since 0.1
 */
function get_user_article_count( $user_id ) {
	global $wpdb;
    $count = $wpdb->get_var('SELECT COUNT(ID) FROM ' . $wpdb->posts . ' WHERE post_type = "post" AND post_author = ' . $user_id . ' AND post_status = "publish"' );
	$count = $count > 0 ? $count : 0;
    return $count;
}



	