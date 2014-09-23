<?php 
/**
 * Apocrypha Theme Members Loop
 * Andrew Clayton
 * Version 2.0
 * 9-22-2014
 */
?>


<?php  if ( bp_has_groups( bp_ajax_querystring( 'groups' ) ) ) : ?>
	<ul id="groups-list" class="directory-list" role="main">


	<?php // Loop through all members
	while ( bp_groups() ) : bp_the_group();
	$group = new Apoc_Group( bp_get_group_id() , 'directory' , 100 );	?>
		<li id="group-<?php bp_group_id(); ?>" class="group directory-entry">
			<div class="directory-member reply-author">
				<?php echo $group->block; ?>
			</div>

			<div class="directory-content">

				<header class="activity-header">	
					<p class="activity"><?php bp_group_last_active(); ?></p>
					<div class="actions"><?php do_action( 'bp_directory_groups_actions' ); ?></div>
				</header>
				
				<div class="guild-description">
					<?php bp_group_description_excerpt(); ?>
				</div>
			</div>
		</li>
	<?php endwhile; ?>
	</ul>

	<nav class="pagination">
		<div class="pagination-count" >
			<?php bp_groups_pagination_count(); ?>
		</div>
		<div class="pagination-links" >
			<?php bp_groups_pagination_links(); ?>
		</div>
	</nav>

<?php else : ?>
	<p class="warning">Sorry, no guilds were found.</p>
<?php endif; ?>