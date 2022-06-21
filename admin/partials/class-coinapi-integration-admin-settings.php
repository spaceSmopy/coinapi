<?php

/**
 * The admin-specific functionality of the plugin.
 *
 */

class Mynewsdesk_Qarea_Admin_Settings
{
    public function activate(){
        add_action('admin_menu', array($this, "options_page"));

        if ($_SERVER['REQUEST_METHOD'] == 'POST') {
            $this->postProcess($_POST);
        }
    }

    public function postProcess($post){
        if (isset($post['action']) && $post['action'] == 'process_form') {
            $this->saveAdminSettings();
        }
    }

    /**
     *
     * Save Api Key field; Clear wp crons; Do new crons with new api key
     *
     * */
    public function saveAdminSettings(){
        if (isset($_POST['api_key'])) {
            $api_key = sanitize_text_field($_POST['api_key']);
            update_option('coingecko_key', $api_key);

            //	    Clear wp cron tasks
            wp_clear_scheduled_hook( 'getCoinDataEvery__5min' );
            wp_clear_scheduled_hook( 'getCoinDataEvery__1hour' );
            wp_clear_scheduled_hook( 'getCoinDataEvery__1day' );

            //   Add new cron tasks
            if ( !wp_get_schedule( 'getCoinDataEvery__5min' )){
                wp_schedule_event( time(), 'five_min', 'getCoinDataEvery__5min');
            }
            if ( !wp_get_schedule( 'getCoinDataEvery__1hour' )){
                wp_schedule_event( time(), 'hourly', 'getCoinDataEvery__1hour');
            }
            if ( !wp_get_schedule( 'getCoinDataEvery__1day' )){
                wp_schedule_event( time(), 'daily', 'getCoinDataEvery__1day');
            }
        }
    }

    public function options_page(){
        add_options_page(
            "CoinAPI Integration",
            "CoinAPI Integration",
            "manage_options",
            "coinapi-integration-settings",
            array($this, 'render')
        );
    }

    public function render(){
        require PLUGIN_PATH . '/admin/partials/class-coinapi-integration-admin-display.php';
    }
}