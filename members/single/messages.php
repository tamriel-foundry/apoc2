<?php 
/**
 * Apocrypha Theme Profile Messages Component
 * Andrew Clayton
 * Version 2.0
 * 11-15-2014
 */

// Get the current search value
$search_value         = !empty( $_REQUEST['s'] ) ? stripslashes( $_REQUEST['s'] ) : "";
?>

<nav class="reply-header" id="subnav">
	<ul id="profile-tabs" class="tabs" role="navigation">
		<?php bp_get_options_nav(); ?>
	</ul>
	<?php if ( bp_is_messages_inbox() || bp_is_messages_sentbox() ) : ?>
	<div class="message-search search" role="search">
		<form action="" method="get" id="search-message-form">
			<input type="text" name="s" id="messages_search" placeholder="Search Messages" value="<?php echo esc_html( $search_value ); ?>"/>
		</form>
	</div>
	<?php endif; ?>
</nav><!-- #subnav -->

<div id="private-messages" role="main">
	
	<?php // Compose new message
	if ( bp_is_current_action( 'compose' ) ) :
		locate_template( array( 'members/single/messages/compose.php' ), true );
	
	// View single message
	elseif ( bp_is_current_action( 'view' ) ) :
		locate_template( array( 'members/single/messages/single.php' ), true );
	
	// View sitewide notices
	elseif ( bp_is_current_action( 'notices' ) ) :
		locate_template( array( 'members/single/messages/notices-loop.php' ), true );
	
	// View message inbox or sentbox
	else :
		locate_template( array( 'members/single/messages/messages-loop.php' ), true ); ?>
	<?php endif; ?>

</div><!-- #private-messages -->