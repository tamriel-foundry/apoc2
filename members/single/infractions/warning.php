<?php 
/**
 * Apocrypha Theme Moderator Notes Component
 * Andrew Clayton
 * Version 2.0
 * 11-09-2014
 */

// Get the currently displayed user
global $user;
$level		= $user->warnings['level'] > 0 ? $user->warnings['level'] : 0;
$points		= 1;
$action_url = bp_get_displayed_user_link() . 'infractions';

// Process new warnings
if ( isset( $_POST['issue_warning_nonce'] ) && wp_verify_nonce( $_POST['issue_warning_nonce'] , 'issue-warning' ) ) {
	
	// Validate data
	$warnings 		= ( $level > 0 ) ? $user->warnings['history'] : array();
	$moderator		= bp_core_get_user_displayname( get_current_user_id() );
	
	// Warning points
	$points = $_POST['warning-points'];
	
	// Warning reason
	if ( empty( $_POST['warning-reason'] ) )
		$error = 'You must supply a reason for issuing this warning.';
	else
		$reason = wpautop( $_POST['warning-reason'] );
		
	// Warning date
	if ( '' != $_POST['warning-date'] )
		$date = date( 'M j, Y' , strtotime( $_POST['warning-date'] , current_time( 'timestamp' ) ) );
	else
		$date = date('M j, Y', current_time( 'timestamp' ) );

	// Add the new warning to the array
	$warnings[] = array(
		'points' 	=> $points,
		'reason' 	=> trim( $reason ),
		'moderator' => $moderator,
		'date' 		=> $date,
	);
	
	// Make sure there was no error
	if ( empty( $error ) ) {
	
		// Update user meta
		update_user_meta( $user->id , 'infraction_history' , $warnings );
		bp_core_add_message( 'Warning successfully issued!' , 'success' );
		
		// Maybe ban the user
		if ( $level + $points == 5 ) {
			$u = new WP_User( $user->id );
			$u->set_role('banned');	
		}
		
		// Email people
		if ( $_POST['email-user'] || $_POST['email-mods'] ) {
		
			// Set the email headers
			$name		= bp_get_displayed_user_username();
			$subject 	= "[Tamriel Foundry] Warning Issued to $name";
			$headers 	= "From: Foundry Discipline Bot <noreply@tamrielfoundry.com>\r\n";
			$headers	.= "Content-Type: text/html; charset=UTF-8";
			
			// Construct the message
			$body = '<p>Your user account, ' . $name . ', has been issued a warning for ' . $points . ' point(s) by the moderation team at Tamriel Foundry for the following reason:</p>';
			$body .= '&nbsp;';
			$body .= '<p><strong>' . $reason . '</strong></p>';
			$body .= '&nbsp;';
			$body .= '<p>Please review the Tamriel Foundry <a href="http://tamrielfoundry.com/topic/tamriel-foundry-code-of-conduct/" title="Read the Code of Conduct" target="_blank">Code of Conduct</a> to better understand the expectations we have of our users.';
			$body .= 'You may review your current infractions on your user profile using the following link:</p>';
			$body .= '<p><a href="' . $action_url . '" title="View Your Infractions" target="_blank">' . $action_url . '</a>';
			
			// Send the message
			if ( $_POST['email-user'] ) {
				$emailto = bp_get_displayed_user_email();
				wp_mail( $emailto , $subject , $body , $headers );
			}
			if ( $_POST['email-mods'] ) {
				$emailto = get_moderator_emails();
				wp_mail( $emailto , $subject , $body , $headers );
			}				
		}
		
		// Redirect
		wp_redirect( $action_url , 302 );
	}
} ?>


<?php get_header(); ?>
	
	<div id="content" role="main">
		<?php apoc_breadcrumbs(); ?>

		<?php locate_template( array( 'members/single/member-header.php' ), true ); ?>	

		<nav class="reply-header" id="subnav">
			<ul id="profile-tabs" class="tabs" role="navigation">
				<?php bp_get_options_nav(); ?>
			</ul>
		</nav><!-- #subnav -->

		<div id="user-profile">

			<div class="instructions">
				<h3>Infraction and Warning Policy.</h3>
				<ul>
					<li>1 point - Warning</li>
					<li>2 points - Warning</li>
					<li>3 points - Warning</li>
					<li>4 points - Temporary Suspension</li>
					<li>5 points - Permanent Ban</li>
				</ul>
				<p>Points will not automatically depreciate over time, and will only be removed under special circumstances at the discretion of site moderators. If you wish to appeal a warning that you have received, you may contact one of the site moderators with your request. Be aware that frivolous appeals can be met with further disciplinary action. Please be kind and respectful to the staff of Tamriel Foundry which works diligently to enhance and protect this community.</p>
			</div>

			<p class="warning">The current warning level for <?php bp_displayed_user_username(); ?> is <?php echo $level; ?> points.</p>
			
			<?php if ( user_can( $user->id , 'moderate' ) ) : ?>
			<p class="error">You cannot issue a warning to another moderator.</p>
				
			<?php elseif ( !empty( $error ) ) : ?>
			<p class="error"><?php echo $error; ?></p>	

			<?php else : ?>
			<form id="send-warning-form" action="<?php echo $action_url ?>/issue" name="send-warning-form" method="post">
				<fieldset>
					
					<div class="form-left">
						<label for="warning-points"><i class="fa fa-warning"></i> How Many Points: </label>
						<select name="warning-points">
						<?php while ( 5 - $level > 0 ) :
							echo '<option>' . $points . '</option>';
							$level++;
							$points++;
						endwhile; ?>
						</select>
					</div>
					
					<div class="form-right">
						<label for="warning-date"><i class="fa fa-calendar"></i> Infraction Date (optional): </label>
						<input type="text" name="warning-date" id="warning-date" />
					</div>
					
					<div class="form-full">
						<label for="warning-reason"><i class="fa fa-pencil"></i>Reason: </label>
						<textarea name="warning-reason" id="warning-reason" rows="5"></textarea>
					</div>
					
					<div class="form-left">
						<ul class="checkbox-list">
							<li>
								<input type="checkbox" name="email-user" id="email-user" checked="checked"/>
								<label for="email-user">Notify user?</label>
							</li>
							<li>
								<input type="checkbox" name="email-mods" id="email-mods" checked="checked"/>
								<label for="email-user">Notify moderators?</label>
							</li>
						</ul>
					</div>
					
					<div class="submit form-right">
						<button type="submit" name="issuewarning"><i class="fa fa-warning"></i>Issue Warning</button>
					</div>
					
					<div class="hidden">
						<?php wp_nonce_field( 'issue-warning' , 'issue_warning_nonce' ) ?>
						<input name="action" type="hidden" id="action" value="issue-warning" />
					</div>
				</fieldset>
			</form>
			<?php endif; ?>

	</div><!-- #content -->
<?php get_footer(); ?>