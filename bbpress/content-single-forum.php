<?php 
/**
 * Apocrypha Theme Single Forum Contents
 * Andrew Clayton
 * Version 2.0
 * 7-23-2014
 */
?>

<?php // Top-level categories
if ( bbp_is_forum_category() && bbp_has_forums() ) :?>
	<?php bbp_get_template_part( 'loop', 'single-forum' ); ?>

<?php // Single sub-forum
elseif ( !bbp_is_forum_category() ) : ?>
	
	<?php // Topics found
	if ( bbp_has_topics() ) : ?>
		<?php bbp_get_template_part( 'loop',       'topics' ); ?>
		<?php bbp_get_template_part( 'pagination', 'topics' ); ?>
	
	<?php // No topics found
	else : ?>
		<?php bbp_get_template_part( 'feedback', 'no-topics' ); ?>
	<?php endif; ?>
	
	<?php // New topic form
	bbp_get_template_part( 'form', 'topic' ); ?>
	
	
<?php // Empty subforum
elseif ( !bbp_is_forum_category() ) : ?>

<?php endif; ?>