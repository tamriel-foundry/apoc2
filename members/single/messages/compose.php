<?php 
/**
 * Apocrypha Theme Messages Compose Template
 * Andrew Clayton
 * Version 2.0
 * 11-15-2014
 */
?>

<form action="<?php bp_messages_form_action('compose'); ?>" method="post" id="send_message_form" class="standard-form" role="main" enctype="multipart/form-data">
	<fieldset>

		<div class="form-full">
			<label for="send-to-input"><i class="fa fa-users fa-fw"></i>Send To:</label>
			<input type="text" name="send-to-input" class="send-to-input" id="send-to-input" placeholder="Separate multiple usernames with commas."/>
		</div>

		<div class="form-full">
			<label for="subject"><i class="fa fa-bookmark fa-fw"></i>Subject:</label>
			<input type="text" name="subject" id="subject" value="<?php bp_messages_subject_value(); ?>" />
		</div>
	
		<?php // Load the TinyMCE Editor
		$thecontent = bp_get_messages_content_value();
		wp_editor( htmlspecialchars_decode( $thecontent, ENT_QUOTES ), 'message_content', array(
			'media_buttons' => false,
			'wpautop'		=> true,
			'editor_class'  => 'private_message',
			'quicktags'		=> false,
			'teeny'			=> true,
		) ); ?>

		<?php if ( bp_current_user_can( 'bp_moderate' ) ) : ?>
		<div class="form-left">
			<input type="checkbox" id="send-notice" name="send-notice" value="1" />
			<label for="send-notice"><?php _e( "This is a notice to all users.", "buddypress" ); ?></label>
		</div>
		<?php endif; ?>

		<div class="form-right">
			<button type="submit" name="send" id="send"><i class="fa fa-envelope"></i>Send Message</button>
		</div>

		<div class="hidden">
			<input type="hidden" name="send_to_usernames" id="send-to-usernames" value="<?php bp_message_get_recipient_usernames(); ?>" class="<?php bp_message_get_recipient_usernames(); ?>" />
			<?php wp_nonce_field( 'messages_send_message' ); ?>
		</div>

	</fieldset>
</form>

<script type="text/javascript">document.getElementById("send-to-input").focus();</script>