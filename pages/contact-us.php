<?php 
/**
 * Apocrypha Theme Contact Us Form
 * Template Name: Contact Form
 * Andrew Clayton
 * Version 2.0
 * 10-25-2014
 */

// Get the current user
$user = wp_get_current_user();
$error = "";
$email_sent = false;

// Process the submitted form
if ( isset( $_POST['submitted'] ) ) {

	// Validate name
	if( empty( $_POST['contact-name'] ) )
		$error = "Please enter your name!";

	// Validate email
	elseif( empty( $_POST['contact-email'] ) )
		$error = "Please enter your email address!";
	elseif ( filter_var( trim( $_POST['contact-email'] ) , FILTER_VALIDATE_EMAIL ) === false )
		$error = "Please enter a valid email address!";

	// Validate comments
	elseif( empty( $_POST['contact-comments'] ) )
		$error = "Please include a message!";

	// Send the email
	if ( !$error ) {

		// Get the data
		$name 		= trim( $_POST['contact-name'] );
		$email 		= trim( $_POST['contact-email'] );
		$comments 	= stripslashes( trim( $_POST['contact-comments'] ) );
		$copy		= isset( $_POST['copy'] ) ? true : false;
		
		// Format the email
		$emailto	= 'admin@tamrielfoundry.com';
		$subject 	= 'Contact Form Submission from ' . $name;
		$body 		= "<p>Name: $name</p>";
		$body		.= "<p>Email: $email</p>";
		$body		.= "<p>Comments:</p>";
		$body		.= $comments;
		$headers[] 	= "From: $name <$email>\r\n";
		$headers[] 	= "Content-Type: text/html; charset=UTF-8";	
		if( true == $copy ) 
			$emailto = $emailto . ',' . $email;
		
		// Send the email
		wp_mail($emailto, $subject, $body, $headers);
		$email_sent = true;
	}
}

?>

<?php get_header(); ?>
	<div id="content" role="main">
		<?php if ( have_posts() ) : while ( have_posts() ) : the_post(); ?>
		
		<?php apoc_breadcrumbs(); ?>
		
		<article id="post-<?php the_ID(); ?>" class="post">
			<header class="post-header <?php apoc_post_header_class('post'); ?>">
				<h1 class="post-title"><?php the_title(); ?></h1>
				<p class="post-byline"><?php apoc_byline(); ?></p>
			</header>

			<section class="post-content">
				<?php the_content(); ?>
			</section>	
		</article>

		<?php // If the form was submitted succesfully
		if ( $email_sent ) : ?>
			<p class="updated">Email succesfully sent, thank you for sharing your thoughts. We'll try to get back in touch with you soon!</p>

		<?php // Otherwise show the form
		else : ?>
		<form action="<?php the_permalink(); ?>" id="contact-form" method="post">

		<?php // Was there an error in the submission?
		if ( $error ) : ?>
			<p id="contact-error" class="error"><?php echo $error; ?></p>
		<?php endif; ?>
		
			<?php // Greet known users
			if ( $user->ID > 0 ) : ?>
			<blockquote>Hey there, <?php echo $user->display_name; ?>. What can we help you with?</blockquote>
			<fieldset>
				<div class="hidden">
					<input type="hidden" name="contact-name" id="name" value="<?php echo $user->display_name; ?>"/>
					<input type="hidden" name="contact-email" id="email" value="<?php echo $user->user_email; ?>"/>
				</div>
			</fieldset>

			<?php // Ask about unknown users
			else : ?>
			<fieldset>
				<div class="form-full">
					<label for="name"><i class="fa fa-user"></i>Your Name:</label>
					<input type="text" name="contact-name" id="name" value="<?php if(isset($_POST['contact-name'])) echo $_POST['contact-name'];?>" size="50"/>
					<br><label for="email"><i class="fa fa-envelope"></i>Email Address:</label>
					<input type="text" name="contact-email" id="email" value="<?php if(isset($_POST['contact-email']))  echo $_POST['contact-email'];?>" size="50"/>
				</div>
			</fieldset>
			<?php endif; ?>

			<fieldset>
				<?php // Display the comment form
				$content = isset( $_POST['contact-comments'] ) ? stripslashes($_POST['contact-comments']) : "";
				wp_editor( $content, 'contact-comments', array(
					'media_buttons' => false,
					'wpautop'		=> false,
					'editor_class'  => 'contact_form_comment',
					'quicktags'		=> false,
				) ); ?>

				<div class="form-left">
					<input type="checkbox" name="copy" id="copy" value="true">
					<label for="copy">Send a copy of this email to yourself?</label>
				</div>						
					
				<div class="form-right">
					<button type="submit" id="submit"><i class="fa fa-pencil"></i>Send Message</i></button>
				</div>

				<div class="hidden">
					<input type="hidden" name="submitted" value="true" />
					<input type="hidden" name="action" value="apoc_contact_form" />
				</div>
			</fieldset>
		</form>
		<?php endif; ?>

		<?php endwhile; endif; ?>
	</div>
	<?php apoc_primary_sidebar(); ?>
<?php get_footer(); ?>