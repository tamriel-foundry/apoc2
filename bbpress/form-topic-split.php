<?php 
/**
 * Apocrypha Theme Topic Split Form
 * Andrew Clayton
 * Version 2.0
 * 9-20-2014
 */
?>

<?php // Make sure the user has permission to split the topic
if ( is_user_logged_in() && current_user_can( 'edit_topic', bbp_get_topic_id() ) ) : ?>
<form id="split_topic" name="split_topic" method="post" action="<?php the_permalink(); ?>">
	<div class="instructions">
		<h3 class="double-border">Split Topic Instructions</h3>
		<ul>
			<li>When you split a topic, you are slicing it in half starting with the reply you just selected. Choose to use that reply as a new topic with a new title, or merge those replies into an existing topic.</li>
			<li>If you use the existing topic option, replies within both topics will be merged chronologically. The order of the merged replies is based on the time and date they were posted.</li>
			<li>If you need to split a thread to merge part of it into a thread that resides in a different forum, follow these steps. First, split off the desired replies and create a new (temporary) topic for them. Next move that temporary topic into the forum that contains the target topic with which you want to merge. Lastly, use the merge topic form on the temporary topic to combine it with your target.</li>
		</ul>
	</div>

	<fieldset class="split-form">

		<?php // Choose split method ?>
		<div id="split-method" class="form-left">	
			<h3 class="double-border">Choose Split Method</h3>
			<ul class="checkbox-list">
			
				<?php // Split to new topic ?>
				<li>
					<input type="radio" name="bbp_topic_split_option" id="bbp_topic_split_option_reply" checked="checked" value="reply" tabindex="<?php bbp_tab_index(); ?>" />
					<label for="bbp_topic_split_option_reply">Create a new topic with the title:</label><br>
					<input type="text" id="bbp_topic_split_destination_title" value="<?php printf( __( 'Split: %s', 'bbpress' ), bbp_get_topic_title() ); ?>" tabindex="<?php bbp_tab_index(); ?>" size="80" name="bbp_topic_split_destination_title" />
				</li>
			
				<?php // Split to existing topic 			
				if ( bbp_has_topics( array( 'show_stickies' => false, 'post_parent' => bbp_get_topic_forum_id( bbp_get_topic_id() ), 'post__not_in' => array( bbp_get_topic_id() ) ) ) ) : ?>
				<li>
					<input type="radio" name="bbp_topic_split_option" id="bbp_topic_split_option_existing" value="existing" tabindex="<?php bbp_tab_index(); ?>" />
					<label for="bbp_topic_split_option_existing"><?php _e( 'Use an existing topic in this forum:', 'bbpress' ); ?></label>
					<?php bbp_dropdown( array(
						'post_type'   => bbp_get_topic_post_type(),
						'post_parent' => bbp_get_topic_forum_id( bbp_get_topic_id() ),
						'selected'    => -1,
						'exclude'     => bbp_get_topic_id(),
						'select_id'   => 'bbp_destination_topic'
					) ); ?>
				</li>
				<?php endif;?>
			</ul>
		</div>

		<?php // Specify subscriber, favorite, and tag options ?>			
		<div id="split-options" class="form-right">	
			<h3 class="double-border">Split Options</h3>
			<ul class="checkbox-list">
				<li>
					<input type="checkbox" name="bbp_topic_subscribers" id="bbp_topic_subscribers" value="1" tabindex="<?php bbp_tab_index(); ?>" />
					<label for="bbp_topic_subscribers">Transfer Topic Subscribers?</label>
				</li>
				<li>
					<input type="checkbox" name="bbp_topic_favoriters" id="bbp_topic_favoriters" value="1" tabindex="<?php bbp_tab_index(); ?>" />
					<label for="bbp_topic_favoriters">Transfer Topic Favorites?</label>
				</li>
				<li>
					<input type="checkbox" name="bbp_topic_tags" id="bbp_topic_tags" value="1" tabindex="<?php bbp_tab_index(); ?>" />
					<label for="bbp_topic_tags">Transfer Topic Tags?</label>
				</li>
			</ul>
		</div>

		<?php // Show a warning ?>				
		<div class="form-left">	
			<p class="warning">This process cannot be undone.</p>
		</div>
		
		<?php // Submit the split ?>			
		<div class="form-right">
			<button type="submit" tabindex="<?php bbp_tab_index(); ?>" id="bbp_merge_topic_submit" name="bbp_merge_topic_submit"><i class="fa fa-code-fork"></i>Split Topic</button>
		</div>

		<?php // Hidden fields required by split handler ?>
		<div class="hidden">
			<?php bbp_split_topic_form_fields(); ?>
		</div>
	</fieldset>
</form>

<?php // Warn off anyone who got here by mistake
else : ?>
	<p class="warning"><?php is_user_logged_in() ? _e('You do not have permissions to split this topic!') : _e('You cannot merge this topic.') ?></p>
<?php endif; ?>