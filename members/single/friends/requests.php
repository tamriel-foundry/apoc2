<?php 
/**
 * Apocrypha Theme Profile Friend Requests
 * Andrew Clayton
 * Version 2.0
 * 9-30-2014
 */
?>

<?php if ( bp_has_members( 'type=alphabetical&include=' . bp_get_friendship_requests() ) ) : ?>
<ul id="friend-request-list" class="directory-list" role="main">

	<?php // Loop through all members
	while ( bp_members() ) : bp_the_member(); 	
	$user = new Apoc_User( bp_get_member_user_id() , 'directory' , 60 ); ?>
		<li class="member directory-entry">
			<div class="directory-member reply-author">
				<?php echo $user->block; ?>
			</div>

			<div class="directory-content">

				<header class="activity-header">	
					<p class="activity"><?php bp_member_last_active(); ?></p>
					<div class="actions">
						<?php do_action( 'bp_directory_members_actions' ); ?>
						<a class="button accept" href="<?php bp_friend_accept_request_link(); ?>"><i class="fa fa-check"></i>Accept Friendship</a>
						<a class="button reject" href="<?php bp_friend_reject_request_link(); ?>"><i class="fa fa-remove"></i>Decline Friendship</a>
					</div>
				</header>
				
				<?php if ( $user->status['content'] ) : ?>
				<blockquote class="activity-content">
					<?php echo $user->status['content']; ?>
				</blockquote>
				<?php endif; ?>

			</div>
		</li>
	<?php endwhile; ?>
</ul>


<?php else: ?>
	<p class="warning"><?php _e( 'You have no pending friendship requests.', 'buddypress' ); ?></p>
<?php endif; ?>