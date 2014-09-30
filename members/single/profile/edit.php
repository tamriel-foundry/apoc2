<?php 
/**
 * Apocrypha Theme Edit Profile Component
 * Andrew Clayton
 * Version 2.0
 * 9-30-2014
 */

// Load the edit profile class
$user = new Edit_Profile( bp_displayed_user_id() );

?>

<?php get_header(); ?>

	<div id="content" role="main">
		<?php apoc_breadcrumbs(); ?>

		<?php locate_template( array( 'members/single/member-header.php' ), true ); ?>	

		<nav class="reply-header" id="subnav" role="navigation">
			<ul id="profile-tabs" class="tabs" role="navigation">
				<?php bp_get_options_nav(); ?>
			</ul>
		</nav><!-- #subnav -->

		<div id="user-profile" role="main">
			<form method="post" id="edit-profile-form" action="<?php echo apoc()->url; ?>">

				<?php // Character Information ?>
				<fieldset>

					<div class="instructions">
						<h3 class="double-border">Character Information</h3>
						<ul>
							<li>Share some information about your main character in The Elder Scrolls Online.</li>
							<li>These fields are displayed as part of your publicly visible character sheet on your user profile.</li>
						</ul>
					</div>

					<ol id="edit-character-info">

						<li>
							<label for="server"><i class="fa fa-globe fa-fw"></i>Megaserver:</label>
							<select name="server" id="server">
								<option value=""></option>
								<option value="pcna" <?php selected( $user->server	, 'pcna' 	, true ); ?>>PC North America</option>
								<option value="pceu" <?php selected( $user->server	, 'pceu', true ); ?>>PC Europe</option>
								<option value="xbox" <?php selected( $user->server	, 'xbox' 	, true ); ?>>Xbox One</option>
								<option value="ps4" <?php selected( $user->server	, 'ps4' 	, true ); ?>>PlayStation 4</option>
							</select>
						</li>

						<li>
							<label for="first_name"><i class="fa fa-book fa-fw"></i>Character Name:</label>
							<input name="first_name" type="text" id="first-name" value="<?php echo $user->first_name; ?>" size="12" />
							<input name="last_name" type="text" id="last-name" value="<?php echo $user->last_name; ?>" size="12" />
						</li>

						<li>
							<label for="faction"><i class="fa fa-flag fa-fw"></i>Character Alliance:</label>
							<select name="faction" id="faction" onchange="updateRaceDropdown('faction')">
								<option value="">Undecided</option>
								<option value="aldmeri" class="aldmeri" <?php selected( $user->faction	 	, 'aldmeri' 	, true ); ?>>Aldmeri Dominion</option>
								<option value="daggerfall" class="daggerfall" <?php selected( $user->faction	, 'daggerfall' 	, true ); ?>>Daggerfall Covenant</option>
								<option value="ebonheart" class="ebonheart" <?php selected( $user->faction 	, 'ebonheart' 	, true ); ?>>Ebonheart Pact</option>
							</select>
						</li>

						<li>
							<label for="race"><i class="fa fa-man fa-fw"></i>Character Race:</label>
							<select name="race" id="race" onchange="updateRaceDropdown('race')">
								<option value="">Undecided</option>
								<option value="altmer" <?php selected( $user->race	, 'altmer' 	, true ); ?>>Altmer</option>
								<option value="argonian" <?php selected( $user->race, 'argonian', true ); ?>>Argonian</option>
								<option value="bosmer" <?php selected( $user->race	, 'bosmer' 	, true ); ?>>Bosmer</option>
								<option value="breton" <?php selected( $user->race	, 'breton' 	, true ); ?>>Breton</option>
								<option value="dunmer" <?php selected( $user->race	, 'dunmer' 	, true ); ?>>Dunmer</option>
								<option value="imperial" <?php selected( $user->race, 'imperial', true ); ?>>Imperial</option>
								<option value="khajiit" <?php selected( $user->race	, 'khajiit' , true ); ?>>Khajiit</option>
								<option value="nord" <?php selected( $user->race	, 'nord' 	, true ); ?>>Nord</option>
								<option value="orc" <?php selected( $user->race		, 'orc'		, true ); ?>>Orc</option>
								<option value="redguard" <?php selected( $user->race, 'redguard', true ); ?>>Redguard</option>
							</select>
						</li>

						<li>
							<label for="playerclass"><i class="fa fa-gear fa-fw"></i>Character Class:</label>
							<select name="playerclass" id="playerclass">
								<option value="">Undecided</option>
								<option value="dragonknight" <?php selected( $user->class 	, 'dragonknight' , true ); ?>>Dragonknight</option>
								<option value="nightblade" <?php selected( $user->class 	, 'nightblade' 	, true ); ?>>Nightblade</option>
								<option value="sorcerer" <?php selected( $user->class 		, 'sorcerer' 	, true ); ?>>Sorcerer</option>
								<option value="templar" <?php selected( $user->class 		, 'templar' 	, true ); ?>>Templar</option>
							</select>
						</li>

						<li>
							<label for="playerclass"><i class="fa fa-shield fa-fw"></i>Preferred Role:</label>
							<select name="prefrole" id="prefrole">
								<option value="">Any</option>
								<option value="tank" <?php selected( $user->prefrole 	, 'tank' 	, true ); ?>>Tank</option>
								<option value="healer" <?php selected( $user->prefrole	, 'healer' 	, true ); ?>>Healer</option>
								<option value="damage" <?php selected( $user->prefrole 	, 'damage' 	, true ); ?>>Damage</option>
							</select>
						</li>

						<li>
							<label for="guild"><i class="fa fa-group fa-fw"></i>Primary Guild:</label>
							<select name="guild" id="guild">
								<option value="">No Guild</option>
								<?php if ( bp_has_groups( array( 'type' => 'alphabetical', 'user_id' => $user_id ) ) ) : while ( bp_groups() ) : bp_the_group(); ?>
									<?php if ( group_is_guild( bp_get_group_id() ) ) : ?>
									<option value="<?php bp_group_name(); ?>" <?php selected( $user->guild , bp_get_group_name() , true ); ?>><?php bp_group_name();?></option>
								<?php endif; endwhile; endif; ?>
							</select>
						</li>

					</ol>
				</fieldset>


				<?php // Biography and Signature ?>
				<fieldset>
					<div class="instructions">	
						<h3 class="double-border">Biography and Signature</h3>
						<ul>
							<li>Your biography is a detailed description of yourself as a gamer and individual. It can describe your character's backstory or personality, or your personal tastes in gaming.</li>
							<li>Your signature is a shorter tagline which is displayed beneath forum posts and article comments. Signatures must be shorter than 6 lines of text and may contain images less than 150 pixels in height. Signatures exceeding these requirements will be truncated and not fully displayed.</li>
						</ul>
					</div>

					<div id="edit-bio-sig" class="form-full">
						<label for="description"><i class="fa fa-pencil fa-fw"></i>Personal or Character Biography:</label>
						<?php wp_editor( htmlspecialchars_decode( $user->bio , ENT_QUOTES ) , 'description' , array(
							'media_buttons' => false,
							'wpautop'		=> false,
							'editor_class'  => 'description',
							'quicktags'		=> true,
							'teeny'			=> false,
						) ); ?>

						<label for="signature"><i class="fa fa-quote-left fa-fw"></i>Forum Signature:</label>
						<?php wp_editor( htmlspecialchars_decode( $user->sig , ENT_QUOTES ), 'signature', array(
							'media_buttons' => false,
							'wpautop'		=> false,
							'editor_class'  => 'signature',
							'quicktags'		=> true,
							'teeny'			=> false,
						) ); ?>
					</div>
				</fieldset>

				<?php // Contact Methods ?>
				<fieldset>
					<div class="instructions">	
						<h3 class="double-border">Contact Methods</h3>
						<ul>
							<li>Specify some ways that you can be reached throughout the social gaming community.</li>
							<li>These contact methods will be listed publicly on your user profile.</li>
						</ul>
					</div>

					<ol id="contact-methods">
						<li>
							<label for="esoacct"><i class="fa fa-user fa-fw"></i>ESO Account:</label>
							<span class="contact-url-prefix">@</span>
							<input type="text" name="esoacct" value="<?php if ( isset( $user->contacts['esoacct'] ) ) echo $user->contacts['esoacct']; ?>" size="48" />
						</li>

						<li>
							<label for="user_url"><i class="fa fa-globe fa-fw"></i>Your Website:</label>
							<input type="url" name="user_url" value="<?php if ( isset( $user->contacts['user_url'] ) ) echo $user->contacts['user_url']; ?>" size="50" />
						</li>
						
						<li>
							<label for="facebook"><i class="fa fa-facebook fa-fw"></i>Facebook:</label>
							<span class="contact-url-prefix">facebook.com/</span>
							<input type="text" name="facebook" value="<?php if ( isset( $user->contacts['facebook'] ) ) echo $user->contacts['facebook']; ?>" size="36">
						</li>
						
						<li class="text">
							<label for="twitter"><i class="fa fa-twitter fa-fw"></i>Twitter:</label>
							<span class="contact-url-prefix">twitter.com/</span>
							<input type="text" name="twitter" value="<?php if ( isset( $user->contacts['twitter'] ) ) echo $user->contacts['twitter']; ?>" size="39">
						</li>
						
						<li class="text">
							<label for="gplus"><i class="fa fa-google-plus fa-fw"></i>Google+:</label>
							<span class="contact-url-prefix">plus.google.com/</span>
							<input type="text" name="gplus" value="<?php if ( isset( $user->contacts['gplus'] ) ) echo $user->contacts['gplus']; ?>" size="34">
						</li>
						
						<li class="text">
							<label for="youtube"><i class="fa fa-youtube fa-fw"></i>YouTube:</label>
							<span class="contact-url-prefix">youtube.com/</span>
							<input type="text" name="youtube" value="<?php if ( isset( $user->contacts['youtube'] ) ) echo $user->contacts['youtube']; ?>" size="37">
						</li>
						
						<li class="text">
							<label for="steam"><i class="fa fa-steam fa-fw"></i>Steam:</label>
							<span class="contact-url-prefix">steamcommunity.com/id/</span>
							<input type="text" name="steam" value="<?php if ( isset( $user->contacts['steam'] ) ) echo $user->contacts['steam']; ?>" size="26">
						</li>
						
						<li class="text">
							<label for="twitch"><i class="fa fa-twitch fa-fw"></i>TwitchTV:</label>
							<span class="contact-url-prefix">twitch.tv/</span>
							<input type="text" name="twitch" value="<?php if ( isset( $user->contacts['twitch'] ) ) echo $user->contacts['twitch']; ?>" size="41">
						</li>
					
						<li class="text">
							<i class="fa fa-circle-o fa-fw"></i><label for="oforums">ESO Forums:</label>
							<span class="contact-url-prefix">forums.elderscrollsonline.com/profile/</span>
							<input type="text" name="oforums" value="<?php if ( isset( $user->contacts['oforums'] ) ) echo $user->contacts['oforums']; ?>" size="14">
						</li>
					</ol>
				</fieldset>

				<?php // Allow plugins to link in
				do_action( 'show_user_profile' , bp_displayed_user_id() );
				do_action( 'edit_user_profile' , bp_displayed_user_id() );
				?>

				<?php // Submit and security ?>
				<fieldset>
					<div class="hidden">
						<input name="action" type="hidden" id="action" value="update-user" />
						<?php wp_nonce_field( 'update-user' , 'edit_user_nonce' ); ?>
					</div>
					<div class="form-right">
						<button type="submit" class="submit"><i class="fa fa-pencil"></i>Update Profile</button>
					</div>
				</fieldset>	
			</form>
		</div>

	</div><!-- #content -->
<?php get_footer(); ?>