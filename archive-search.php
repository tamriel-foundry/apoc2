<?php 
/**
 * Apocrypha Theme Advanced Search Template
 * Andrew Clayton
 * Version 2.0
 * 9-24-2014
 */

// Get the search class
$search = new Apoc_Search;

// Get the search context
$context = $search->context;
?>

<?php get_header(); ?>
	
	<div id="content" role="main">
		<?php apoc_breadcrumbs(); ?>

		<header class="post-header <?php apoc_post_header_class('post'); ?>">
			<h1 class="post-title"><?php apoc_title(); ?></h1>
			<p class="post-byline"><?php apoc_description(); ?></p>
		</header>

		<form id="advanced-search" class="double-border" action="<?php echo SITEURL . '/advsearch/'; ?>" method="post">

			<div class="instructions">
			<?php if ( $search->submitted ) : ?>
				<h2 style="margin:0;"><?php echo $search->notice; ?></h2>
			<?php else : ?>
				<h3 class="double-border">Sitewide Search Form</h3>
				<ul>
					<li>You can use this form to search for articles, pages, forum topics, members, or guilds.</li>
					<li>Set options to narrow down your search to just what you are seeking.</li>
				</ul>	
			<?php endif; ?>		
			</div>

			<?php // Common search options ?>
			<fieldset>
				<div class="form-left">
					<label for="type"><i class="fa fa-bookmark fa-fw"></i>Search In: </label>
					<select name="type" id="search-for">
						<option value="posts" <?php selected( $context , 'posts' ); ?>>Articles</option>
						<option value="pages" <?php selected( $context , 'pages' ); ?>>Pages</option>
						<option value="topics" <?php selected( $context , 'topics' ); ?>>Topics</option>
						<option value="members" <?php selected( $context , 'members' ); ?>>Members</option>
						<option value="groups" <?php selected( $context , 'groups' ); ?>>Guilds</option>
					</select>
				</div>

				<div class="form-full">
					<label for="s"><i class="fa fa-quote-left fa-fw"></i>Search For: </label>
					<input type="text" name="s" id="s" size="50" value="<?php echo $search->search; ?>">
				</div>
			</fieldset>


			<?php // Articles options ?>
			<fieldset id="adv-search-posts" class="adv-search-fields <?php if ( $context == 'posts' || $context == '' ) echo 'active'; ?>">
				<div class="form-left">
					<label for="search-author"><i class="fa fa-user fa-fw"></i>By Author: </label>
					<?php wp_dropdown_users( $args = array(
						'show_option_none'		=> 'Any',
						'orderby'				=> 'display_name',
						'order'					=> 'ASC',
						'show'					=> 'display_name',
						'echo'					=> true,
						'name'					=> 'author',
						'who'					=> 'authors',
						'selected'				=> isset( $search->author ) ? $search->author : NULL,				
					) ); ?>
				</div>
				
				<div class="form-right">
					<label for="search-category"><i class="fa fa-tag fa-fw"></i>In Category: </label>
					<?php wp_dropdown_categories( $args = array(
						'show_option_none'		=> 'Any',
						'orderby'				=> 'NAME',
						'order'					=> 'ASC',
						'exclude'				=> get_cat_ID( 'entropy rising' ) . ',' . get_cat_ID( 'guild news' ),
						'echo'					=> true,
						'name'					=> 'cat',
						'selected'				=> isset( $search->cat ) ? $search->cat : NULL,
					) ); ?>
				</div>
			</fieldset>

			<?php // Topics options ?>
			<fieldset id="adv-search-topics" class="adv-search-fields <?php if ( $context == 'topics' ) echo 'active'; ?>">
				<div class="form-left">
					<label for="inforum"><i class="fa fa-list fa-fw"></i>In Forum: </label>
					<?php bbp_dropdown( $args = array(
						'post_type'				=> 'forum',
						'show_none'          	=> 'Any Forum',
						'selected'				=> isset( $search->forum ) ? $search->forum : NULL,
						'select_id'          	=> 'inforum',
					) ); ?>
				</div>
			</fieldset>

			<?php // Members and Groups options ?>
			<fieldset id="adv-search-members" class="adv-search-fields <?php if ( $context == 'members' || $context == 'groups' ) echo 'active'; ?>">
				<div class="form-left">
					<label for="faction"><i class="fa fa-flag fa-fw"></i>In Alliance: </label>
					<select name="faction">
						<option value="">Any Alliance</option>
						<option value="aldmeri" class="aldmeri" <?php selected( $search->faction , 'aldmeri' , true ); ?>>Aldmeri Dominion</option>
						<option value="daggerfall" class="daggerfall" <?php selected( $search->faction , 'daggerfall' , true ); ?>>Daggerfall Covenant</option>
						<option value="ebonheart" class="ebonheart" <?php selected( $search->faction , 'ebonheart' , true ); ?>>Ebonheart Pact</option>
					</select>
				</div>
			</fieldset>

			<?php // Search submission ?>
			<fieldset>
				<div class="form-right">
					<button type="submit" id="submit"><i class="fa fa-search"></i>Submit Search</button>
				</div>
				
				<div class="hidden">
					<input type="hidden" name="submitted" value="true" />
					<?php wp_nonce_field( 'apoc_adv_search' ); ?>
				</div>			
			</fieldset>
		</form>


		<div id="search-results">

			<?php // Posts and Pages
			if ( $context == 'posts' || $context == 'pages' ) : ?>
			<div id="posts" >		
				<?php if ( $search->query->have_posts() ) : 
					while ( $search->query->have_posts() ) : $search->query->the_post();
						apoc_single_post();
					endwhile; ?>
				<?php else: ?>
					<p class="warning">Sorry, but no <?php echo $context; ?> were found that match this search.</p>
				<?php endif; ?>
			</div>

			<?php // Forum Topics
			elseif ( $context == 'topics' ) : ?>
			<div id="forums">
				<?php if ( bbp_has_topics( $search->query ) ) :
					bbp_get_template_part( 'loop', 'topics' );
				else : ?>
					<p class="warning">Sorry, but no <?php echo $context; ?> were found that match this search.</p>
				<?php endif; ?>
			</div>

			<?php // Members and Groups
			elseif ( $context == 'members' ) : ?>
			<header class="reply-header" id="subnav" role="navigation">
				<div class="directory-member">Member</div>
				<div class="directory-content">Current Status
			</header><!-- #subnav -->
			<div id="members-dir-list" class="members dir-list">
				<?php locate_template( array( 'members/members-loop.php' ), true ); ?>
			</div><!-- #members-dir-list -->	


			<?php // Groups
			elseif ( $context == 'groups' ) : ?>
			<header class="reply-header" id="subnav" role="navigation">
				<div class="directory-member">Guild</div>
				<div class="directory-content">Description
			</header><!-- #subnav -->
			<div id="groups-dir-list" class="groups dir-list">
				<?php locate_template( array( 'groups/groups-loop.php' ), true ); ?>
			</div><!-- #groups-dir-list -->	
			<?php endif; ?>
		</div>
	</div>

	<?php apoc_primary_sidebar(); ?>
<?php get_footer(); ?>