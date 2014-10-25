<?php 
/**
 * Apocrypha Theme Group Members Component
 * Andrew Clayton
 * Version 2.0
 * 10-25-2014
 */
?>

<nav class="reply-header" id="subnav">
	<ul id="profile-tabs" class="tabs" role="navigation">
		<li class="current"><a href="<?php bp_group_permalink(); ?>send-invites/" title="Send Invites">Invite Friends</a></li>
	</ul>
</nav><!-- #subnav -->

<?php // Only show the form to people who have friends
if ( bp_get_total_friend_count( bp_loggedin_user_id() ) ) : ?>	
<form action="<?php bp_group_send_invite_form_action(); ?>" method="post" id="send-invite-form" class="standard-form" role="main">

	<div class="instructions">
		<h3 class="double-border">Invite Friends to Join This Guild</h3>
		<ul>
			<li>You may invite friends to participate in this guild.</li>
			<li>To directly invite new members to join they must first be on your friends list.</li>
		</ul>
	</div>

	<fieldset>
		<div id="invite-list" class="form-left">
			<h3 class="double-border">Your Friends</h3>
			<ul>
				<?php apoc_new_group_invite_friend_list(); ?>
			</ul>
		</div>

		<div class="form-right">
			<h3 class="double-border">Invited Users</h3>
			<?php if ( bp_group_has_invites() ) : ?>

			<ul id="friend-list" class="directory-list">
				<?php while ( bp_group_invites() ) : bp_group_the_invite(); ?>
					<li id="<?php bp_group_invite_item_id(); ?>" class="member directory-entry">

						<?php // Get the invited user
						global $invites_template;
						$user = new Apoc_User( $invites_template->invite->user->id , 'directory' , 60 ); ?>

						<div class="directory-member reply-author">
							<?php echo $user->block; ?>
						</div>

						<div class="directory-content">
							<header class="activity-header">
								<p class="activity"><?php bp_group_invite_user_last_active(); ?></p>
								<div class="actions">
									<a class="button remove" href="<?php bp_group_invite_user_remove_invite_url(); ?>" id="<?php bp_group_invite_item_id(); ?>"><i class="fa fa-remove"></i><?php _e( 'Remove Invite', 'buddypress' ); ?></a>
									<?php do_action( 'bp_group_send_invites_item_action' ); ?>
								</div>
							</header>
						</div>
					</li>
				<?php endwhile; ?>
			</ul>
			<?php endif; ?>
		</div>

		<div class="form-right">
			<button type="submit" name="submit" id="submit"><i class="fa fa-plus"></i><?php esc_attr_e( 'Send Invites', 'buddypress' ); ?></button>
		</div>

		<div class="hidden">
			<input type="hidden" name="group_id" id="group_id" value="<?php bp_group_id(); ?>" />
			<?php wp_nonce_field( 'groups_send_invites', '_wpnonce_send_invites'); ?>
			<?php wp_nonce_field( 'groups_invite_uninvite_user', '_wpnonce_invite_uninvite_user' ); ?>
		</div>
	</fieldset>
</form>


<?php // Otherwise ask people to get friends first
else : ?>
	<p class="warning"><?php _e( 'Once you have built up friend connections you will be able to invite others to your group.', 'buddypress' ); ?></p>
<?php endif; ?>
