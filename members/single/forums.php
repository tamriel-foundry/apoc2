<?php 
/**
 * Apocrypha Theme Profile Forums Component
 * Andrew Clayton
 * Version 2.0
 * 10-11-2014
 */
?>

<nav class="reply-header" id="subnav">
	<ul id="profile-tabs" class="tabs" role="navigation">
		<?php bp_get_options_nav(); ?>
	</ul>
</nav><!-- #subnav -->

<div id="forums" class="profile-forums" role="main">
	
	<?php // Topics Created
	if ( 'topics' == bp_current_action() ) :
	bbp_get_template_part( 'user', 'topics-created' );
	
	// Replies
	elseif ( 'replies' == bp_current_action() ) :
	bbp_get_template_part( 'user', 'replies-created' );			
	
	// Favorites
	elseif ( 'favorites' == bp_current_action() ) :
	bbp_get_template_part( 'user', 'favorites' );				
	
	// Subscriptions
	elseif ( 'subscriptions' == bp_current_action() ) :
	bbp_get_template_part( 'user', 'subscriptions' );				
	endif; ?>
	
</div>	