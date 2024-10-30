<?php

/**
 * Plugin Name: Circulair Currency
 * Plugin URI: https://github.com/Marco-Daniel/circulair-geld
 * Description: A WordPress plugin that enables payments with Circulair Currency on WooCommerce webshops.
 * Version: 1.0.7
 * Author: M.D. Leguijt | M.D. Design & Development
 * Author URI: https://mddd.nl
 * License: GPLv2 or later
 * Text Domain: circulair-geld
 * Domain Path: /languages/
 */

if (!defined('ABSPATH')) {
    exit;
}

// define constants
define('MDDD_CG_PLUGIN_DIR_PATH', untrailingslashit(plugin_dir_path(__FILE__)));
define('MDDD_CG_PLUGIN_DIR_URL', untrailingslashit(plugin_dir_url(__FILE__)));

// include gateway class
require_once(MDDD_CG_PLUGIN_DIR_PATH.'/src/class.php');

// add link to settings on plugin page
add_filter( 'plugin_action_links_'.plugin_basename(__FILE__), function($links) {
  $links[] = '<a href="'.admin_url( 'admin.php?page=wc-settings&tab=checkout&section=CG').'">'. __('Settings', 'circulair-geld') . '</a>';
  return $links;
});

// register class as an WooCommerce payment gateway
add_filter( 'woocommerce_payment_gateways', function($gateways) {
  $gateways[] = 'WC_Gateway_CG';
  return $gateways;
});

// load text-domain
add_action('init', function() {
  load_plugin_textdomain('circulair-geld', false, 'circulair-geld/languages');
});

// load plugin
add_action('plugins_loaded', 'wooce_payment_gateway_init');

// register activation hook
register_activation_hook( __FILE__, function() {
  if (!class_exists('WooCommerce')) {
    die(__('WooCommerce is not activated.', 'circulair-geld'));
  }
});


  