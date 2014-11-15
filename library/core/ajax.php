<?php 
/**
 * Apocrypha Theme AJAX Library
 * Andrew Clayton
 * Version 2.0
 * 5-6-2014
 */
 
// Exit if accessed directly
if ( !defined( 'ABSPATH' ) ) exit;
 
/*---------------------------------------------
	1.0 - LOGIN
----------------------------------------------*/
add_action( 'wp_ajax_nopriv_apoc_login', 'apoc_login' );
add_action( 'wp_ajax_apoc_login', 'apoc_login' );

/**
 * Login Handler
 * @version 2.0
 */
function apoc_login() {

	// Check security token
	check_ajax_referer( 'top-login-nonce' , 'security' );
	
	// Get the credentials
	$credentials = array();
	$credentials['user_login'] 		= $_POST['username'];
	$credentials['user_password'] 	= $_POST['password'];
	$credentials['remember']		= $_POST['remember'];
	
	// Attempt to log in
	$login = wp_signon( $credentials , false );
	 if ( is_wp_error($login) ) {
		$results = array(
			'loggedin' 	=> false,
			'message' 	=> $login->get_error_message(),
		);
	} else {
		$results = array(
			'loggedin' 	=> true, 
			'message'	=> 'Login successful, redirecting!',
		);
	}
	
	// Return results
	echo json_encode( $results );
	die();
}

/*---------------------------------------------
	2.0 - NOTIFICATIONS
----------------------------------------------*/

/**
 * Remove frontend BuddyPress notifications with AJAX
 * @version 2.0
 */
add_action( 'apoc_ajax_apoc_clear_notification' , 'apoc_clear_notification' );
function apoc_clear_notification() {
	
	// Get required global objects
	global $bp;
	global $wpdb;

	// Get data
	$user_id 	= get_current_user_id();
	$id 		= $_POST['id'];
	$type		= $_POST['type'];
	
	// Clear all mentions at once
	if ( $type == "new_at_mention" ) :
		$wpdb->query( $wpdb->prepare( "DELETE FROM " . $bp->core->table_name_notifications . " WHERE user_id = %d AND component_action = %s", $user_id , $type ) );	
	
	// Delete all reply notifications for a single topic
	elseif ( $type == "bbp_new_reply" ) :
		$wpdb->query( $wpdb->prepare( "DELETE FROM " . $bp->core->table_name_notifications . " WHERE user_id = %d AND item_id = %d", $user_id , $id ) );	
	
	// Otherwise, delete the single notification	
	else :
		$wpdb->query( $wpdb->prepare( "DELETE FROM " . $bp->core->table_name_notifications . " WHERE user_id = %d AND id = %d", $user_id , $id ) );
	endif;
	
	// Send a response
	die("Notifications Cleared!");
}

/*---------------------------------------------
	3.0 - POST REPORTS
----------------------------------------------*/

/**
 * Process post reports using AJAX
 * @version 2.0
 */
add_action( 'apoc_ajax_apoc_report_post' , 'apoc_report_post' );
function apoc_report_post() {
	
	/* Get the needed data */
	$userid = get_current_user_id();
	$from	= bp_core_get_user_displayname( $userid );
	
	/* Get AJAX data */
	$type	= $_POST['type'];
	$postid = $_POST['id'];
	$number	= $_POST['num'];
	$user 	= $_POST['user'];
	$reason	= $_POST['reason'];
	
	/* Get the post URL */
	if( 'reply' == $type ) :
		$link = bbp_get_reply_url( $postid );
	elseif ( 'comment' == $type ) :
		$link = get_comment_link( $postid );
	elseif( 'message' == $type ) :
		$link 	= bp_core_get_user_domain( $user ) . 'messages/view/' . trailingslashit( $postid );
		$user	= bp_core_get_user_displayname( $user );
	endif;
	
	/* Set the email headers */
	$subject 	= "Reported Post From $from";
	$headers 	= "From: Post Report Bot <noreply@tamrielfoundry.com>\r\n";
	$headers	.= "Content-Type: text/html; charset=UTF-8";
	
	/* Construct the message */
	$body = '<h3>' . $from . ' has reported a post violating the Code of Conduct.</h3>';
	$body .= '<ul><li>Report URL: <a href="' . $link . '" title="View Post" target="_blank">' . $link . '</a></li>';
	$body .= '<li>Post Number: ' . $number . '</li>';
	$body .= '<li>User Reported: ' . $user . '</li>';
	$body .= '<li>Reason: ' . $reason . '</li></ul>';
	
	/* Send the email */
	$emailto = get_moderator_emails();
	wp_mail( $emailto , $subject , $body , $headers );
	
	echo "1";
	exit(0);
}


/*---------------------------------------------
	4.0 - PRIVATE MESSAGES
----------------------------------------------*/

/**
 * Send a private message reply to a thread via a POST request.
 * @version 2.0
 */
add_action( 'wp_ajax_apoc_private_message_reply' , 'apoc_private_message_reply' );
function apoc_private_message_reply() {
	
	// Bail if not a POST action
	if ( 'POST' !== strtoupper( $_SERVER['REQUEST_METHOD'] ) )
		return;

	// Check the nonce and register the new message
	check_ajax_referer( 'messages_send_message' );
	$result = messages_new_message( array( 'thread_id' => (int) $_REQUEST['thread_id'], 'content' => $_REQUEST['content'] ) );

	// If the new message was registered successfully
	if ( $result ) :
	$user = new Apoc_User( get_current_user_id() , 'reply' ); ?>
	<li class="reply new-message">

		<header class="reply-header">
			<time class="reply-time">Right Now</time>
		</header>	

		<section class="reply-body">	
			<div class="reply-author">
				<?php echo $user->block; ?>
			</div>
			<div class="reply-content">
				<?php echo wpautop( stripslashes( $_REQUEST['content'] ) ); ?>
			</div>
			<?php $user->signature(); ?>
		</section>				
	</li>
	
	<?php // Otherwise, process errors
	else :
		echo '<p class="error">There was a problem sending that reply. Please try again.</p>';
	endif;
	exit;
}