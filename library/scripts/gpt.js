/*! Setup GoogleTag Ads
========================================================================== */
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
	googletag.defineSlot('/1045124/_TF_Sidebar', [300, 250], 'sidebar-banner-300').addService(googletag.pubads());
	googletag.pubads().enableSingleRequest();
	googletag.enableServices();
});