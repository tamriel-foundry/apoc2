<?php 
/**
 * Apocrypha Theme General Settings Component
 * Andrew Clayton
 * Version 2.0
 * 11-15-2014
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

			<form action="<?php echo bp_displayed_user_domain() . bp_get_settings_slug() . '/general'; ?>" method="post" class="standard-form" id="settings-form">
				
				<div class="instructions">	
					<h3 class="double-border bottom">Modify Account Settings</h3>
					<ul>
						<li>You can use this area to change details regarding your Tamriel Foundry user account.</li>
						<li>Your current password required to change your account email or password.</li>
						<li>You are not allowed to change your account username. In exceptional cases, Tamriel Foundry administrators may grant name changes. If you wish to request your username be changed, please email <a href="mailto:admin@tamrielfoundry.com?Subject=Requested%20Username%20Change" title="Email Us">admin@tamrielfoundry.com</a> with the reason for your request.</li>
						<li>You may change your account password, or leave these fields blank for your password to remain unchanged.</li>
					</ul>
				</div>
			
				<fieldset>
					<?php if ( !is_super_admin() ) : ?>
					<div class="form-full">
						<label for="pwd"><i class="fa fa-lock fa-fw"></i>Current Password:</label>
						<input type="password" name="pwd" id="pwd" size="30" value="" />
						<a href="<?php echo site_url( add_query_arg( array( 'action' => 'lostpassword' ), 'wp-login.php' ), 'login' ); ?>" title="<?php _e( 'Password Lost and Found', 'buddypress' ); ?>" class="button">Lost your password?</a>
					</div>
					<?php endif; ?>

					<div class="form-left">
						<label for="login"><i class="fa fa-lock fa-fw"></i>Username:</label>
						<input type="text" name="login" id="login" size="30" value="<?php bp_displayed_user_username(); ?>" disabled />
					</div>
					
					<div class="form-left">
						<label for="email"><i class="fa fa-envelope fa-fw"></i>Change Account Email:</label>
						<input type="text" name="email" id="email" value="<?php echo bp_get_displayed_user_email(); ?>" class="settings-input" size="30"/>
					</div>

					<div class="form-left">
						<label for="pass1"><i class="fa fa-key fa-fw"></i>New Password:</label>
						<input type="password" name="pass1" id="pass1" size="30" value="" class="settings-input small" />
					</div>
					
					<div class="form-left">
						<label for="pass2"><i class="fa fa-check fa-fw"></i>Confirm Password:</label>
						<input type="password" name="pass2" id="pass2" size="30" value="" class="settings-input small" />
					</div>					
				
					<div class="form-right">
						<button type="submit" name="submit"id="submit" class="auto"><i class="fa fa-check"></i>Save Changes</button>
					</div>
				
					<div class="hidden">
						<?php wp_nonce_field( 'bp_settings_general' ); ?>
					</div>

				</fieldset>
			</form>
		</div>
	</div><!-- #content -->
<?php get_footer(); ?>