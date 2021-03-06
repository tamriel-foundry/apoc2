<?php 
/**
 * Apocrypha Theme Group Forums
 * Andrew Clayton
 * Version 2.0
 * 10-18-2014
 */

// Load the requested group
if ( bp_has_groups() ) : while ( bp_groups() ) : bp_the_group(); 

// Specific group overrides
$header = ( 'entropy-rising' == bp_get_current_group_slug() ) ? 'er' : ''; ?>

<?php get_header($header); ?>
	
	<div id="content" role="main">
		<?php apoc_breadcrumbs(); ?>

		<?php do_action( 'template_notices' ); ?>
		<?php do_action( 'bp_template_content' ); ?>

	</div><!-- #content -->
<?php get_footer(); ?>
<?php endwhile; endif; ?>