<?php 
/**
 * Apocrypha Theme Interactive Map Template
 * Andrew Clayton
 * Version 2.0
 * 9-19-2014
 */
?>

<?php get_header(); ?>
	
	<div id="content" role="main">
		<?php apoc_breadcrumbs(); ?>
		
		<header class="post-header <?php apoc_post_header_class('post'); ?>">
			<h1 class="post-title"><?php apoc_title(); ?></h1></h1>
			<p class="post-byline"><?php apoc_description(); ?></p>		
		</header>

		<div id="map-container">
			<div id="map-canvas"></div>

			<form method="post" id="map-controls">
				<fieldset>
					<h2 class="double-border">Select Zone</h2>
						<select name="zone" id="zone-select" class="ebonheart" onchange="get_markers()">
							<option value=""></option>
							<optgroup label="Aldmeri Dominion">
								<option value="roost">Khenarthi's Roost</option>
								<option value="auridon">Auridon</option>
								<option value="grahtwood">Grahtwood</option>
								<option value="greenshade">Greenshade</option>
								<option value="malabal">Malabal Tor</option>
								<option value="reapers">Reaper's March</option>
							</optgroup>
							<optgroup label="Daggerfall Covenant">
								<option value="stros">Stros M'Kai</option>
								<option value="betnikh">Betnikh</option>
								<option value="glenumbra">Glenumbra</option>
								<option value="stormhaven">Stormhaven</option>
								<option value="rivenspire">Rivenspire</option>
								<option value="alikr">Alik'r Desert</option>
								<option value="bangkorai">Bangkorai</option>
								<option value="lcraglorn">Lower Craglorn</option>
							</optgroup>
							<optgroup label="Ebonheart Pact">
								<option value="bleakrock">Bleakrock Isle</option>
								<option value="balfoyen">Bal Foyen</option>
								<option value="stonefalls">Stonefalls</option>
								<option value="deshaan">Deshaan</option>
								<option value="shadowfen">Shadowfen</option>
								<option value="eastmarch">Eastmarch</option>
								<option value="therift">The Rift</option>
							</optgroup>
							<optgroup label="Planes of Oblivion">
								<option value="coldharbour">Coldharbour</option>
							</optgroup>
							<optgroup label="Cyrodiil">
								<option value="cyrodiil">Cyrodiil</option>
							</optgroup>
						</select>
				</fieldset>
						
				<fieldset>
					<h2 class="double-border">Filter Markers</h2>
					<ul id="marker-filters" class="checkbox-list">
						<li><input type="checkbox" name="filters" value="locales" checked="checked" onclick="get_markers()"/><label for="playstyle">Locales</label></li>
						<li><input type="checkbox" name="filters" value="skyshard" checked="checked" onclick="get_markers()"/><label for="playstyle">Skyshards</label></li>
						<li><input type="checkbox" name="filters" value="lorebook" checked="checked" onclick="get_markers()"/><label for="playstyle">Lorebooks</label></li>
						<li><input type="checkbox" name="filters" value="boss" checked="checked" onclick="get_markers()"/><label for="playstyle">Bosses</label></li>
						<li><input type="checkbox" name="filters" value="treasure" checked="checked" onclick="get_markers()"/><label for="playstyle">Treasure</label></li>
					</ul>
				</fieldset>

				<fieldset>
					<h2 class="double-border">About the Map</h2>
					<p>Welcome to the Tamriel Foundry interactive map. This map allows you to interact with the entire continent of Tamriel to view important locations and share coordinates with your friends.</p>
				</fieldset>
			</form>		
		</div>
		
	</div><!-- #content -->
<?php get_footer(); ?>