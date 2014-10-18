

<?php //BPRESS/INCLUDES/EXTEND/BUDDYPRESS/GROUPS.PHP

public function create_screen( $group_id = 0 ) {

	// Bail if not looking at this screen
	if ( !bp_is_group_creation_step( $this->slug ) )
		return false;

	// Check for possibly empty group_id
	if ( empty( $group_id ) ) {
		$group_id = bp_get_new_group_id();
	}

	$checked = bp_get_new_group_enable_forum() || groups_get_groupmeta( $group_id, 'forum_id' ); ?>

	<div class="instructions">
		<h3 class="double-border">Step 3 - Create Group Forum?</h3>
		<ul>
			<li>Create a discussion forum to allow members of this group to communicate in a structured, bulletin-board style fashion.</li>
			<li>This should be left disabled except under special circumstances.</li>
		</ul>
	</div>

	<fieldset>
		<div class="form-left">
			<input type="checkbox" name="bbp-create-group-forum" id="bbp-create-group-forum" value="1" /><label><?php esc_html_e( 'Yes. I want this group to have a forum.', 'bbpress' ); ?></label>
		</div>
	</fieldset>
	<?php
}



?>