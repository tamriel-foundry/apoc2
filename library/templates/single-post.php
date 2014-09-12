<?php 
/**
 * Apocrypha Theme Single Post Template
 * Andrew Clayton
 * Version 2.0
 * 5-6-2014
 */
 
// Get the post class
global $wp_query;
$class = ( 0 == $wp_query->current_post % 2 ) ? 'odd' : 'even';
?>

<article id="post-<?php the_ID(); ?>" class="post <?php echo $class; ?> double-border">
	<header class="post-header <?php apoc_home_header_class(); ?>">
		<h2 class="post-title">
			<a href="<?php the_permalink(); ?>" title="<?php the_title(); ?>"><?php the_title(); ?></a>
		</h2>
		<p class="post-byline"><?php apoc_byline(); ?></p>
	</header>
	
	<section class="post-content">
		<?php apoc_thumbnail(); ?>
		<div class="post-excerpt">

			<?php apoc_custom_excerpt(); ?>
		</div>
	</section>	
	
	<footer class="post-footer">
		<?php if ( 'post' == get_post_type() ) : ?>
			<span class="post-categories">
				<i class="fa fa-tags"></i>
				<?php echo get_the_term_list( get_the_ID() , 'category', 'Posted In: ', ', ', '' ); ?> 
			</span>
			
			<?php apoc_comments_link(); ?>
		<?php endif; ?>
	</footer>
</article>