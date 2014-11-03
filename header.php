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
</head>

<body class="<?php apoc_body_class(); ?>">
	
	<div id="header-container">	
	
		<nav id="admin-bar" role="navigation">
			<?php apoc_admin_bar(); ?>
		</nav>
	
		<header id="site-header" role="banner">	
			<a id="site-title" href="<?php echo SITEURL; ?>" title="<?php echo SITENAME; ?>"></a>
			<div id="primary-leaderboard-728">
				Primary Ad: Leaderboard - 728x90
			</div>
		</header>
		
		<nav id="primary-menu">	
			<?php apoc_primary_menu(); ?>
			<a id="menu-scroll-bottom" class="scroll-bottom" href="#site-footer"><i class="fa fa-long-arrow-down"></i></a>
		</nav><!-- #primary-menu -->
		
	</div><!-- #header-container -->
<!-- End Header -->
	
	<div id="main-container">

