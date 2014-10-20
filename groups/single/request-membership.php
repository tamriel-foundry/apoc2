<?php 
/**
 * Apocrypha Theme Group Membership Request Component
 * Andrew Clayton
 * Version 2.0
 * 10-18-2014
 */
?>

<nav class="reply-header" id="subnav">
	<ul id="profile-tabs" class="tabs" role="navigation">
		<li class="current"><a href="#request-membership-form">Request Membership</a></li>
	</ul>
</nav><!-- #subnav -->

<?php if ( !bp_group_has_requested_membership() ) : ?>
<form action="<?php bp_group_form_action('request-membership'); ?>" method="post" name="request-membership-form" id="request-membership-form">
		
	<div class="instructions">
		<h3 class="double-border bottom">Request Membership to Join this Guild!</h3>
		<ul>
			<li>You can request guild membership using the form below.</li>
			<li>Please leave any information which you believe to be useful in describing your request to join this guild.</li>
			<li>Whether or not your request is accepted will depend on the recruitment policies of the specific guild in question.</li>
			<li>As a general rule of thumb, the more relevant information you provide, the higher the likelihood your request will be approved.</li>
		</ul>
	</div>
	
	<fieldset>
		<div class="form-full">
			<p>Application Request:</p>
			
			<?php // Load the TinyMCE Editor
			wp_editor( '' , 'group-request-membership-comments', array(
				'media_buttons' => false,
				'wpautop'		=> true,
				'editor_class'  => 'group-request-membership-comments',
				'quicktags'		=> false,
				'teeny'			=> true,
			) );?>
		</div>
	
		<div class="form-right">
			<button type="submit" name="group-request-send" id="group-request-send"><i class="fa fa-envelope fa-fw"></i>Send Request</button>
		</div>
		
		<div class="hidden">
			<?php wp_nonce_field( 'groups_request_membership' ); ?>		
		</div>
	</fieldset>
</form>

<?php else : ?>
	<p class="warning">You have already applied to join this guild!</p>
<?php endif; ?>