<?php 
/**
 * Apocrypha Theme Profile Forum Subscriptions
 * Andrew Clayton
 * Version 2.0
 * 10-11-2014
 */
?>

<?php // Forum subscriptions
if ( bbp_get_user_forum_subscriptions() ) : ?>
<div id="user-forum-subscriptions" class="forum-archive">
	<?php while ( bbp_forums() ) : bbp_the_forum(); ?>
		<?php bbp_get_template_part( 'loop', 'single-forum' ); ?>
	<?php endwhile;	?>
</div>
<?php else : ?>
	<p class="instructions"><?php bbp_is_user_home() ? _e( 'You are not currently subscribed to any forums.', 'bbpress' ) : _e( 'This user is not currently subscribed to any forums.', 'bbpress' ); ?></p>
<?php endif; ?>
</div>


<?php // Topic subscriptions
if ( bbp_get_user_topic_subscriptions() ) : ?>
<div id="user-topic-subscriptions" class="single-forum">
	<?php bbp_get_template_part( 'loop',       'topics' ); ?>
	<?php bbp_get_template_part( 'pagination', 'topics' ); ?>
</div>
<?php else : ?>
	<p class="instructions"><?php bbp_is_user_home() ? _e( 'You are not currently subscribed to any topics.', 'bbpress' ) : _e( 'This user is not currently subscribed to any topics.', 'bbpress' ); ?></p>
<?php endif; ?>