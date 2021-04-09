<?php
global $wpdb, $woocommerce;
$currency = get_woocommerce_currency_symbol();
$payout_id = $_GET['payout_id'];
 $paid_sql = "SELECT * FROM th_profit_payout WHERE payout_id = '$payout_id'";
    $row1 = $wpdb->get_row($paid_sql);
    
    $franchise_id = $row1->franchise_id;
	$insert_date = $row1->insert_date;
	$massage = $row1->massage ;	    
	$final_amount = $row1->amount;
	$invoice_no = $row1->invoice_no;



 $installer = "SELECT * FROM th_installer_data WHERE installer_data_id = '$franchise_id'";

?>
<?php


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

			<td class="shop-info">
				<div class="shop-name"><strong>Sold By:</strong> <h3><?php echo $business_name; ?></h3></div>
				<div class="shop-address">
					<?=$address?>, <?=$city?>-<?=$pincode;?><br>
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
			<th width="30%">Tyres</th>
			<th width="">Services</th>
			<th class="hsn" width="20%">Completed Date</th>
			<th class="price" width="10%" style="text-align:right;">Price</th>
		</tr>
	</thead>
	<tbody>
		<?php
		
		$SQL = "SELECT ph.*,fp.* FROM th_payout_history ph LEFT JOIN th_franchise_profit as fp ON fp.profit_id=ph.profit_id WHERE ph.payout_id = '$payout_id'";
		$payout_history = $wpdb->get_results($SQL);

		foreach ($payout_history as $key => $value)
		{
			$SQL="SELECT vehicle_type FROM th_vehicle_type WHERE vehicle_id='$value->vehicle_id' LIMIT 1";
			$vehicle_name = $wpdb->get_var($SQL);
		  	
		  							$variation_des='';
                                 $product_variation = wc_get_product($value->product_id);
                                    $variation_des = $product_variation->get_description();
                                     if(empty($variation_des)){
                                       $variation_des ='*No Tyre Purchase*';
                                     }
                                   
                                    $serviArra=array();
                                    $service='';
                                    if($value->balancing_price>0){
                                        $serviArra[0]['name']='Balancing';
                                        $serviArra[0]['image']=get_template_directory_uri().'/images/service-icon/balancing.png';
                                    }
                                    if($value->alignment_price>0){
                                        $serviArra[1]['name']='Alignment';
                                        $serviArra[1]['image']=get_template_directory_uri().'/images/service-icon/alignment.png';
                                    }
                                    if($value->carwash_price>0){
                                        $serviArra[2]['name']='Carwash';
                                        $serviArra[2]['image']=get_template_directory_uri().'/images/service-icon/carwash.png';
                                    }
                                    if($value->balancing_alignment>0){
                                        $serviArra[3]['name']='Balancing & Alignment';
                                        $serviArra[3]['image']=get_template_directory_uri().'/images/service-icon/alignment_balance.png';
                                    }
                                    if($value->single_carwash>0){
                                        $serviArra[4]['name']='Carwash';
                                        $serviArra[4]['image']=get_template_directory_uri().'/images/service-icon/carwash.png';
                                    }

                                    if(!empty($serviArra)){
                                       
                                        //$service=implode(',',$serviArra);
                                    }
                                 
			 ?>

			 <tr>
			 	<td><?php echo $value->order_id; ?></td>
			 	
			 	<td><?php echo $variation_des; ?></td>
			 	<td><?php 
                       foreach ($serviArra as $value1) {
                            echo '<span style="margin-right:12px;"><img class="service-img"  title="'.$value1['name'].'" src="'.$value1['image'].'"></span>';
                        }
                       ?></td>
			 	
			 	<td><?=date('d-m-Y',strtotime($value->payout_date));?></td>

			 	<td class="price" style="text-align:right">
			 		<?php echo $currency.number_format((float)$value->base_profit, 2, '.', ''); ?>
			 	</td>
			 </tr>

			 <?php

			 $subtotal= $subtotal + $value->base_profit;

			 $GST= round(($subtotal * 18) / 100);

			 $sgst= $GST/2;
			 $cgst= $GST/2;


		}
		$final_amount= $subtotal+$GST;
	?>

		<tr>
				<td></td>
				<td></td>
				<td></td>
			 	<td colspan="2" class="price" style="text-align:right">
			 		<!-- <table class="totals">

						<tr>
							<td style="width: 70%; border: 0px;">SGST - 9% (OUTPUT)</td>
							<td style="width: 30%; border: 0px; text-align: right;"><?php echo $currency.number_format((float)$sgst, 2, '.', ''); ?></td>
						</tr>
						<tr>
							<td class="" style="width: 70%; border: 0px;">CGST - 9% (OUTPUT)</td>
							<td class="" style="width: 30%;border: 0px; text-align: right;"><?php echo $currency.number_format((float)$cgst, 2, '.', ''); ?></td>
						</tr>

				</table> -->

			    </td>
			 </tr>
	</tbody>
	<tfoot>
		<tr class="no-borders">
			<td colspan="3"><strong>Amount in words:<br><?php echo getIndianCurrency($final_amount);?></strong></td>
			<td colspan="2" class="">
				<table class="totals">
					<tfoot>
						<tr class="totals">
							<td class="no-borders"></td>
							<th class="description" style="border-top: 2px solid #000;border-bottom: 2px solid #000;">Before GST</th>
							<td class="price" style="text-align: right;border-top: 2px solid #000;border-bottom: 2px solid #000;width: 70%;"><span class="totals-price" style="text-align: center;"><strong><?php echo $currency.number_format((float)$subtotal, 2, '.', ''); ?></strong></span></td>
						</tr>
						

						<tr class="totals">
							<td class="no-borders"></td>
							<th class="description" style="border-top: 2px solid #000;border-bottom: 2px solid #000;">SGST - 9% (OUTPUT)</th>
							<td class="price" style="text-align: right;border-top: 2px solid #000;border-bottom: 2px solid #000;width: 70%;"><span class="totals-price" style="text-align: center;"><strong><?php echo $currency.number_format((float)$sgst, 2, '.', ''); ?></strong></span></td>
						</tr>

						<tr class="totals">
							<td class="no-borders"></td>
							<th class="description" style="border-top: 2px solid #000;border-bottom: 2px solid #000;">CGST - 9% (OUTPUT)</th>
							<td class="price" style="text-align: right;border-top: 2px solid #000;border-bottom: 2px solid #000;width: 70%;"><span class="totals-price" style="text-align: center;"><strong><?php echo $currency.number_format((float)$cgst, 2, '.', ''); ?></strong></span></td>
						</tr>
						<tr class="totals">
							<td class="no-borders"></td>
							<th class="description" style="border-top: 2px solid #000;border-bottom: 2px solid #000;">GST Total</th>
							<td class="price" style="text-align: right;border-top: 2px solid #000;border-bottom: 2px solid #000;width: 70%;"><span class="totals-price" style="text-align: center;"><strong><?php echo $currency.number_format((float)$GST, 2, '.', ''); ?></strong></span></td>
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