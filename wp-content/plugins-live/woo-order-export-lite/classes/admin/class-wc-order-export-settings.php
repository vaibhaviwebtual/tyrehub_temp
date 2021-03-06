<?php
if ( ! defined( 'ABSPATH' ) ) {
	exit; // Exit if accessed directly
}

class WC_Order_Export_Main_Settings {

	public static function get_settings() {

		$settings = array(
			'default_tab'                          => 'export',
			'cron_tasks_active'                    => '1',
			'show_export_status_column'            => '1',
			'show_export_actions_in_bulk'          => '1',
			'show_export_in_status_change_job'     => '0',
			'autocomplete_products_max'            => '10',
			'ajax_orders_per_step'                 => '30',
			'limit_button_test'                    => '1',
			'cron_key'                             => null,
			'ipn_url'                              => '',
			'zapier_api_key'                       => '12345678',
			'zapier_file_timeout'                  => 60,
			'show_date_time_picker_for_date_range' => false,
			'display_html_report_in_browser'       => false,
		);

		return apply_filters( 'woe_get_main_settings', $settings );
	}

}
