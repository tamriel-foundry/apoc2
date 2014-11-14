/*!
 * Apocrypha Theme BuddyPress JavaScript
 * Andrew Clayton
 * Version 2.0
 * 10-12-2014
 *
 * 1.0 - Page Load Actions (in progress)
 * 2.0 - New Activity (done)
 * 3.0 - Activity Directory
 * X.0 - Helper Functions (done)

========================================================================== */

// Define constants
var	siteurl = ( window.location.host == 'localhost' ) ? 'http://localhost/tamrielfoundry/' : 'http://tamrielfoundry.com/';
var ajaxurl 	= siteurl + 'wp-admin/admin-ajax.php';
var jq 			= jQuery;

// Global variable to prevent multiple AJAX requests
var bp_ajax_request = null;

// Start document ready block
jq(document).ready( function() {
	
/*! ----------------------------------------------------------
	1.0 - PAGE LOAD ACTIONS
----------------------------------------------------------- */

/* Activity filter and scope set */
bp_init_activity();

/* Object filter and scope set. */
var objects = [ 'members', 'groups', 'blogs', 'forums' ],
	$whats_new = jq('#whats-new');
bp_init_objects( objects );

/* @mention Compose Scrolling */
if ( $whats_new.length && bp_get_querystring('r') ) {
	var $member_nicename = $whats_new.val();

	jq('#whats-new-options').animate({
		height:'40px'
	});

	$whats_new.animate({
		height:'50px'
	});

	jq.scrollTo( $whats_new, 500, {
		offset:-125,
		easing:'swing'
	} );

	$whats_new.val('').focus().val( $member_nicename );
}

/*! ----------------------------------------------------------
	2.0 - ACTIVITY POSTING
----------------------------------------------------------- */

/* Hide the new status form by default */
jq( '#whats-new-form' ).hide();

/*! ---------------------------------
	2.1 - Form Visibility
---------------------------------- */
jq( '.update-status-button' ).click( function() {
	jq( 'form#whats-new-form' ).slideToggle( 'fast' , function() {
		jq('#whats-new').focus();
	});
});

/* Enable on focus */
jq('#whats-new').focus( function(){
	jq("#aw-whats-new-submit").prop("disabled", false);
	var $whats_new_form = jq("form#whats-new-form");
	if ( $whats_new_form.hasClass("submitted") ) {
		$whats_new_form.removeClass("submitted");	
	}
});

/* Disable on blur */
jq('#whats-new').blur( function(){
	if (!this.value.match(/\S+/)) {
		jq("textarea#whats-new").val('');
		jq("#aw-whats-new-submit").prop("disabled", true);
	}
});

/*! ---------------------------------
	2.2 - Form Submission
---------------------------------- */
jq("#aw-whats-new-submit").click( function(event) {

	// Prevent default
	event.preventDefault();
	
	// Get data
	var button = jq(this);
	var form = button.closest("form#whats-new-form");

	// Remove any displayed errors
	jq('div.error').remove();

	// Disable the form
	button.prop('disabled', true);
	form.children().each( function() {
		if ( jq.nodeName(this, "textarea") || jq.nodeName(this, "input") )
			jq(this).prop( 'disabled', true );
	});

	// Display a tooltip
	var orgHtml = button.html();
	button.html( '<i class="fa fa-spinner fa-spin"></i>Submitting' );
	form.addClass("submitted");

	// Default POST values
	var object = '';
	var item_id = jq("#whats-new-post-in").val();
	var content = jq("textarea#whats-new").val();

	// Set object for non-profile posts
	if ( item_id > 0 ) {
		object = jq("#whats-new-post-object").val();
	}

	// Submit the post request
	jq.post( ajaxurl, {
		action: 'post_update',
		'cookie': bp_get_cookies(),
		'_wpnonce_post_update': jq("input#_wpnonce_post_update").val(),
		'content': content,
		'object': object,
		'item_id': item_id,
		'_bp_as_nonce': jq('#_bp_as_nonce').val() || ''
	},
	function(response) {

		// Re-enable the form
		form.children().each( function() {
			if ( jq.nodeName(this, "textarea") || jq.nodeName(this, "input") ) {
				jq(this).prop( 'disabled', false );
			}
		});

		// Handle errors
		if ( response[0] + response[1] == '-1' ) {
			form.prepend( response.substr( 2, response.length ) );
			jq( 'form#' + form.attr('id') + ' div.error').hide().fadeIn( 200 );
		
		// Handle success
		} else {

			// If we are on a user profile page, add the new update to their recent status
			if ( 0 != jq("#profile-status").length ) {
				jq("#profile-status").slideUp(300,function(){				
					jq("#profile-status span#latest-status").html( content );
					jq("#profile-status").slideDown(300);
				});
			}

			// Append the new activity to the stream
			if ( 0 == jq("ul.activity-list").length ) {
				jq("div.error").slideUp(100).remove();
				jq("div#message").slideUp(100).remove();
				jq("div.activity").append( '<ul id="activity-stream" class="activity-list item-list">' );
			}
			jq("ul#activity-stream").prepend(response);

			// Make sure comment forms are hidden
			jq('.ac-form').hide();

			// Re-flag the newest update on the stream
			jq("ul#activity-stream li:first").addClass('new-update just-posted');
			jq("li.new-update").hide().slideDown( 300 );
			jq("li.new-update").removeClass( 'new-update' );

			// Empty the form and hide it
			jq("textarea#whats-new").val('');
			form.slideUp(300);
		}

		// Restore original button tooltip
		jq("#aw-whats-new-submit").prop("disabled", true).html( orgHtml );
	});
});

/*! ----------------------------------------------------------
	3.0 - ACTIVITY DIRECTORY
----------------------------------------------------------- */

/* Hide all activity comment forms by default */
jq('form.ac-form').hide();

/* Hide excess comments */
if ( jq('.activity-comments').length )
	bp_dtheme_hide_comments();


/* Activity Type Tab Delegation */
jq('.directory-tabs').click( function(event) {

	// Prevent default action
	event.preventDefault();

	// Get the click target
	var target = jq(event.target).parent();

	// Only target <a> elements
	if ( event.target.nodeName == 'STRONG' || event.target.nodeName == 'SPAN' )
		target = target.parent();
	else if ( event.target.nodeName != 'A' )
		return false;

	// Reset the page
	jq.cookie( 'bp-activity-oldestpage', 1, {
		path: '/'
	} );

	// Get the activity stream scope and filter
	var scope = target.attr('id').substr( 9, target.attr('id').length );
	var filter = jq("#activity-filter-select select").val();

	// Retrieve activities for the specified parameters
	bp_activity_request(scope, filter);
});

/* Activity Filter Select Delegation */
jq('#activity-filter-select select').change( function() {

	// Prevent default action
	event.preventDefault();

	// Get the selected tab
	var selected_tab = jq( '.activity-type-tabs li.selected' );
	if ( !selected_tab.length )
		var scope = null;
	else
		var scope = selected_tab.attr('id').substr( 9, selected_tab.attr('id').length );

	// Get the requested filter
	var filter = jq(this).val();

	// Retrieve activities for the specified parameters
	bp_activity_request(scope, filter);
});

/* Activity list event delegation */
jq('div.activity').click( function(event) {
	
	// Get the click target
	var target = jq(event.target);

	/*! ---------------------------------
		3.1 - Delete Activity
	---------------------------------- */
	if ( target.hasClass('delete-activity') ) {

		// Prevent default action
		event.preventDefault();

		// Display a tooltip
		target.children('i').toggleClass('fa-remove fa-spinner').addClass('fa-spin');

		// Get data about target activity
		var li        = target.parents('div.activity ul li');
		var id        = li.attr('id').substr( 9, li.attr('id').length );
		var link_href = target.attr('href');
		var nonce     = link_href.split('_wpnonce=');
		nonce = nonce[1];

		// Submit the post request
		jq.post( ajaxurl, {
			action: 'delete_activity',
			'cookie': bp_get_cookies(),
			'id': id,
			'_wpnonce': nonce
		},
		function(response) {

			// Handle errors
			if ( response[0] + response[1] == '-1' ) {
				li.prepend( response.substr( 2, response.length ) );
				li.children('div#message').hide().fadeIn(300);
				target.children('i').toggleClass('fa-remove fa-spinner').removeClass('fa-spin');
			
			// Handle success
			} else {
				li.slideUp(300);
			}
		});
	}

	/*! ---------------------------------
		3.2 - Reply Links
	---------------------------------- */
	if ( target.hasClass('acomment-reply') || target.parent().hasClass('acomment-reply') ) {
		if ( target.parent().hasClass('acomment-reply') )
			target = target.parent();

		// Prevent default action
		event.preventDefault();

		// Determine the target activity and appropriate form
		var id = target.attr('id');
		ids = id.split('-');
		var a_id = ids[2]
		var c_id = target.attr('href').substr( 10, target.attr('href').length );
		var form = jq( '#ac-form-' + a_id );

		// Hide any other forms
		form.css( 'display', 'none' );
		form.removeClass('root');
		jq('.ac-form').hide();

		// Hide any error messages
		form.children('div').each( function() {
			if ( jq(this).hasClass( 'error' ) )
				jq(this).hide();
		});

		// Possibly move the form to a new parent
		if ( ids[1] != 'comment' ) {
			jq('.activity-comments li#acomment-' + c_id).append( form );
		} else {
			jq('li#activity-' + a_id + ' .activity-comments').append( form );
		}
		if ( form.parent().hasClass( 'activity-comments' ) )
			form.addClass('root');

		// Display the form, scroll to it, and focus
		form.slideDown( 200 );	
		jq('html, body').animate({scrollTop: form.offset().top - 200 }, 600);
		jq('#ac-form-' + ids[2] + ' textarea').focus();
	}

	/*! ---------------------------------
		3.3 - Load More Activities
	---------------------------------- */
	if ( target.parent().hasClass('load-more') ) {

		// Prevent default action
		event.preventDefault();

		// Display a tooltip
		jq("#content li.load-more").addClass('loading');

		// Determine the correct "page" of activities
		if ( null == jq.cookie('bp-activity-oldestpage') )
			jq.cookie('bp-activity-oldestpage', 1, {
				path: '/'
			} );
		var oldest_page = ( jq.cookie('bp-activity-oldestpage') * 1 ) + 1;

		// Get recently posted activities
		var just_posted = [];
		jq('.activity-list li.just-posted').each( function(){
			just_posted.push( jq(this).attr('id').replace( 'activity-','' ) );
		});

		// Submit the ajax request
		jq.post( ajaxurl, {
			action: 'activity_get_older_updates',
			'cookie': bp_get_cookies(),
			'page': oldest_page,
			'exclude_just_posted': just_posted.join(',')
		},
		function(response)
		{
			jq("#content li.load-more").removeClass('loading');
			jq.cookie( 'bp-activity-oldestpage', oldest_page, {
				path: '/'
			} );
			jq("#content ul.activity-list").append(response.contents);

			target.parent().hide();
		}, 'json' );

		// Prevent default action
		return false;
	}

	/*! ---------------------------------
		3.4 - Activity Comment Submit
	---------------------------------- */
	if ( target.attr('name') == 'ac_form_submit' ) {

		// Prevent default action
		event.preventDefault();
		
		// Get activity comment data
		var form        = target.parents( 'form' );
		var form_parent = form.parent();
		var form_id     = form.attr('id').split('-');

		// Associate with the correct parent comment
		if ( !form_parent.hasClass('activity-comments') ) {
			var tmp_id = form_parent.attr('id').split('-');
			var comment_id = tmp_id[1];
		} else {
			var comment_id = form_id[2];
		}

		// Hide any error messages
		jq( 'form#' + form.attr('id') + ' div.error').hide();
		target.prop('disabled', true).children('i').toggleClass('fa-pencil fa-spinner').addClass('fa-spin');

		// Set up Ajax
		var ajaxdata = {
			action: 'new_activity_comment',
			'cookie': bp_get_cookies(),
			'_wpnonce_new_activity_comment': jq("input#_wpnonce_new_activity_comment").val(),
			'comment_id': comment_id,
			'form_id': form_id[2],
			'content': jq('form#' + form.attr('id') + ' textarea').val()
		};

		// Akismet
		var ak_nonce = jq('#_bp_as_nonce_' + comment_id).val();
		if ( ak_nonce ) {
			ajaxdata['_bp_as_nonce_' + comment_id] = ak_nonce;
		}

		// Submit the post request
		jq.post( ajaxurl, ajaxdata, function(response) {
			target.removeClass('loading');

			// Handle errors
			if ( response[0] + response[1] == '-1' ) {
				form.append( jq( response.substr( 2, response.length ) ).hide().fadeIn( 200 ) );
				jq(target).prop("disabled", false).children('i').toggleClass('fa-pencil fa-spinner').removeClass('fa-spin');
			
			// Handle success
			} else {
				var activity_comments = form.parent();
				form.fadeOut( 200, function() {
					if ( 0 == activity_comments.children('ul').length ) {
						if ( activity_comments.hasClass('activity-comments') ) {
							activity_comments.prepend('<ul></ul>');
						} else {
							activity_comments.append('<ul></ul>');
						}
					}

					// Preceeding whitespace breaks output with jQuery 1.9.0
					var the_comment = jq.trim( response );

					// Add the new comment to the list
					activity_comments.children('ul').append( jq( the_comment ).hide().fadeIn( 200 ).css('display','') );

					// De-populate the comment form
					form.children('textarea').val('');
				} );

				// Increase the "Reply (X)" button count
				jq('li#activity-' + form_id[2] + ' a.acomment-reply span').html( Number( jq('li#activity-' + form_id[2] + ' a.acomment-reply span').html() ) + 1 );

				// Increment the 'Show all x comments' string, if present
				var show_all_a = activity_comments.find('.show-all').find('a');
				if ( show_all_a ) {
					var new_count = jq('li#activity-' + form_id[2] + ' a.acomment-reply span').html();
					show_all_a.html( 'Show All ' + new_count + ' Comments' );
				}
			}
		});
	}

	/*! ---------------------------------
		3.5 - Delete Activity Comment
	---------------------------------- */
	if ( target.hasClass('acomment-delete') ) {

		// Prevent default action
		event.preventDefault();
		
		// Get comment data
		var link_href = target.attr('href');
		var comment_li = target.parents('li.activity-comment');
		var form = comment_li.parents('div.activity-comments').children('form');
		var nonce = link_href.split('_wpnonce=');
		nonce = nonce[1];

		// Determine the correct comment to delete
		var comment_id = link_href.split('cid=');
		comment_id = comment_id[1].split('&');
		comment_id = comment_id[0];

		// Give a tooltip
		target.children('i').toggleClass('fa-trash fa-spinner').addClass('fa-spin');

		// Remove any error messages
		jq('.activity-comments ul .error').remove();

		// Reset the form position
		comment_li.parents('.activity-comments').append(form);

		// Submit the ajax request
		jq.post( ajaxurl, {
			action: 'delete_activity_comment',
			'cookie': bp_get_cookies(),
			'_wpnonce': nonce,
			'id': comment_id
		},
		function(response)
		{
			// Handle errors
			if ( response[0] + response[1] == '-1' ) {
				comment_li.prepend( jq( response.substr( 2, response.length ) ).hide().fadeIn( 200 ) );

			// Restore the tooltip
			target.children('i').toggleClass('fa-trash fa-spinner').removeClass('fa-spin');
			
			// Handle success
			} else {

				// Remove the comment
				var children = jq( 'li#' + comment_li.attr('id') + ' ul' ).children('li');
				var child_count = 0;
				jq(children).each( function() {
					if ( !jq(this).is(':hidden') )
						child_count++;
				});
				comment_li.fadeOut(200, function() {
					comment_li.remove();
				});

				// Decrease the "Reply (X)" button count
				var count_span = jq('li#' + comment_li.parents('ul#activity-stream > li').attr('id') + ' a.acomment-reply span');
				var new_count = count_span.html() - ( 1 + child_count );
				count_span.html(new_count);

				// Change the 'Show all x comments' text
				var show_all_a = comment_li.siblings('.show-all').find('a');
				if ( show_all_a ) {
					show_all_a.html( 'Show All ' + new_count + ' Comments' );
				}

				/* If that was the last comment for the item, remove the has-comments class to clean up the styling */
				if ( 0 == new_count ) {
					jq(comment_li.parents('ul#activity-stream > li')).removeClass('has-comments');
				}
			}
		});
	}

	/*! ---------------------------------
		3.6 - Show Full Activity
	---------------------------------- */
	if ( target.parent().hasClass('activity-read-more') ) {

		// Prevent default action
		event.preventDefault();

		// Get info about the activity
		var link_id = target.parent().attr('id').split('-');
		var a_id = link_id[3];
		var type = link_id[0]; /* activity or acomment */
		var inner_class = type == 'acomment' ? 'acomment-content' : 'activity-content';
		var a_inner = jq('li#' + type + '-' + a_id + ' .' + inner_class );

		// Display a tooltip
		target.addClass('loading');

		// Get the full comment
		jq.post( ajaxurl, {
			action: 'get_single_activity_content',
			'activity_id': a_id
		},
		function(response) {
			jq(a_inner).slideUp(300).html(response).slideDown(300);
		});
	}

	/*! ---------------------------------
		3.7 - Show Hidden Comments
	---------------------------------- */
	if ( target.parent().hasClass('show-all') ) {

		// Prevent default action
		event.preventDefault();
		
		// Add a tooltip
		target.addClass('loading');

		// Fade in the comments 
		setTimeout( function() {
			$('.activity-comments ul li').fadeIn(200, function() {
				target.parent().remove();
			});
		}, 600 );
	}
});

// Escape Key Press for cancelling comment forms
jq(document).keydown( function(e) {
	e = e || window.event;
	if (e.target)
		element = e.target;
	else if (e.srcElement)
		element = e.srcElement;

	if( element.nodeType == 3)
		element = element.parentNode;

	if( e.ctrlKey == true || e.altKey == true || e.metaKey == true )
		return;

	var keyCode = (e.keyCode) ? e.keyCode : e.which;

	if ( keyCode == 27 ) {
		if (element.tagName == 'TEXTAREA') {
			if ( jq(element).hasClass('ac-input') || 'whats-new' === jq(element).attr('id') )
				jq(element).parents('form').slideUp( 200 );
		}
	}
});


/*! ----------------------------------------------------------
	5.0 - MEMBERS AND GROUPS DIRECTORY
----------------------------------------------------------- */

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

/*! ---------------------------------
	5.1 - Tabs and Filters
---------------------------------- */

/* Directory Navigation Tabs */
jq('.directory-tabs').click( function(event) {
	if ( jq(this).hasClass('no-ajax') )	return;

	// Prevent default action
	event.preventDefault();	

	// Determine the correct target
	var targetElem = ( event.target.nodeName == 'SPAN' ) ? event.target.parentNode : event.target;
	var target     = jq( targetElem ).parent();

	// If it's a valid directory tab, process the request
	if ( 'LI' == target[0].nodeName && !target.hasClass('last') ) {

		// Identify the tab
		var css_id = target.attr('id').split( '-' );
		var object = css_id[0];

		// Activity has its own handling for this
		if ( 'activity' == object )	return false;

		// Determine the scope, filter, and search terms
		var scope = css_id[1];
		var filter = jq("#" + object + "-order-select select").val();
		var search_terms = jq("#" + object + "_search").val();

		// Filter the request
		bp_filter_request( object, filter, scope, 'div.' + object, search_terms, 1, jq.cookie('bp-' + object + '-extras') );
	}
});

/* Directory Navigation Filters */
jq('.filter select').change( function() {

	// Prevent default action
	event.preventDefault();	

	// Determine the selected object and scope
	if ( jq('.directory-tabs li.selected').length )
		var el = jq('.directory-tabs li.selected');
	else
		var el = jq(this);
	var css_id = el.attr('id').split('-');
	
	var object = css_id[0];
	if ( 'friends' == object )	object = 'members';
	var scope = css_id[1];
	var filter = jq(this).val();

	// Determine the current search term
	var search_terms = false;
	if ( jq('.dir-search input').length )
		search_terms = jq('.dir-search input').val();

	// Process the request
	bp_filter_request( object, filter, scope, 'div.' + object, search_terms, 1, jq.cookie('bp-' + object + '-extras') );
});

/* Clear BP cookies on logout */
jq('#login-logout').click( function() {
	jq.removeCookie('bp-activity-scope', {
		path: '/'
	});
	jq.removeCookie('bp-activity-filter', {
		path: '/'
	});
	jq.removeCookie('bp-activity-oldestpage', {
		path: '/'
	});

	var objects = [ 'members', 'groups', 'blogs', 'forums' ];
	jq(objects).each( function(i) {
		jq.removeCookie('bp-' + objects[i] + '-scope', {
			path: '/'
		} );
		jq.removeCookie('bp-' + objects[i] + '-filter', {
			path: '/'
		} );
		jq.removeCookie('bp-' + objects[i] + '-extras', {
			path: '/'
		} );
	});
});


// End document ready block
});


/*! ----------------------------------------------------------
	10.0 - INITIALIZATION AND HELPER FUNCTIONS
----------------------------------------------------------- */

/* Get the current query string */
function bp_get_querystring( n ) {
	var half = location.search.split( n + '=' )[1];
	return half ? decodeURIComponent( half.split('&')[0] ) : null;
}

/* Setup activity scope and filter based on the current cookie settings. */
function bp_init_activity() {
	/* Reset the page */
	jq.cookie( 'bp-activity-oldestpage', 1, {
		path: '/'
	} );

	if ( null != jq.cookie('bp-activity-filter') && jq('#activity-filter-select').length )
		jq('#activity-filter-select select option[value="' + jq.cookie('bp-activity-filter') + '"]').prop( 'selected', true );

	/* Activity Tab Set */
	if ( null != jq.cookie('bp-activity-scope') && jq('.activity-type-tabs').length ) {
		jq('.activity-type-tabs li').each( function() {
			jq(this).removeClass('selected');
		});
		jq('li#activity-' + jq.cookie('bp-activity-scope') + ', .directory-tabs li.current').addClass('selected');
	}
}

/* Setup object scope and filter based on the current cookie settings for the object. */
function bp_init_objects(objects) {
	jq(objects).each( function(i) {
		if ( null != jq.cookie('bp-' + objects[i] + '-filter') && jq('li#' + objects[i] + '-order-select select').length )
			jq('li#' + objects[i] + '-order-select select option[value="' + jq.cookie('bp-' + objects[i] + '-filter') + '"]').prop( 'selected', true );

		if ( null != jq.cookie('bp-' + objects[i] + '-scope') && jq('div.' + objects[i]).length ) {
			jq('.directory-tabs li').each( function() {
				jq(this).removeClass('selected');
			});
			jq('.directory-tabs li#' + objects[i] + '-' + jq.cookie('bp-' + objects[i] + '-scope') + ', .directory-tabs#object-nav li.current').addClass('selected');
		}
	});
}

/* Filter the current content list (groups/members/blogs/topics) */
function bp_filter_request( object, filter, scope, target, search_terms, page, extras, caller ) {
	if ( 'activity' == object )
		return false;

	if ( bp_get_querystring('s') && !search_terms )
		search_terms = bp_get_querystring('s');

	if ( null == scope )
		scope = 'all';

	/* Save the settings we want to remain persistent to a cookie */
	jq.cookie( 'bp-' + object + '-scope', scope, {
		path: '/'
	} );
	jq.cookie( 'bp-' + object + '-filter', filter, {
		path: '/'
	} );
	jq.cookie( 'bp-' + object + '-extras', extras, {
		path: '/'
	} );

	/* Set the correct selected nav and filter */
	jq('.directory-tabs li').each( function() {
		jq(this).removeClass('selected');
	});
	jq('.directory-tabs li#' + object + '-' + scope + ', .directory-tabs#object-nav li.current').addClass('selected');
	jq('.directory-tabs li.selected a').prepend('<i class="fa fa-spinner fa-spin"></i>');
	jq('.directory-tabs select option[value="' + filter + '"]').prop( 'selected', true );

	if ( 'friends' == object )
		object = 'members';

	if ( bp_ajax_request )
		bp_ajax_request.abort();

	bp_ajax_request = jq.post( ajaxurl, {
		action: object + '_filter',
		'cookie': bp_get_cookies(),
		'object': object,
		'filter': filter,
		'search_terms': search_terms,
		'scope': scope,
		'page': page,
		'extras': extras
	},
	function(response)
	{
		/* animate to top if called from bottom pagination */
		if ( caller == 'pag-bottom' && jq('#subnav').length ) {
			var top = jq('#subnav').parent();
			jq('html,body').animate({scrollTop: top.offset().top}, 'slow', function() {
				jq(target).fadeOut( 100, function() {
					jq(this).html(response);
					jq(this).fadeIn(100);
			 	});
			});	

		} else {
			jq(target).fadeOut( 100, function() {
				jq(this).html(response);
				jq(this).fadeIn(100);
		 	});
		}

		jq('.directory-tabs li.selected i').remove();
	});
}

/* Activity Loop Requesting */
function bp_activity_request(scope, filter) {
	/* Save the type and filter to a session cookie */
	jq.cookie( 'bp-activity-scope', scope, {
		path: '/'
	} );
	jq.cookie( 'bp-activity-filter', filter, {
		path: '/'
	} );
	jq.cookie( 'bp-activity-oldestpage', 1, {
		path: '/'
	} );

	/* Remove selected and loading classes from tabs */
	jq('.directory-tabs li').each( function() {
		jq(this).removeClass('selected loading');
	});
	/* Set the correct selected nav and filter */
	jq('li#activity-' + scope + ', .directory-tabs li.current').addClass('selected');
	jq('.activity-type-tabs li.selected a').prepend('<i class="fa fa-spinner fa-spin"></i>');
	jq('#activity-filter-select select option[value="' + filter + '"]').prop( 'selected', true );

	/* Reload the activity stream based on the selection */
	jq('.widget_bp_activity_widget h2 span.ajax-loader').show();

	if ( bp_ajax_request )
		bp_ajax_request.abort();

	bp_ajax_request = jq.post( ajaxurl, {
		action: 'activity_widget_filter',
		'cookie': bp_get_cookies(),
		'_wpnonce_activity_filter': jq("input#_wpnonce_activity_filter").val(),
		'scope': scope,
		'filter': filter
	},
	function(response)
	{
		jq('.widget_bp_activity_widget h2 span.ajax-loader').hide();

		jq('div.activity').fadeOut( 100, function() {
			jq(this).html(response.contents);
			jq(this).fadeIn(100);

			// Hide comments and comment forms
			bp_dtheme_hide_comments();
			jq('form.ac-form').hide();
		});

		/* Update the feed link */
		if ( null != response.feed_url )
			jq('.directory #subnav li.feed a, .home-page #subnav li.feed a').attr('href', response.feed_url);

			jq('.activity-type-tabs li.selected a i').remove();

	}, 'json' );
}

/* Hide long lists of activity comments, only show the latest five root comments. */
function bp_dtheme_hide_comments() {
	var comments_divs = jq('div.activity-comments');

	if ( !comments_divs.length )
		return false;

	comments_divs.each( function() {
		if ( jq(this).children('ul').children('li').length < 5 ) return;

		var comments_div = jq(this);
		var parent_li = comments_div.parents('ul#activity-stream > li');
		var comment_lis = jq(this).children('ul').children('li');
		var comment_count = ' ';

		if ( jq('li#' + parent_li.attr('id') + ' a.acomment-reply span').length )
			var comment_count = jq('li#' + parent_li.attr('id') + ' a.acomment-reply span').html();
		
		/* Show the latest 5 root comments */
		comment_lis.each( function(i) {

			if ( i < comment_lis.length - 5 ) {
				jq(this).addClass('hidden');
				jq(this).toggle();
				if ( !i )
					comments_div.after( '<footer class="activity-footer show-all"><a class="button-dark" href="#' + parent_li.attr('id') + '/show-all/" title="Show all comments">Show All ' + comment_count + ' Comments</a></footer>' );
			}
		});

	});
}

/* Helper Functions */
function checkAll() {
	var checkboxes = document.getElementsByTagName("input");
	for(var i=0; i<checkboxes.length; i++) {
		if(checkboxes[i].type == "checkbox") {
			if($("check_all").checked == "") {
				checkboxes[i].checked = "";
			}
			else {
				checkboxes[i].checked = "checked";
			}
		}
	}
}

function clear(container) {
	if( !document.getElementById(container) ) return;
	var container = document.getElementById(container);
	if ( radioButtons = container.getElementsByTagName('INPUT') ) {
		for(var i=0; i<radioButtons.length; i++) {
			radioButtons[i].checked = '';
		}
	}
	if ( options = container.getElementsByTagName('OPTION') ) {
		for(var i=0; i<options.length; i++) {
			options[i].selected = false;
		}
	}
	return;
}

/* Returns a querystring of BP cookies (cookies beginning with 'bp-') */
function bp_get_cookies() {
	// get all cookies and split into an array
	var allCookies   = document.cookie.split(";");

	var bpCookies    = {};
	var cookiePrefix = 'bp-';

	// loop through cookies
	for (var i = 0; i < allCookies.length; i++) {
		var cookie    = allCookies[i];
		var delimiter = cookie.indexOf("=");
		var name      = jq.trim( unescape( cookie.slice(0, delimiter) ) );
		var value     = unescape( cookie.slice(delimiter + 1) );

		// if BP cookie, store it
		if ( name.indexOf(cookiePrefix) == 0 ) {
			bpCookies[name] = value;
		}
	}

	// returns BP cookies as querystring
	return encodeURIComponent( jq.param(bpCookies) );
}


/*! ----------------------------------------------------------
	X.0 - EMBEDDED PLUGINS
----------------------------------------------------------- */

/*! jQuery Cookie */
!function(a){"function"==typeof define&&define.amd?define(["jquery"],a):a("object"==typeof exports?require("jquery"):jQuery)}(function(a){function b(a){return h.raw?a:encodeURIComponent(a)}function c(a){return h.raw?a:decodeURIComponent(a)}function d(a){return b(h.json?JSON.stringify(a):String(a))}function e(a){0===a.indexOf('"')&&(a=a.slice(1,-1).replace(/\\"/g,'"').replace(/\\\\/g,"\\"));try{return a=decodeURIComponent(a.replace(g," ")),h.json?JSON.parse(a):a}catch(b){}}function f(b,c){var d=h.raw?b:e(b);return a.isFunction(c)?c(d):d}var g=/\+/g,h=a.cookie=function(e,g,i){if(void 0!==g&&!a.isFunction(g)){if(i=a.extend({},h.defaults,i),"number"==typeof i.expires){var j=i.expires,k=i.expires=new Date;k.setTime(+k+864e5*j)}return document.cookie=[b(e),"=",d(g),i.expires?"; expires="+i.expires.toUTCString():"",i.path?"; path="+i.path:"",i.domain?"; domain="+i.domain:"",i.secure?"; secure":""].join("")}for(var l=e?void 0:{},m=document.cookie?document.cookie.split("; "):[],n=0,o=m.length;o>n;n++){var p=m[n].split("="),q=c(p.shift()),r=p.join("=");if(e&&e===q){l=f(r,g);break}e||void 0===(r=f(r))||(l[q]=r)}return l};h.defaults={},a.removeCookie=function(b,c){return void 0===a.cookie(b)?!1:(a.cookie(b,"",a.extend({},c,{expires:-1})),!a.cookie(b))}});