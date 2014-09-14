<?php 
/**
 * Apocrypha Theme Forum Replies Loop
 * Andrew Clayton
 * Version 2.0
 * 7-22-2014
 */
?>

<ol id="topic-<?php bbp_topic_id();?>" class="topic replies double-border">
	<?php while ( bbp_replies() ) : bbp_the_reply(); ?>
		<?php bbp_get_template_part( 'loop', 'single-reply' ); ?>
	<?php endwhile; ?>
</ol>