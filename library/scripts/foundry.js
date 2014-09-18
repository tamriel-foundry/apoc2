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

$(document).ready(function(){

/*! Scroll To Top */
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
	$('#respond .forum-header .forum-collapse').trigger("click");
});


/*! Top Login Form */
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
				message.attr('class','update').html(result.message).slideDown();
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


/*! Truncate Sidebar to Content Width */
if ( $( '.single-post ').length > 0 ) {
	if ( $( '#primary-sidebar').height() - $( '.post-content').height() > 750 ) $( '.widget.group-widget' ).remove();
	if ( $( '#primary-sidebar').height() - $( '.post-content').height() > 500 ) $( '.widget.community-stats' ).remove();
}


/*! Collapsing Respond Form */
$('#bbpress #respond .forum-header .forum-content').append( '<a class="forum-collapse collapsed" href="#"><i class="fa fa-angle-double-left"></i></a>' ).parent().next().hide();
$('#respond .forum-header .forum-content h2').click(function(event) {
	$('#respond .forum-header .forum-collapse').trigger("click");
});

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




});