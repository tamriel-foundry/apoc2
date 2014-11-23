<?php
/**
 * Apocrypha Slides Custom Post Type
 * Andrew Clayton
 * Version 2.0
 * 9-21-2014
 */
 
// Exit if accessed directly
if ( !defined( 'ABSPATH' ) ) exit;
 
 
/*---------------------------------------------
	1.0 - APOC_EVENTS CLASS
----------------------------------------------*/

/**
 * Registers the "Event" custom post type.
 * Events are used with the taxonomy "Calendar" to display a list of upcoming events.
 *
 * @author Andrew Clayton
 * @version 2.0
 */
class Apoc_Events {

	/**
	 * Register the custom post and taxonomy with WordPress on init
	 * @version 2.0
	 */
	function __construct() {
	
		// Add universal actions
		add_action( 'init'							, array( $this , 'register_events' 		) );
		add_action( 'init'							, array( $this , 'register_calendar' 	) );

		// Universal Filters
		add_filter( 'calendar_event_notification'	, array( $this , 'format_notification' 	) , 10 , 2 );

		// Admin-only
		if ( is_admin() && !defined( 'DOING_AJAX' ) ) {
		
			// Admin Actions
			add_action( 'admin_menu'				, array( $this , 'meta_boxes' ) );
			add_action( 'save_post'					, array( $this , 'save_event' )	, 10, 2 );
			add_action( 'calendar_add_form_fields'	, array( $this , 'calendar_meta_box' ) , 10, 2 );
			add_action( 'calendar_edit_form_fields'	, array( $this , 'calendar_edit_meta_box' ) , 10 , 2  );
			add_action( 'edited_calendar'			, array( $this , 'save_calendar' ) , 10, 2 );  
			add_action( 'create_calendar'			, array( $this , 'save_calendar' ), 10, 2 );
			add_action( 'manage_event_posts_custom_column', array( $this , 'custom_event_columns' ) );
			
			// Admin Filters
			add_filter( 'post_updated_messages'		, array( $this , 'update_messages') );
			add_filter( 'manage_edit-event_columns'	, array( $this , 'event_columns' ) );
		}
	}

	/**
	 * Register a custom post type for Events
	 * @version 2.0
	 */
	function register_events() {

		// Labels for the backend Event publisher
		$event_labels = array(
			'name'					=> 'Events',
			'singular_name'			=> 'Event',
			'add_new'				=> 'New Event',
			'add_new_item'			=> 'Schedule Event',
			'edit_item'				=> 'Edit Event',
			'new_item'				=> 'New Event',
			'view_item'				=> 'View Event',
			'search_items'			=> 'Search Events',
			'not_found'				=> 'No events found',
			'not_found_in_trash'	=> 'No events found in Trash', 
			'parent_item_colon'		=> '',
			'menu_name'				=> 'Events',
		);
		
		$event_capabilities = array(
			'edit_post'				=> 'edit_post',
			'edit_posts'			=> 'edit_posts',
			'edit_others_posts'		=> 'edit_others_posts',
			'publish_posts'			=> 'publish_posts',
			'read_post'				=> 'read_post',
			'read_private_posts'	=> 'read_private_posts',
			'delete_post'			=> 'delete_post'
		);			
			
		// Construct the arguments for our custom slide post type
		$event_args = array(
			'labels'				=> $event_labels,
			'description'			=> 'Scheduled calendar events',
			'public'				=> true,
			'publicly_queryable'	=> true,
			'exclude_from_search'	=> true,
			'show_ui'				=> true,
			'show_in_menu'			=> true,
			'show_in_nav_menus'		=> false,
			'menu_icon'				=> THEME_URI . '/images/icons/calendar-icon-20.png',
			'capabilities'			=> $event_capabilities,
			'map_meta_cap'			=> true,
			'hierarchical'			=> false,
			'supports'				=> array( 'title', 'editor', 'thumbnail' ),
			'taxonomies'			=> array( 'calendar' , 'occurence' ),
			'has_archive'			=> false,
			'rewrite'				=> array(
										'slug' 	=> 'event',
										'feeds'	=> false,
										'pages'	=> false,
										),
			'query_var'				=> true,
			'can_export'			=> true,
		);

		
		// Register the Event post type!
		register_post_type( 'event', $event_args );
	}

	/**
	 * Register a Calendar taxonomy for Events
	 * @since 2.0
	 */
	function register_calendar() {
		
		/* Calendar */
		$calendar_tax_labels = array(			
			'name'							=> 'Calendars',
			'singular_name'					=> 'Calendar',
			'search_items'					=> 'Search Calendars',
			'popular_items'					=> 'Popular Calendars',
			'all_items'						=> 'All Calendars',
			'edit_item'						=> 'Edit Calendar',
			'update_item'					=> 'Update Calendar',
			'add_new_item'					=> 'Add New Calendar',
			'new_item_name'					=> 'New Calendar Name',
			'menu_name'						=> 'Calendars',
			'separate_items_with_commas'	=> 'Separate calendars with commas',
			'choose_from_most_used'			=> 'Choose from the most used calendars',
		);
		
		$calendar_tax_caps = array(
			'manage_terms'	=> 'manage_categories',
			'edit_terms'	=> 'manage_categories',
			'delete_terms'	=> 'manage_categories',
			'assign_terms'	=> 'edit_posts'
		);
		
		$calendar_tax_args = array(
			'labels'				=> $calendar_tax_labels,
			'public'				=> true,
			'show_ui'				=> true,
			'show_in_nav_menus'		=> false,
			'show_tagcloud'			=> false,
			'hierarchical'			=> true,
			'rewrite'				=> array( 'slug' => 'calendar' ),
			'capabilities'    	  	=> $calendar_tax_caps,
		);		

		/* Register the Calendar post taxonomy! */
		register_taxonomy( 'calendar', 'event', $calendar_tax_args );
	}

	/**
	 * Add custom event meta boxes.
	 * @version 2.0
	 */
	function meta_boxes() {
		add_meta_box( 'event-details', 'Event Details', array( $this , 'details_box' ), 'event', 'normal', 'high' );
	}

	/**
	 * Display custom event details box.
	 * @version 2.0
	 */
	function details_box( $object , $box ) {
		wp_nonce_field( basename( __FILE__ ) , 'event-details-box' ); 

		// Retrieve the post object
		global $post; ?>
		<p>
			<label for="event-date">Event Time (EST)</label>
			<input type="datetime-local" name="event-time" value="<?php echo date( "Y-m-d\TH:i" , strtotime($post->post_date) ); ?>" tabindex="10" />
		</p>
		<p>
			<label for="event-start">Event Duration (Hours)</label>
			<input type="number" name="event-duration" value="<?php echo get_post_meta( $object->ID , 'event_duration' , true ); ?>" tabindex="11" />
		</p>
		<p>
			<label for="event-capacity">Event Capacity (Players)</label>
			<input type="number" name="event-capacity" value="<?php echo get_post_meta( $object->ID , 'event_capacity' , true ); ?>" tabindex="12" />
		</p>
		<p>
			<input type="checkbox" name="event-rsvp" value="true" <?php checked( get_post_meta( $object->ID , 'event_rsvp' , true ) , 'true' ); ?> tabindex="13" />
			<label for="event-require-rsvp">Send Notification?</label>
		</p>
		<p>
			<input type="checkbox" name="event-role" value="true" <?php checked( get_post_meta( $object->ID , 'event_role' , true ) , 'true' ); ?> tabindex="14" />
			<label for="event-require-role">Request Role?</label>
		</p>
	<?php 	
	}	

	/**
	 * Save or update a new event
	 * @version 2.0
	 */
	function save_event( $post_id , $post = '' ) {
	
		// Don't do anything if it's not an event
		if ( 'event' != $post->post_type ) return;

		// Verify the nonce before proceeding.
		if ( !isset( $_POST['event-details-box'] ) || !wp_verify_nonce( $_POST['event-details-box'], basename( __FILE__ ) ) )
			return $post_id;			

		/* -----------------------------------
			SAVE EVENT TIME 
		------------------------------------*/

		// Retrieve the event time
		$event_time = date( 'Y-m-d H:i:s' , strtotime($_POST['event-time']) );

		// Update the post object
		$post->post_date 		= $event_time;
		remove_action( 'save_post' , array( $this , 'save_event' )	);
		wp_update_post( $post );
		add_action( 'save_post'	, array( $this , 'save_event' )	, 10, 2 );
	
		/* -----------------------------------
			SAVE META INFORMATION 
		------------------------------------ */

		// Define the meta to look for
		$meta = array(
			'event_duration'	=> $_POST['event-duration'],
			'event_capacity'	=> $_POST['event-capacity'],
			'event_rsvp'		=> $_POST['event-rsvp'],
			'event_role'		=> $_POST['event-role'],

		);
		
		// Loop through each meta, saving it to the database
		foreach ( $meta as $meta_key => $new_meta_value ) {
		
			// Get the meta value of the custom field key.
			$meta_value = get_post_meta( $post_id, $meta_key, true );

			// If there is no new meta value but an old value exists, delete it.
			if ( current_user_can( 'delete_post_meta', $post_id, $meta_key ) && '' == $new_meta_value && $meta_value )
				delete_post_meta( $post_id, $meta_key, $meta_value );

			// If a new meta value was added and there was no previous value, add it.
			elseif ( current_user_can( 'add_post_meta', $post_id, $meta_key ) && $new_meta_value && '' == $meta_value )
				add_post_meta( $post_id, $meta_key, $new_meta_value, true );

			// If the new meta value does not match the old value, update it.
			elseif ( current_user_can( 'edit_post_meta', $post_id, $meta_key ) && $new_meta_value && $new_meta_value != $meta_value )
				update_post_meta( $post_id, $meta_key, $new_meta_value );
		}		

		/* -----------------------------------
			BUDDYPRESS NOTIFICATION
		------------------------------------ */
			
		// Get event data
		global $bp, $wpdb;
		if ( !$user_id )
			$user_id = $post->post_author;

		// Figure out which calendars this event belongs to
		$calendars = wp_get_post_terms( $post_id , 'calendar' );
		$group_slugs = array();
			
		// For each calendar, check if it's a group calendar
		foreach ( $calendars as $calendar ) {
			if ( is_group_calendar( $calendar->term_id ) )
				$groups[] = $calendar;
		}	
		
		// If this event does not belong to a group, we can stop here
		if ( empty( $groups ) ) return $post_id;	
		
		// Only register notifications for future or published events
		if ( !in_array( $post->post_status , array('publish','future') ) ) return $post_id;	
		
		// Loop through each group, adding an activity entry for each one
		foreach ( $groups as $group ) {
		
			// Get the group data
			$group_id 	= groups_get_id( $group->slug );
			$group_name	= $group->name;

			// Configure the activity entry
			$post_permalink 	= get_permalink( $post_id );
			$activity_action 	= sprintf( '%1$s added the event %2$s to the %3$s.' , bp_core_get_userlink( $post->post_author ), '<a href="' . $post_permalink . '">' . $post->post_title . '</a>' , $group_name . ' <a href="' . SITEURL . '/calendar/' . $group->slug .'">group calendar</a>' );
			$activity_content 	= $post->post_content;

			// Check for existing entry
			$activity_id = bp_activity_get_activity_id( array(
				'user_id'           => $user_id,
				'component'         => $bp->groups->id,
				'type'              => 'new_calendar_event',
				'item_id'           => $group_id,
				'secondary_item_id' => $post_id,
			) );
			
			// Record the entry
			groups_record_activity( array(
				'id'				=> $activity_id,
				'user_id' 			=> $user_id,
				'action' 			=> $activity_action,
				'content' 			=> $activity_content,
				'primary_link' 		=> $post_permalink,
				'type' 				=> 'new_calendar_event',
				'item_id' 			=> $group_id,
				'secondary_item_id' => $post_id,
			));
			
			// Update the group's last activity meta
			groups_update_groupmeta( $group_id, 'last_activity' , bp_core_current_time() );
			
			// Maybe notify every group member
			if ( $_POST['event-rsvp'] ) :
				if ( bp_group_has_members( $args = array( 'group_id' => $group_id, 'exclude_admins_mods' => false , 'per_page' => 99999 ) ) ) :	while ( bp_members() ) : bp_the_member();
						
					// Remove any existing notifications ( $user_id, $item_id, $component_name, $component_action, $secondary_item_id = false )
					bp_notifications_delete_notifications_by_item_id( bp_get_group_member_id() , $group_id , $bp->groups->id , 'new_calendar_event' , $post_id );
			
					// Send a notification ( itemid , groupid , component, action , secondary )
					bp_notifications_add_notification( array(
						'user_id'			=> bp_get_group_member_id(),
						'item_id'			=> $group_id,
						'secondary_item_id'	=> $post_id,
						'component_name'	=> $bp->groups->id,
						'component_action'	=> 'new_calendar_event'					
					));						
				endwhile; endif;
			endif;
		}
	}

	/**
	 * Customize backend messages when an event is updated.
	 * @version 2.0
	 */
	function update_messages( $event_messages ) {
		global $post, $post_ID;
		
		/* Set some simple messages for editing slides, no post previews needed. */
		$event_messages['event'] = array( 
			0	=> '',
			1	=> 'Event updated.',
			2	=> 'Custom field updated.',
			2	=> 'Custom field deleted.',
			4	=> 'Event updated.',
			5	=> isset($_GET['revision']) ? sprintf( 'Event restored to revision from %s', wp_post_revision_title( (int) $_GET['revision'], false ) ) : false,
			6	=> 'Event added to calendar.',
			7	=> 'Event saved.',
			8	=> 'Event added to calendar.',
			9	=> sprintf( 'Event scheduled for: <strong>%1$s</strong>.' , strtotime( $post->post_date ) ),
			10	=> 'Event draft updated.',
		);
		return $event_messages;
	}

	/**
	 * Add group association to calendar taxonomies.
	 * @version 2.0
	 */
	function calendar_meta_box() { ?>

		<div>
			<label for="is-group-calendar"><input type="checkbox" name="is-group-calendar" value="true"> This calendar is for a BuddyPress group.</label>
			<p class="description">A calendar may be associated with a BuddyPress group by assigning it the same slug as the slug of the group.</p>	
		</div><?php 
	}
	
	/**
	 * Edit calendar meta box
	 * @version 2.0
	 */
	function calendar_edit_meta_box( $term ) { 

		// Get any existing value
		$term_meta = get_option( 'taxonomy_' . $term->term_id ); ?>
		
		<tr>
			<th scope="row" valign="top"><label for="is-group-calendar">BuddyPress Group</label></th>
			<td>
				<input type="checkbox" name="is-group-calendar" value="true" <?php checked( $term_meta['is_group_calendar'] , 'true' ); ?>>
				<p class="description">A calendar may be associated with a BuddyPress group by assigning it the same slug as the slug of the group.</p>	
			</td>
		</tr><?php
	}
	
	/**
	 * Save custom calendar taxonomy.
	 * @version 2.0
	 */
	function save_calendar( $term_id ) {
		
		$term_meta 	= get_option( "taxonomy_$term_id" );
		
		// If it has a value, update the option
		if ( isset( $_POST['is-group-calendar'] ) ) {
			$term_meta['is_group_calendar'] = $_POST['is-group-calendar'];
			update_option( "taxonomy_$term_id", $term_meta );
		}
		
		// Otherwise, if it had a value, remove it
		elseif ( !empty( $term_meta ) )
			delete_option( "taxonomy_$term_id" );
	}
	
	/**
	 * Title event columns
	 * @since 1.0.0
	 */
	function event_columns( $columns ) {
		$columns = array(		
			'cb'			=> '<input type="checkbox" />',
			'title'			=> 'Event Name',
			'calendar'		=> 'Calendar',
			'event_date'	=> 'Date',
			'event_time'	=> 'Time' );
		return $columns; 
	}
	
	/**
	 * Customize the display of events page
	 * @since 0.1
	 */
	function custom_event_columns( $columns ) {
		global $post;
		switch ( $columns ) {		
			case 'calendar' :
				echo get_the_term_list( $post->ID , 'calendar' );
			break;
			
			case 'event_date' :	
				$meta_date 		= get_post_meta( $post->ID , 'event_date' , true );
				$display_date 	= date('l, F j', strtotime( $meta_date ) );
				echo $display_date;
			break;
			
			case 'event_time' :	
				$meta_time 		= get_post_meta( $post->ID , 'event_start' , true );
				$display_time 	= date('g:i a', strtotime( $meta_time ) );
				echo $display_time;
			break;
		}
	}

	/**
	 * Formats the display of calendar event notifications on group calendars
	 * Currently uses a core hack in bp-groups-notifications.php line 608
	 * @version 2.0
	 */
	function format_notification( $item_id, $secondary_item_id ) {
		
		// Get some data
		$group 	= groups_get_group( array( 'group_id' => $item_id ) );
		$post 	= get_post( $secondary_item_id );

		// Format the notification
		return sprintf( '<a href="%1$s">%2$s added to the %3$s group calendar.</a>' , $post->post_permalink , $post->post_title , $group->name );		
	}
}
$apoc_events = new Apoc_Events();

/*---------------------------------------------
	2.0 - CALENDAR TAXONOMY FUNCTIONS
----------------------------------------------*/

/**
 * Query upcoming events
 * @version 2.0
 */
function calendar_have_events( $calendar = '' ) {
	
	// Set up calendar arguments
	$calendar_args = array(
		
		// Get an unlimited number of events with any status
		'post_type'			=> 'event',
		'post_status'		=> 'any',
		'posts_per_page'	=> -1,

		// Restrict them to this specific calendar
		'tax_query' 		=> array(
			array(
				'taxonomy' 	=> 'calendar',
				'field' 	=> 'slug',
				'terms' 	=> $calendar,
			)),
	
		// Restrict to future dates
		'date_query'		=> array(
			array(
				'after'		=> date('Y-m-d'),
				'inclusive'	=> true,
			) ),
		'orderby'			=> 'date',
		'order'				=> 'ASC',
	);

	// Retrieve posts
	query_posts( $calendar_args );

	// Return a true/false for whether events were found
	return have_posts();
}

/*---------------------------------------------
	3.0 - APOC EVENT CLASS
----------------------------------------------*/

/**
 * Display a single event
 * @version 2.0
 */
function apoc_single_event() {
	include( THEME_DIR . '/library/templates/single-event.php' );
}

/*---------------------------------------------
	4.0 - HELPER FUNCTIONS
----------------------------------------------*/

/**
 * Helper function to check if a calendar belongs to a BuddyPress group
 * @version 2.0
 */
function is_group_calendar( $term_id ) {
	$term_meta 	= get_option( "taxonomy_$term_id" );
	if ( $term_meta['is_group_calendar'] )
		return true;
}