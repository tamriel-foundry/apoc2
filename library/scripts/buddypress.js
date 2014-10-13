/*!
 * Apocrypha Theme BuddyPress JavaScript
 * Andrew Clayton
 * Version 2.0
 * 10-12-2014
========================================================================== */

// AJAX Functions
var jq = jQuery;

// Global variable to prevent multiple AJAX requests
var bp_ajax_request = null;

jq(document).ready( function() {
	
	/**** Page Load Actions *******************************************************/

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

	/**** Activity Posting ********************************************************/

	/**** Activity Comments *******************************************************/

	/* Hide all activity comment forms */
	jq('form.ac-form').hide();

	/* Hide excess comments */
	if ( jq('.activity-comments').length )
		bp_dtheme_hide_comments();

	/* Activity list event delegation */
	jq('div.activity').click( function(event) {
		
		// Get the click target
		var target = jq(event.target);

		// Comment and comment reply links
		if ( target.hasClass('acomment-reply') || target.parent().hasClass('acomment-reply') ) {
			if ( target.parent().hasClass('acomment-reply') )
				target = target.parent();

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

			// Prevent default action
			return false;
		}

		// Activity comment posting
		if ( target.attr('name') == 'ac_form_submit' ) {
			var form        = target.parents( 'form' );
			var form_parent = form.parent();
			var form_id     = form.attr('id').split('-');

			if ( !form_parent.hasClass('activity-comments') ) {
				var tmp_id = form_parent.attr('id').split('-');
				var comment_id = tmp_id[1];
			} else {
				var comment_id = form_id[2];
			}

			// Hide any error messages
			jq( 'form#' + form.attr('id') + ' div.error').hide();
			target.addClass('loading').prop('disabled', true);

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

			jq.post( ajaxurl, ajaxdata, function(response) {
				target.removeClass('loading');

				// Check for errors and append if found.
				if ( response[0] + response[1] == '-1' ) {
					form.append( jq( response.substr( 2, response.length ) ).hide().fadeIn( 200 ) );
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

						activity_comments.children('ul').append( jq( the_comment ).hide().fadeIn( 200 ) );
						form.children('textarea').val('');
						activity_comments.parent().addClass('has-comments');
					} );

					jq( 'form#' + form.attr('id') + ' textarea').val('');

					// Increase the "Reply (X)" button count
					jq('li#activity-' + form_id[2] + ' a.acomment-reply span').html( Number( jq('li#activity-' + form_id[2] + ' a.acomment-reply span').html() ) + 1 );

					// Increment the 'Show all x comments' string, if present
					var show_all_a = activity_comments.find('.show-all').find('a');
					if ( show_all_a ) {
						var new_count = jq('li#activity-' + form_id[2] + ' a.acomment-reply span').html();
						show_all_a.html( BP_DTheme.show_x_comments.replace( '%d', new_count ) );
					}
				}

				jq(target).prop("disabled", false);
			});

			return false;
		}

		// Showing hidden comments - pause for half a second
		if ( target.parent().hasClass('show-all') ) {
			target.addClass('loading');

			setTimeout( function() {
				$('.activity-comments ul li').fadeIn(200, function() {
					target.parent().remove();
				});
			}, 600 );

			return false;
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
				if ( jq(element).hasClass('ac-input') )
					jq(element).parent().parent().parent().slideUp( 200 );
			}
		}
	});








// End on-ready
});









/*! ----------------------------------------------------------
	X.0 - INITIALIZATION AND HELPER FUNCTIONS
----------------------------------------------------------- */

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
		jq('li#activity-' + jq.cookie('bp-activity-scope') + ', .item-list-tabs li.current').addClass('selected');
	}
}

/* Setup object scope and filter based on the current cookie settings for the object. */
function bp_init_objects(objects) {
	jq(objects).each( function(i) {
		if ( null != jq.cookie('bp-' + objects[i] + '-filter') && jq('li#' + objects[i] + '-order-select select').length )
			jq('li#' + objects[i] + '-order-select select option[value="' + jq.cookie('bp-' + objects[i] + '-filter') + '"]').prop( 'selected', true );

		if ( null != jq.cookie('bp-' + objects[i] + '-scope') && jq('div.' + objects[i]).length ) {
			jq('.item-list-tabs li').each( function() {
				jq(this).removeClass('selected');
			});
			jq('.item-list-tabs li#' + objects[i] + '-' + jq.cookie('bp-' + objects[i] + '-scope') + ', div.item-list-tabs#object-nav li.current').addClass('selected');
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
	jq('.item-list-tabs li').each( function() {
		jq(this).removeClass('selected');
	});
	jq('.item-list-tabs li#' + object + '-' + scope + ', .item-list-tabs#object-nav li.current').addClass('selected');
	jq('.item-list-tabs li.selected').addClass('loading');
	jq('.item-list-tabs select option[value="' + filter + '"]').prop( 'selected', true );

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

		jq('.item-list-tabs li.selected').removeClass('loading');
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
	jq('.item-list-tabs li').each( function() {
		jq(this).removeClass('selected loading');
	});
	/* Set the correct selected nav and filter */
	jq('li#activity-' + scope + ', .item-list-tabs li.current').addClass('selected');
	jq('#object-nav.item-list-tabs li.selected, div.activity-type-tabs li.selected').addClass('loading');
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

			/* Selectively hide comments */
			bp_dtheme_hide_comments();
		});

		/* Update the feed link */
		if ( null != response.feed_url )
			jq('.directory #subnav li.feed a, .home-page #subnav li.feed a').attr('href', response.feed_url);

		jq('.item-list-tabs li.selected').removeClass('loading');

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