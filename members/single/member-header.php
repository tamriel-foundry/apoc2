<?php 
/**
 * Apocrypha Theme User Profile Header
 * Andrew Clayton
 * Version 2.0
 * 9-24-2014
 */

// Get the currently displayed user
$user 	= new Apoc_User( bp_displayed_user_id() , 'profile' , 200 );
?>

<div id="profile-header">

	<header class="post-header <?php echo $user->faction; ?>">
		<h1 id="profile-title" class="post-title">User Profile - <?php echo $user->fullname; ?></h1>
		<p id="profile-description" class="post-byline <?php echo $user->faction; ?>"><?php echo $user->byline; ?></p>		
		<div id="profile-actions">
		<?php if ( bbp_is_user_home() ) : ?>
			<a class="button" href="<?php echo $user->domain; ?>profile/edit" title="Edit your user profile"><i class="fa fa-edit"></i>Edit Profile</a>
		<?php else : ?>
			<?php do_action( 'bp_member_header_actions' ); ?>
		<?php endif; ?>
		</div>
	</header><!-- #profile-header -->
	
	<div id="profile-user" class="reply-author">
		<?php echo $user->block; ?>	
	</div>

	<div id="profile-content">
		<nav id="directory-nav" role="navigation">
			<ul id="directory-actions" class="directory-tabs">
				<?php bp_get_displayed_user_nav(); ?>
			</ul>
		</nav>

		<blockquote id="profile-status" class="user-status">
			<p><?php echo '@' . $user->nicename . ' &rarr; <span id="latest-status">' . bp_get_activity_latest_update( $user->id ); ?></span></p>
			<?php if ( bp_is_my_profile() ) : ?>
				<a class="update-status-button button-dark"><i class="fa fa-pencil"></i>What's New?</a>
			<?php else : ?>
				<span class="activity"><?php bp_last_activity( $user->id ); ?></span>
			<?php endif; ?>
		</blockquote>

		<div id="detail-post-count" class="widget profile-widget">
			<header class="widget-header">
				<h3 class="widget-title">Post Details</h3>
			</header>

			<ul id="detail-post-count">
				<?php $posts = $user->posts; 
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
			<ul id"user-badges">
				<li>badge</li>
			</ul>
		</div>

		<div id="profile-contacts" class="widget profile-widget">
			<header class="widget-header">
				<h3 class="widget-title">Contact Info</h3>
			</header>
			<?php // $user->contacts(); ?>
		</div>


	</div>
</div>