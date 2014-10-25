<?php 
/**
 * Apocrypha Theme Group Forum Topic
 * Andrew Clayton
 * Version 2.0
 * 10-25-2014
 */
?>

<header class="post-header <?php apoc_topic_header_class(); ?>">
	<h1 class="post-title"><?php bbp_topic_title(); ?></h1>
	<?php apoc_topic_byline(); ?>
	<div id="subscription-controls" class="header-actions">
		<?php bbp_user_subscribe_link(); ?>
		<?php bbp_user_favorites_link(); ?>
	</div>
</header>

<?php bbp_get_template_part( 'content', 'single-topic' ); ?>