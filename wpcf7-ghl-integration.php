<?php

/**
 * Contact Form 7 GHL Integration
 *
 * @package ContactForm7GHLIntegration
 * @author Robert Ochoa
 * @copyright 2025 Robert Ochoa
 * @license GPL-2.0-or-later
 *
 * @wordpress-plugin
 * Plugin Name: Contact Form 7 GHL Integration
 * Plugin URI: https://robertochoaweb.com/casos/wpcf7-ghl-integration/
 * Description: Integrates GoHighLevel API to Contact Forms.
 * Version: 1.0.0
 * Requires at least: 6.0
 * Requires PHP: 8.0
 * Author: Robert Ochoa
 * Author URI: https://robertochoaweb.com/
 * Text Domain: wpcf7-ghl-integration
 * Domain Path: /languages
 * License: GPL v2 or later
 * License URI: http://www.gnu.org/licenses/gpl-2.0.txt
 * Requires Plugins: contact-form-7
 */

if (! defined('ABSPATH')) {
	exit; // Exit if accessed directly.
}

define('WPCF7_GHL_VERSION', '1.0.0');

define('WPCF7_GHL_TEXT_DOMAIN', 'wpcf7-ghl-integration');

define('WPCF7_GHL_PLUGIN', __FILE__);

define('WPCF7_GHL_PLUGIN_BASENAME', plugin_basename(WPCF7_GHL_PLUGIN));

define('WPCF7_GHL_PLUGIN_NAME', trim(dirname(WPCF7_GHL_PLUGIN_BASENAME), '/'));

define('WPCF7_GHL_PLUGIN_DIR', untrailingslashit(dirname(WPCF7_GHL_PLUGIN)));

if (! class_exists('WPCF7_GHL_Integration')) {
	/**
	 * WPCF7_GHL_Integration
	 */
	class WPCF7_GHL_Integration
	{

		/**
		 * Method __construct
		 *
		 * @return void
		 */
		public function __construct()
		{
			add_action('init', array($this, 'load_textdomain'));
			add_action('admin_enqueue_scripts', array($this, 'enqueue_scripts'));
		}

		/**
		 * Method enqueue_scripts
		 *
		 * @return void
		 */
		public function enqueue_scripts()
		{
			wp_enqueue_script(
				'wpcf7-ghl-sweetalert2',
				'https://cdn.jsdelivr.net/npm/sweetalert2@11',
				array('jquery'),
				WPCF7_GHL_VERSION,
				true
			);

			wp_enqueue_script(
				'wpcf7-ghl-admin',
				plugins_url('/admin/js/wpcf7-ghl-integration-admin.js', __FILE__),
				array('jquery', 'wpcf7-ghl-sweetalert2'),
				WPCF7_GHL_VERSION,
				true
			);

			wp_localize_script(
				'wpcf7-ghl-admin',
				'wpcf7ghl',
				array(
					'ajax_url' => admin_url('admin-ajax.php'),
					'nonce' => wp_create_nonce('wpcf7ghl_nonce'),
				)
			);
		}

		/**
		 * Method load_textdomain
		 *
		 * @return void
		 */
		public function load_textdomain()
		{
			load_plugin_textdomain(
				WPCF7_GHL_TEXT_DOMAIN,
				false,
				dirname(plugin_basename(__FILE__)) . '/languages/'
			);
		}
	}

	new WPCF7_GHL_Integration();

	require_once __DIR__ . '/admin/class-wpcf7-ghl-admin.php';
	require_once __DIR__ . '/includes/class-wpcf7-ghl-sender.php';
}
