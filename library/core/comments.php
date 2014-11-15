<?php 
/**
 * Apocrypha Theme Context Functions
 * Andrew Clayton
 * Version 2.0
 * 4-29-2014
 */
 
// Exit if accessed directly
if ( !defined( 'ABSPATH' ) ) exit;

/*---------------------------------------------
	1.0 - COMMENTS LOOP
----------------------------------------------*/

/**
 * Generate a number sensitive link to article comments
 * @since 2.0
 */
function apoc_comments_link() {
	
	// Get the comment count
	$count = doubleval( get_comments_number() );
	
	// Generate the link
	$link = '<a class="comments-link" href="' . get_comments_link() . '" title="Article Comments"><i class="fa fa-comment"></i>';
	
	// Context sensitive
	if( $count == 0 ) :
		$link .= 'Leave a Comment';
	elseif ( $count >= 1 ) :
		$link .= 'Comments<span class="activity-count">' . $count . '</span>';
	endif;
	$link .= '</a>';
	
	// Echo the link
	echo $link;
}

/**
 * Set up arguments for wp_list_comments() used in the comments template
 * @version 2
 */
function apoc_comments_args() {

	$args = array(
		'style'        		=> 'ol',
		'type'        		=> 'all',
		'per_page'			=> get_option('comments_per_page'),
		'reverse_top_level'	=> false,
		'avatar_size'  		=> 100,
		'callback'     		=> 'apoc_comments_template',
		'end-callback' 		=> ''
	);

	return $args;
}

/**
 * Callback function for choosing the comment template
 * @version 2
 */
function apoc_comments_template( $comment , $args , $depth ) {
	
	// Determine the post type for this comment
	$apoc = apoc();
	$post_type 		= isset( $apoc->post_type ) ? $apoc->post_type : 'post';
	$comment_type 	= get_comment_type( $comment->comment_ID );
	
	// Is the comment count already set?
	if ( isset( $apoc->counts['comment'] ) )
		$count = ++$apoc->counts['comment'];
		
	// If not, compute the correct count
	else {
	
		// Get comment page
		$apoc->counts['cpage'] = ( '' == $args['page'] ) ? get_query_var('cpage') : $args['page'];

		// Adjust the count
		$adj = ( $apoc->counts['cpage'] - 1 ) * $args['per_page'];
		$count = $adj + 1;
		
		// Update the object
		$apoc->counts['comment'] = $count;
	}
	
	// Load the comment template
	include( THEME_DIR . '/library/templates/comment.php' );
}

/*---------------------------------------------
	2.0 - COMMENTS ADMIN BUTTONS
----------------------------------------------*/

/**
 * Output the comment admin links
 * @version 2.0
 */
function apoc_comment_admin_links() {
	
	// Make sure it's a logged-in user
	if ( !is_user_logged_in() ) return false;
	
	// If so, go ahead
	global $comment;
	$links = apoc_quote_button( 'comment' );
	$links .= '<a class="scroll-respond button button-dark" href="#respond" title="Quick Reply"><i class="fa fa-reply"></i>Reply</a>';
	$links 	.= apoc_comment_edit_button();
	$links	.= apoc_comment_delete_button();
	echo $links;
}

/**
 * Quote button for comments and replies
 * @version 2.0
 */
function apoc_quote_button( $context = 'comment' , $post_id = 0 ) {

	// Get information by context
	switch( $context ) {
		
		// Article comments
		case 'comment' :
			global $comment;
			$id  			= $comment->comment_ID;
			$author_name 	= $comment->comment_author;
			$post_date 		= get_comment_date( 'F j, Y' , $comment->comment_ID );
			break;
		
		// Forum replies
		case 'reply' :
			$id  			= ( $post_id > 0 ) ? $post_id : bbp_get_reply_id();
			$author_name 	= bbp_get_reply_author( $id );
			$post_date 		= get_post_time( 'F j, Y' , false , $id, true );
			break;
	}

    // Create quote link using data attributes to pass parameters
	$quoteButton = '<a class="quote-link scroll-respond button" href="#respond" title="Click here to quote selected text" ';
	$quoteButton .= 'data-context="' . $context . '" data-id="'.$id.'" data-author="'.$author_name.'" data-date="'.$post_date.'">';
	$quoteButton .= '<i class="fa fa-comment"></i>Quote</a>';
    
	return $quoteButton;
}

/**
 * Edit button for comments
 * @version 2.0
 */
function apoc_comment_edit_button() {
	
	// Only show the button if the user can edit
	if ( user_can_edit_comment() ) {
	
		// Build the link
		global $comment;
		$parent_url 	= get_permalink( $comment->comment_post_ID );
		$edit_url 		= $parent_url . 'comment-' . $comment->comment_ID . '/edit/';
		$edit_button 	= '<a class="edit-comment-link button button-dark" href="' . $edit_url . '" title="Edit this comment" ><i class="fa fa-edit"></i>Edit</a>';
		return $edit_button;
	}
}

/**
 * Delete button for comments
 * @version 2.0
 */
function apoc_comment_delete_button() {
	
	// Only allow moderators to delete
	if ( current_user_can( 'moderate' ) || current_user_can( 'moderate_comments' ) ) {
	
		// Build the link
		global $comment;
		$delete_button = '<a class="delete-comment-link button button-dark" title="Delete this comment"  data-id="' . $comment->comment_ID . '" data-nonce="' . wp_create_nonce( 'delete-comment-nonce' ) . '"><i class="fa fa-trash"></i>Trash</a>';
		return $delete_button;
	}
}


/**
 * Generate post report buttons
 * @version 2.0
 */
function apoc_report_post_button( $type ) {
	
	// Only let members report stuff
	if ( !is_user_logged_in() ) return false;
	
	// Get the data by context
	switch( $type ) {
		
		// Forum reply
		case 'reply' :
			$post_id		= bbp_get_reply_id();
			$reported_user	= bbp_get_reply_author();
			$post_number 	= bbp_get_reply_position();
			break;
		
		// Article comment
		case 'comment' :
			global $comment, $comment_count;
			$post_id		= $comment->comment_ID;
			$reported_user	= $comment->comment_author;
			$post_number 	= $comment_count['count'];
			break;
		
		// Private message
		case 'message' :
			global $thread_template;
			$post_id		= $thread_template->message->thread_id;
			$reported_user	= $thread_template->message->sender_id;
			$post_number	= $thread_template->current_message + 1;
			break;
	}
	
	// Echo the button
	$button = '<a class="report-post" title="Report This Post" data-id="' . $post_id . '" data-number="' . $post_number . '" data-user="' . $reported_user . '" data-type="' . $type . '"><i class="fa fa-warning"></i></a>';
	echo $button;
}




/*---------------------------------------------
	3.0 - COMMENT EDIT CLASS
----------------------------------------------*/

/**
 * Frontend Article Comment Editing Class
 * @version 2.0
 */
class Apoc_Comment_Edit {

	// Construct the class
	function __construct() {
		add_action( 'init', array( &$this, 'generate_rewrite_rules' ) ); 
		add_action( 'init', array( &$this, 'add_rewrite_tags' ) ); 
		add_action( 'template_redirect', array( &$this , 'comment_edit_template' ) );
	}

	// Define the rule for parsing new query variables
	function add_rewrite_tags() {
		add_rewrite_tag( '%comment%' , '([0-9]{1,})' ); // Comment Number
	}
	
	// Define the rule for identifying comment edits
	function generate_rewrite_rules() {
		$rule	= '[0-9]{4}/[0-9]{2}/([^/]+)/comment-([0-9]{1,})/edit/?$';
		$query	= 'index.php?name=$matches[1]&comment=$matches[2]&edit=1';
		add_rewrite_rule( $rule , $query , 'top' );
	}

	
	// Redirect the template to use comment edit
	function comment_edit_template() {
		
		// Is this a comment edit?
		global $wp_query;
		if ( isset( $wp_query->query_vars['comment'] ) && $wp_query->query_vars['edit'] == 1 ) {
		
			// Get the comment
			$comment_id = $wp_query->query_vars['comment'];
			global $comment;
			$comment = get_comment( $comment_id  );
			
			// Can the user edit this comment?
			if ( user_can_edit_comment() ) 
				include ( THEME_DIR . '/library/templates/comment-edit.php' );
			else
				include ( THEME_DIR . '/404.php' );
			exit();
		}
	}
}
$comment_edit = new Apoc_Comment_Edit();

/**
 * Determines if the current user can edit a comment;
 * @version 2.0
 */
function user_can_edit_comment() {

	/* Check to see who can edit */
	global $comment;
	$user_id 	= get_current_user_id();
	$author_id 	= $comment->user_id;

	/* Comment authors and moderators are allowed */
	if ( $user_id == $author_id || current_user_can( 'moderate_comments' ) || current_user_can( 'moderate' ) ) 
		return true;
}

/**
 * Context function for detecting whether we are editing an article comment
 * @version 2.0
 */
function is_comment_edit() {
	global $wp_query;
	if ( isset( $wp_query->query_vars['comment'] ) && isset( $wp_query->query_vars['edit'] ) )
		return true;
	else return false;
}
