<?php 
/**
 * Apocrypha Theme Registration Template
 * Andrew Clayton
 * Version 2.0
 * 5-12-2014
 */
?>

<?php get_header(); ?>

	<div id="content" role="main">

		<header id="registration-header" class="entry-header">
			<h1 class="entry-title"><?php echo SITENAME; ?> User Registration</h1>
		</header>
		
		<?php the_post(); the_content(); ?>
		
		<form action="<?php echo apoc()->url; ?>" name="signup_form" method="post" enctype="multipart/form-data">
		
			<?php do_action( 'template_notices' ); ?>
			
			<?php // New user registration is disabled
			if ( 'registration-disabled' == bp_get_current_signup_step() ) : ?>
				<p class="error">Sorry, but new user registration is currently disabled on <?php echo SITENAME; ?>. Please try again later!</p>
	
	
			<?php // Registration step
			elseif ( 'request-details' == bp_get_current_signup_step() ) : ?>
				
				<fieldset id="registration-basic-details">
					<ol id="registration-account-details">
						
						<li class="text">
							<?php do_action( 'bp_signup_username_errors' ); ?>
							<label class="settings-field-label" for="signup_username"><i class="icon-user icon-fixed-width"></i>Desired Username:</label>
							<input type="text" name="signup_username" id="signup_username" value="<?php bp_signup_username_value(); ?>" size="40" />					
						</li>
						
						<li class="text">
							<?php do_action( 'bp_signup_email_errors' ); ?>
							<label class="settings-field-label" for="signup_email"><i class="icon-envelope icon-fixed-width"></i>Your Email Address:</label>
							<input type="text" name="signup_email" id="signup_email" value="<?php bp_signup_email_value(); ?>" size="40" />
						</li>
						
						<li class="text">
							<?php do_action( 'bp_signup_password_errors' ); ?>	
							<label class="settings-field-label" for="signup_password"><i class="icon-key icon-fixed-width"></i>Choose A Password:</label>
							<input type="password" name="signup_password" id="signup_password" value="" autocomplete="off" size="40" />
						</li>
						
						<li class="text">
							<?php do_action( 'bp_signup_password_confirm_errors' ); ?>	
							<label class="settings-field-label" for="signup_password_confirm"><i class="icon-ok icon-fixed-width"></i>Confirm Password:</label>
							<input type="password" name="signup_password_confirm" id="signup_password_confirm" value="" autocomplete="off" size="40" />
						</li>
					</ol>
				</fieldset>
			
			
	
			<?php endif; ?>
		
		</form>	
	</div><!-- #content -->
	
<?php get_footer(); ?>