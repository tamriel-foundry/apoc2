<?php 
/**
 * Apocrypha Theme Admin Bar
 * Andrew Clayton
 * Version 2.0
 * 4-30-2014
 */
 
// Get current user information
$user		= apoc()->user;
$user_id	= $user->ID;
$logged_in	= ( $user_id > 0 );

// Get current url
$url		= apoc()->url;

// Get current search information
$search		= apoc()->search;
?>

<div id="top-login" class="<?php echo $logged_in ? 'logged-in' : 'logged-out'; ?>">

	<form name="top-login-form" id="top-login-form" action="<?php echo SITEURL . '/wp-login.php'; ?>" method="post">			
		
		<?php if ( $logged_in ) : ?>
		<fieldset>	
			<?php echo apoc_get_avatar( array( 'user_id' => $user_id , 'size' => 24 , 'link' => true ) ); ?>
			<span id="user-welcome">Welcome back, <?php echo $user->display_name; ?>.</span>
			<a id="login-logout" class="admin-bar-button button" href="<?php echo wp_logout_url( $url ); ?>" title="Log out of this account."><i class="fa fa-lock"></i>Log Out</a>
		</fieldset>

		<?php else : ?>
		<fieldset>	
			<input type="text" name="log" id="username" value="" placeholder="Username" size="20" tabindex="1">
			<label for="log"><i class="fa fa-user"></i></label>

			<input type="password" name="pwd" id="password" value="" placeholder="Password" size="20" tabindex="1">
			<label for="pwd"><i class="fa fa-key"></i></label>

			<input type="checkbox" name="rememberme" id="rememberme" value="forever" tabindex="1">
			<label for="rememberme">Save</label>
			
			<input type="hidden" name="redirect_to" id="login-redirect" value="<?php echo $url; ?>">
			<?php wp_nonce_field( 'top-login-nonce', 'security' ); ?>			
			<button type="submit" name="wp-submit" id="login-submit" class="admin-bar-login-link" tabindex="1"><i class="fa fa-lock"></i>Log In</button>
			
			<a id="register" class="admin-bar-button button" href="<?php echo trailingslashit(SITEURL) . 'register'; ?>" title="Register a new user account!"><i class="fa fa-user"></i>Register</a>
			<a id="lostpass" class="admin-bar-button button" href="<?php echo wp_lostpassword_url(); ?>" title="Lost your password?"><i class="fa fa-question"></i>Lost Password</a>			
		</fieldset>
		<?php endif; ?>
		
		<div id="top-login-message"></div>
	</form>
	
	<?php if ( $logged_in ) apoc_notifications(); ?>
	
	<form name="top-search-form" id="top-search-form" "<?php echo SITEURL . '/advsearch/'; ?>" method="post">	
		<fieldset>
			<input type="text" name="s" id="search" value="<?php echo $search; ?>" placeholder="Search Articles" />
			<label for="search"><i class="fa fa-search"></i></label>
			<input type="hidden" name="type" value="posts" />
		</fieldset>		
	</form>

</div><!-- #top-login -->

