<?php
/**
 * Apocrypha Theme bbPress Functions
 * Andrew Clayton
 * Version 2.0
 * 9-11-2014
*/

// Exit if accessed directly
if ( !defined( 'ABSPATH' ) ) exit;


class Apoc_bbPress {


	/**
	 * Construct the bbPress Class
	 * @since 2.0
	 */
	function __construct() {
		
		// Includes
		$this->includes();
		
		// Actions
		$this->actions();
		
		// Filters
		$this->filters();
	}
	
	/**
	 * Include required bbPress functions
	 */	
	function includes() {
	
	}
	
	
	/**
	 * Modify global bbPress actions
	 */
	function actions() {
		remove_action( 'wp_enqueue_scripts' , 'bbp_enqueue_scripts'  );
	}
	
	
	/**
	 * Modify global bbPress filters
	 */
	function filters() {

		// Subscribe and Favorite Buttons
		add_filter( 'bbp_before_get_user_favorites_link_parse_args' 	, array( $this , 'favorite_button' ) );
		add_filter( 'bbp_before_get_user_subscribe_link_parse_args' 	, array( $this , 'subscribe_button' ) );
		add_filter( 'bbp_is_subscriptions'								, array( $this , 'subscriptions_component' ) );
			
	}	





	/** 
	 * Apply custom styling to favorite and subscribe buttons
	 * @version 2.0
	 */
	function favorite_button( $r ) {
		$r = array (
			'favorite'		=> '<i class="fa fa-thumbs-up"></i>This Thread Rocks',
			'favorited'		=> '<i class="fa fa-thumbs-down"></i>This Got Ugly',
			'before'    	=> '',
			'after'     	=> '',
		);
		return $r;
	}
	function subscribe_button( $r ) {
		$r = array(
				'subscribe'		=> '<i class="fa fa-bookmark"></i>Subscribe',
				'unsubscribe'	=> '<i class="fa fa-remove"></i>Unsubscribe',
				'before'    	=> '',
				'after'     	=> '',
			);
		return $r;
	}
	function subscriptions_component() {
		if ( bp_is_user() ) return true;
		else return false;
	}

	
	
}

// Automatically invoke the class
new Apoc_bbPress();



function apoc_loop_subforums() {
	
	// Exclude private forums
	$private	= apoc_private_forum_ids();
	
	// Check for subforums
	$subs		= bbp_forum_get_subforums( array( 'post__not_in' => $private ) );
	if ( empty( $subs ) ) return;
	
	// Buffer output
	ob_start();
	
	// Print a header ?>
	<header class="forum-header">
		<div class="forum-content"><h2><?php bbp_forum_title(); ?></h2></div>
		<div class="forum-count">Topics</div>
		<div class="forum-freshness">Latest Post</div>
	</header>
	<ol class="forums category <?php bbp_forum_status(); ?>"><?php	
	
	// Loop over forums
	foreach ( $subs as $count => $sub ) :
		
		// Get forum details
		$sub_id			= $sub->ID;
		$title			= $sub->post_title;
		$desc			= $sub->post_content;
		$permalink		= bbp_get_forum_permalink( $sub_id );
		
		// Get topic counts
		$topics	 		= bbp_get_forum_topic_count( $sub_id , false );
		
		// Get the most recent reply and its topic
		$reply_id		= bbp_get_forum_last_reply_id( $sub_id );
		$topic_id		= bbp_is_reply( $reply_id ) ? bbp_get_reply_topic_id( $reply_id ) : $reply_id;
		$topic_title	= bbp_get_topic_title( $topic_id );
		$link 			= bbp_get_reply_url( $reply_id );

		// Get the author avatar
		$user_id 		= bbp_get_reply_author_id( $reply_id );
		$avatar			= apoc_get_avatar( array( 'user_id' => $user_id , 'link' => true , 'size' => 50 ));
		
		// Toggle html class
		$class			= ( $count % 2 ) ? 'odd' : 'even';
		
		// Print output ?>
		<li id="forum-<?php echo $sub_id ?>" class="forum <?php echo $class; ?>">
			<div class="forum-content">
				<h3 class="forum-title"><a href="<?php echo $permalink; ?>" title="Browse <?php echo $title; ?>"><?php echo $title; ?></a></h3>
				<p class="forum-description"><?php echo $desc; ?></p>
			</div>

			<div class="forum-count">
				<?php echo $topics; ?>
			</div>

			<div class="forum-freshness">
				<?php echo $avatar; ?>
				<div class="freshest-meta">
					<a class="freshest-title" href="<?php echo $link; ?>" title="<?php echo $topic_title; ?>"><?php echo $topic_title; ?></a>
					<span class="freshest-author">By <?php bbp_author_link( array( 'post_id' => $reply_id, 'type' => 'name' ) ); ?></span>
					<span class="freshest-time"><?php bbp_topic_last_active_time( $topic_id ); ?></span>
				</div>
			</div>
		</li>
	<?php endforeach; ?>
	</ol>
		
	<?php // Retrieve from buffer
	$output = ob_get_contents();
	ob_end_clean();
	echo $output;
}



function apoc_private_forum_ids() {
	
	$private = array();
	
	// Get the current user
	$user = apoc()->user;
	
	// Example - logged in only
	if ( $user->ID == 0 ) $private[] = 23;
	
	// Return the list of private forums
	$private = empty( $private ) ? '' : $private;
	return $private;
}