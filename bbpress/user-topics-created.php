<?php 
/**
 * Apocrypha Theme Profile Forum Topics
 * Andrew Clayton
 * Version 2.0
 * 10-11-2014
 */
?>

<?php if ( bbp_get_user_topics_started() ) : ?>
		
		<?php bbp_get_template_part( 'pagination', 'topics' ); ?>
		<?php bbp_get_template_part( 'loop',       'topics' ); ?>
		<?php bbp_get_template_part( 'pagination', 'topics' ); ?>
	
<?php else : ?>
	<p class="warning"><?php bbp_is_user_home() ? _e( 'You have not created any topics.', 'bbpress' ) : _e( 'This user has not created any topics.', 'bbpress' ); ?></p>
<?php endif; ?>