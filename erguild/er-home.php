<?php 
/** 
 * Entropy Rising Guild Homepage
 * Template Name: Entropy Rising Home
 * Andrew Clayton
 * Version 2.0
 * 11-16-2014
 */

// Load up the ER group
$group = groups_get_group( array( 'group_id' => 1 , 'populate_extras' => true ) ); ?>

<?php get_header('er'); ?>

	<div id="content" role="main">

		<div id="showcase-container">
			<?php get_slideshow(); ?>
			<?php apoc_recent_discussion(); ?>
		</div>

		<div id="posts">
			posts
		</div>

		<nav class="pagination">
			<div class="pagination-links">
				<?php echo paginate_links( array('prev_text' => '&larr;', 'next_text' => '&rarr;') ); ?>
			</div>
		</nav>

	</div><!-- #content -->
<?php get_footer(); ?>