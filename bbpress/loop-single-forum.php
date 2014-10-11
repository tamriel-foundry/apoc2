<?php 
/**
 * Apocrypha Theme Forums Archive
 * Andrew Clayton
 * Version 2.0
 * 5-10-2014
 */
?>
 
<?php // Top level categories
if ( bbp_get_forum_subforum_count() ) : ?>
	<?php apoc_loop_subforums(); ?>

<?php // Single non-category forums
else : ?>
	<header class="forum-header">
		<div class="forum-content"><h2>Forum</h2></div>
		<div class="forum-count">Topics</div>
		<div class="forum-freshness">Latest Post</div>
	</header>
	<ol class="forums category <?php bbp_forum_status(); ?>">
		<li id="forum-<?php bbp_forum_id() ?>" class="forum">
			
			<div class="forum-content">
				<h3 class="forum-title"><a href="<?php bbp_forum_permalink(); ?>" title="Browse <?php bbp_forum_title(); ?>"><?php bbp_forum_title(); ?></a></h3>
				<p class="forum-description"><?php bbp_forum_content(); ?></p>
			</div>

			<div class="forum-count">
				<?php bbp_forum_topic_count(); ?>
			</div>

			<div class="forum-freshness">
				<?php echo apoc_get_avatar( array( 'user_id' => bbp_get_forum_last_reply_author_id()  , 'link' => true , 'size' => 50 ) ); ?>
				<div class="freshest-meta">
					<a class="freshest-title" href="<?php echo $link; ?>" title=""><?php bbp_forum_last_topic_title(); ?></a>
					<span class="freshest-author">By <?php bbp_author_link( array( 'post_id' => bbp_get_forum_last_topic_id() , 'type' => 'name' ) ); ?></span>
					<span class="freshest-time"><?php bbp_topic_last_active_time( bbp_get_forum_last_topic_id() ); ?></span>
				</div>
			</div>
			
		</li>
	</ol>
<?php endif; ?>