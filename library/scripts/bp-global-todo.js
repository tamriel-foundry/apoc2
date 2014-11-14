
	/**** Directory Search ****************************************************/

	/* The search form on all directory pages */
	jq('.dir-search').click( function(event) {
		if ( jq(this).hasClass('no-ajax') )
			return;

		var target = jq(event.target);

		if ( target.attr('type') == 'submit' ) {
			var css_id = jq('.item-list-tabs li.selected').attr('id').split( '-' );
			var object = css_id[0];

			bp_filter_request( object, jq.cookie('bp-' + object + '-filter'), jq.cookie('bp-' + object + '-scope') , 'div.' + object, target.parent().children('label').children('input').val(), 1, jq.cookie('bp-' + object + '-extras') );

			return false;
		}
	});

	/**** Tabs and Filters ****************************************************/

	/* All pagination links run through this function */
	jq('div#content').click( function(event) {
		var target = jq(event.target);

		if ( target.hasClass('button') )
			return true;

		if ( target.parent().parent().hasClass('pagination') && !target.parent().parent().hasClass('no-ajax') ) {
			if ( target.hasClass('dots') || target.hasClass('current') )
				return false;

			if ( jq('.item-list-tabs li.selected').length )
				var el = jq('.item-list-tabs li.selected');
			else
				var el = jq('li.filter select');

			var page_number = 1;
			var css_id = el.attr('id').split( '-' );
			var object = css_id[0];
			var search_terms = false;
			var pagination_id = jq(target).closest('.pagination-links').attr('id');

			if ( jq('div.dir-search input').length )
				search_terms = jq('.dir-search input').val();

			if ( jq(target).hasClass('next') )
				var page_number = Number( jq('.pagination span.current').html() ) + 1;
			else if ( jq(target).hasClass('prev') )
				var page_number = Number( jq('.pagination span.current').html() ) - 1;
			else
				var page_number = Number( jq(target).html() );
			
			if ( pagination_id.indexOf( 'pag-bottom' ) !== -1 ) {
				var caller = 'pag-bottom';
			} else {
				var caller = null;
			}

			bp_filter_request( object, jq.cookie('bp-' + object + '-filter'), jq.cookie('bp-' + object + '-scope'), 'div.' + object, search_terms, page_number, jq.cookie('bp-' + object + '-extras'), caller );

			return false;
		}

	});

	/** Invite Friends Interface ****************************************/

	/* Select a user from the list of friends and add them to the invite list */
	jq("div#invite-list input").click( function() {
		jq('.ajax-loader').toggle();

		var friend_id = jq(this).val();

		if ( jq(this).prop('checked') == true )
			var friend_action = 'invite';
		else
			var friend_action = 'uninvite';

		jq('.item-list-tabs li.selected').addClass('loading');

		jq.post( ajaxurl, {
			action: 'groups_invite_user',
			'friend_action': friend_action,
			'cookie': bp_get_cookies(),
			'_wpnonce': jq("input#_wpnonce_invite_uninvite_user").val(),
			'friend_id': friend_id,
			'group_id': jq("input#group_id").val()
		},
		function(response)
		{
			if ( jq("#message") )
				jq("#message").hide();

			jq('.ajax-loader').toggle();

			if ( friend_action == 'invite' ) {
				jq('#friend-list').append(response);
			} else if ( friend_action == 'uninvite' ) {
				jq('#friend-list li#uid-' + friend_id).remove();
			}

			jq('.item-list-tabs li.selected').removeClass('loading');
		});
	});

	/* Remove a user from the list of users to invite to a group */
	jq("#friend-list").on('click', 'li a.remove', function() {
		jq('.ajax-loader').toggle();

		var friend_id = jq(this).attr('id');
		friend_id = friend_id.split('-');
		friend_id = friend_id[1];

		jq.post( ajaxurl, {
			action: 'groups_invite_user',
			'friend_action': 'uninvite',
			'cookie': bp_get_cookies(),
			'_wpnonce': jq("input#_wpnonce_invite_uninvite_user").val(),
			'friend_id': friend_id,
			'group_id': jq("input#group_id").val()
		},
		function(response)
		{
			jq('.ajax-loader').toggle();
			jq('#friend-list li#uid-' + friend_id).remove();
			jq('#invite-list input#f-' + friend_id).prop('checked', false);
		});

		return false;
	});

	/** Profile Visibility Settings *********************************/
	jq('.field-visibility-settings').hide();
	jq('.visibility-toggle-link').on( 'click', function() {
		var toggle_div = jq(this).parent();

		jq(toggle_div).fadeOut( 600, function(){
			jq(toggle_div).siblings('.field-visibility-settings').slideDown(400);
		});

		return false;
	} );

	jq('.field-visibility-settings-close').on( 'click', function() {
		var settings_div = jq(this).parent();

		jq(settings_div).slideUp( 400, function(){
			jq(settings_div).siblings('.field-visibility-settings-toggle').fadeIn(800);
		});

		return false;
	} );

	jq("#profile-edit-form input:not(:submit), #profile-edit-form textarea, #profile-edit-form select, #signup_form input:not(:submit), #signup_form textarea, #signup_form select").change( function() {
		var shouldconfirm = true;

		jq('#profile-edit-form input:submit, #signup_form input:submit').on( 'click', function() {
			shouldconfirm = false;
		});
		
		window.onbeforeunload = function(e) {
			if ( shouldconfirm ) {
				return BP_DTheme.unsaved_changes;
			}
		};
	});

	/** Friendship Requests **************************************/

	/* Accept and Reject friendship request buttons */
	jq("ul#friend-list a.accept, ul#friend-list a.reject").click( function() {
		var button = jq(this);
		var li = jq(this).parents('ul#friend-list li');
		var action_div = jq(this).parents('li div.action');

		var id = li.attr('id').substr( 11, li.attr('id').length );
		var link_href = button.attr('href');

		var nonce = link_href.split('_wpnonce=');
		nonce = nonce[1];

		if ( jq(this).hasClass('accepted') || jq(this).hasClass('rejected') )
			return false;

		if ( jq(this).hasClass('accept') ) {
			var action = 'accept_friendship';
			action_div.children('a.reject').css( 'visibility', 'hidden' );
		} else {
			var action = 'reject_friendship';
			action_div.children('a.accept').css( 'visibility', 'hidden' );
		}

		button.addClass('loading');

		jq.post( ajaxurl, {
			action: action,
			'cookie': bp_get_cookies(),
			'id': id,
			'_wpnonce': nonce
		},
		function(response) {
			button.removeClass('loading');

			if ( response[0] + response[1] == '-1' ) {
				li.prepend( response.substr( 2, response.length ) );
				li.children('div#message').hide().fadeIn(200);
			} else {
				button.fadeOut( 100, function() {
					if ( jq(this).hasClass('accept') ) {
						action_div.children('a.reject').hide();
						jq(this).html( BP_DTheme.accepted ).contents().unwrap();
					} else {
						action_div.children('a.accept').hide();
						jq(this).html( BP_DTheme.rejected ).contents().unwrap();
					}
				});
			}
		});

		return false;
	});

	/* Add / Remove friendship buttons */
	jq('#members-dir-list').on('click', '.friendship-button a', function() {
		jq(this).parent().addClass('loading');
		var fid = jq(this).attr('id');
		fid = fid.split('-');
		fid = fid[1];

		var nonce = jq(this).attr('href');
		nonce = nonce.split('?_wpnonce=');
		nonce = nonce[1].split('&');
		nonce = nonce[0];

		var thelink = jq(this);

		jq.post( ajaxurl, {
			action: 'addremove_friend',
			'cookie': bp_get_cookies(),
			'fid': fid,
			'_wpnonce': nonce
		},
		function(response)
		{
			var action = thelink.attr('rel');
			var parentdiv = thelink.parent();

			if ( action == 'add' ) {
				jq(parentdiv).fadeOut(200,
					function() {
						parentdiv.removeClass('add_friend');
						parentdiv.removeClass('loading');
						parentdiv.addClass('pending_friend');
						parentdiv.fadeIn(200).html(response);
					}
					);

			} else if ( action == 'remove' ) {
				jq(parentdiv).fadeOut(200,
					function() {
						parentdiv.removeClass('remove_friend');
						parentdiv.removeClass('loading');
						parentdiv.addClass('add');
						parentdiv.fadeIn(200).html(response);
					}
					);
			}
		});
		return false;
	} );

	/** Group Join / Leave Buttons **************************************/

	jq('#groups-dir-list').on('click', '.group-button a', function() {
		var gid = jq(this).parent().attr('id');
		gid = gid.split('-');
		gid = gid[1];

		var nonce = jq(this).attr('href');
		nonce = nonce.split('?_wpnonce=');
		nonce = nonce[1].split('&');
		nonce = nonce[0];

		var thelink = jq(this);

		jq.post( ajaxurl, {
			action: 'joinleave_group',
			'cookie': bp_get_cookies(),
			'gid': gid,
			'_wpnonce': nonce
		},
		function(response)
		{
			var parentdiv = thelink.parent();

			// user groups page
			if ( ! jq('body.directory').length ) {
				location.href = location.href;

			// groups directory
			} else {
				jq(parentdiv).fadeOut(200,
					function() {
						parentdiv.fadeIn(200).html(response);

						var mygroups = jq('#groups-personal span');
						var add      = 1;

						if( thelink.hasClass( 'leave-group' ) ) {
							// hidden groups slide up
							if ( parentdiv.hasClass( 'hidden' ) ) {
								parentdiv.closest('li').slideUp( 200 );
							}

							add = 0;
						} else if ( thelink.hasClass( 'request-membership' ) ) {
							add = false;
						}

						// change the "My Groups" value
						if ( add !== false && mygroups.length ) {
							if ( add ) {
								mygroups.text( ( mygroups.text() >> 0 ) + 1 );
							} else {
								mygroups.text( ( mygroups.text() >> 0 ) - 1 );
							}
						}

					}
				);
			}
		});
		return false;
	} );

	/** Button disabling ************************************************/

	jq('.pending').click(function() {
		return false;
	});

	/** Private Messaging ******************************************/

	/** Message search*/
	jq('.message-search').click( function(event) {
		if ( jq(this).hasClass('no-ajax') )
			return;

		var target = jq(event.target);

		if ( target.attr('type') == 'submit' ) {
			//var css_id = jq('.item-list-tabs li.selected').attr('id').split( '-' );
			var object = 'messages';

			bp_filter_request( object, jq.cookie('bp-' + object + '-filter'), jq.cookie('bp-' + object + '-scope') , 'div.' + object, target.parent().children('label').children('input').val(), 1, jq.cookie('bp-' + object + '-extras') );

			return false;
		}
	});

	/* AJAX send reply functionality */
	jq("input#send_reply_button").click(
		function() {
			var order = jq('#messages_order').val() || 'ASC',
			offset = jq('#message-recipients').offset();

			var button = jq("input#send_reply_button");
			jq(button).addClass('loading');

			jq.post( ajaxurl, {
				action: 'messages_send_reply',
				'cookie': bp_get_cookies(),
				'_wpnonce': jq("input#send_message_nonce").val(),

				'content': jq("#message_content").val(),
				'send_to': jq("input#send_to").val(),
				'subject': jq("input#subject").val(),
				'thread_id': jq("input#thread_id").val()
			},
			function(response)
			{
				if ( response[0] + response[1] == "-1" ) {
					jq('form#send-reply').prepend( response.substr( 2, response.length ) );
				} else {
					jq('form#send-reply div#message').remove();
					jq("#message_content").val('');

					if ( 'ASC' == order ) {
						jq('form#send-reply').before( response );
					} else {
						jq('#message-recipients').after( response );
						jq(window).scrollTop(offset.top);
					}

					jq(".new-message").hide().slideDown( 200, function() {
						jq('.new-message').removeClass('new-message');
					});
				}
				jq(button).removeClass('loading');
			});

			return false;
		}
	);

	/* Marking private messages as read and unread */
	jq("a#mark_as_read, a#mark_as_unread").click(function() {
		var checkboxes_tosend = '';
		var checkboxes = jq("#message-threads tr td input[type='checkbox']");

		if ( 'mark_as_unread' == jq(this).attr('id') ) {
			var currentClass = 'read'
			var newClass = 'unread'
			var unreadCount = 1;
			var inboxCount = 0;
			var unreadCountDisplay = 'inline';
			var action = 'messages_markunread';
		} else {
			var currentClass = 'unread'
			var newClass = 'read'
			var unreadCount = 0;
			var inboxCount = 1;
			var unreadCountDisplay = 'none';
			var action = 'messages_markread';
		}

		checkboxes.each( function(i) {
			if(jq(this).is(':checked')) {
				if ( jq('tr#m-' + jq(this).attr('value')).hasClass(currentClass) ) {
					checkboxes_tosend += jq(this).attr('value');
					jq('tr#m-' + jq(this).attr('value')).removeClass(currentClass);
					jq('tr#m-' + jq(this).attr('value')).addClass(newClass);
					var thread_count = jq('tr#m-' + jq(this).attr('value') + ' td span.unread-count').html();

					jq('tr#m-' + jq(this).attr('value') + ' td span.unread-count').html(unreadCount);
					jq('tr#m-' + jq(this).attr('value') + ' td span.unread-count').css('display', unreadCountDisplay);

					var inboxcount = jq('tr.unread').length;

					jq('a#user-messages span').html( inboxcount );

					if ( i != checkboxes.length - 1 ) {
						checkboxes_tosend += ','
					}
				}
			}
		});
		jq.post( ajaxurl, {
			action: action,
			'thread_ids': checkboxes_tosend
		});
		return false;
	});

	/* Selecting unread and read messages in inbox */
	jq( 'body.messages #item-body div.messages' ).on( 'change', '#message-type-select', function() {
		var selection = this.value;
		var checkboxes = jq( "td input[type='checkbox']" );

		checkboxes.each( function(i) {
			checkboxes[i].checked = "";
		});

		var checked_value = "checked";
		switch ( selection ) {
			case 'unread' :
				checkboxes = jq("tr.unread td input[type='checkbox']");
				break;
			case 'read' :
				checkboxes = jq("tr.read td input[type='checkbox']");
				break;
			case '' :
				checked_value = "";
				break;
		}

		checkboxes.each( function(i) {
			checkboxes[i].checked = checked_value;
		});
	});
	
	/* Bulk delete messages */
	jq( 'body.messages #item-body div.messages' ).on( 'click', '.messages-options-nav a', function() {
		if ( -1 == jq.inArray( this.id ), Array( 'delete_sentbox_messages', 'delete_inbox_messages' ) ) {
			return;
		}
		
		checkboxes_tosend = '';
		checkboxes = jq("#message-threads tr td input[type='checkbox']");

		jq('#message').remove();
		jq(this).addClass('loading');

		jq(checkboxes).each( function(i) {
			if( jq(this).is(':checked') )
				checkboxes_tosend += jq(this).attr('value') + ',';
		});

		if ( '' == checkboxes_tosend ) {
			jq(this).removeClass('loading');
			return false;
		}
		
		jq.post( ajaxurl, {
			action: 'messages_delete',
			'thread_ids': checkboxes_tosend
		}, function(response) {
			if ( response[0] + response[1] == "-1" ) {
				jq('#message-threads').prepend( response.substr( 2, response.length ) );
			} else {
				jq('#message-threads').before( '<div id="message" class="updated"><p>' + response + '</p></div>' );

				jq(checkboxes).each( function(i) {
					if( jq(this).is(':checked') ) {
						// We need to uncheck because message is only hidden
						// Otherwise, AJAX will be fired again with same data 
						jq(this).attr( 'checked', false );
						jq(this).parent().parent().fadeOut(150);
					}
				});
			}

			jq('#message').hide().slideDown(150);
			jq("#delete_inbox_messages, #delete_sentbox_messages").removeClass('loading');
		});

		return false;
	});

});

