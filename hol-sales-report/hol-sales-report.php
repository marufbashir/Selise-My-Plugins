<?php
/**
 * @package HoL_Sales_report
 * @version 1.0.2
 */
/*
Plugin Name: HoL Sales Report
Plugin URI: http://www.houseofladerach.com/
Description: House of laderach sales report on completed order and processing order. Go to the menu HoL sales Report "wp-admin/admin.php?page=hol-sales-report" to view the report.
Author: Maruf Al Bashir
Version: 1.7.2
Author URI: http://marufcse.com
*/

// Exit if accessed directly
defined('ABSPATH') || define('ABSPATH', __DIR__ . '/');
require_once(ABSPATH . 'wp-settings.php');
// add style

if (!defined('ABSPATH')) exit;

if (!function_exists('is_plugin_active'))
	include_once(ABSPATH . 'wp-admin/includes/plugin.php');


if (is_plugin_active('woocommerce/woocommerce.php')) {

	// add_action( 'wp_ajax_reports', 'reports_callback' );

	require_once plugin_dir_path(__FILE__) . 'admin/admin-menu.php';

	// update completed order transection id
	add_action('woocommerce_order_status_completed', 'update_frm_entry_after_wc_order_completed');
	function update_frm_entry_after_wc_order_completed($order_id)
	{

		$order = new WC_Order($order_id);
		if ($order->payment_method_title == 'Cash on delivery' || $order->payment_method_title  == 'Per Nachnahme' ||  $order->payment_method_title == 'Paiement à la livraison') {
			return  update_post_meta($order_id, '_transaction_id', 'CoD_complete_delivery');
		} else {

		}
	}
}

// load style 

function hol_sales_report_assets()
{
	wp_register_style('hol_sales_report_style', plugins_url('assets/style.css', __FILE__));
	wp_enqueue_style('hol_sales_report_style');

	// 	//date picker css
	// 	wp_register_style('hol_sales_report_date_picker_style', 'https://cdn.jsdelivr.net/npm/daterangepicker/daterangepicker.css');
	// 	wp_enqueue_style('hol_sales_report_date_picker_style');
	// //
	// wp_register_style('hol_sales_report_date_select2_style', 'https://cdnjs.cloudflare.com/ajax/libs/select2/3.4.8/select2.css');
	// 	wp_enqueue_style('hol_sales_report_date_select2_style');

	// 	//jquery
	// 	wp_register_script('hol_sales_report_script_jquery', 'https://cdn.jsdelivr.net/jquery/latest/jquery.min.js',true);
	// 	wp_enqueue_script('hol_sales_report_script_jquery');
	// 	//moment
	// 	wp_register_script('hol_sales_report_script_moment', 'https://cdn.jsdelivr.net/momentjs/latest/moment.min.js');
	// 	wp_enqueue_script('hol_sales_report_script_moment');
	// 	//date picker
	// 	wp_register_script('hol_sales_report_script_daterangepicker', 'https://cdn.jsdelivr.net/npm/daterangepicker/daterangepicker.min.js');
	// 	wp_enqueue_script('hol_sales_report_script_daterangepicker');
	// 	//select 2
	// 	wp_register_script('hol_sales_report_script_select2', 'https://cdnjs.cloudflare.com/ajax/libs/select2/3.4.8/select2.js');
	// 	wp_enqueue_script('hol_sales_report_script_select2');


	// 	// main script
	// 	wp_register_script('hol_sales_report_script', plugins_url('assets/index.js', __FILE__));
	// 	wp_enqueue_script('hol_sales_report_script');
}

add_action('admin_init', 'hol_sales_report_assets');
