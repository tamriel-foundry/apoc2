<?php 
/**
 * Apocrypha Theme Single Event Template
 * Andrew Clayton
 * Version 2.0
 * 11-22-2014
 */

// Gather some data about the event
global 		$post;

// Get event meta
$capacity	= get_post_meta( $post->ID , 'event_capacity' , true );
			if ( '' == $capacity ) $capacity = '&infin;';

$req_rsvp	= get_post_meta( $post->ID , 'event_require_rsvp' , true );
$req_role	= get_post_meta( $post->ID , 'event_require_role' , true );
$rsvps		= get_post_meta( $post->ID , 'event_rsvps' , true );
			if ( empty( $rsvps ) ) $rsvps = array();

// Get date information
$time	= get_the_date( 'g:ia' );
$day	= get_the_date( 'l' );
$date	= get_the_date( 'M j' );

// Has it passed?
$is_past = ( strtotime( get_the_date( "Y-m-d\TH:i" ) ) < time() ) ? true : false;

// Attendance
$confirmed = 0;
foreach ( $rsvps as $response ) {
	if ( 'yes' == $response['rsvp'] ) $confirmed++;
}

// Current user response
$user_id = get_current_user_id();
$response = isset( $rsvps[$user_id] ) ? $rsvps[$user_id] : ''; 
switch ( $response ) {
	case "yes" :
		$rsvp = "Attending";
		break;
	case "no" :
		$rsvp = "Absent";
		break;
	case "maybe" :
		$rsvp = "Maybe";
		break;
	default :
		$rsvp = "Please RSVP";
		break;
} ?>

<li id="event-<?php the_ID(); ?>" class="event double-border">
	
	<div class="event-datetime">
		<span class="event-day"><?php echo $day; ?></span>
		<span class="event-date"><?php echo $date; ?></span>
		<span class="event-time"><?php echo $time; ?> EST</span>
	</div>

	<div class="event-meta">
		<span class="event-attendance"><?php echo $confirmed . '/' . $capacity; ?></span>
		<?php if ( !$is_past ) : ?>
			<span class="event-rsvp"><?php echo $rsvp; ?></span>
		<?php endif; ?>
	</div>

	<div class="event-content">
		<h2><a href="<?php the_permalink(); ?>" title="View Event" ><?php the_title(); ?></a></h2>
		<div class="event-description post-content"><?php the_content(); ?></div>
	</div>


</li>