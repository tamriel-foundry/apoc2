<?php 
/**
 * Apocrypha Theme Profile Groups Invites
 * Andrew Clayton
 * Version 2.0
 * 9-30-2014
 */
?>

<?php if ( bp_has_groups( 'type=invites&user_id=' . bp_loggedin_user_id() ) ) : ?>
<ul id="group-invite-list" class="directory-list" role="main">

	<?php // Loop through all invites
	while ( bp_groups() ) : bp_the_group();
	$group = new Apoc_Group( bp_get_group_id() , 'directory' , 100 );	?>
	
	<li id="group-<?php bp_group_id(); ?>" class="group directory-entry">
		<div class="directory-member reply-author">
			<?php echo $group->block; ?>
		</div>

		<div class="directory-content">
			<header class="activity-header">	
				<p class="activity"><?php bp_group_last_active(); ?></p>
				<div class="actions">
					<?php do_action( 'bp_directory_groups_actions' ); ?>
					<a class="button accept" href="<?php bp_group_accept_invite_link(); ?>"><i class="icon-ok"></i>Join Guild</a>
					<a class="button reject confirm" href="<?php bp_group_reject_invite_link(); ?>"><i class="icon-remove"></i>Decline Invitation</a>
				</div>
			</header>
			<div class="guild-description">
				<?php bp_group_description_excerpt(); ?>
			</div>
		</div>
	</li>
	<?php endwhile; ?>
</ul><!-- #groups-list -->

<?php else: ?>
<p class="warning"><?php _e( 'You have no outstanding group invites.', 'buddypress' ); ?></p>
<?php endif;?>