<?php
/**
 * Apocrypha Theme BuddyPress Functions
 * Andrew Clayton
 * Version 2.0
 * 10-11-2014
 *
 * Contents:
 * 1.0 - Constants, Actions, and Filters
 * 2.0 - Activity
 * 3.0 - Members
 * 4.0 - Groups
 * 5.0 - Profiles
 * 6.0 - Registration
**/

// Exit if accessed directly
if ( !defined( 'ABSPATH' ) ) exit;

/*--------------------------------------------------------------
	APOC BUDDYPRESS PLUGIN CLASS
--------------------------------------------------------------*/
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

	/*------------------------------------------
		1.0 - CONSTANTS, ACTIONS, AND FILTERS
	------------------------------------------*/
	
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

		// BuddyPress extension files
		require( LIB_DIR . 'buddypress/groups.php' );
		require( LIB_DIR . 'buddypress/notifications.php' );
	}
	
	/**
	 * Modify global BuddyPress actions
	 */
	function actions() {
	
		// Unhook default actions
		remove_action( 'wp_head' 					, 'bp_core_add_ajax_url_js' 			);		

		// Prevent BuddyPress from loading scripts or styles
		remove_action( 'bp_enqueue_scripts' 		, 'bp_core_register_common_scripts' 	);
		remove_action( 'bp_enqueue_scripts' 		, 'bp_core_register_common_styles' 		);
		remove_action( 'bp_enqueue_scripts' 		, 'bp_core_confirmation_js' 			);
		remove_action( 'bp_enqueue_scripts' 		, 'bp_activity_mentions_script' 		);

		// Load additional BuddyPress customizations
		add_action( 'bp_init'						, array( $this , 'init' )				 );

		// BuddyPress Navigation
		add_action( 'bp_setup_nav'					, array( $this , 'navigation' ) , 99 	);

		// User Profiles
		add_action( 'bp_member_header_actions'		, 'bp_add_friend_button',           5 	);
		add_action( 'bp_member_header_actions'		, 'bp_send_public_message_button',  20 	);
		add_action( 'bp_member_header_actions'		, 'bp_send_private_message_button', 20 	);

		// Group Creation
		add_action( 'groups_group_before_save'		, array( $this , 'submit_guild' ) , 1 	);
	
		// Guild Buttons
		add_action( 'bp_group_header_actions'		, 'bp_group_join_button'	, 	5 		);
		add_action( 'bp_directory_groups_actions'	, 'bp_group_join_button'				);

		// User registration
		add_action( 'bp_signup_pre_validate'		, array( $this , 'pre_registration' ) 	);
		add_action( 'bp_signup_validate'			, array( $this , 'post_registration' ) 	);

	}
	
	/**
	 * Modify global BuddyPress filters
	 */
	function filters() {

		// Prevent Activity Favoriting
		add_filter( 'bp_activity_can_favorite' 					, '__return_false' );

		// Activity strip "View" link
		add_filter( 'bp_get_activity_latest_update' 			, array( $this , 'activity_update') );

		// Activity delete link
		add_filter( 'bp_get_activity_delete_link'				, array( $this , 'activity_delete_button' ) );

		// Profile Buttons
		add_filter( 'bp_get_add_friend_button'					, array( $this , 'friend_button' ) );
		add_filter( 'bp_get_send_public_message_button'			, array( $this , 'message_button' ) );
		add_filter( 'bp_get_send_message_button_args' 			, array( $this , 'message_button' ) );

		// Override bbPress Forum Tracker Templates 
		add_filter( 'bbp_member_forums_screen_topics' 		 	, array( $this, 'forums_template' ) );
		add_filter( 'bbp_member_forums_screen_replies' 		 	, array( $this, 'forums_template' ) );
		add_filter( 'bbp_member_forums_screen_favorites' 	 	, array( $this, 'forums_template' ) );
		add_filter( 'bbp_member_forums_screen_subscriptions' 	, array( $this, 'forums_template' ) );

		// Group Buttons
		add_filter( 'bp_get_group_join_button' 					, array( $this, 'join_button' ) );

		// Group Avatar
		add_filter( 'bp_get_group_avatar'						, array( $this, 'group_avatar' ) , 10 , 2 );
	}

	/**
	 * Load additional BuddyPress classes
	 */	
	function init() {

		// Load the Apoc_Group_Edit class for group administration screens
		if (  bp_is_group_create() || bp_is_group_admin_page() ) {
			global $group_edit;
			$group_edit = new Apoc_Group_Edit();
		}
	}

	/*------------------------------------------
		2.0 - ACTIVITY
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
		3.0 - MEMBERS
	------------------------------------------*/
	function friend_button( $button ) {
		
		// Remove the div wrapper
		$button['wrapper'] = false;

		// Set the button class
		$button['link_class'] = bp_is_user() ? 'button ' . $button['link_class'] : 'button-dark ' . $button['link_class'];

		// Not friends
		if ( in_array( $button['id'] , array( 'pending' , 'awaiting_response' , 'not_friends' ) ) )
			$button['link_text'] = '<i class="fa fa-check"></i>' . $button['link_text'];

		// Friends
		else if ( 'is_friend' === $button['id'] )
			$button['link_text'] = '<i class="fa fa-remove"></i>' . $button['link_text'];

		// Return the button
		return $button;
	}

	function message_button( $button ) {

		// Remove the div wrapper
		$button['wrapper'] = false;

		// Set the button class
		$button['link_class'] = bp_is_user() ? 'button ' . $button['link_class'] : 'button-dark ' . $button['link_class'];

		// Public message
		if ( $button['id'] === 'public_message' )
			$button['link_text'] = '<i class="fa fa-comment"></i>' . $button['link_text'];
		
		// Private message
		elseif ( $button['id'] === 'private_message' )
			$button['link_text'] = '<i class="fa fa-envelope"></i>' . $button['link_text'];	
		
		// Return the button
		return $button;
	}


	/*------------------------------------------
		4.0 - GROUPS
	------------------------------------------*/

	function submit_guild( &$group ) {

		// If the user is allowed to delete posts, then they can also create guilds and bypass validation requirements
		if ( current_user_can( 'delete_others_posts' ) ) return;

		// Get the BP object
		global $bp;

		// Retrieve and sanitize submission data
		$group->server 		= $_POST['group-server'];
		$group->interests 	= implode(',', $_POST['group-interests']);
		$group->faction 	= $_POST['group-faction'];
		$group->website 	= esc_url( $_POST['group-website'] );
		$group->style 		= $_POST['group-style']; 

		// Validate submitted data
		if ( '' === $group->server )			$error = 'Please select your guild&apos;s platform and server.';
		elseif ( empty( $group->interests ) )	$error = 'Please select your guild&apos;s primary interests.';
		elseif ( empty( $group->faction ) )		$error = 'Please select your guild&apos;s primary alliance.';


		// Assign the current group to the group object
		$bp->groups->current_group = $group;

		// If there was an error, display it and redirect
		if ( isset( $error ) ) {
			bp_core_add_message( $error , 'error' );
			bp_core_redirect( bp_get_root_domain() . '/' . bp_get_groups_root_slug() . '/create/step/' . bp_get_groups_current_create_step() . '/' );
		}

		// Otherwise, send an email
		else {

			// Get the current user
			$user 		= new Apoc_User( get_current_user_id() , 'profile' );
			$username	= $user->display_name;
			$user_email	= $user->user_email;
			$profile	= $user->link;

			// Set email headers
			$emailto 	= 'admin@tamrielfoundry.com';
			$subject 	= "Guild Creation Request From $username";
			$headers[] 	= "From: $username <$user_email>\r\n";
			$headers[] 	= "Content-Type: text/html; charset=UTF-8";

			// User Information
			$body = "<h3>Submitting User</h3>";
			$body .= "<ul>";
				$body .= "<li>Guild Leader: $profile";
				$body .= "<li>Email: $user_email</li>";
			$body .= "</ul>";

			// Guild Information
			$body .= "<h3>Guild Information</h3>";
			$body .= "<ul>";
				$body .= "<li>Guild Name: $group->name";
				$body .= "<li>Website: $group->website";
				$body .= "<li>Server: $group->server";
				$body .= "<li>Faction: $group->faction";
				$body .= "<li>Interests: $group->interests";
			$body .= "</ul>";

			// Guild Description
			$body .= "<h3>Guild Description</h3>";
			$body .= "<div>$group->description</div>";

			// Send the mail!
			wp_mail( $emailto , $subject , $body , $headers );

			// Redirect
			bp_core_add_message( 'Thank you for submitting your guild, ' . $user->fullname . '. Your request was successfully sent. We will review it and respond as soon as possible. If your request is approved, you will be added to your group, and promoted to guild leader. We will contact you via email regarding your guild request once it has been processed. Thank you for contributing to Tamriel Foundry!' );
			bp_core_redirect( SITEURL . '/' . bp_get_groups_root_slug() );	
		}
	}

	function join_button( $button ) {
		
		// Remove the div wrapper
		$button['wrapper'] = false;

		// Dark buttons on directories
		$button['link_class'] = bp_is_groups_directory() ? 'button-dark ' . $button['link_class'] : 'button ' . $button['link_class'];

		// Button icon
		$button['link_text'] = ( 'leave_group' === $button['id'] ) ? '<i class="fa fa-remove"></i>' . $button['link_text'] : '<i class="fa fa-check"></i>' . $button['link_text'];

		// Return the button
		return $button;
	}

	function group_avatar( $avatar , $r ) {
		if ( strpos( $avatar , "gravatar" ) > 0 ) {
			$default = ( $r['width'] > 100 ) ? BP_AVATAR_DEFAULT : BP_AVATAR_DEFAULT_THUMB;
			$avatar = preg_replace( '/src="(.*)" class/' , 'src="' . $default . '" class' , $avatar );
		}
		return $avatar;
	}


	/*------------------------------------------
		5.0 - PROFILES
	------------------------------------------*/

	/*
	 * Custom BuddyPress user and group profile navigation
	 */	
	function navigation() {
		global $bp;
		
		// Main navigation
		$bp->bp_nav['profile']['position'] 			= 10;
		$bp->bp_nav['activity']['position'] 		= 20;
		$bp->bp_nav['forums']['position'] 			= 30;
		$bp->bp_nav['friends']['position'] 			= 40;
		$bp->bp_nav['groups']['position'] 			= 50;
		$bp->bp_nav['messages']['position'] 		= 60;
		$bp->bp_nav['notifications']['position'] 	= 70;
		$bp->bp_nav['settings']['position'] 		= 90;
	
		// Profile biography
		$bp->bp_options_nav['profile']['public']['name'] 					= 'Player Biography';
		$bp->bp_options_nav['profile']['change-avatar']['name'] 			= 'Change Avatar';
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

		// Add moderation and infraction management panel
		if ( bp_is_user() && ( bp_is_my_profile() || current_user_can( 'moderate' ) ) ) {
				
			// Get the user object
			global $user;
			$user = new Apoc_User( bp_displayed_user_id() , 'profile' );
			$level = $user->warnings['level'];
			$level = ( $level > 0 ) ? '<span class="activity-count">' . $level . '</span>' : '';
			$notes = $user->mod_notes['count'];
			$notes = ( $notes > 0 ) ? '<span class="activity-count">' . $notes . '</span>' : '';
			bp_core_new_nav_item( array(
				'name' 					=> 'Infractions' . $level,
				'slug' 					=> 'infractions',
				'position' 				=> 80, 
				'screen_function' 		=> array( $this , 'infractions_screen' ),
				'default_subnav_slug' 	=> 'status',
				'item_css_id' 			=> 'infractions', ) );
		
			// Add infraction overview screen
			bp_core_new_subnav_item( array( 
				'name' 					=> 'History' . $level,
				'slug' 					=> 'status',
				'parent_url' 			=> $bp->displayed_user->domain . 'infractions/',
				'parent_slug' 			=> 'infractions',
				'screen_function' 		=> array( $this , 'infractions_screen' ),
				'position' 				=> 10 ) );
				
			// Add send warning screen
			if ( current_user_can( 'moderate' ) ) {	
				bp_core_new_subnav_item( array( 
					'name' 				=> 'Issue Warning',
					'slug' 				=> 'issue',
					'parent_url' 		=> $bp->displayed_user->domain . 'infractions/',
					'parent_slug' 		=> 'infractions',
					'screen_function' 	=> array( $this , 'warning_screen' ),
					'position' 			=> 30 ) );
			
				// Add moderator notes screen
				bp_core_new_subnav_item( array( 
					'name' 				=> 'Mod Notes' . $notes,
					'slug' 				=> 'notes',
					'parent_url' 		=> $bp->displayed_user->domain . 'infractions/',
					'parent_slug' 		=> 'infractions',
					'screen_function' 	=> array( $this , 'modnotes_screen' ),
					'position' 			=> 20 ) );
			}
		}

		// Group profile navigation
		if( bp_is_group() ) {
			$group_id = bp_get_current_group_id();

			// Add activity tab
			bp_core_new_subnav_item( array( 
				'name' 				=> 'Activity', 
				'slug' 				=> 'activity', 
				'parent_slug' 		=> $bp->groups->current_group->slug, 
				'parent_url' 		=> bp_get_group_permalink( $bp->groups->current_group ), 
				'screen_function' 	=> array( $this , 'group_activity_screen' ),
				'position' 			=> 65, 
			) );

			// Rename group navigation elements
			$bp->bp_options_nav[$bp->groups->current_group->slug]['admin']['name'] = 'Admin';
		}
	}

	/**
	 * Override the bbPress forum tracker templating
	 */
	function forums_template( $template ) {
		$template = 'members/single/home';
		return $template;
	}

	/*
	 * Profile screen templates
	 */
	function edit_profile_screen() {
		bp_core_load_template( 'members/single/profile/edit' );
	}
	function infractions_screen() {
		bp_core_load_template( 'members/single/infractions' );
	}
	function warning_screen() {
		bp_core_load_template( 'members/single/infractions/warning' );
	}
	function modnotes_screen() {
		bp_core_load_template( 'members/single/infractions/notes' );
	}
	function group_activity_screen() {
		bp_core_load_template( 'groups/single/home' );
	}
	

	/*------------------------------------------
		6.0 - USER REGISTRATION
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



