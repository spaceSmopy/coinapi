<?php

/**
 * The plugin bootstrap file
 *
 * This file is read by WordPress to generate the plugin information in the plugin
 * admin area. This file also includes all of the dependencies used by the plugin,
 * registers the activation and deactivation functions, and defines a function
 * that starts the plugin.
 *
 * @link              https://urich.org/
 * @since             1.0.0
 * @package           Coinapi_Integration
 *
 * @wordpress-plugin
 * Plugin Name:       CoinAPI Integration
 * Plugin URI:        https://urich.org/
 * Description:       Pulls data from CoinGecko. Need to activate -> put CoinGecko API key to admin setting on "CoinAPI Integration" page
 * Version:           1.0.0
 * Author:            Urich
 * Author URI:        https://urich.org/
 * License:           GPL-2.0+
 * License URI:       http://www.gnu.org/licenses/gpl-2.0.txt
 * Text Domain:       coinapi-integration
 * Domain Path:       /languages
 */

// If this file is called directly, abort.
if ( ! defined( 'WPINC' ) ) {
	die;
}

/**
 * Currently plugin version.
 * Start at version 1.0.0 and use SemVer - https://semver.org
 * Rename this for your plugin and update it as you release new versions.
 */
define( 'COINAPI_INTEGRATION_VERSION', '1.0.0' );

define( 'PLUGIN_PATH', plugin_dir_path( __FILE__ ) );
define( 'FILENAME_COINSINFO', 'coins_info' );
define( 'FILENAME_COINSDAY', 'coins_history__day_perMin' );
define( 'FILENAME_COINSWEEK', 'coins_history__week_perHour' );
define( 'FILENAME_COINSMONTH', 'coins_history__month_perHour' );
define( 'FILENAME_COINSYEAR', 'coins_history__year_perDay' );
define( 'FILENAME_COINSALLTIME', 'coins_history__allTime_perDay' );

/**
 * The code that runs during plugin activation.
 * This action is documented in includes/class-coinapi-integration-activator.php
 */
function activate_coinapi_integration() {
	require_once plugin_dir_path( __FILE__ ) . 'includes/class-coinapi-integration-activator.php';
	(new Coinapi_Integration_Activator)->activate();

}

/**
 * The code that runs during plugin deactivation.
 * This action is documented in includes/class-coinapi-integration-deactivator.php
 */
function deactivate_coinapi_integration() {
	require_once plugin_dir_path( __FILE__ ) . 'includes/class-coinapi-integration-deactivator.php';
	Coinapi_Integration_Deactivator::deactivate();
}

register_activation_hook( __FILE__, 'activate_coinapi_integration' );
register_deactivation_hook( __FILE__, 'deactivate_coinapi_integration' );

/**
 * The core plugin class that is used to define internationalization,
 * admin-specific hooks, and public-facing site hooks.
 */
require plugin_dir_path( __FILE__ ) . 'includes/class-coinapi-integration.php';

/**
 * Begins execution of the plugin.
 *
 * Since everything within the plugin is registered via hooks,
 * then kicking off the plugin from this point in the file does
 * not affect the page life cycle.
 *
 * @since    1.0.0
 */
function run_coinapi_integration() {

	$plugin = new Coinapi_Integration();
	$plugin->run();

}
add_action('init', 'run_coinapi_integration');
