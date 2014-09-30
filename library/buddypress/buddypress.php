<?php
/**
 * Apocrypha Theme BuddyPress Functions
 * Andrew Clayton
 * Version 2.0
 * 5-5-2014
*/

// Exit if accessed directly
if ( !defined( 'ABSPATH' ) ) exit;


class Apoc_BuddyPress {


	/**
	 * Construct the BuddyPress Class
	 * @since 2.0
	 */
	function __construct() {
	
		// Constants
		$this->constants();
		
		// Includes
		$this->includes();
		
		// Actions
		$this->actions();
		
		// Filters
		$this->filters();
	}
	
	/**
	 * Define additional BuddyPress constants
	 */
	function constants() {
	
		// Avatar Uploads
		define( 'BP_AVATAR_THUMB_WIDTH'		, 100 );
		define( 'BP_AVATAR_THUMB_HEIGHT'	, 100 );
		define( 'BP_AVATAR_FULL_WIDTH'		, 200 ); 
		define( 'BP_AVATAR_FULL_HEIGHT'		, 200 ); 
		define( 'BP_AVATAR_DEFAULT'			, THEME_URI . '/images/avatars/neutral-200.jpg' );
		define( 'BP_AVATAR_DEFAULT_THUMB'	, THEME_URI . '/images/avatars/neutral-100.jpg' );
		
		// Profile Components
		define( 'BP_DEFAULT_COMPONENT' 		, 'profile' );

		// Define Registration Check
		apoc()->humanity = "khajiit";
	}
	
	/**
	 * Include required BuddyPress functions
	 */	
	function includes() {
	
		// BuddyPress bundled AJAX library
		require_once( BP_PLUGIN_DIR . '/bp-themes/bp-default/_inc/ajax.php' );
	}
	
	
	/**
	 * Modify global BuddyPress actions
	 */
	function actions() {
	
		// Unhook default actions
		remove_action( 'wp_head' 					, 'bp_core_add_ajax_url_js' );		

		// Remove scripts and styles
		remove_action( 'wp_enqueue_scripts' 		, 'bp_core_confirmation_js' );

		// Guild Buttons
		add_action( 'bp_group_header_actions'		,	'bp_group_join_button'	, 	5 	);
		add_action( 'bp_directory_groups_actions'	, 	'bp_group_join_button'			);

		// User registration
		add_action( 'bp_signup_pre_validate'		, array( $this , 'pre_registration' ) 	);
		add_action( 'bp_signup_validate'			, array( $this , 'post_registration' ) );

		// Profile Navigation
		add_action( 'bp_setup_nav'					, array( $this , 'navigation' ) , 99 );
	}
	
	
	/**
	 * Modify global BuddyPress filters
	 */
	function filters() {

		// Activity strip "View" link
		add_filter( 'bp_get_activity_latest_update' , array( $this , 'activity_update') );

		// Activity delete link
		add_filter( 'bp_get_activity_delete_link'	, array( $this , 'activity_delete_button' ) );

		// Add-Remove friend button
		add_filter( 'bp_get_add_friend_button'		, array( $this , 'friend_button' ) );

		// Guild Buttons
		add_filter( 'bp_get_group_join_button' 		, array( $this, 'join_button' ) );
	}



	/*------------------------------------------
		ACTIVITY
	------------------------------------------*/

	/**
	 * Strip "View" link from the end of activity updates
	 */	
	function activity_update( $latest_update ) {


		$latest_update = substr( $latest_update , 0 , strrpos( $latest_update , ' <a href=' , -1 ) );
		return $latest_update;
	}

	function activity_delete_button( $link ) {
		$link = str_replace( array( 'class="button' , 'Delete</a>') , array( 'class="button-dark' , '<i class="fa fa-remove"></i>Delete</a>' ) , $link ); 
		return $link;
	}	

	/*------------------------------------------
		MEMBERS
	------------------------------------------*/
	function friend_button( $button ) {
		
		// Remove the div wrapper
		$button['wrapper'] = false;
		$button['link_class'] = 'button-dark ' . $button['link_class'];

		// Not friends
		if ( in_array( $button['id'] , array( 'pending' , 'awaiting_response' , 'not_friends' ) ) )
			$button['link_text'] = '<i class="fa fa-check"></i>' . $button['link_text'];

		// Friends
		else if ( 'is_friend' === $button['id'] )
			$button['link_text'] = '<i class="fa fa-remove"></i>' . $button['link_text'];

		// Return the button
		return $button;
	}


	/*------------------------------------------
		GROUPS
	------------------------------------------*/
	function join_button( $button ) {
		
		// Remove the div wrapper
		$button['wrapper'] = false;
		$button['link_class'] = 'button-dark ' . $button['link_class'];

		// Button icon
		$button['link_text'] = ( 'leave_group' === $button['id'] ) ? '<i class="fa fa-remove"></i>' . $button['link_text'] : '<i class="fa fa-check"></i>' . $button['link_text'];

		// Return the button
		return $button;

	}


	/*------------------------------------------
		PROFILE
	------------------------------------------*/

	/*
	 * Custom BuddyPress user and group profile navigation
	 */	
	function navigation() {
		global $bp;
		
		// Main navigation
		$bp->bp_nav['profile']['position'] 			= 1;
		$bp->bp_nav['activity']['position'] 		= 2;
		$bp->bp_nav['forums']['position'] 			= 3;
		$bp->bp_nav['friends']['position'] 			= 4;
		$bp->bp_nav['groups']['position'] 			= 5;
		$bp->bp_nav['messages']['position'] 		= 6;
		$bp->bp_nav['notifications']['position'] 	= 7;
		$bp->bp_nav['settings']['position'] 		= 8;
	
		// Profile biography
		$bp->bp_options_nav['profile']['public']['name'] 					= 'Player Biography';
		$bp->bp_options_nav['profile']['change-avatar']['link'] 			= $bp->displayed_user->domain . 'profile/change-avatar';
		if ( !bp_is_my_profile() && !current_user_can( 'edit_users' ) )
		$bp->bp_options_nav['profile']['change-avatar']['user_has_access']	= false;

		// Profile activity
		$bp->bp_options_nav['activity']['just-me']['name'] 					= 'All Activity';
		
		// Profile forums
		$bp->bp_options_nav['forums']['replies']['name'] 					= 'Recent Post Tracker';
		if ( !current_user_can( 'moderate_comments' ) )
		$bp->bp_options_nav['forums']['replies']['user_has_access']			= false;
		$bp->bp_options_nav['forums']['favorites']['name'] 					= 'Favorite Topics';
		
		// Profile settings
		$bp->bp_options_nav['settings']['general']['name'] 					= 'Edit Account Info';
		$bp->bp_options_nav['settings']['notifications']['name'] 			= 'Notification Preferences';
		$bp->bp_options_nav['settings']['profile']['user_has_access'] 		= false;

		// Custom edit profile screen
		bp_core_remove_subnav_item( 'profile' , 'edit' );
		if ( bp_is_my_profile() || current_user_can( 'edit_users' ) ) {
			bp_core_new_subnav_item( array(
				'name' 				=> 'Edit Profile',
				'slug' 				=> 'edit',
				'parent_url' 		=> $bp->displayed_user->domain . $bp->profile->slug . '/',
				'parent_slug' 		=> $bp->profile->slug,
				'screen_function' 	=> array( $this , 'edit_profile_screen' ),
				'position' 			=> 20 ) );
		}

		// Remove activity favorites, because they are dumb
		bp_core_remove_subnav_item( 'activity' , 'favorites' );		
	}


	/*
	 * Profile screen templates
	 */
	function edit_profile_screen() {
		bp_core_load_template( apply_filters( 'apoc_edit_profile_template', 'members/single/profile/edit' ) );
	}
	function infractions_screen() {
		bp_core_load_template( apply_filters( 'apoc_infractions_template', 'members/single/infractions' ) );
	}
	function warning_screen() {
		bp_core_load_template( apply_filters( 'apoc_warning_template', 'members/single/infractions/warning' ) );
	}
	function modnotes_screen() {
		bp_core_load_template( apply_filters( 'apoc_modnotes_template', 'members/single/infractions/notes' ) );
	}
	function guild_activity_screen() {
		bp_core_load_template( apply_filters( 'apoc_guild_activity_template', 'groups/single/home' ) );
	}
	





	/*------------------------------------------
		USER REGISTRATION
	------------------------------------------*/
	/*
	 * Check that custom registration fields have been successfully completed.
	 */
	function pre_registration() {

		// Force the display name and login name to match
		$_POST['field_1'] = $_POST['signup_username'];	
	}

	function post_registration() {

		// Get the BuddyPress object
		global $bp;

		// Prevent special characters or spaces in usernames
		$_POST['signup_username'] = str_replace( " " , "-" , trim( $_POST['signup_username'] ) );

		if ( strpos( $_POST['signup_username'] , "@" ) || strpos( $_POST['signup_username'] , "." ) )
			$bp->signup->errors['signup_username'] = 'Your username may not contain special characters like "@" or "."';

		// Check extra fields
		if ( empty( $_POST['confirm_tos_box'] ) )
			$bp->signup->errors['confirm_tos_box'] = 'You must indicate that you understand the fundamental purpose of the Tamriel Foundry website and community.';

		if ( empty( $_POST['confirm_coc_box'] ) )
			$bp->signup->errors['confirm_coc_box'] = 'You must indicate your acknowledgement of the Tamriel Foundry code of conduct.';
			
		if ( apoc()->humanity != trim( strtolower ( $_POST['confirm_humanity'] ) ) )
			$bp->signup->errors['confirm_humanity'] = 'That is incorrect. Hover on the image if you require a hint.';
	}

}

// Automatically invoke the class
new Apoc_BuddyPress();










/*--------------------------------------------------------------
	GROUPS
--------------------------------------------------------------*/

/**
 * Apocrypha Group Class
 * For use in directories and guild profiles
 */
class Apoc_Group {

	// The context in which this user is being displayed
	public $context;
	
	// The HTML member block
	public $avatar;
	public $block;

	
	/**
	 * Constructs relevant information regarding a TF user 
	 * The scope of information that is added depends on the context supplied
	 */	
	function __construct( $group_id = 0 , $context = 'profile' , $avatar_size = 100 ) {
	
		// Set the context
		$this->context = $context;
		$this->size = $avatar_size;
		
		// Get data for the user
		$this->get_data( $group_id );
		
		// Format data depending on the context
		$this->format_data( $context );
	}
	
	/**
	 * Gets user data for a forum reply or article comment
	 */	
	function get_data( $group_id ) {
		
		// Get the meta data
		$allmeta = wp_cache_get( 'bp_groups_allmeta_' . $group_id, 'bp' );
		if ( false === $allmeta ) {
			global $bp, $wpdb;
			$allmeta = array();
			$rawmeta = $wpdb->get_results( $wpdb->prepare( "SELECT meta_key, meta_value FROM " . $bp->groups->table_name_groupmeta . " WHERE group_id = %d", $group_id ) );
			foreach( $rawmeta as $meta ) {
				$allmeta[$meta->meta_key] = $meta->meta_value;			
			}
			wp_cache_set( 'bp_groups_allmeta_' . $group_id, $allmeta, 'bp' );
		}
		
		// Add data to the class object
		$this->id			= $group_id;
		$this->fullname		= bp_get_group_name();
		$this->domain		= bp_get_group_permalink();
		$this->slug			= bp_get_group_slug();
		$this->guild		= ( $allmeta['is_guild'] == 1 ) 		? 1 : 0;
		$this->type			= $this->type();
		$this->members		= bp_get_group_member_count();
		$this->alliance		= isset( $allmeta['group_faction'] )	? $allmeta['group_faction'] : NULL;
		$this->faction		= $this->allegiance();
		$this->platform		= isset( $allmeta['group_platform'] )	? $allmeta['group_platform'] : NULL;
		$this->region		= isset( $allmeta['group_region'] )		? $allmeta['group_region'] : NULL;
		$this->style		= isset( $allmeta['group_style'] )		? $allmeta['group_style'] : NULL;
		$this->interests	= isset( $allmeta['group_interests'] )	? unserialize( $allmeta['group_interests'] ) : NULL;
		$this->website		= isset( $allmeta['group_website'] )	? $allmeta['group_website'] : NULL;
		
		// Get some extra stuff on user profiles
		if ( $this->context == 'profile' ) {
			$this->byline	= $this->byline();	
			$this->admins 	= $this->admins();
			$this->mods		= $this->mods();
		}
	}
	
	/* 
	 * Get a group's filtered type
	 * @since 0.4
	 */
	function type() {
		$type = bp_get_group_type();
		if ( $this->guild )
			$type = str_replace( 'Group' , 'Guild' , $type );
		return $type;
	}

	/* 
	 * Get a group's declared allegiance
	 */
	function allegiance() {
	
		switch( $this->alliance ) {
			
			case 'aldmeri' :
				$faction = 'Aldmeri Dominion';
				break;
			case 'daggerfall' :
				$faction = 'Daggerfall Covenant';
				break;
			case 'ebonheart' :
				$faction = 'Ebonheart Pact';
				break;
			case 'neutral' :
				$faction = 'Neutral';
				break;
			default :
				$faction = 'Undeclared';
				break;		
		}
		return $faction;
	}

	/* 
	 * Get a group's platform and region preference
	 */	
	function platform() {
		
		// Format platform
		$platform 	= $this->platform;
		if ( $platform ) {
			$sql	 	= array( 'pcmac' , 'xbox' , 'playstation' , 'blank' );
			$formatted	= array( 'PC' , 'Xbox' , 'PS4' , '' );
			$platform	= str_replace( $sql , $formatted , $platform );
		}
		
		// Format region
		$region		= $this->region;
		if ( $region ) {
			$sql		= array( 'NA' , 'EU' , 'OC' , 'blank' , '' );
			$formatted	= array( 'North America' , 'Europe' , 'Oceania' , 'Global' , 'Global' );
			$region		= str_replace( $sql , $formatted , $region );
		}
		
		// Format the tooltip based on what data is available
		if ( $platform != '' && $region != '' )
			$tooltip = implode( ' - ' , array( $platform , $region ) );
		elseif ( $platform == '' && $region != '' ) 
			$tooltip = $region;
		elseif ( $platform != '' && $region == '' ) 
			$tooltip = $platform;
	
		// Return the tip
		$tooltip 	= ( $tooltip ) ? '<p class="group-member-count">' . $tooltip . '</p>' : '';
		return $tooltip;
	}
	
	/* 
	 * Display the group's interest icons
	 */	
	function interest_icons() {
	
		// Get the data
		$interests 	= $this->interests;
		if ( empty ( $interests ) )
			return false;
			
		$playstyle 	= $this->style;
		if ( $playstyle == 'blank' ) 
			$playstyle = '';

		// Do some grammar
		$lower 	= array( 'pve' , 'pvp' , 'rp' , 'crafting' );
		$upper 	= array( 'PvE' , 'PvP' , 'RP' , 'Crafting' );
		$focus 	= implode( ', ' , $interests );
		$focus 	= str_replace ( $lower , $upper , $focus );
		
		// Generate a tooltip for our icons
		$tooltip = implode( ' - ' , array ( ucfirst( $playstyle ) ,  $focus ) );
			
		// Display them
		$icons 		 = '<div class="guild-style-icons ' . $playstyle . '" title="' . $tooltip . '"><ul>';
		foreach( $interests as $interest_name => $interest_val ) {
			$icons 	.= '<li class="guild-style-icon ' . $interest_val . '"></li>';
		}
		$icons 		.= '</ul></div>';
		return $icons;
	}
	
	/* 
	 * Generate a byline for the user profile with their allegiance information
	 */
	function byline() {
	
		// Get the data
		$faction	= $this->faction;
		$type		= strtolower( $this->type );
		$name		= $this->fullname;
			
		// Generate the byline
		if ( $faction == 'Undeclared' || $faction == 'Neutral' )
			$byline = $name . ' is a ' . $type . ' with no declared political allegiance.';
		else
			$byline = $name . ' is a ' . $type . ' of the ' . $faction;
		
		// Return the byline
		return $byline;
	}
	
	/**
	 * Formats the guild website
	 */	
	function website() {
	
		// Get the url
		$url = $this->website;
		$website = '';
		if ( $url )	$website = '<p class="group-website"><a href="' . $url . '" title="Visit Guild Website" target="_blank">Guild Website</a></p>';
		return $website;
	}
	
	function admins() {
	
		global $groups_template;
		$admins = $groups_template->group->admins;
		$list 	= '';
		
		if ( !empty( $admins ) ) {
			$list = '<ul id="group-admins">';
			foreach( $admins as $admin ) {
				$avatar = new Apoc_Avatar( array( 'user_id' => $admin->user_id , 'size' => 50 , 'link' => true ) );
				$list .= '<li>' . $avatar->avatar;
				$list .= '<span class="leader-name">' . bp_core_get_user_displayname( $admin->user_id ) . '</span></li>';
			}
			$list .= '</ul>';
		}
		
		return $list;
	}
	
	function mods() {
	
		global $groups_template;
		$mods = $groups_template->group->mods;
		$list = '';
		
		if ( !empty( $mods ) ) {
			$list = '<ul id="group-admins">';
			foreach( $mods as $mod ) {
				$avatar = new Apoc_Avatar( array( 'user_id' => $mod->user_id , 'size' => 50 , 'link' => true ) );
				$list .= '<li>' . $avatar->avatar;
				$list .= '<span class="leader-name">' . bp_core_get_user_displayname( $mod->user_id ) . '</span></li>';
			}
			$list .= '</ul>';
		}
		
		return $list;
	}
	
	/**
	 * Formats the output user block
	 */	
	function format_data( $context ) {
		
		// Setup the basic info block
		$block		= '<a class="member-name" href="' . $this->domain . '" title="View ' . $this->fullname . ' Group Page">' . $this->fullname . '</a>';
		$block		.= '<p class="group-type">' . $this->type . '</p>';
		$block		.= $allegiance = '<p class="user-allegiance ' . $this->alliance . '">' . $this->faction . '</p>';
		$block		.= $this->platform();
		$block		.= '<p class="group-member-count">' . $this->members . '</p>';

		//$icons			= $this->interest_icons();
		$icons = "";

		// Do some things differently depending on context
		switch( $context ) {
		
			case 'directory' :
				$avatar					= bp_get_group_avatar( $args = array( 'type' => 'thumb' , 'height' => $this->size , 'width' => $this->size ) );
				$avatar					= '<a class="member-avatar" href="' . $this->domain . '" title="View ' . $this->fullname . ' Group Page">' . $avatar . '</a>';
				$avatar					= '<div class="group-avatar-block">' . $avatar . $icons . '</div>';
				$block 					= '<div class="member-meta user-block">' . $block . '</div>';	
				break;
					
			case 'profile' :
				$avatar					= bp_get_group_avatar( $args = array( 'type' => 'full' , 'height' => $this->size , 'width' => $this->size ) );
				$avatar					= '<a class="member-avatar" href="' . $this->domain . '" title="View ' . $this->fullname . ' Group Page">' . $avatar . '</a>';
				$block					.= $this->website();
				$block					= $block . $icons;
				break;
				
			case 'widget' :
				$avatar					= bp_get_group_avatar( $args = array( 'type' => 'thumb' , 'height' => $this->size , 'width' => $this->size ) );
				$avatar					= '<a class="member-avatar" href="' . $this->domain . '" title="View ' . $this->fullname . ' Group Page">' . $avatar . '</a>';
				$avatar					= '<div id="featured-guild-avatar" class="group-avatar-block">' . $avatar . '</div>';
				$block 					= '<div id="featured-guild-meta" class="member-meta user-block">' . $block . '</div>';	
				break;				
		}
		
		// Prepend the avatar
		$this->avatar 	= $avatar;
		$block			= $avatar . $block;
		
		// Add the html to the object
		$this->block 	= $block;
	}
}


/**
 * Count groups having a specific meta key
 * @version 2.0
 */
function count_groups_by_meta($meta_key, $meta_value) {
	global $wpdb, $bp;
	$user_meta_query = $wpdb->get_var( $wpdb->prepare( "SELECT COUNT(*) FROM " . $bp->groups->table_name_groupmeta . " WHERE meta_key = %d AND meta_value= %s" , $meta_key , $meta_value ) );
	return intval($user_meta_query);
}


/*--------------------------------------------------------------
	REGISTRATION
--------------------------------------------------------------*/


/**
 * Display the humanity confirmation image set in Apocrypha theme settings
 * @version 2.0
 */
function apoc_registration_humanity_image() {

	// Get the current check
	$race = apoc()->humanity;

	// Return the image HTML
	echo '<img id="humanity-image" class="noborder" src="' . THEME_URI .'/registration/humanity.png" alt="HINT: This is a ' . ucfirst($race) . '" title="HINT: This is a ' . ucfirst($race) . '!" width="200" height="230" />';
}