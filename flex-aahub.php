<?php

	/**
	 * Flex Aahub 
	 *
	 * @package ababilitworld/flex-aahub
	 *
	 * @wordpress-plugin
	 * Plugin Name:       Flex Aahub 
	 * Plugin URI:        https://ababilithub.com/wp-plugin/flex-aahub
	 * Description:       The Ababilithub Plugins's Aahub Funtionalities, Exclusively By Ababil IT Hub.
	 * Version:           1.0.0
	 * Requires at least: 5.2
	 * Requires PHP:      7.4
	 * WC requires at least: 3.0.9
	 * WC tested up to:   6.5
	 * Author:            Ababil IT Hub
	 * Author URI:        https://ababilithub.com/
	 * Author Email:      ababilithub@gmail.com
	 * License:           GPL v3 or later
	 * License URI:       https://www.gnu.org/licenses/gpl-3.0.html
	 * Text Domain:       flex-aahub-by-ababilithub
	 * Domain Path:       /language
	 *
	 * Contributors:
	 *  - Ababil IT World (ababilithub@gmail.com, https://ababilithub.com/)
	 *  - Md Shafiul Alam (cse.shafiul@gmail.com, https://ababilithub.com/)
	 */

    namespace Ababilithub\FlexAahub;

	(defined('ABSPATH') && defined('WPINC')) || die();

	require_once __DIR__ . '/bootstrap.php';
	
	use Ababilithub\{
		FlexAahub\Package\Package
	};
	
	$package = Package::getInstance();
		
	register_activation_hook(__FILE__, [$package, 'activate']);
	register_deactivation_hook(__FILE__, [$package, 'deactivate']);