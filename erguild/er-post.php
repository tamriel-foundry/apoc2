<?php 
/**
 * Entropy Rising Post or Page
 * Template Name: Entropy Rising Page
 * Post Template: Entropy Rising Post
 * Andrew Clayton
 * Version 2.0
 * 7-22-2014
 */
?>

<?php get_header('er'); ?>
	
	<div id="content" role="main">
		<?php if ( have_posts() ) : while ( have_posts() ) : the_post(); ?>
			
			<?php apoc_breadcrumbs(); ?>
			<article id="post-<?php the_ID(); ?>" class="post">

				<header class="post-header <?php apoc_post_header_class('post'); ?>">
					<h1 class="post-title"><?php the_title(); ?></h1>
					<p class="post-byline"><?php apoc_byline(); ?></p>
				</header>
				
				<section class="post-content <?php if ( 'post' == get_post_type() ) echo 'double-border'; ?>">
					<?php the_content(); ?>
				</section>	
				
				<?php if ( 'post' == get_post_type() ) : ?>
				<footer class="post-footer">
					<span class="post-categories">
						<i class="fa fa-tags"></i>
						<?php echo get_the_term_list( get_the_ID() , 'category', 'Posted In: ', ', ', '' ); ?> 
					</span>
				</footer>
				<?php endif; ?>

			</article>
			
		<?php endwhile; endif; ?>
	</div>

	<?php er_guild_sidebar(); ?>
	
	<?php if ( 'post' == get_post_type() ) comments_template( '/library/templates/comments.php', true ); ?>

<?php get_footer(); ?>