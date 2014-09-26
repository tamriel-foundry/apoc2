<?php 
/**
 * Apocrypha Theme 404 Page
 * Andrew Clayton
 * Version 2.0
 * 9-19-2014
 */
?>

<?php get_header(); ?>
	
	<div id="content" role="main">
		<?php apoc_breadcrumbs(); ?>

		<article id="error-404" class="post">
			<header class="post-header <?php apoc_post_header_class('post'); ?>">
				<h1 class="post-title"><?php apoc_title(); ?></h1></h1>
				<p class="post-byline"><?php apoc_description(); ?></p>		
			</header>
			
			<section class="post-content double-border">
				<blockquote>Turn back, traveller, have ventured into an uninhabitable land. Do not despair, all is not lost. You may still search for what you seek!</blockquote>
			
				<form role="search" method="post" class="search-form" id="search-404" action="<?php echo SITEURL . '/advsearch/'; ?>">
					<fieldset>
						<select name="type" id="search-for">
							<option value="posts">Articles</option>
							<option value="pages">Pages</option>
							<option value="topics">Topics</option>
							<option value="members">Members</option>
							<option value="groups">Guilds</option>
						</select>
						<input class="search-text" type="text" name="s" value="Search Tamriel Foundry" onfocus="if(this.value==this.defaultValue)this.value='';" onblur="if(this.value=='')this.value=this.defaultValue;"/>
						<button type="submit" class="submit"><i class="fa fa-search"></i>Find it!</button>
					</fieldset>
				</form>


			</section>	
		</article>
	</div>

	<?php apoc_primary_sidebar(); ?>

<?php get_footer(); ?>