<?php 
/**
 * Apocrypha Theme Members Directory Template
 * Andrew Clayton
 * Version 2.0
 * 9-22-2014
 */
?>

<?php get_header(); ?>
	
	<div id="content" role="main">
		<?php apoc_breadcrumbs(); ?>
		
		<form action="<?php the_permalink(); ?>" method="post" id="members-directory-form" class="dir-form">
		
			<header id="directory-header" class="post-header <?php apoc_post_header_class( 'page' ); ?>">
				<h1 class="post-title"><?php apoc_title(); ?></h1>
				<p class="post-byline"><?php apoc_description(); ?></p>
				<?php wp_nonce_field( 'directory_members', '_wpnonce-member-filter' ); ?>
			</header>

			<nav id="directory-nav" class="dir-list-tabs" role="navigation">
				<ul id="directory-actions" class="directory-tabs">
					<li class="selected" id="members-all"><a href="<?php echo trailingslashit( SITEURL . '/' . bp_get_members_root_slug() ); ?>">All Members<span><?php echo bp_get_total_member_count(); ?></span></a></li>
					<?php if ( is_user_logged_in() ) : ?>
					<li id="members-personal"><a href="<?php echo bp_loggedin_user_domain() . bp_get_friends_slug() . '/my-friends/' ?>">My Friends<span><?php echo bp_get_total_friend_count(); ?></span></a></li>
					<?php endif; ?>			
				</ul>
			</nav><!-- #directory-nav -->

			<?php do_action( 'template_notices' ); ?>

			<header class="reply-header" id="subnav" role="navigation">
				<div class="directory-member">Member</div>
				<div class="directory-content">Current Status
					<div id="members-order-select" class="filter">
						<select id="members-order-by">
							<option value="active"><?php _e( 'Last Active', 'buddypress' ); ?></option>
							<option value="newest"><?php _e( 'Newest Registered', 'buddypress' ); ?></option>
							<option value="alphabetical"><?php _e( 'Alphabetical', 'buddypress' ); ?></option>
						</select>
					</div>
				</div>
			</header><!-- #subnav -->

			<div id="members-dir-list" class="members dir-list">
				<?php locate_template( array( 'members/members-loop.php' ), true ); ?>
			</div><!-- #members-dir-list -->		


		</form>
	</div><!-- #content -->
<?php get_footer(); ?>