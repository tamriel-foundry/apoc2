<?php 
/**
 * Apocrypha Theme Forum Single Topic
 * Andrew Clayton
 * Version 2.0
 * 7-22-2014
 */
?>

<li id="topic-<?php bbp_topic_id(); ?>" <?php bbp_topic_class(); ?>>

	<div class="forum-content">
		<h3 class="forum-title">
			<a href="<?php bbp_topic_permalink(); ?>" title="Read <?php bbp_topic_title(); ?>"><?php bbp_topic_title(); ?></a>
			<?php if ( bbp_get_topic_post_count() > 1 ) : ?>
				<a class="last-reply-link" href="<?php bbp_topic_last_reply_url(); ?>" title="Jump to the last reply">&rarr;</a>
			<?php endif; ?>
		</h3>
		<p class="forum-description">
			Started by <?php bbp_topic_author_link( array( 'type' => 'name' ) ); ?>		
			<?php if ( !bbp_is_single_forum() ) : ?>
				in <a class="topic-location" href="<?php bbp_forum_permalink( bbp_get_topic_forum_id() ); ?>" title="Browse this forum"><?php bbp_forum_title( bbp_get_topic_forum_id() ); ?></a>
			<?php endif; ?>
		</p>
	</div>
	
	<div class="forum-count">
		<?php bbp_topic_post_count(); ?>
	</div>
	
	<div class="forum-freshness">
		<?php echo apoc_get_avatar( array( 'user_id' => bbp_get_reply_author_id(bbp_get_topic_last_active_id()) , 'link' => true , 'size' => 50 )); ?>
		<div class="freshest-meta">
			<span class="freshest-author">By <?php bbp_author_link( array( 'post_id' => bbp_get_topic_last_active_id(), 'type' => 'name' ) ); ?></span>
			<span class="freshest-time"><?php bbp_topic_last_active_time(); ?></span>
		</div>
	</div>	
</li>