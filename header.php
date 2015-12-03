<?php 
/**
 * Apocrypha Theme Header Template
 * Andrew Clayton
 * Version 2.0
 * 4-29-2014
 */
?>
<!doctype html>
<!-- 
	<?php echo SITENAME; ?> 
	Theme Version <?php echo THEMEVER; ?> 
	Copyright <?php echo date( 'm-d-Y' ); ?> 
-->

<!-- Begin Header -->
<html dir="ltr" lang="en-US">
<head>
	<meta charset="UTF-8">
	<title><?php apoc_title(); ?></title>
	<meta name="description" content="<?php apoc_description(); ?>"/>
 	<meta name="viewport" content="width=device-width, initial-scale=1"> 
	<link rel="SHORTCUT ICON" href="<?php echo THEME_URI . '/images/icons/favicon.ico'; ?>">
	<?php wp_head(); ?>	

	<!-- Start: GPT Sync -->
	<script type='text/javascript'>
		var gptadslots=[];
		(function(){
			var useSSL = 'https:' == document.location.protocol;
			var src = (useSSL ? 'https:' : 'http:') + '//www.googletagservices.com/tag/js/gpt.js';
			document.write('<scr' + 'ipt src="' + src + '"></scr' + 'ipt>');
		})();
	</script>

	<script type="text/javascript">

		//For 300x250
		var mapping1 = googletag.sizeMapping().
		addSize([1024, 500], [300, 250]).
		addSize([768, 400], [300, 250]).
		addSize([470, 400], [300, 250]).
		addSize([360, 400], [320, 50]).
		addSize([0, 0], [320, 50]).
		build();

		//For 728x90
		var mapping2 = googletag.sizeMapping().
		addSize([1024, 500], [728, 90]).
		addSize([768, 400], [728, 90]).
		addSize([470, 400], [728, 90]).
		addSize([360, 400], [320, 50]).
		addSize([0, 0], [320, 50]).
		build();
		
		//Adslot 1 declaration
		gptadslots[1]= googletag.defineSlot('/1045124/TamrielFoundry', [[728,90]],'div-gpt-ad-821545701545728213-1').defineSizeMapping(mapping2).addService(googletag.pubads());

		//Adslot 2 declaration
		gptadslots[2]= googletag.defineSlot('/1045124/TamrielFoundry', [[300,250]],'div-gpt-ad-821545701545728213-2').defineSizeMapping(mapping1).addService(googletag.pubads());

		//Adslot 3 declaration
		//gptadslots[3]= googletag.defineSlot('/1045124/TamrielFoundry', [[1,1]],'div-gpt-ad-821545701545728213-3').addService(googletag.pubads());

		//Adslot 4 declaration
		//gptadslots[4]= googletag.defineSlot('/1045124/TamrielFoundry_Interstitial', [[1,1]],'div-gpt-ad-821545701545728213-4').addService(googletag.pubads());

		googletag.pubads().setTargeting('section',['<?php echo apoc()->classes[1]; ?>']).setTargeting('platform',['<?php if ( apoc()->user->ID != 0 ) echo get_user_meta( apoc()->user->ID , "server" , true ); ?>']).setTargeting('GS_Game',['TF_Game']).setTargeting('GS_Genre',['TF_Genre']).setTargeting('URL',['<?php echo str_replace( SITEURL , '' , apoc()->url ); ?>']);
		googletag.pubads().enableSyncRendering();
		googletag.enableServices();
	</script>
	<!-- End: GPT -->


	<!-- Begin comScore Tag -->
	<script>
	  var _comscore = _comscore || [];
	  _comscore.push({ c1: "2", c2: "20676402" });
	  (function() {
	    var s = document.createElement("script"), el = document.getElementsByTagName("script")[0]; s.async = true;
	    s.src = (document.location.protocol == "https:" ? "https://sb" : "http://b") + ".scorecardresearch.com/beacon.js";
	    el.parentNode.insertBefore(s, el);
	  })();
	</script>
	<noscript>
	  <img src="http://b.scorecardresearch.com/p?c1=2&c2=20676402&cv=2.0&cj=1" />
	</noscript>
	<!-- End comScore Tag -->
</head>

<body class="<?php apoc_body_class(); ?>">
	
	<div id="header-container">	
	
		<nav id="admin-bar" role="navigation">
			<?php apoc_admin_bar(); ?>
		</nav>
	
		<header id="site-header" role="banner">	
			<a id="site-title" href="<?php echo SITEURL; ?>" title="<?php echo SITENAME; ?>"></a>

			<!-- Beginning Sync AdSlot 1 for Ad unit TamrielFoundry ### size: [[728,90],[320,50]]  -->
			<div id='div-gpt-ad-821545701545728213-1'>
				<script type='text/javascript'>googletag.display('div-gpt-ad-821545701545728213-1');</script>
			</div>
			<!-- End AdSlot 1 -->
		</header>
		
		<nav id="primary-menu">	
			<?php apoc_primary_menu(); ?>
			<a id="menu-scroll-bottom" class="scroll-bottom" href="#site-footer"><i class="fa fa-long-arrow-down"></i></a>
		</nav><!-- #primary-menu -->
		
	</div><!-- #header-container -->
<!-- End Header -->
	
	<div id="main-container">

