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
		<?php apoc_breadcrumbs(); ?>

		<header id="registration-header" class="post-header <?php apoc_post_header_class('post'); ?>">
			<h1 class="post-title"><?php apoc_title(); ?></h1></h1>
			<p class="post-byline"><?php apoc_description(); ?></p>			
		</header>
		
		<form id="signup-form" action="<?php echo apoc()->url; ?>" name="signup_form" method="post" enctype="multipart/form-data">
		
			<?php do_action( 'template_notices' ); ?>
			
			<?php // New user registration is disabled
			if ( 'registration-disabled' == bp_get_current_signup_step() ) : ?>
				<p class="error">Sorry, but new user registration is currently disabled on <?php echo SITENAME; ?>. Please try again later!</p>
	
	
			<?php // Registration step
			elseif ( 'request-details' == bp_get_current_signup_step() ) : ?>

				<div class="instructions">
					<h3 class="double-border">Important Registration Instructions</h3>
					<ul>
						<li>Please carefully review the following form, paying special attention to the registration instructions. All fields are required.</li>
						<li>Your username is how you will be known throughout the community. Choose the name that you wish to be associated with your user profile on Tamriel Foundry. Spaces are permitted, but will be replaced with hyphens (-) for your login credentials.</li>
						<li>Please do not register a user account for your guild. Guilds are collections of individual users, not users themselves.</li>
						<li>A valid email address is required in order to activate your account, any site notifications which you elect to recieve or correspondence from Tamriel Foundry administrators will be sent to this address.</li>		
						<li>If you have any trouble completing the registration process, please contact <a href="mailto:admin@tamrielfoundry.com?Subject=Registration%20Trouble" target="_blank">admin@tamrielfoundry.com</a> for assistance.</p>
					</ul>
				</div>
				
				<fieldset id="registration-basic-details">
					<div>
						<?php do_action( 'bp_signup_username_errors' ); ?>
						<label class="settings-field-label" for="signup_username"><i class="fa fa-user fa-fw"></i>Desired Username:</label>
						<input type="text" name="signup_username" id="signup_username" value="<?php bp_signup_username_value(); ?>" size="40" />					
					</div>
					
					<div>
						<?php do_action( 'bp_signup_email_errors' ); ?>
						<label class="settings-field-label" for="signup_email"><i class="fa fa-envelope fa-fw"></i>Your Email Address:</label>
						<input type="text" name="signup_email" id="signup_email" value="<?php bp_signup_email_value(); ?>" size="40" />
					</div>
					
					<div>
						<?php do_action( 'bp_signup_password_errors' ); ?>	
						<label class="settings-field-label" for="signup_password"><i class="fa fa-key fa-fw"></i>Choose A Password:</label>
						<input type="password" name="signup_password" id="signup_password" value="" autocomplete="off" size="40" />
					</div>
					
					<div>
						<?php do_action( 'bp_signup_password_confirm_errors' ); ?>	
						<label class="settings-field-label" for="signup_password_confirm"><i class="fa fa-check fa-fw"></i>Confirm Password:</label>
						<input type="password" name="signup_password_confirm" id="signup_password_confirm" value="" autocomplete="off" size="40" />
					</div>
				
					<?php do_action( 'bp_after_account_details_fields' ); ?>
				</fieldset>

				<div id="registration-terms" class="instructions">
					<h3 class="double-border">Terms of Registration</h3>
					<p>By registering for Tamriel Foundry, you agree to respect our sitewide <a href="http://tamrielfoundry.com/topic/tamriel-foundry-code-of-conduct" title="Tamriel Foundry Code of Conduct" target="_blank">Code of Conduct</a>. Violations of these rules may result in immediate warning, suspension, or removal.</p>
					<ul class="form-left" style="padding: 0 5%;">
						<li style="list-style:none;"><h4><u>Tamriel Foundry IS:</u></h4></li>
						<li>An active community of <em>ESO</em> enthusiasts and players.</li>
						<li>A platform supporting both PC and console players.</li>
						<li>A helpful resource for useful articles, guides, and tools.</li>
						<li>A forum for discussing <em>ESO</em> gameplay mechanics and strategies.</li>
					</ul>
					<ul class="form-right" style="padding: 0 5%; text-align:left;">
						<li style="list-style:none;"><h4><u>Tamriel Foundry IS NOT:</u></h4></li>
						<li>A general purpose gaming forum.</li>		
						<li>A comprehensive Elder Scrolls lore compendium or library.</li>
						<li>A platform dedicated to roleplaying.</li>		
						<li>A listing service for guild recruitment and advertisment.</li>		
					</ul>
				</div>

				<fieldset id="registration-use-terms">
				<h3 class="double-border">Membership Agreement</h3>
					<ul class="checkbox-list">
						<li>
							<input type="checkbox" name="confirm_tos_box" id="confirm_tos_box" value="confirmed" autocomplete="off" />
							<label for="confirm_tos_box">I understand the distinction regarding the nature and purpose of Tamriel Foundry.</label>
							<?php do_action( 'bp_confirm_tos_box_errors' ); ?>
						</li>
						<li>
							<input type="checkbox" name="confirm_coc_box" id="confirm_coc_box" value="confirmed" autocomplete="off" />
							<label for="confirm_coc_box">I agree to comply by the terms of the <a href="http://tamrielfoundry.com/topic/tamriel-foundry-code-of-conduct/" target="_blank" title="Read the Code of Conduct">Code of Conduct</a> when participating in Tamriel Foundry.</label>
							<?php do_action( 'bp_confirm_coc_box_errors' ); ?>
						</li>
					</ul>
				</fieldset>

				<fieldset>
					<h3 class="double-border">Confirm Your Humanity</h3>
					<div id="humanity-section">
						<?php apoc_registration_humanity_image(); ?>
						<div id="humanity-fields">		
							<?php do_action( 'bp_confirm_humanity_errors' ); ?>
							<label for="confirm_humanity"><i class="icon-search"></i>Identify which iconic Elder Scrolls race is shown in the image to the left:</label><br><br>
							<input type="text" name="confirm_humanity" id="confirm_humanity" value="<?php if ( isset ( $_POST['confirm_humanity'] ) ) echo $_POST['confirm_humanity']; ?>" autocomplete="off" size="40" placeholder="&larr; What is that thing???"  />
						</div>
					</div>	
				</fieldset>

				<fieldset id="registration-complete">
	
					<div class="form-right">
						<button type="submit" name="signup_submit" id="signup_submit" ><i class="fa fa-check"></i>Complete Account Creation</button>
					</div>


					<div class="hidden">

						<?php // Spoof XProfile registration steps
						if ( bp_is_active( 'xprofile' ) ) : ?>
							<?php if ( bp_has_profile( array( 'profile_group_id' => 1, 'fetch_field_data' => false ) ) ) : while ( bp_profile_groups() ) : bp_the_profile_group(); ?>
								<?php while ( bp_profile_fields() ) : bp_the_profile_field(); ?>
									<?php do_action( bp_get_the_profile_field_errors_action() ); ?>
									<input type="text" name="<?php bp_the_profile_field_input_name(); ?>" id="<?php bp_the_profile_field_input_name(); ?>" value="<?php bp_the_profile_field_edit_value(); ?>" />
								<?php endwhile; ?>
								<input type="hidden" name="signup_profile_field_ids" id="signup_profile_field_ids" value="<?php bp_the_profile_group_field_ids(); ?>" />
							<?php endwhile; endif; ?>
							<?php do_action( 'bp_after_signup_profile_fields' ); ?>
						<?php endif; ?>

						<?php do_action( 'bp_before_registration_submit_buttons' ); ?>				
						<?php wp_nonce_field( 'bp_new_signup' ); ?>
					</div>	
				</fieldset>	

			<?php // Signup completed confirmation
			elseif ( 'completed-confirmation' == bp_get_current_signup_step() ) : ?>

				<p class="update">Thank you for registering a new account on <?php echo SITENAME; ?>!</p>
				
				<div class="instructions">
				<?php if ( bp_registration_needs_activation() ) : ?>
					<h3 class="double-border">Signup Almost Complete!</h3>
					<p>You have successfully created your account! However, before you can begin using your account, you must finalize your Tamriel Foundry registration by confirming your account via the email we have just sent to your given email address. This email will contain an activation link, following it will automatically complete the activation process. If you have trouble locating this email, please check your <u>SPAM</u> folder, <em>just in case</em>. Thanks for registering!</p>	
				<?php else : ?>
					<h3 class="double-border">Congratulations, Signup Complete!</h3>
					<p>You have successfully created your account! You may now log in using the username and password you have just created. Thanks for registering!</p>	
				<?php endif; ?>
				</div>		
			<?php endif; ?>		
			<?php do_action( 'bp_custom_signup_steps' ); ?>

		</form>	
	</div><!-- #content -->
	
<?php get_footer(); ?>