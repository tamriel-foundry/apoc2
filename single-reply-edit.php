<?php 
/**
 * Apocrypha Theme Single Reply Edit
 * Andrew Clayton
 * Version 2.0
 * 9-19-2014
 */
?>

<?php get_header(); ?>
<div id="content" role="main">
	<?php apoc_breadcrumbs(); ?>
	
	<div id="forums">	
		<header class="post-header <?php apoc_topic_header_class(); ?>">
			<h1 class="post-title">Edit Reply: <?php bbp_topic_title(); ?></h1>
			<?php apoc_topic_byline(); ?>
		</header>
	
		<div id="respond" class="edit-reply">
			<?php bbp_get_template_part( 'form', 'reply' ); ?>
		</div><!-- #respond -->
	</div><!-- #forums -->	
	
</div><!-- #content -->
<?php get_footer(); ?>