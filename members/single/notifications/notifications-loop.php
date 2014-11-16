<?php 
/**
 * Apocrypha Theme Notifications Loop
 * Andrew Clayton
 * Version 2.0
 * 11-15-2014
 */
?>

<ol id="notifications-list" class="directory-list" role="main">
	<?php while ( bp_the_notifications() ) : bp_the_notification(); ?>
	<li class="notification">
		<div class="notification-content">
			<?php bp_the_notification_description();  ?>
			<span class="activity"><?php bp_the_notification_time_since();   ?></span>
		</div>
		<div class="notification-actions">
			<?php bp_the_notification_action_links( array( 'sep' => '|' ) ); ?>
		</div>
	</li>
	<?php endwhile; ?>
</ol>
