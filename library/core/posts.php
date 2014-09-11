<?php 
/**
 * Apocrypha Theme Posts Functions
 * Andrew Clayton
 * Version 2.0
 * 4-29-2014
 */
 
// Exit if accessed directly
if ( !defined( 'ABSPATH' ) ) exit;

function apoc_custom_excerpt() {


	$excerpt = get_the_excerpt();
	$limit = 55;
	$length = 300;

	while ( strlen( $excerpt ) >= $length ) {
		$limit = $limit - 1;
		$excerpt = wp_trim_words( $excerpt , $limit , NULL );
	}

	echo str_replace( '&hellip;' , ' [&hellip;]' , $excerpt );
}
