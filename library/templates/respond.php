<?php 
/**
 * Apocrypha Theme Comments Respond Template
 * Andrew Clayton
 * Version 2.0
 * 9-19-2014
 */

// Exit if accessed directly
if ( !defined( 'ABSPATH' ) ) exit; 

// Get the current user
$user_id	= get_current_user_id();

// Get the current post
global $post;
$post_id 	= $post->ID;
$status		= $post->comment_status;
?>



<div id="respond">
	<header class="forum-header">
		<div class="forum-content">
			<h2>Comment On: <?php the_title( '&#8220;', '&#8221;' ); ?></h2>
		</div>
	</header>

	<?php // User is logged in and comments are open
	if ( 0 !== $user_id & 'open' == $status ) : ?>
	<form id="commentform" name="commentform" action="<?php echo SITEURL . '/wp-comments-post.php'; ?>" method="post">
		<fieldset class="reply-form">
			<?php wp_editor( '' , 'comment' , array(
				'media_buttons' => false,
				'wpautop'		=> true,
				'editor_class'  => 'comment',
				'quicktags'		=> true,
				'teeny'			=> false,
				)
			); ?>

			<div class="hidden">
				<?php do_action( 'comment_form', $post_id ); ?>	
				<?php comment_id_fields( $post_id ); ?>
			</div>

			<div class="form-right">
				<button name="submit" type="submit" id="submit"><i class="fa fa-pencil"></i>Post Comment</button>	

			</div>
		</fieldset>
	</form>


	<?php // Comments are closed
	elseif ( 'closed' == $status ) : ?>
	<p class="warning">Sorry, comments are currently closed for this article.</p>

	<?php // User cannot reply, not logged in
	else : ?>
	<p class="warning">You are not currently logged in. You must <a class="scroll-top" href="<?php echo SITEURL . '/wp-login.php'; ?>" title="Please Log In">log in</a> before commenting.</p>
	<?php endif; ?>	

</div><!-- #respond -->