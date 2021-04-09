<?php
function tir_franchise_report(){
	?>
 	<h2>Payout Listing</h2>
 	<div class="admin-url" hidden=""><?php echo admin_url('admin-ajax.php'); ?></div>
 	<?php
	 	global $wpdb;
	    $currency = get_woocommerce_currency_symbol();
	    $installer = "SELECT * FROM th_installer_data WHERE is_franchise='yes'";
	    $irow = $wpdb->get_results($installer);
 	?>
 	<strong>Select Franchise</strong>
 	<select class="all-franchise">
 		<option>Select Franchise</option>
 		<?php 
 		foreach ($irow as $data) 
	    { ?>
	    	<option value="<?php echo $data->installer_data_id; ?>"><?php echo $data->business_name; ?></option>
	    <?php
	    }
 		?>
 	</select>
    
 	<?php
 	global $wpdb;
    $currency = get_woocommerce_currency_symbol();
    $sql = "SELECT * FROM th_profit_payout ORDER BY  payout_id DESC";
    $row = $wpdb->get_results($sql); 
    ?>

    <table class="wp-list-table widefat fixed striped posts invoice-report-data ">
 			<thead>
 				<tr>
	 				<th>Pay Order No.</th>
	 				<th>Description</th>
	 				<th>Date</th>
	 				<th style="text-align: center;">Created by</th>
	 				<th>Franchise</th>
	 				<th style="text-align: center;">Invoice</th>
	 				<th style="text-align: center;">Price</th>
	 				<th>Action</th>
	 			</tr>
 			</thead>
	 			
	 		<tbody class="pagnation">
	 			<?php
	 			foreach ($row as $key => $value)
	 			{
	 				$date = $value->insert_date;
	 				$invoice_no = $value->invoice_no;

	 				$createdby = $value->created_by; 
	 				$user_meta=get_userdata($createdby);
	 				$user_login = $user_meta->user_login;

	 				$franchise_id = $value->franchise_id;
	 				$installer_name = $wpdb->get_var("SELECT business_name FROM th_installer_data WHERE installer_data_id='$franchise_id'");

	 				$amount = $value->amount;
	 				$id = $value->payout_id;
	 				?>
	 				<tr class="newsbox">
	 					<td><?php echo $invoice_no; ?></td>
	 					<td><?php echo $value->massage; ?></td>
	 					<td>
	 						<?php 
	 						
	 						$date=date_create($date);
	 						echo date_format($date,"d-m-Y h:i a"); ?>
	 							
	 						</td>
	 					<td style="text-align: center;"><?php echo $user_login; ?></td>
	 					<td><?php echo $installer_name; ?></td>
	 					<td style="text-align: center;">
	 						<a href="<?php echo get_site_url();?>/wp-admin/admin-ajax.php?action=franchise_report_pdf&document_type=paid-invoice-franchise&order_ids=<?php echo $id; ?>&payout_id=<?php echo $id; ?>&_wpnonce=04e74a5779" target="_blank">Download PDF</a>
	 					</td>
	 					<td style="text-align: center;"><?php echo $currency.number_format((float)$amount, 2, '.', ''); ?></td>
	 					<td><a onclick="return confirm('Are you sure?')" href="?page=franchise-report-delete&invoice_id=<?php echo $id; ?>">Delete</a></td>
	 				</tr>
	 				<?php
	 			}
	 			?>

	 		</tbody>
	 	</table>
	 	<div class="page-info"></div>
	 	<div class="controls"></div>
	 	<?php
}


function franchise_payout_history(){
	
	?>
	<!-- <link rel="stylesheet" href="<?php echo get_template_directory_uri(); ?>/assest/css/plugins/bootstrap.min.css?ver=345">
<script src="<?php echo get_template_directory_uri(); ?>/assest/js/plugins/bootstrap.min.js?ver=416" defer onload></script> -->
 	<h2>Payout History</h2>
 	<div class="admin-url" hidden=""><?php echo admin_url('admin-ajax.php'); ?></div>
 	<?php
	 	global $wpdb;
	    $currency = get_woocommerce_currency_symbol();
	    $installer = "SELECT * FROM th_installer_data WHERE is_franchise='yes'";
	    $irow = $wpdb->get_results($installer);
 	?>
 	<strong>Select Franchise</strong>
 	<select class="all-franchise">
 		<option value="">Select Franchise</option>
 		<?php 
 		foreach ($irow as $data) 
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

 
 	<button class="search-btn franhise-history">Search</button>
 	<?php
 	global $wpdb;
    $currency = get_woocommerce_currency_symbol();
    $sql = "SELECT * FROM th_profit_payout ORDER BY  payout_id DESC";
    $row = $wpdb->get_results($sql); 
    ?>
 	<div class="franchise-data">
 		<table class="franchise-report">
 			<thead>
 				<tr>
	 				<!-- <th>Order No.</th> -->
                    <th>Pay Order No.</th>
	 				<th>Tyre</th>
	 				<th>Payout Date</th>
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

function admin_offline_ordres_fun(){
    
if($_POST['csv-export']){
}
    ?>
    <!-- <link rel="stylesheet" href="<?php echo get_template_directory_uri(); ?>/assest/css/plugins/bootstrap.min.css?ver=345">
<script src="<?php echo get_template_directory_uri(); ?>/assest/js/plugins/bootstrap.min.js?ver=416" defer onload></script> -->
    <h2>Offline Orders</h2>
    <div class="admin-url" hidden=""><?php echo admin_url('admin-ajax.php'); ?></div>
    <?php
        global $wpdb;
        $currency = get_woocommerce_currency_symbol();
        $installer = "SELECT * FROM th_installer_data WHERE is_franchise='yes'";
        $irow = $wpdb->get_results($installer);
    ?>
    <strong>Select Franchise</strong>
    <select class="all-franchise all-franchise-offline">
        <option value="">Select Franchise</option>
        <?php 
        foreach ($irow as $data) 
        { ?>
            <option value="<?php echo $data->installer_data_id; ?>"><?php echo $data->business_name; ?></option>
        <?php
        }
        ?>
    </select>
    <strong>Mobile</strong>
    <input type="text" name="mobile" id="mobile" class="mobile" value="">
    <input type="date" id="start_date" name="start_date" value="" placeholder="Start Date">
    <input type="date" id="end_date" name="end_date" value="" placeholder="End Date">


 
    <button class="search-btn franchise-offline-orders">Search</button>
    <a href="" id="csv-export-link"><button class="search-btn csv-export1">Export</button></a>
    

    <div class="franchise-data franoffline">
        <table class="franchise-report">
            <thead>
                <tr>
                   <th>Order.No</th>
					<th>Month</th>
					<th>Date</th>
                    <th>Franchise</th>
                    <th>Customer</th>
                    <th>Phone</th>
					<th>Brand</th>
					<th>Tyre Size</th>
					<th>Tyre Qty</th>
					<th>Alignment</th>
					<th>Balancing</th>
					<th>Car Wash</th>
					<th>Vehical Type</th>
                    <th>Status</th>
                    <th>Total</th>
                    <th>Action</th>
                </tr>
            </thead>
                
                <tbody></tbody>
        </table>
    </div>
    <style type="text/css">
        .franoffline  tr td {border-bottom: 1px solid #000;}
    </style>
<script type="text/javascript">
jQuery(document).ready(function($) 
{
     setTimeout(function() {
           $ (".franchise-offline-orders").trigger( "click" );
        }, 1000);

        $(document).on('click','.franchise-offline-orders',function(e){

        var installer_id = $('.all-franchise-offline').val();
        var mobile = $('.mobile').val();
        var start_date = $('#start_date').val();
        var end_date = $('#end_date').val();
        //console.log(installer_id);

        var au = $('.admin-url').text();

        $.ajax({    
                type: "POST", 
                url: au,
                data: {
                    action: 'admin_franchise_offline_orders',
                    installer_id: installer_id,
                    start_date: start_date,
                    end_date: end_date,
                    mobile: mobile,
                },
                beforeSend: function() { 
                    $('.franchise-data table tbody').html('<tr><td colspan="6"><div class="spinner"></div></td></tr>');
                    $(".franchise-data .spinner").show();
                    $(".franchise-data .spinner").css('visibility','visible');
                    $('.total-charge').css('display','none');
                },
                success: function (r) {
                   var href= '<?php echo bloginfo('template_url')?>/offline-export.php?act=export';
                   var exporturl=href+'&installer_id='+installer_id+'&datefrom='+start_date+'&dateto='+end_date;
                    $('#csv-export-link').attr('href',exporturl);
                    $('.franchise-data table tbody').html(r);

                },
            }); 

    });

});
</script>
<?php
}

function tir_delete_franchise_invoice(){

	$id = $_GET['invoice_id'];

	global $wpdb, $woocommerce;
	$wpdb->get_results("DELETE from th_profit_payout WHERE payout_id = '$id'");
	$delete_service1 = $wpdb->get_results("SELECT * from th_payout_history WHERE payout_id = '$id'");

	foreach ($delete_service1 as $key => $value) {
		# code...
		$wpdb->get_results("DELETE from th_franchise_profit WHERE profit_id = '$value->profit_id'");
	}
	$wpdb->get_results("DELETE from th_payout_history WHERE payout_id = '$id'");


	wp_redirect('?page=franchise-invoice-report');
}

