<?php 
/**
 * Apocrypha Theme Notification Settings Component
 * Andrew Clayton
 * Version 2.0
 * 11-16-2014
 */

?>

<?php get_header(); ?>
	
	<div id="content" role="main">
		<?php apoc_breadcrumbs(); ?>

		<?php locate_template( array( 'members/single/member-header.php' ), true ); ?>	

		<div id="profile-body">
			<?php do_action( 'template_notices' ); ?>
			
			<nav class="reply-header" id="subnav">
				<ul id="profile-tabs" class="tabs" role="navigation">
					<?php bp_get_options_nav(); ?>
				</ul>
			</nav><!-- #subnav -->

			<form action="<?php echo bp_displayed_user_domain() . bp_get_settings_slug() . '/notifications'; ?>" method="post" class="standard-form" id="settings-form">
				<fieldset>
					<?php do_action( 'bp_notification_settings' ); ?>

					<div class="form-right">
						<button type="submit" name="submit" id="submit" class="auto"><i class="fa fa-check"></i>Save Changes</button>
					</div>

					<div class="hidden">
						<?php wp_nonce_field('bp_settings_notifications'); ?>
					</div>
				</fieldset>
			</form>
		</div>
	</div><!-- #content -->
<?php get_footer(); ?>