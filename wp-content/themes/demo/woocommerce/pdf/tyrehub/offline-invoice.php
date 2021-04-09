<?php
	global $wpdb,$woocommerce;
	$user_id = get_current_user_id();
	$SQL="SELECT * FROM th_installer_data WHERE user_id='".$user_id."' AND is_franchise='yes'";
	$franchise=$wpdb->get_row($SQL);

	if($_GET['order_ids']){
		$order_id=$_GET['order_ids'];
	}else{
		$table = $wpdb->prefix.'franchises_order';
		$SQL="SELECT * FROM $table WHERE franchise_id='".$franchise->installer_data_id."' ORDER BY order_id DESC LIMIT 1";
		$get_order_no=$wpdb->get_row($SQL);
		$order_id=$get_order_no->order_id;
	}
	

	

		$gst_no = $franchise->gst_no;
		$cmp_name = $franchise->company_name;
		$cmp_add = $franchise->company_add;
		$business_name = $franchise->business_name;
		$address = $franchise->address;
		$city = $franchise->city;
		$pincode = $franchise->pincode;
		$contact_no = $franchise->contact_no;
		$gstno = $franchise->gst_no;
		$installer_city_id = $franchise->city_id;
		$installer_id = $franchise->installer_data_id;

		 $tyreGST = get_option('tyre_gst');
		 $tyreGSTD = (100 + $tyreGST);
		 $serviceGST = get_option('service_gst');
		 $serviceGSTD = (100 + $serviceGST);
		 $serviceGST_2 = $serviceGST/2;
		 $tyreGST_2 = $tyreGST/2;


	$SQLORDER="SELECT * FROM wp_franchises_order WHERE order_id IN ('".$order_id."') AND franchise_id='".$franchise->installer_data_id."'";
	$order_data=$wpdb->get_row($SQLORDER);

$TyreInfo = "SELECT vd.*, mk.make_name,md.model_name,sm.submodel_name FROM th_vehicle_details as vd 
LEFT JOIN th_make as mk ON mk.make_id=vd.make 
LEFT JOIN th_model as md ON md.model_id=vd.model
LEFT JOIN th_submodel as sm ON sm.submodel_id=vd.submodel
WHERE vd.order_type=1 AND vd.order_id = '$order_data->order_number'";
$vehicleInfo = $wpdb->get_row($TyreInfo);

$TyreInfo = "SELECT * FROM th_vehicle_tyre_information WHERE order_id = '$order_data->order_number'";
$TyreDetails = $wpdb->get_results($TyreInfo);


?>
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
				?>
			</td>
		</tr>
		<tr>
			<td colspan="2">
					<h1 class="document-type-label">TAX INVOICE</h1>
					<table class="order-data-addresses">
					<tr>
						<td class="address billing-address" style="width: 50%;">
							<h3><?php _e( 'Billing Address:', 'woocommerce-pdf-invoices-packing-slips' ); ?></h3>
							<?php do_action( 'wpo_wcpdf_before_billing_address', $this->type, $this->order ); ?>
							<?php echo $order_data->billing_first_name.' '.$order_data->billing_last_name;?>
							<?php do_action( 'wpo_wcpdf_after_billing_address', $this->type, $this->order ); ?>
							<div class="billing-email">
								<?php
									echo $order_data->billing_email;
								?>
							</div>
							<div class="billing-phone"><div> Mobile number : <?php echo  $order_data->billing_phone; ?> </div></div>
		
					<table>			
						<?php if($order_data->gst_no != '' || $order_data->cmp_add != '' || $order_data->cmp_name != ''){?>
							<tr>
							<td colspan="1">
								<?php
                                                                echo '<div style="border:1px solid #000;padding:5px;">';
								if($order_data->cmp_name != ''){	
									
									echo '<div> Company Name : '.$order_data->cmp_name.'</div>';
								}
								if($order_data->cmp_add != ''){
									echo '<div> Company Address : '.$order_data->cmp_add.'</div>';
								}
								if($order_data->gst_no != ''){
									echo '<div> GSTIN : '.$order_data->gst_no.'</div>';
									
								}
                                                                echo '</div>';
								?>
							</td>
						</tr>
						<?php } ?>
					</table>

						</td>
							<td class="order-data">
							<table>			
								<tr class="invoice-number">
									<th><?php _e( 'Invoice Number:', 'woocommerce-pdf-invoices-packing-slips' ); ?></th>
									<td><?php echo  $order_data->order_number; ?></td>
								</tr>
								<tr class="invoice-date">
									<th><?php _e( 'Invoice Date:', 'woocommerce-pdf-invoices-packing-slips' ); ?></th>
									<td><?php echo  date('d-m-Y h:i A',strtotime($order_data->date_completed)); ?></td>
								</tr>
								<tr class="order-number">
									<th><?php _e( 'Order Number:', 'woocommerce-pdf-invoices-packing-slips' ); ?></th>
									<td><?php echo  $order_data->order_number; ?></td>
								</tr>
								<tr class="order-date">
									<th><?php _e( 'Order Date:', 'woocommerce-pdf-invoices-packing-slips' ); ?></th>
									<td><?php echo  date('d-m-Y h:i A',strtotime($order_data->date_completed)); ?></td>
								</tr>
								<tr class="payment-method">
									<th><?php _e( 'Payment Method:', 'woocommerce-pdf-invoices-packing-slips' ); ?></th>
									<td><?php echo 	get_payment_method($order_data->payment_method);?></td>
								</tr>
							</table>
						</td>
					</tr>

					</table>
			</td>
		</tr>
	</table>


<div style="margin-bottom: 5px;margin-top: -70px;">*Keep this invoice for guarantee and warranty purposes.</div>
<table class="order-details" style="font-size:12px">
<thead>
	<tr>
		<th class="product"><?php _e('Product', 'woocommerce-pdf-invoices-packing-slips' ); ?></th>
		<th class="hsn" style="text-align: center;"><?php _e('HSN / SCA', 'woocommerce-pdf-invoices-packing-slips' ); ?></th>
		<th class="gst" style="text-align: center;"><?php _e('GST', 'woocommerce-pdf-invoices-packing-slips' ); ?></th>
		<th class="quantity" style="text-align: center;"><?php _e('Qty', 'woocommerce-pdf-invoices-packing-slips' ); ?></th>
		<th class="price" style="text-align:center;"><?php _e('Price', 'woocommerce-pdf-invoices-packing-slips' ); ?></th>
		<th class="price" style="text-align:center;"><?php _e('Discount', 'woocommerce-pdf-invoices-packing-slips' ); ?></th>
		<th class="price" style="text-align:center;"><?php _e('Amount', 'woocommerce-pdf-invoices-packing-slips' ); ?></th>
		<!-- <th class="price"><?php _e('SGST', 'woocommerce-pdf-invoices-packing-slips' ); ?></th>
		<th class="price"><?php _e('CGST', 'woocommerce-pdf-invoices-packing-slips' ); ?></th> -->
	</tr>
</thead>
<tbody>
	<?php
	/*$SQLORD="SELECT * FROM wp_franchise_order WHERE order_id='".$order_id."'";
	$orderdata=$wpdb->get_row($SQLORD);*/

	 $SQLITEM="SELECT * FROM wp_franchise_order_items WHERE order_id='".$order_id."'";
	 $products=$wpdb->get_results($SQLITEM);

if( sizeof( $products ) > 0 ) : 
		

		$i=0;
		$j=0;
		foreach( $products as $item_id => $item ) {
			$product_array= array(get_option("car_wash"),get_option("balancing_alignment"));
			$product_id = get_cust_ord_meta($item->order_item_id,'_product_id',true);
			if(in_array($product_id,$product_array)){
				$car_serivces[$i]=$item;
				$i++;
			}else{
				$products1[$j]=$item;
				$j++;
			}
		}
	if(!empty($products1) && !empty($car_serivces)){
		$products1=array_merge($products1,$car_serivces);
	}elseif($car_serivces){
		$products1=$car_serivces;
	}else{
		$products1=$products1;	
	}

/*echo '<pre>';
print_r($products1);
die;*/
foreach( $products1 as $item_id => $item ) :
		$taxgst=0;
		$product_id = get_cust_ord_meta($item->order_item_id,'_product_id',true);
		$_qty =get_cust_ord_meta($item->order_item_id,'_qty',true);
		$_line_subtotal =get_cust_ord_meta($item->order_item_id,'_line_subtotal',true);
		$_line_total =get_cust_ord_meta($item->order_item_id,'_line_total',true);
		$_sgst =get_cust_ord_meta($item->order_item_id,'_sgst',true);
		$_cgst =get_cust_ord_meta($item->order_item_id,'_cgst',true);
		$vehicle_name1 =get_cust_ord_meta($item->order_item_id,'vehicle_name',true);

		$tyre_gst = get_cust_ord_meta($item->order_item_id,'tyre_gst',true);
		$service_gst = get_cust_ord_meta($item->order_item_id,'service_gst',true);
		
		$taxgst=(($_sgst + $_cgst) / $_qty);
		//$taxgst = $taxgst * $_qty;
		 $_line_subtotal=$_line_subtotal / $_qty;

		$product_array= array(get_option("car_wash"),get_option("balancing_alignment"));
		if(in_array($product_id,$product_array)){
			
			if($product_id==get_option("balancing_alignment")){
				$variation_des = 'Balancing & Alignment x '.$vehicle_name1;
			}else{
				$variation_des ='Car Wash x '.$vehicle_name1;
			}
			$HSN='9954';
			$GSTVALUE = $service_gst."%";
			$service_gstnew = $service_gst;
			$service_gstD = ($service_gst/2);
			$SGST= $service_gstD.'%';
			$CGST = $service_gstD.'%';
			

		}else{
			$product_variation = wc_get_product($product_id);
			$variation_des = $product_variation->get_description();
			$variation_price = $product_variation->get_price();
			$HSN='4011';
			$GSTVALUE = $tyre_gst.'%';
			$tyre_gstD = ($tyre_gst/2);
			$SGST = $tyre_gstD.'%';
			$CGST = $tyre_gstD.'%';
			
		}
		

		$parent_id = wp_get_post_parent_id($product_id);
		//$guarantee_text = get_post_meta($parent_id, '_guarantee_cart', true );
		$guarantee_text = get_cust_ord_meta($item->order_item_id,'guarantee_text',true); 

		$installer = "SELECT *
							FROM th_franchise_cart_item
							WHERE order_id = '$order_data->order_number' and product_id = '$product_id'";
			$installerdata = $wpdb->get_row($installer);
			$vehicle_id = $installerdata->vehicle_id;			

			$vehicle_name = $wpdb->get_var("SELECT vehicle_type FROM th_vehicle_type WHERE vehicle_id='$vehicle_id'");

		
	?>
	<tr class="">

		<td class="product" style="border-bottom: 0px; width: 40%;">
		
			<span class="item-name" style="height:45px;">
				<?php
					echo $variation_des;
					
				?>
			</span>
		</td>

		<td style="width: 5%;"> <?=$HSN?> </td>
		<td style="width: 5%;"> <?=$GSTVALUE?> </td>
		<td class="quantity" style="text-align: center;"><?php echo $_qty; ?></td>
		<td class="price" style="text-align:right">
		<?php
			echo get_woocommerce_currency_symbol(). number_format((float)($_line_subtotal - $taxgst ), 2, '.', ''); ?>
		</td>

		<td style="width: 10%; text-align: center;" class="discount-column" >
			<?php
			echo get_woocommerce_currency_symbol(). number_format((float)0.00, 2, '.', ''); ?>
		</td>

		<td style="width: 20%;text-align:right">
			<?php 
			echo get_woocommerce_currency_symbol(). number_format((float)(($_line_subtotal-$taxgst) * $_qty), 2, '.', '');
			?>
				<?php //echo '<p style="margin-top:30px;text-align:right"><span class="item-name">'.get_woocommerce_currency_symbol(). number_format((float)$_sgst, 2, '.', '').'</span></p>'; ?>
				<?php //echo '<p  style="text-align:right;margin:0;padding:0"><span class="item-name">'.get_woocommerce_currency_symbol(). number_format((float)$_cgst, 2, '.', '').'</span></p>'; ?>
		</td>
	</tr>
				
				<tr>
				
				<td colspan="7" style="width: 20%;text-align:right;border: none;">
				<?php echo '<span class="item-name">SGST - '.$SGST.' (OUTPUT)  '.get_woocommerce_currency_symbol(). number_format((float)$_sgst, 2, '.', '').'</span>'; ?><br>
				<?php echo '<span class="item-name">CGST - '.$CGST.' (OUTPUT)  '.get_woocommerce_currency_symbol(). number_format((float)$_cgst, 2, '.', '').'</span>'; ?>


				</td>

				</tr>
				<!--<tr>
				<td colspan="7" style="border: none;">
				<?php
					if($guarantee_text != '')
					{
						echo '<div class="guarantee-info">*'.$guarantee_text.'</div>';
					}
					?>
				</td>
				
				</tr>-->
				<?php if($vehicleInfo->model_name != '' && $vehicleInfo->car_number != '' && $vehicleInfo->odo_meter != ''){ ?>
				<tr>
				<td colspan="7" style="border: none;">
				<strong>Vehicle Detail:</strong> <?=$vehicleInfo->make_name;?>,<?=$vehicleInfo->model_name;?>,<?=$vehicleInfo->submodel_name;?>, <?=$vehicleInfo->car_number;?><br> <strong>Odo Meter-</strong><?=$vehicleInfo->odo_meter;?> <br>
					
					<?php 
					$serial_number = array();
					foreach($TyreDetails as $key=>$value){ 
						$serial_number[] = $value->serial_number; 
					
					}
					$serial=implode(',',$serial_number);
					if(!empty($serial_number)){
						echo "<strong>Tyre Serial Number:</strong>"; 
						echo $serial;
					}
					?>
				
				</td>
				</tr>
				<?php }?>
				<?php if($vehicle_name){?>
				<tr>
					<td colspan="7" style="border: none;">					
						<b>Vehicle Type : </b><?php echo $vehicle_name; ?>
					</td>
					
				</tr>
			<?php } ?>

			<?php 
				$sub_total = ($sub_total + $line_subtotal);
				$invoice_total = ($invoice_total + $_line_total);
			?>

			<?php
				
				if(!empty($installerdata))
				{
					
								 $services = "SELECT *
										FROM th_franchise_cart_item_services
										WHERE order_id = '$order_data->order_number' and product_id = '$product_id'";
									$row = $wpdb->get_results($services);
									
									$service_name = '';
									$service_list = [];
									$amount = '';
									$total_amout = 0;
									$bforegst=0;
									foreach ($row as $key => $service)
									{
										$tyre_count = $service->tyre;
										$service_name = $service->service_name;
										$rate = $service->rate;

										$service_list[$service_name] = $tyre_count;
									echo '<tr>';
										if($service_data_id ==1){ // 'Tyre Fitment'
											
											if($rate == 0){
												echo '<td>'.$service_name.' - <b>FREE</b></td>';
											}
											else{
												echo '<td>'.$service_name.'</td>';
											}

										}else{
											//$bforegst=$bforegst + $rate;
											$service_gstt = (100 + $service_gst);
											$tax = ($rate*$service_gst)/$service_gstt;
											//$sgst=$tax / 2;
											//$cgst=$tax / 2;
											$amount = number_format($rate-$tax,2);
											echo '<td>'.$service_name.'</td>';
										}
										echo '<td style="width: 5%;"> 9954 </td>';
										echo '<td style="width: 5%;"> '.$service_gst.'% </td>';


										echo '<td></td>';
										echo '<td style="text-align:right;">'.get_woocommerce_currency_symbol().$amount.'</td>';
										echo '<td></td>';
										echo '<td style="text-align:right;">'.get_woocommerce_currency_symbol().$amount.'</td>';
										$total_amout = $total_amout + $amount;											
										echo '</tr>';
										$bforegst=$bforegst + $rate;


									}

											$service_gstt = (100 + $service_gst);
											$tax = ($bforegst*$service_gst)/$service_gstt;
											$sgst=$tax / 2;
											$cgst=$tax / 2;
										
										$service_gstD = ($service_gst/2);	
									?>


				<tr>
					<td colspan="7" style="text-align:right;border-bottom: double 2px;">

					<?php echo '<span class="item-name">SGST - '.$service_gstD.'% (OUTPUT)  '.get_woocommerce_currency_symbol(). number_format((float)$sgst, 2, '.', '').'</span>'; ?><br>
					<?php echo '<span class="item-name">CGST - '.$service_gstD.'% (OUTPUT)  '.get_woocommerce_currency_symbol(). number_format((float)$cgst, 2, '.', '').'</span>'; ?>


					</td>
				</tr>
				<?php 
			}

				
		
?>
<?php endforeach; endif; ?>


</tbody>
<tfoot>
	<tr class="no-borders">
			<td colspan="5">Amount in words: <?php echo getIndianCurrency($order_data->total);?></td>
			<td colspan="2" class="">
				<table class="totals">
					<tfoot>
						<tr class="">
						<th class="description1" style="width: 20%;">Subtotal</th>
						<td class="price1"  style="text-align:right"><span class="totals-price1"><?php echo get_woocommerce_currency_symbol(). number_format((float)$order_data->total - $bforegst, 2, '.', '') ?></span></td>
					</tr>
					<tr class="">
						<th class="description1" style="width: 20%;">Service Charges</th>
						<td class="price1" style="text-align:right"><span class="totals-price1"><?php echo get_woocommerce_currency_symbol(). number_format((float)$bforegst, 2, '.', '') ?></span></td>
					</tr>
					<tr class="">
						<th class="description1" style="width: 20%; text-align:right">Total</th>
						<td class="price1" style="text-align:right"><span class="totals-price1"><?php echo get_woocommerce_currency_symbol(). number_format((float)$order_data->total, 2, '.', ''); ?></span></td>
					</tr>
					</tfoot>
				</table>
			</td>
		</tr>

</tfoot>
</table>

<div id="footer">
<ul class="terms-conditions">
	<li>* Guarantee and Warranty are subject to Tyre Company’s terms and condition.</li>
	<li>* Customers are responsible to claim their Tyre’s warranty directly with manufacturers.</li>
	<li>* If consumer is submitting claim for tyre(s) Guarantee or warranty, consumer must wait till Inspector come from the Tyre Company and inspect the tyre.</li>
	<li>That tyre replacement it's depend on Tyre Company.</li>
	<li>* It is customer's responsibility to call tyre company and register guarantee and warranty.</li>
</ul>
<div class="border" style="width: 100%;"></div>
<div class="name" style="width: 49%; float: left;">This is a Computer Generated Invoice</div>
<div class="shop-phone" style="width: 49%; float: right; text-align: right;"><p style="margin:0">www.Tyrehub.com</p><p style="margin:0"> <?php echo $this->extra_1();?></p></div>
</div>
