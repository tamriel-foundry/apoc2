<?php 
/**
 * Apocrypha Theme User Functions
 * Andrew Clayton
 * Version 2.0
 * 9-12-2014
 *
 * Contents:
 * 1.0 - Apoc User Class
 * 2.0 - Apoc Avatar Class
 * 3.0 - Edit Profile Class
 * 4.0 - Post Counts
 */
 
// Exit if accessed directly
if ( !defined( 'ABSPATH' ) ) exit;

/*--------------------------------------------------------------
1.0 - APOC USER CLASS
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
		$this->server	= isset( $meta['server'] ) 				? $meta['server'] : NULL;
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
		$this->servname	= $this->user_server( $this->server );
		$this->rank		= $this->user_rank( $this->posts );
		$this->title	= $this->user_title( $user_id );
		$this->rclass 	= $this->race_class();

		// Get the user profile
		$this->profile	= bp_core_get_user_domain( $user_id );
		$grammar		= ( substr( $this->fullname , -1) == "s" ) ? $this->fullname . '\'' : $this->fullname . '\'s';
		$this->link 	= '<a class="member-name" href="' . $this->profile . '" title="Visit ' . $grammar . ' user profile" target="_blank">' . $this->fullname . '</a>';

		// Get additional data on user profile pages
		if ( 'profile' == $this->context ) {	

			// Get additional data from retrieved meta
			$this->prefrole		= isset( $meta['prefrole'] ) ? $meta['prefrole'] : NULL;
			$this->first_name	= isset( $meta['first_name'] ) ? $meta['first_name'] : "";
			$this->last_name	= isset( $meta['last_name'] ) ? $meta['last_name'] : "";
			$this->charname		= implode( ' ' , array( $this->first_name , $this->last_name ) );
			$this->warnings		= isset( $meta['infraction_history'] ) ? $this->warnings( $meta['infraction_history'] ) : NULL;
			$this->mod_notes	= isset( $meta['moderator_notes'] ) ? $this->notes( $meta['moderator_notes'] ) : NULL;

			// Get additional data from user object
			$user				= get_userdata( $this->id );
			$this->nicename		= $user->user_nicename;
			$this->regdate 		= strtotime( $user->user_registered );

			// Generate profile HTML sections
			$this->byline		= $this->byline();

			// Populate volunteered contact methods
			$this->contacts		= array();
			$contacts 			= array( 'esoacct' , 'twitter' , 'facebook' , 'gplus' , 'steam' , 'youtube' , 'twitch' , 'oforums' );
			foreach( $contacts as $c ) {
				if ( isset( $meta[$c] ) ) $this->contacts[$c] = $meta[$c];
			}
			if ( !empty( $user->user_url ) ) $this->contacts['user_url'] = $user->user_url;
		}
	}
	
	/**
	 * Format the output user block
	 */	
	function format_data( $context ) {
	
		// Setup global block features
		$block		= $this->link;
		$block		.= $this->title;	
		
		// Prepare to fetch an avatar
		$avatar_type = $this->size > 100 ? 'full' : 'thumb';
		$avatar_args = array( 
			'user_id' 	=> $this->id , 
			'alliance' 	=> $this->faction , 
			'race' 		=> $this->race , 
			'type' 		=> $avatar_type , 
			'size' 		=> $this->size,
			'link'		=> true,
			'url'		=> $this->profile,
		);
		
		// Do some things differently depending on context
		switch( $context ) {
		
			case 'directory' :
				if ( isset( $this->guild ) )
				$block		.= '<p class="user-guild ' . strtolower( str_replace( ' ' , '-' , $this->guild ) ) . '">' . $this->guild . '</p>';
				else
				$block		.= $this->rclass;
				break;
		
			case 'reply' :
				$block		.= '<p class="user-post-count">Total Posts: ' . $this->posts['total'] . '</p>';
				$block		.= $this->rclass;
				$block		.= ( isset( $this->guild ) ) ? '<p class="user-guild ' . strtolower( str_replace( ' ' , '-' , $this->guild ) ) . '">' . $this->guild . '</p>' : '' ;
				$block		.= $this->expbar();
				break;
					
			case 'profile' :
				$avatar_args['link'] = false;
				$block		.= '<p class="user-post-count">Total Posts: ' . $this->posts['total'] . '</p>';
				$block		.= $this->rclass;
				$block		.= ( isset( $this->guild ) ) ? '<p class="user-guild ' . strtolower( str_replace( ' ' , '-' , $this->guild ) ) . '">' . $this->guild . '</p>' : '' ;
				$block		.= $this->expbar();
				break;
		}
		
		// Prepend the avatar
		$avatar			= new Apoc_Avatar( $avatar_args );
		$this->avatar	= $avatar->avatar;
		
		// Add the html to the object
		$this->block 	= $this->avatar . '<div class="user-meta">' . $block . '</div>';
	}

	/**
	 * Decode server tag
	 */
	function user_server( $server ) {

		// Declare server translations
		$servers = array(
			'pcna' => 'PC North America',
			'pceu' => 'PC Europe',
			'xbox' => 'Xbox One',
			'ps4'  => 'PlayStation 4'
		);

		// Decode the tag
		foreach( $servers as $tag => $name ) {
			if ( $server === $tag ) return $name;
		}

		// Else return null
		return NULL;
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

	/* 
	 * Get a user's declared race and class
	 * @version 2.0
	 */
	function race_class() {
	
		// Set it up
		$separator	= '';
		$faction	= $this->faction;
		$race 		= $this->race;
		$class 		= $this->class;
	
		// Make sure we have info to use
		if ( '' == $race && '' == $class && '' == $faction )
			return false;
	
		// Otherwise, display what we have		
		if ( '' == $race ) $race = $faction;
		if ( $race != '' ) $separator = ' ';
		$allegiance = '<p class="user-allegiance ' . $faction . '">' . ucfirst( $race ) . $separator . ucfirst( $class ) . '</p>';
		return $allegiance;
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

	/**
	 * Display user signature
	 */
	function signature() {
		if ( '' != $this->sig )
			echo '<footer class="user-signature double-border top"><div class="signature-content">' . do_shortcode( $this->sig ) . '</div></footer>';
	}
	

	/* 
	 * Generate a byline for the user profile with their allegiance information
	 * @version 2.0
	 */
	function byline() {
	
		// Get the data
		$faction 	= $this->faction;
		$race 		= $this->race;
		$class		= ucfirst( $this->class );
		$name		= $this->fullname;

		// Obey proper grammar
		if ( '' == $race ) 
			$grammar 	= 'a sworn ';
		elseif ( in_array( $race , array('altmer','orc','argonian','imperial' ) ) )
			$grammar 	= 'an ' . ucfirst($race);
		else $grammar 	= 'a ' 	. ucfirst($race);
			
		// Generate the byline
		switch( $faction ) {
			case 'aldmeri' :
				if ( $class == '' ) $class = 'champion';
				$byline = $name . ' is ' . $grammar . ' ' . $class . ' of the Aldmeri Dominion.';
				break;
			case 'daggerfall' :
				if ( $class == '' ) $class = 'protector';
				$byline = $name . ' is ' . $grammar . ' ' . $class . ' of the Daggerfall Covenant.';
				break;
			case 'ebonheart' :
				if ( $class == '' ) $class = 'vanguard';
				$byline = $name . ' is ' . $grammar . ' ' . $class . ' of the Ebonheart Pact.';
				break;
			default : 
				$class = 'mercenary';
				$byline = $name . ' is a ' . $class . ' with no political allegiance.';
				break;
		}
		
		// Return the byline
		return $byline;
	}


	/** 
	 * Display the user's contact information
	 * @version 2.0
	 */
	function contacts() {
	
		// Get the data
		$contacts = array_filter( $this->contacts );

		// Display the list
		echo '<ul class="user-contact-list">' ;

		// No contact information provided
		if ( empty( $contacts ) ) {
			echo '<li><i class="fa fa-eye-slash fa-fw"></i>No contact information provided</li>';
			return;
		}

		// Contacts found
		if ( isset( $contacts['esoacct'] ) )
			echo '<li><span><i class="fa fa-user fa-fw"></i>ESO Account:</span> @'  . $contacts['esoacct'] . '</li>' ;
		if ( isset( $contacts['user_url'] ) )
			echo '<li><span><i class="fa fa-globe fa-fw"></i>Website:</span><a href="' . $contacts['user_url'] . '" target="_blank">' . $contacts['user_url'] . '</a></li>' ;
		if ( isset( $contacts['twitter'] ) )
			echo '<li><span><i class="fa fa-twitter fa-fw"></i>Twitter:</span><a href="http://twitter.com/' . $contacts['twitter'] . '" target="_blank">' . $contacts['twitter'] . '</a></li>' ;
		if ( isset( $contacts['facebook'] ) )
			echo '<li><span><i class="fa fa-facebook fa-fw"></i>Facebook:</span><a href="http://facebook.com/' . $contacts['facebook'] . '" target="_blank">' . $contacts['facebook'] . '</a></li>' ;		
		if ( isset( $contacts['gplus'] ) )
			echo '<li><span><i class="fa fa-google-plus fa-fw"></i>Google+:</span><a href="http://plus.google.com/' . $contacts['gplus'] . '" target="_blank">' . $contacts['gplus'] . '</a></li>' ;
		if ( isset( $contacts['steam'] ) )
			echo '<li><span><i class="fa fa-steam fa-fw"></i>Steam ID:</span><a href="http://steamcommunity.com/id/' . $contacts['steam'] . '" target="_blank">' . $contacts['steam'] . '</a></li>' ;
		if ( isset( $contacts['youtube'] ) )
			echo '<li><span><i class="fa fa-youtube fa-fw"></i>YouTube:</span><a href="http://www.youtube.com/user/' . $contacts['youtube'] . '" target="_blank">' . $contacts['youtube'] . '</a></li>' ;
		if ( isset( $contacts['twitch'] ) )
			echo '<li><span><i class="fa fa-twitch fa-fw"></i>TwitchTV:</span><a href="http://www.twitch.tv/' . $contacts['twitch'] . '" target="_blank">' . $contacts['twitch'] . '</a></li>' ;
		if ( isset( $contacts['oforums'] ) )
			echo '<li><span><i class="fa fa-circle-o fa-fw"></i>ESO Forums:</span><a href="http://forums.elderscrollsonline.com/profile/' . $contacts['oforums'] . '" target="_blank">' . $contacts['oforums'] . '</a></li>' ;
		echo '</ul>' ;
	}

	/**
	 * Retrieves the user's warnings and current warning level from the database
	 * @version 2.0
	 */
	function warnings( $warnings ) {
	
		// Setup an array
		$infractions = array();
	
		// Grab the infractions
		$infractions['history'] = maybe_unserialize( $warnings );
		
		// Get an accurate count
		$level = 0;
		if ( !empty( $infractions['history'] ) ) {
			foreach ( $infractions['history'] as $id => $warning ) {
				$level += $warning['points'];	
			}
		}
		$infractions['level'] = min( $level , 5 );
		return $infractions;
	}
	
	/**
	 * Retrieves the user's moderator notes and notes count
	 * @version 2.0
	 */
	function notes( $mod_notes ) {
	
		// Setup an array
		$notes = array();
	
		// Grab the infractions
		$notes['history'] = maybe_unserialize( $mod_notes );
		
		// Get an accurate count
		$notes['count'] = count( $notes['history'] );	
		return $notes;
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
			if ( $this->link ) {
				
				// Maybe retrieve the profile URL
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
	3.0 - EDIT PROFILE CLASS
--------------------------------------------------------------*/
class Edit_Profile extends Apoc_User {

	/** 
	 * Constructor function for Edit Profile class
	 * Inherits the arguments $user_id and $context from the Apoc_User class
	 * Checks to see if the edit form has been submitted, if so, update the form
	 */
	function __construct( $user_id = 0 ) {
	
		// Construct the user
		parent::__construct( $user_id , 'profile' );
	
		// Was the form submitted?
		if ( 'POST' == $_SERVER['REQUEST_METHOD'] && !empty( $_POST['action'] ) && $_POST['action'] == 'update-user' )
			$this->save( $user_id );		
	}

	/** 
	 * Update user profile fields
	 */
	function save( $user_id ) {

		// Check the nonce
		if ( !wp_verify_nonce( $_POST['edit_user_nonce'] , 'update-user' ) )
			exit;

		// Declare the usermeta fields and their sanitization treatments
		$meta 	= array(
			'server'		=> array ( $this->server 		, 'trim' ),
			'first_name'	=> array ( $this->first_name	, 'esc_attr' ),
			'last_name'		=> array( $this->last_name		, 'esc_attr' ),
			'faction'		=> array( $this->faction		, 'trim' ),
			'race'			=> array( $this->race			, 'trim' ),
			'playerclass'	=> array( $this->class			, 'trim' ),
			'prefrole'		=> array( $this->prefrole		, 'trim'),
			'guild'			=> array( $this->guild			, 'esc_attr' ),
			'description'	=> array( $this->bio			, 'apoc_custom_kses' ),
			'signature'		=> array( $this->sig			, 'apoc_custom_kses' ),
			'esoacct'		=> array( $this->contacts['esoacct']	, 'esc_attr' ),	
			'twitter'		=> array( $this->contacts['twitter']	, 'esc_attr' ),
			'facebook'		=> array( $this->contacts['facebook']	, 'esc_attr' ),
			'gplus'			=> array( $this->contacts['gplus']		, 'esc_attr' ),
			'youtube'		=> array( $this->contacts['youtube']	, 'esc_attr' ),
			'steam'			=> array( $this->contacts['steam']		, 'esc_attr' ),
			'twitch'		=> array( $this->contacts['twitch']		, 'esc_attr' ),
			'oforums'		=> array( $this->contacts['oforums']	, 'esc_attr' ),
		);

		// Declare the users table fields and their sanitization treatments
		$users	= array( 
			'user_url'		=> array( $this->contacts['user_url']	, 'esc_attr' ),
		);

		// Check each usermeta for updates
		foreach( $meta as $field => $values ) {

			// Get the value and its treatment
			$original 	= $values[0];
			$treatment 	= $values[1];

			// The field has been changed
			if ( ( $_POST[$field] != "" ) && ( $_POST[$field] != $original ) )
				update_user_meta( $user_id	, $field , call_user_func( $treatment , $_POST[$field] ) );

			// Otherwise if the value was deleted
			elseif ( $_POST[$field] == "" )
				delete_user_meta( $user_id	, $field  )	;
		}

		// Check the users table for updates
		foreach( $users as $field => $values ) {

			// Get the value and its treatment
			$original 	= $values[0];
			$treatment 	= $values[1];

			// The field has been changed
			if ( $_POST[$field] != $original )
				wp_update_user( array ( 
					'ID' => $user_id , 
					$field => call_user_func( $treatment , $_POST[$field] ) 
				) ) ;
		}

		// Allow plugins to save additional fields
		do_action('edit_user_profile_update', $user_id );	
			
		// Add a success message
		bp_core_add_message( 'Your user profile was successfully updated!' );

		// Redirect back to the profile
		wp_redirect( bp_displayed_user_domain() );	
	}
}


/*--------------------------------------------------------------
	4.0 - POST COUNTS
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
	if ( 'post' == $post->post_type || 'page' == $post->post_type )
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
    $count = $wpdb->get_var('SELECT COUNT(ID) FROM ' . $wpdb->posts . ' WHERE post_type IN ( "post" , "page" ) AND post_author = ' . $user_id . ' AND post_status = "publish"' );
	$count = $count > 0 ? $count : 0;
    return $count;
}



	