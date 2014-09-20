<?php 
/**
 * Apocrypha Theme Posts Functions
 * Andrew Clayton
 * Version 2.0
 * 4-29-2014
 */
 
// Exit if accessed directly
if ( !defined( 'ABSPATH' ) ) exit;



/**
 * Generates a class for the homepage headers.
 * Randomizes between the six artistic header images.
 * Ensures that each of the headers are only displayed once
 *
 * @version 2.0
 */
function apoc_post_header_class( $context ) {
	
	if( !isset( apoc()->post_headers ) ) {
		$headers = range( 1 , 6 );
		shuffle( $headers );
		apoc()->post_headers = $headers;
	}

	$header = array_shift( apoc()->post_headers );
	echo $context . '-header-' . $header;
}


/**
 * Generates a byline for posts and pages
 * @version 2.0
 */
function apoc_byline() {
	
	// Get the current post within the loop
	global $post;
	$post_ID 	= $post->ID;
	$author_ID 	= $post->post_author;
	$type 		= $post->post_type;
	$byline 	= '';
	
	// Posts 
	if ( $type == 'post' ) :
		
		// Get some info 
		$author 	= '<a class="post-author" href="' . get_author_posts_url( $author_ID ) . '" title="All posts by ' . get_the_author_meta( 'display_name' ) . '">' . get_the_author_meta( 'display_name' ) . '</a>';
		$published 	= '<time class="post-date" datetime="'. get_the_time( 'Y-m-d' ) . '">' . get_the_time( 'F j, Y' ) . '</time>';
		$edit_link 	= current_user_can( 'edit_post' , $post_ID ) ? '<a class="post-edit-link" href="' . get_edit_post_link( $post_ID ) . '" title="Edit this post" target="_blank">Edit</a>' : "";
		
		// Show a bunch of stuff for single views
		if ( is_single() ) :
			$avatar		= new Apoc_Avatar( array( 'user_id' => $author_ID , 'type' => 'thumb' , 'size' => 50 ) );
			$category = get_the_term_list( $post_ID, 'category', ' in ' , ', ', '' );
			$description = $avatar->avatar . '<span>By ' . $author . ' on ' . $published . $category . $edit_link . '</span>';			
		
		// Otherwise, a simple one-liner
		else : 
			$description = '<span>By ' . $author . ' on ' . $published . $edit_link . '</span>';

		endif;
			
	// Pages 
	elseif ( $type == 'page' ) :
		$description = get_post_meta( $post_ID , 'description' , true );
		if ( current_user_can( 'edit_post' , $post_ID ) )
			$description = $description . '<a class="post-edit-link" href="' . get_edit_post_link( $post_ID ) . '" title="Edit this post" target="_blank">Edit</a>';
	endif;
	
	// Echo the post description 
	echo $description;
}


/**
 * Restricts the excerpt length by characters rather than words
 * @version 2.0
 */
function apoc_custom_excerpt( $length = 300 ) {

	// Get the excerpt and it's default word count
	$excerpt = get_the_excerpt();
	$limit = 55;

	// Trim until we hit the target
	while ( strlen( $excerpt ) >= $length ) {

		// Converge quickly until we are close
		$limit = ( strlen( $excerpt ) - $length > 30 ) ? $limit - 5 : $limit - 1;
		
		// Trim off entire words until we get approximately the desired length
		$excerpt = wp_trim_words( $excerpt , $limit , NULL );
	}

	// Echo the post excerpt
	echo str_replace( '&hellip;' , ' [&hellip;]' , $excerpt );
}



 
