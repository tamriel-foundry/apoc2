<?php 
/**
 * Apocrypha Theme Single Page Template
 * Andrew Clayton
 * Version 2.0
 * 9-18-2014
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

			<section class="post-content">
				<?php the_content(); ?>
			</section>			
		</article>
			
		<?php endwhile; endif; ?>
	</div>

	<?php apoc_primary_sidebar(); ?>
<?php get_footer(); ?>