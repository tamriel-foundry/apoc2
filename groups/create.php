<?php 
/**
 * Apocrypha Theme Group Creation Template
 * Andrew Clayton
 * Version 2.0
 * 10-02-2014
 */

// Initialize the group edit class
$group_edit = new Apoc_Group_Edit();
?>

<?php get_header(); ?>
	
	<div id="content" role="main">
		<?php apoc_breadcrumbs(); ?>

		<header id="directory-header" class="post-header <?php apoc_post_header_class( 'page' ); ?>">
			<h1 class="post-title"><?php apoc_title(); ?></h1>
			<p class="post-byline"><?php apoc_description(); ?></p>
			<a class="button" href="<?php echo SITEURL . '/' . bp_get_groups_root_slug(); ?>">Back to Guilds</a>	
		</header>

		<form action="<?php bp_group_creation_form_action(); ?>" method="post" id="create-group-form" enctype="multipart/form-data">
			
			<?php // Only show navigation to admins
			if ( current_user_can( 'delete_posts' ) ) : ?>
			<nav id="directory-nav" class="dir-list-tabs" role="navigation">
				<ul id="directory-actions" class="directory-tabs">
					<?php bp_group_creation_tabs(); ?>
				</ul>
			</nav>
			<?php endif; ?>

			<?php do_action( 'template_notices' ); ?>

			<?php // STEP 1 - Basic Group Details
			if ( bp_is_group_creation_step( 'group-details' ) ) : ?>
			<div class="instructions">
				<h3 class="double-border">Step 1 - Basic Guild Details</h3>
				<ul>
					<li>Enter the guild name, description, and basic details.</li>
					<li>Please ensure to strip unnecessary html formatting out of guild descriptions before finalizing them.</li>
					<li>Fields denoted with a star (&#9734;) are required.</li>
				</ul>
			</div>

			<fieldset>
				<div class="form-left">
					<label for="group-name"><i class="fa fa-bookmark"></i>Guild Name (&#9734;):</label>
					<input type="text" name="group-name" id="group-name" aria-required="true" value="<?php bp_new_group_name(); ?>" size="50" />
				</div>

				<div class="form-full">
					<label for="group-description"><i class="fa fa-edit"></i>Guild Description (&#9734;):</label>
					<?php wp_editor( htmlspecialchars_decode( bp_get_new_group_description() , ENT_QUOTES ), 'group-desc', array(
						'media_buttons' => false,
						'wpautop'		=> true,
						'editor_class'  => 'group-description',
						'quicktags'		=> true,
						'teeny'			=> false,
					) ); ?>	
				</div>

				<div class="form-left">
					<label for="group-website"><i class="fa fa-home fa-fw"></i>Guild Website:</label>
					<input type="url" name="group-website" id="group-website" size="50" />
				</div>

				<div class="form-right">
					<label for="group-server"><i class="fa fa-globe fa-fw"></i>Guild Platform:</label>
					<select name="group-server">
						<option value="blank"></option>
						<option value="napc">North America PC/Mac</option>
						<option value="eupc">Europe PC/Mac</option>
						<option value="xbox">Xbox One</option>
						<option value="ps4">PlayStation 4</option>
					</select>
				</div>

				<div class="form-left">
					<label for="group-type"><i class="fa fa-group fa-fw"></i>Group Type (&#9734;):</label>
					<ul class="checkbox-list">
						<li>
							<input type="radio" name="group-type" value="group">
							<label for="group-type">Group</label>
						</li>
						<li>
							<input type="radio" name="group-type" value="guild">
							<label for="group-type">Guild</label>
						</li>
					</ul>
				</div>

				<div class="form-right">
					<label for="group-faction"><i class="fa fa-flag fa-fw"></i>Faction Allegiance (&#9734;):</label>
					<select name="group-faction">
						<option value="neutral">Undeclared</option>
						<option value="aldmeri">Aldmeri Dominion</option>
						<option value="daggerfall">Daggerfall Covenant</option>
						<option value="ebonheart">Ebonheart Pact</option>
					</select>
				</div>

				<div class="form-right">
					<label for="group-style"><i class="fa fa-shield fa-fw"></i>Guild Playstyle:</label>
					<select name="group-style">
						<option value="blank"></option>
						<option value="casual">Casual</option>
						<option value="moderate">Moderate</option>
						<option value="hardcore">Hardcore</option>
					</select>
				</div>

				<div class="form-left">
					<label for="group-interests-list"><i class="fa fa-gear fa-fw"></i>Guild Interests (&#9734;):</label>
					<ul class="checkbox-list">
						<li>
							<input type="checkbox" name="group-interests[]" value="pve">
							<label for="group-interests">Player vs. Environment (PvE)</label>
						</li>
						<li>
							<input type="checkbox" name="group-interests[]" value="pvp">
							<label for="group-interests">Player vs. Player (PvP)</label>
						</li>
						<li>
							<input type="checkbox" name="group-interests[]" value="rp">
							<label for="group-interests">Roleplaying (RP)</label>
						</li>
						<li>
							<input type="checkbox" name="group-interests[]" value="crafting">
							<label for="group-interests">Crafting</label>
						</li>
					</ul>
				</div>

				<div class="hidden">
					<?php wp_nonce_field( 'groups_create_save_group-details' ); ?>
				</div>
			</fieldset>
			<?php endif; ?>


			<?php // Step 2 - Visibility Settings
			if ( bp_is_group_creation_step( 'group-settings' ) ) : ?>

				<div class="instructions">
					<h3 class="double-border">Step 2 - Guild Visibility Settings</h3>
					<ul>
						<li>Choose the desired privacy and visibility settings for this guild.</li>
						<li>Select which types of group members are allowed to invite others to join.</li>
						<li>Specify whether to set up a private guild forum.</li>
					</ul>
				</div>

				<div class="form-left">
					<label for="group-status"><strong>Choose group visibility level:</strong></label>
					<ul class="checkbox-list">
						<li>
							<input type="radio" name="group-status" value="public"/>
							<label><i class="fa fa-unlock fa-fw"></i><strong>This is a public guild.</strong></label>
							<ul>
								<li>Any Tamriel Foundry member can join this guild.</li>
								<li>This guild will be listed in the guilds directory and will appear in search results.</li>
								<li>Guild content and activity will be visible to all site members and guests.</li>
							</ul>
						</li>

						<li>				
							<input type="radio" name="group-status" value="private"/>
							<label><i class="fa fa-lock fa-fw"></i><strong>This is a private guild.</strong></label>
							<ul>
								<li>Only users who request membership and are accepted can join this guild.</li>
								<li>This guild will be listed in the guilds directory and will appear in search results.</li>
								<li>Guild content and activity will only be visible to guild members.</li>
							</ul>
						</li>
							
						<li>			
							<input type="radio" name="group-status" value="hidden"/>
							<label><i class="fa fa-eye-slash fa-fw"></i><strong>This is a hidden guild.</strong></label>
							<ul>
								<li>Only users who are invited can join this guild</li>
								<li>This guild will not be listed in the guilds directory or search results.</li>
								<li>Guild content and activity will only be visible to guild members</li>
							</ul>
						</li>
					</ul>
				</div>

				<div class="form-right">
					<label for="group-invite-status"><i class="fa fa-legal fa-fw"></i><strong>Select guild invitation permissions:</strong></label>
					<ul class="checkbox-list">
						<li><input type="radio" name="group-invite-status" value="members" /><label for="group-status">All guild members.</label></li>
						<li><input type="radio" name="group-invite-status" value="mods" /><label for="group-status">Guild leaders and officers only.</label></li>
						<li><input type="radio" name="group-invite-status" value="admins" checked="checked" /><label for="group-status">Guild leaders only.</label></li>
					</ul>
				</div>

				<div class="hidden">
					<?php wp_nonce_field( 'groups_create_save_group-settings' ); ?>
				</div>
			<?php endif; ?>


			<?php // Shared controls for every creation step (except cropping)
			if ( 'crop-image' != bp_get_avatar_admin_step() ) : ?>
			<fieldset>

				<?php // Create Button on first step
				if ( bp_is_first_group_creation_step() ) : ?>
				<div class="form-right">
					
					<?php // Admins can actually go through the create process
					if ( current_user_can( 'delete_posts' ) ) : ?>
					<button type="submit" name="save"><i class="fa fa-group"></i>Create Group and Continue</button>
				
					<?php // Users get intercepted at stage one
					else : ?>
					<button type="submit" name="save"><i class="fa fa-group"></i>Submit Group for Approval</button>
					<?php endif; ?>
				</div>
				<?php endif; ?>

				<?php // Next button for middle steps
				if ( !bp_is_last_group_creation_step() && !bp_is_first_group_creation_step() ) : ?>			
				<div class="form-right">
					<button type="submit" name="save"><i class="fa fa-forward"></i>Next Step</button>
				</div>
				<?php endif;?>

				<?php // Previous button for later steps
				if ( !bp_is_first_group_creation_step() ) : ?>
				<div class="form-left">
					<button name="previous" onclick="location.href='<?php bp_group_creation_previous_link(); ?>'"><i class="fa fa-backward"></i>Previous Step</button>
				</div>
				<?php endif; ?>

				<?php // Finish button for last step
				if ( bp_is_last_group_creation_step() ) : ?>
				<div class="form-right">
					<button type="submit" name="save"><i class="fa fa-check"></i>Finish</button>
				</div>
				<?php endif; ?>				
			</fieldset>				
			<?php endif; ?>
				
			<div class="hidden">
				<input type="hidden" name="group_id" id="group_id" value="<?php bp_new_group_id(); ?>" />
			</div>
		</form>

	</div><!-- #content -->
<?php get_footer(); ?>