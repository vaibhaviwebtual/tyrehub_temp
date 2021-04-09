<?php if ( ! defined( 'ABSPATH' ) ) exit; // Exit if accessed directly ?>
<?php
global $wpdb, $woocommerce;
$currency = get_woocommerce_currency_symbol();
$paid_service = $_GET['service_id'];
$paid_sql = "SELECT * FROM th_paid_service WHERE id = '$paid_service'";
    $row1 = $wpdb->get_results($paid_sql);
    foreach ($row1 as $key => $value)
    {
    	$installer_id = $value->installer_id;
    	$date = $value->date;
    	$services = $value->services;
    	$vouchers = $value->vouchers;
    	$service_arr = unserialize($services);
    	$voucher_arr = unserialize($vouchers);
    	$final_amount = $value->amount;
    	$invoice_no = $value->invoice_no;
    }




?>
<?php

$installer = "SELECT * FROM th_installer_data WHERE installer_data_id = '$installer_id'";
$row = $wpdb->get_results($installer);
foreach ($row as $key => $value)
{
	$gst_no = $value->gst_no;
	$cmp_name = $value->company_name;
	$cmp_add = $value->company_add;
	$business_name = $value->business_name;
	$address = $value->address;
	$city = $value->city;
	$pincode = $value->pincode;
	$contact_no = $value->contact_no;
	$gstno = $value->gst_no;
	$installer_city_id = $value->city_id;
}


?>
<div class="single_record">
	<table class="head container">
		<tr>

			<!-- <td class="shop-info">
				<div class="shop-name"><h3><?php $this->shop_name(); ?></h3></div>
				<div class="shop-address">
					ATOZ TYRE HUB PVT. LTD.<br>
					3RD EYE RESIDENCY, MOTERA STADIUM ROAD, MOTERA, AHMEDABAD-380005<br>
					E-Mail: sales@tyrehub.com<br>
					Toll Free: 1-800-233-5551
				</div>
			</td> -->
			<td class="shop-info">
				<div class="shop-name"><strong>Sold By:</strong> <h3><?php echo $business_name; ?></h3></div>
				<div class="shop-address">
					<?=$address?>, <?=$city?>-<?=$pincode;?><br>
					<!-- E-Mail: sales@tyrehub.com<br> -->
					<!-- Mobile: <?=$contact_no;?><br> -->
					<strong>GST NO: <?=$gstno;?></strong>
				</div>
			</td>
			<td class="header">
				<?php
				if( $this->has_header_logo() ) {
					$this->header_logo();
				} else {
					echo $this->get_title();
				}
				?><br>
				<h3>Toll Free Number: 1-800-233-5551</h3>
			</td>
		</tr>
	</table>
</div>
<h1 class="document-type-label">Service Invoice</h1>
<?php do_action( 'wpo_wcpdf_after_document_label', $this->type, $this->order ); ?>
<table class="order-data-addresses">
	<tr>
		<td class="address billing-address" style="width: 50%;">


			<p><strong>To,</strong><br>ATOZ TYRE HUB PVT. LTD.<br>
			3RD EYE RESIDENCY, MOTERA STADIUM ROAD, MOTERA, AHMEDABAD-380005<br>
			<strong>GSTIN: 24AARCA0146M1ZJ</strong></p>
		</td>
		<td class="order-data">
			<table>

				<tr class="invoice-number">
					<th><?php _e( 'Invoice Number:', 'woocommerce-pdf-invoices-packing-slips' ); ?></th>
					<td><?php echo $invoice_no; ?></td>
				</tr>

				<?php if ( isset($this->settings['display_date']) ) { ?>
				<tr class="invoice-date">
					<th><?php _e( 'Invoice Date:', 'woocommerce-pdf-invoices-packing-slips' ); ?></th>
					<td><?php $date=date_create($date);
	 				echo date_format($date,"d-m-Y h:i a"); ?></td>
				</tr>
				<?php } ?>

			</table>
		</td>
	</tr>
</table>
<p>All price inclusive of GST</p>
<br>
<table class="order-details" style="font-size:12px;">
	<thead>
		<tr>
			<th class="product" width="10%">Order No.</th>
			<th width="20%">Service Type</th>
			<th width="40%">Services</th>
			<th class="hsn" width="20%">Service Date</th>
			<th class="price" width="10%" style="text-align:right;">Price</th>
		</tr>
	</thead>
	<tbody>
		<?php
		foreach ($service_arr as $key => $value)
		{
			$installer_data = "SELECT * FROM th_cart_item_installer WHERE cart_item_installer_id = '$value'";



			$data_row = $wpdb->get_results($installer_data);
		  	foreach ($data_row as $key => $data)
            {
                $destination = $data->destination;
                $completed_date = $data->completed_date;
                $vehicle_id = $data->vehicle_id;

                $vehicle_name = $wpdb->get_var( $wpdb->prepare( "SELECT vehicle_type FROM th_vehicle_type WHERE vehicle_id='%s' LIMIT 1" , $vehicle_id) );
                $order_id = $data->order_id;
                $product_id = $data->product_id;
                $order = wc_get_order( $order_id );
                foreach ( $order->get_items() as $item_id => $item )
    			{
    				if($item['variation_id'] != ''){
			            $ord_prd_id = $item['variation_id'];
			         }
			         else{
			            $ord_prd_id = $item['product_id'];
			         }

			         if($ord_prd_id == $product_id){
			         	$service_taxable = wc_get_order_item_meta($item_id,'service_taxable',true);
						$service_gst = wc_get_order_item_meta($item_id,'service_sgst',true);
			         }

    			}
            }
			 ?>

			 <tr>
			 	<td><?php echo $order_id; ?></td>
			 	<td><?php echo $vehicle_name; ?></td>
			 	<td>
			 		<?php
				 	 	$services = "SELECT *
	                                FROM th_cart_item_services
	                                WHERE product_id = '$product_id' and order_id = '$order_id'";

                        $row = $wpdb->get_results($services);
                        $total = 0;

                        foreach ($row as $key => $service)
                        {
                            $service_id = $service->cart_item_services_id;
                            $service_name = $service->service_name;
                            $rate = $service->rate;
                            $tyre = $service->tyre;

                            $service_id = $service->service_data_id;
                            $vehicle_id = $service->vehicle_id;

                            if($service_name == 'Tyre Fitment')
                            {
                                if($rate == 0){

                                $fitting_charge = $wpdb->get_var( $wpdb->prepare( "SELECT rate FROM th_installer_service_price WHERE service_data_id='$service_id' and vehicle_id = '$vehicle_id' and city_id = '$installer_city_id' LIMIT 1" , $vehicle_id) );

                                $rate = $fitting_charge;
                                }
                            }

	                            $charg = $rate * $tyre;
	                            //$total = $total + $charg;
								$voucher_gst = round($charg * 18 / 118);

								$total_gst = $total_gst + $voucher_gst;
								$new_price = $charg - $voucher_gst;
								$total=$total+$new_price;

                            echo '<div>'.$service_name.' - '.$currency.number_format((float)$charg, 2, '.', '').'</div>';
                        }
                        	echo '<p>(All price inclusive of GST)</p>';

                        $total_sgst=$total_gst/2;
                        $total_cgst=$total_gst/2;

				 	?>
			 	</td>
			 	<td>
			 		<?php
			 		if($completed_date == ''){
			 			echo '-';
			 		}else{
			 			$date=date_create($completed_date);
	 					echo date_format($date,"d-m-Y");
			 		}

			 		 ?></td>

			 	<td class="price" style="text-align:right">
			 		<?php echo $currency.number_format((float)$total, 2, '.', ''); ?>
			 	</td>
			 </tr>

			 <?php
		}

		foreach ($voucher_arr as $key => $value)
		{
			$voucher_data = "SELECT * FROM th_cart_item_service_voucher WHERE service_voucher_id = '$value'";
			$vrow = $wpdb->get_results($voucher_data);
		  	foreach ($vrow as $key => $data)
            {
                $completed_date = $data->completed_date;
                $vehicle_id = $data->vehicle_id;
                $voucher_id = $data->service_voucher_id;
                $amount = $data->rate;
                $vehicle_name = $wpdb->get_var( $wpdb->prepare( "SELECT vehicle_type FROM th_vehicle_type WHERE vehicle_id='%s' LIMIT 1" , $vehicle_id) );
                $voucher_name = $data->voucher_name;
                if($voucher_name == 'promotional'){
                            $service_type = 'Promotion Voucher';
                            $voucher_name = 'promotion';
                        }else{
                            $service_type = $vehicle_name;
                        }
                $order_id = $data->order_id;
                $product_id = $data->product_id;

                $order = wc_get_order( $order_id );
                foreach ( $order->get_items() as $item_id => $item )
    			{
    				if($item['variation_id'] != ''){
			            $ord_prd_id = $item['variation_id'];
			         }
			         else{
			            $ord_prd_id = $item['product_id'];
			         }

			         if($ord_prd_id == $product_id){
			         	$voucher_cgst = wc_get_order_item_meta($item_id, $voucher_id.'_service_sgst', true);
						$voucher_taxable_value = wc_get_order_item_meta($item_id, $voucher_id.'_service_taxable', true);
			         }

    			}
            }

								$voucher_gst = round($amount * 18 / 118);
								$total_gst = $total_gst + $voucher_gst;
								$new_price = $amount - $voucher_gst;

								$total_sgst=$total_gst/2;

                        		$total_cgst=$total_gst/2;

								$total=$total+$new_price;
			 ?>

			 <tr>
			 	<td><?php echo $order_id; ?></td>
			 	<td><?php echo $vehicle_name; ?></td>
			 	<td><?php echo $voucher_name; ?></td>
			 	<td><?php $date=date_create($completed_date);
	 				echo date_format($date,"d-m-Y");  ?></td>
			 	<td class="price" style="text-align:right"><?php echo $currency.number_format((float)$new_price, 2, '.', ''); ?>
			  	</td>
			 </tr>
			 <?php
		}

		?>

		<tr>
				<td></td>
				<td></td>
				<td></td>
			 	<td colspan="2" class="price" style="text-align:right">
			 		<table class="totals">

						<tr>
							<td style="width: 70%; border: 0px;">SGST - 9% (OUTPUT)</td>
							<td style="width: 30%; border: 0px; text-align: right;"><?php echo $currency.number_format((float)$total_sgst, 2, '.', ''); ?></td>
						</tr>
						<tr>
							<td class="" style="width: 70%; border: 0px;">CGST - 9% (OUTPUT)</td>
							<td class="" style="width: 30%;border: 0px; text-align: right;"><?php echo $currency.number_format((float)$total_cgst, 2, '.', ''); ?></td>
						</tr>

				</table>

			    </td>
			 </tr>
	</tbody>
	<?php $before_gst_total = ($final_amount - $total_gst); ?>
	<tfoot>
		<tr class="no-borders">
			<td colspan="3"><strong>Amount in words:<br><?php echo getIndianCurrency($final_amount);?></strong></td>
			<td colspan="2" class="">
				<table class="totals">
					<tfoot>
						<tr class="totals">
							<td class="no-borders"></td>
							<th class="description" style="border-top: 2px solid #000;border-bottom: 2px solid #000;">Before GST</th>
							<td class="price" style="text-align: right;border-top: 2px solid #000;border-bottom: 2px solid #000;width: 70%;"><span class="totals-price" style="text-align: center;"><strong><?php echo $currency.number_format((float)$before_gst_total, 2, '.', ''); ?></strong></span></td>
						</tr>
						<tr class="totals">
							<td class="no-borders"></td>
							<th class="description" style="border-top: 2px solid #000;border-bottom: 2px solid #000;">GST</th>
							<td class="price" style="text-align: right;border-top: 2px solid #000;border-bottom: 2px solid #000;width: 70%;"><span class="totals-price" style="text-align: center;"><strong><?php echo $currency.number_format((float)$total_gst, 2, '.', ''); ?></strong></span></td>
						</tr>
						<tr class="totals">
							<td class="no-borders"></td>
							<th class="description" style="border-top: 2px solid #000;border-bottom: 2px solid #000;">Total</th>
							<td class="price" style="text-align: right;border-top: 2px solid #000;border-bottom: 2px solid #000;width: 70%;"><span class="totals-price" style="text-align: center;"><strong><?php echo $currency.number_format((float)$final_amount, 2, '.', ''); ?></strong></span></td>
						</tr>
					</tfoot>
				</table>
			</td>
		</tr>

	</tfoot>

<?php do_action( 'wpo_wcpdf_after_order_details', $this->type, $this->order ); ?>
<div class="second-footer">
	<!-- <strong>Your Sincerely<br> Tyrehub.com</strong> -->
</div>
<?php if ( $this->get_footer() ): ?>
<div id="footer">
	<div class="border" style="width: 100%;"></div>
	<div class="name" style="width: 40%; float: left;"><?php $this->footer(); ?></div>
	<div class="shop-phone" style="width: 60%; float: right; text-align: right; font-size: 10px;">
					<h3><?php $this->shop_name(); ?></h3>
					3RD EYE RESIDENCY, MOTERA STADIUM ROAD, MOTERA, AHMEDABAD-380005<br>
					E-Mail: sales@tyrehub.com, Toll Free: 1-800-233-5551
	</div>
</div><!-- #letter-footer --></p>
<?php endif; ?>
<?php do_action( 'wpo_wcpdf_after_document', $this->type, $this->order ); ?>