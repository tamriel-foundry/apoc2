<?php 
/**
 * Apocrypha Theme Message Loop Template
 * Andrew Clayton
 * Version 2.0
 * 11-15-2014
 */
?>

<?php if ( bp_has_message_threads( bp_ajax_querystring( 'messages' ) ) ) : ?>
<ol id="message-threads" class="directory-list" role="main">
	
	<?php while ( bp_message_threads() ) : bp_message_thread();

		// Get some data
		global $messages_template;
		$sender_id 	= $messages_template->thread->last_sender_id; 
		$avatar		= new Apoc_Avatar( array( 'user_id' => $sender_id , 'size' => 50 , 'link' => true ) ); ?>

		<li id="m-<?php bp_message_thread_id(); ?>" class="message topic <?php bp_message_css_class(); ?><?php if ( bp_message_thread_has_unread() ) echo 'unread';  else echo 'read'; ?>">
			
			<div class="forum-content">
				<h3 class="forum-title">
					<a href="<?php bp_message_thread_view_link(); ?>" title="Read <?php bp_message_thread_subject(); ?>"><?php bp_message_thread_subject(); ?></a>
					<span class="unread-count">
					<?php if ( bp_get_message_thread_unread_count() > 0 ) : ?>
						&rarr; <?php bp_message_thread_unread_count(); ?> Unread
					<?php endif; ?>
					</span>
				</h3>

				<p class="forum-description">
					<?php bp_message_thread_excerpt(); ?>
				</p>			
			</div>

			<div class="message-actions checkbox">
				<input type="checkbox" name="message_ids[]" value="<?php bp_message_thread_id(); ?>" /><label></label>
				<a class="button delete-single-message" href="<?php bp_message_thread_delete_link(); ?>" title="<?php _e( "Delete Message", "buddypress" ); ?>"><i class="fa fa-remove"></i>Delete</a></label>
			</div>

			<div class="forum-freshness">
				<?php echo $avatar->avatar; ?>
				<div class="freshest-meta">
					<span class="freshest-author">By <?php bp_message_thread_from(); ?></span><br/>
					<span class="freshest-time"><?php echo bp_core_time_since( strtotime( $messages_template->thread->last_message_date ) ); ?></span>
				</div>
			</div>
		</li>

	<?php endwhile; ?>
</ol>

<nav id="messages-options-nav">
	<label for="message-type-select">Select</label>
	<select name="message-type-select" id="message-type-select">
		<option value=""></option>
		<option value="read"><?php _e('Read', 'buddypress') ?></option>
		<option value="unread"><?php _e('Unread', 'buddypress') ?></option>
		<option value="all"><?php _e('All', 'buddypress') ?></option>
	</select>

	<?php if ( !bp_is_current_action( 'sentbox' ) ) : ?>
		<a class="button" href="#" id="mark_as_read"><i class="fa fa-eye"></i>Mark as Read</a>
		<a class="button" href="#" id="mark_as_unread"><i class="fa fa-eye-slash"></i>Mark as Unread</a>
	<?php endif; ?>
	<a class="button bulk-delete-messages" href="#" id="delete_<?php echo bp_current_action(); ?>_messages"><i class="fa fa-trash"></i><?php _e( 'Delete Selected', 'buddypress' ); ?></a>
</nav><!-- #messages-options-nav -->

<nav class="pagination no-ajax" id="messages-pagination">
	<div class="pagination-count">
		<?php bp_messages_pagination_count(); ?>
	</div>
	<div class="pagination-links">
		<?php bp_messages_pagination(); ?>
	</div>
</nav><!-- .pagination -->

<?php elseif ( 'sentbox' == bp_current_action() ) : ?>	
	<p class="warning"><i class="fa fa-inbox"></i>Your outbox is empty!</p>

<?php else : ?>
	<p class="warning"><i class="fa fa-inbox"></i>Your inbox is empty!</p>
<?php endif; ?>