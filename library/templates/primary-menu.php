<?php 
/**
 * Apocrypha Theme Navigation Menu
 * Andrew Clayton
 * Version 2.0
 * 4-29-2014
 */
?>

<div id="menu-container">
	<ul id="top-menu">
		<li class="top first">
			<span class="top-label"><a href="<?php echo SITEURL; ?>">Home</a></span>
		</li>
		
		<li class="top">
			<span class="top-label"><i class="fa fa-angle-down"></i><a href="#">Game Info</a></span>
			<div class="dropdown">
				<div class="dropdown-content">
					
					<div class="col1">
						<h3 class="double-border">Classes</h3>
						<ul class="submenu">
							<li><a href="<?php echo SITEURL . '/dragonknight/' ?>">Dragonknight</a></li>
							<li><a href="<?php echo SITEURL . '/templar/' ?>">Templar</a></li>
							<li><a href="<?php echo SITEURL . '/sorcerer/' ?>">Sorcerer</a></li>
							<li><a href="<?php echo SITEURL . '/nightblade/' ?>">Nightblade</a></li>
						</ul>
					</div>

					<div class="col1">
						<h3 class="double-border">Guides</h3>
						<ul class="submenu">
								<li><a href="<?php echo SITEURL . '/races/' ?>">Racial Comparison</a></li>
							<li><a href="<?php echo SITEURL . '/guides/' ?>">PvE Guides</a></li>
						</ul>
					</div>

					<div class="col2">
						<h3 class="double-border">Crafting</h3>
						<ul class="submenu">
							<li><a href="<?php echo SITEURL . '/crafting/alchemy' ?>">Alchemy</a></li>
							<li><a href="<?php echo SITEURL . '/crafting/blacksmithing' ?>">Blacksmithing</a></li>
							<li><a href="<?php echo SITEURL . '/crafting/clothier' ?>">Clothier</a></li>
						</ul>
						<ul class="submenu">
							<li><a href="<?php echo SITEURL . '/crafting/enchanting' ?>">Enchanting</a></li>
							<li><a href="<?php echo SITEURL . '/crafting/provisioning' ?>">Provisioning</a></li>
							<li><a href="<?php echo SITEURL . '/crafting/woodworking' ?>">Woodworking</a></li>
						</ul>
					</div>

				</div>
			</div>	
		</li>
		
		<li class="top">
			<span class="top-label"><i class="fa fa-angle-down"></i><a href="#">Community</a></span>
			<div class="dropdown">
				<div class="dropdown-content">

					<div class="col1">
						<h3 class="double-border">Directories</h3>
						<ul class="submenu">
							<li><a href="<?php echo SITEURL . '/activity/' ?>">Activity Feed</a></li>
							<li><a href="<?php echo SITEURL . '/members/' ?>">Members</a></li>
							<li><a href="<?php echo SITEURL . '/groups/' ?>">Guild Listing</a></li>
							<li><a href="<?php echo SITEURL . '/advsearch/' ?>">Advanced Search</a></li>
						</ul>
					</div>

					<div class="col1">
						<h3 class="double-border">Foundry Team</h3>
						<ul class="submenu">
							<li><a href="<?php echo SITEURL . '/about-us/' ?>">About Tamriel Foundry</a></li>
							<li><a href="<?php echo SITEURL . '/contact-us/' ?>">Contact Us</a></li>
							<li><a href="<?php echo SITEURL . '/entropy-rising/' ?>">Entropy Rising</a></li>
							<li><a href="http://www.guildlaunch.com/" target="_blank">Guild Launch</a></li>
						</ul>
					</div>

					<div class="col1">
						<h3 class="double-border">Your Account</h3>
						<ul class="submenu">	
						<?php if ( is_user_logged_in() ) : ?>					
							<li><a href="<?php echo bp_loggedin_user_link(); ?>">Your Profile</a></li>
							<li><a href="<?php echo bp_loggedin_user_link() . 'profile/edit/' ?>">Edit Profile</a></li>
							<li><a href="<?php echo bp_loggedin_user_link() . 'messages/' ?>">Private Messages</a></li>
							<li><a href="<?php echo bp_loggedin_user_link() . 'settings/' ?>">Account Settings</a></li>
						<?php else : ?>
							<li><a href="<?php echo trailingslashit(SITEURL) . 'register'; ?>">Register Account</a></li>
						<?php endif; ?>
						</ul>
					</div>
				</div>
			</div>	
		</li>
		
		<li class="top single">
			<span class="top-label"><i class="fa fa-angle-down"></i><a href="#">Resources</a></span>
			<div class="dropdown">
				<div class="dropdown-content">
					<div class="col1">
						<ul class="submenu noheader">
							<li><a href="<?php echo SITEURL . '/map/' ?>">Interactive Map</a></li>
							<li><a href="<?php echo SITEURL . '/development-faq/' ?>">Development Timeline</a></li>
							<li><a href="<?php echo SITEURL . '/ftc/' ?>">Foundry Tactical Combat</a></li>
						</ul>
					</div>

				</div>
			</div>	
		</li>
		
		<li class="top single">
			<span class="top-label"><i class="fa fa-angle-down"></i><a href="<?php echo get_post_type_archive_link( 'forum' ) ?>">Forums</a></span>
			<div class="dropdown">
				<div class="dropdown-content">
					<div class="col1">
						<ul class="submenu noheader">
							<li><a href="<?php echo get_post_type_archive_link( 'forum' ) ?>">Forums Home</a></li>
							<li><a href="<?php echo get_post_type_archive_link( 'topic' ) ?>">Recent Topics</a></li>
						</ul>
					</div>
				</div>
			</div>	
		</li>		
	</ul>
</div><!-- #menu-container -->
