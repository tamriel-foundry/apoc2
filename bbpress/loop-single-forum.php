<?php 
/**
 * Apocrypha Theme Forums Archive
 * Andrew Clayton
 * Version 2.0
 * 5-10-2014
 */
?>
 
<?php // Top level categories
if ( bbp_get_forum_subforum_count() ) : ?>
	<?php apoc_loop_subforums(); ?>

<?php // Single subforums - not used
else : ?>
	<p><?php bbp_forum_title(); ?> is a single forum with no category.</p>
<?php endif; ?>