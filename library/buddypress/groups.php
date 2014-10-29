<?php
/**
 * Apocrypha Theme BuddyPress Groups Functions
 * Andrew Clayton
 * Version 2.0
 * 10-11-2014
 * Contents:
 * 1.0 - Apoc Group Class
 * 2.0 - Group Creation Class
*/


/*--------------------------------------------------------------
	APOC GROUP CLASS
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
		
		// Get the meta from cache
		$allmeta = wp_cache_get( 'bp_groups_allmeta_' . $group_id, 'bp' );
		
		// Otherwise query the groupmeta table
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
		$this->guild		= ( isset( $allmeta['is_guild']) ) 		? $allmeta['is_guild'] : 0;
		$this->members		= bp_get_group_member_count();
		$this->alliance		= isset( $allmeta['group_faction'] )	? $allmeta['group_faction'] : 'neutral';
		$this->server		= isset( $allmeta['group_server'] )		? $allmeta['group_server'] : NULL;
		$this->style		= isset( $allmeta['group_style'] )		? $allmeta['group_style'] : NULL;
		$this->interests	= isset( $allmeta['group_interests'] )	? unserialize( $allmeta['group_interests'] ) : array();
		$this->website		= isset( $allmeta['group_website'] )	? $allmeta['group_website'] : NULL;
		
		// Get some derived data
		$this->type			= $this->type();
		$this->faction		= $this->allegiance();
		$this->servname		= $this->server_name( $this->server );	
		$this->tooltip		= $this->tooltip();

		// Get some extra stuff on user profiles
		if ( $this->context == 'profile' ) {
			$this->byline	= $this->byline();	
			$this->admins 	= $this->admins();
			$this->mods 	= $this->mods();
		}
	}

	
	/**
	 * Formats the output user block
	 */	
	function format_data( $context ) {
		
		// Setup the basic info block
		$block		= '<a class="member-name" href="' . $this->domain . '" title="View ' . $this->fullname . ' Group Page">' . $this->fullname . '</a>';
		$block		.= '<p class="group-server">' . $this->servname . '</p>';
		$block		.= $allegiance = '<p class="user-allegiance ' . $this->alliance . '">' . $this->faction . '</p>';
		$block		.= $this->tooltip();
		$block		.= '<p class="group-member-count">' . $this->members . '</p>';

		// Do some things differently depending on context
		switch( $context ) {
		
			case 'directory' :
				$avatar					= bp_get_group_avatar( $args = array( 'type' => 'thumb' , 'height' => $this->size , 'width' => $this->size ) );
				$avatar					= '<a class="member-avatar" href="' . $this->domain . '" title="View ' . $this->fullname . ' Group Page">' . $avatar . '</a>';
				$avatar					= '<div class="directory-member-avatar">' . $avatar . '</div>';
				$block 					= '<div class="directory-member-meta">' . $block . '</div>';	
				break;
					
			case 'profile' :
				$avatar					= bp_get_group_avatar( $args = array( 'type' => 'full' , 'height' => $this->size , 'width' => $this->size ) );
				$avatar					= '<a class="member-avatar" href="' . $this->domain . '" title="View ' . $this->fullname . ' Group Page">' . $avatar . '</a>';
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

	/**
	 * Decode server tag
	 */
	function server_name( $server ) {

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
	function tooltip() {
		
		// Get server
		$server 	= $this->servname;

		// Get interests
		$interests 	= $this->interest_icons();

		return $interests;
	}
	
	/* 
	 * Display the group's interest icons
	 */	
	function interest_icons() {

		// Assign interest styles
		$style = array(
			'pve' 		=> array( 
				'name'	=> 'PvE',
				'icon'	=> '<i class="fa fa-trophy fa-fw"></i>' ),
			'pvp' 		=> array( 
				'name'	=> 'PvP',
				'icon'	=> '<i class="fa fa-bookmark fa-fw"></i>' ),
			'rp' 		=> array( 
				'name'	=> 'RP',
				'icon'	=> '<i class="fa fa-beer fa-fw"></i>' ),
			'crafting' 	=> array( 
				'name'	=> 'Craft',
				'icon'	=> '<i class="fa fa-legal fa-fw"></i>' ),
		);
	
		// Get interests
		$inputs = $this->interests;
		if ( empty ( $inputs ) ) return false;

		// Get playstyle
		$playstyle 	= isset( $this->style ) ? $this->style : "";

		// Implode a list of interests
		$interests = array();
		foreach ( $inputs as $input ) {
			$interests[] = $style[$input]['icon'] . $style[$input]['name'];
		}

		// Generate a tooltip for our icons
		$icons 	= implode( ' ' , $interests );
			
		// Display them
		$icons 	= '<p class="guild-style-icons">' . $icons . '</p>';
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
		if ( $url )	$website = '<a href="' . $url . '" title="Visit ' . $this->fullname . ' Guild Website" target="_blank">' . $this->fullname . '</a>';
		return $website;
	}
	
	function admins() {
	
		global $groups_template;
		$admins = $groups_template->group->admins;
		$list 	= '';
		
		if ( !empty( $admins ) ) {		
			$list = '<ul class="leader-list">';		
			foreach( $admins as $admin ) {
				$user = new Apoc_User( $admin->user_id , 'reply' , 50 );
		
				$list .= '<li class="directory-member">';
					$list .= $user->avatar;
					$list .= '<div class="directory-member-meta">';
						$list .= $user->link;
						$list .= $user->title;
					$list .= '</div>';
				$list .= '</li>';				
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
			$list = '<ul class="leader-list">';
			foreach( $mods as $mod ) {
				$user = new Apoc_User( $mod->user_id , 'reply' , 50 );
		
				$list .= '<li class="directory-member">';
					$list .= $user->avatar;
					$list .= '<div class="directory-member-meta">';
						$list .= $user->link;
						$list .= $user->title;
					$list .= '</div>';
				$list .= '</li>';				
			}
			$list .= '</ul>';
		}
		
		return $list;
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

/**
 * Helper function to check if a group is a guild
 * @version 2.0
 */
function group_is_guild( $group_id ) {
	$guild = groups_get_groupmeta( $group_id , 'is_guild' );
	$is_guild = ( $guild == 1 ) ? true : false;
	return $is_guild;
}




/*--------------------------------------------------------------
	2.0 - GROUP CREATION
--------------------------------------------------------------*/

/**
 * Apocrypha theme group editing
 * This class is called when user groups are being edited in their administration panels or during the group creation process.
 *
 * @version 2.0
 */
class Apoc_Group_Edit {

	// Is guild creation enabled?
	public $enabled 		= true;

	// What are the creation requirements?
	public $minposts		= 25;
	public $mintime			= '2 weeks';

	// What access level does the current user have?
	public $create 			= false;
	public $access 			= true;
	public $error			= '';

	/**
	 * Initialize Group edit class
	 */
	function __construct() {
	
		// Add profile edit actions
		$this->actions();		
		
		// Add profile edit filters
		$this->filters();	

		// Determine whether user can create new groups
		$this->can_user_create();
	}
	
	/**
	 * Register group edit actions
	 */
	private function actions() {

		// Save group meta fields
		add_action( 'groups_details_updated'						, array( $this , 'save_group_fields' ) );
		add_action( 'groups_create_group_step_save_group-details' 	, array( $this , 'save_group_fields' ) );
	}

	/**
	 * Register group edit filters
	 */
	private function filters() {
	}

	function can_user_create() {

		// Can the current user administrate?
		if ( current_user_can( 'delete_posts' ) ) {
			$this->create 		= true;
			$this->access 		= true;
			return;
		}

		// Is creation enabled?
		else if ( true !== $this->enabled ) {
			$this->access = false;
			$this->error = '<p class="warning">We are sorry, but guild creation is temporarily disabled while we catch back up on processing back-logged submissions. This service should be re-enabled in the next several days, so please check back later to submit your guild. Sorry for the inconvenience.</p>';
			return;	
		}

		// Does the current user meet the submission requirements?
		else {
			$user 		= new Apoc_User( get_current_user_id() , 'profile' );
			$regdate 	= strtotime( $user->regdate );	
			
			// The user satisfies the requirements
			if ( $regdate <= strtotime( '-'.$this->mintime ) && $user->posts['total'] >= $this->minposts ) {
				$this->access 		= true;
			} else {
				$this->access 		= false;	
				$this->error 		= '<p class="warning">Guild submission is only available to Tamriel Foundry members who have been a site member for longer than ' . $this->mintime . ' and contributed at least ' . $this->minposts . ' total posts to the community. This is to prevent the submission of guilds which are only seeking to use Tamriel Foundry as a recruitment or advertisment tool with no intention to participate within the community. Acceptable posts contribute positively to discussion within the Tamriel Foundry community while conforming to our site Code of Conduct. The spam creation of topics or replies solely to meet this submission requirement will be punished accordingly.</p>';				
			}
		}
	}


	/* 
	 * Save custom groupmeta fields on group profile updates and at creation
	 * @version 2.0
	 */
	function save_group_fields( $group_id ) {
		
		// Get the current BP group object
		global $bp;

		// Get ID of newly created groups
		if( bp_is_group_create() )
			$group_id = $bp->groups->new_group_id;

		// Save the eligible meta
		$is_guild = ( 'group' == $_POST['group-type'] ) ? 0 : 1;
			groups_update_groupmeta( $group_id, 'is_guild', $is_guild );
			
		if ( $_POST['group-website'] )
			groups_update_groupmeta( $group_id, 'group_website', $_POST['group-website'] );  
	
		if ( $_POST['group-server']  )
			groups_update_groupmeta( $group_id, 'group_server', $_POST['group-server'] );
			
		if ( $_POST['group-faction']  )
			groups_update_groupmeta( $group_id, 'group_faction', $_POST['group-faction'] );

		if ( $_POST['group-style']  )
			groups_update_groupmeta( $group_id, 'group_style', $_POST['group-style'] );
			
		if ( $_POST['group-interests']  )
			groups_update_groupmeta( $group_id, 'group_interests', $_POST['group-interests'] );
			
		// Clear the cached metadata
		wp_cache_delete( 'bp_groups_allmeta_' . $group_id , 'bp' );
	}
}


/**
 * Modify the way checkboxes are rendered on the invite screen
 * @version 2.0
 */
function apoc_new_group_invite_friend_list() {

	// Get the HTML from the BuddyPress function
	$html = bp_get_new_group_invite_friend_list();

	// Wrap usernames in labels for our checkbox styling
	$html = str_replace( '> ' , '><label>' , $html );
	$html = str_replace( '</li>' , '</label></li>' , $html );

	// Output the HTML
	echo $html;
}