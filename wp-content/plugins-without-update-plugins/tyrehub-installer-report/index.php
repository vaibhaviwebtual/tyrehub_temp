<?php
/*
Plugin Name: Tyrehub Installer Report
Plugin URI: https://acespritech.com/
Description: Installer reports and paid functionality.
Version: 1.1.1
Author: Acespritech
Author URI: https://acespritech.com/
*/

add_action('admin_menu', 'tir_menu');

function tir_menu()
{
	add_menu_page('Installer Reports Page', 'Service Invoice', 'manage_options', 'installer-report', 'tir_main', 'dashicons-admin-tools',62 );
	add_menu_page('Installer Reports Page', 'Service Invoice History', 'manage_options', 'installer-invoice-report', 'tir_report', 'dashicons-media-text',62 );

	add_submenu_page(
        'installer-invoice-report' // Use the parent slug as usual.
        , __( 'Page title', 'textdomain' )
        , ''
        , 'manage_options'
        , 'invoice-report-delete'
        , 'tir_delete_invoice'
    );
}

add_action('admin_enqueue_scripts', 'admin_js_installer_report');

function admin_js_installer_report()
{
    wp_enqueue_script('admin_js_installer_report', plugins_url('/installer-report.js', __FILE__), array('jquery'));


     wp_enqueue_style('admin_css_installer_report', plugins_url('/installer-report.css', __FILE__));
}

function tir_main(){
?>
 	<h2>Installer Report</h2>
 	<div class="admin-url" hidden=""><?php echo admin_url('admin-ajax.php'); ?></div>
 	<?php 
 	global $wpdb;
    $currency = get_woocommerce_currency_symbol();
    $sql = "SELECT * FROM th_installer_data WHERE (is_franchise='' OR is_franchise IS NULL) AND visibility=1";
    $row = $wpdb->get_results($sql);
 	?>
 	<strong>Select Installer</strong>
 	<select class="installer-list">
 		<option selected="" disabled="">Select Installer</option>
 		<?php 
 		foreach ($row as $data) 
	    { ?>
	    	<option value="<?php echo $data->installer_data_id; ?>"><?php echo $data->business_name; ?></option>
	    <?php
	    }
 		?>
 	</select>
 
 	<?php

$monthArray = array(
                    "Jan-01" =>"01",
                    "Feb-02" =>"02",
                    "Mar-03" =>"03",
                    "Apr-04" =>"04",
                    "May-05"=>"05",
                    "Jun-06" =>"06",
                    "Jul-07"=>"07",
                    "Aug-08" =>"08",
                    "Sep-09" =>"09",
                    "Oct-10" =>"10",
                    "Nov-11" =>"11",
                    "Dec-12" =>"12"
                );



?>
<!-- displaying the dropdown list -->
<select class="installer-list month" id="month">
    <option value="">Select Month</option>
    <?php
    foreach ($monthArray as $key=>$month) {
        // if you want to select a particular month
        $selected = ($month == date('m')) ? 'selected' : '';
        // if you want to add extra 0 before the month uncomment the line below
        //$month = str_pad($month, 2, "0", STR_PAD_LEFT);
        echo '<option '.$selected.' value="'.$month.'">'.$key.'</option>';
    }
    ?>
</select>
 	<?php
	// set start and end year range
	$yearArray = range(2018, 2025);
	?>
<!-- displaying the dropdown list -->
<select class="installer-list year" id="year">
    <option value="">Select Year</option>
    <?php
    foreach ($yearArray as $year) {
        // if you want to select a particular year
        $selected = ($year == date('Y')) ? 'selected' : '';
        echo '<option '.$selected.' value="'.$year.'">'.$year.'</option>';
    }
    ?>
</select>

 
 	<button class="search-btn search-history">Search</button>
 	
 	
 	<span class="grand-total">
 		<strong>Grand Total: <?php echo $currency; ?></strong>
 		<span class="amount"></span>
 	</span>

 	<span class="total-charge" style="display: none;">
 		<strong>Select Total: <?php echo $currency; ?></strong>
 		<span class="amount"></span>
 	</span>
 	
 	<button class="create-invoice">Pay Out</button>

 	<div id="after_create_invoice" style="display: none;">
		<div class="inner">
			<div class="body">
		  		<h3>Invoice Created for selected Service!</h3>
		  		<button><a href="<?php echo get_site_url();?>/wp-admin/admin-ajax.php?action=installer_report_pdf&document_type=invoice&my-account&_wpnonce=e1f2e73d5f&order_ids=3759" class="download-pdf" traget="_blank">Download Invoice</a></button>
		  		<button class="close">Close</button>
		  	</div>
		</div>		  
	</div>
	
 	<div class="installer-data">
 		<table class="installer-report">
 			<thead>
 				<tr>
	 				<th>
	 				<input type="checkbox" name="" class="select-all-service">
	 				Order No.</th>
	 				<th>Date</th>
	 				<th>Complition On</th>
	 				<th>Customer</th>
	 				<th>Service Type</th>
	 				<th>Service Selected</th>
                    <th>Tyre QTY</th>
	 				<th>Price</th>
	 			</tr>
 			</thead>
	 			
	 			<tbody></tbody>
	 	</table>
 	</div>
<?php
}


add_action('wp_ajax_tir_update_status_paid', 'tir_update_status_paid');
add_action('wp_ajax_nopriv_tir_update_status_paid', 'tir_update_status_paid');
function tir_update_status_paid()
{
	global $woocommerce,$wpdb;
	$id = $_POST['id'];
	$type = $_POST['type'];

	if($type == 's'){
		$update_service = $wpdb->get_results("UPDATE th_cart_item_installer set paid = 'yes' WHERE cart_item_installer_id = '$id' ");
	}
	else{
		$update_service = $wpdb->get_results("UPDATE th_cart_item_service_voucher set paid = 'yes' WHERE service_voucher_id = '$id' ");
	}	

}

add_action('wp_ajax_tir_save_paid_details', 'tir_save_paid_details');
add_action('wp_ajax_nopriv_tir_save_paid_details', 'tir_save_paid_details');
function tir_save_paid_details(){
	$service_arr = $_POST['service_arr'];
	$voucher_arr = $_POST['voucher_arr'];
	$total = $_POST['total'];
	$installer_id = $_POST['installer_id'];
	$voucher = serialize($voucher_arr);
	$service = serialize($service_arr);
	global $woocommerce,$wpdb;

	date_default_timezone_set('Asia/Kolkata');
    $date = date('d-m-Y h:i a', time()); 
    $current_user = get_current_user_id();

    
    $rowcount = $wpdb->get_var("SELECT COUNT(*) FROM th_paid_service");

    $rowcount = $rowcount + 1;
    $invoice_number = 'TH-'.date('ymd', time()).'-'.$rowcount;

	$insert = $wpdb->insert('th_paid_service', array(
                                        'date' => $date,
                                        'services' => $service,
                                        'vouchers' => $voucher,
                                        'amount' => $total,
                                        'installer_id' => $installer_id,
                                        'created_by' => $current_user,
                                        'invoice_no' => $invoice_number,
                                        ));
	
	echo $last_id = $wpdb->insert_id;
die();
}

include('function.php');
include('invoice-report.php');