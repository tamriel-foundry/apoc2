<?php 
/**
 * Apocrypha Theme Profile Forum Replies
 * Andrew Clayton
 * Version 2.0
 * 10-11-2014
 */
?>

<?php if ( bbp_get_user_replies_created() ) : ?>
		
		<?php bbp_get_template_part( 'pagination', 'replies' ); ?>
		<?php bbp_get_template_part( 'loop',       'replies' ); ?>
		<?php bbp_get_template_part( 'pagination', 'replies' ); ?>
	
<?php else : ?>
	<p class="warning"><?php bbp_is_user_home() ? _e( 'You have not replied to any topics.', 'bbpress' ) : _e( 'This user has not replied to any topics.', 'bbpress' ); ?></p>
<?php endif; ?>