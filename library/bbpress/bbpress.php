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

		// Remove bbPress scripts and styles
		remove_action( 'wp_enqueue_scripts' , 'bbp_enqueue_scripts'  );

		// Increment Favorite Counts
		add_action( 'bbp_add_user_favorite' 	, array( $this , 'fav_count_plus' )	, 10 , 2 );
		add_action( 'bbp_remove_user_favorite' 	, array( $this , 'fav_count_minus' ), 10 , 2 );
	}
	
	
	/**
	 * Modify global bbPress filters
	 */
	function filters() {

		// Subscribe and Favorite Buttons
		add_filter( 'bbp_before_get_user_favorites_link_parse_args' 	, array( $this , 'favorite_button' ) );
		add_filter( 'bbp_before_get_user_subscribe_link_parse_args' 	, array( $this , 'subscribe_button' ) );
		add_filter( 'bbp_is_subscriptions'								, array( $this , 'subscriptions_component' ) );

		// Prevent Self-Favoriting
		add_filter( 'bbp_get_user_favorites_link' 						, array( $this , 'no_self_favorite' ) , 10 , 4 );
		
		// Revision Logs
		add_filter( 'bbp_get_reply_revision_log'						, array( $this , 'revision_log' ) );
		add_filter( 'bbp_get_topic_revision_log'						, array( $this , 'revision_log' ) );

		// Allow additional formatting options
		add_filter( 'bbp_kses_allowed_tags'								, array( $this , 'allowed_kses' ) );
		
		// Quote Mentions
		add_filter( 'bbp_activity_reply_create_excerpt' 				, array( $this , 'quote_mention' ) );
		
		// Block topic spam
		add_filter( 'bbp_new_topic_pre_title' 	, array( $this , 'block_spam' ) );
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

	/**
	 * Prevent users from favoriting their own posts
	 * @version 2.0
	 */
	
	function no_self_favorite( $html, $r, $user_id, $topic_id ) {

		// Prevent a topic author from favoriting him/herself
		if ( $user_id == bbp_get_topic_author_id() )
			return false;
		
		// Otherwise, allow the link
		else return $html;
	}

	/**
	 * Increment Topic Favorite Counts
	 * @version 2.0
	 */
	function fav_count_plus( $user_id , $topic_id ) {
		
		// Get the favorite count, converting missing to zero
		$count = (int) get_post_meta( $topic_id , 'topic_fav_count' , true );
		
		// Save the incremented value
		update_post_meta( $topic_id , 'topic_fav_count' , ++$count );
	}
	
	/**
	 * Decrement Topic Favorite Counts
	 * @version 2.0
	 */	
	function fav_count_minus( $user_id , $topic_id ) {
		
		// Get the favorite count, converting missing to zero
		$count = (int) get_post_meta( $topic_id , 'topic_fav_count' , true );
		
		// Don't let the count go below zero
		$count = max( $count , 1 );
		
		// Save the decremented value
		if ( $count > 1 )	update_post_meta( $topic_id , 'topic_fav_count' , --$count );
			
		// If the count would be going to zero, just delete the postmeta entirely
		else 				delete_post_meta( $topic_id , 'topic_fav_count' );
	}


	/**
	 * Prepend an icon to the revision log
	 * @version 2.0
	 */

	function revision_log( $revision ) {
		$revision = str_replace( 'revision-log">' , 'revision-log icons-ul double-border top">' , $revision );
		$revision = str_replace( 'revision-log-item">' , 'revision-log-item"><i class="fa fa-edit"></i>' , $revision );
		return $revision;
	}

	/**
	 * Special bbPress allowed KSES
	 * @version 2.0
	 */
	function allowed_kses( $allowed ) {
		$allowed['div']['class']	= array();
		$allowed['div']['style']	= array();
		$allowed['p']['class']		= array();
		$allowed['p']['style']		= array();
		$allowed['h1']['style']		= array();
		$allowed['h2']['style']		= array();
		$allowed['h3']['style']		= array();
		$allowed['h4']['style']		= array();
		$allowed['h5']['style']		= array();
		$allowed['h6']['style']		= array();
		$allowed['span']['style']	= array();
		return $allowed;
	}


	/** 
	 * Modify reply content when it is passed to the activity stream
	 * Includes quote mentions before stripping quotes
	 * @version 2.0
	 */
	function quote_mention( $reply_content ) {
		
		// Match the pattern for quote shortcodes
		$thequote = '#\[quote(.*)\](.*)\[\/quote\]#is';
		if ( preg_match( $thequote , $reply_content ) ) :
		
			// If there are quotes found, match the quoted usernames
			$author_pattern = '#(?<=\[quote author=")(.+?)(?=\|)#i';
			preg_match_all( $author_pattern , $reply_content , $authors );
			
			// For each username, turn it into a mention
			if ( isset( $authors ) ) :
				$authors = array_unique( $authors[0] );
				count( $authors ) > 1 ? $grammar = ' were quoted:' : $grammar = ' was quoted:';
				$mentions = implode( ",@" , $authors );
				$mentions = str_replace( " ", "-", $mentions );
				$mentions = str_replace( ".", "-", $mentions );
				$mentions = '<p><span class="activity-quote-mention">@'. $mentions . $grammar . '</span></p>';
			endif;
			
			// Add the mentions to the content and register them with BuddyPress
			$reply_content = $mentions . $reply_content ;
			$reply_content = strip_shortcodes( $reply_content );
			$reply_content = bp_activity_at_name_filter( $reply_content );
		endif;
		
		// Return the excerpt
		return $reply_content;
	}

	/**
	 * Block certain recurring spam topics
	 * @version 2.0
	 */	
	function block_spam( $topic_title ) {
	
		// Set up an array of banned words
		$illegals = array(
			'vashikaran',
			'baba ji',
			'love problem',
			'marriage problem',
			'+91',
			'+91',
			'+O99',
			'91-85',
			'91-99',
			'919914',
		);
		
		// Get the all-lowercase title
		$spam_title = strtolower( $topic_title );
		
		// Check for any of the illegals in the title
		foreach ( $illegals as $illegal ) {
			if ( strpos( $spam_title , $illegal ) !== false ) {
			
				// If the topic matches as spam, let's ban the user
				$user = new WP_User( get_current_user_id() );
				$user->set_role('banned');	
				
				// Send an email letting me know
				$headers 	= "From: Foundry Discipline Bot <noreply@tamrielfoundry.com>\r\n";
				$headers	.= "Content-Type: text/html; charset=UTF-8";
				$subject 	= 'User ' . $user->user_login . ' banned for spamming.';
				$body 		= 'The user ' . bp_core_get_userlink( $user->ID ) . ' was banned for attempting to post the topic: "' . $topic_title . '".';
				wp_mail( 'atropos@tamrielfoundry.com' , $subject , $body , $headers );
			
				// Trigger an error, preventing the topic from posting
				bbp_add_error( 'apoc_topic_spam' , '<strong>ERROR</strong>: Die, filthy spammer!' );
				
				// Log the user out
				wp_logout();
				break;
			}
		}
	
		// Otherwise go ahead!	
		return $topic_title;
	}
}

// Automatically invoke the class
new Apoc_bbPress();


/**
 * Display nested subforums with a hierarchical structure using their parent category
 * @version 2.0
 */	
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


/*---------------------------------------------
3.0 - SINGLE TOPICS
----------------------------------------------*/
function apoc_topic_header_class( $topic_id = 0 ) {
	$topic_id = bbp_get_topic_id( $topic_id );
	
	// Generate some classes
	$classes = array();
	$classes[] = 'page-header-' . rand(1,6);
	$classes[] = bbp_is_topic_sticky( $topic_id, false ) ? 'sticky'       : '';
	$classes[] = bbp_is_topic_super_sticky( $topic_id  ) ? 'super-sticky' : '';
	$classes[] = 'status-' . get_post_status( $topic_id );
	
	// Output it
	echo join( ' ', $classes );
}


/* 
 * Display a custom freshness block for subforums
 * @version 2.0
 */
function apoc_topic_byline( $args = '' ) {

	// Default arguments
	$defaults = array (
		'topic_id'  => 0,
		'before'    => '<p class="post-byline">',
		'after'     => '</p>',
		'size'		=> 50,
		'echo'		=> true,
	);
	$args = wp_parse_args( $args, $defaults );

	// Validate topic_id
	$topic_id = bbp_get_topic_id( $args['topic_id'] );

	// Get the author avatar
	$avatar 		= apoc_get_avatar( array( 'user_id' => bbp_get_topic_author_id() , 'size' => $args['size'] ) );

	// Build the topic description
	$voice_count	= bbp_get_topic_voice_count ( $topic_id );
	$reply_count	= bbp_get_topic_reply_count ( $topic_id , true ) + 1;
	$time_since  	= bbp_get_topic_freshness_link ( $topic_id );
	$author			= bbp_get_author_link( array( 'post_id' => $topic_id , 'type' => 'name' ) );

	// Singular/Plural
	$reply_count = sprintf( _n( '%d posts' , '%d posts', $reply_count ) 	, $reply_count );
	$voice_count = sprintf( _n( '%s member', '%s members', $voice_count	) 	, $voice_count );

	// Topic has replies
	$last_reply = bbp_get_topic_last_active_id( $topic_id );
	if ( !empty( $last_reply ) ) :
		$last_updated_by = bbp_get_author_link( array( 'post_id' => $last_reply, 'type' => 'name' ) );
		$retstr = sprintf( 'This topic by %1$s contains %2$s by %3$s, and was last updated by %4$s, %5$s.', $author, $reply_count, $voice_count, $last_updated_by, $time_since );

	// Topic has no replies
	elseif ( ! empty( $voice_count ) && ! empty( $reply_count ) ) :
		$retstr = sprintf( 'This topic contains %1$s by %2$s.', $reply_count, $voice_count );

	// Topic has no replies and no voices
	elseif ( empty( $voice_count ) && empty( $reply_count ) ) :
		$retstr = sprintf( 'This topic has no replies yet.' );
	endif;

	// Combine the elements together
	$retstr = $args['before'] . $avatar . '<span>' . $retstr . '</span>' . $args['after'];

	// Return filtered result
	if ( true == $args['echo'] )
		echo $retstr;
	else
		return $retstr;
}


/**
 * Output custom bbPress admin links
 * @version 2.0
 */
function apoc_reply_admin_links( $reply_id ) {
	
	// Make sure it's a logged-in user
	if ( !is_user_logged_in() ) return false;
		
	// Get post id and setup desired links
	$links = array();
	
	// Add common quote and reply links except on forum profiles
	if ( !bp_is_forums_component() ) :
		$links['quote'] 	= apoc_quote_button( 'reply' , $reply_id );
		$links['reply']		= '<a class="scroll-respond button button-dark" href="#new-post" title="Quick Reply"><i class="fa fa-reply"></i>Reply</a>';
	endif;
	
	// Topic admin links
	if( bbp_is_topic( $reply_id ) ) :
		$links['edit'] 		= bbp_get_topic_edit_link  ( array( 
								'id'			=> $reply_id,
								'edit_text' 	=> '<i class="fa fa-pencil"></i>Edit' ) );
		$links['close']		= bbp_get_topic_close_link ( array( 
								'id'			=> $reply_id,
								'close_text'	=> '<i class="fa fa-lock"></i>Close',
								'open_text'		=> '<i class="fa fa-unlock"></i>Open',		
								) );
		$links['stick']		= bbp_get_topic_stick_link ( array(
								'id'			=> $reply_id,
								'stick_text' 	=> '<i class="fa fa-thumb-tack"></i>Stick',
								'unstick_text' 	=> '<i class="fa fa-level-down"></i>Unstick',
								'super_text' 	=> '<i class="fa fa-paperclip"></i>Notice', ) );
		$links['merge']		= bbp_get_topic_merge_link ( array( 'merge_text'=> '<i class="fa fa-code-fork"></i>Merge') );
		$links['trash']		= bbp_get_topic_trash_link ( array(
								'id'			=> $reply_id,
								'trash_text' 	=> '<i class="fa fa-trash"></i>Trash',
								'restore_text' 	=> '<i class="fa fa-undo"></i>Restore',
								'delete_text' 	=> '<i class="fa fa-remove"></i>Delete',
								'sep'			=> '',
								) );
									
	// Reply admin links
	else :
		$links['edit'] 		= bbp_get_reply_edit_link (	array( 
								'id'			=> $reply_id,
								'edit_text'  	=> '<i class="fa fa-pencil"></i>Edit' ) );
		$links['move'] 		= bbp_get_reply_move_link (	array( 
								'id'			=> $reply_id,
								'split_text' 	=> '<i class="fa fa-arrows"></i>Move' ) );
		$links['split'] 	= bbp_get_topic_split_link( array( 
								'id'			=> $reply_id,
								'split_text' 	=> '<i class="fa fa-code-fork"></i>Split' ) );
		$links['trash'] 	= bbp_get_reply_trash_link( array( 
								'id'			=> $reply_id,
								'trash_text' 	=> '<i class="fa fa-trash"></i>Trash',
								'restore_text' 	=> '<i class="fa fa-undo"></i>Restore',
								'delete_text' 	=> '<i class="fa fa-remove"></i>Delete',
								'sep'			=> '',
								) );
	endif;
	
	// Get the admin links!
	bbp_reply_admin_links( array(
		'id'		=> $reply_id,
		'before'	=> '',
		'after'		=> '',
		'sep'		=> '',
		'links'		=> $links,
	));
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