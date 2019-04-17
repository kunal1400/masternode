<?php

/**
 * The plugin bootstrap file
 *
 * This file is read by WordPress to generate the plugin information in the plugin
 * admin area. This file also includes all of the dependencies used by the plugin,
 * registers the activation and deactivation functions, and defines a function
 * that starts the plugin.
 *
 * @link              https =>//www.facebook.com
 * @since             1.0.0
 * @package           Master Node
 *
 * @wordpress-plugin
 * Plugin Name =>       Master Node Shortcodes
 * Plugin URI =>        https =>//www.facebook.com
 * Description =>       This is a short description of what the plugin does. It's displayed in the WordPress admin area.
 * Version =>           1.0.0
 * Author =>            Ondrej Stekly
 * Author URI =>        https =>//www.facebook.com
 * License =>           GPL-2.0+
 * License URI =>       http =>//www.gnu.org/licenses/gpl-2.0.txt
 * Text Domain =>       masternodeshortcodes
 * Domain Path =>       /languages
 */

// If this file is called directly, abort.
if ( ! defined( 'WPINC' ) ) {
	die;
}

$sampleArray = array(
	"coin_ticker" => "PIVX",
    "coin_name" => "PIVX",
    "price_usd" => "0.954916",
    "price_btc" => "0.00018245",
    "price_change" => "3.12",
    "volume_usd" => "389864.51",
    "volume_btc" => "74.488",
    "marketcap_usd" => "56954925.00",
    "marketcap_btc" => "10882.00",
    "daily_income_coins" => "3.3021",
    "daily_income_usd" => "3.1532",
    "daily_income_btc" => "0.00060247",
    "roi_percent" => "12.05",
    "roi_days" => "3029",
    "paid_rewards_for_mns" => "5574.0000",
    "avg_masternode_reward_frequency_seconds" => "78495",
    "active_masternodes" => "1688",
    "supply" => "59643905.79470984",
    "coins_locked" => "28.30",
    "required_coins_for_masternode" => "10000",
    "masternode_worth_usd" => "9549.16",
    "masternode_worth_btc" => "1.82450",
    "blocks_24_hours" => "1858",
    "avg_block_time_seconds" => "46.50",
    "genesis_block_timestamp" => "1454127007",
    "is_ico" => "0",
    "status" => "1"
);

function mno_callback( $atts ) {
	// Get shortcodes
	$a = shortcode_atts( array(
		"coin_ticker" => "",
		"get" => ""
	), $atts );
	
	$output = "";

	if( empty($a['get']) ) {
		$output = "get parameter is empty";
	}

	if( !empty($a['coin_ticker']) ) {
		// Response 
		$response = wp_remote_get( "https://masternodes.online/mno_api/?apiseed=MNOAPI-0063-ac44ae4a-5c47ac12-50a3-5cfc6053" );

		if ( is_array( $response ) && ! is_wp_error( $response ) ) {
		    $headers = $response['headers']; // array of http header lines
		    $body    = $response['body']; // use the content
		    if($body) {
		    	$coins = json_decode($body, ARRAY_A);
		    	foreach ($coins as $i => $coin) {
		    		if( @$coin['coin_ticker'] == @$a['coin_ticker'] ) {
		    			$output = @$coin[$a['get']];		    			
		    		}		    		
		    	}		  
		    }	    
		}		
	}
	else {
		$output = "Coin name is required";
	}
	
	// ob_start();
	// //return "foo = {$a['foo']}";
	
	// $output = ob_get_clean();
	return $output;
}

add_shortcode( 'mno', 'mno_callback' );
