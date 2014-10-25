<?php 
/**
 * Apocrypha Theme Group Forum Topic Edit
 * Andrew Clayton
 * Version 2.0
 * 10-25-2014
 */
?>

<header class="post-header <?php apoc_topic_header_class(); ?>">
	<h1 class="post-title"><?php bbp_topic_title(); ?></h1>
	<?php apoc_topic_byline(); ?>
</header>

<?php bbp_get_template_part( 'form', 'topic' ); ?>