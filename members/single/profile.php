<?php 
/**
 * Apocrypha Theme User Profile Component
 * Andrew Clayton
 * Version 2.0
 * 9-25-2014
 */

// Get the currently displayed user object
global $user;

// Get the character sheet class
$charsheet_class = 'neutral';
if ( '' != $user->race ) $charsheet_class = $user->race;
elseif ( '' != $user->faction ) $charsheet_class = $user->faction;
?>

<nav class="reply-header" id="subnav">
	<ul id="profile-tabs" class="tabs" role="navigation">
		<?php bp_get_options_nav(); ?>
	</ul>
</nav><!-- #subnav -->

<?php // Secondary components
if ( bp_is_current_action( 'change-avatar' ) ) : 
	locate_template( array( 'members/single/profile/change-avatar.php' ), true );
else : ?>

<div id="user-profile">

	<section id="user-character" class="widget">
		<header class="widget-header">
			<h3 class="widget-title">Character Sheet</h3>
		</header>
		<div id="character-sheet" class="<?php echo $charsheet_class; ?>">
			<ul>
				<li><i class="fa fa-globe fa-fw"></i><span>Server:</span><?php echo $user->servname; ?></li>
				<li><i class="fa fa-book fa-fw"></i><span>Name:</span><?php echo $user->charname; ?></li>
				<li><i class="fa fa-group fa-fw"></i><span>Guild:</span><?php echo $user->guild; ?></li>
				<li><i class="fa fa-user fa-fw"></i><span>Race:</span><?php echo ucfirst( $user->race ); ?></li>
				<li><i class="fa fa-gear fa-fw"></i><span>Class:</span><?php echo ucfirst( $user->class ); ?></li>
				<li><i class="fa fa-shield fa-fw"></i><span>Role:</span><?php echo ucfirst( $user->prefrole ); ?></li>
			</ul>
		</div>
	</section>	

	<section id="user-biography" class="post-content">
		<?php if( !empty( $user->bio ) ) :
			echo $user->bio;
		else :
			echo 'This member has not written a biography.';
		endif; ?>
	</section>

</div><!-- #user-profile -->
<?php endif; ?>