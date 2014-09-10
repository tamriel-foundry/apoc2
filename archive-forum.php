<?php 
/**
 * Apocrypha Theme Forums Archive
 * Andrew Clayton
 * Version 2.0
 * 5-10-2014
 */
?>

<?php get_header(); ?>
<div id="content" role="main">
	<?php apoc_breadcrumbs(); ?>

	<div id="forums">
	
		<?php // Forums present
		if ( bbp_has_forums() ) : ?>
			<?php while ( bbp_forums() ) : bbp_the_forum(); ?>
				<?php bbp_get_template_part( 'loop', 'single-forum' ); ?>
			<?php endwhile; ?>
	
		<?php // No forums found
		else : ?>	
			<p class="warning">Sorry, but no forums were found here.</p>
		<?php endif; ?>
	
	</div><!-- #forums -->	
</div><!-- #content -->
<?php get_footer(); ?>