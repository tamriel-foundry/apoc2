<?php 
/**
 * Apocrypha Theme Context Functions
 * Andrew Clayton
 * Version 2.0
 * 4-29-2014
 */
 
// Exit if accessed directly
if ( !defined( 'ABSPATH' ) ) exit;

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



/* Temporary spoof */
function is_comment_edit() {

	return false;
}