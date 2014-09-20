<?php 
/**
 * Apocrypha Theme Topic Merge Form
 * Andrew Clayton
 * Version 2.0
 * 9-9-2014
 */
?>


<?php // Make sure the user has permission to merge the topic
if ( is_user_logged_in() && current_user_can( 'edit_topic', bbp_get_topic_id() ) ) : ?>
<form id="merge-topic" name="merge-topic" method="post" action="<?php the_permalink(); ?>">
	<div class="instructions">
		<h3 class="double-border">Topic Merge Instructions</h3>
		<ul>
			<li>Select the topic to merge this one into. The destination topic will remain the lead topic, and this one will change into a reply.</li>
			<li>To keep this topic as the lead, go to the other topic and use the merge tool from there instead.</li>
			<li>All replies within both topics will be merged chronologically. The order of the merged replies is based on the time and date they were posted. If the destination topic was created after this one, it&apos;s post date will be updated to one second earlier than this one.</li>
			<li>Your merger destination must be located within the same forum. If you wish to merge into a topic that resides in a different forum, please use the topic edit function first to move this topic to the destination forum.</li>
		</ul>
	</div>

	<fieldset class="merge-form">

		<?php // Choose the target topic ?>
		<div class="form-left">	
			<h3 class="double-border">Choose Merge Target</h3>
			<?php if ( bbp_has_topics( array( 'show_stickies' => false, 'post_parent' => bbp_get_topic_forum_id( bbp_get_topic_id() ), 'post__not_in' => array( bbp_get_topic_id() ) ) ) ) : ?>
			<label for="bbp_destination_topic"><i class="icon-screenshot"></i>Merge Into:</label>
			<?php bbp_dropdown( array(
					'post_type'   => bbp_get_topic_post_type(),
					'post_parent' => bbp_get_topic_forum_id( bbp_get_topic_id() ),
					'selected'    => -1,
					'exclude'     => bbp_get_topic_id(),
					'select_id'   => 'bbp_destination_topic',
					'none_found'  => __( 'No topics were found to which the topic could be merged to!', 'bbpress' )
				) ); ?>

			<?php else : ?>
			<label>No Topics!</label>
			<?php endif; ?>
		</div>
	
		<?php // Merge options, what settings to keep ?>		
		<div class="form-right">
			<h3 class="double-border">Merge Options</h3>
			<ul class="checkbox-list">
				<li>
					<input name="bbp_topic_subscribers" id="bbp_topic_subscribers" type="checkbox" value="1" checked="checked" tabindex="<?php bbp_tab_index(); ?>" />
					<label for="bbp_topic_subscribers">Merge Topic Subscribers?</label>
				</li>
				<li>
					<input name="bbp_topic_favoriters" id="bbp_topic_favoriters" type="checkbox" value="1" checked="checked" tabindex="<?php bbp_tab_index(); ?>" />
					<label for="bbp_topic_favoriters">Merge Topic Favorites?</label>
				</li>
				<li>
					<input name="bbp_topic_tags" id="bbp_topic_tags" type="checkbox" value="1" checked="checked" tabindex="<?php bbp_tab_index(); ?>" />
					<label for="bbp_topic_tags">Merge Topic Tags?</label>
				</li>
			</ul>
		</div>

		<?php // Warning ?>
		<div class="form-left">	
			<p class="warning">This process cannot be undone.</p>
		</div>
		
		<?php // Submit the merge ?>			
		<div class="form-right">
			<button type="submit" tabindex="<?php bbp_tab_index(); ?>" id="bbp_merge_topic_submit" name="bbp_merge_topic_submit"><i class="fa fa-code-fork"></i>Merge Topics</button>
		</div>

		<?php // Hidden fields required by merge handler ?>
		<div class="hidden">
			<?php bbp_merge_topic_form_fields(); ?>
		</div>
	</fieldset>
</form>

<?php // Warn off anyone who got here by mistake
else : ?>
	<p class="warning"><?php is_user_logged_in() ? _e('You do not have permissions to merge this topic!') : _e('You cannot merge this topic.') ?></p>
<?php endif; ?>

