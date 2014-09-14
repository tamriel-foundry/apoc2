<?php 
/**
 * Apocrypha Theme Homepage Template
 * Andrew Clayton
 * Version 2.0
 * 7-22-2014
 */
?>

<?php get_header(); ?>
	
	<div id="content" role="main">
		<?php if ( have_posts() ) : while ( have_posts() ) : the_post(); ?>
			
			<?php apoc_breadcrumbs(); ?>
			<article id="post-<?php the_ID(); ?>" class="post">
				<header class="post-header <?php apoc_post_header_class('post'); ?>">
					<h1 class="post-title"><?php the_title(); ?></h1>
					<p class="post-byline"><?php apoc_byline(); ?></p>
				</header>
				
				<section class="post-content double-border">
					<?php the_content(); ?>
				</section>	
				
				<footer class="post-footer">
					<span class="post-categories">
						<i class="fa fa-tags"></i>
						<?php echo get_the_term_list( get_the_ID() , 'category', 'Posted In: ', ', ', '' ); ?> 
					</span>
				</footer>
			</article>
			
		<?php endwhile; endif; ?>
	</div>

	<?php apoc_primary_sidebar(); ?>
	
	<?php comments_template( '/library/templates/comments.php', true ); ?>

<?php get_footer(); ?>