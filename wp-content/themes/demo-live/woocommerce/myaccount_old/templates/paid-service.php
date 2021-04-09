<?php
 	global $wpdb;
 	$current_user = wp_get_current_user();
    $currency = get_woocommerce_currency_symbol();

    $mobile_no = $current_user->user_login;

	$current_inst_id = $wpdb->get_var( $wpdb->prepare( "SELECT installer_data_id FROM th_installer_data WHERE contact_no ='%s' LIMIT 1", $mobile_no ) );

    $sql = "SELECT * FROM th_paid_service where installer_id = '$current_inst_id' order by id desc";
    $row = $wpdb->get_results($sql);
?>

<table class="wp-list-table widefat fixed striped posts">
	<thead>
		<tr>
			<th>Invoice No.</th>
			<th>Date</th>
			<th>Invoice</th>
			<th>Price</th>
		</tr>
	</thead>

	<tbody>
		<?php
		if($row){
		foreach ($row as $key => $value)
		{
			$date = $value->date;
			$invoice_no = $value->invoice_no;

			$installer_id = $value->installer_id;
			$installer_name = $wpdb->get_var( $wpdb->prepare( "SELECT business_name FROM th_installer_data WHERE installer_data_id='%s' LIMIT 1" , $installer_id) );

			$amount = $value->amount;
			$id = $value->id;
			?>
			<tr>
				<td><?php echo $invoice_no; ?></td>
				<td><?php echo $date; ?></td>
				<td>
					<a href="<?php echo admin_url(); ?>/admin-ajax.php?action=installer_report_pdf&document_type=invoice&order_ids=3759&service_id=<?php echo $id; ?>&_wpnonce=04e74a5779" target="_blank"><button><i class="fa fa-file-pdf-o" aria-hidden="true"></i>&nbsp;View</button></a>
				</td>
				<td><?php echo $currency.number_format((float)$amount, 2, '.', ''); ?></td>
			</tr>
			<?php
		}
	}else{

		echo '<tr><td colspan="4">No paid invoices!</td></tr>';
	}
	?>
	</tbody>
</table>
