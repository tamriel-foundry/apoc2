<?php 
/**
 * Apocrypha Theme Activity Directory Template
 * Andrew Clayton
 * Version 2.0
 * 9-20-2014
 */
?>

<?php get_header(); ?>
	
	<div id="content" role="main">
		<?php apoc_breadcrumbs(); ?>

		<header id="directory-header" class="post-header <?php apoc_post_header_class( 'page' ); ?>">
			<h1 class="post-title"><?php apoc_title(); ?></h1>
			<p class="post-byline"><?php apoc_description(); ?></p>
		</header>

		<nav id="directory-nav" class="activity-type-tabs" role="navigation">
			<ul id="directory-actions" class="directory-tabs">
				<li class="selected" id="activity-all"><a href="<?php bp_activity_directory_permalink(); ?>">All Members<span><?php echo bp_get_total_member_count(); ?></span></a></li>
				
				<?php if ( is_user_logged_in() ) : ?>
					<li id="activity-friends"><a href="<?php echo bp_loggedin_user_domain() . bp_get_activity_slug() . '/' . bp_get_friends_slug() . '/'; ?>">My Friends<span><?php echo bp_get_total_friend_count(); ?></span></a></li>
												
					<?php if ( bp_get_total_group_count_for_user() ) : ?>
					<li id="activity-groups"><a href="<?php echo bp_loggedin_user_domain() . bp_get_activity_slug() . '/' . bp_get_groups_slug() . '/'; ?>" title="Recent activity in my guilds.">My Guilds<span><?php echo bp_get_total_group_count_for_user(); ?></span></a></li>
					<?php endif; ?>
					
					<li id="activity-mentions">
						<a href="<?php echo bp_loggedin_user_domain() . bp_get_activity_slug() . '/mentions/'; ?>" title="Activity where I'm mentioned">My Mentions
						<?php if ( bp_get_total_mention_count_for_user() ) : ?> 
							<span><?php echo bp_get_total_mention_count_for_user(); ?></span>
						<?php endif; ?>
						</a>
					</li>		
				<?php endif; ?>
			</ul>
		</nav><!-- #directory-nav -->

		<?php if ( is_user_logged_in() ) : $user = apoc()->user; ?>
		<blockquote id="profile-status" class="user-status">
			<p><?php echo '@' . $user->display_name . ' &rarr; <span id="latest-status">' . bp_get_activity_latest_update( $user->ID ); ?></span></p>
			<a class="update-status-button button-dark"><i class="fa fa-pencil"></i>What's New?</a>
		</blockquote>
		<?php locate_template( array( 'activity/post-form.php'), true ); ?>
		<?php endif; ?>

		<?php do_action( 'template_notices' ); ?>

		<header class="reply-header" id="subnav" role="navigation">
			<div class="directory-member reply-author">Member</div>
			<div class="directory-content">Activity</div>
			<div id="activity-filter-select" class="filter">
				<select id="activity-filter-by">
				<option value="-1">All Activity</option>
				<option value="activity_update">Status Updates</option>
				<option value="new_blog_post">Front-Page Articles</option>
				<option value="new_blog_comment">Article Comments</option>						
				<?php do_action( 'bp_activity_filter_options' ); // Topics & Replies ?>
				<option value="new_member">New Members</option>
				<option value="friendship_accepted,friendship_created">Friendships</option>
				<option value="created_group">New Guilds</option>
				<option value="joined_group">Guild Memberships</option>
				</select>
			</div>
		</header><!-- #subnav -->	

		<div id="activity-directory" class="activity" role="main">
			<?php locate_template( array( 'activity/activity-loop.php' ), true ); ?>
		</div><!-- #activity-directory -->

	</div>
<?php get_footer(); ?>
