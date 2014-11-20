<?php 
/** 
 * Entropy Rising Guild Homepage
 * Template Name: Entropy Rising Home
 * Andrew Clayton
 * Version 2.0
 * 11-16-2014
 */
?>

<?php get_header('er'); 
global $er;
?>

	<div id="content" role="main">

		<div id="showcase-container">
			<?php get_slideshow('er-slideshow'); ?>
			
			<div id="er-home-stats" class="widget showcase-widget">
				<header class="widget-header"><h3 class="widget-title">About Entropy Rising</h3></header>
				
				<div class="instructions">
					<ul class="er-status-list">
						<li class="er-status"><i class="fa fa-flag fa-fw"></i>Alliance:<span class="status-aldmeri">Aldmeri Dominion</span></li>
						<li class="er-status"><i class="fa fa-globe fa-fw"></i>Region/Platform:<span>North America - PC</span></li>
						<li class="er-status"><i class="fa fa-gears fa-fw"></i>Playstyle:<span>Hardcore PvE/PvP</span></li>
						<li class="er-status"><i class="fa fa-users fa-fw"></i>Members:<span><?php echo $er->group->total_member_count; ?></span></li>
						<li class="er-status"><i class="fa fa-plus fa-fw"></i>Recruitment:
							<?php if ( $er->recruiting ) : ?>
								<span class="status-high">Selective</span>
							<?php else : ?>
								<span class="status-low">Closed</span>
							<?php endif; ?>
						</li>
						<li class="er-status"><i class="fa fa-list fa-fw"></i>More Info:
							<span><a href="<?php echo SITEURL . '/entropy-rising/charter/'; ?>" target="_blank" title="Entropy Rising Guild Charter">Guild Charter</a></span>
						</li>
					</ul>
				</div>
			</div>

			<?php if ( $er->recruiting ) : ?>
				<p id="er-recruitment-status" class="updated">We are currently seeking members of exceptional quality to help our guild conquer greater challenges in <em>ESO</em>. If you think you would be a good fit for Entropy Rising, please read our guild charter and visit our recruitment page for more details!</p>
			<?php else : ?>
				<p id="er-recruitment-status" class="error">We are not currently soliciting further applications, recruitment is conducted on an invite-only basis until further notice. You may still apply if you wish and we will hold your application until recruitment is reopened.</p>
			<?php endif; ?>
		</div>

		<div id="posts" class="archive">
			<?php if ( er_have_posts() ) : while ( have_posts() ) : the_post(); ?>
				<?php apoc_single_post(); ?>
			<?php endwhile; endif; ?>
		</div>

		<nav class="pagination">
			<div class="pagination-links">
				<?php echo paginate_links( array('prev_text' => '&larr;', 'next_text' => '&rarr;') ); ?>
			</div>
		</nav>

	</div><!-- #content -->
	<?php er_guild_sidebar(); ?>
<?php get_footer(); ?>