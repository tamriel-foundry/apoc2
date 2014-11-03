/*!
 * Apocrypha Theme JavaScript
 * Andrew Clayton
 * Version 2.0
 * 5-6-2014
========================================================================== */
var	siteurl		= 'http://localhost/tamrielfoundry/';
var themeurl	= siteurl	+ 'wp-content/themes/foundry/';
var wp_ajax 	= siteurl	+ 'wp-admin/admin-ajax.php';
var apoc_ajax 	= themeurl	+ "library/ajax.php";
var $			= jQuery;

/*! ----------------------------------------------------------
	1.0 - GOOGLE ANALYTICS AND GOOGLETAG ADS
----------------------------------------------------------- */

/*! Google Analytics */
(function(i,s,o,g,r,a,m){i['GoogleAnalyticsObject']=r;i[r]=i[r]||function(){
(i[r].q=i[r].q||[]).push(arguments)},i[r].l=1*new Date();a=s.createElement(o),
m=s.getElementsByTagName(o)[0];a.async=1;a.src=g;m.parentNode.insertBefore(a,m)
})(window,document,'script','//www.google-analytics.com/analytics.js','ga');
ga('create', 'UA-33555290-2', 'auto');
ga('send', 'pageview');

/*! Setup GoogleTag Ads */
var googletag = googletag || {};
googletag.cmd = googletag.cmd || [];
(function() {
	var gads = document.createElement('script');
	gads.async = true;
	gads.type = 'text/javascript';
	var useSSL = 'https:' == document.location.protocol;
	gads.src = (useSSL ? 'https:' : 'http:') + 
	'//www.googletagservices.com/tag/js/gpt.js';
	var node = document.getElementsByTagName('script')[0];
	node.parentNode.insertBefore(gads, node);
})();

// Primary Leaderboard
googletag.cmd.push(function() {
	googletag.defineSlot('/1045124/_TF_Leaderboard', [728, 90], 'primary-leaderboard-728').addService(googletag.pubads());
	googletag.pubads().enableSingleRequest();
	googletag.enableServices();
});

// Begin Document Ready
$(document).ready(function(){

/*! ----------------------------------------------------------
	2.0 - SCROLLING FUNCTIONS
----------------------------------------------------------- */
$('a.scroll-top').click(function(event) {
	event.preventDefault();
	$('html, body').animate({scrollTop: 0 }, 300);
});
/*! Scroll To Bottom */
$('a.scroll-bottom').click(function(event) {
	event.preventDefault();
	$('html, body').animate({scrollTop: $(document).height() }, 600);
});
/*! Scroll To Respond */
$('a.scroll-respond').click(function(event) {
	event.preventDefault();
	$('html, body').animate({scrollTop: $('#respond').offset().top }, 600);
	if ( !$( '#respond #new-post').is(":visible") ) {
		$('#respond .forum-header .forum-collapse').trigger("click");
	}
});

/*! ----------------------------------------------------------
	3.0 - LOGIN AND LOGOUT
----------------------------------------------------------- */
$('form#top-login-form').submit(function(event) {

	// Prevent the default action and display a tooltip
	event.preventDefault();
	var icon	= $(this).find( '#login-submit i.fa' )
	icon.attr('class','fa fa-cog fa-spin');
	
	// Hide error messages if they were visible
	var message = $('#top-login-message');
	message.slideUp();
	
	// Submit AJAX
	$.ajax({
		type: 		'POST',
		dataType: 	'json',
		url:		wp_ajax,
		data:		{
			'action'	: 'apoc_login',
			'username'	: $(this).find( '#username' ).val(),
			'password'	: $(this).find( '#password' ).val(),
			'remember'	: $(this).find( '#rememberme' ).val(),
			'security'	: $(this).find( '#security' ).val(),
		},
		success: function(result) {
			
			// Log the result
			console.log(result)
			
			// Redirect successful logins
			if ( result.loggedin === true ) {
				message.attr('class','updated').html(result.message).slideDown();
				document.location.href = $('#login-redirect').val();
			}
			
			// Handle unsuccesful attempts
			else {
				message.attr('class','error').html(result.message).slideDown();
				icon.attr('class','fa fa-lock');
			}
		}
	});	
});

/*! Logout Button */
$('a#login-logout').click(function(event) {
	$(this).children('i.fa').attr('class','fa fa-cog fa-spin');
});


/*! ----------------------------------------------------------
	4.0 - WORDPRESS FUNCTIONS
----------------------------------------------------------- */
if ( $( '.single-post ').length > 0 ) {
	if ( $( '#primary-sidebar').height() - $( '.post-content').height() > 750 ) $( '.widget.group-widget' ).remove();
	if ( $( '#primary-sidebar').height() - $( '.post-content').height() > 500 ) $( '.widget.community-stats' ).remove();
}

/*! Advanced Search */
$( '.adv-search-fields' ).not( '.active' ).hide();
$( 'select#search-for' ).bind( 'change', function() {

	// Hide old search results
	$( '#search-results' ).slideUp();
	
	// Get the new context
	var type 		= $( 'select#search-for' ).val();

	// Hide fields
	$( '.adv-search-fields' ).removeClass( 'active' ).hide();

	// Get the new fields
	type = ( type == 'pages' ) ? 'posts' : type;
	type = ( type == 'groups' ) ? 'members' : type;
	var target		= $( '#adv-search-' + type );
	
	// Show the relevant fields
	target.addClass( 'active' ).fadeIn();
});


/*! ----------------------------------------------------------
	5.0 - BBPRESS FUNCTIONS
----------------------------------------------------------- */

/*! Collapsing Forum Categories */
$('.forum-archive .forum-header .forum-freshness').append( '<a class="forum-collapse" href="#"><i class="fa fa-angle-double-down"></i></a>' );
$('.forum-collapse').click(function(event) {
	
	// Prevent default action
	event.preventDefault();
	
	// Change the icon
	if ( $(this).hasClass( 'collapsed' ) ) {
		$(this).children('i.fa').attr( 'class' , 'fa fa-angle-double-down' );
	} else {
		$(this).children('i.fa').attr( 'class' , 'fa fa-angle-double-left' );	
	}
	$(this).toggleClass( 'collapsed' );
	
	// Target subforum list
	$(this).parents( '.forum-header' ).next().slideToggle();
});


/*! Forum Quotes */
$("#comments,#forums,#bbpress-forums").on( "click" , "a.quote-link" , function( event ){

	// Prevent the default
	event.preventDefault();

	// Declare some variables
	var quoteParent = '';
	var quoteSource = '';
	var posttext 	= '';
	
	// Get the passed arguments
	var context		= $(this).data('context');
	var postid 		= $(this).data('id');
	var author 		= $(this).data('author');
	var date		= $(this).data('date');
	
	// Determine the context
	if ( 'reply' == context ) {
		quoteParent = '#post-' + postid;
		quoteSource = '#post-' + postid + ' .reply-content';
		editor		= 'bbp_reply_content';
	} else if ( 'comment' == context ) {
		quoteParent = '#comment-' + postid;
		quoteSource = '#comment-' + postid + ' .reply-content';
		editor		= 'comment';
	}
	
	// Look first for a specific text selection		
	if (window.getSelection) {
		posttext = window.getSelection().toString();
	} else if (document.selection && document.selection.type != "Control") {
		posttext = document.selection.createRange().text;
	}
	else return;
			
	// If there is a selection, make sure it came from the right place
	if ( '' !== posttext ) {
		
		// Split the selection to grab the first and last lines
		postlines = posttext.split(/\r?\n/);
		firstline 	= postlines[0];
		lastline 	= postlines[postlines.length-1];
		
		// If both the first line AND the last line come from within the target area, it must be valid
		if ( 0 === $( quoteSource ).find( ":contains(" + firstline + ")" ).length || 0 === $( quoteSource ).find( ":contains(" + lastline + ")" ).length ) {
			alert( 'This is not a valid quote selection. Either select a specific passage or select nothing to quote the full post.' );
			return;
		}
	}
		
	// Otherwise, if there's no selection, grab the whole post
	if ( '' === posttext )
		posttext = $( quoteSource ).html();
		
	// Remove revision log
	posttext = posttext.replace(/<ul id="bbp-reply-revision((.|\n)*?)(<\/ul>)/,"");
	
	// Remove spoilers (greedily)
	posttext = posttext.replace(/<div class="spoiler">((.|\n)*?)(<\/div>)/g,"");
	
	// Remove images (greedily)
	posttext = posttext.replace(/<img((.|\n)*?)(>)/g,"");
	
	// Remove extra line-breaks and spaces
	posttext = posttext.replace(/<br>/g,"");
	posttext = posttext.replace(/&nbsp;/g,"");
	
	// Strip out quote-toggle buttons from deep threads (greedily)
	posttext = posttext.replace(/<button class="quote-toggle((.|\n)*?)(<\/button>)/g,"");
	
	// Make collapsed content visible in the editor
	posttext = posttext.replace(/display: none;/g,"");

	// Build the quote
	var quote = '\r\n\r\n[quote author="' + author + '|' +quoteParent.substring(1)+ '|' +date+ '"]';
	quote += '\r\n' +posttext;
	quote += '\r\n[/quote]\r\n\r\n&nbsp;';
	
	// Switch to the html editor to embed the quote
	editor_html = document.getElementById( editor + '-html');
		switchEditors.switchto(editor_html);
			
	// Write the quote
	document.getElementById( editor ).value += quote;

	// Switch back to visual
	editor_tmce = document.getElementById( editor + '-tmce' );
		switchEditors.switchto(editor_tmce);
});

// End document ready
});