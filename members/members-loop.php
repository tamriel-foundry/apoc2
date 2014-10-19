<?php 
/**
 * Apocrypha Theme Members Loop
 * Andrew Clayton
 * Version 2.0
 * 9-22-2014
 */
?>

<?php // Group members
if ( ( bp_is_group() && bp_group_has_members( 'exclude_admins_mods=0' ) ) || bp_has_members( bp_ajax_querystring( 'members' ) ) ) : ?>

<ul id="members-list" class="directory-list" role="main">
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
					<div class="actions"><?php do_action( 'bp_directory_members_actions' ); ?></div>
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

<nav class="pagination">
	<div class="pagination-count" >
		<?php bp_members_pagination_count(); ?>
	</div>
	<div class="pagination-links" >
		<?php bp_members_pagination_links(); ?>
	</div>
</nav>

<?php else : ?>
<p class="warning"><?php _e( "Sorry, no members were found.", 'buddypress' ); ?></p>
<?php endif; ?>