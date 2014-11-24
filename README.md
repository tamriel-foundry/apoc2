apoc2
=====

The new Tamriel Foundry apocrypha theme!

-----------------------------------
	DATABASE CHANGES
-----------------------------------

group_meta {
	group_platform -> group_server
	group_server = pcmac -> pcna
	group_server = playstation -> ps4
	group_region (DELETE)

	delete rows where meta_value = 'blank'
}

usermeta {
	delete rows where meta_value = 'blank'
	delete contact method bethforums
}

-----------------------------------
	CONFIGURATION OPTIONS
-----------------------------------

BuddyPress options {
	allow group creation for everyone
}

-----------------------------------
	CODE HACKS
-----------------------------------

bbpress/includes/extend/buddypress/groups.php
	setup_variables()
		$this->enable_create_step   = false;
		$this->enable_edit_item     = current_user_can( 'delete_posts' ) ? true : false;
		$this->template_file        = 'groups/single/forum';

	create_screen() - restructure/format

	display_forums() - restructure, change div#forums, remove <h3>s

buddypress/bp-groups/bp-groups-template.php
	bp_group_admin_tabs() - change "Photo" to "Avatar"

buddypress/bp-groups/bp-groups-notifications.php | LINE 609
	case 'new_calendar_event' :
		return apply_filters( 'calendar_event_notification' , $item_id, $secondary_item_id );