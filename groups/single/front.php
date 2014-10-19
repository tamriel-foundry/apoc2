<?php 
/**
 * Apocrypha Theme Group Profile Component
 * Andrew Clayton
 * Version 2.0
 * 10-19-2014
 */
?>

<nav class="reply-header" id="subnav">
	<ul id="profile-tabs" class="tabs" role="navigation">
		<li class="current"><a href="#group-description" title="Group Description">Description</a></li>
	</ul>
</nav><!-- #subnav -->

<div id="user-profile">
	<section id="group-description" class="post-content">
		<?php bp_group_description(); ?>
	</section>
</div>