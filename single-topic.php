<?php 
/**
 * Apocrypha Theme Single Topic Template
 * Andrew Clayton
 * Version 2.0
 * 7-22-2014
 */
?>

<?php get_header(); ?>
<div id="content" role="main">
	<?php apoc_breadcrumbs(); ?>
	
	<div id="forums">	
	<?php if ( bbp_user_can_view_forum( array( 'forum_id' => bbp_get_topic_forum_id() ) ) ) : ?>
		<header class="post-header">
			<h1 class="post-title"><?php bbp_topic_title(); ?></h1>
			<?php apoc_topic_byline(); ?>
		</header>
	
		<?php while ( have_posts() ) : the_post(); ?>
			<?php bbp_get_template_part( 'content', 'single-topic' ); ?>
		<?php endwhile; ?>	
		
	<?php elseif ( bbp_is_forum_private( bbp_get_topic_forum_id(), false ) ) : ?>
		<?php bbp_get_template_part( 'feedback', 'no-access' ); ?>
	<?php endif; ?>
	</div><!-- #forums -->	
	
</div><!-- #content -->
<?php get_footer(); ?>