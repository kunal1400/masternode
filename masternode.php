<?php

/**
 * The plugin bootstrap file
 *
 * This file is read by WordPress to generate the plugin information in the plugin
 * admin area. This file also includes all of the dependencies used by the plugin,
 * registers the activation and deactivation functions, and defines a function
 * that starts the plugin.
 *
 * @link              http://example.com
 * @since             1.0.0
 * @package           Master Node
 *
 * @wordpress-plugin
 * Plugin Name:       Master Node
 * Plugin URI:        http://example.com/plugin-name-uri/
 * Description:       This is a short description of what the plugin does. It's displayed in the WordPress admin area.
 * Version:           1.0.0
 * Author:            Ondrej
 * Author URI:        http://example.com/
 * License:           GPL-2.0+
 * License URI:       http://www.gnu.org/licenses/gpl-2.0.txt
 * Text Domain:       master-node
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
define( 'PLUGIN_NAME_VERSION', '1.0.0' );


/**
* Setting up a cron to get data
**/
// function masternode_custom_cron_schedule( $schedules ) {
//     $schedules['every_minute'] = array(
//         'interval' => 1 * MINUTE_IN_SECONDS, // Every 60 seconds
//         'display'  => __( 'Every 5 minutes' ),
//     );
//     return $schedules; 
// }
// add_filter( 'cron_schedules', 'masternode_custom_cron_schedule' );

// if ( ! wp_next_scheduled( 'get_coin_data' ) ) {
//   wp_schedule_event( time(), 'every_minute', 'get_coin_data' );
// }
// add_action( 'get_coin_data', 'save_coin_data' );

/**
* Whenever page will redirect then saving the response in option table
**/
add_action( 'template_redirect', 'save_coin_data' );

function save_coin_data() {
	$args = array(
		'timeout'     => 15,
		'redirection' => 15,
		'httpversion' => '1.0',
		'blocking'    => true,
		'headers'     => array(),
		'cookies'     => array(),
		'body'        => null,
		'compress'    => false,
		'decompress'  => true,
		'sslverify'   => true,
		'stream'      => false,
		'filename'    => null
	); 
	// Response 
	$response = wp_remote_get("https://masternodes.online/mno_api/?apiseed=MNOAPI-0063-ac44ae4a-5c47ac12-50a3-5cfc6053", $args);

	if ( is_array( $response ) && ! is_wp_error( $response ) ) {
	    $headers = $response['headers']; // array of http header lines
	    $body    = $response['body']; // use the content
	    update_option( '__masternode_coin_data', $body );
	}
}


function mno_callback( $atts ) {
	// Get shortcodes
	$a = shortcode_atts( array(
		"coin_ticker" => "",
		"get" => "",
		"formula" => 0,
		"class" => "",
		"do_round" => 1
	), $atts );
	
	$output = "";
	$coinValue = 0;
	$selectedCoin = array();

	if( empty($a['get']) ) {
		$output = "get parameter is empty";
	}	

	if( !empty($a['coin_ticker']) ) {		
		$body = get_option('__masternode_coin_data', false);
		if($body) {
	    	$coins = json_decode($body, ARRAY_A);
	    	foreach ($coins as $i => $coin) {
	    		if( @$coin['coin_ticker'] == @$a['coin_ticker'] ) {
	    			$coinValue = @$coin[$a['get']];	    			
	    			$selectedCoin = $coin;
	    		}		    		
	    	}		  
	    }
	    else {
	    	$output = "Coin data is empty";
	    }
	}
	else {
		$output = "Coin name is required";
	}

	if( $a['formula'] == 1 ) {
		$output = ($coinValue*30)-9.99;
		
		if($a['do_round'] == 0 ) {
			$output = $output;
		}
		else {
			$output = round($output, 1);
		}
	}	
	else if( $a['formula'] == 2 ) {
		$output = $coinValue*7;
		
		if($a['do_round'] == 0 ) {
			$output = $output;
		}
		else {
			$output = round($output, 1);
		}

	}
	else if( $a['formula'] == 3 ) {
		$output = $coinValue*30;
		
		if($a['do_round'] == 0 ) {
			$output = $output;
		}
		else {
			$output = round($output, 1);
		}
	}
	else if( $a['formula'] == 4 ) {
		$output = $coinValue*365;
		
		if($a['do_round'] == 0 ) {
			$output = $output;
		}
		else {
			$output = round($output, 1);
		}
	}
	else if( $a['formula'] == 5 ) {		
		$output = $selectedCoin['daily_income_usd']*365*100/(($selectedCoin['price_usd']*$selectedCoin['required_coins_for_masternode'])+(9.99*12));
		
		if($a['do_round'] == 0 ) {
			$output = $output;
		}
		else {
			$output = round($output, 1);
		}
	}
	else if( $a['formula'] == 6 ) {
		$output = $selectedCoin['daily_income_usd']*365*100/(($selectedCoin['price_usd']*$selectedCoin['required_coins_for_masternode'])+(29.99*12));
		
		if($a['do_round'] == 0 ) {
			$output = $output;
		}
		else {
			$output = round($output, 1);
		}
	}
	else if( $a['formula'] == 7 ) {
		$output = ($coinValue*30)-29.99;
		
		if($a['do_round'] == 0 ) {
			$output = $output;
		}
		else {
			$output = round($output, 1);
		}
	}	
	else if( $a['formula'] == 8 ) {

		if($a['do_round'] == 0 ) {
			$output = $coinValue;
			$output .= '<script>
					var _coinVal = '.$output.';
					function doCalculations() {
						var _input = document.getElementById("masterNodeNumberInput").value;
						if(_input) {
							var formula2 = (+_input + +100)*_coinVal*0.3;
							document.getElementById("masterNodeOutput2").innerHTML = formula2.toFixed(4)
						}
						else {
							document.getElementById("masterNodeOutput2").innerHTML = "";
						}
					}
				</script>';
		}
		else {
			$output = round($coinValue, 1);
			$output .= '<script>
					var _coinVal = '.$output.';
					function doCalculations() {
						var _input = document.getElementById("masterNodeNumberInput").value;
						if(_input) {
							var formula2 = (+_input + +100)*_coinVal*0.3;
							document.getElementById("masterNodeOutput2").innerHTML = formula2.toFixed(1)
						}
						else {
							document.getElementById("masterNodeOutput2").innerHTML = "";
						}
					}
				</script>';
		}		
	}
	else {
		if($a['do_round'] == 0 ) {
			$output = $coinValue;
		}
		else {
			$output = round($coinValue, 1);
		}
	}

	if($a['class'] == 'hidden') {
		return '<span style="display:none">'.$output.'</span>';
	}
	else {
		return '<span class="'.$a['class'].'">'.$output.'</span>';
	}
}

add_shortcode( 'mno', 'mno_callback' );

/**
 * Shortcode for generating the input for formula8
 */
add_shortcode( 'formula8_input', 'mno_formula8_input_callback' );
function mno_formula8_input_callback( $atts ) {
	// Get shortcodes
	$a = shortcode_atts( array(
		"class" => "",
	), $atts );

	return '<input class="'.$a['class'].'" onkeyup="doCalculations()" type="number" id="masterNodeNumberInput" />';
}

/**
 * Shortcode for generating the output for formula8
 */
add_shortcode( 'formula8_output', 'mno_formula8_output_callback' );
function mno_formula8_output_callback( $atts ) {
	// Get shortcodes
	$a = shortcode_atts( array(
		"class" => "",
	), $atts );

	return '<span class="'.$a['class'].'" id="masterNodeOutput2"></span>';
}