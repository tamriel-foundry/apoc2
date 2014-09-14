<?php 
/**
 * Apocrypha Theme Footer Template
 * Andrew Clayton
 * Version 2.0
 * 4-29-2014
 */
?>
	
	</div><!-- #main-container -->

<!-- Begin Footer -->
	<div id="footer-container">
		
		<div id="footer-divider">
			<a id="footer-scroll-top" class="scroll-top" href="#site-header"><i class="fa fa-long-arrow-up"></i></a>
		</div><!-- #footer-divider -->	
	
		<nav id="footer-navigation">
		
		
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