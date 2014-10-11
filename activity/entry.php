<?php 
/**
 * Apocrypha Theme Single Activity Entry
 * Andrew Clayton
 * Version 2.0
 * 9-20-2014
 */
 
// Get the user info
$user = new Apoc_User( bp_get_activity_user_id() , 'directory' , 60 );	?>

<li id="activity-<?php bp_activity_id(); ?>" class="<?php bp_activity_css_class(); ?> directory-entry">

	<div class="directory-member reply-author">
		<?php echo $user->block; ?>	
	</div>

	<div class="directory-content">

		<header class="activity-header">		

			<?php bp_activity_action(); ?>

			<?php if ( is_user_logged_in() ) : ?>
			<div class="actions">
				
				<?php // Favorite Activity
				if ( bp_activity_can_favorite() ) : ?>
					<?php if ( !bp_get_activity_is_favorite() ) : ?>
					<a href="<?php bp_activity_favorite_link(); ?>" class="button-dark fav bp-secondary-action" title="<?php esc_attr_e( 'Mark as Favorite', 'buddypress' ); ?>"><i class="fa fa-star"></i>Favorite</a>
					<?php else : ?>
					<a href="<?php bp_activity_unfavorite_link(); ?>" class="button-dark unfav bp-secondary-action" title="<?php esc_attr_e( 'Remove Favorite', 'buddypress' ); ?>"><i class="fa fa-remove"></i><?php _e( 'Remove Favorite', 'buddypress' ); ?></a>
					<?php endif; ?>
				<?php endif; ?>	

				<?php // Delete Activity
				if ( bp_activity_user_can_delete() ) bp_activity_delete_link(); ?>
			
				<?php // Activity Comment Button
				if ( bp_activity_can_comment() ) : ?>
					<a href="<?php bp_activity_comment_link(); ?>" class="button-dark acomment-reply bp-primary-action" id="acomment-comment-<?php bp_activity_id(); ?>"><i class="fa fa-comments"></i>Comment <span class="comments-link-count activity-count"><?php echo bp_activity_get_comment_count(); ?></span></a>
				<?php endif; ?>
			</div>
			<?php endif; ?>
		</header>

		<?php if ( bp_activity_has_content() ) : ?>
		<blockquote class="activity-content">
			<?php bp_activity_content_body(); ?>
		</blockquote>
		<?php endif; ?>

		<?php // Activity Comments
		if ( ( is_user_logged_in() && bp_activity_can_comment() ) || bp_is_single_activity() ) : ?>
		<div class="activity-comments">

			<?php bp_activity_comments(); ?>

			<?php if ( is_user_logged_in() ) : ?>
				<form action="<?php bp_activity_comment_form_action(); ?>" method="post" id="ac-form-<?php bp_activity_id(); ?>" class="ac-form">
					<fieldset class="ac-reply-content">
						<div class="form-full ac-textarea">
							<textarea id="ac-input-<?php bp_activity_id(); ?>" class="ac-input bp-suggestions" name="ac_input_<?php bp_activity_id(); ?>"></textarea>
						</div>
						
						<div class="form-left">
							<span>Reply to this activity, or press escape to cancel.</span>
						</div>

						<div class="form-right">
							<button type="submit" name="ac_form_submit"><i class="fa fa-pencil"></i>Post Comment</button>
						</div>

						<div class="hidden">
							<input type="hidden" name="comment_form_id" value="<?php bp_activity_id(); ?>" />
							<?php wp_nonce_field( 'new_activity_comment', '_wpnonce_new_activity_comment' ); ?>
						</div>
					</fieldset>
				</form>
			<?php endif; ?>
		
		</div>
		<?php endif; ?>

	</div>

</li>