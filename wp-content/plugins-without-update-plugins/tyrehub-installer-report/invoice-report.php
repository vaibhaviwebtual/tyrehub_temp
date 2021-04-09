<?php

function tir_report(){
	?>
 	<h2>Service invoice Report</h2>
 	<div class="admin-url" hidden=""><?php echo admin_url('admin-ajax.php'); ?></div>
 	<?php
	 	global $wpdb;
	    $currency = get_woocommerce_currency_symbol();
	    $installer = "SELECT * FROM th_installer_data";
	    $irow = $wpdb->get_results($installer);
 	?>
 	<strong>Select Installer</strong>
 	<select class="all-installer">
 		<option selected="" disabled="">Select Installer</option>
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
    $sql = "SELECT * FROM th_paid_service order by id desc";
    $row = $wpdb->get_results($sql);
    ?>

    <table class="wp-list-table widefat fixed striped posts invoice-report-data ">
 			<thead>
 				<tr>
	 				<th>Invoice No.</th>
	 				<th>Date</th>
	 				<th style="text-align: center;">Created by</th>
	 				<th>Installer</th>
	 				<th style="text-align: center;">Invoice</th>
	 				<th style="text-align: center;">Price</th>
	 				<th>Action</th>
	 			</tr>
 			</thead>
	 			
	 		<tbody class="pagnation">
	 			<?php
	 			foreach ($row as $key => $value)
	 			{
	 				$date = $value->date;
	 				$invoice_no = $value->invoice_no;

	 				$createdby = $value->created_by; 
	 				$user_meta=get_userdata($createdby);
	 				$user_login = $user_meta->user_login;

	 				$installer_id = $value->installer_id;
	 				$installer_name = $wpdb->get_var( $wpdb->prepare( "SELECT business_name FROM th_installer_data WHERE installer_data_id='%s' LIMIT 1" , $installer_id) );

	 				$amount = $value->amount;
	 				$id = $value->id;
	 				?>
	 				<tr class="newsbox">
	 					<td><?php echo $invoice_no; ?></td>
	 					<td>
	 						<?php 
	 						
	 						$date=date_create($date);
	 						echo date_format($date,"d-m-Y h:i a"); ?>
	 							
	 						</td>
	 					<td style="text-align: center;"><?php echo $user_login; ?></td>
	 					<td><?php echo $installer_name; ?></td>
	 					<td style="text-align: center;">
	 						<a href="<?php echo get_site_url();?>/wp-admin/admin-ajax.php?action=installer_report_pdf&document_type=invoice&order_ids=3759&service_id=<?php echo $id; ?>&_wpnonce=04e74a5779" target="_blank">Download PDF</a>
	 					</td>
	 					<td style="text-align: center;"><?php echo $currency.number_format((float)$amount, 2, '.', ''); ?></td>
	 					<td><a onclick="return confirm('Are you sure?')" href="?page=invoice-report-delete&invoice_id=<?php echo $id; ?>">Delete</a></td>
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

function tir_delete_invoice(){

	$id = $_GET['invoice_id'];

	global $wpdb, $woocommerce;
	$delete_service = $wpdb->get_results("DELETE from th_paid_service WHERE id = '$id'");

	wp_redirect('?page=installer-invoice-report');
}