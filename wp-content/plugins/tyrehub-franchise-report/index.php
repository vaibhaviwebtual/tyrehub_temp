<?php
/*
Plugin Name: Franchise Report
Plugin URI: https://acespritech.com/
Description: Installer reports and paid functionality.
Version: 1.1.1
Author: Acespritech
Author URI: https://acespritech.com/
*/
add_action( 'wp_enqueue_scripts','scripts_styles' );
/**
* Register and Enqueue Scripts and Styles.
*
* @since 1.0.0
*/
//Script-tac-ulous -> All the Scripts and Styles Registered and Enqueued, scripts first - then styles
function scripts_styles() {
  $options = get_option( 'bootstrap_modal_settings' );  
  $options_default = array(
    'ng_modal_disable_bootstrap' => '',  
    );
    $options = wp_parse_args( $options, $options_default );

    //wp_register_script( 'modaljs' , plugins_url( '/js/bootstrap.min.js',  __FILE__), array( 'jquery' ), '3.3.7', true );
    //wp_register_style( 'modalcss' , plugins_url( '/css/bootstrap.css',  __FILE__), '' , '3.3.7', 'all' );
    wp_register_style( 'custommodalcss' , plugins_url( '/css/custommodal.css',  __FILE__), '' , '3.3.7', 'all' );
    wp_register_style( 'font-awesome' ,get_template_directory_uri().'/assest/css/font-awesome.css?ver=1110100');    

     wp_enqueue_script( 'modaljs' );
        wp_enqueue_style( 'modalcss' );
}

add_action( 'admin_enqueue_scripts','admin_modal' );
/**
 * Add scripts in back-end.
 *
 * @since 1.3.0
 */
function admin_modal($hook) {
   
   wp_register_script( 'modaljs' , plugins_url( '/js/bootstrap.min.js',  __FILE__), array( 'jquery' ), '3.3.7', true );
   wp_register_style( 'modalcss' , plugins_url( '/css/bootstrap.css',  __FILE__), '' , '3.3.7', 'all' );
    wp_register_style( 'custommodalcss' , plugins_url( '/css/custommodal.css',  __FILE__), '' , '3.3.7', 'all' );
    wp_register_style( 'font-awesome' ,get_template_directory_uri().'/assest/css/font-awesome.css?ver=1110100');    

     wp_enqueue_script( 'modaljs' );
        wp_enqueue_style( 'modalcss' );

    wp_enqueue_style( 'wp-color-picker' );

    wp_enqueue_script( 'wp-color-picker-alpha', plugins_url( '/js/wp-color-picker-alpha.min.js',  __FILE__ ), array( 'wp-color-picker' ), '2.1.2', true );
}


//add_action( 'admin_menu','plugin_page' );

add_action('admin_menu', 'tir_franchise_menu');

function tir_franchise_menu()
{
	add_menu_page('Franchise Reports', 'Franchise Reports', 'manage_options', 'franchise-report', 'tir_franchise_main', 'dashicons-admin-tools',62 );
    add_submenu_page('franchise-report','Franchise Payout Listing', 'Franchise Payout Listing', 'manage_options', 'franchise-invoice-report', 'tir_franchise_report', 'dashicons-media-text',62 );
    add_submenu_page('franchise-report','Franchise Payout History', 'Franchise Payout History', 'manage_options', 'franchise-payout-history', 'franchise_payout_history', 'dashicons-media-text',62 );
    add_submenu_page('franchise-report','Offline Orders', 'Offline Orders', 'manage_options', 'ofline-ordres', 'admin_offline_ordres_fun', 'dashicons-media-text',62 );
    

	add_submenu_page(
        'franchise-invoice-report' // Use the parent slug as usual.
        , __( 'Page title', 'textdomain' )
        , ''
        , 'manage_options'
        , 'franchise-report-delete'
        , 'tir_delete_franchise_invoice'
    );
}

add_action('admin_enqueue_scripts', 'admin_js_franchise_report');

function admin_js_franchise_report()
{
    wp_enqueue_script('admin_js_franchise_report', plugins_url('/franchise-report.js', __FILE__), array('jquery'));


     wp_enqueue_style('admin_css_franchise_report', plugins_url('/franchise-report.css', __FILE__));
}

function tir_franchise_main(){
?>
 	<h2>Franchise Report</h2>
 	<div class="admin-url" hidden=""><?php echo admin_url('admin-ajax.php'); ?></div>
 	<?php 
 	global $wpdb;
    $currency = get_woocommerce_currency_symbol();
    $sql = "SELECT * FROM th_installer_data WHERE  is_franchise='yes'";
    $row = $wpdb->get_results($sql);
 	?>
 	<strong>Select Franchise</strong>
 	<select class="franchise-list">
 		<option value="">Select Franchise</option>
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
<select class="franchise-list month" id="month">
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
<select class="franchise-list year" id="year">
    <option value="">Select Year</option>
    <?php
    foreach ($yearArray as $year) {
        // if you want to select a particular year
        $selected = ($year == date('Y')) ? 'selected' : '';
        echo '<option '.$selected.' value="'.$year.'">'.$year.'</option>';
    }
    ?>
</select>

 
 	<button class="search-btn franhise-search-history">Search</button>
 	
 	
 	<span class="grand-total">
 		<strong>Grand Total: <?php echo $currency; ?></strong>
 		<span class="amount"></span>
 	</span>

 	<span class="total-charge" style="display: none;">
 		<strong>Select Total: <?php echo $currency; ?></strong>
 		<span class="amount"></span>
 	</span>
 	
 	<button class="create-franchise-invoice">Pay Out</button>

 	<div id="after_create_franchise_invoice" style="display: none;">
		<div class="inner">
			<div class="body">
		  		<h3>Invoice Created for selected Tyre/Service!</h3>
		  		<button><a href="<?php echo get_site_url();?>/wp-admin/admin-ajax.php?action=franchise_report_pdf&document_type=paid-invoice-franchise&my-account&_wpnonce=e1f2e73d5f&order_ids=3759" class="download-pdf" traget="_blank">Download Invoice</a></button>
		  		<a href="<?=site_url();?>/wp-admin/admin.php?page=franchise-report"><button class="close" id="payoutclose">Close</button></a>
		  	</div>
		</div>		  
	</div>
	
 	<div class="franchise-data">
 		<table class="franchise-report">
 			<thead>
 				<tr>
	 				<th>
	 				<input type="checkbox" name="" class="select-all-service">
	 				Order No.</th>
	 				<th>Tyre</th>
	 				<th>Complition On</th>
	 				<th>Customer</th>
	 				<th>Service Type</th>
	 				<th>Service Selected</th>
                    <th>Tyre QTY</th>
	 				<th>Price</th>
                    <th>View</th>
	 			</tr>
 			</thead>
	 			
	 			<tbody></tbody>
	 	</table>
 	</div>
        <!-- Modal -->
<div id="profit_view" class="profit_view modal fade"  role="dialog">
  <div class="modal-dialog">
    <!-- Modal content-->
    <div class="modal-content">
      <div class="modal-header">
        <button type="button" class="close" data-dismiss="modal">&times;</button>
        <h4 class="modal-title" style="text-align: center;">Profit Share View</h4>
       
      </div>
      <div class="modal-body">
        <table class="table table-striped table-bordered" style="width:100%">
                        <thead>
                            <tr style="background-color: #e3e3e3;">
                                <th>Tyre And Service List</th>
                                <th>*All amount are before GST</th>
                                
                            </tr>
                            <tr id="tyre_sale_tr">
                                <td>Tyrehub Tyre Selling Price (<span id="qty"></span> tyres)</td>
                                <td id="tyre_sale" style="text-align: right;"></td>
                                
                            </tr>
                            <tr id="tyre_buy_tr">
                                <td>Tyrehub Buying Price (<span id="qty1"></span> tyres)</td>
                                <td id="tyre_buy" style="text-align: right;"></td>
                                
                            </tr>
                            <tr id="tyre_profit_tr" style="background-color: #e3e3e3;">
                                <th>Your Franchise Benefits</th>
                                <th id="tyre_profit" style="text-align: right"></th>
                                
                            </tr>
                            <tr id="balancing_with_tyre_tr">
                                <td>Balancing</td>
                                <td id="balancing_with_tyre" style="text-align: right;"></td>
                                
                            </tr>
                             <tr id="alignment_with_tyre_tr">
                                <td>Alignment</td>
                                <td id="alignment_with_tyre" style="text-align: right;"></td>
                                
                            </tr>
                             <tr id="car_wash_with_tyre_tr">
                                <td>Car Wash</td>
                                <td id="car_wash_with_tyre" style="text-align: right;"></td>
                                
                            </tr>
                             <tr id="balancing_alignment_tr">
                                <td>Balancing & Alignment</td>
                                <td id="balancing_alignment" style="text-align: right;"></td>
                                
                            </tr>
                             <tr id="separate_car_wash_tr">
                                <td>Car Wash</td>
                                <td  id="separate_car_wash" style="text-align: right;"></td>
                                
                            </tr>
                             <tr id="service_payment_tr" style="background-color: #e3e3e3;">
                                <th>Your Services Benefits </th>
                                <th id="service_payment" style="text-align: right;"></th>
                                
                            </tr>
                            <tr id="gross_total_tr" style="background-color: #e3e3e3;">
                                <th>Sub Total</th>
                                <th style="text-align: right;" id="gross_total"></th>
                                
                            </tr>
                            <tr>
                                <td>Online Payment Handling Charges</td>
                                <td id="handling_charge" style="text-align: right;"></td>
                                
                            </tr>
                            <tr style="background-color: #e3e3e3;">
                                <th>Your Profit</th>
                                <th id="your_profit" style="text-align: right;"></th>
                                
                            </tr>
                            <tr>
                                <td>GST</td>
                                <td id="profit_gst" style="text-align: right;"></td>
                                
                            </tr>
                            <tr style="background-color: #e3e3e3;">
                                <th>Total Amount</th>
                                <th id="total_profit" style="text-align: right;"></th>
                                
                            </tr>
                        </thead>
                        
                    </table>
      </div>
     
    </div>

  </div>
</div>
<?php
}


add_action('wp_ajax_tir_update_franchise_status_paid', 'tir_update_franchise_status_paid');
add_action('wp_ajax_nopriv_tir_update_franchise_status_paid', 'tir_update_franchise_status_paid');
function tir_update_franchise_status_paid()
{
	global $woocommerce,$wpdb;
	 $profit = $_POST['profit'];
     $total = $_POST['total'];

     $month = $_POST['month'];
     $year = $_POST['year'];
    $franchise_id= $_POST['franchise_id'];
     date_default_timezone_set('Asia/Kolkata');
    $date = date('d-m-Y h:i a', time()); 
    $current_user = get_current_user_id();

    
    $rowcount = $wpdb->get_var("SELECT COUNT(*) FROM th_profit_payout");

    $rowcount = $rowcount + 1;
    $invoice_number = 'TH-'.date('ymd', time()).'-'.$rowcount;

    $insert = $wpdb->insert('th_profit_payout', array(
                                        'invoice_no' =>$invoice_number,
                                        'franchise_id' => $franchise_id,
                                        'massage' =>'Payout'.$month.'-'.$year,
                                        'amount' => $total,
                                        'created_by' => $current_user,
                                        ));

    $last_id = $wpdb->insert_id;

     foreach ($profit as $key => $value) {
         # code...
        $SQL="UPDATE th_franchise_profit  SET payout_status = 'paid' , payout_date='".date('Y-m-d H:i:s')."' WHERE profit_id = '$value'";
        $update_service = $wpdb->query($SQL);

        $wpdb->insert('th_payout_history', array(
                'payout_id' =>$last_id,
                'profit_id' => $value
                ));

     }

echo $last_id;
die;
    
}

add_action('wp_ajax_tir_franchise_save_paid_details', 'tir_franchise_save_paid_details');
add_action('wp_ajax_nopriv_tir_franchise_save_paid_details', 'tir_franchise_save_paid_details');
function tir_franchise_save_paid_details(){
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

	/*$insert = $wpdb->insert('th_paid_service', array(
                                        'date' => $date,
                                        'services' => $service,
                                        'vouchers' => $voucher,
                                        'amount' => $total,
                                        'installer_id' => $installer_id,
                                        'created_by' => $current_user,
                                        'invoice_no' => $invoice_number,
                                        ));
	
	echo $last_id = $wpdb->insert_id;*/
die();
}

include('function.php');
include('franchise-report.php');
?>