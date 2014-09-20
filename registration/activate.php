<?php 
/**
 * Apocrypha Theme Activation Template
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

		<form id="activation-form" action="<?php echo apoc()->url; ?>" method="get" class="standard-form">

			<?php do_action( 'template_notices' ); ?>

			<?php // Account succesfully activated
			if ( bp_account_was_activated() ) : ?>
			<p class="update">Your account was activated successfully! Please log in with the username and password you provided when you signed up. Welcome to the Tamriel Foundry community!</p>
			<div class="instructions">
				<h3 class="double-border bottom">Registration Completed</h3>
				<p>Thank you for completing your user registration at Tamriel Foundry. We look forward to welcoming you to our community! Here are some helpful links to get you started:</p>
				<ul>
					<li><a href="http://tamrielfoundry.com/topic/welcome-to-the-tamriel-foundry-forums/" title="Read welcome thread!" target="_blank">Welcome to the Tamriel Foundry Forums</a></li>
					<li><a href="http://tamrielfoundry.com/topic/introductions-ii/" title="Read welcome thread!" target="_blank">Introduce Yourself</a></li>
				</ul>
			</div>


			<?php // Account not yet activated
			else : ?>
			<div class="instructions">
				<h3 class="double-border">Activate an Account</h3>
				<p>If you already followed the activation link from your email, your account has been activated and you can now log in with the username and password you provided at registration. Otherwise you can manually activate a pending account using a valid activation key.</p>
			</div>	

			<fieldset>
				<div class="form-left">
					<label for="key"><?php _e( 'Activation Key:', 'buddypress' ); ?></label>
					<input type="text" name="key" id="key" value="" size="40" />
				</div>

				<div class="form-right">
					<button type="submit" name="submit"><i class="fa fa-check"></i>Activate Account</button>
				</div>
			</fieldset>
			<?php endif; ?>
		</form>

	</div><!-- #content -->
	<?php apoc_primary_sidebar(); ?>
<?php get_footer(); ?>	