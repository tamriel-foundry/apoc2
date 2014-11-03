<?php 
/**
 * Apocrypha Theme Comments Template
 * Andrew Clayton
 * Version 2.0
 * 9-13-2014
 */


// Get some information
global $comment;
$count 	= apoc()->counts['comment'];
$user	= new Apoc_User( $comment->user_id , 'reply' );

// Display the comment ?>
<li id="comment-<?php echo $comment->comment_ID; ?>" class="reply">
	
	<header class="reply-header">
		<time class="reply-time" datetime="<?php echo date( 'Y-m-d\TH:i' , strtotime($comment->comment_date) ); ?>"><?php echo bp_core_time_since( $comment->comment_date_gmt , current_time( 'timestamp' , true ) )?></time>
		<?php // apoc_report_post_button( 'comment' ); ?>
		<div class="reply-admin-links">
			<?php apoc_comment_admin_links(); ?>
			<span><a class="reply-permalink" href="<?php echo get_comment_link( $comment->comment_ID ); ?>" title="Link directly to this comment">#<?php echo $count; ?></a></span>
		</div>
	</header>
	
	<section class="reply-body">
		<div class="reply-author">
			<?php echo $user->block; ?>
		</div>
		
		<div class="reply-content">
			<?php if ( '0' == $comment->comment_approved ) : ?>
				<p class="warning comment-moderation">Your comment is awaiting moderation.</p>
			<?php endif; ?>
			<?php comment_text( $comment->comment_ID ); ?>
		</div>
		
		<?php $user->signature(); ?>
	</section>
</li>