<?php 
/**
 * Apocrypha Theme Group Members Component
 * Andrew Clayton
 * Version 2.0
 * 10-18-2014
 */
?>

<nav class="reply-header" id="subnav">
	<ul id="profile-tabs" class="tabs" role="navigation">
		<li class="current"><a href="<?php bp_group_permalink(); ?>members/" title="Group Members">Guild Members</a></li>
		
		<?php if ( bp_group_is_admin() || bp_group_is_mod() ) : ?>
		<li><a href="<?php bp_group_permalink(); ?>admin/manage-members/" title="Manage group members">Roster Management</a></li>
		<?php endif; ?>
	</ul>
</nav><!-- #subnav -->

<div id="members-dir-list" class="members dir-list">
<?php if ( bp_is_current_action( 'requests' ) ) : ?>
	<?php locate_template( array( 'members/single/friends/requests.php' ), true ); ?>
<?php else : ?>
	<?php locate_template( array( 'members/members-loop.php' ), true ); ?>
<?php endif; ?>
</div><!-- #members-dir-list -->