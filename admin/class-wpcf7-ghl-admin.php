<?php

/**
 * The admin-specific functionality of the plugin.
 *
 * @package    ContactForm7GHLIntegration
 * @subpackage admin
 * @author     Robert Ochoa <ochoa.robert1@gmail.com>
 */

if (! defined('ABSPATH') ) {
	exit; // Exit if accessed directly.
}

if (! class_exists('WPCF7_GHL_Integration_Admin') ) {
	/**
	 * WPCF7_GHL_Integration_Admin
	 */
	class WPCF7_GHL_Integration_Admin extends WPCF7_GHL_Integration {

		/**
		 * Method __construct
		 *
		 * @return void
		 */
		public function __construct() {
			add_action('admin_menu', array( $this, 'add_admin_menu' ));
			add_action('wp_ajax_wpcf7ghl_admin_form_submission', array( $this, 'admin_submit' ));
			add_filter('wpcf7_editor_panels', array( $this, 'show_panel' ));
			add_action('admin_init', array( $this, 'save_field_mappings' ));
		}

		/**
		 * Method add_admin_menu
		 *
		 * @return void
		 */
		public function add_admin_menu() {
			add_submenu_page(
				'wpcf7',
				esc_html__('GHL Integration', WPCF7_GHL_TEXT_DOMAIN),
				esc_html__('GHL Integration', WPCF7_GHL_TEXT_DOMAIN),
				'manage_options',
				'wpcf7-ghl-integration',
				array( $this, 'add_option_page' )
			);
		}

		/**
		 * Method add_option_page
		 *
		 * @return void
		 */
		public function add_option_page() {
			if (! current_user_can('manage_options') ) {
				return;
			} else {
				include_once __DIR__ . '/partials/wpcf7-ghl-integration-admin-display.php';
			}
		}

		/**
		 * Method admin_submit
		 *
		 * @return void
		 */
		public function admin_submit() {
			$error_array = array(
				'title' => esc_html__('Error', WPCF7_GHL_TEXT_DOMAIN),
				'accept_btn' => esc_html__('Accept', WPCF7_GHL_TEXT_DOMAIN),
			);

			$success_array = array(
				'title' => esc_html__('Success', WPCF7_GHL_TEXT_DOMAIN),
				'accept_btn' => esc_html__('Accept', WPCF7_GHL_TEXT_DOMAIN),
			);

			if (! check_ajax_referer('wpcf7ghl_nonce', 'nonce', false) ) {
				$error_array['message'] = esc_html__('Invalid nonce', WPCF7_GHL_TEXT_DOMAIN);
				wp_send_json_error($error_array, 400);
			}

			if (! isset($_POST['data']) ) {
				$error_array['message'] = esc_html__('No data provided', WPCF7_GHL_TEXT_DOMAIN);
				wp_send_json_error($error_array, 400);
			}

			parse_str(wp_unslash($_POST['data']), $data);

			if (empty($data) ) {
				$error_array['message'] = esc_html__('No data provided', WPCF7_GHL_TEXT_DOMAIN);
				wp_send_json_error($error_array, 400);
			}

			if (! isset($data['wpcf7_ghl_integration_apikey']) ) {
				$error_array['message'] = esc_html__('No data provided', WPCF7_GHL_TEXT_DOMAIN);
				wp_send_json_error($error_array, 400);
			}

			$apikey = sanitize_text_field($data['wpcf7_ghl_integration_apikey']);
			update_option('wpcf7_ghl_integration_apikey', $apikey);

			$success_array['message'] = esc_html__('API Key Saved successfully', WPCF7_GHL_TEXT_DOMAIN);
			wp_send_json_success($success_array['message'], 200);

			wp_die();
		}

		/**
		 * Method show_panel
		 *
		 * @param array $panels Panel Arrays.
		 *
		 * @return array
		 */
		public function show_panel( $panels ) {
			$panels['wpcf7-ghl-integration-panel'] = array(
				'title' => esc_html__('GHL Integration', WPCF7_GHL_TEXT_DOMAIN),
				'callback' => array( $this, 'show_panel_content' ),
			);

			return $panels;
		}

		/**
		 * Method show_panel_content
		 *
		 * @param object $post The CF7 post object.
		 * @param array  $args Additional arguments.
		 *
		 * @return void
		 */
		public function show_panel_content( $post, $args = array() ) {
			$apikey = get_option('wpcf7_ghl_integration_apikey', '');
			include_once __DIR__ . '/partials/wpcf7-ghl-integration-admin-display-panel.php';
		}

		/**
		 * Method save_field_mappings
		 *
		 * @return void
		 */
		public function save_field_mappings() {
			if (! isset($_POST['wpcf7_ghl_save']) || ! isset($_POST['wpcf7_ghl_nonce']) || ! wp_verify_nonce($_POST['wpcf7_ghl_nonce'], 'wpcf7_ghl_save_mappings') ) {
				return;
			}

			if ( ! isset($_POST['post_id']) ) {
				return;
			}

			$post_id = intval($_POST['post_id']);
			$cf7_fields = isset($_POST['wpcf7-ghl-cf7-field']) ? array_map('sanitize_text_field', $_POST['wpcf7-ghl-cf7-field']) : array();
			$ghl_fields = isset($_POST['wpcf7-ghl-field']) ? array_map('sanitize_text_field', $_POST['wpcf7-ghl-field']) : array();

			$mappings = array();
			$counter = count($cf7_fields);
			for ($i = 0; $i < $counter; $i++ ) {
				if (! empty($cf7_fields[ $i ]) && ! empty($ghl_fields[ $i ]) ) {
					$mappings[] = array(
						'cf7_field' => $cf7_fields[ $i ],
						'ghl_field' => $ghl_fields[ $i ],
					);
				}
			}

			update_post_meta($post_id, '_wpcf7_ghl_field_mappings', $mappings);
			wp_redirect(add_query_arg('message', 'saved', wp_get_referer()));
			exit;
		}
	}

	new WPCF7_GHL_Integration_Admin();
}
