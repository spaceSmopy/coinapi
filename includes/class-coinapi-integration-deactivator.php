<?php

/**
 * Fired during plugin deactivation
 *
 * @link       https://urich.org/
 * @since      1.0.0
 *
 * @package    Coinapi_Integration
 * @subpackage Coinapi_Integration/includes
 */

/**
 * Fired during plugin deactivation.
 *
 * This class defines all code necessary to run during the plugin's deactivation.
 *
 * @since      1.0.0
 * @package    Coinapi_Integration
 * @subpackage Coinapi_Integration/includes
 * @author     Urich <info@urich.org>
 */
class Coinapi_Integration_Deactivator {

	public static function deactivate() {
        $api_key = get_option('coingecko_key');
        if(!empty($api_key)) {
            delete_option('coingecko_key');
        }

//	    Clear wp cron tasks
        wp_clear_scheduled_hook( 'getCoinDataEvery__5min' );
        wp_clear_scheduled_hook( 'getCoinDataEvery__1hour' );
        wp_clear_scheduled_hook( 'getCoinDataEvery__1day' );

//      Remove json files
        $file_path__coin_general_info = PLUGIN_PATH."/data-json/".FILENAME_COINSINFO.".json";
        if(file_exists($file_path__coin_general_info)){
            wp_delete_file( $file_path__coin_general_info );
        }
        $file_path__coin_data__24hours = PLUGIN_PATH."/data-json/".FILENAME_COINSDAY.".json";
        if(file_exists($file_path__coin_data__24hours)){
            wp_delete_file( $file_path__coin_data__24hours );
        }
        $file_path__coin_data__7days = PLUGIN_PATH."/data-json/".FILENAME_COINSWEEK.".json";
        if(file_exists($file_path__coin_data__7days)){
            wp_delete_file( $file_path__coin_data__7days );
        }
        $file_path__coin_data__30days = PLUGIN_PATH."/data-json/".FILENAME_COINSMONTH.".json";
        if(file_exists($file_path__coin_data__30days)){
            wp_delete_file( $file_path__coin_data__30days );
        }
        $file_path__coin_data__1year = PLUGIN_PATH."/data-json/".FILENAME_COINSYEAR.".json";
        if(file_exists($file_path__coin_data__1year)){
            wp_delete_file( $file_path__coin_data__1year );
        }
        $file_path__coin_data__allTime = PLUGIN_PATH."/data-json/".FILENAME_COINSALLTIME.".json";
        if(file_exists($file_path__coin_data__allTime)){
            wp_delete_file( $file_path__coin_data__allTime );
        }
	}

}
