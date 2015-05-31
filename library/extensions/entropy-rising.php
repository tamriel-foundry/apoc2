<?php 
/**
 * Entropy Rising Guild Functions
 * Andrew Clayton
 * Version 2.0
 * 12-02-2014
 */
 
// Exit if accessed directly
if ( !defined( 'ABSPATH' ) ) exit;

/*---------------------------------------------
	1.0 - ENTROPY_RISING CLASS
----------------------------------------------*/

/**
 * Configures login, authentication, and security features
 *
 * @author Andrew Clayton
 * @version 2.0
 */
class Entropy_Rising {

	// Assign the Entropy Rising group ID
	public $group_id 	= 1;

	// Assign recruitment status
	public $recruiting 	= false;

	// Assign recruitment priorities
	public $priorities = array(
		'dragonknight' 	=> 'medium',
		'templar' 		=> 'medium',
		'sorcerer' 		=> 'medium',
		'nightblade' 	=> 'medium',
	);

 	/**
	 * Construct the class
	 * @version 2.0
	 */	
	function __construct() {
	
		// Get the Entropy Rising group from BuddyPress
		$this->group = groups_get_group( array( 'group_id' => $this->group_id , 'populate_extras' => true ) );

		// Remap membership flag
		$this->is_member = $this->group->is_member;

	}
}

/*---------------------------------------------
	2.0 - HELPER FUNCTIONS
----------------------------------------------*/

function er_is_member() {
	global $er;
	return $er->is_member;
}

function er_is_recruiting() {
	global $er;
	return $er->recruiting;
}

function er_recruitment_priorities() {
	global $er;
	return $er->priorities;
}


/**
 * Entropy Rising homepage have_posts query
 * @version 2.0
 */
function er_have_posts() {

	// Get the page and offset
	$posts_per_page = 6;
	$paged 			= ( get_query_var( 'paged' ) ) ? get_query_var( 'paged' ) : 1;
	$offset 		= ( $posts_per_page * $paged ) - $posts_per_page;
	
	// Format query arguments
	$args = array( 
		'paged'=> $paged, 
		'posts_per_page'=> $posts_per_page,
		'offset' => $offset,
		'cat' => get_cat_ID( 'guild news' ) . ',' . get_cat_ID('entropy rising'),
		);
		
	// Retrieve posts
	query_posts( $args );

	// Return whether there are posts
	return have_posts();
}

function er_guild_sidebar() {
	include( THEME_DIR . '/erguild/er-sidebar.php' );
}
function er_guild_menu() {
	include( THEME_DIR . '/erguild/er-menu.php' );
}

/*--------------------------------------------------------------
	3.0 - ER EVENTS WIDGET
--------------------------------------------------------------*/
class ER_Events_Widget {

	// Declare properties
	public $html 	= "";
	public $number 	= 3;
	public $cached 	= false;
	
	// Constructor function
	function __construct() {
	
		// Get the topics
		$this->get_events();
		
		// Print the output
		$this->display_widget();
	}

	
	// Get recent discussion activity
	function get_events() {
	
		// Depends on both bbPress and BuddyPress
		if ( !class_exists( 'bbPress' ) | !class_exists( 'BuddyPress' ) ) return false;
		
		// Try to retrieve the widget from the cache
		$widget = wp_cache_get( 'er_events' , 'apoc' );
		if ( $widget ) {
			$this->html = $widget;
			$this->cached = true;
		}
		
		// Otherwise build from scratch
		else {

			// Set up calendar arguments
			$args = array(
				
				// Get an unlimited number of events with any status
				'post_type'			=> 'event',
				'post_status'		=> 'any',
				'posts_per_page'	=> 3,
				'paged'				=> 1,

				// Restrict them to this specific calendar
				'tax_query' 		=> array(
					array(
						'taxonomy' 	=> 'calendar',
						'field' 	=> 'slug',
						'terms' 	=> 'entropy-rising',
					)),
			
				// Restrict to future dates
				'date_query'		=> array(
					array(
						'after'		=> date('Y-m-d',strtotime('yesterday')),
						'inclusive'	=> true,
					) ),
				'orderby'			=> 'date',
				'order'				=> 'ASC',
			);

			// Retrieve events
			$this->events = new WP_Query( $args );
			
			// If topics are found, build the HTML
			if ( $this->events->have_posts() ) {
				$this->html = $this->build_html();
				
				// Store the new HTML in the cache with 5 minute expiration
				wp_cache_set( 'er_events' , $this->html , 'apoc' , 300 );
			}
		}
	}

	// Format the topics into a widget
	function build_html() {

		// Get the events
		$events = $this->events;

		// Store everything in an output buffer
		ob_start(); ?>	

		<div id="er-events-widget" class="widget">
			<header class="widget-header">
				<h3 class="widget-title">Upcoming Events</h3>
			</header>

			<ol class="calendar">	
	
			<?php // Iterate topics
			while ( $events->have_posts() ) : $events->the_post();

				// Get date information
				$time	= get_the_date( 'ga' );
				$date	= get_the_date( 'm/d' );

				// Output the HTML ?>
				<li id="event-<?php the_ID(); ?>" class="event double-border">
					<div class="event-datetime">
						<span class="event-md"><?php echo $date; ?> - <?php echo $time; ?></span>
					</div>	
					<div class="event-content">
						<a href="<?php the_permalink(); ?>" title="View Event" ><?php the_title(); ?></a>
					</div>	
				</li>
			
			<?php endwhile; ?>
			</ol>
		</div>

		<?php // Get the contents of the buffer
		$html = ob_get_contents();
		ob_end_clean();
		
		// Return the html to the class
		return $html;
	}
	
	// Display the widget
	function display_widget() {
		if ( !empty( $this->html ) )
			echo $this->html;
	}
}
function er_events_widget() {
	new ER_Events_Widget();
}

