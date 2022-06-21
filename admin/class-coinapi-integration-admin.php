<?php

/**
 * The admin-specific functionality of the plugin.
 *
 * @link       https://urich.org/
 * @since      1.0.0
 *
 * @package    Coinapi_Integration
 * @subpackage Coinapi_Integration/admin
 */

/**
 *
 * @package    Coinapi_Integration
 * @subpackage Coinapi_Integration/admin
 * @author     Urich <info@urich.org>
 */
class Coinapi_Integration_Admin {

    /**
     * The list of coins.
     *
     * @since    1.0.0
     * @access   private
     * @var      array  $coins      The list of crypto coins.
     */
    public $coins;

    /**
	 * Initialize the class and set its properties.
	 *
	 * @since    1.0.0
	 */
	public function __construct( ) {

	    $this->coins = ['bitcoin','ethereum','ripple','usd-coin','uniswap','litecoin','chainlink','bitcoin-cash','matic-network','internet-computer','tezos','aave','maker','cardano','sushi','compound-governance-token','curve-dao-token','havven','bancor','1inch','aragon','digital-swis-franc'];


    }

    public function pull_coin_data__general_info($coins){
//	        Do call to get coins info
        $coinsInfo = $this->get_apiResponse($coins,'general_info');
        $this->create_json($coinsInfo,FILENAME_COINSINFO);
    }
    public function pull_coin_data__24hours($coins){
//          Do call to get coin history data (24 hours)
        $coinsHistory__day = $this->get_apiResponse($coins, '24hours');
        $this->create_json($coinsHistory__day,FILENAME_COINSDAY);
    }
    public function pull_coin_data__7days($coins){
//          Do call to get coin history data (7 days)
        $coinsHistory__week = $this->get_apiResponse($coins, '7days');
        $this->create_json($coinsHistory__week,FILENAME_COINSWEEK);
    }
    public function pull_coin_data__30days($coins){
//          Do call to get coin history data (30 days)
        $coinsHistory__month = $this->get_apiResponse($coins, '30days');
        $this->create_json($coinsHistory__month,FILENAME_COINSMONTH);
    }
    public function pull_coin_data__1year($coins){
//          Do call to get coin history data (1 year)
        $coinsHistory__year = $this->get_apiResponse($coins, '1year');
        $this->create_json($coinsHistory__year,FILENAME_COINSYEAR);
    }
    public function pull_coin_data__allTime($coins){
//          Do call to get coin history data (All time)
        $coinsHistory__allTime = $this->get_apiResponse($coins, 'allTime');
        $this->create_json($coinsHistory__allTime,FILENAME_COINSALLTIME);
    }
    /*
    * Create asynchronous call request to service
    * */
    public function get_apiResponse($coins, $whatToGet){
        $coinsInfo = [];
        $coins_curl = [];

//        Init object where will be located all requests
        $curl_multi = curl_multi_init();
        $requestEndpoint = 'https://pro-api.coingecko.com/api/v3/coins/';
        $apiKey = get_option('coingecko_key');
        $apiKeyParam = '&x_cg_pro_api_key='.$apiKey;
        switch ($whatToGet) {
            case 'general_info':
                $additionalPathForEndpoint = '';
                $params = '?localization=false&tickers=false&market_data=true&community_data=false&developer_data=false&sparkline=true';
                break;
            case '24hours':
                $additionalPathForEndpoint = '/market_chart/';
                $params = '?vs_currency=usd&days=1&interval=minutely';
                break;
            case '7days':
                $additionalPathForEndpoint = '/market_chart/';
                $params = '?vs_currency=usd&days=7&interval=hourly';
                break;
            case '30days':
                $additionalPathForEndpoint = '/market_chart/';
                $params = '?vs_currency=usd&days=30&interval=hourly';
                break;
            case '1year':
                $additionalPathForEndpoint = '/market_chart/';
                $params = '?vs_currency=usd&days=365&interval=daily';
                break;
            case 'allTime':
                $additionalPathForEndpoint = '/market_chart/';
                $params = '?vs_currency=usd&days=max&interval=daily';
                break;
            default:
                $additionalPathForEndpoint = '';
                $params = '';
                break;
        }

        foreach ($coins as $key => $coin){
//          For all coins do request and store it in curl_multi
            $coins_curl[$key] = curl_init();
            curl_setopt_array($coins_curl[$key], array(
                CURLOPT_URL => $requestEndpoint.$coin.$additionalPathForEndpoint.$params.$apiKeyParam,
                CURLOPT_RETURNTRANSFER => true,
                CURLOPT_ENCODING => '',
                CURLOPT_MAXREDIRS => 10,
                CURLOPT_TIMEOUT => 0,
                CURLOPT_FOLLOWLOCATION => true,
                CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
                CURLOPT_CUSTOMREQUEST => 'GET',
            ));
            curl_multi_add_handle($curl_multi,$coins_curl[$key]);
        }
        do {
            $status = curl_multi_exec($curl_multi, $active);
            if ($active) {
                curl_multi_select($curl_multi);
            }
        } while ($active && $status == CURLM_OK);

        $i = 0;
        foreach ($coins_curl as $curl){
            $coinName = $this->coins[$i];
            if(!curl_errno($curl)){
//                If no errors - add decoded json to variable
                $coinsInfo[$coinName] = json_decode(curl_multi_getcontent($curl));
            } else {
                var_dump('error:'.curl_error($curl));
            }
//            Remove handle for one coin request
            curl_multi_remove_handle($curl_multi, $curl);
            $i++;
        }
//        Close requests "session"
        curl_multi_close($curl_multi);

        $array = $this->clearArrayFromUnneededData($coinsInfo, $whatToGet);
        return $array;

    }

    public function clearArrayFromUnneededData($response, $typeOfNeededData){
        $coinNames = array_keys($response);
        $i = 0;
        $coinsInfoArray = [];
        foreach ($response as $oneCoinObjectResponse) {
//        Check if response has errors
//          Return coin object with error info
            if(isset($oneCoinObjectResponse->error)){
                $coinInfoArray = [
                    $coinNames[$i] => [
                        'error' => $oneCoinObjectResponse->error,
                    ]
                ];
                return $coinInfoArray;
            }
            switch ($typeOfNeededData){
                case 'general_info':
                    $coinInfoArray = [
                        $coinNames[$i] => [
                            'id' => $oneCoinObjectResponse->id,
                            'name' => $oneCoinObjectResponse->name,
                            'symbol' => $oneCoinObjectResponse->symbol,
                            'current_price' => $oneCoinObjectResponse->market_data->current_price->usd,
                            'price_change_percentage_24h' => $oneCoinObjectResponse->market_data->price_change_percentage_24h,
                            'price_change_percentage_7d' => $oneCoinObjectResponse->market_data->price_change_percentage_7d,
                            'price_change_percentage_30d' => $oneCoinObjectResponse->market_data->price_change_percentage_30d,
                            'market_cap' => $oneCoinObjectResponse->market_data->market_cap->usd,
                            'market_cap_change_percentage_24h' => $oneCoinObjectResponse->market_data->market_cap_change_percentage_24h,
                            'fully_diluted_valuation' => isset($oneCoinObjectResponse->market_data->fully_diluted_valuation->usd) ? $oneCoinObjectResponse->market_data->fully_diluted_valuation->usd : null,
                            'total_volume' => $oneCoinObjectResponse->market_data->total_volume->usd,
                            'ath' => $oneCoinObjectResponse->market_data->ath->usd,
                            'circulating_supply' => $oneCoinObjectResponse->market_data->circulating_supply,
                            'total_supply' => $oneCoinObjectResponse->market_data->total_supply,
                            'max_supply' => $oneCoinObjectResponse->market_data->max_supply,
                            'sparkline_7d' => ($coinNames[$i] !== 'digital-swis-franc') ? $oneCoinObjectResponse->market_data->sparkline_7d : $this->changeDataToDummy($oneCoinObjectResponse->market_data->sparkline_7d, 'general_info'),
                            'image' => $oneCoinObjectResponse->image,
                        ]
                    ];
                        break;
                    case '24hours':
                    case '7days':
                    case '30days':
                    case '1year':
                    $coinInfoArray = [
                        $coinNames[$i] => [
                            'prices' => ($coinNames[$i] !== 'digital-swis-franc') ? $oneCoinObjectResponse->prices : $this->changeDataToDummy($oneCoinObjectResponse->prices),
                            'market_caps' => $oneCoinObjectResponse->market_caps,
                            'total_volumes' => $oneCoinObjectResponse->total_volumes,
                        ]
                    ];
                        break;
                    case 'allTime':
//                        Decrease array length
                        $oneCoinFilteredData__prices = $this->decreaseArrayLength($oneCoinObjectResponse->prices);
                        $oneCoinFilteredData__market_caps = $this->decreaseArrayLength($oneCoinObjectResponse->market_caps);
                        $oneCoinFilteredData__total_volumes = $this->decreaseArrayLength($oneCoinObjectResponse->total_volumes);
                        $coinInfoArray = [
                            $coinNames[$i] => [
                                'prices' => ($coinNames[$i] !== 'digital-swis-franc') ? $oneCoinFilteredData__prices : $this->changeDataToDummy($oneCoinFilteredData__prices),
                                'market_caps' => $oneCoinFilteredData__market_caps,
                                'total_volumes' => $oneCoinFilteredData__total_volumes,
                            ]
                        ];

                        break;
            }
            $i++;
            array_push($coinsInfoArray, $coinInfoArray);
        }
        return $coinsInfoArray;
    }
	public function create_json($coinArray, $fileName){
        // encode array to json
        $json = json_encode($coinArray);
        file_put_contents(PLUGIN_PATH."/data-json/".$fileName.".json", $json);
    }

    private function decreaseArrayLength($itemArr){
        if(count($itemArr) >= 1000){
            return $oneCoinFilteredData = $this->coinDataNotNull($this->moreThan1000($itemArr));
        } elseif (count($itemArr) >= 500 && count($itemArr) < 1000){
            return $oneCoinFilteredData = $this->coinDataNotNull($this->lessThan1000($itemArr));
        } else {
            return $oneCoinFilteredData = $this->coinDataNotNull($itemArr);
        }
    }
    private function coinDataNotNull($arrayWithData){
        foreach ($arrayWithData as $array) {
            if($array[1] !== null){
                $checkedArray[] = $array;
            }
        }
        return $checkedArray;
    }

    private function changeDataToDummy($arrayWithData, $type = 'default'){
        if($type == 'general_info'){
            $newArray['price'] = [];
            foreach ($arrayWithData as $array){
                foreach ($array as $item){
                    array_push($newArray['price'], 1);
                }
            }
            return $newArray;
        } else {
            foreach ($arrayWithData as $array){
                $newArray[] = [
                    $array[0],
                    $array[1] = 1
                ];
            }
            return $newArray;
        }
    }
    private function moreThan1000($itemArr){
        $idRate = round((count($itemArr) / 500));
        foreach ($itemArr as $index=>$item){
            if( $index == 0 ||
                $index % $idRate == 0 ||
                $index == count($itemArr)-1){

                $newArray[] = $item;
            }
        }
        return $newArray;
    }
    private function lessThan1000($itemArr){
        $idCoef = round((count($itemArr) / 500 * 100)) / 100;
        $idRate = round(1 / ($idCoef - 1));
        foreach ($itemArr as $index=>$item){
            if( $index == 0 ||
                $index % $idRate !== 0 ||
                $index == count($itemArr)-1){

                $newArray[] = $item;
            }
        }
        return $newArray;
    }
}

