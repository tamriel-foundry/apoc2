<?php 
/**
 * Apocrypha Theme Group Activity Component
 * Andrew Clayton
 * Version 2.0
 * 10-18-2014
 */
?>

<nav class="reply-header" id="subnav">
	<ul id="profile-tabs" class="tabs" role="navigation">
		<li class="current"><a href="<?php bp_group_permalink(); ?>activity/" title="Group Activity">Guild Activity</a></li>
		
		<?php if ( is_user_logged_in() && bp_group_is_member() ) : ?>
		<a id="group-status-button" class="update-status-button button"><i class="fa fa-pencil"></i>What's New?</a>
		<?php endif; ?>

		<div id="activity-filter-select" class="filter">
			<select id="activity-filter-by">
				<option value="-1">All Activity</option>
				<option value="activity_update">Status Updates</option>
				<?php if ( bp_group_is_forum_enabled() ) do_action( 'bp_activity_filter_options' ); // Topics & Replies ?>
				<option value="joined_group">Guild Memberships</option>
			</select>
		</div>
	</ul>
</nav><!-- #subnav -->

<?php if ( is_user_logged_in() && bp_group_is_member() ) : ?>		
	<?php locate_template( array( 'activity/post-form.php'), true ); ?>
<?php endif; ?>

<div id="activity-directory" class="activity" role="main">
	<?php locate_template( array( 'activity/activity-loop.php' ), true ); ?>
</div><!-- #activity-directory -->

