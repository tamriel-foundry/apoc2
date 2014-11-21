<?php 
/**
 * Entropy Rising Navigation Menu
 * Andrew Clayton
 * Version 2.0
 * 11-20-2014
 */

// Declare ER Home URL
$er_home = SITEURL . '/entropy-rising/';
$er_group = SITEURL . '/groups/entropy-rising/';
?>

<div id="menu-container">
	<ul id="top-menu">
		<li class="top first">
			<span class="top-label"><a href="<?php echo SITEURL; ?>">TF Home</a></span>
		</li>

		<li class="top">
			<span class="top-label"><a href="<?php echo $er_home; ?>">ER Home</a></span>
		</li>
		
		<li class="top single">
			<span class="top-label"><i class="fa fa-angle-down"></i><a href="#">Guild Info</a></span>
			<div class="dropdown">
				<div class="dropdown-content">
					<div class="col1">
						<ul class="submenu noheader">
							<li><a href="<?php echo $er_home . 'charter'; ?>">Guild Charter</a></li>
							<li><a href="<?php echo $er_group . 'members'; ?>">Roster</a></li>
							<li><a href="<?php echo $er_home . 'application'; ?>">Application Form</a></li>
						</ul>
					</div>
				</div>
			</div>		
		</li>
		
		<li class="top single">
			<span class="top-label"><i class="fa fa-angle-down"></i><a href="#">Private</a></span>
			<div class="dropdown">
				<div class="dropdown-content">
					<div class="col1">
						<ul class="submenu noheader">
							<li><a href="<?php echo $er_group . 'forum'; ?>">Guild Forum</a></li>
							<li><a href="<?php echo $er_group . 'activity'; ?>">Guild Activity</a></li>
							<li><a href="#">Calendar</a></li>
						</ul>
					</div>
				</div>
			</div>	
		</li>		
	</ul>
</div><!-- #menu-container -->
