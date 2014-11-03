<?php 
/**
 * Apocrypha Theme Comments Template
 * Andrew Clayton
 * Version 2.0
 * 11-2-2014
 */

// Get the comment
global $comment;

// Was the form submitted?
if( isset( $_POST['submit'] ) && wp_verify_nonce( $_POST['edit_comment_nonce'] , 'edit-comment' ) ) :

	/* Register the update */
	$comment_tosave = (array) $comment;
	$comment_tosave['comment_content'] = $_POST['comment-edit'];
	wp_update_comment( $comment_tosave );
	
	/* Redirect to the new content */
	$link = get_comment_link( $comment->comment_ID );
	wp_redirect( $link , 302 );
endif;
?>

<?php get_header(); ?>
	
	<div id="content">
		<?php if ( have_posts() ) : while ( have_posts() ) : the_post(); ?>
			
		<?php apoc_breadcrumbs(); ?>
		<header class="post-header <?php apoc_post_header_class('post'); ?>">
			<h1 class="post-title"><?php the_title(); ?></h1>
			<p class="post-byline"><?php apoc_byline(); ?></p>
		</header>

		<?php endwhile; endif; ?>
	</div>

	<div id="respond" role="main">

		<form id="edit-comment-form" class="standard-form" name="edit-comment-form" method="post" action="<?php echo apoc()->url; ?>">
			<fieldset>
				<?php wp_editor( stripslashes( $comment->comment_content ) , 'comment-edit' , array(
					'media_buttons' => false,
					'wpautop'		=> true,
					'editor_class'  => 'comment-edit',
					'quicktags'		=> true,
					'teeny'			=> false,
				) ); ?>		

				<div class="form-right">
					<button type="submit" name="submit"><i class="fa fa-pencil"></i>Edit Comment</button>
				</div>

				<div class="hidden">
					<?php wp_nonce_field( 'edit-comment' , 'edit_comment_nonce' ) ?>
				</div>
			</fieldset>		
		</form>
	</div><!-- #respond -->
<?php get_footer(); ?>

