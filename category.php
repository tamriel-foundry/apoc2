<?php 
/**
 * Apocrypha Theme Category Archive Template
 * Andrew Clayton
 * Version 2.0
 * 9-20-2014
 */
?>

<?php get_header(); ?>
	
	<div id="content" role="main">
		<?php apoc_breadcrumbs(); ?>

		<header id="archive-header" class="instructions">
			<h3 class="double-border"><?php printf( 'Category Archives: %s' , single_cat_title( '', false ) ); ?></h3>
			<?php if ( category_description() ) : ?>
				<?php echo category_description(); ?>
			<?php else : ?>
				<p>Browse archived posts in this category.</p>
			<?php endif; ?>			
		</header>

		<div id="posts">
			<?php // Posts found in category
			if ( have_posts() ) :
				while ( have_posts() ) : the_post();
					apoc_single_post();
				endwhile; ?>

			<?php // No posts found in category
			else : ?>
				<div class="warning">Sorry, no posts were found for this category.</div>
			<?php endif; ?>
		</div>

		<nav class="pagination">
			<div class="pagination-links">
				<?php echo paginate_links( array('prev_text' => '&larr;', 'next_text' => '&rarr;') ); ?>
			</div>
		</nav>
	</div>

	<?php apoc_primary_sidebar(); ?>

<?php get_footer(); ?>