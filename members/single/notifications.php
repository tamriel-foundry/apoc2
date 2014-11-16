<?php 
/**
 * Apocrypha Theme Profile Notifications Template
 * Andrew Clayton
 * Version 2.0
 * 11-15-2014
 */

// Get the current profile user
global $user;
?>

<nav class="reply-header no-ajax" id="subnav">
	<ul id="profile-tabs" class="tabs" role="navigation">
		<?php bp_get_options_nav(); ?>
	</ul>
	<div id="notifications-actions" class="filter">
		<?php if ( bp_is_current_action( 'unread' ) ) : ?>
		<a class="button" href="#" id="mark_as_read" data-id="<?php echo $user->id; ?>"><i class="fa fa-eye"></i>Mark All Read</a>
		<?php endif; ?>
		<a class="button" href="#" id="delete_all_notifications" data-id="<?php echo $user->id; ?>"><i class="fa fa-trash"></i>Delete All</a>
	</div>
</nav><!-- #subnav -->

<div id="notifications-dir-list" class="notifications directory-list">
<?php if ( bp_is_current_action( 'unread' ) ) : ?>
	<?php bp_get_template_part( 'members/single/notifications/unread' ); ?>
<?php else : ?>
	<?php bp_get_template_part( 'members/single/notifications/read' ); ?>
<?php endif; ?>
</div><!-- #notifications-dir-list -->