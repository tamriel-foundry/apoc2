<?php 
/**
 * Apocrypha Theme Notifications Bar
 * Andrew Clayton
 * Version 2.0
 * 10-25-2014
 */

// Retrieve the user's notifications
$nots = apoc_get_notifications();

// Create existence flags
$activity = $nots['counts']['activity'] > 0;
$messages = $nots['counts']['messages'] > 0;
$friends  = $nots['counts']['friends'] > 0;
$groups   = $nots['counts']['groups'] > 0;
?>
	
<ul id="notifications-menu">
	<li id="notifications-activity" class="notification-group <?php if ( $activity ) echo 'active'; ?>">
		<span class="notification-count"><i class="fa fa-comments"></i><?php echo $nots['counts']['activity']; ?></span>
		<?php if ( $activity ) : ?>
		<div class="notification-drop">
			<ul class="notifications-list">
				<?php foreach( $nots['activity'] as $id => $not ) : ?>
				<li>
					<?php echo $not->desc; ?>
					<a class="notification-clear" data-type="<?php echo $not->component_action; ?>" data-id="<?php echo $not->id; ?>" data-count="<?php echo $not->count; ?>"><i class="fa fa-remove"></i></a>
				</li>
				<?php endforeach; ?>
			</ul>
		</div>
		<?php endif; ?>
	</li>

	<li id="notifications-messages" class="notification-group <?php if ( $messages ) echo 'active'; ?>">
		<span class="notification-count"><i class="fa fa-envelope"></i><?php echo $nots['counts']['messages']; ?></span>
		<?php if ( $nots['counts']['messages'] > 0 ) : ?>
		<div class="notification-drop">
			<ul class="notifications-list">
				<?php foreach( $nots['messages'] as $id => $not ) : ?>
				<li>
					<?php echo $not->desc; ?>
					<a class="notification-clear" data-type="<?php echo $not->component_action; ?>" data-id="<?php echo $not->id; ?>" data-count="<?php echo $not->count; ?>"><i class="fa fa-remove"></i></a>
				</li>
				<?php endforeach; ?>
			</ul>
		</div>
		<?php endif; ?>
	</li>

	<li id="notifications-friends" class="notification-group <?php if ( $friends ) echo 'active'; ?>">
		<span class="notification-count"><i class="fa fa-user"></i><?php echo $nots['counts']['friends']; ?></span>
		<?php if ( $nots['counts']['friends'] > 0 ) : ?>
		<div class="notification-drop">
			<ul class="notifications-list">
				<?php foreach( $nots['friends'] as $id => $not ) : ?>
				<li>
					<?php echo $not->desc; ?>
					<a class="notification-clear" data-type="<?php echo $not->component_action; ?>" data-id="<?php echo $not->id; ?>" data-count="<?php echo $not->count; ?>"><i class="fa fa-remove"></i></a>
				</li>
				<?php endforeach; ?>
			</ul>
		</div>
		<?php endif; ?>
	</li>

	<li id="notifications-groups" class="notification-group <?php if ( $groups ) echo 'active'; ?>">
		<span class="notification-count"><i class="fa fa-users"></i><?php echo $nots['counts']['groups']; ?></span>
		<?php if ( $nots['counts']['groups'] > 0 ) : ?>
		<div class="notification-drop">
			<ul class="notifications-list">
				<?php foreach( $nots['groups'] as $id => $not ) : ?>
				<li>
					<?php echo $not->desc; ?>
					<a class="notification-clear" data-type="<?php echo $not->component_action; ?>" data-id="<?php echo $not->id; ?>" data-count="<?php echo $not->count; ?>"><i class="fa fa-remove"></i></a>
				</li>
				<?php endforeach; ?>
			</ul>
		</div>
		<?php endif; ?>
	</li>
</ul>