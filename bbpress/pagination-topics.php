<?php 
/**
 * Apocrypha Theme Forum Pagination
 * Andrew Clayton
 * Version 2.0
 * 7-22-2014
 */
?>

<nav class="pagination" data-type="topics" data-id="<?php bbp_forum_id(); ?>">
	<div class="pagination-count">
		<?php bbp_forum_pagination_count(); ?>
	</div>
	<div class="pagination-links">
		<?php bbp_forum_pagination_links(); ?>
	</div>
</nav>