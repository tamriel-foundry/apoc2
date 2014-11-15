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

-----------------------------------
	FILE INDEX
-----------------------------------

ROOT THEME							| PROGRESS
----------------------------------- | -----------
404.php								| done
archive-bestof.php					| on-hold
archive-forum.php					| done
archive-topic.php					| done
archive.php							| deprecated
author.php							| done
calendar.php						|
category.php						| done
footer.php							| done
functions.php						| in-progress
header.php							| done
home.php							| done
page.php							| done
screenshot.jpg						| done
search.php							| deprecated
single-forum.php					| done
single-reply-edit.php				| done
single-reply-move.php				| done
single-topic-edit.php				| done
single-topic-merge.php				| done
single-topic-split.php				| done
single-topic.php					| done
singular-event.php					|
singular.php						| done
style.css							| in-progress

ACTIVITY							| PROGRESS
----------------------------------- | -----------
activity-loop.php					| done
comment.php							| done
entry.php							| done
index.php							| done
post-form.php						| done

BBPRESS								| PROGRESS
----------------------------------- | -----------
content-single-forum.php			| done
content-single-topic.php			| done
form-reply-move.php					| done
form-reply.php						| done
form-topic-merge.php				| done
form-topic-split.php				| done
form-topic.php						| done
loop-replies.php					| done
loop-single-forum.php				| done
loop-single-reply.php				| done
loop-single-topic.php				| done
loop-topics.php						| done
user-favorites.php					| done
user-replies-created.php			| done
user-subscriptions.php				| done
user-topics-created.php				| done

GROUPS								| PROGRESS
----------------------------------- | -----------
create.php							| done
groups-loop.php						| done
index.php							| done
single/activity.php					| done
single/admin.php					| done
single/front.php					| done
single/group-header.php				| done
single/home.php						| done
single/members.php					| done
single/request-membership.php 		| done
single/send-invites.php				| done
single/forum.php					| done
single/forum/merge.php				| done
single/forum/move.php				| done
single/forum/reply-edit.php			| done
single/forum/topic-edit.php			| done
single/forum/single-forum.php 		| deprecated
single/forum/split.php				| done
single/forum/topic.php				| done

GUILD								| PROGRESS
----------------------------------- | -----------
application-form.php				|
guild-application.php				|
guild-header.php					|
guild-home.php						|
guild-menu.php						|
guild-post.php						|
guild-roster.php					|
guild-sidebar.php					|
css/entropy-rising.css				|

LIBRARY								| PROGRESS
----------------------------------- | -----------
ajax.php							| done
apocrypha.php						| in-progress
admin/ajax.php						| in-progress
admin/postmeta.php					| done
css/editor-buttons.css 				| done
css/editor-content.css 				| done
css/login-style.css					| done
css/map-style.css					| done	
extensions/bbpress.php				| done
extensions/breadcrumbs.php			| done
extensions/buddypress.php			| in-progress
extensions/events.php				|
extensions/map.php					| done
extensions/search.php				| done
extensions/shortcodes.php			| done
extensions/slides.php				| done
extensions/thumbnail.php 			| done
extensions/widgets.php 				| done
functions/comments.php				| done
functions/context.php				| in-progress
functions/core.php 					| done
functions/login.php					| done
functions/pagination.php 			| on-hold				
functions/posts.php					| done
functions/seo.php					| deprecated
functions/users.php					| in-progress
js/buddypress.js					| in-progress
js/colorbox.min.js					| on-hold
js/contactform.js					| deprecated
js/flexslider.min.js				| done
js/foundry.js						| in-progress
js/ga.js							| done - embedded in foundry.js
js/map-control.js					| done
js/qc.js							| done - embedded in foundry.js
templates/admin-bar.php				| done
templates/admin-notifications.php 	| done
templates/adv-search.php			| done
templates/comment-edit.php			| done
templates/comment.php				| done
templates/comments.php				| done
templates/loop-single-event.php 	|
templates/loop-single-post.php		| done
templates/map.php					| done
templates/menu-primary.php			| done
templates/respond.php				| done
templates/searchform.php			| deprecated
templates/sidebar-primary.php 		| done
templates/slideshow.php				| done
	
MEMBERS								| PROGRESS
----------------------------------- | -----------
index.php							| done
members-loop.php					| done
single/activity.php					| done
single/forums.php					| done
single/friends.php					| done
single/friends/requests.php			| done
single/groups.php					| done
single/groups/invites.php			| done
single/home.php						| done
single/infractions.php				| done
single/infractions/notes.php		| done
single/infractions/warning.php 		| done
single/member-header.php			| done
single/messages.php					| done
single/messages/compose.php			| done
single/messages/messages-loop.php	| done
single/messages/single.php			| done
single/notifications.php			|
single/notifications/notifications-loop.php	|
single/notifications/read.php		|
single/notifications/unread.php		|
single/profile.php					| done
single/profile/change-avatar.php	| done
single/profile/edit.php				| done
single/settings.php					|
single/settings/capabilities.php	|
single/settings/delete-account.php	|
single/settings/general.php			|
single/settings/notifications.php	|

PAGES								| PROGRESS
----------------------------------- | -----------
classes/dragonknight.php			| on-hold
classes/nightblade.php				| on-hold
classes/sorcerer.php				| on-hold
classes/templar.php					| on-hold
development-faq.php					| deprecated
page-class.php						| on-hold
page-contact.php					| done
page-submit-guild.php				| deprecated

REGISTRATION						| PROGRESS
----------------------------------- | -----------
activate.php						| done
register.php						| done