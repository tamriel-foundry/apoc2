<?php 
/**
 * Apocrypha Theme Homepage Template
 * Andrew Clayton
 * Version 2.0
 * 9-20-2014
 */
?>

<?php get_header(); ?>
	<div id="content" role="main">

		<div id="showcase-container">
			<div id="showcase" class="flexslider">
				Showcase - 512x288
			</div>
			<?php apoc_recent_discussion(); ?>
		</div>


		
		<div id="posts">
			<?php if ( have_posts() ) : while ( have_posts() ) : the_post(); ?>
				<?php apoc_single_post(); ?>
			<?php endwhile; endif; ?>
		</div>

		<nav class="pagination">
			<div class="pagination-links">
				<?php echo paginate_links( array('prev_text' => '&larr;', 'next_text' => '&rarr;') ); ?>
			</div>
		</nav>
	</div>

	<?php apoc_primary_sidebar(); ?>

<?php get_footer(); ?>