<?php 
/**
 * Apocrypha Theme Groups Directory Template
 * Andrew Clayton
 * Version 2.0
 * 9-22-2014
 */
?>

<?php get_header(); ?>
	
	<div id="content" role="main">
		<?php apoc_breadcrumbs(); ?>
		
		<form action="<?php the_permalink(); ?>" method="post" id="groups-directory-form" class="dir-form">
		
			<header id="directory-header" class="post-header <?php apoc_post_header_class( 'page' ); ?>">
				<h1 class="post-title"><?php apoc_title(); ?></h1>
				<p class="post-byline"><?php apoc_description(); ?></p>
				<?php wp_nonce_field( 'directory_members', '_wpnonce-member-filter' ); ?>
			</header>

			<nav id="directory-nav" class="dir-list-tabs" role="navigation">
				<ul id="directory-actions" class="directory-tabs">
					<li class="selected" id="groups-all"><a href="<?php echo trailingslashit( SITEURL . '/' . bp_get_groups_root_slug() ); ?>">All Guilds<span><?php echo bp_get_total_group_count(); ?></span></a></li>
					<?php if ( is_user_logged_in() && bp_get_total_group_count_for_user() ) : ?>
					<li id="groups-personal"><a href="<?php echo trailingslashit( bp_loggedin_user_domain() . bp_get_groups_slug() . '/my-groups' ); ?>">My Guilds<span><?php echo bp_get_total_group_count_for_user(); ?></span></a></li>
					<?php endif; ?>
					<li id="groups-aldmeri"><a href="?faction=aldmeri">Aldmeri<span><?php echo count_groups_by_meta( 'group_faction' , 'aldmeri' ); ?></span></a></li>
					<li id="groups-daggerfall"><a href="?faction=daggerfall">Daggerfall<span><?php echo count_groups_by_meta( 'group_faction' , 'daggerfall' ); ?></span></a></li>
					<li id="groups-ebonheart"><a href="?faction=ebonheart">Ebonheart<span><?php echo count_groups_by_meta( 'group_faction' , 'ebonheart' ); ?></span></a></li>
				</ul>
			</nav><!-- #directory-nav -->

			<?php do_action( 'template_notices' ); ?>

			<header class="reply-header" id="subnav" role="navigation">
				<div class="directory-member">Guild</div>
				<div class="directory-content">Description
					<div id="members-order-select" class="filter">
						<select id="groups-order-by">
							<option value="active"><?php _e( 'Last Active', 'buddypress' ); ?></option>
							<option value="popular"><?php _e( 'Most Members', 'buddypress' ); ?></option>
							<option value="newest"><?php _e( 'Newly Created', 'buddypress' ); ?></option>
							<option value="alphabetical"><?php _e( 'Alphabetical', 'buddypress' ); ?></option>
						</select>
						<?php wp_nonce_field( 'directory_groups', '_wpnonce-groups-filter' ); ?>
					</div>
				</div>
			</header><!-- #subnav -->

			<div id="groups-dir-list" class="groups dir-list">
				<?php locate_template( array( 'groups/groups-loop.php' ), true ); ?>
			</div><!-- #groups-dir-list -->		


		</form>
	</div><!-- #content -->
<?php get_footer(); ?>