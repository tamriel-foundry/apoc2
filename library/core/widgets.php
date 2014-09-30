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
	echo '<div id="sidebar-welcome" class="widget">Welcome to Tamriel Foundry, a community dedicated to theorycrafting, strategies, guides, and discussion in The Elder Scrolls Online.</div>';
}

/*--------------------------------------------------------------
	2.0 - Social Connections Widget
--------------------------------------------------------------*/
function apoc_sidebar_social() {

	// Define the data
	$socials = array(
		'YouTube' 	=> array(
			'url'	=> 'https://www.youtube.com/user/TamrielFoundry',
			'icon'	=> 'fa-youtube-square' ),
		'Facebook'	=> array(		
			'url'	=> 'https://www.facebook.com/tamrielfoundry',
			'icon'	=> 'fa-facebook-square' ),
		'Twitter'	=> array(
			'url'	=> 'https://twitter.com/tamrielfoundry',
			'icon'	=> 'fa-twitter-square' ),
		'Google+'	=> array(
			'url'	=> 'https://plus.google.com/+tamrielfoundryESO',
			'icon'	=> 'fa-google-plus-square' ),
		);
		
	// Build an array of links
	$links = array();
	foreach ( $socials as $name => $data ) {
		$links[] = '<a class="social-widget-link" href="' . $data['url'] . '"><i class="fa ' . $data['icon'] . '"></i>' . $name . '</a>';	
	}
	$links = implode( $links ); 
	
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
	3.0 - Recent Discussion Widget
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
		$this->get_activity();
		
		// Print the output
		$this->display_widget();
	}

	
	// Get recent discussion activity
	function get_activity() {
	
		// Depends on both bbPress and BuddyPress
		if ( !class_exists( 'bbPress' ) | !class_exists( 'BuddyPress' ) ) return false;
		
		// Try to retrieve the widget from the cache
		$widget = wp_cache_get( 'recent_discussion' , 'apoc' );
		if ( $widget ) {
			$this->cached = true;
		}
		
		// Otherwise build from scratch
		else {

			// Declare valid actions
			$actions = array( 'bbp_topic_create' , 'bbp_reply_create' , 'new_blog_comment' );
		
			// Setup query args
			$args = array(
				'max'				=> $this->number,
				'action'			=> implode(',',$actions)
			);
			
			// If topics are found, build the HTML
			if ( bp_has_activities( $args ) ) {
				$this->html = $this->build_html();
				
				// Store the new HTML in the cache with 1 minute expiration
				wp_cache_set( 'recent_discussion' , $this->html , 'apoc' , 60 );
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
			while ( bp_activities() ) : bp_the_activity(); 

				// Get the activity user
				$user = new Apoc_User( bp_get_activity_user_id() , 'directory' , 40 );

				// Get the activity type
				$post_id 	= bp_get_activity_secondary_item_id();
				$type 		= bp_get_activity_type();

				// Format activity based on context
				switch( $type ) {
					case 'bbp_topic_create' :
						$link =  '<a href="' . bbp_get_topic_permalink( $post_id )  . '" title="Read topic" target="_blank">' . bbp_get_topic_title( $post_id ) . '</a>';
						$verb = 'created topic';	
						break;

					case 'bbp_reply_create' :
						$link =  '<a href="' . bbp_get_topic_last_reply_url( $post_id ) . '" title="Read reply" target="_blank">' . bbp_get_topic_title( $post_id ) . '</a>';
						$verb = 'replied to';
						break;

					case 'new_blog_comment' :
						$comment = get_comment( $post_id );
						$link =  '<a href="' . get_comment_link( $post_id ) . '" title="Read reply" target="_blank">' . get_the_title( $comment->comment_post_ID ) . '</a>';
						$verb = 'commented on';
						break;
				}
				
				// Get the activity time
				$time = bp_core_time_since( bp_get_activity_date_recorded() );

				// Output the HTML ?>
				<li class="recent-discussion double-border">			
					<?php echo $user->avatar ; ?>
					<div class="recent-discussion-content">
						<span class="recent-discussion-title"><?php echo $user->link . ' ' . $verb . ' ' . $link; ?></span>
						<span class="recent-discussion-time"><?php echo $time; ?>
					</div>
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
		$members = implode( " | " , $members );

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
				'atropos' 			=> 'atropos_nyx', 
				'testuser' 			=> 'erlexx', 
				'moderator' 		=> 'phazius', 
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
					<i class="fa fa-twitch"></i><?php echo $name; ?>
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
	echo '<div class="widget group-widget"><header class="widget-header"><h3 class="widget-title">Featured Group</h3></header><div class="featured-group">A recently active group!</div></div>';
}

/*--------------------------------------------------------------
	6.0 - Member Stats
--------------------------------------------------------------*/
class Apoc_Sidebar_Stats {

	// Declare properties
	public $stats	= array();
	public $html 	= "";
	public $cached 	= false;
	
	// Constructor function
	function __construct() {
	
		// Get the counts
		$this->stats = $this->get_stats();
		
		// Construct the html
		$this->html = $this->build_html();

		// Print the output
		$this->display_widget();
	}


	// Get member counts by faction group
	function get_stats() {

		// Try to retrieve the stats from the cache
		$stats = wp_cache_get( 'community_stats' , 'apoc' );
		if ( $stats ) {
			$this->cached = true;
		}
		
		// Otherwise build from scratch
		else {

			// Get the faction counts
			global $wpdb;
			$counts = $wpdb->get_results(
				"
				SELECT meta_value as 'group', COUNT(*) AS 'count' 
				FROM $wpdb->usermeta
				WHERE meta_key = 'faction'
				GROUP BY meta_value
				UNION ALL
				SELECT 'total' as 'group', COUNT(DISTINCT user_id) AS count
				FROM $wpdb->usermeta
				"
			, OBJECT_K );

			// Extract the data
			foreach ( $counts as $group => $count ) {
				$stats[$group] = $count->count;
			}

			// Make sure nothing is empty
			foreach ( array( 'aldmeri' , 'daggerfall' , 'ebonheart') as $faction ) {
				if ( !isset($stats[$faction]) ) $stats[$faction] = 1;
			}

			// Store the counts in the cache with 60 minute expiration
			wp_cache_set( 'community_stats' , $stats , 'apoc' , 3600 );
		}

		// Return the stats to the class object
		return $stats;
	}

	// Get member counts by faction group
	function build_html() {

		// Get the counts
		$a = $this->stats['aldmeri'];
		$d = $this->stats['daggerfall'];
		$e = $this->stats['ebonheart'];
		$t = $this->stats['total'];

		// Compute Banner Heights - normalize max to 150px 
		$l = max( $a , $d , $e );
		$ah = round( ( $a / $l ) * 100 ) + 50;
		$dh = round( ( $d / $l ) * 100 ) + 50;
		$eh = round( ( $e / $l ) * 100 ) + 50;

		// Get the groups slug
		$groups = SITEURL . '/groups/';

		// Store everything in an output buffer
		ob_start(); ?>	
	
			<div class="widget community-stats">
				<header class="widget-header">
					<h3 class="widget-title">Foundry Stats</h3>
				</header>
				
				<h3 class="stat-counter-total">Total Champions: <?php echo number_format( $t, 0 , '' , ',' ); ?></h3>

				<div id="stats-banner">
					<div class="banner-top aldmeri" style="height:<?php echo $ah; ?>px">
						<div class="banner-bottom aldmeri">
							<a class="banner-count" href="<?php echo $groups; ?>aldmeri-dominion" title="Aldmeri Dominion - <?php echo round( $a * 100 / $a + $d + $e ); ?>%"><?php echo number_format( $a , 0 , '' , ',' ); ?></a>
						</div>
					</div>
					<div class="banner-top daggerfall" style="height:<?php echo $dh; ?>px">
						<div class="banner-bottom daggerfall">
							<a class="banner-count" href="<?php echo $groups; ?>daggerfall-covenant" title="Daggerfall Covenant - <?php echo round( $d * 100 / $a + $d + $e ); ?>%"><?php echo number_format( $d , 0 , '' , ',' ); ?></a>
						</div>
					</div>
					<div class="banner-top ebonheart" style="height:<?php echo $eh; ?>px">
						<div class="banner-bottom ebonheart">
							<a class="banner-count" href="<?php echo $groups; ?>ebonheart-pact" title="Ebonheart Pact - <?php echo round( $e * 100 / $a + $d + $e ); ?>%"><?php echo number_format( $e , 0 , '' , ',' ); ?></a>
						</div>
					</div>
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
function apoc_sidebar_stats() {
	new Apoc_Sidebar_Stats();
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
		<p>Please donate to help support Tamriel Foundry, fund further improvements, and unlock account perks!</p>
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
