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
	
	// // Daily income usd
	if( isset($a['get']) &&  $a['get'] == 'daily_income_usd' ) {
		return '<div id="masterNodeWrapper">
			<table>
				<thead>
					<tr>
						<th>'.$a['coin_ticker'].' - Daily Income USD</th>
						<th>Input</th>
						<th>Action</th>
						<th>Output</th>
					</tr>
				</thead>
				<tbody>
					<tr>
						<td>'.$output.'</td>
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
				var _coinVal = '.$output.';
				if(_input) {
					var formula1 = _input * _coinVal * 30 * 0.01;
					var formula2 = (_input - _coinVal * 30) - 9.9;
					document.getElementById("masterNodeOutput1").value = formula1.toFixed(1)
					document.getElementById("masterNodeOutput2").value = formula2.toFixed(1)
					//alert(calculation)
				}
				else {
					alert("Input is empty")					
				}
			}
		</script>';
	}	

	return $output;
}

add_shortcode( 'mno', 'mno_callback' );
