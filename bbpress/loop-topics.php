<?php 
/**
 * Apocrypha Theme Forum Topics Loop
 * Andrew Clayton
 * Version 2.0
 * 7-23-2014
 */
?>

<header class="forum-header">
	<div class="forum-content">
	
	<?php // Recent Topics Archive
	if ( bbp_is_topic_archive() ) : ?>
		<h2>Recent Topics</h2>
	
	<?php // Search Results or Profile Topics
	elseif ( is_search() || bp_is_forums_component() ) : ?>
		<h2>Topic Title</h2>
	
	<?php // Single Forum
	elseif ( bbp_is_single_forum() ) : ?>
		<h2><?php bbp_forum_title(); ?></h2>
		<a class="button scroll-respond" href="#respond" title="Create new topic in <?php bbp_forum_title(); ?>"><i class="fa fa-pencil"></i>New Topic</a>
		<?php bbp_forum_subscription_link(); ?>
	<?php endif; ?>
	</div>
	
	<div class="forum-count">Posts</div>
	<div class="forum-freshness">Latest Post</div>
</header>

<ol id="forum-<?php bbp_forum_id(); ?>" class="forum topics">
	<?php while ( bbp_topics() ) : bbp_the_topic(); ?>
		<?php bbp_get_template_part( 'loop', 'single-topic' ); ?>
	<?php endwhile; ?>
</ol>