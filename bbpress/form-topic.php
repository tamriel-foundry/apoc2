<?php 
/**
 * Apocrypha Theme New Topic Form
 * Andrew Clayton
 * Version 2.0
 * 7-22-2014
 */
?>

<div id="respond">

	<?php // Display the header for topic replies
	if ( bbp_is_single_forum() ) : ?>
		<header class="forum-header">
			<div class="forum-content"><h2>Create New Topic in "<?php bbp_forum_title(); ?>"</h2></div>
		</header>
	<?php endif; ?>

	<?php if ( bbp_current_user_can_access_create_topic_form() ) : ?>
		<form id="new-post" name="new-post" method="post" action="<?php the_permalink(); ?>">
			
			<?php // Notices and warnings
			// apoc_forum_rules(); ?>
		
			<?php // Admin can post in a closed forum
			if ( bbp_is_forum_closed() ) : ?>
				<p class="warning"><?php _e( 'This forum is marked as closed to new topics, however your posting capabilities still allow you to do so.', 'bbpress' ); ?></p>
			<?php endif; ?>
			
			<?php do_action( 'bbp_template_notices' ); ?>	
			
			<fieldset class="reply-form">
			
				<?php // Topic title ?>
				<div id="new-topic-title">
					<label for="bbp_topic_title"><i class="fa fa-bookmark"></i>Topic Title:</label>
					<input type="text" id="bbp_topic_title" value="<?php bbp_form_topic_title(); ?>" tabindex="<?php bbp_tab_index(); ?>" size="100" name="bbp_topic_title" maxlength="<?php bbp_title_max_length(); ?>" />
				</div>

				<?php // The TinyMCE editor
				bbp_the_content( array(
					'context' 		=> 'topic',
					'media_buttons' => false,
					'wpautop'		=> true,
					'tinymce'		=> true,
					'quicktags'		=> true,
					'teeny'			=> false,
				) ); ?>
				
				<?php // Moderators can set topic type
				if ( current_user_can( 'moderate' ) ) : ?>
				<div class="form-left">
					<label for="bbp_stick_topic_select"><i class="fa fa-thumb-tack"></i><?php _e( 'Topic Type: ' , 'bbpress' ); ?></label>
					<?php bbp_topic_type_select(); ?>
				</div>
				<?php endif; ?>
				
				<?php // Move topic to a different forum ?>
				<?php if ( !bbp_is_single_forum() ) : ?>
				<div class="form-right">
					<label for="bbp_forum_id"><i class="fa fa-folder-open"></i>In Forum:</label>
					<?php bbp_dropdown( array( 'selected' => bbp_get_form_topic_forum() ) ); ?>
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
				
				<?php // Moderators can edit topic tags
				if ( current_user_can( 'moderate' ) || ( bbp_allow_topic_tags() && current_user_can( 'assign_topic_tags' ) ) ) : ?>
				<div class="form-left">
					<label for="bbp_topic_tags"><i class="fa fa-tags"></i>Topic Tags:</label>
					<input type="text" value="<?php bbp_form_topic_tags(); ?>" tabindex="<?php bbp_tab_index(); ?>" size="40" name="bbp_topic_tags" id="bbp_topic_tags" <?php disabled( bbp_is_topic_spam() ); ?> />
				</div>
				<?php endif; ?>
				
				<?php // Save revision history on edits
				if ( bbp_allow_revisions() && bbp_is_topic_edit() ) : ?>
				<div class="form-left">
					<label for="bbp_topic_edit_reason"><i class="fa fa-eraser"></i>Edit Reason?</label>
					<input type="text" value="<?php bbp_form_topic_edit_reason(); ?>" tabindex="<?php bbp_tab_index(); ?>" size="40" name="bbp_topic_edit_reason" id="bbp_topic_edit_reason" />
				</div>
				
				<div class="form-left">				
					<input name="bbp_log_topic_edit" id="bbp_log_topic_edit" type="checkbox" value="1" <?php bbp_form_topic_log_edit(); ?> tabindex="<?php bbp_tab_index(); ?>" />
					<label for="bbp_log_topic_edit">Track Revision?</label>
				</li>
				<?php endif; ?>
				
				
				<?php // Submit button ?>
				<div class="form-right">
					<button type="submit" class="submit" id="bbp_topic_submit" name="bbp_topic_submit" tabindex="<?php bbp_tab_index(); ?>"><i class="fa fa-pencil"></i>Post Topic</button>
				</div>

				<?php // Hidden fields required by reply handler ?>				
				<div class="hidden">
					<input type="hidden" name="apoc_ajax" id="apoc_ajax_action" value="apoc_post_topic">
					<?php bbp_topic_form_fields(); ?>
				</div>	
			
			</fieldset>
		</form>
	
	<?php // User cannot reply , forum is closed
	elseif ( bbp_is_topic_closed() ) : ?>
		<p class="warning"><?php printf( __( 'The forum &#8216;%s&#8217; is closed to new topics and replies.', 'bbpress' ), bbp_get_forum_title() ); ?></p>

	<?php // User cannot reply, not logged in
	else : ?>
		<p class="warning">You are not currently logged in. You must <a class="scroll-top" href="<?php echo SITEURL . '/wp-login.php'; ?>" title="Please Log In">log in</a> before creating a new topic.</p>
	<?php endif; ?>	
	
</div><!-- #respond -->		