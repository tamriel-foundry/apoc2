<?php 
/**
 * Apocrypha Theme Topic Reply Form
 * Andrew Clayton
 * Version 2.0
 * 7-28-2014
 */
?>

<div id="respond">

	<?php // Display the header for topic replies
	if ( bbp_is_single_topic() ) : ?>
		<header class="forum-header">
			<div class="forum-content"><h2>Reply To "<?php bbp_topic_title(); ?>"</h2></div>
		</header>
	<?php endif; ?>

	<?php // User is allowed to reply
	if ( bbp_current_user_can_access_create_reply_form() ) : ?>
		<form id="new-post" name="new-post" method="post" action="<?php the_permalink(); ?>">
			
			<?php // Notices and warnings
			// apoc_forum_rules(); ?>
		
			<?php // Admin can post in a closed topic
			if ( !bbp_is_topic_open() ) : ?>
				<p class="warning"><?php _e( 'This topic is marked as closed to new replies, however your posting capabilities still allow you to do so.', 'bbpress' ); ?></p>
			<?php endif; ?>
			
			<?php do_action( 'bbp_template_notices' ); ?>	
			
			<fieldset class="reply-form">

				<?php // The TinyMCE editor
				bbp_the_content( array(
					'context' 		=> 'reply',
					'media_buttons' => false,
					'wpautop'		=> true,
					'tinymce'		=> true,
					'quicktags'		=> true,
					'teeny'			=> false,
				) ); ?>
				
				<?php // Moderators can edit topic tags
				if ( current_user_can( 'moderate' ) || ( bbp_allow_topic_tags() && current_user_can( 'assign_topic_tags' ) ) ) : ?>
				<div class="form-left">
					<label for="bbp_topic_tags"><i class="fa fa-tags"></i>Topic Tags:</label>
					<input type="text" value="<?php bbp_form_topic_tags(); ?>" tabindex="<?php bbp_tab_index(); ?>" size="40" name="bbp_topic_tags" id="bbp_topic_tags" />
				</div>
				<?php endif; ?>	
				
				<?php // Alter subscription preferences
				if ( bbp_is_subscriptions_active() ) : ?>
				<div class="form-right">
					<input name="bbp_topic_subscription" id="bbp_topic_subscription" type="checkbox" value="bbp_subscribe"<?php bbp_form_topic_subscribed(); ?> tabindex="<?php bbp_tab_index(); ?>" />
					<label for="bbp_topic_subscription">
						<?php if ( bbp_is_reply_edit() && ( bbp_get_reply_author_id() !== bbp_get_current_user_id() ) ) :
							_e( 'Notify the author of follow-up replies via email', 'bbpress' );
						else :
							_e( 'Notify me of follow-up replies via email', 'bbpress' );
						endif; ?>
					</label>
				</div>
				<?php endif; ?>
				
				<?php // Save revision history on edits
				if ( bbp_allow_revisions() && bbp_is_reply_edit() ) : ?>
				<div class="form-left">
					<label for="bbp_reply_edit_reason"><i class="fa fa-eraser"></i>Edit Reason?</label>
					<input type="text" value="<?php bbp_form_reply_edit_reason(); ?>" tabindex="<?php bbp_tab_index(); ?>" size="40" name="bbp_reply_edit_reason" id="bbp_reply_edit_reason" />
				</div>
				
				<div class="form-left">				
					<input name="bbp_log_reply_edit" id="bbp_log_reply_edit" type="checkbox" value="1" <?php bbp_form_reply_log_edit(); ?> tabindex="<?php bbp_tab_index(); ?>" />
					<label for="bbp_log_reply_edit">Track Revision?</label>
				</li>
				<?php endif; ?>
				
				
				<?php // Submit button ?>
				<div class="form-right">
					<button type="submit" class="submit" id="bbp_reply_submit" name="bbp_reply_submit" tabindex="<?php bbp_tab_index(); ?>"><i class="fa fa-pencil"></i>Post Reply</button>
				</div>

				<?php // Hidden fields required by reply handler ?>				
				<div class="hidden">
					<input type="hidden" name="apoc_ajax" id="apoc_ajax_action" value="apoc_post_reply">
					<?php bbp_reply_form_fields(); ?>
				</div>	
			
			</fieldset>
		</form>
	
	<?php // User cannot reply , topic is closed
	elseif ( bbp_is_topic_closed() ) : ?>
		<p class="warning"><?php printf( 'The topic &ldquo;%s&rdquo; is closed to new replies.' , bbp_get_topic_title() ); ?></p>
	
	<?php // User cannot reply, forum is closed
	elseif ( bbp_is_forum_closed( bbp_get_topic_forum_id() ) ) : ?>
		<p class="warning"><?php printf( 'The forum &ldquo;%s&rdquo; is closed to new posts.' , bbp_get_forum_title( bbp_get_topic_forum_id() ) ); ?></p>

	<?php // User cannot reply, not logged in
	else : ?>
		<p class="warning">You are not currently logged in. You must <a class="scroll-top" href="<?php echo SITEURL . '/wp-login.php'; ?>" title="Please Log In">log in</a> before replying to this topic.</p>
	<?php endif; ?>	
	
</div><!-- #respond -->		