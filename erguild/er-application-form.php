<?php 
/**
 * Apocrypha Theme Entropy Rising Application Form
 * Andrew Clayton
 * Version 1.3
 * 10-10-2014
 */

 
// Gather information about the current user
$user 				= apoc()->user;
$userid 			= $user->ID;
$username 			= $user->display_name;
$username_link 		= '<a href="http://tamrielfoundry.com/members/'.$username.'" target="_blank">'.$username.'</a>';
$user_email 		= $user->user_email;

// Populate default form values and sanitize form submissions
$account_name 		= isset( $_POST['account-name'] ) 		? trim($_POST['account-name']) : '';
$character_class 	= isset( $_POST['character-class'] ) 	? $_POST['character-class'] : '';
$character_level 	= isset( $_POST['character-level'] ) 	? trim($_POST['character-level']) : '';
$your_age 			= isset( $_POST['your-age'] ) 			? trim($_POST['your-age']) : '';
$preferred_role 	= isset( $_POST['preferred-role'] ) 	? $_POST['preferred-role'] : '';
$playstyle 			= isset( $_POST['playstyle'] )			? $_POST['playstyle'] : '';
$experience 		= isset( $_POST['experience'] ) 		? wpautop( stripslashes( trim( $_POST['experience'] ) ) ) : '';
$youoffer 			= isset( $_POST['youoffer'] ) 			? wpautop( stripslashes( trim( $_POST['youoffer'] ) ) ) : '';
$mainguild 			= isset( $_POST['mainguild'] ) 			? $_POST['mainguild'] : '';
$otherguildurl 		= isset( $_POST['otherguildurl'] )		? $_POST['otherguildurl'] : '';
$voicechat 			= isset( $_POST['voicechat'] ) 			? $_POST['voicechat'] : '';


// Was the form submitted?
if( isset( $_POST['submitted']) ) {

	// Check nonce
	if ( !wp_verify_nonce( $_POST['_wpnonce'] , 'er_guild_application' ) )
		die( 'Security Failure' );

	// Check honeypot
	if( trim( $_POST['checking'] ) !== '' ) 
		die( 'No Bots Allowed' );

	// Account Name
	if( '' === $account_name ) {
		$nameError = 'Please enter your character name.';
		$hasError = true;
	}
	
	// Character Class
	if( '' === $character_class ) {
		$classError = 'Please specify your primary character class.';
		$hasError = true;
	}

	// Current Level
	if( '' === $character_level ) {
		$levelError = 'Please enter the current level of your main character.';
		$hasError = true;
	}

	// Your Age
	if( '' === $your_age ) {
		$ageError = 'Please share your age (in years).';
		$hasError = true;
	}

	// Preferred Role
	if ( '' === $preferred_role ) {
		$roleError = 'Please express your preferred character role in group PvE situations.';
		$hasError = true;
	} 
	
	// Typical Playstyle
	if ( '' === $playstyle ) {
		$playstyleError = 'Please indicate your typical availability and playstyle.';
		$hasError = true;
	}

	// Gaming Experience
	if( '' === $experience ) {
		$experienceError = 'Please describe your MMO experience.';
		$hasError = true;
	}

	// What You Offer
	if( '' === $youoffer ) {
		$youofferError = 'Please explain the characteristics you have to offer Entropy Rising.';
		$hasError = true;
	}
	
	// Primary Guild
	if( '' === $mainguild ) {
		$mainguildError = 'Please indicate whether you are interested in joining Entropy Rising as your primary guild?';
		$hasError = true;
	}

	// Main Guild URL
	if ( 'no' === $mainguild && '' === $otherguildurl ) {
		$guildurlError = 'Please provide link your primary guild website.';
		$hasError = true;
	}
	
	// Voice Chat
	if( '' === $voicechat ) {
		$voicechatError = 'Please describe your ability to use voice communication.';
		$hasError = true;
	}
	
	// Confirm Charter
	if ( !isset($_POST['readrules'] ) ) {
		$readrulesError = 'Please confirm your understanding of our guild charter.';
		$hasError = true;
	}

	// Send email if no errors!
	if( !isset($hasError) ) {
		
		// Email Headers
		$emailto 	= "admin@tamrielfoundry.com";
		$subject 	= "Entropy Rising Guild Application From $username";
		$headers[] 	= "From: $username <$user_email>";
		$headers[] 	= "Content-type: text/html";

		// Basic Information
		$body = "<h3>Basic Information</h3>";
		$body .= "<ul>";
			$body .= "<li>Applicant: $username_link";
			$body .= "<li>Email: $user_email</li>";
			$body .= "<li>Age: $your_age</li>";
			$body .= "<li>Account Name: $account_name</li>";
			$body .= "<li>Character Class: $character_class</li>";
			$body .= "<li>Character Level: $character_level</li>";
			$body .= "<li>Preferred Role: $preferred_role</li>";
			$body .= "<li>Playstyle and Availability: $playstyle</li>";
			$body .= "<li>ER as Primary Guild: $mainguild";
			$body .= "<li>Other Guild Website: $otherguildurl</li>";
			$body .= "<li>Voicechat: $voicechat</li>";
		$body .= "</ul>";
		
		// MMO Experience
		$body .= "<h3>MMO Experience</h3>";
		$body .= "<div>$experience</div>";
		
		// Interest in ER
		$body .= "<h3>Interest in Entropy Rising</h3>";
		$body .= "<div>$youoffer</div>";
	
		// Send mail!
		wp_mail( $emailto , $subject , $body, $headers);
		$emailSent = true;
	}
} ?>

<?php if ( isset( $emailSent ) ) : ?>
<p class="updated">Congratulations <?php echo $username; ?>, your application was successfully submitted! We will attempt to review it within the next several days. You will be contacted via the email address registered on your Tamriel Foundry user account once we have processed your application. Thank you for taking the time to apply to Entropy Rising.
</p>

<?php elseif ( isset($hasError) ) : ?>
<p class="error">Sorry <?php echo $username; ?>, there was an error with your application, please correct any errors before resubmitting this form.</p>

<?php else : ?>
<div class="instructions">
	<p>Hello, <?php echo $username; ?>, thank you for your interest in applying to join Entropy Rising. Please carefully fill out the following form. We receive a large volume of guild applications to join Entropy Rising for a limited number of guild openings. In order to give yourself the best possible chance of being accepted it is important that you answer the following questions carefully and clearly so that we can evaluate how well you would fit in with our guild.</p>
</div>
<?php endif; ?>

<?php if ( !isset( $emailSent ) ) : ?>
<form action="<?php the_permalink(); ?>" id="er-application-form" method="post">
	<fieldset>	
		<div class="form-flex">
			<label for="account-name"><i class="fa fa-tag fa-fa"></i>ESO Username:</label>
			<input type="text" name="account-name" value="<?php echo $account_name; ?>" size="30" placeholder="@username" />
			<?php if( isset( $nameError ) && $nameError ) echo '<p class="error">' . $nameError . '</p>'; ?> 
		</div>
	
		<div class="form-flex">
			<label for="character-class"><i class="fa fa-gear fa-fw"></i>Main Class:</label>
			<select name="character-class">
			<?php $classes = array ( '' , 'dragonknight', 'templar', 'sorcerer', 'nightblade' ); 
				foreach ( $classes as $key => $class ) :
				echo '<option value="' . $class . '" ' . selected( $character_class , $class , false ) . '>' . ucfirst($class) . '</option>';
				endforeach; ?>
			</select>
			<?php if( isset( $classError ) && $classError ) echo '<p class="error">' . $classError . '</p>'; ?> 
		</div>
		
		<div class="form-flex">
			<label for="character-level"><i class="fa fa-bookmark fa-fw"></i>Veteran Rank:</label>
			<select name="character-level">
				<option value=""></option>
				<option value="max" <?php selected( $character_level , 'max' ); ?>>VR14</option>
				<option value="near" <?php selected( $character_level , 'near' ); ?>>VR10-13</option>
				<option value="low" <?php selected( $character_level , 'low' ); ?>>&lt; VR10</option>
			</select>
			<?php if( isset( $levelError ) && $levelError ) echo '<p class="error">' . $levelError . '</p>'; ?> 
		</div>
		
		<div class="form-flex">
			<label for="preferred-role"><i class="fa fa-shield fa-fw"></i>Preferred Role (group PvE):</label>
			<select name="preferred-role">
				<option value=""></option>
				<option value="dps" <?php selected( $preferred_role , 'dps' ); ?>>Damage</option>
				<option value="heal" <?php selected( $preferred_role , 'heal' ); ?>>Healing</option>
				<option value="tank" <?php selected( $preferred_role , 'tank' ); ?>>Tanking</option>			
			</select>
			<?php if( isset( $roleError ) && $roleError ) echo '<p class="error">' . $roleError . '</p>'; ?> 
		</div>
		
		<div class="form-flex">
			<label for="playstyle"><i class="fa fa-calendar fa-fw"></i>How Old Are You?:</label>
			<input type="text" name="your-age" value="<?php echo $your_age; ?>" size="4" placeholder="years"/>
			<?php if( isset( $ageError ) && $ageError ) echo '<p class="error">' . $ageError . '</p>'; ?> 
		</div>
		
		<div class="form-full">	
			<p><i class="fa fa-clock-o fa-fw"></i>Typical Availability:</p>
			<ul class="checkbox-list">
				<li><input type="radio" name="playstyle" value="casual" <?php checked( $playstyle , 'casual' ); ?>/><label for="playstyle">0-13 Hours/Week</label></li>
				<li><input type="radio" name="playstyle" value="moderate" <?php checked( $playstyle , 'moderate' ); ?>/><label for="playstyle">14-27 Hours/Week</label></li>
				<li><input type="radio" name="playstyle" value="hardcore" <?php checked( $playstyle , 'hardcore' ); ?>/><label for="playstyle">28+ Hours/Week</label></li>
			</ul>
			<?php if( isset( $playstyleError ) && $playstyleError ) echo '<p class="error">' . $playstyleError . '</p>'; ?> 
		</div>
		
		<div class="form-full">	
			<p><i class="fa fa-list fa-fw"></i>Please describe your prior MMO experience, placing emphasis on past competitive raiding or PvP experience. Describe your experience with <em>ESO</em> in detail. Some specific items to mention include:</p>
			<ul>
				<li>Describe your main character(s) and their builds.</li>
				<li>What is your preferred playstyle and role within a group?</li>
				<li>What endgame PvE content have you completed? What do you consider your most significant achievement?</li>
				<li>What item sets do you typically use?</li>
				<li>Are you interested in PvP? How do you adapt your character build to be successful in PvP?</li>
			</ul>
			<?php if( isset( $experienceError ) && $experienceError ) echo '<p class="error">' . $experienceError . '</p>'; ?> 
			
			<?php // Load the TinyMCE Editor
			wp_editor( $experience, 'experience', array(
				'media_buttons' => false,
				'wpautop'		=> true,
				'editor_class'  => 'experience-field',
				'quicktags'		=> false,
				'teeny'			=> true,
			) ); ?>
		</div>
		
		<div class="form-full">	
			<p><i class="fa fa-flag fa-fw"></i>Why are you interested in joining Entropy Rising? What do you have to offer Entropy Rising as a player and as a community member? Are you interested in contributing to the Tamriel Foundry community? Is there anything else you would like to share about yourself as a gamer or as a person?</p>	
			<?php if( isset( $youofferError ) && $youofferError ) echo '<p class="error">' . $youofferError . '</p>'; ?>
			
			<?php // Load the TinyMCE Editor
			wp_editor( $youoffer, 'youoffer', array(
				'media_buttons' => false,
				'wpautop'		=> true,
				'editor_class'  => 'youoffer-field',
				'quicktags'		=> false,
				'teeny'			=> true,
			) ); ?>
		</div>
		
		<div class="form-full">
			<label for="mainguild"><i class="fa fa-group fa-fw"></i>Are you applying to join Entropy Rising as your primary guild?</label>
			<input type="radio" name="mainguild" value="yes" <?php checked( $mainguild , 'yes' ); ?>/><label for="mainguild">Yes</label>
			<input type="radio" name="mainguild" value="no" <?php checked( $mainguild , 'no' ); ?>/><label for="mainguild">No</label>
			<?php if( isset( $mainguildError ) && $mainguildError ) echo '<p class="error">' . $mainguildError . '</p>'; ?> 
		</div>
		
		<div class="form-flex">		
			<label for="otherguildurl"><i class="fa fa-globe fa-fw"></i>(If NO) Main guild website:</label>
			<input type="url" name="otherguildurl" id="otherguildurl" value="<?php echo $otherguildurl; ?>" size="75">
			<?php if( isset( $guildurlError ) && $guildurlError ) echo '<p class="error">' . $guildurlError . '</p>'; ?> 
		</div>
		
		<div class="form-full">
			<label for="voicechat"><i class="fa fa-microphone fa-fw"></i>Do you own a working microphone and are you comfortable using TeamSpeak3 while playing?</label>
			<input type="radio" name="voicechat" value="yes" <?php checked( $voicechat , 'yes' ); ?>/><label for="voicechat">Yes</label>
			<input type="radio" name="voicechat" value="no" <?php checked( $voicechat , 'no' ); ?>/><label for="voicechat">No</label>
			<?php if( isset( $voicechatError ) && $voicechatError ) echo '<p class="error">' . $voicechatError . '</p>'; ?> 
		</div>
		
		<div id="confirm-rules" class="form-full">
			<input type="checkbox" name="readrules" value="read"><label for="readrules">I have read the Entropy Rising <a href="http://tamrielfoundry.com/entropy-rising/charter" title="Read the charter" target="_blank">guild charter</a>, and understand what will be expected of me if my application is approved.</label></p>
			<?php if( isset( $readrulesError ) && $readrulesError ) echo '<p class="error">' . $readrulesError . '</p>'; ?> 
		</div>
		
		<div class="hidden">
			<label for="checking">If you want to submit this form, do not enter anything in this field</label>
			<input type="text" name="checking" id="checking" value=""/>
			<?php wp_nonce_field( 'er_guild_application' ); ?>
			<input type="hidden" name="submitted" id="submitted" value="true" />
		</div>
		 
		<div class="form-right">
			<button type="submit"><i class="fa fa-check"></i>Submit Application</button>
		</div>
	</fieldset>
</form>
<?php endif; ?>
