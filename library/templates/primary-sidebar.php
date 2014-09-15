<?php 
/**
 * Apocrypha Theme Primary Sidebar
 * Andrew Clayton
 * Version 2.0
 * 5-4-2014
 */
?>

<div id="primary-sidebar" class="sidebar">
	
	<?php apoc_sidebar_welcome(); ?>
	
	<?php apoc_sidebar_social(); ?>
	
	<?php //apoc_sidebar_streams(); ?>
	
	<?php apoc_sidebar_members(); ?>

	<div id="sidebar-banner">
		Sidebar Ad:<br/>
		Medium Rectangle - 300x250
	</div>

	<?php apoc_donate_box(); ?>

	<?php apoc_sidebar_group(); ?>
	
	<?php apoc_sidebar_stats(); ?>
</div>