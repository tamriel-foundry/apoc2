<?php 
/**
 * Apocrypha Theme Group Profile Template
 * Andrew Clayton
 * Version 2.0
 * 10-18-2014
 */

// Load the requested group
if ( bp_has_groups() ) : while ( bp_groups() ) : bp_the_group(); 
?>

<?php get_header(); ?>
	
	<div id="content" role="main">
		<?php apoc_breadcrumbs(); ?>

		<?php locate_template( array( 'groups/single/group-header.php' ), true ); ?>

		<div id="profile-body">
			<?php do_action( 'template_notices' ); ?>

			<?php // Group is visible
			if ( bp_group_is_visible() ) :			
				if ( bp_is_group_home() ) :
					locate_template( array( 'groups/single/front.php' 			), true );
				elseif ( bp_is_group_activity() ) : 
					locate_template( array( 'groups/single/activity.php' 		), true );
				elseif ( bp_is_group_members() ) :
					locate_template( array( 'groups/single/members.php' 		), true );
				elseif ( bp_is_group_invites() ) : 
					locate_template( array( 'groups/single/send-invites.php' 	), true );
				elseif ( bp_is_group_admin_page() ) : 
					locate_template( array( 'groups/single/admin.php' 			), true );
				else : 
					locate_template( array( 'groups/single/plugins.php' 		), true );
				endif; ?>

			<?php // Group is not visible
			else : 
				if ( bp_is_group_membership_request() ) :
					locate_template( array( 'groups/single/request-membership.php' ), true );
				else : ?>
				<p id="message" class="warning"><?php bp_group_status_message(); ?></p>
				<?php endif; ?>
			<?php endif; ?>

		</div>
	</div><!-- #content -->
<?php get_footer(); ?>
<?php endwhile; endif; ?>