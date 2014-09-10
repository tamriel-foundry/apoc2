<?php 
/**
 * Apocrypha Theme Single Forum Template
 * Andrew Clayton
 * Version 2.0
 * 7-23-2014
 */
?>

<?php get_header(); ?>
<div id="content" role="main">
	<?php apoc_breadcrumbs(); ?>
	
	<?php do_action( 'bbp_template_notices' ); ?>
	
	<div id="forums">
	<?php if ( bbp_user_can_view_forum() ) : ?>
		<?php bbp_get_template_part( 'content', 'single-forum' ); ?>
	<?php else : ?>
		<?php bbp_get_template_part( 'feedback', 'no-access' ); ?>
	<?php endif; ?>
	</div><!-- #forums -->	
		
</div><!-- #content -->
<?php get_footer(); ?>