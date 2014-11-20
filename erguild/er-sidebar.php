<?php 
/**
 * Entropy Rising Sidebar
 * Andrew Clayton
 * Version 2.0
 * 11-20-2014
 */
?>

<div id="primary-sidebar" class="sidebar">
	
	<div id="sidebar-welcome" class="widget"><span class="er-name">Entropy Rising</span> is a competitive progression guild, and the official arm of Tamriel Foundry within the NA-PC megaserver. We pride ourselves on unrivaled excellence in PvE, AvA, and community involvement.</div>

	<?php apoc_sidebar_streams(); ?>

	<div id="er-progression-widget" class="widget">
		<header class="widget-header"><h3 class="widget-title">PvE Progression</h3></header>
		<ol class="er-progression-list">
			<li class="progression-item aetherian">
				<span class="progression-name">Aetherian Archive</span>
				<p class="progression-status">
					<span class="complete">Normal</span>
					<span class="incomplete">Hard Mode</span>
				</p>
			</li>
			<li class="progression-item hel-ra">
				<span class="progression-name">Hel Ra Citadel</span>
				<p class="progression-status">
					<span class="complete">Normal</span>
					<span class="complete">Hard Mode</span>
				</p>
			</li>
			<li class="progression-item sanctum">
				<span class="progression-name">Sanctum Ophidia</span>
				<p class="progression-status">
					<span class="complete">Normal</span>
					<span class="incomplete">Hard Mode</span>
				</p>
			</li>
			<li class="progression-item dragonstar">
				<span class="progression-name">Dragonstar Arena</span>
				<p class="progression-status">
					<span class="complete">Normal</span>
					<span class="incomplete">Veteran</span>
				</p>
			</li>
		</ol>
	</div>

	<div id="er-events-widget" class="widget">
		<header class="widget-header"><h3 class="widget-title">Upcoming Events</h3></header>
	</div>

	<div id="er-recruitment-widget" class="widget">
		<header class="widget-header"><h3 class="widget-title">Recruitment Priorities</h3></header>
		<ul class="er-status-list">
			<?php foreach ( er_recruitment_priorities() as $class => $status ) {
			echo '<li class="er-status">' . ucfirst($class) . ': <span class="status-' . $status . '">' . ucfirst($status) . '</span></li>';
			} ?>
		</ul>
	</div>

</div>