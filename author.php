<?php 
/**
 * Apocrypha Theme Homepage Template
 * Andrew Clayton
 * Version 2.0
 * 4-29-2014
 */

// Get the requested author
$author	= new Apoc_User( get_query_var( 'author' ) , 'profile' );
?>

<?php get_header(); ?>
	
	<div id="content" role="main">
		<?php apoc_breadcrumbs(); ?>

		<header id="archive-header" class="instructions">
			<h3 class="double-border"><?php printf( 'Articles By %s' , $author->fullname ); ?></h3>
			<div class="reply-body">
				<div class="reply-author">
					<?php echo $author->block; ?>
				</div>
				<div class="reply-content">
					<?php echo $author->bio; ?>
				</div>
			</div>
		</header>

		<div id="posts">
			<?php // Posts found for author
			if ( have_posts() ) :
				while ( have_posts() ) : the_post();
					apoc_single_post();
				endwhile; ?>

			<?php // No posts found for author
			else : ?>
				<div class="warning">Sorry, no posts were found for this author.</div>
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