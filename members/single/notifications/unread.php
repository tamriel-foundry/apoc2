<?php 
/**
 * Apocrypha Theme Unread Notifications Component
 * Andrew Clayton
 * Version 2.0
 * 11-15-2014
 */
?>

<?php if ( bp_has_notifications() ) : ?>

	<?php bp_get_template_part( 'members/single/notifications/notifications-loop' ); ?>

	<nav class="pagination no-ajax">
		<div class="pagination-count">
			<?php bp_notifications_pagination_count(); ?>
		</div>
		<div class="pagination-links" >
			<?php bp_notifications_pagination_links(); ?>
		</div>
	</nav>

<?php else : ?>
<p class="warning">
	<?php if ( bp_is_my_profile() ) : ?>
		<?php _e( 'You have no unread notifications.', 'buddypress' ); ?>
	<?php else : ?>
		<?php _e( 'This member has no unread notifications.', 'buddypress' ); ?>
	<?php endif; ?>	
</p>
<?php endif;
