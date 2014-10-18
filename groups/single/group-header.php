<?php 
/**
 * Apocrypha Theme Group Profile Header
 * Andrew Clayton
 * Version 2.0
 * 10-18-2014
 */

// Get the currently displayed user
$group = new Apoc_Group( bp_displayed_user_id() , 'profile' , 200 ); 
$type = ( $group->guild ) ? 'Guild' : 'Group'; ?>

<div id="profile-header">

	<header class="post-header <?php echo $group->faction; ?>">
		<h1 id="profile-title" class="post-title"><?php echo $type; ?> Profile - <?php echo $group->fullname; ?></h1>
		<p id="profile-description" class="post-byline <?php echo $group->faction; ?>"><?php echo $group->byline; ?></p>

		<div id="profile-actions" class="header-actions">
			<?php do_action( 'bp_group_header_actions' ); ?>
		</div>
	</header><!-- #profile-header -->

	<div id="profile-user" class="reply-author">
		<?php echo $group->block; ?>	
	</div>

	<div id="profile-content">
		
		<nav id="directory-nav" role="navigation">
			<ul id="directory-actions" class="directory-tabs">
				<?php bp_get_options_nav(); ?>
			</ul>
		</nav>
		
		<div id="group-activity">
			<span class="activity"><?php printf( __( 'active %s', 'buddypress' ), bp_get_group_last_active() ); ?></span>
		</div>

		<div id="group-details" class="widget profile-widget">
			<header class="widget-header">
				<h3 class="widget-title"><?php echo $type; ?> Details</h3>
			</header>
			group details
		</div>


		<div id="group-administrators" class="widget profile-widget">
			<header class="widget-header">
				<h3 class="widget-title"><?php echo $type; ?> Leaders</h3>
			</header>
			<?php echo $group->admins; ?>
		</div>

		<?php if (bp_group_has_moderators() ) : ?>
		<div id="group-moderators" class="widget profile-widget">
			<header class="widget-header">
				<h3 class="widget-title"><?php echo $type; ?> Officers</h3>
			</header>
			<?php echo $group->mods; ?>
		</div>
		<?php endif; ?>
	</div>
</div>