<?php 
/**
 * Apocrypha Theme Widgets
 * Andrew Clayton
 * Version 2.0
 * 7-28-2014
 */
 
/*--------------------------------------------------------------
	1.0 - Welcome Widget
--------------------------------------------------------------*/
function apoc_sidebar_welcome() {
	echo '<div id="sidebar-welcome" class="widget">Welcome to Tamriel Foundry, your home for theorycrafting, strategies, guides, and discussion in The Elder Scrolls Online.</div>';
}

/*--------------------------------------------------------------
	2.0 - Social Connections Widget
--------------------------------------------------------------*/
function apoc_sidebar_social() {

	// Define the data
	$socials = array(
		'YouTube' 	=> array(
			'url'	=> '#',
			'icon'	=> 'fa-youtube-square' ),
		'Facebook'	=> array(		
			'url'	=> '#',
			'icon'	=> 'fa-facebook-square' ),
		'Twitter'	=> array(
			'url'	=> '#',
			'icon'	=> 'fa-twitter-square' ),
		'Google+'	=> array(
			'url'	=> '#',
			'icon'	=> 'fa-google-plus-square' ),
		);
		
	// Build an array of links
	$links = array();
	foreach ( $socials as $name => $data ) {
		$links[] = '<a class="social-widget-link" href="' . $data['url'] . '"><i class="fa ' . $data['icon'] . '"></i>' . $name . '</a>';	
	}
	$links = implode( "|" , $links ); 
	
	// Display the output ?>
	<div class="widget">
		<header class="widget-header">
			<h3 class="widget-title">Get Connected</h3>
		</header>
		<div class="social-widget-links">
			<?php echo $links; ?>
		</div>
	</div><?php
}

/*--------------------------------------------------------------
	3.0 - Recent Topics Widget
--------------------------------------------------------------*/
class Apoc_Recent_Discussion {

	// Declare properties
	public $html 	= "";
	public $number 	= 5;
	public $size 	= 40;
	public $cached 	= false;
	
	// Constructor function
	function __construct() {
	
		// Get the topics
		$this->get_topics();
		
		// Print the output
		$this->display_widget();
	}

	
	// Get recent topics
	function get_topics() {
	
		// Depends on both bbPress and BuddyPress
		if ( !class_exists( 'bbPress' ) | !class_exists( 'BuddyPress' ) ) return false;
		
		// Try to retrieve the widget from the cache
		$widget = wp_cache_get( 'recent_discussion_topics' , 'apoc' );
		if ( $widget ) {
			$this->cached = true;
		}
		
		// Otherwise build from scratch
		else {
		
			// Setup query args
			$args = array(
				'posts_per_page'	=> $this->number,
				'show_stickies'		=> false,
				'max_num_pages'		=> 1,
			);
			
			// If topics are found, build the HTML
			if ( bbp_has_topics( $args ) ) {
				$this->html = $this->build_html();
				
				// Store the new HTML in the cache with 1 minute expiration
				wp_cache_set( 'recent_discussion_topics' , $this->html , 'apoc' , 60 );
			}
		}
	}

	// Format the topics into a widget
	function build_html() {

		// Store everything in an output buffer
		ob_start(); ?>	
		<div id="recent-discussion-widget" class="widget">
			<header class="widget-header">
				<h3 class="widget-title">Recent Discussion</h3>
			</header>
			<ul class="recent-discussion-list">		
	
			<?php // Iterate topics
			while ( bbp_topics() ) : bbp_the_topic(); ?>
				
				<li class="recent-discussion">			
					<h5 class="recent-discussion-title"><a href="<?php bbp_topic_last_reply_url(); ?>" title="Read <?php bbp_topic_title(); ?>"><?php bbp_topic_title(); ?></a></h5>
					<span class="recent-discussion-location"><i class="fa fa-tag"></i><?php bbp_forum_title( bbp_get_topic_forum_id() ); ?></span>
					<span class="recent-discussion-time"><?php bbp_topic_last_active_time() ?></span>
				</li>
				
			<?php endwhile; ?>
			</ul>
		</div><?php 
		
		// Get the contents of the buffer
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
function apoc_recent_discussion() {
	new Apoc_Recent_Discussion();
}

/*--------------------------------------------------------------
	4.0 - Online Members Widget
--------------------------------------------------------------*/
class Apoc_Online_Members {

	// Declare properties
	public $html 	= "";
	public $number 	= 10;
	public $cached 	= false;
	
	// Constructor function
	function __construct() {
	
		// Get the members
		$this->get_members();
		
		// Print the output
		$this->display_widget();
	}
	
	// Get online members
	function get_members() {
		
		// Depends on BuddyPress
		if ( !class_exists( 'BuddyPress' ) ) return false;
		
		// Try to retrieve the widget from the cache
		$widget = wp_cache_get( 'online_members' , 'apoc' );
		if ( $widget ) {
			$this->cached = true;
		}
		
		// Otherwise build from scratch
		else {
		
			// Setup query args
			$args = array(
				'type'				=> 'online',
				'per_page'			=> $this->number,
				'populate_extras'	=> false,
			);
			
			// If topics are found, build the HTML
			if ( bp_has_members( $args ) ) {
				$this->html = $this->build_html();
				
				// Store the new HTML in the cache with 1 minute expiration
				wp_cache_set( 'online_members' , $this->html , 'apoc' , 60 );
			}
		}		
	}
	
	// Format the topics into a widget
	function build_html() {
	
		// Get the total member count
		global $members_template;
		$total = $members_template->total_member_count;
		
		// Add everything to an array
		$members = array();
		while ( bp_members() ) : bp_the_member();
			$members[] = sprintf( '<a href="%1$s" title="%2$s User Profile">%2$s</a>' , bp_get_member_permalink() , bp_get_member_name() );
		endwhile;
		
		if ( $total > $this->number ) :
			$members[] = sprintf( '<a href="%1$s" title="View all online members">%2$d More</a>' , bp_get_members_directory_permalink() , $total - $this->number );
		endif;
		
		// Implode the members with separator
		$members = implode( "|" , $members );

		// Store everything in an output buffer
		ob_start(); ?>	
		<div class="widget">
			<header class="widget-header">
				<h3 class="widget-title">Online Members</h3>
			</header>
			
			<p class="online-members"><i class="fa fa-users"></i>
				<?php if ( $total > 1 ) :	printf( 'There are currently <span class="activity-count">%d</span> members online:' , $total );
				elseif ( $total == 1 ) :	printf( 'There is currently <span class="activity-count">%d</span> member online:' , $total );
				else : 						echo 'There are currently no members online:';
				endif; ?>
			</p>
			
			<p class="online-members"><?php echo $members; ?></p>
		</div><?php 
		
		// Get the contents of the buffer
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
function apoc_sidebar_members() {
	new Apoc_Online_Members();
}


/*--------------------------------------------------------------
	5.0 - Featured Stream
--------------------------------------------------------------*/
class Apoc_Featured_Stream {

	// Declare properties
	public $html 	= "";
	public $cached 	= false;
	
	// Constructor function
	function __construct() {
	
		// Get the members
		$this->get_stream();
		
		// Print the output
		$this->display_widget();
	}

	// Get featured stream
	function get_stream() {
		
		// Try to retrieve the widget from the cache
		$widget = wp_cache_get( 'featured_stream' , 'apoc' );
		if ( $widget ) {
			$this->cached = true;
		}
		
		// Otherwise build from scratch
		else {
		
			// List of valid streams as username => twitch name
			$streams = array(
				'admin' 			=> 'atropos_nyx', 
				'subscriber' 		=> 'erlexx', 
				'subscriber' 		=> 'phazius', 
			);
			
			// Shuffle the array
			$keys 	= array_keys( $streams );
			shuffle( $keys );
			$new	= array();
			foreach($keys as $key) $new[$key] = $streams[$key];
			$streams = $new;			

			// Loop through streamers, checking for online status
			foreach ( $streams as $username => $twitch ) {
				
				// Get Twitch data
				$twitch_response 	= json_decode( @file_get_contents( "https://api.twitch.tv/kraken/streams/" . $twitch ) );
				$is_online			= isset( $twitch_response->stream->game );		

				// Are they live?
				if ( $is_online ) break;
			}
			
			// Get the user
			$user = get_user_by( 'slug' , $username );
			
			// Construct data array
			$stream_data	= array(
				'user_id'	=> $user->ID,
				'name'		=> $user->display_name,
				'online'	=> $is_online,
				'url'		=> 'http://twitch.tv/'.$twitch,
				'viewers'	=> $is_online ? intval( $twitch_response->stream->viewers ) . " Viewers" : "Offline",
				'class'		=> $is_online ? 'online' : 'offline',
			);
			
			// Build the HTML
			$this->html = $this->build_html( $stream_data );
			
			// Store the new HTML in the cache with 5 minute expiration
			wp_cache_set( 'featured_stream' , $this->html , 'apoc' , 300 );
		}
	}
		
	// Format the stream into a widget		
	function build_html( $stream_data ) {
	
		// Get the data
		extract( $stream_data , EXTR_SKIP );
	
		// Store everything in an output buffer
		ob_start(); ?>	
		<div class="widget">
			<header class="widget-header">
				<h3 class="widget-title">Featured Stream</h3>
			</header>
			<div class="featured-stream-content <?php echo $class; ?>">
				<a class="featured-stream-name" href="<?php echo $url; ?>" target="_blank">
					<?php echo $name; ?>
					<span class="featured-stream-count"><i class="fa fa-power-off"></i><?php echo $viewers; ?></span>
				</a>

			</div>
		</div><?php 
		
		// Get the contents of the buffer
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
function apoc_sidebar_streams() {
	new Apoc_Featured_Stream();
}

/*--------------------------------------------------------------
	6.0 - Featured Group
--------------------------------------------------------------*/
function apoc_sidebar_group() {
	echo '<div class="widget"><header class="widget-header"><h3 class="widget-title">Featured Group</h3></header>Group here</div>';
}

/*--------------------------------------------------------------
	6.0 - Member Stats
--------------------------------------------------------------*/
function apoc_sidebar_stats() {
	echo '<div class="widget"><header class="widget-header"><h3 class="widget-title">Foundry Stats</h3></header>Stats here</div>';
}


/*--------------------------------------------------------------
	7.0 - PayPal Donation
--------------------------------------------------------------*/
function apoc_donate_box() {

	// Get the user's name
	$user		= apoc()->user;
	$user_id	= $user->ID;
	$name		= ( $user_id == 0 ) ? 'Anonymous' : $user->data->display_name;

	// Echo the HTML ?>
	<div class="widget donate-widget">
		<header class="widget-header"><h3 class="widget-title">Support Us!</h3></header>
		<p>Please donate to help fund Tamriel Foundry and support further community improvements!</p>
		<form id="donation-form" action="https://www.paypal.com/cgi-bin/webscr" method="post" target="_top">
			<input type="hidden" name="cmd" value="_donations">
			<input type="hidden" name="business" value="admin@tamrielfoundry.com">
			<input type="hidden" name="lc" value="US">
			<input type="hidden" name="item_name" value="Tamriel Foundry">
			<input type="hidden" name="item_number" value="Donation From <?php echo $name; ?> (<?php echo $user_id; ?>)">
			<input type="hidden" name="currency_code" value="USD">
			<input type="hidden" name="user_id" value="<?php echo $user_id; ?>">
			<input type="image" id="donate-image" src="<?php echo THEME_URI . '/images/icons/donate.png'; ?>" border="0" name="submit" width="200" height="50" alt="Donate to support Tamriel Foundry!">
		</form>
	</div>
	<?php
}
