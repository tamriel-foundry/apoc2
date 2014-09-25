<?php 
/**
 * Apocrypha Theme User Profile Template
 * Andrew Clayton
 * Version 2.0
 * 9-24-2014
 */
?>

<?php get_header(); ?>
	
	<div id="content" role="main">
		<?php apoc_breadcrumbs(); ?>

		<?php locate_template( array( 'members/single/member-header.php' ), true ); ?>	

	</div><!-- #content -->
<?php get_footer(); ?>