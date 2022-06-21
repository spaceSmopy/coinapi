<?php

/**
 * Routes
 *
 * @link       https://urich.org/
 * @since      1.0.0
 *
 * @package    Coinapi_Integration
 * @subpackage Coinapi_Integration/includes
 */

/**
 *
 * This class defines all code necessary to new routes.
 *
 * @since      1.0.0
 * @package    Coinapi_Integration
 * @subpackage Coinapi_Integration/includes
 * @author     Urich <info@urich.org>
 */
class Coinapi_Integration_Routes {

    public function activate(){
        add_action('rest_api_init', array($this, 'create_rest_routes'));
    }

    public function create_rest_routes() {
        register_rest_route('coinapi_data/v1', '/coins_info', array(
            'methods' => 'GET',
            'callback' => array($this, 'get_coinsInfo'),
            'permission_callback' => false,
        ));
        register_rest_route('coinapi_data/v1', '/coins_dayChange', array(
            'methods' => 'GET',
            'callback' => array($this, 'get_coinsDayChange'),
            'permission_callback' => false,
        ));
        register_rest_route('coinapi_data/v1', '/coins_weekChange', array(
            'methods' => 'GET',
            'callback' => array($this, 'get_coinsWeekChange'),
            'permission_callback' => false,
        ));
        register_rest_route('coinapi_data/v1', '/coins_monthChange', array(
            'methods' => 'GET',
            'callback' => array($this, 'get_coinsMonthChange'),
            'permission_callback' => false,
        ));
        register_rest_route('coinapi_data/v1', '/coins_yearChange', array(
            'methods' => 'GET',
            'callback' => array($this, 'get_coinsYearChange'),
            'permission_callback' => false,
        ));
        register_rest_route('coinapi_data/v1', '/coins_allTimeChange', array(
            'methods' => 'GET',
            'callback' => array($this, 'get_coinsAllTimeChange'),
            'permission_callback' => false,
        ));
    }
    public function get_coinsInfo( ) {
        if(file_exists(PLUGIN_PATH."/data-json/".FILENAME_COINSINFO.".json")){
            $file = file_get_contents(PLUGIN_PATH."/data-json/".FILENAME_COINSINFO.".json");
            if ( empty( $file ) ){
                return new WP_Error( 'file_empty', 'File is empty', array( 'status' => 404 ) );
            }
            $file = json_decode($file);
        } else {
            return new WP_Error( 'no_such_file', 'No such file founded', array( 'status' => 404 ) );
        }
        return $file;
    }
    public function get_coinsDayChange( ) {
        if(file_exists(PLUGIN_PATH."/data-json/".FILENAME_COINSDAY.".json")){
            $file = file_get_contents(PLUGIN_PATH."/data-json/".FILENAME_COINSDAY.".json");
            if ( empty( $file ) ){
                return new WP_Error( 'file_empty', 'File is empty', array( 'status' => 404 ) );
            }
            $file = json_decode($file);
        } else {
            return new WP_Error( 'no_such_file', 'No such file founded', array( 'status' => 404 ) );
        }
        return $file;
    }
    public function get_coinsWeekChange( ) {
        if(file_exists(PLUGIN_PATH."/data-json/".FILENAME_COINSWEEK.".json")){
            $file = file_get_contents(PLUGIN_PATH."/data-json/".FILENAME_COINSWEEK.".json");
            if ( empty( $file ) ){
                return new WP_Error( 'file_empty', 'File is empty', array( 'status' => 404 ) );
            }
            $file = json_decode($file);
        } else {
            return new WP_Error( 'no_such_file', 'No such file founded', array( 'status' => 404 ) );
        }
        return $file;
    }
    public function get_coinsMonthChange( ) {
        if(file_exists(PLUGIN_PATH."/data-json/".FILENAME_COINSMONTH.".json")){
            $file = file_get_contents(PLUGIN_PATH."/data-json/".FILENAME_COINSMONTH.".json");
            if ( empty( $file ) ){
                return new WP_Error( 'file_empty', 'File is empty', array( 'status' => 404 ) );
            }
            $file = json_decode($file);
        } else {
            return new WP_Error( 'no_such_file', 'No such file founded', array( 'status' => 404 ) );
        }
        return $file;
    }
    public function get_coinsYearChange( ) {
        if(file_exists(PLUGIN_PATH."/data-json/".FILENAME_COINSYEAR.".json")){
            $file = file_get_contents(PLUGIN_PATH."/data-json/".FILENAME_COINSYEAR.".json");
            if ( empty( $file ) ){
                return new WP_Error( 'file_empty', 'File is empty', array( 'status' => 404 ) );
            }
            $file = json_decode($file);
        } else {
            return new WP_Error( 'no_such_file', 'No such file founded', array( 'status' => 404 ) );
        }
        return $file;
    }
    public function get_coinsAllTimeChange( ) {
        if(file_exists(PLUGIN_PATH."/data-json/".FILENAME_COINSALLTIME.".json")){
            $file = file_get_contents(PLUGIN_PATH."/data-json/".FILENAME_COINSALLTIME.".json");
            if ( empty( $file ) ){
                return new WP_Error( 'file_empty', 'File is empty', array( 'status' => 404 ) );
            }
            $file = json_decode($file);
        } else {
            return new WP_Error( 'no_such_file', 'No such file founded', array( 'status' => 404 ) );
        }
        return $file;
    }

}