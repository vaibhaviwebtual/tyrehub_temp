<?php
if ( ! defined( 'ABSPATH' ) ) {
	exit; // Exit if accessed directly
}

trait WC_Order_Export_Admin_Tab_Abstract_Ajax_Filters {
	/**
	 * Select2 method
	 */
	public function ajax_get_products() {

		$main_settings = WC_Order_Export_Main_Settings::get_settings();

		echo json_encode( WC_Order_Export_Data_Extractor_UI::get_products_like( $_REQUEST['q'],
			$main_settings['autocomplete_products_max'] ) );
	}

	/**
	 * Select2 method
	 */
	public function ajax_get_users() {
		echo json_encode( WC_Order_Export_Data_Extractor_UI::get_users_like( $_REQUEST['q'] ) );
	}

	/**
	 * Select2 method
	 */
	public function ajax_get_coupons() {
		echo json_encode( WC_Order_Export_Data_Extractor_UI::get_coupons_like( $_REQUEST['q'] ) );
	}

	/**
	 * Select2 method
	 */
	public function ajax_get_categories() {
		echo json_encode( WC_Order_Export_Data_Extractor_UI::get_categories_like( $_REQUEST['q'] ) );
	}

	/**
	 * Select2 method
	 */
	public function ajax_get_vendors() {
		$this->ajax_get_users();
	}

	public function ajax_get_used_custom_order_meta() {

		$settings = WC_Order_Export_Manage::make_new_settings( $_POST );
		$sql      = WC_Order_Export_Data_Extractor::sql_get_order_ids( $settings );
		$ret      = WC_Order_Export_Data_Extractor_UI::get_all_order_custom_meta_fields( $sql );
		echo json_encode( $ret );
	}

	public function ajax_get_used_custom_products_meta() {

		$settings = WC_Order_Export_Manage::make_new_settings( $_POST );
		$sql      = WC_Order_Export_Data_Extractor::sql_get_order_ids( $settings );
		$ret      = WC_Order_Export_Data_Extractor_UI::get_product_custom_meta_fields_for_orders( $sql );
		echo json_encode( $ret );
	}

	public function ajax_get_used_custom_order_items_meta() {

		$settings = WC_Order_Export_Manage::make_new_settings( $_POST );
		$sql      = WC_Order_Export_Data_Extractor::sql_get_order_ids( $settings );
		$ret      = WC_Order_Export_Data_Extractor_UI::get_order_item_custom_meta_fields_for_orders( $sql );
		echo json_encode( $ret );
	}

	public function ajax_get_used_custom_coupons_meta() {

		$ret = array();
		echo json_encode( $ret );
	}

	public function ajax_get_order_custom_fields_values() {
		echo json_encode( WC_Order_Export_Data_Extractor_UI::get_order_custom_fields_values( $_POST['cf_name'] ) );
	}

	public function ajax_get_user_custom_fields_values() {
		echo json_encode( WC_Order_Export_Data_Extractor_UI::get_user_custom_fields_values( $_POST['cf_name'] ) );
	}

	public function ajax_get_product_custom_fields_values() {
		echo json_encode( WC_Order_Export_Data_Extractor_UI::get_product_custom_fields_values( $_POST['cf_name'] ) );
	}

	public function ajax_get_products_taxonomies_values() {
		echo json_encode( WC_Order_Export_Data_Extractor_UI::get_products_taxonomies_values( $_POST['tax'] ) );
	}

	public function ajax_get_products_attributes_values() {
		echo json_encode( WC_Order_Export_Data_Extractor_UI::get_products_attributes_values( $_POST['attr'] ) );
	}

	public function ajax_get_products_itemmeta_values() {
		echo json_encode( WC_Order_Export_Data_Extractor_UI::get_products_itemmeta_values( $_POST['item'] ) );
	}

	public function ajax_get_order_shipping_values() {
		echo json_encode( WC_Order_Export_Data_Extractor_UI::get_order_meta_values( '_shipping_', $_POST['item'] ) );
	}

	public function ajax_get_order_billing_values() {
		echo json_encode( WC_Order_Export_Data_Extractor_UI::get_order_meta_values( '_billing_', $_POST['item'] ) );
	}

	public function ajax_get_order_item_names() {
		echo json_encode( WC_Order_Export_Data_Extractor_UI::get_order_item_names( $_POST['item_type'] ) );
	}

	public function ajax_get_order_item_meta_key_values() {
		echo json_encode( WC_Order_Export_Data_Extractor_UI::get_order_item_meta_key_values( $_POST['meta_key'] ) );
	}

}