<?php 
/**
 * Apocrypha Theme Footer Template
 * Andrew Clayton
 * Version 2.0
 * 11-09-2014
 */
?>
	
	</div><!-- #main-container -->

<!-- Begin Footer -->
	<div id="footer-container">
		
		<div id="footer-divider">
			<a id="footer-scroll-top" class="scroll-top" href="#site-header"><i class="fa fa-long-arrow-up"></i></a>
		</div><!-- #footer-divider -->	
	
		<nav id="footer-navigation">
			<a class="footer-nav-item" href="<?php echo SITEURL . '/about-us/'; ?>" title="Learn more about Tamriel Foundry">
				<img id="footer-about" class="footer-nav-image" src="<?php echo THEME_URI; ?>/images/backgrounds/about-us.png" height="180" width="180"/>
				<h3>About Us</h3>
			</a>

			<a class="footer-nav-item" href="<?php echo SITEURL . '/activity/'; ?>" title="Browse recent Tamriel Foundry activity">
				<img id="footer-guides" class="footer-nav-image" src="<?php echo THEME_URI; ?>/images/backgrounds/guides.png" height="180" width="180"/>
				<h3>Activity</h3>
			</a>

			<a class="footer-nav-item" href="<?php echo SITEURL . '/forums/'; ?>" title="Browse the forums">
				<img id="footer-forums" class="footer-nav-image" src="<?php echo THEME_URI; ?>/images/backgrounds/forums.png" height="180" width="180"/>
				<h3>Forums</h3>
			</a>

			<a class="footer-nav-item" href="<?php echo SITEURL . '/groups/' ?>" title="Browse groups and guilds">
				<img id="footer-guilds" class="footer-nav-image" src="<?php echo THEME_URI; ?>/images/backgrounds/guilds.png" height="180" width="180"/>
				<h3>Guilds</h3>
			</a>	
		</nav><!-- #footer-navigation -->	
		
		<footer id="site-footer">
			<p>Copyright &copy; <?php echo date( 'F, Y' ); ?> <a href="<?php echo SITEURL; ?>" title="<?php echo SITENAME; ?>"><?php echo SITENAME; ?></a>. Questions, comments, or concerns? <a href="<?php echo SITEURL . '/contact/'; ?>" title="Contact Us">Contact Us</a>.</p>
			<p><?php echo SITENAME; ?> was created using content and materials from The Elder Scrolls Online &copy; ZeniMax Online Studios, LLC or its licensors.</p>
			<p><?php echo SITENAME; ?> is a proud partner of <a href="http://www.guildlaunch.com/" title="Visit Guild Launch" target="_blank">Guild Launch</a>, offering full featured guild hosting services for serious gamers.</p>
		</footer>	
			
	</div><!-- #footer-container -->
	<?php wp_footer(); ?>
<!-- End Footer -->
	
</body>
</html>

<!-- 
	<?php echo get_num_queries(); ?> queries in <?php timer_stop(1); ?> seconds.
	<?php echo round ( memory_get_peak_usage() / 1048576 , 2 ) . 'megabytes used.'; ?> 
--->