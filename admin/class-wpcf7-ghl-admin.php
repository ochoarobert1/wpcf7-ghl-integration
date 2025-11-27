<?php

/**
 * The admin-specific functionality of the plugin.
 *
 * @package    ContactForm7GHLIntegration
 * @subpackage admin
 * @author     Robert Ochoa <ochoa.robert1@gmail.com>
 */

if (! defined('ABSPATH')) {
	exit; // Exit if accessed directly.
}

if (! class_exists('WPCF7_GHL_Integration_Admin')) {
	class WPCF7_GHL_Integration_Admin extends WPCF7_GHL_Integration
	{

		/**
		 * Method __construct
		 *
		 * @return void
		 */
		public function __construct()
		{
			add_action('admin_menu', array($this, 'add_admin_menu'));
			add_action('wp_ajax_wpcf7ghl_admin_form_submission', array($this, 'admin_submit'));
		}

		/**
		 * Method add_admin_menu
		 *
		 * @return void
		 */
		public function add_admin_menu()
		{
			add_submenu_page(
				'wpcf7',
				'GHL Integration',
				'GHL Integration',
				'manage_options',
				'wpcf7-ghl-integration',
				array($this, 'add_option_page')
			);
		}

		/**
		 * Method add_option_page
		 *
		 * @return void
		 */
		public function add_option_page()
		{
			if (! current_user_can('manage_options')) {
				return;
			} else {
				include_once __DIR__ . '/partials/wpcf7-ghl-integration-admin-display.php';
			}
		}

		public function admin_submit()
		{
			if (! check_ajax_referer('wpcf7ghl_nonce', 'nonce', false)) {
				wp_send_json_error(esc_html__('Invalid nonce', 'wpcf7-ghl-integration'), 400);
			}
			if (! isset($_POST['data'])) {
				wp_send_json_error(esc_html__('No data provided', 'wpcf7-ghl-integration'), 400);
			}
			parse_str($_POST['data'], $data);
			if (empty($data)) {
				wp_send_json_error(esc_html__('No data provided', 'wpcf7-ghl-integration'), 400);
			}
			if (! isset($data['wpcf7_ghl_integration_apikey'])) {
				wp_send_json_error(esc_html__('No data provided', 'wpcf7-ghl-integration'), 400);
			}

			$apikey = sanitize_text_field($data['wpcf7_ghl_integration_apikey']);
			update_option('wpcf7_ghl_integration_apikey', $apikey);
			wp_send_json_error(esc_html__('API Key Saved successfully', 'wpcf7-ghl-integration'), 200);
		}
	}

	new WPCF7_GHL_Integration_Admin();
}
