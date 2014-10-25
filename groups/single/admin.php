<?php 
/**
 * Apocrypha Theme Group Admin Component
 * Andrew Clayton
 * Version 2.0
 * 10-24-2014
 */

// Get the displayed group
global $group;

// Load the group edit class
global $group_edit;
$can_create = $group_edit->create;
?>

<nav class="reply-header" id="subnav">
	<ul id="profile-tabs" class="tabs" role="navigation">
		<?php bp_group_admin_tabs(); ?>
	</ul>
</nav><!-- #subnav -->

<form action="<?php bp_group_admin_form_action(); ?>" name="group-settings-form" id="group-settings-form" class="group-admin-form" method="post" enctype="multipart/form-data" role="main">
	
<?php // Edit Group Details
if ( bp_is_group_admin_screen( 'edit-details' ) ) : ?>
	<div class="instructions">
		<h3 class="double-border bottom">Edit Guild Details</h3>
		<ul>
			<li>You can fine tune many aspects of your guild's presentation on Tamriel Foundry below.</li>
			<li>Provide some information about your guild's role within <em>The Elder Scrolls Online</em>.</li>
			<li>Listing information such as your playstyle, interests, region, and recruitment status will assist prospective members in evaluating the appeal of your guild.</li>
			<li>Fields denoted with a star (&#9734;) are required, however, there are many optional fields included which can help refine your guild's categorization within the community.</li>
			<li>Including a link to a permanent guild website is also encouraged. Tamriel Foundry is happy to provide tools for existing guilds to interact within the ESO community, but we are not a substitute for a full guild website and the tools which one can provide!</li>
		</ul>
	</div>

	<fieldset>
		<div class="form-left">
			<label for="group-name"><i class="fa fa-bookmark"></i>Guild Name (&#9734;):</label>
			<input type="text" name="group-name" id="group-name" aria-required="true" value="<?php bp_group_name(); ?>" size="50" />
		</div>

		<div class="form-full">
			<label for="group-description"><i class="fa fa-edit"></i>Guild Description (&#9734;):</label>
			<?php wp_editor( htmlspecialchars_decode( bp_get_group_description() , ENT_QUOTES ), 'group-desc', array(
				'media_buttons' => false,
				'wpautop'		=> true,
				'editor_class'  => 'group-description',
				'quicktags'		=> true,
				'teeny'			=> false,
			) ); ?>	
		</div>

		<div class="form-left">
			<label for="group-website"><i class="fa fa-home fa-fw"></i>Guild Website:</label>
			<input type="url" name="group-website" id="group-website" value="<?php echo $group->website; ?>" size="50" />
		</div>

		<div class="form-right">
			<label for="group-server"><i class="fa fa-globe fa-fw"></i>Guild Server  (&#9734;):</label>
			<select name="group-server">
				<option value=""></option>
				<option value="pcna" <?php selected( $group->server, 'pcna' ); ?>>North America PC/Mac</option>
				<option value="pceu" <?php selected( $group->server, 'pceu' ); ?>>Europe PC/Mac</option>
				<option value="xbox" <?php selected( $group->server, 'xbox' ); ?>>Xbox One</option>
				<option value="ps4" <?php selected( $group->server, 'ps4' ); ?>>PlayStation 4</option>
			</select>
		</div>

		<div class="form-left">
			<label for="group-interests-list"><i class="fa fa-gear fa-fw"></i>Guild Interests (&#9734;):</label>
			<ul class="checkbox-list">
				<li>
					<input type="checkbox" name="group-interests[]" value="pve" <?php checked( in_array( 'pve' , $group->interests ) , 1 ); ?>>
					<label for="group-interests">Player vs. Environment (PvE)</label>
				</li>
				<li>
					<input type="checkbox" name="group-interests[]" value="pvp" <?php checked( in_array( 'pvp' , $group->interests ) , 1 ); ?>>
					<label for="group-interests">Player vs. Player (PvP)</label>
				</li>
				<li>
					<input type="checkbox" name="group-interests[]" value="rp" <?php checked( in_array( 'rp' , $group->interests ) , 1 ); ?>>
					<label for="group-interests">Roleplaying (RP)</label>
				</li>
				<li>
					<input type="checkbox" name="group-interests[]" value="crafting" <?php checked( in_array( 'crafting' , $group->interests ) , 1 ); ?>>
					<label for="group-interests">Crafting</label>
				</li>
			</ul>
		</div>

		<div class="form-right">
			<label for="group-faction"><i class="fa fa-flag fa-fw"></i>Faction Allegiance (&#9734;):</label>
			<select name="group-faction">
				<option value=""></option>
				<option value="neutral" <?php selected( $group->alliance, 'neutral' ); ?>>Neutral</option>
				<option value="aldmeri" <?php selected( $group->alliance, 'aldmeri' ); ?>>Aldmeri Dominion</option>
				<option value="daggerfall" <?php selected( $group->alliance, 'daggerfall' ); ?>>Daggerfall Covenant</option>
				<option value="ebonheart" <?php selected( $group->alliance, 'ebonheart' ); ?>>Ebonheart Pact</option>
			</select>
		</div>

		<div class="form-right">
			<label for="group-style"><i class="fa fa-shield fa-fw"></i>Guild Playstyle:</label>
			<select name="group-style">
				<option value="blank"></option>
				<option value="casual" <?php selected( $group->style, 'casual' ); ?>>Casual</option>
				<option value="moderate" <?php selected( $group->style, 'moderate' ); ?>>Moderate</option>
				<option value="hardcore" <?php selected( $group->style, 'hardcore' ); ?>>Hardcore</option>
			</select>
		</div>

		<?php if ( $can_create ) : ?>
		<div class="form-left">
			<label for="group-type"><i class="fa fa-group fa-fw"></i>Group Type (&#9734;):</label>
			<ul class="checkbox-list">
				<li>
					<input type="radio" name="group-type" value="group" <?php checked( $group->guild , 0 ); ?>>
					<label for="group-type">Group</label>
				</li>
				<li>
					<input type="radio" name="group-type" value="guild" <?php checked( $group->guild , 1 ); ?>>
					<label for="group-type">Guild</label>
				</li>
			</ul>
		</div>
		<?php endif; ?>

		<div class="form-right">
			<button type="submit" id="save" name="save"><i class="fa fa-check"></i>Update Group</button>
		</div>

		<div class="hidden">
			<?php wp_nonce_field( 'groups_edit_group_details' ); ?>
		</div>
	</fieldset>

<?php // Manage Group Settings
elseif ( bp_is_group_admin_screen( 'group-settings' ) ) : ?>
	<div class="instructions">	
		<h3 class="double-border bottom">Guild Privacy Settings</h3>
		<ul>
			<li>You can fine tune your guild's privacy settings below.</li>
			<li>Please also specify which members of this guild should be allowed to invite others to join.</li>
			<li>Please note that the settings you choose will affect the Tamriel Foundry community's ability to interact with your guild!</li>
		</ul>
	</div>

	<fieldset>
		<div class="form-left">
			<label for="group-status"><i class="fa fa-eye"></i>Choose group visibility level:</label>
			<ul class="checkbox-list">
				<li>
					<input type="radio" name="group-status" value="public" <?php bp_group_show_status_setting( 'public' ); ?>/>
					<label><i class="fa fa-unlock fa-fw"></i>This is a public guild.</label>
					<ul>
						<li>Any Tamriel Foundry member can join this guild.</li>
						<li>This guild will be listed in the guilds directory and will appear in search results.</li>
						<li>Guild content and activity will be visible to all site members and guests.</li>
					</ul>
				</li>

				<li>				
					<input type="radio" name="group-status" value="private" <?php bp_group_show_status_setting( 'private' ); ?>/>
					<label><i class="fa fa-lock fa-fw"></i>This is a private guild.</label>
					<ul>
						<li>Only users who request membership and are accepted can join this guild.</li>
						<li>This guild will be listed in the guilds directory and will appear in search results.</li>
						<li>Guild content and activity will only be visible to guild members.</li>
					</ul>
				</li>
					
				<li>			
					<input type="radio" name="group-status" value="hidden" <?php bp_group_show_status_setting( 'hidden' ); ?>/>
					<label><i class="fa fa-eye-slash fa-fw"></i>This is a hidden guild.</label>
					<ul>
						<li>Only users who are invited can join this guild</li>
						<li>This guild will not be listed in the guilds directory or search results.</li>
						<li>Guild content and activity will only be visible to guild members</li>
					</ul>
				</li>
			</ul>
		</div>

		<div class="form-right">
			<label for="group-invite-status"><i class="fa fa-legal fa-fw"></i>Select guild invitation permissions:</label>
			<ul class="checkbox-list">
				<li><input type="radio" name="group-invite-status" value="members" <?php bp_group_show_invite_status_setting( 'members' ); ?>/><label for="group-status">All guild members.</label></li>
				<li><input type="radio" name="group-invite-status" value="mods" <?php bp_group_show_invite_status_setting( 'mods' ); ?>/><label for="group-status">Guild leaders and officers only.</label></li>
				<li><input type="radio" name="group-invite-status" value="admins" <?php bp_group_show_invite_status_setting( 'admins' ); ?> /><label for="group-status">Guild leaders only.</label></li>
			</ul>
		</div>
		
		<div class="form-right">
			<button type="submit" id="save" name="save"><i class="fa fa-check"></i>Update Group</button>
		</div>

		<div class="hidden">
			<?php wp_nonce_field( 'groups_edit_group_settings' ); ?>
		</div>
	</fieldset>

<?php // Group Avatar Settings
elseif ( bp_is_group_admin_screen( 'group-avatar' ) ) : ?>
	<div class="instructions">
		<h3 class="double-border bottom">Step 4 - Upload Guild Avatar</h2>
		<ul>
			<li>Upload an image to use as the guild avatar.</li>
			<li>The image will be shown on the main group page, and in search results.</li>
			<li>Avatars are automatically resized to 200 pixel jpegs after cropping.</li>
			<li>You may skip the avatar upload process by hitting the "Next Step" button.</li>
		</ul>
	</div>

	<?php if ( 'upload-image' == bp_get_avatar_admin_step() ) : ?>
	<fieldset>
		<div class="form-left">
			<?php bp_new_group_avatar( $args = array(
				'type' => 'full',
				'width' => 200,
				'height' => 200,
				'no_grav' => true, 
			) ); ?>
			<input type="file" name="file" id="file" />
		
			<?php if ( bp_get_group_has_avatar() ) : ?>
				<?php bp_button( array( 
					'id' => 'delete_group_avatar',
					'component' => 'groups', 
					'wrapper_id' => 'delete-group-avatar-button', 
					'link_class' => 'button', 
					'link_href' => bp_get_group_avatar_delete_link(), 
					'link_title' => __( 'Delete Avatar', 'buddypress' ), 
					'link_text' => '<i class="fa fa-remove"></i>Delete Avatar' , 
					'wrapper' => false 
				) ); ?>
			<?php endif; ?>
		</div>
		
		<div class="form-right">
			<button type="submit" name="upload" id="upload" class="button"><i class="fa fa-cloud-upload"></i>Upload New Avatar</button>
		</div>

		<div class="hidden">
			<?php wp_nonce_field( 'bp_avatar_upload' ); ?>
			<input type="hidden" name="action" id="action" value="bp_avatar_upload" />
		</div>
	</fieldset>


	<?php elseif ( 'crop-image' == bp_get_avatar_admin_step() ) : ?>
	<fieldset>
		<div class="form-left">
			<img src="<?php bp_avatar_to_crop(); ?>" id="avatar-to-crop" class="avatar" alt="<?php _e( 'Avatar to crop', 'buddypress' ); ?>" />
		</div>

		<div class="form-right">
			<div id="avatar-crop-pane">
				<img src="<?php bp_avatar_to_crop(); ?>" id="avatar-crop-preview" class="avatar" alt="<?php _e( 'Avatar preview', 'buddypress' ); ?>" />
			</div>
		</div>

		<div class="form-right">
			<button type="submit" name="avatar-crop-submit" id="avatar-crop-submit">
				<i class="fa fa-crop"></i>Crop Image
			</button>
		</div>
		
		<div class="hidden">
			<input type="hidden" name="image_src" id="image_src" value="<?php bp_avatar_to_crop_src(); ?>" />
			<input type="hidden" id="x" name="x" />
			<input type="hidden" id="y" name="y" />
			<input type="hidden" id="w" name="w" />
			<input type="hidden" id="h" name="h" />
			<?php wp_nonce_field( 'bp_avatar_cropstore' ); ?>
		</div>
	</fieldset>
	<?php endif; ?>

<?php // Manage Group Members
elseif ( bp_is_group_admin_screen( 'manage-members' ) ) : ?>
	<div class="instructions">
		<h3 class="double-border">Manage Guild Members</h3>
		<ul>
			<li>From this panel you can manage the current members of this guild.</li>
			<li>Guilds are allowed three member ranks: member, officer, and leader.</li>
		</ul>
	</div>

	<?php if ( bp_has_members( '&include='. bp_group_admin_ids() ) ) : ?>
	<header class="reply-header">Leaders</header>
	<ul id="admin-list" class="directory-list">
	<?php while ( bp_members() ) : bp_the_member();

		// Get the admin user
		$user = new Apoc_User( bp_get_member_user_id() , 'directory' , 60 ); ?>
		
		<li class="member directory-entry">
			<div class="directory-member reply-author">
				<?php echo $user->block; ?>
			</div>
			
			<div class="directory-content">
				<header class="activity-header">
					<p class="activity"><?php bp_member_last_active(); ?></p>
					<div class="actions">
						<?php if ( count( bp_group_admin_ids( false, 'array' ) ) > 1 ) : ?>
						<a class="button confirm admin-demote-to-member" href="<?php bp_group_member_demote_link( bp_get_member_user_id() ); ?>"><i class="fa fa-level-down"></i>Demote to Member</a>
						<?php endif; ?>
					</div>
				</header>

				<?php if ( $user->status['content'] ) : ?>
				<blockquote class="activity-content">
					<p><?php echo $user->status['content']; ?></p>
				</blockquote>
				<?php endif; ?>
			</div>
		</li>
	<?php endwhile; ?>
	</ul>
	<?php endif; ?>

	<?php if ( bp_has_members( '&include=' . bp_group_mod_ids() ) ) : ?>
	<header class="reply-header">Officers</header>
	<ul id="mod-list" class="directory-list">
	<?php while ( bp_members() ) : bp_the_member();

		// Get the admin user
		$user = new Apoc_User( bp_get_member_user_id() , 'directory' , 60 ); ?>
		
		<li class="member directory-entry">
			<div class="directory-member reply-author">
				<?php echo $user->block; ?>
			</div>
			
			<div class="directory-content">
				<header class="activity-header">
					<p class="activity"><?php bp_member_last_active(); ?></p>
					<div class="actions">
						<a href="<?php bp_group_member_promote_admin_link( array( 'user_id' => bp_get_member_user_id() ) ); ?>" class="button confirm mod-promote-to-admin" title="Promote to Leader"><i class="fa fa-level-up"></i>Promote to Leader</a>
						<a class="button confirm mod-demote-to-member" href="<?php bp_group_member_demote_link( bp_get_member_user_id() ); ?>"><i class="fa fa-level-down"></i>Demote to Member</a>
					</div>
				</header>

				<?php if ( $user->status['content'] ) : ?>
				<blockquote class="activity-content">
					<p><?php echo $user->status['content']; ?></p>
				</blockquote>
				<?php endif; ?>
			</div>
		</li>
	<?php endwhile; ?>
	</ul>
	<?php endif; ?>

	<?php if ( bp_group_has_members( 'per_page=15&exclude_banned=false' ) ) : ?>
	<header class="reply-header">Officers</header>
	<ul id="member-list" class="directory-list">
	<?php while ( bp_members() ) : bp_the_member();

		// Get the admin user
		$user = new Apoc_User( bp_get_member_user_id() , 'directory' , 60 ); ?>
		
		<li class="member directory-entry">
			<div class="directory-member reply-author">
				<?php echo $user->block; ?>
			</div>
			
			<div class="directory-content">
				<header class="activity-header">
					<p class="activity"><?php bp_member_last_active(); ?></p>
					<div class="actions">
						<?php if ( bp_get_group_member_is_banned() ) : ?>
							<a href="<?php bp_group_member_unban_link(); ?>" class="button confirm member-unban" title="<?php _e( 'Unban this member', 'buddypress' ); ?>"><i class="fa fa-check"></i>Unban User</a>
						<?php else : ?>
							<a href="<?php bp_group_member_ban_link(); ?>" class="button confirm member-ban" title="<?php _e( 'Kick and ban this member', 'buddypress' ); ?>"><i class="fa fa-ban"></i>Kick and Ban</a>
							<a href="<?php bp_group_member_promote_mod_link(); ?>" class="button confirm member-promote-to-mod" title="Promote to Officer"><i class="fa fa-level-up"></i>Promote to Officer</a>
							<a href="<?php bp_group_member_promote_admin_link(); ?>" class="button confirm member-promote-to-admin" title="Promote to Leader"><i class="fa fa-level-up"></i>Promote to Leader</a>
						<?php endif; ?>
						<a href="<?php bp_group_member_remove_link(); ?>" class="button confirm" title="<?php _e( 'Remove this member', 'buddypress' ); ?>"><i class="fa fa-remove"></i>Remove from Guild</a>
					</div>
				</header>

				<?php if ( $user->status['content'] ) : ?>
				<blockquote class="activity-content">
					<p><?php echo $user->status['content']; ?></p>
				</blockquote>
				<?php endif; ?>
			</div>
		</li>
	<?php endwhile; ?>
	</ul>
	<?php endif; ?>

	<?php if ( bp_group_member_needs_pagination() ) : ?>
	<nav class="pagination">
		<div class="pagination-count">
			<?php bp_group_member_pagination_count(); ?>
		</div>
		<div class="pagination-links">
			<?php bp_group_member_admin_pagination(); ?>
		</div>
	</nav>
	<?php endif; ?>



<?php // Manage Membership Requests
elseif ( bp_is_group_admin_screen( 'membership-requests' ) ) : ?>
	<div class="instructions">
		<h3 class="double-border">Pending Membership Requests</h3>
		<ul>
			<li>Review users who have requested membership to join this guild.</li>
			<li>Approved members will be added to the guild roster with the rank of "member".</li>
			<li>You can promote or remove approved members at a later time using the "Manage Members" section of the admin panel.</li>
	</div>

	<?php if ( bp_group_has_membership_requests() ) : ?>
	<fieldset>
		<ul id="members-list" class="directory-list">
		<?php while ( bp_group_membership_requests() ) : bp_group_the_membership_request();
			
			// Get the requesting user
			global $requests_template; 
			$user = new Apoc_User( $requests_template->request->user_id , 'directory' , 60 ); ?>
			
			<li class="member directory-entry">
				<div class="directory-member reply-author">
					<?php echo $user->block; ?>
				</div>
				
				<div class="directory-content">
					<header class="activity-header">	
						<p class="activity"><?php bp_group_request_time_since_requested(); ?></p>
						<div class="actions">
							<?php bp_button( array( 'id' => 'group_membership_accept', 'component' => 'groups', 'wrapper_class' => 'accept', 'link_href' => bp_get_group_request_accept_link(), 'link_title' => __( 'Accept', 'buddypress' ), 'link_class' => 'button' , 'link_text' => '<i class="fa fa-check"></i>Accept' , 'wrapper' => false ) ); ?>
							<?php bp_button( array( 'id' => 'group_membership_reject', 'component' => 'groups', 'wrapper_class' => 'reject', 'link_href' => bp_get_group_request_reject_link(), 'link_title' => __( 'Reject', 'buddypress' ), 'link_class' => 'button' , 'link_text' => '<i class="fa fa-remove"></i>Reject' , 'wrapper' => false ) ); ?>
						</div>
					</header>

					<blockquote class="activity-content">
						<?php bp_group_request_comment(); ?>
					</blockquote>
				</div>
			</li>		
			<?php endwhile ?>
		</ul>
	</fieldset>
	
	<?php else: ?>
		<p class="warning"><?php _e( 'There are no pending membership requests.', 'buddypress' ); ?></p>
	<?php endif; ?>

<?php // Delete Group Option
elseif ( bp_is_group_admin_screen( 'delete-group' ) ) : ?>
	<div class="instructions">
		<h3 class="double-border bottom">Delete Group</h3>
		<ul>
			<li>If you so choose, you may delete this group/guild and remove it from the Tamriel Foundry directory.</li>
			<li>WARNING: Deleting this group will completely remove ALL content associated with it. There is no way back, please be careful with this option.</li>
		</ul>
	</div>

	<fieldset>
		<div class="form-full">
			<input type="checkbox" name="delete-group-understand" id="delete-group-understand" value="1" onclick="if(this.checked) { document.getElementById('delete-group-button').disabled = ''; } else { document.getElementById('delete-group-button').disabled = 'disabled'; }" />
			<label>I understand the consequences of deleting this guild.</label>
		</div>

		<div class="form-right">
			<button type="submit" disabled="disabled" id="delete-group-button" name="delete-group-button"><i class="fa fa-remove"></i>Delete This Group</button>
		</div>
			
		<div class="hidden">
			<?php wp_nonce_field( 'groups_delete_group' ); ?>
		</div>
	</fieldset>
<?php endif; ?>





	<?php // Allow plugins to add custom group edit screens
	do_action( 'groups_custom_edit_steps' ); ?>

	<fieldset>
		<input type="hidden" name="group-id" id="group-id" value="<?php bp_group_id(); ?>" />
	</fieldset>
</form>
