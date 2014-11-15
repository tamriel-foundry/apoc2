
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

});

