<?php 
/**
 * Apocrypha Theme Single Topic Contents
 * Andrew Clayton
 * Version 2.0
 * 7-22-2014
 */
?>

<?php // Private Topic 
if ( post_password_required() ) : ?>

	<?php bbp_get_template_part( 'form', 'protected' ); ?>

		
<?php // Public Topic
else : ?>
	<?php if ( bbp_has_replies() ) : ?>
	<?php bbp_get_template_part( 'pagination', 'replies' ); ?>
	<?php bbp_get_template_part( 'loop',       'replies' ); ?>
	<?php bbp_get_template_part( 'pagination', 'replies' ); ?>
	<?php endif; ?>
	
	<?php bbp_get_template_part( 'form', 'reply' ); ?>
<?php endif; ?>