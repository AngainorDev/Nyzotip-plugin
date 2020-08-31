<?php
/**
 * @package nyzo-tip
 */
/*
Plugin Name: Nyzo tip button
Plugin URI: https://angainor.com
Description: Nyzo rules
Version: 0.0.2
Author: Angainor
Author URI: https://angainor.com
License: Unlicence
Text Domain: nyzo-tip
*/

$nyzotip_options = get_option('nyzotip_options');


function nyzotip_install() {
	global $nyzotip_options;
	if (!get_option('nyzo_options')) {
		add_option('nyzo_options', array (
			'client_url' => '',
			'receiver_id' => 'id__8fKFZURhpkYzQA6CkP~kivW_ospgrjJXU0tMwS3uByzcqjaVvi-I',
			'stealth_tip' => true
		));
		$nyzotip_options = get_option('nyzo_options');
	}
}

function nyzotip_get_dataset(){
	global $nyzotip_options;
	return(' data-client-url="'.esc_attr($nyzotip_options['client_url']).'" data-receiver-id="'.esc_attr($nyzotip_options['receiver_id']).'" data-tag="Sent with Nyzo tip WP extension" ');
}

function nyzotip_wp_head(){
	global $nyzotip_options;
	if($nyzotip_options["stealth_tip"]){
		echo('<span style="display:none" class="nyzo-tip-button" '.nyzotip_get_dataset().'></span>');
	}
	wp_enqueue_style('nyzotip_head_style', plugins_url('nyzotip_head_style.css', __FILE__));
}

add_action('wp_head', 'nyzotip_wp_head');

function nyzotip_shortcode($atts) {
	return('<img src="'.plugins_url('nyzo-extension-not-installed-256.png', __FILE__).'" class="nyzo-tip-button nyzo-extension-not-installed" '.nyzotip_get_dataset().'" data-tag="Sent with nyzo tip wp extension" style="width:120px">');
}

add_shortcode('nyzotip', 'nyzotip_shortcode');

function nyzotip_options() {
	global $current_user;
	global $nyzotip_options;
	if (!is_admin()) {
		die();
	}
	if (!empty($_POST['receiver_id'])) {
		$receiver_id = sanitize_text_field($_POST['receiver_id']);
		$valid_id = preg_match("/^id__([0-9a-zA-Z]|[\.~_-]){48,96}$/", $receiver_id);
		if ($valid_id == 1) {
			$client_url = esc_url_raw($_POST['client_url']);
			$nyzotip_options['receiver_id'] = $receiver_id; 
			$nyzotip_options['client_url'] = $client_url; 
			if($_POST['stealth_tip'] == "1"){
				$nyzotip_options['stealth_tip'] = true; 
			} else{
				$nyzotip_options['stealth_tip'] = false; 
			}	
			update_option('nyzotip_options', $nyzotip_options);
			echo '<div id="message" class="updated fade"><p>' . __('Changes successfully saved!', 'nyzotip') . '</p></div>' . "\n";
		} else {
			echo '<div id="message" class="error fade"><p>' . __('Invalid id__ format', 'nyzotip') . '</p></div>' . "\n";
		}	
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
