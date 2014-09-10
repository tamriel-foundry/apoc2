<?php 
/**
 * Apocrypha Theme Forum Single Reply
 * Andrew Clayton
 * Version 2.0
 * 7-22-2014
 */
 
// Get the reply author object
$author = new Apoc_User( bbp_get_reply_author_id() , 'reply' );
?>

<li id="post-<?php bbp_reply_id(); ?>" class="reply">
	
	<header class="reply-header">
		<time class="reply-time" datetime="<?php echo get_the_time( 'Y-m-d\TH:i' ); ?>"><?php echo bp_core_time_since( strtotime( get_the_time( 'c' ) ) , current_time( 'timestamp' ) ); ?></time>
		<div class="reply-admin-links">
			<?php bbp_reply_admin_links(); ?>
			<a class="reply-permalink" href="<?php bbp_reply_url(); ?>">#<?php echo bbp_get_reply_position(); ?></a>
		</div>
	</header>
	
	<section class="reply-body">
		<div class="reply-author">
			<?php echo $author->block; ?>
		</div>
		
		<div class="reply-content">
			<?php bbp_reply_content(); ?>		
		</div>
		
	</section>
	
</li>