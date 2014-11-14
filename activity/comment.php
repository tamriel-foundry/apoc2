<?php 
/**
 * Apocrypha Theme Single Activity Comment
 * Andrew Clayton
 * Version 2.0
 * 10-11-2014
 */
?>

<li id="acomment-<?php bp_activity_comment_id(); ?>" class="activity-comment">
	<div class="acomment-avatar">
		<?php echo apoc_get_avatar( array( 'user_id' => bp_get_activity_comment_user_id() , 'size' => 50 ) ); ?>
	</div>

	<div class="acomment-body">
		<header class="activity-header">
			<?php printf( __( '<a href="%1$s">%2$s</a> replied <a href="%3$s" class="activity-time-since"><span class="time-since">%4$s</span></a>', 'buddypress' ), bp_get_activity_comment_user_link(), bp_get_activity_comment_name(), bp_get_activity_thread_permalink(), bp_get_activity_comment_date_recorded() ); ?>
			
			<?php if ( bp_activity_user_can_delete() ) : ?>		
			<div class="actions">
				<a href="<?php bp_activity_comment_delete_link(); ?>" class="delete acomment-delete confirm bp-secondary-action button button-dark" rel="nofollow"><i class="fa fa-trash"></i>Delete</a>
			</div>
			<?php endif; ?>
		</header>
	
		<div class="acomment-content">
			<?php bp_activity_comment_content(); ?>
		</div>
	</div>	
</li>
