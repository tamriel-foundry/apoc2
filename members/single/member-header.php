<?php 
/**
 * Apocrypha Theme User Profile Header
 * Andrew Clayton
 * Version 2.0
 * 12-12-2014
 */

// Get the currently displayed user
global $user;
$user 	= new Apoc_User( bp_displayed_user_id() , 'profile' , 200 ); ?>

<div id="profile-header">

	<header class="post-header <?php echo $user->faction; ?>">
		<h1 id="profile-title" class="post-title">User Profile - <?php echo $user->fullname; ?></h1>
		<p id="profile-description" class="post-byline"><?php echo $user->byline; ?></p>		
		<div id="profile-actions" class="header-actions">
		<?php if ( bbp_is_user_home() ) : ?>
			<a class="button" href="<?php echo $user->profile; ?>profile/edit" title="Edit your user profile"><i class="fa fa-edit"></i>Edit Profile</a>
		<?php else : ?>
			<?php do_action( 'bp_member_header_actions' ); ?>
		<?php endif; ?>
		</div>
	</header><!-- #profile-header -->
	
	<div id="profile-user" class="reply-author user-<?php echo $user->id; ?>">
		<?php echo $user->block; ?>	
	</div>

	<div id="profile-content">
		<nav id="directory-nav" role="navigation">
			<ul id="directory-actions">
				<?php bp_get_displayed_user_nav(); ?>
			</ul>
		</nav>

		<blockquote id="profile-status" class="user-status">
			<?php echo '@' . $user->nicename . ' &rarr; '; ?>
			<div id="latest-status">
			<?php if ( !empty($user->status['content']) ) echo $user->status['content']; ?>
			</div>
			<?php if ( bp_is_my_profile() ) : ?>
				<a class="update-status-button button-dark"><i class="fa fa-pencil"></i>What's New?</a>
			<?php endif; ?>
		</blockquote>

		<?php if ( bp_is_my_profile() ) : ?>
			<?php locate_template( array( 'activity/post-form.php'), true ); ?>
		<?php endif; ?>

		<div id="detail-post-count" class="widget profile-widget">
			<header class="widget-header">
				<h3 class="widget-title">Posting History</h3>
			</header>
			<ul id="detail-post-count">
				<?php $posts = $user->posts; 
				foreach ( array('comments','topics','replies') as $ptype ) if ( !isset($posts[$ptype]) ) $posts[$ptype] = 0;
				if ( isset( $posts['articles'] ) && $posts['articles'] > 0 ) : ?>
					<li><i class="fa fa-tag fa-fw"></i>Articles <span class="activity-count"><?php echo $posts['articles']; ?></span></li>
				<?php endif; ?>
				<li><i class="fa fa-comment fa-fw"></i>Comments <span class="activity-count"><?php echo $posts['comments']; ?></span></li>
				<li><i class="fa fa-bookmark fa-fw"></i>Topics <span class="activity-count"><?php echo $posts['topics']; ?></span></li>
				<li><i class="fa fa-reply fa-fw"></i>Replies <span class="activity-count"><?php echo $posts['replies']; ?></span></li>
				<li class="post-count-total"><i class="fa fa-star fa-fw"></i>Total <span class="activity-count"><?php echo $posts['total']; ?></span></li>
			</ul>
		</div>

		<div id="profile-badges" class="widget profile-widget">
			<header class="widget-header">
				<h3 class="widget-title">User Badges</h3>
			</header>
			<ul id="user-badges">
				<?php if ( !empty( $user->badges ) ) :
				foreach ( $user->badges as $badge ) : ?>
					<li class="user-badge <?php echo $badge['class']; ?> <?php echo $badge['tier']; ?>" title="<?php echo $badge['name']; ?>"></li>
				<?php endforeach;
				else : ?>
					<li>No badges earned yet!</li>
				<?php endif; ?>
			</ul>
		</div>

		<div id="profile-contacts" class="widget profile-widget">
			<header class="widget-header">
				<h3 class="widget-title">Contact Info</h3>
			</header>
			<?php $user->contacts(); ?>
		</div>

	</div>
</div>