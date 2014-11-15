<?php 
/**
 * Apocrypha Theme Single Message Template
 * Andrew Clayton
 * Version 2.0
 * 11-15-2014
 */
?>

<?php if ( bp_thread_has_messages() ) : ?>
<div id="private-message">

		
	<header class="post-header <?php apoc_post_header_class('post'); ?>">
		<h1 class="post-title">Private Message - <?php bp_the_thread_subject(); ?></h1>
		<p class="post-byline"><?php printf('Conversation between you and %s.' , bp_get_the_thread_recipients() ); ?></p>
		<div class="header-actions">
			<a class="button confirm" href="<?php bp_the_thread_delete_link(); ?>" title="<?php _e( "Delete Message", "buddypress" ); ?>"><i class="fa fa-remove"></i>Delete Message</a>
		</div>
	</header>

	<ol id="message-thread" class="topic single-topic" role="main">	
	<?php while ( bp_thread_messages() ) : bp_thread_the_message();

		// Get some data
		global $thread_template;
		$user = new Apoc_User( $thread_template->message->sender_id , 'reply' ); ?>

		<li class="reply <?php bp_the_thread_message_alt_class(); ?>">


			<header class="reply-header">
				<time class="reply-time"><?php bp_the_thread_message_time_since(); ?></time>
				<?php apoc_report_post_button( 'message' ); ?>
				<div class="reply-admin-links">
					<span><a class="reply-permalink" href="<?php apoc()->url; ?>">#<?php echo $thread_template->current_message + 1; ?></a></span>
				</div>
			</header>

			<section class="reply-body">	
				<div class="reply-author">
					<?php echo $user->block; ?>
				</div>
				<div class="reply-content">
					<?php bp_the_thread_message_content(); ?>
				</div>
				<?php $user->signature(); ?>
			</section>	

		</li>
	<?php endwhile; ?>	
	</ol>

	<header class="forum-header">
		<div class="forum-content">
			<h2>Reply To - "<?php bp_the_thread_subject(); ?>"</h2>
		</div>
	</header>

	<form id="send-reply" action="<?php bp_messages_form_action(); ?>" method="post" class="standard-form">
		
		<fieldset>

			<?php // Load the TinyMCE Editor
			wp_editor( '', 'message_content', array(
				'media_buttons' => false,
				'wpautop'		=> true,
				'editor_class'  => 'private_message',
				'quicktags'		=> false,
				'teeny'			=> true,
			) ); ?>
			
			<div class="form-right">
				<button type="submit" name="send" id="send_reply_button"><i class="fa fa-envelope"></i>Send Reply</button>
			</div>		
			
			<div class="hidden">
				<input type="hidden" id="thread_id" name="thread_id" value="<?php bp_the_thread_id(); ?>" />
				<input type="hidden" id="messages_order" name="messages_order" value="<?php bp_thread_messages_order(); ?>" />
				<?php wp_nonce_field( 'messages_send_message', 'send_message_nonce' ); ?>
			</div>

		</fieldset>	
	</form>
</div>

<?php endif; ?>