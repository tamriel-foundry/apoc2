<?php 
/**
 * Apocrypha Theme Profile Forum Favorites
 * Andrew Clayton
 * Version 2.0
 * 10-11-2014
 */
?>

<?php if ( bbp_get_user_favorites() ) : ?>
		
		<?php bbp_get_template_part( 'pagination', 'topics' ); ?>
		<?php bbp_get_template_part( 'loop',       'topics' ); ?>
		<?php bbp_get_template_part( 'pagination', 'topics' ); ?>
	
<?php else : ?>
	<p class="warning"><?php bbp_is_user_home() ? _e( 'You currently have no favorite topics.', 'bbpress' ) : _e( 'This user has no favorite topics.', 'bbpress' ); ?></p>
<?php endif; ?>