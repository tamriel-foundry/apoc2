<?php 
/**
 * Apocrypha Theme Interactive Map Template
 * Andrew Clayton
 * Version 2.0
 * 9-19-2014
 */

// Was the form submitted?
if ( !empty($_POST) ) :

	// Validate data
	foreach( $_POST as $field ) {
		if ( '' == $field )	$error = true;
	}

	// If we are OK, process the marker
	if ( !isset( $error ) ) {
		
		// Extract and sanitize data
		extract( $_POST , EXTR_SKIP );
		$name 			= htmlspecialchars( $name , ENT_QUOTES );
		$description 	= htmlspecialchars( $description , ENT_QUOTES );
		$lat 			= round( $lat , 3 );
		$lng			= round( $lng , 3 );

		// Get the requested zone file
		$file 			= THEME_DIR . '/library/map/zones/' . $zone . '.js';
			
		// Switch the context
		switch ( $context ) {
		
			// Add a new marker to the file
			case 'new' :
			
				// Open the file, implicitly creating it in append mode
				$handle = fopen( $file , 'a' );
			
				// If the file is empty, we need to add the header
				$lines = file( $file );
				if ( 0 == count( $lines ) ) {
					$header = "locations = new Array();";
					fwrite( $handle , $header );
					$marker_id = 0;
				} else {
					$marker_id = count( $lines ) - 1;
				}
					
				// Add the marker
				$marker 	= "\r\nlocations[$marker_id]=['$name','$type','$description',$lat,$lng];";
				fwrite( $handle , $marker );
				fclose( $handle );
				break;
				
			// Edit an existing marker
			case 'edit' :
				$marker   	= "locations[$editid]=['$name','$type','$description',$lat,$lng];";
				$contents 	= file_get_contents( $file );
				$contents 	= preg_replace ( "#locations\[$editid\].*\];#" , $marker , $contents );
				file_put_contents( $file , $contents );
				break;
		
		
			// Delete a marker
			case 'delete' :
				break;
		}

		// Redirect back to the map if a merker was successfully added/edited/deleted
		//header('Location: '.$_SERVER['PHP_SELF'] . '?zone=' . $zone );
		//header("Cache-Control: no-store, no-cache, must-revalidate");  // HTTP/1.1
		//header("Cache-Control: post-check=0, pre-check=0", false);
	}	
endif;

// Get the requested zone
$zone = isset( $_GET['zone'] ) ? $_GET['zone'] : '';
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
								<option value="ucraglorn">Upper Craglorn</option>
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


				<?php // User can add markers
				if ( current_user_can( 'moderate_comments' ) )  : ?>
				<fieldset id="marker-form">
					<h2 class="double-border">Add New Marker</h2>
					<label for="lat">Latitude:</label><input type="text" name="lat" id="latFld" size="20"><br>
					<label for="lng">Longitude:</label><input type="text" name="lng" id="lngFld" size="20"><br>
					<label for="name">Name:</label><input type="text" name="name" id="nameFld" size="30"><br>

					<label for="type">Type:</label>
					<select name="type" id="typeFld">
						<option value=""></option>
						<optgroup label="Buildings">
							<option value="camp">Camp</option>
							<option value="farm">Farm</option>
							<option value="fort">Fort</option>
							<option value="lighthouse">Lighthouse</option>
							<option value="mill">Lumber Mill</option>
							<option value="mine">Mine</option>
							<option value="tent">Outpost</option>
							<option value="ruin">Ruin</option>
							<option value="tower">Tower</option>
							<option value="town">Town</option>
						</optgroup>
						
						<optgroup label="Cities">
							<option value="bank">Bank</option>
							<option value="castle">Castle</option>
							<option value="fightersguild">Fighters Guild</option>
							<option value="magesguild">Mages Guild</option>
							<option value="undaunted">Undaunted Tavern</option>
							<option value="temple">Temple</option>
							<option value="dock">Dockyard</option>
						</optgroup>

						<optgroup label="Collectibles">
							<option value="treasure">Buried Treasure</option>	
							<option value="crafting">Crafting Camp</option>	
							<option value="boss">Rare Monster</option>
							<option value="skyshard">Skyshard</option>
							<option value="mundus">Mundus Stone</option>						
							<option value="lorebook">Lorebook</option>
							<option value="landmark">Landmark</option>
						</optgroup>

						<optgroup label="Dungeons">
							<option value="barrow">Barrow</option>		
							<option value="cave">Cave</option>		
							<option value="pubdungeon">Public Dungeon</option>
							<option value="instance">Instanced Dungeon</option>						
							<option value="dwemer">Dwemer Ruin</option>
							<option value="ayleid">Ayleid Ruin</option>
							<option value="daedric">Daedric Ruin</option>
							<option value="tomb">Tomb</option>						
						</optgroup>
						
						<optgroup label="Landmarks">				
							<option value="dolmen">Dolmen</option>
							<option value="mountain">Mountain</option>
							<option value="wayshrine">Wayshrine</option>						
							<option value="tree">Tree</option>		
							<option value="battle">Battle</option>						
						</optgroup>	
						
						<optgroup label="Resources">				
							<option value="fibrous">Fibrous</option>
							<option value="ore">Ore</option>
							<option value="reagent">Reagent</option>
							<option value="runestone">Runestone</option>						
							<option value="water">Water</option>	
							<option value="wood">Wood</option>								
						</optgroup>
					</select><br>

					<textarea name="description" id="descFld" rows="5"></textarea>

					<div>
						<button type="button" id="clear" name="clear" onclick="ClearMarker()">New Marker</button>
						<button type="submit" id="submit">Submit</button>		
					</div>

					<div class="hidden">
						<input type="hidden" id="context" name="context" value="new" />
						<input type="hidden" id="editid" name="editid" value="0" />
					</div>
				</fieldset>

				<?php // User cannot add markers
				else : ?>
				<fieldset>
					<h2 class="double-border">About the Map</h2>
					<p>Welcome to the Tamriel Foundry interactive map. This map allows you to interact with the entire continent of Tamriel to view important locations and share coordinates with your friends.</p>
				</fieldset>
				<?php endif; ?>
			</form>		
		</div>
		
	</div><!-- #content -->
<?php get_footer(); ?>

<script type="text/javascript">
	jQuery(document).ready( function(){ interactiveMap( '<?php echo $zone; ?>' ); } );
</script>