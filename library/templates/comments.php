<?php 
/**
 * Apocrypha Theme Comments Template
 * Andrew Clayton
 * Version 2.0
 * 9-19-2014
 */

// Exit if accessed directly
if ( !defined( 'ABSPATH' ) ) exit; ?>


<div id="comments">
	<header class="forum-header">
		<div class="forum-content">
			<h2><?php comments_number( sprintf( 'No responses to %1$s' , the_title( '&#8220;', '&#8221;', false ) ) ,sprintf( 'One response to %1$s' , the_title( '&#8220;', '&#8221;', false ) ), sprintf( '%1$s responses to %2$s' , '%' , the_title( '&#8220;', '&#8221;', false ) ) ); ?></h2>
		</div>
	</header>

	<?php if ( have_comments() ) : ?>
	<nav class="pagination">
		<div class="pagination-links">
			<?php paginate_comments_links( array('prev_text' => '&larr;', 'next_text' => '&rarr;') ); ?>
		</div>
	</nav>

	<ol id="comment-list" class="double-border">
		<?php wp_list_comments( apoc_comments_args() ); ?>
	</ol>

	<nav class="pagination">
		<div class="pagination-links">
			<?php paginate_comments_links( array('prev_text' => '&larr;', 'next_text' => '&rarr;') ); ?>
		</div>
	</nav>
	<?php endif; ?>

	<?php apoc_comment_form(); ?>

</div><!-- #comments -->