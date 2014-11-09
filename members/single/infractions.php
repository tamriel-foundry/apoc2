<?php 
/**
 * Apocrypha Theme Member Infractions Component
 * Andrew Clayton
 * Version 2.0
 * 9-30-2014
 */

// Get the displayed user
global $user;
$level	= $user->warnings['level'] > 0 ? $user->warnings['level'] : 0;
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

			<?php if ( bp_is_my_profile() ) : ?>
				<p class="warning">Your current warning level is <?php echo $level; ?> points.</p>
			<?php else : ?>
				<p class="warning">The current warning level for <?php bp_displayed_user_username(); ?> is <?php echo $level; ?> points.</p>
			<?php endif; ?>
			

			<?php if ( $level > 0 ) : ?>
			<ol class="infraction-list">
			<?php foreach( $user->warnings['history'] as $id => $entry ) : 
				$points = ( $entry['points'] > 1 ) ? $entry['points'] . ' Points' : $entry['points'] . ' Point'; ?>
				<li id="infraction-<?php echo $id; ?>" class="infraction-entry">
					<header>
						<span class="infraction-meta activity"><?php echo $entry['date'] . ' - ' . $points; ?></span>
						<?php if ( current_user_can( 'moderate' ) ) : ?>
						<span class="infraction-mod">Issued By <?php echo $entry['moderator']; ?></span>
						<?php endif; ?>
					</header>
					
					<div class="infraction-content">
						<?php echo $entry['reason']; ?>
						<?php if ( current_user_can( 'moderate' ) ) : ?>
							<a class="clear-infraction button" href="<?php echo bp_core_get_user_domain( $user->id ) . 'infractions?id=' . $id . '&amp;_wpnonce=' . wp_create_nonce( 'clear-single-infraction' ); ?>" title="Delete Infraction"><i class="fa fa-trash"></i>Delete</a>
						<?php endif; ?>
					</div>	
				</li>
			<?php endforeach; ?>	
			</ol>
			<?php endif; ?>
		</div>

	</div><!-- #content -->
<?php get_footer(); ?>