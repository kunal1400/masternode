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

function mno_callback( $atts ) {
	// Get shortcodes
	$a = shortcode_atts( array(
		"coin_ticker" => "",
		"get" => "",
		"formula" => 1
	), $atts );
	
	$output = "";
	$coinValue = 0;
	$selectedCoin = array();

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
		    			$coinValue = @$coin[$a['get']];
		    			$selectedCoin = $coin;
		    		}		    		
		    	}		  
		    }
		}
		else {
			$output = "Some error occured while calling the api";
			// echo '<pre>';
			// print_r($response);
			// echo '</pre>';			
		}
	}
	else {
		$output = "Coin name is required";
	}

	if( $a['formula'] == 1 ) {
		$output = ($coinValue*30)-9.99;
		$output = round($output, 1);
	}	
	else if( $a['formula'] == 2 ) {
		$output = $coinValue*7;
		$output = round($output, 1);		
	}
	else if( $a['formula'] == 3 ) {
		$output = $coinValue*30;
		$output = round($output, 1);		
	}
	else if( $a['formula'] == 4 ) {
		$output = $coinValue*365;
		$output = round($output, 1);		
	}
	else if( $a['formula'] == 5 ) {
		// echo '<pre>';
		// print_r($output);
		// echo '</pre>';
		$output = $selectedCoin['daily_income_usd']*365*100/(($selectedCoin['price_usd']*$selectedCoin['required_coins_for_masternode'])+(9.99*12));
		$output = round($output, 1);		
	}
	else if( $a['formula'] == 6 ) {
		$output = $selectedCoin['daily_income_usd']*365*100/(($selectedCoin['price_usd']*$selectedCoin['required_coins_for_masternode'])+(29.99*12));
		$output = round($output, 1);		
	}
	else if( $a['formula'] == 7 ) {
		$output = '<div id="masterNodeWrapper">
			<table>
				<thead>
					<tr>
						<th>'.$a['coin_ticker']. $a['get'] .'</th>
						<th>Input</th>
						<th>Action</th>
						<th>Output</th>
					</tr>
				</thead>
				<tbody>
					<tr>
						<td>'.$coinValue.'</td>
						<td><input type="number" id="masterNodeNumberInput" /></td>
						<td><button onclick="doCalculations()" id="masterNodeNumberButton">Submit</button></td>
						<td>
							<input type="number" disabled="disabled" id="masterNodeOutput1" />
							<input type="number" disabled="disabled" id="masterNodeOutput2" />
						</td>
					</tr>
				</tbody>				
			</table>
		</div>
		<script>
			function doCalculations() {
				var _input = document.getElementById("masterNodeNumberInput").value;
				var _coinVal = '.$coinValue.';
				if(_input) {
					var formula2 = (_input - _coinVal * 30) - 9.9;
					document.getElementById("masterNodeOutput2").value = formula2.toFixed(1)
				}
				else {
					alert("Input is empty")					
				}
			}
		</script>';
	}	
	else if( $a['formula'] == 8 ) {
		$output = '<div id="masterNodeWrapper">
			<table>
				<thead>
					<tr>
						<th>'.$a['coin_ticker']. $a['get'] .'</th>
						<th>Input</th>
						<th>Action</th>
						<th>Output</th>
					</tr>
				</thead>
				<tbody>
					<tr>
						<td>'.$coinValue.'</td>
						<td><input type="number" id="masterNodeNumberInput" /></td>
						<td><button onclick="doCalculations()" id="masterNodeNumberButton">Submit</button></td>
						<td>
							<input type="number" disabled="disabled" id="masterNodeOutput1" />
							<input type="number" disabled="disabled" id="masterNodeOutput2" />
						</td>
					</tr>
				</tbody>				
			</table>
		</div>
		<script>
			function doCalculations() {
				var _input = document.getElementById("masterNodeNumberInput").value;
				var _coinVal = '.$coinValue.';
				if(_input) {
					var formula2 = _input*_coinVal*30*0.01;
					document.getElementById("masterNodeOutput2").value = formula2.toFixed(1)
				}
				else {
					alert("Input is empty")					
				}
			}
		</script>';
	}
	else {
		$output = $coinValue;
	}

	// // // Daily income usd
	// if( isset($a['get']) &&  $a['formula'] == 2 ) {
	// 	return 	}

	return $output;
}

add_shortcode( 'mno', 'mno_callback' );
