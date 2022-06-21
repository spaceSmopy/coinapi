<?php

/**
 * Cron
 *
 * @link       https://urich.org/
 * @since      1.0.0
 *
 * @package    Coinapi_Integration
 * @subpackage Coinapi_Integration/includes
 */

/**
 *
 * This class defines all code necessary to cron schedules.
 *
 * @since      1.0.0
 * @package    Coinapi_Integration
 * @subpackage Coinapi_Integration/includes
 * @author     Urich <info@urich.org>
 */
class Coinapi_Integration_Cron {

    public function activate(){
        add_action( 'getCoinDataEvery__5min', array($this, "getCoinDataEvery__5min__callback"));
        add_action( 'getCoinDataEvery__1hour', array($this, "getCoinDataEvery__1hour__callback"));
        add_action( 'getCoinDataEvery__1day', array($this, "getCoinDataEvery__1day__callback"));
        add_filter( 'cron_schedules', array($this, "cron_add_five_min") );

        $api_key = get_option('coingecko_key');
        if(!empty($api_key)) {
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
    public function getCoinDataEvery__5min__callback(){
        require_once PLUGIN_PATH . '/admin/class-coinapi-integration-admin.php';
        $coinsapi = new Coinapi_Integration_Admin();
        $coinsapi->pull_coin_data__general_info($coinsapi->coins);
        $coinsapi->pull_coin_data__24hours($coinsapi->coins);
    }
    public function getCoinDataEvery__1hour__callback(){
        require_once PLUGIN_PATH . '/admin/class-coinapi-integration-admin.php';
        $coinsapi = new Coinapi_Integration_Admin();
        $coinsapi->pull_coin_data__7days($coinsapi->coins);
        $coinsapi->pull_coin_data__30days($coinsapi->coins);
    }
    public function getCoinDataEvery__1day__callback(){
        require_once PLUGIN_PATH . '/admin/class-coinapi-integration-admin.php';
        $coinsapi = new Coinapi_Integration_Admin();
        $coinsapi->pull_coin_data__1year($coinsapi->coins);
        $coinsapi->pull_coin_data__allTime($coinsapi->coins);
    }

    public function cron_add_five_min( $schedules ) {
        if(!isset($schedules["five_min"])) {
            $schedules['five_min'] = array(
                'interval' => 60 * 5,
                'display' => 'Once in 5 min'
            );
        }
        return $schedules;
    }

}