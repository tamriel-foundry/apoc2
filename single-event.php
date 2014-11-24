<?php 
/**
 * Apocrypha Theme Homepage Template
 * Andrew Clayton
 * Version 2.0
 * 11-22-2014
 */

// Get the calendar event
$event 		= new Apoc_Event();
$capacity 	= $event->capacity;
$confirmed	= $event->confirmed;
$maybe 		= $event->maybe;
$declined 	= $event->declined;
$rsvps 		= $event->rsvps;
$user_id	= get_current_user_id();

// Maybe use a different header or sidebar
$group = ( $event->calendar->slug == 'entropy-rising' ) ? "er" : ""; ?>

<?php get_header($group); ?>
	
	<div id="content" role="main">
		<?php if ( have_posts() ) : while ( have_posts() ) : the_post(); ?>
			
			<?php apoc_breadcrumbs(); ?>
			<article id="post-<?php the_ID(); ?>" class="post">
				<header class="post-header <?php apoc_post_header_class('post'); ?>">
					<h1 class="post-title"><?php the_title(); ?></h1>
					<p class="post-byline"><?php echo apoc()->description; ?></p>
				</header>
				
				<section class="post-content double-border event">
					<div class="event-datetime">
						<span class="event-day"><?php echo $event->day; ?></span>
						<span class="event-date"><?php echo $event->date; ?></span>
						<span class="event-time"><?php echo $event->time; ?> EST</span>
					</div>
					<div class="event-content">
						<?php the_content(); ?>
					</div>
				</section>	
				
				<footer class="post-footer">
					<span class="post-categories">
						<i class="fa fa-tags"></i>
						<?php echo get_the_term_list( get_the_ID() , 'calendar', 'Posted On: ', ', ', '' ); ?> 
					</span>
				</footer>
			</article>

			<div class="event-attendance">
				<?php if ( $confirmed > 0 ) : ?>
				<h2 class="calendar-header">Attending (<?php echo $confirmed; ?>)</h2>
				<ul class="respondent-list attending">
					<?php foreach ( $rsvps as $uid => $response ) :
						if ( 'yes' == $response['rsvp'] ) :						
							echo '<li class="event-respondent">' . implode( ' | ' , array( $response['link']  , $response['role'] , stripslashes( $response['comment'] ) ) ) . '</li>';
						endif;
					endforeach; ?>
				</ul>
				<?php endif; ?>
				
				<?php if ( $maybe > 0 ) : ?>
				<h2 class="calendar-header">Maybe (<?php echo $maybe; ?>)</h2>
				<ul class="respondent-list attending">
					<?php foreach ( $rsvps as $responder => $response ) :
						if ( 'maybe' == $response['rsvp'] ) :
							echo '<li class="event-respondent">' . implode( ' | ' , array( $response['link']  , $response['role'] , stripslashes( $response['comment'] ) ) ) . '</li>';
						endif;
					endforeach; ?>
				</ul>
				<?php endif; ?>
				
				<?php if ( $declined > 0 ) : ?>
				<h2 class="calendar-header">Not Attending (<?php echo $declined; ?>)</h2>
				<ul class="respondent-list attending">
					<?php foreach ( $rsvps as $responder => $response ) :
						if ( 'no' == $response['rsvp'] ) :
							echo '<li class="event-respondent">' . implode( ' | ' , array( $response['link']  , $response['role'] , stripslashes( $response['comment'] ) ) ) . '</li>';
						endif;
					endforeach; ?>
				</ul>
				<?php endif; ?>
				
				<?php if ( 0 == count( $rsvps ) ) : ?>
				<h2 class="calendar-header">No Responses Yet!</h2>
				<?php endif; ?>
			</div>

			<?php if ( !$event->is_past ) : ?>	
			<h2 class="calendar-header">Respond to this Event</h2>
			<form action="<?php echo apoc()->url; ?>" name="calendar-rsvp-form" id="calendar-rsvp-form" method="post">
				<?php do_action( 'template_notices' ); ?>

				<fieldset>
					<div class="form-left">
						<ul class="checkbox-list">
							<?php if ( $confirmed < $capacity || ( isset( $rsvps[$user_id] ) && 'yes' == $rsvps[$user_id]['rsvp'] ) ) : ?>
							<li><input type="radio" name="attendance" value="yes" <?php if ( isset( $rsvps[$user_id] ) ) checked( $rsvps[$user_id]['rsvp'] , 'yes' ); ?>/><label for="attendance">Yes</label></li>
							<li><input type="radio" name="attendance" value="no" <?php if ( isset( $rsvps[$user_id] ) ) checked( $rsvps[$user_id]['rsvp'] , 'no' ); ?>/><label for="playstyle">No</label></li>
							<li><input type="radio" name="attendance" value="maybe" <?php if ( isset( $rsvps[$user_id] ) ) checked( $rsvps[$user_id]['rsvp'] , 'maybe' ); ?>/><label for="attendance">Maybe</label></li>
							<?php else : ?>
							<li><input type="radio" name="attendance" value="maybe" <?php if ( isset( $rsvps[$user_id] ) ) checked( $rsvps[$user_id]['rsvp'] , 'maybe' ); ?>/><label for="attendance">Standby</label></li>
							<li><input type="radio" name="attendance" value="no" <?php if ( isset( $rsvps[$user_id] ) ) checked( $rsvps[$user_id]['rsvp'] , 'no' ); ?>/><label for="playstyle">No</label></li>
							<?php endif; ?>
						</ul>
					</div>

					<?php if ( $event->req_role ) : ?>
					<div class="form-right">
						<label for="rsvp-role">Preferred Role:</label>
						<select name="rsvp-role">
							<option></option>
							<option value="tank" <?php if ( isset( $rsvps[$user_id] ) ) selected( $rsvps[$user_id]['role'] , 'tank' ); ?>>Tank</option>
							<option value="healer" <?php if ( isset( $rsvps[$user_id] ) ) selected( $rsvps[$user_id]['role'] , 'healer' ); ?>>Healer</option>
							<option value="dps" <?php if ( isset( $rsvps[$user_id] ) ) selected( $rsvps[$user_id]['role'] , 'dps' ); ?>>DPS</option>
						</select>
					</div>
					<?php endif; ?>

					<div class="form-full">
						<label for="rsvp-comment">Comment:</label><br/>
						<textarea type="textarea" name="rsvp-comment" value="" rows="2" ><?php if ( isset( $rsvps[$user_id] ) ) echo stripslashes( $rsvps[$user_id]['comment'] ); ?></textarea>
					</div>		

					<div class="form-right">
						<?php wp_nonce_field( 'event-rsvp' , 'event_rsvp_nonce' ) ?>
						<button type="submit" name="submit"><i class="fa fa-calendar"></i>Respond</button>
					</div>
				</fieldset>
			</form>
			<?php endif; ?>
		<?php endwhile; endif; ?>
	</div>

	<?php apoc_primary_sidebar($group); ?>
<?php get_footer(); ?>