<?php 
/**
 * Apocrypha Theme Homepage Template
 * Andrew Clayton
 * Version 2.0
 * 4-29-2014
 */
?>

<?php get_header(); ?>

	<?php apoc_primary_sidebar(); ?>

	<div id="showcase-container">
		<div id="showcase" class="flexslider">
			Showcase
		</div>
		
		<?php //apoc_recent_discussion(); ?>
	</div>
	
	<div id="content" role="main">
		<div id="posts">

			<div id="post1" class="post odd">Post1</div>
			<div id="post2" class="post even">Post2</div>
			<div id="post3" class="post odd">Post3</div>
			<div id="post4" class="post even">Post4</div>

			<?php if ( have_posts() ) : while ( have_posts() ) : the_post(); ?>
				<?php //apoc_single_post(); ?>
			<?php endwhile; endif; ?>
		</div>
	</div>

<?php //get_footer(); ?>