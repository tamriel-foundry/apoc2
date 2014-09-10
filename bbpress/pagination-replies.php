<?php 
/**
 * Apocrypha Theme Topic Pagination
 * Andrew Clayton
 * Version 2.0
 * 7-22-2014
 */
?>

<nav class="pagination" data-type="replies" data-id="<?php bbp_topic_id(); ?>">
	<div class="pagination-count">
		<?php bbp_topic_pagination_count(); ?>
	</div>
	<div class="pagination-links">
		<?php bbp_topic_pagination_links(); ?>
	</div>
</nav>