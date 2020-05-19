<?php
/**
 * @package nyzo-tip
 */
/*
Plugin Name: Nyzo tip
Plugin URI: https://github.com/AngainorDev/Nyzotip-plugin
Description: Activate on your WordPress blog and get tips from the Nyzo Chrome extension in seconds.
Version: 0.0.1
Author: AngainorDev
Author URI: https://angainor.com
License: Unlicence
Text Domain: nyzo-tip
*/

defined( 'ABSPATH' ) or die( 'No script kiddies please!' );
load_plugin_textdomain('nyzo-tip', false, basename( dirname( __FILE__ ) ) . '/languages' );

$nyzotip_options = get_option('nyzotip_options');


function nyzotip_install() {
	global $nyzotip_options;
	if (!get_option('nyzo_options')) {
		add_option('nyzo_options', array (
			'client_url' => 'https://client.nyzo.co',
			'receiver_id' => 'id__8fKFZURhpkYzQA6CkP~kivW_ospgrjJXU0tMwS3uByzcqjaVvi-I',
			'stealth_tip' => true
		));
		$nyzotip_options = get_option('nyzo_options');
	}
}

function get_dataset(){
	global $nyzotip_options;
	return(' data-client-url="'.$nyzotip_options['client_url'].'" data-receiver-id="'.$nyzotip_options['receiver_id'].'" data-tag="Sent with nyzo tip wp extension" ');
}

function nyzotip_wp_head(){
	global $nyzotip_options;
	if($nyzotip_options["stealth_tip"]){
		echo('<span style="display:none" class="nyzo-tip-button" '.get_dataset().'></span>');
	}
	echo('<style>.nyzo-extension-installed{content:url("/wp-content/plugins/nyzo-tip/nyzo-extension-installed-256.png");};</style>');
}

add_action('wp_head', 'nyzotip_wp_head');

function nyzotip_shortcode($atts) {
	return('<img src="/wp-content/plugins/nyzo-tip/nyzo-extension-not-installed-256.png" class="nyzo-tip-button nyzo-extension-not-installed" '.get_dataset().'" data-tag="Sent with nyzo tip wp extension" style="width:120px">');
}

add_shortcode('nyzotip', 'nyzotip_shortcode');

function nyzotip_options() {
	global $current_user;
	global $nyzotip_options;
	if (!is_admin()) {
		die();
	}
	if (!empty($_POST['receiver_id'])) {
		//check_admin_referer();
		$nyzotip_options['receiver_id'] = $_POST['receiver_id'];
		$nyzotip_options['client_url'] = $_POST['client_url'];
		if($_POST['stealth_tip'] == "1"){
			$nyzotip_options['stealth_tip'] = true;
		}
		else{
			$nyzotip_options['stealth_tip'] = false;
		}

		update_option('nyzotip_options', $nyzotip_options);

		echo '<div id="message" class="updated fade"><p>' . __('Changes successfully saved!', 'nyzotip') . '</p></div>' . "\n";
	}

	$nyzotip_options = get_option('nyzotip_options');
	include ('options.tmpl.php');
}

function nyzotip_admin_menu() {
	if (is_admin()) {
		$menu = array (
			'Nyzotip',
			'Nyzotip',
			8,
			'nyzotip/nyzo-tip.php' ,
			'nyzotip_options'
		);
		call_user_func_array('add_menu_page', $menu);
	}
}

if (is_admin()) {
	register_activation_hook(__FILE__,   'nyzotip_install');
	add_action('admin_menu', 'nyzotip_admin_menu');
}
