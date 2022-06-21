<?php

/**
 * The file that defines the core plugin class
 *
 * A class definition that includes attributes and functions used across both the
 * public-facing side of the site and the admin area.
 *
 * @link       https://urich.org/
 * @since      1.0.0
 *
 * @package    Coinapi_Integration
 * @subpackage Coinapi_Integration/includes
 */

/**
 * The core plugin class.
 *
 *
 * Also maintains the unique identifier of this plugin as well as the current
 * version of the plugin.
 *
 * @since      1.0.0
 * @package    Coinapi_Integration
 * @subpackage Coinapi_Integration/includes
 * @author     Urich <info@urich.org>
 */
class Coinapi_Integration {

	/**
	 * The loader that's responsible for maintaining and registering all hooks that power
	 * the plugin.
	 *
	 * @since    1.0.0
	 * @access   protected
	 * @var      Coinapi_Integration_Loader    $loader    Maintains and registers all hooks for the plugin.
	 */
	protected $loader;

	/**
	 * The unique identifier of this plugin.
	 *
	 * @since    1.0.0
	 * @access   protected
	 * @var      string    $plugin_name    The string used to uniquely identify this plugin.
	 */
	protected $plugin_name;

	/**
	 * The current version of the plugin.
	 *
	 * @since    1.0.0
	 * @access   protected
	 * @var      string    $version    The current version of the plugin.
	 */
	protected $version;

	/**
	 * Define the core functionality of the plugin.
	 *
	 * Set the plugin name and the plugin version that can be used throughout the plugin.
	 * Load the dependencies, define the locale, and set the hooks for the admin area and
	 * the public-facing side of the site.
	 *
	 * @since    1.0.0
	 */
	public function __construct() {
        if ( defined( 'COINAPI_INTEGRATION_VERSION' ) ) {
			$this->version = COINAPI_INTEGRATION_VERSION;
		} else {
			$this->version = '1.0.0';
		}
		$this->plugin_name = 'coinapi-integration';

		$this->load_dependencies();

	}

	/**
	 * Load the required dependencies for this plugin.
	 *
	 * Include the following files that make up the plugin:
	 *
	 * - Coinapi_Integration_Loader. Orchestrates the hooks of the plugin.
	 * - Coinapi_Integration_Admin. Defines all hooks for the admin area.
	 * - Coinapi_Integration_Routes. Defines all hooks for the rest api routes.
	 * - Coinapi_Integration_Cron. Defines all hooks for the cron schedules.
	 *
	 * Create an instance of the loader which will be used to register the hooks
	 * with WordPress.
	 *
	 * @since    1.0.0
	 * @access   private
	 */
	private function load_dependencies() {

		/**
		 * The class responsible for orchestrating the actions and filters of the
		 * core plugin.
		 */
		require_once plugin_dir_path( dirname( __FILE__ ) ) . 'includes/class-coinapi-integration-loader.php';

		/**
		 * The class responsible for defining all actions that occur in the admin area.
		 */
		require_once plugin_dir_path( dirname( __FILE__ ) ) . 'admin/class-coinapi-integration-admin.php';

		$this->loader = new Coinapi_Integration_Loader();

        /**
         * The class responsible for plugin admin settings.
         */
        require_once plugin_dir_path( dirname( __FILE__ ) ) . 'admin/partials/class-coinapi-integration-admin-settings.php';
        (new Mynewsdesk_Qarea_Admin_Settings)->activate();

        /**
         * The class responsible for new routes.
         */
        require_once plugin_dir_path( dirname( __FILE__ ) ) . 'includes/class-coinapi-integration-routes.php';
        (new Coinapi_Integration_Routes)->activate();

        /**
         * The class responsible for new cron schedules.
         */
        require_once plugin_dir_path( dirname( __FILE__ ) ) . 'includes/class-coinapi-integration-сron_schedules.php';
        (new Coinapi_Integration_Cron)->activate();


    }


	/**
	 * Run the loader to execute all of the hooks with WordPress.
	 *
	 * @since    1.0.0
	 */
	public function run() {
		$this->loader->run();
	}

	/**
	 * The name of the plugin used to uniquely identify it within the context of
	 * WordPress and to define internationalization functionality.
	 *
	 * @since     1.0.0
	 * @return    string    The name of the plugin.
	 */
	public function get_plugin_name() {
		return $this->plugin_name;
	}

	/**
	 * The reference to the class that orchestrates the hooks with the plugin.
	 *
	 * @since     1.0.0
	 * @return    Coinapi_Integration_Loader    Orchestrates the hooks of the plugin.
	 */
	public function get_loader() {
		return $this->loader;
	}

	/**
	 * Retrieve the version number of the plugin.
	 *
	 * @since     1.0.0
	 * @return    string    The version number of the plugin.
	 */
	public function get_version() {
		return $this->version;
	}

}
