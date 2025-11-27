<?php

/**
 * The admin-specific functionality of the plugin.
 *
 * @package    ContactForm7GHLIntegration
 * @subpackage admin/partials
 * @author     Robert Ochoa <ochoa.robert1@gmail.com>
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit; // Exit if accessed directly.
}
?>
<div class="wrap">
	<h1><?php echo esc_html( get_admin_page_title() ); ?></h1>

	<form id="contactForm7GHL" method="post">
		<table class="form-table">
			<tr valign="top">
				<th scope="row">
					<?php esc_html_e( 'API Key', WPCF7_GHL_TEXT_DOMAIN ); ?>
				</th>
				<td>
					<input type="text" name="wpcf7_ghl_integration_apikey" size="90" value="<?php echo esc_attr( get_option( 'wpcf7_ghl_integration_apikey' ) ); ?>" />
					<br/>
					<small><?php esc_html_e( 'Enter GoHighLevel API Key. You can find it in your GoHighLevel account settings.', WPCF7_GHL_TEXT_DOMAIN ); ?></small>
				</td>
			</tr>
		</table>
		<p class="submit"><input type="submit" name="submit" id="submit" class="button button-primary" value="<?php esc_html_e( 'Save Changes', WPCF7_GHL_TEXT_DOMAIN ); ?>"></p>
	</form>
</div>
