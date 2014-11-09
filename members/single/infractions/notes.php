<?php 
/**
 * Apocrypha Theme Moderator Notes Component
 * Andrew Clayton
 * Version 2.0
 * 11-09-2014
 */

// Get the currently displayed user
global $user;
$level	= $user->warnings['level'] > 0 ? $user->warnings['level'] : 0;
$count	= intval($user->mod_notes['count']);

// Process the form
if ( isset( $_POST['moderator_note_nonce'] ) && wp_verify_nonce( $_POST['moderator_note_nonce'] , 'moderator-note' ) )  {

	// Get some data
	$notes 			= ( $count > 0 ) ? $user->mod_notes['history'] : array();
	$moderator		= bp_core_get_user_displayname( get_current_user_id() );
	$date 			= date('M j, Y', current_time( 'timestamp' ) );
	
	// Validate form contents
	if ( empty( $_POST['note-content'] ) )
		$error = 'You have to actually write something, dummy!';
	else
		$note = wpautop( $_POST['note-content'] );
		
	// Add the note to the array
	if ( !$error ) {
		
		$notes[] = array(
			'note' 	=> trim( $note ),
			'moderator' => $moderator,
			'date' 		=> $date,
			);
			
		// Update the usermeta
		update_user_meta( $user->id , 'moderator_notes' , $notes );	
		
		// Redirect
		wp_redirect( bp_get_displayed_user_link() . 'infractions/notes' , 302 );
	}
}
?>


<?php get_header(); ?>
	
	<div id="content" role="main">
		<?php apoc_breadcrumbs(); ?>

		<?php locate_template( array( 'members/single/member-header.php' ), true ); ?>	

		<nav class="reply-header" id="subnav">
			<ul id="profile-tabs" class="tabs" role="navigation">
				<?php bp_get_options_nav(); ?>
			</ul>
		</nav><!-- #subnav -->

		<div id="user-profile">

			<div class="instructions">
				<h3>Infraction and Warning Policy.</h3>
				<ul>
					<li>1 point - Warning</li>
					<li>2 points - Warning</li>
					<li>3 points - Warning</li>
					<li>4 points - Temporary Suspension</li>
					<li>5 points - Permanent Ban</li>
				</ul>
				<p>Points will not automatically depreciate over time, and will only be removed under special circumstances at the discretion of site moderators. If you wish to appeal a warning that you have received, you may contact one of the site moderators with your request. Be aware that frivolous appeals can be met with further disciplinary action. Please be kind and respectful to the staff of Tamriel Foundry which works diligently to enhance and protect this community.</p>
			</div>

			<p class="warning">The current warning level for <?php bp_displayed_user_username(); ?> is <?php echo $level; ?> points.</p>
			
			<?php if ( $count > 0 ) : ?>
			<ol class="infraction-list">
				<?php foreach( $user->mod_notes['history'] as $id => $entry ) : ?>
				<li id="modnote-<?php echo $id; ?>" class="infraction-entry">
					<header>
						<span class="infraction-meta activity"><?php echo $entry['date']; ?></span>
						<span class="infraction-mod">Issued By <?php echo $entry['moderator']; ?></span>
					</header>			
					<div class="infraction-content">
						<?php echo $entry['note']; ?>
						<a class="clear-infraction button" href="<?php echo bp_core_get_user_domain( $user->id ) . 'infractions?id=' . $id . '&amp;_wpnonce=' . wp_create_nonce( 'clear-single-infraction' ); ?>" title="Delete Infraction"><i class="fa fa-trash"></i>Delete</a>
					</div>	
				</li>
			<?php endforeach; ?>	
			</ol>
			<?php endif; ?>

			<form action="<?php echo apoc()->url; ?>" name="moderator-note-form" id="moderator-note-form" class="standard-form" method="post">
				<?php if ( $error ) : ?><p class="error"><?php echo $error; ?></p><?php endif; ?>
				<fieldset>
					<div class="form-full">
						<textarea name="note-content" rows="5"></textarea>
					</div>
					
					<div class="form-right">
						<button type="submit" name="issuewarning" class="submit button"><fa class="fa fa-pencil"></i>Add Note</button>
					</div>				
					
					<div class="hidden">
						<?php wp_nonce_field( 'moderator-note' , 'moderator_note_nonce' ) ?>
						<input name="action" type="hidden" id="action" value="issue-mod-note" />
					</div>
				</fildset>		
			</form>		
		</div>

	</div><!-- #content -->
<?php get_footer(); ?>