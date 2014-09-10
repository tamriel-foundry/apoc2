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
			<div class="dropdown full">
				<div class="dropdown-content">
					A full width mega-menu dropdown.
				</div>
			</div>	
		</li>
		
		<li class="top">
			<span class="top-label"><i class="fa fa-angle-down"></i><a href="#">Community</a></span>
			<div class="dropdown full">
				<div class="dropdown-content">
					A four-column wide mega-menu dropdown.
				</div>
			</div>	
		</li>
		
		<li class="top">
			<span class="top-label"><i class="fa fa-angle-down"></i><a href="#">Resources</a></span>
			<div class="dropdown full">
				<div class="dropdown-content">
					A three-column wide mega-menu dropdown.	
				</div>
			</div>	
		</li>
		
		<li class="top last">
			<span class="top-label"><i class="fa fa-angle-down"></i><a href="<?php echo get_post_type_archive_link( 'forum' ) ?>">Forums</a></span>
			<div class="dropdown single">
				<div class="dropdown-content">
					<ul class="submenu">
						<li><a href="<?php echo get_post_type_archive_link( 'forum' ) ?>">Forums Home</a></li>
						<li><a href="<?php echo get_post_type_archive_link( 'topic' ) ?>">Recent Topics</a></li>
					</ul>
				</div>
			</div>	
		</li>		
	</ul>
</div><!-- #menu-container -->
