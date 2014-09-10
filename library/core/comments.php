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
		$link .= 'Comments <span class="comments-link-count activity-count">' . $count . '</span>';
	endif;
	$link .= '</a>';
	
	// Echo the link
	echo $link;
}