<?php 
/**
 * Apocrypha Theme Calendar Template
 * Andrew Clayton
 * Version 2.0
 * 11-20-2014
 */

// Get the current user
$user_id 	= get_current_user_id();

// Get the requested calendar
$calendar 	= get_queried_object();
$term_id 	= $calendar->term_id;
$slug 		= $calendar->slug;

// Does it belong to a specific group?
$is_group	= is_group_calendar( $term_id ); 

// Maybe use a different header or sidebar
$group		= ( $calendar->slug == 'entropy-rising' ) ? "er" : ""; ?>

<?php get_header($group); ?>
	
	<div id="content" role="main">
		<?php apoc_breadcrumbs(); ?>

		<header class="post-header <?php apoc_post_header_class('page'); ?>">
			<h1 class="post-title"><?php echo $calendar->name; ?> Calendar</h1>
			<p class="post-byline"><?php echo $calendar->description; ?></p>
		</header>
		
		<?php // Upcoming events found
		if ( calendar_have_events( $slug ) ) : ?>
		<header class="forum-header">
			<div class="forum-content"><h2>Upcoming Events</h2></div>
		</header>
		<ol class="calendar">
			<?php while ( have_posts() ) : the_post(); 
				apoc_single_event(); 
			endwhile; ?>
		</ol>

		<?php // No posts found in category
		else : ?>
			<div class="warning">Sorry, no events are currently scheduled for this calendar.</div>
		<?php endif; ?>
	</div>

	<?php apoc_primary_sidebar($group); ?>
<?php get_footer(); ?>