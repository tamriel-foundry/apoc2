<?php 
/**
 * Apocrypha Theme Topics Archive
 * Andrew Clayton
 * Version 2.1
 * 6-22-2015
 */
?>

<?php get_header(); ?>
<div id="content" role="main">
	<?php apoc_breadcrumbs(); ?>

	<div id="forums">

		<?php // Recent topics found
		if ( bbp_has_topics( array( 
			'post_parent__not_in' => array( 5459 ),
			'meta_value'		=> date( 'Y-m-d' , strtotime( '-30 days' )),
			'meta_compare'		=> '>=',
		) ) ) : ?>
			<?php bbp_get_template_part( 'loop',       'topics'    ); ?>
			<?php bbp_get_template_part( 'pagination', 'topics'    ); ?>

		<?php // No topics found
		else : ?>	
			<p class="warning">Sorry, but no recent topics were found.</p>
		<?php endif; ?>

	</div><!-- #forums -->	
</div><!-- #content -->
<?php get_footer(); ?>