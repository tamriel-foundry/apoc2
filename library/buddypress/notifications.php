<?php
/**
 * Apocrypha Theme Notifications Class
 * Andrew Clayton
 * Version 2.0
 * 10-25-2014
*/


/*--------------------------------------------------------------
	APOC NOTIFICATIONS CLASS
--------------------------------------------------------------*/

/** 
 * Generates user notifications for the admin bar in the site header
 * Formats these notifications by grouping them by component
 * Disaggregates multiple notifications of the same type to display notifications individually
 *
 * @version 2.0
 */
class Apoc_Notifications extends BP_Core_Notification {

	// Class Properties
	public $user_id;
	public $notifications;

	/**
	 * Constructs and formats the notifications
	 */
	function __construct( $user_id ) {
		$this->user_id 			= $user_id;
		$this->notifications	= $this->get_notifications();
	}

	/**
	 * Get the notifications from BuddyPress 
	 */
	function get_notifications() {

		// Setup notification array
		$nots = array();

		// Count each type
		$nots['counts'] = array(
			'activity' 	=> 0,
			'messages' 	=> 0,
			'friends'	=> 0,
			'groups'	=> 0,
		);

		// Configure query parameters
		$args = array(
			'user_id'      => $this->user_id,
			'is_new'       => true,
			'page'         => '',
			'per_page'     => '',
			'max'          => '',
			'search_terms' => ''
		);

		// Loop through notifications, sorting them by type
		if ( bp_has_notifications( $args ) ) :
			while ( bp_the_notifications() ) : bp_the_notification();

				// Get the notification
				global $bp;
				$not = $bp->notifications->query_loop->notification;

				// Consolidate action components
				if ( $not->component_name == "forums" ) $not->component_name = "activity";
				if ( $not->component_name == "events" ) $not->component_name = "groups";
				$comp = $not->component_name;

				// Add a count for each notification type
				$not->count = 1;

				// Get the default description
				$not->desc = ( 'activity' != $not->component_name ) ? bp_get_the_notification_description() : "";

				// Add notifications to the array
				$nots[$comp][] = $not;
				
				// Increment the count
				$nots['counts'][$comp]++;	
			endwhile;
		endif;

		// Group activity notifications
		if ( $nots['counts']['activity'] > 0 ) {

			// Get the activities and setup a combined array
			$acts = $nots['activity'];
			$activities = array();

			// Loop over activities and group them by item_id
			for ( $i = 0; $i < count($acts); $i++ ) {

				// Mentions get grouped, other activities use their item_id
				$item_id = ( $acts[$i]->component_action == 'new_at_mention' ) ? 0 : $acts[$i]->item_id;	
				$acts[$i]->id = $item_id;					
				
				// Add the activity, or increment the count
				if ( !isset( $activities[$item_id] ) )
					$activities[$item_id] = $acts[$i];
				else
					$activities[$item_id]->count++;
			}

			// Loop over grouped notifications, apply custom formatting for activities
			foreach ( $nots['activity'] as $id => $not ) {

				// Get the formatted description
				$nots['activity'][$id]->desc = $this->format_notification( $not );
			}

			// Replace activities with the grouped ones
			$nots['activity'] = $activities;
		}

		// Return the notifications
		return $nots;
	}

	/**
	 * Format the display of each notification
	 */
	function format_notification( $not ) {

		// Get the action
		$action = $not->component_action;

		// Maybe override the action description
		switch( $action ) {

			// Activities
			case 'new_at_mention':
				$grammar			= ( $not->count > 1 ) ? ' times.' : ' time.';
				$link 				= bp_loggedin_user_domain() . bp_get_activity_slug() . '/mentions/';
				$desc				= '<a href="' . $link . '">You were mentioned in discussion ' . $not->count . $grammar . '</a>';
				break;

			case 'bbp_new_reply' :
				$grammar 		= ( $not->count > 1 ) ? ' new replies.' : ' new reply.';
				$link 			= bbp_get_topic_last_reply_url( $not->item_id );
				$desc				= '<a href="' . $link . '">Your topic "' . bbp_get_topic_title( $not->item_id ) . '" has ' . $not->count . $grammar . '</a>';
				break;

			// Default
			default :
				break;
		}
		
		// Return the description
		return $desc;			
	}
}

/**
 * Helper function to output notifications to template
 * @version 2.0
 */
function apoc_notifications() {
	include( THEME_DIR . '/library/templates/notifications.php' );
}
function apoc_get_notifications() {
	$notifications = new Apoc_Notifications( get_current_user_id() );
	return $notifications->notifications;
}
