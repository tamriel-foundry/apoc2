<?php 
/**
 * Apocrypha Theme Reply Move Form
 * Andrew Clayton
 * Version 2.0
 * 9-20-2014
 */
?>

<?php // Make sure the user has permission to move the topic
if ( is_user_logged_in() && current_user_can( 'edit_topic', bbp_get_topic_id() ) ) : ?>
<form id="move_reply" name="move_reply" method="post" action="<?php the_permalink(); ?>">
	<div class="instructions">
		<h3 class="double-border">Move Reply Instructions</h3>
		<ul>
			<li>You can either make this reply a new topic with a new title, or merge it into an existing topic.</li>
			<li>If you choose an existing topic, replies will be ordered by the time and date they were created.</li>
		</ul>
	</div>

	<fieldset class="move-form">

		<div id="split-method" class="form-left">	
			<h3 class="double-border">Choose Split Method</h3>
			<ul class="checkbox-list">
			
				<?php // Split to new topic ?>
				<li>
					<input type="radio" name="bbp_reply_move_option" id="bbp_reply_move_option_reply" checked="checked" value="topic" tabindex="<?php bbp_tab_index(); ?>" />
					<label for="bbp_reply_move_option_reply">Create a new topic with the title:</label><br>
					<input type="text" id="bbp_reply_move_destination_title" value="<?php echo bbp_get_reply_title(); ?>" tabindex="<?php bbp_tab_index(); ?>" size="80" name="bbp_reply_move_destination_title" />
				</li>
			
				<?php // Split to existing topic 
				if ( bbp_has_topics( array( 'show_stickies' => false, 'post_parent' => bbp_get_reply_forum_id( bbp_get_reply_id() ), 'post__not_in' => array( bbp_get_reply_topic_id( bbp_get_reply_id() ) ) ) ) ) : ?>
				<li>
					<input type="radio" name="bbp_reply_move_option" id="bbp_reply_move_option_existing" value="existing" tabindex="<?php bbp_tab_index(); ?>" />
					<label for="bbp_reply_move_option_existing"><?php _e( 'Use an existing topic in this forum:', 'bbpress' ); ?></label>
					<?php bbp_dropdown( array(
						'post_type'   => bbp_get_topic_post_type(),
						'post_parent' => bbp_get_reply_forum_id( bbp_get_reply_id() ),
						'selected'    => -1,
						'exclude'     => bbp_get_reply_topic_id( bbp_get_reply_id() ),
						'select_id'   => 'bbp_destination_topic'
					) ); ?>
				</li>
				<?php endif; ?>
			</ul>
		</div>

		<?php // Show a warning ?>				
		<div class="form-left">	
			<p class="warning">This process cannot be undone.</div>
		</div>
		
		<?php // Submit the split ?>			
		<div class="form-right">
			<button type="submit" tabindex="<?php bbp_tab_index(); ?>" id="bbp_move_reply_submit" name="bbp_move_reply_submit"><i class="fa fa-arrows"></i>Move Reply</button>
		</div>

		<div class="hidden">
			<?php bbp_move_reply_form_fields(); ?>
		</div>
	</fieldset>
</form>

<?php // Warn off anyone who got here by mistake
else : ?>
	<p class="warning"><?php is_user_logged_in() ? _e('You do not have permissions to move this reply!') : _e('You cannot move this reply.') ?></p>
<?php endif; ?>