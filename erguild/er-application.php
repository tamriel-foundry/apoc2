<?php 
/**
 * Apocrypha Theme Entropy Rising Guild Application Template
 * Template Name: Entropy Rising Application
 * Andrew Clayton
 * Version 1.2
 * 10-10-2014
 */
?>

<?php get_header('er'); global $er; ?>

	<div id="content" role="main">
		
		<div id="showcase-container">
			<div id="showcase" class="er-application-video">
				<header class="widget-header"><h3 class="widget-title">Recruitment Video</h3></header>
				<?php // Retrieve the recruitment video
				$autoplay = !isset($_POST['submitted']) ? 'autoplay=0' : '';
				$source = 'http://www.youtube.com/embed/yXW3E8pBw4M';
				$options = '?' . $autoplay . '&vq=hd720'; ?>
				<iframe width="640" height="360" src="//www.youtube.com/embed/yXW3E8pBw4M?rel=0" frameborder="0" allowfullscreen></iframe>
			</div><!-- #showcase --> 
			
			<div class="er-application-widgets showcase-widget">	
				<div class="widget">						
					<header class="widget-header"><h3 class="widget-title">Recruitment Status: <span class="status-open">Selective</span></h3></header>
					<div class="instructions">
						<p>Thank you for your interest in Entropy Rising. We are currently looking for exceptional individuals to join our team. Before applying, please read our charter for details regarding our structure, recruitment objectives, and member requirements.</p>		
					</div>
				</div>
				<div class="widget">				
					<header class="widget-header"><h3 class="widget-title">Current Priorities</h3></header>
					<div class="instructions">
						<ul class="er-status-list">
							<?php foreach ( er_recruitment_priorities() as $class => $status ) {
							echo '<li class="er-status">' . ucfirst($class) . ': <span class="status-' . $status . '">' . ucfirst($status) . '</span></li>';
							} ?>
						</ul>
					</div>
				</div>
			</div>
		</div><!-- #showcase-container -->
		
		<div id="er-application">
			<header class="forum-header">
				<div class="forum-content"><h2>Entropy Rising Guild Application Form</h2></div>
			</header>
		
			<?php if ( is_user_logged_in() && er_is_recruiting() ) : ?>
				<?php locate_template( array( 'erguild/er-application-form.php' ), true ); ?>

			<?php elseif ( !er_is_recruiting() ) : ?>
				<p class="error">Entropy Rising guild recruitment is CLOSED at this time. We are not planning to add any further members for the next several weeks. If you are interested in joining and have exceptional MMO experience both in <em>ESO</em> as well as past games please check back periodically for any recruitment openings that we may have. Thank you for your interest!</p>

			<?php else : ?>
				<p class="warning">You must be a registered member of Tamriel Foundry in order to apply to join Entropy Rising.</p>
			<?php endif; ?>
		</div><!-- #er-application -->

	</div><!-- #content -->
<?php get_footer(); ?>