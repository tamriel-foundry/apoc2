<?php
/**
 * Apocrypha Slideshow Template
 * Andrew Clayton
 * Version 2.0
 * 9-19-2014
 */
?>

<?php if ( $slide_loop->have_posts() ) : ?>
<div id="showcase" class="flexslider">

	<ol class="slideshow-tabs">
	<?php while ( $slide_loop->have_posts() ) : $slide_loop->the_post(); ?>
		<li class="slideshow-tab">
			<a class="slideshow-tab-title" href="#slide-<?php the_ID(); ?>"><?php echo get_post_meta( get_the_ID() , 'TabTitle' , true ); ?></a>
		</li>
	<?php endwhile; ?>
	</ol>

	<?php $slide_loop->rewind_posts(); ?>
	<ol class="slides">
	<?php while ( $slide_loop->have_posts() ) : $slide_loop->the_post(); ?>
		<li id="slide-<?php the_ID(); ?>" class="slideshow-slide">
			<a href="<?php echo get_post_meta( get_the_ID() , 'Permalink' , true ); ?>" title="<?php the_title(); ?>" target="_blank" >
				<?php echo get_the_post_thumbnail( get_the_ID(), 'featured-slide' ); ?>
			</a>

			<header class="slide-caption">
				<h2 class="slide-title"><?php the_title(); ?></h2>
				<section class="slide-content">
					<?php the_content(); ?>
				</section>
				<a href="<?php echo get_post_meta( get_the_ID() , 'Permalink' , true ); ?>" title="<?php the_title(); ?>" target="_blank" >[...]</a>
			</header>
		</li>
	<?php endwhile; ?>
	</ol>

</div><!-- #showcase -->
<?php endif; ?>