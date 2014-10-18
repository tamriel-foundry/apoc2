<?php 
/**
 * Apocrypha Theme Change Avatar Component
 * Andrew Clayton
 * Version 2.0
 * 10-18-2014
 */
?>

<div id="user-profile">
	<form action="<?php echo apoc()->url; ?>" method="post" id="avatar-upload-form" class="standard-form" enctype="multipart/form-data">

		<div class="instructions">
			<h3 class="double-border bottom">Upload Avatar</h3>
			<ul>
				<li>Upload an image to use as your personal avatar, an image that can be used to identify you throughout the site.</li>
				<li>Avatars can be .png, .jpg, or .jpeg files, and are automatically resized to 200 pixel square dimensions after cropping.</li>
				<li>If you'd like to delete your current avatar without uploading a new one, please use the delete avatar button.</li>
			</ul>
		</div>

		<?php // Upload a new image
		if ( 'upload-image' == bp_get_avatar_admin_step() ) : ?>
		<fieldset>
			<div id="avatar-upload" class="form-left">
				<input type="file" name="file" id="file" />

				<?php if ( bp_get_user_has_avatar() ) : ?>
				<a class="button edit" href="<?php bp_avatar_delete_link(); ?>" title="<?php esc_attr_e( 'Delete Avatar', 'buddypress' ); ?>"><i class="fa fa-remove"></i>Delete Current Avatar</a>
				<?php endif; ?>
			</div>

			<div class="form-right">
				<button type="submit" name="upload" id="upload"><i class="fa fa-cloud-upload"></i>Upload New Image</button>
			</div>

			<div class="hidden">
				<input type="hidden" name="action" id="action" value="bp_avatar_upload" />
				<?php wp_nonce_field( 'bp_avatar_upload' ); ?>
			</div>
		</fieldset>


		<?php // Crop uploaded image
		elseif ( 'crop-image' == bp_get_avatar_admin_step() ) : ?>
		<fieldset>
			<div class="form-left">
				<h3>Original Image</h3>
				<img src="<?php bp_avatar_to_crop(); ?>" id="avatar-to-crop" class="avatar"/>
			</div>

			<div class="form-right">
				<h3>Cropped Preview</h3>
				<div id="avatar-crop-pane">
					<img src="<?php bp_avatar_to_crop(); ?>" id="avatar-crop-preview" class="avatar" alt="<?php esc_attr_e( 'Avatar preview', 'buddypress' ); ?>" />
				</div>
			</div>

			<div class="form-full">
				<button type="submit" name="avatar-crop-submit" id="avatar-crop-submit"><i class="fa fa-crop"></i>Crop Image</button>
			</div>

			<div class="hidden">
				<input type="hidden" name="image_src" id="image_src" value="<?php bp_avatar_to_crop_src(); ?>" />
				<input type="hidden" id="x" name="x" />
				<input type="hidden" id="y" name="y" />
				<input type="hidden" id="w" name="w" />
				<input type="hidden" id="h" name="h" />
			<?php wp_nonce_field( 'bp_avatar_cropstore' ); ?>
			</div>
		</fieldset>
		<?php endif; ?>

	</form>
</div>
