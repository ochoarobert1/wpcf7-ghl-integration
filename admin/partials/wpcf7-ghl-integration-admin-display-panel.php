<?php

/**
 * The admin-specific functionality of the plugin.
 *
 * @package    ContactForm7GHLIntegration
 * @subpackage admin/partials
 * @author     Robert Ochoa <ochoa.robert1@gmail.com>
 */

if (! defined('ABSPATH') ) {
	exit; // Exit if accessed directly.
}
?>
<div class="wrapper">
	<h2><?php esc_html_e('GHL Integration', WPCF7_GHL_TEXT_DOMAIN); ?></h2>
	<p><?php esc_html_e('Please map all fields you need to register on GoHighLevel', WPCF7_GHL_TEXT_DOMAIN); ?></p>
	<fieldset>
		<legend>In the following fields, you can use these mail-tags:</legend>
		<div class="wpcf7-ghl-main-fields">
			<?php $post->suggest_mail_tags('wpcf7-ghl-integration-panel'); ?>
		</div>
	</fieldset>
	<form method="post" action="">
		<?php wp_nonce_field('wpcf7_ghl_save_mappings', 'wpcf7_ghl_nonce'); ?>
		<input type="hidden" name="post_id" value="<?php echo esc_attr($post->id()); ?>" />
		<div id="wpcf7GHLMapper" class="wpcf7-ghl-form-mapper">
			<div id="wpcf7GHLRepeater" class="repeater">
				<?php
				$saved_mappings = get_post_meta($post->id(), '_wpcf7_ghl_field_mappings', true);
				if (! empty($saved_mappings) ) {
					foreach ($saved_mappings as $index => $mapping ) {
						?>
						<div class="form-wrapper" data-index="<?php echo esc_attr($index); ?>">
							<div class="form-group">
								<input name="wpcf7-ghl-cf7-field[]" type="text" value="<?php echo esc_attr($mapping['cf7_field']); ?>" placeholder="e.g., [your-name]" />
								<label>Contact Form 7 Field</label>
							</div>
							<div class="form-group">
								<input name="wpcf7-ghl-field[]" type="text" value="<?php echo esc_attr($mapping['ghl_field']); ?>" placeholder="e.g., firstName" />
								<label>GoHighLevel Field</label>
							</div>
							<div class="form-group-buttons">
								<button type="button" class="remove-field button button-link-delete">Remove</button>
							</div>
						</div>
						<?php
					}
				}
				?>
			</div>
			<div class="repeater-controls">
				<button type="button" id="addField" class="button button-secondary"><?php esc_html_e('Add Field Mapping', WPCF7_GHL_TEXT_DOMAIN); ?></button>
			</div>
		</div>
		<p class="submit">
			<input type="submit" name="wpcf7_ghl_save" class="button button-primary" value="<?php esc_attr_e('Save Field Mappings', WPCF7_GHL_TEXT_DOMAIN); ?>" />
		</p>
	</form>
</div>