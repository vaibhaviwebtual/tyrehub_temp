<?php
if ( ! defined( 'ABSPATH' ) ) exit; // Exit if accessed directly ?>
<?php do_action( 'wpo_wcpdf_before_document', $this->type, $this->order ); ?>

<table class="head container">
<tr>

	<td class="shop-info">
		<div class="shop-name"><h3><?php $this->shop_name(); ?></h3></div>
		<div class="shop-address"><?php $this->shop_address(); ?></div>
	</td>
	<td class="header">
		<?php
		if( $this->has_header_logo() ) {
			$this->header_logo();
		} else {
			echo $this->get_title();
		}
		?><br>
		<span style="font-size: 20px!important;"><?php echo $this->extra_1();?></span>
	</td>
</tr>
</table>

<h1 class="document-type-label">
<?php if ( $order->has_status('completed') ) {
	echo  "TAX INVOICE";
}else{
	echo "Order Challan";
	}	?>

<?php //if( $this->has_header_logo() ) echo $this->get_title(); ?>
</h1>

<?php do_action( 'wpo_wcpdf_after_document_label', $this->type, $this->order ); ?>
<?php
$user = $order->get_user();

$user_idd = $user->ID;
$user_role = $user->roles[0];
global $wpdb;

$installer = "SELECT * FROM th_cart_item_installer WHERE order_id = '$order_id'";
$row = $wpdb->get_results($installer);
foreach ($row as $key => $installer)
{
	$destination = $installer->destination;

}

$TyreInfo = "SELECT vd.*, mk.make_name,md.model_name,sm.submodel_name FROM th_vehicle_details as vd 
LEFT JOIN th_make as mk ON mk.make_id=vd.make 
LEFT JOIN th_model as md ON md.model_id=vd.model
LEFT JOIN th_submodel as sm ON sm.submodel_id=vd.submodel
WHERE vd.order_id = '$order_id'";
$vehicleInfo = $wpdb->get_row($TyreInfo);

$TyreInfo = "SELECT * FROM th_vehicle_tyre_information WHERE order_id = '$order_id'";
$TyreDetails = $wpdb->get_results($TyreInfo);

?>

<table class="order-data-addresses">
<tr>
	<td class="address billing-address" style="width: 50%;">
		<p style="font-size: 15px; font-weight: bold;"> Customer</p>
		<?php
				$gst_no = get_post_meta( $order_id, '_gst_no', true );
				$cmp_name = get_post_meta( $order_id, '_cmp_name', true );
				$cmp_add = get_post_meta( $order_id, '_cmp_add', true );
		if($user_role == "Installer"){
					$installer = "SELECT * FROM th_installer_data WHERE user_id = '$user_idd'";
					$row = $wpdb->get_results($installer);
					foreach ($row as $key => $value)
					{
						$gst_no = $value->gst_no;
						$cmp_name = $value->company_name;
						$cmp_add = $value->company_add;
						$business_name = $value->business_name;
					}


			?>
			<p><strong><?php echo $business_name; ?></strong></p>
		<?php
		}
		?>
		<table>
		<tr>
			<td colspan="1">				
		<!-- <h3><?php _e( 'Billing Address:', 'woocommerce-pdf-invoices-packing-slips' ); ?></h3> -->
		<?php do_action( 'wpo_wcpdf_before_billing_address', $this->type, $this->order ); ?>
		<?php $this->billing_address(); ?>
		<?php do_action( 'wpo_wcpdf_after_billing_address', $this->type, $this->order ); ?>
		<?php //if ( isset($this->settings['display_email']) ) { ?>
		<div class="billing-email">
			<?php
				$order = wc_get_order( $order_id );
				$order_data = $order->get_data();
				$order_billing_email = $order_data['billing']['email'];

				if($order_billing_email != 'sales@tyrehub.com' && $order_billing_email != 'admin@tyrehub.com' && $order_billing_email != 'tyrehub-admin2@test.com' && $order_billing_email != 'tyrehub-admin3@test.com' && $order_billing_email != 'tyrehub-admin@test.com')
				{
					echo $this->billing_email();
				}
			
			?>
		</div>
		<div class="billing-phone"><div> Mobile number : <?php echo  $this->billing_phone(); ?> </div></div>
		

	</td>
	<td class="address shipping-address">

		<?php
		 if ($destination==0 && get_post_meta($order_id,'ship_to_different_address',true)==1) { ?>
		<h3><?php _e( 'Shipping Address:', 'woocommerce-pdf-invoices-packing-slips' ); ?></h3>
		<?php  $this->shipping_address(); ?>
		<?php } ?>
	</td>
</tr></table>
	<td class="order-data">
		<table>
			<?php do_action( 'wpo_wcpdf_before_order_data', $this->type, $this->order ); ?>
			<?php if ( $order->has_status('completed') ) { ?>
			<?php if ( isset($this->settings['display_number']) ) { ?>
			<tr class="invoice-number">
				<th><?php _e( 'Invoice Number:', 'woocommerce-pdf-invoices-packing-slips' ); ?></th>
				<td><?php $this->invoice_number(); ?></td>
			</tr>
			<?php } ?>
			<?php if ( isset($this->settings['display_date']) ) { ?>
			<tr class="invoice-date">
				<th><?php _e( 'Invoice Date:', 'woocommerce-pdf-invoices-packing-slips' ); ?></th>
				<td><?php $this->invoice_date(); ?></td>
			</tr>
			<?php } ?>
			<?php }?>
			<tr class="order-number">
				<th><?php _e( 'Order Number:', 'woocommerce-pdf-invoices-packing-slips' ); ?></th>
				<td><?php $this->order_number(); ?></td>
			</tr>
			<tr class="order-date">
				<th><?php _e( 'Order Date:', 'woocommerce-pdf-invoices-packing-slips' ); ?></th>
				<td><?php $this->order_date(); ?></td>
			</tr>
			<tr class="payment-method">
				<th><?php _e( 'Payment Method:', 'woocommerce-pdf-invoices-packing-slips' ); ?></th>
				<td><?php $this->payment_method(); ?></td>
			</tr>
			<?php do_action( 'wpo_wcpdf_after_order_data', $this->type, $this->order ); ?>
		</table>
	</td>
</tr>
<tr>
	<td colspan="2"><div class="billing-phone" style="width: 100%;">
			<?php

				if($cmp_name != '')
				{	echo '<div style="border:1px solid #000;padding:5px;">';
					echo '<div> Company Name : '.$cmp_name.'</div>';
				}
				if($cmp_add != '')
				{
					echo '<div> Company Address : '.$cmp_add.'</div>';
				}
				if($gst_no != '')
				{
					echo '<div> GSTIN : '.$gst_no.'</div>';
					echo '</div>';
				}


			?>
		</div></td>
</tr>
</table>

<?php do_action( 'wpo_wcpdf_before_order_details', $this->type, $this->order ); ?>
<div style="margin-bottom: 5px;margin-top: -10px;">*Keep this invoice for guarantee and warranty purposes.</div>
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
	$i=0;
	$items = $this->get_order_items(); if( sizeof( $items ) > 0 ) : foreach( $items as $item_id => $item ) :
		$item_data = $item;
		//var_dump($item_data);

		global $wpdb,$woocommerce;
		$sku = 'service_voucher';
		$SQL="SELECT post_id FROM $wpdb->postmeta WHERE meta_key='_sku' AND meta_value='$sku' LIMIT 1";
		$service_voucher_prd = $wpdb->get_var($SQL);

		$taxable_value = wc_get_order_item_meta($item_id,'taxable_value',true);
		
		$tyreGST = wc_get_order_item_meta($item_id,'tyre_gst',true);
		$serviceGST = wc_get_order_item_meta($item_id,'service_gst',true);
		$serviceGST_2 = $serviceGST/2;
		$tyreGST_2 = $tyreGST/2;
		
		$cgst = wc_get_order_item_meta($item_id,'cgst',true);
		$sgst = wc_get_order_item_meta($item_id,'sgst',true);
		$serviceGST_2 = $serviceGST/2;
		$discount = wc_get_order_item_meta($item_id,'discount',true);
		$service_taxable = wc_get_order_item_meta($item_id,'service_taxable',true);
		$service_gst = wc_get_order_item_meta($item_id,'service_sgst',true);
		$delivery_charge = wc_get_order_item_meta($item_id,'delivery_charge',true);
		$tyre_price = number_format((float)$taxable_value, 2, '.', '');
		$order_price_per_unit = $tyre_price / $item['quantity'] ;

		$item_discount_rule = wc_get_order_item_meta($item_id,'_discount_rule',true);

		$item_discount_amount = wc_get_order_item_meta($item_id,'_discount_rule_amount',true);

		$installer_discount = wc_get_order_item_meta($item_id,'installer_discount',true);

		$rule_name = '';
		if($item_discount_rule != '')
		{
			$rule_sql = "SELECT * FROM th_discount_rule where rule_id = $item_discount_rule";
			$rule_data = $wpdb->get_results($rule_sql);
			$rule_name = $rule_data[0]->name;
		}

		if($item_data['variation_id'] != '')
		{
			$product_id = $item_data['variation_id'];
		}else{
			$product_id = $item_data['product_id'];
		}

		if($product_id != $service_voucher_prd){
	?>
	<tr class="<?php echo apply_filters('wpo_wcpdf_item_row_class', $item_id, $this->type, $this->order, $item_id ); ?>">

		<td class="product" style="border-bottom: 0px; width: 40%;">
			<?php $description_label = __( 'Description', 'woocommerce-pdf-invoices-packing-slips' ); // registering alternate label translation ?>
			<span class="item-name" style="height:45px;">
				<?php
					$product_variation = wc_get_product( $product_id );
					echo $variation_des = $product_variation->get_description();
					$variation_price = $product_variation->get_price();

					$parent_id = wp_get_post_parent_id($product_id);
					//$guarantee_text = get_post_meta($parent_id, '_guarantee_cart', true );
					$guarantee_text = wc_get_order_item_meta($item_id,'guarantee_text',true);

				?>
			</span>
			<?php do_action( 'wpo_wcpdf_before_item_meta', $this->type, $item, $this->order  ); ?>

			<?php do_action( 'wpo_wcpdf_after_item_meta', $this->type, $item, $this->order  ); ?>
			<?php //echo '<p style="margin-top:15px;text-align:right"><span class="item-name">SGST - '.$tyreGST_2.'% (OUTPUT)</span></p>'; ?>
			<?php //echo '<p  style="text-align:right;margin:0;padding:0"> <span class="item-name">CGST - '.$tyreGST_2.'% (OUTPUT)</span> </p>'; ?>
			<?php
				// if($guarantee_text != '')
				// {
				// 	echo '<div class="guarantee-info">*'.$guarantee_text.'</div>';
				// }
			?>
		</td>

		<td style="width: 5%;"> 4011 </td>
		<td style="width: 5%;"> <?php echo $tyreGST; ?>% </td>
		<td class="quantity" style="text-align: center;"><?php echo $item['quantity']; ?></td>

		<td class="price" style="text-align:right">
		<?php
			if($user_role != 'Installer')
			{

				if($item_discount_amount == ''){

					echo get_woocommerce_currency_symbol(). number_format((float)$order_price_per_unit, 2, '.', '');
				} else{
				$new_discount_tacable_value = $order_price_per_unit + $item_discount_amount;
				echo get_woocommerce_currency_symbol(). number_format((float)$new_discount_tacable_value, 2, '.', '');
				 }
			}
			else{
				echo get_woocommerce_currency_symbol(). number_format((float)$order_price_per_unit + $installer_discount, 2, '.', '');
			} ?>
		</td>
		<td style="width: 10%; text-align: center;" class="discount-column" >
			<?php
			if($user_role != 'Installer')
			{
				if($item_discount_amount == '')
				{ echo ''; }
				else{
						echo get_woocommerce_currency_symbol(). number_format((float)$item_discount_amount, 2, '.', '');
					}
			?><br>
			<?php if($rule_name != ''){ echo '('.$rule_name.')'; }
			}else{
				echo get_woocommerce_currency_symbol(). number_format((float)$installer_discount, 2, '.', '');
			}
			?>
		</td>
		<td style="width: 20%;text-align:right"><?php echo get_woocommerce_currency_symbol(). number_format((float)$taxable_value, 2, '.', ''); ?></td>
	
	
	</tr>

	<tr>
		<td colspan="7" style="width: 20%;text-align:right;border: none;">
			<?php echo '<span class="item-name">SGST - '.$tyreGST_2.'% (OUTPUT)  '.get_woocommerce_currency_symbol(). number_format((float)$sgst, 2, '.', '').'</span>'; ?><br>
			<?php echo '<span class="item-name">CGST - '.$tyreGST_2.'% (OUTPUT)  '.get_woocommerce_currency_symbol(). number_format((float)$cgst, 2, '.', '').'</span>'; ?>
		</td>
	</tr>
	<?php if($vehicleInfo->model_name){?>
		<tr>
					<td colspan="7" style="border: none;">					
						<strong>Vehicle Detail:</strong> <?=$vehicleInfo->model_name;?>, <?=$vehicleInfo->car_number;?>
						<br> <strong>Odo Meter-</strong><?=$vehicleInfo->odo_meter;?> <br>
						<strong>Tyre Serial Number:</strong> 
						<?php 
						$serial_number = array();
						foreach($TyreDetails as $key=>$value){ 
							$serial_number[] = $value->serial_number; 
						
						}
						$serial=implode(',',$serial_number);
						//echo substr_replace($serial ,"",-1);
						echo $serial;
						?>
					<?php
						if($guarantee_text != '')
						{
							
							//echo '<div class="guarantee-info">*'.$guarantee_text.'</div>';
						}
						?>
					</td>
		</tr>
	<?php }?>

	<?php } ?>

	<!-- Tyrehub custom code -->
	<?php

		$user = $order->get_user();
		$user_role = $user->roles[0];
		if($user_role != 'Installer')
		{

			$item_data = $item;
			//var_dump($item_data);

			if($item_data['variation_id'] != ''){
				$product_id = $item_data['variation_id'];
			}
			else{
				$product_id = $item_data['product_id'];
			}

			if($product_id != $service_voucher_prd)
			{
				$installer = "SELECT *
							FROM th_cart_item_installer
							WHERE order_id = '$order_id' and product_id = '$product_id'";
				$row = $wpdb->get_results($installer);

				if(!empty($row))
				{
					foreach ($row as $key => $installer)
					{
						$destination = $installer->destination;
						$installer_table_id = $installer->cart_item_installer_id;
						$installer_id = $installer->installer_id;
						$vehicle_id = $installer->vehicle_id;
						$installer_name = $wpdb->get_var( $wpdb->prepare( "SELECT business_name FROM th_installer_data WHERE installer_data_id='%s' LIMIT 1" , $installer_id) );

						$vehicle_name = $wpdb->get_var( $wpdb->prepare( "SELECT vehicle_type FROM th_vehicle_type WHERE vehicle_id='%s' LIMIT 1" , $vehicle_id) );
						$selected_vehicle_id = $installer->vehicle_id;
					}?>

					<?php if($vehicle_name){?>
				<tr>
					<td colspan="7" style="border: none;">					
						<b>Vehicle Type :</b> <?php echo $vehicle_name; ?>
					</td>
					
				</tr>
			<?php } ?>	

					<?php
									 $services = "SELECT *
											FROM th_cart_item_services
											WHERE order_id = '$order_id' and product_id = '$product_id'";
									$row = $wpdb->get_results($services);

									$service_name = '';
									$service_list = [];
									$amount = '';
									$total_amout = 0;
									foreach ($row as $key => $service)
									{
										$tyre_count = $service->tyre;
										$service_name = $service->service_name;
										$rate = $service->rate;

										$service_list[$service_name] = $tyre_count;
									echo '<tr>';
										if($service_name == 'Wheel alignment'){
											$amount1 = $rate;
											$serviceGSTD = (100 + $serviceGST);
											$tax = ($amount1*$serviceGST)/$serviceGSTD;
											$amount = number_format(($amount1)-$tax,2);
											echo '<td>'.$service_name.'</td>';
										}
										elseif($service_name == 'Tyre Fitment'){
											$amount = $rate;
											if($rate == 0){
												echo '<td>'.$service_name.' - <b>FREE</b></td>';
											}
											else{
												echo '<td>'.$service_name.'</td>';
											}

										}
										elseif($service_name == 'Pickup & Drop Off Service'){
											$amount1 = $rate;
											$serviceGSTD = (100 + $serviceGST);
											$tax = ($amount1*$serviceGST)/$serviceGSTD;
											$amount = number_format(($amount1)-$tax,2);
											echo '<td>'.$service_name.'</td>';

										}
										else{
											$amount1 = $tyre_count * $rate;
											$serviceGSTD = (100 + $serviceGST);
											$tax = ($amount1*$serviceGST)/$serviceGSTD;
											$amount = number_format(($tyre_count * $rate)-$tax,2);
											echo '<td>'.$service_name.'</td>';
										}
										echo '<td style="width: 5%;"> 9954 </td>';
										echo '<td style="width: 5%;"> '.$serviceGST.'% </td>';


										echo '<td></td>';
										echo '<td style="text-align:center;">'.get_woocommerce_currency_symbol().$amount.'</td>';
										echo '<td></td>';
										echo '<td style="text-align:center;">'.get_woocommerce_currency_symbol().$amount.'</td>';
										$total_amout = $total_amout + $amount;

										echo '</tr>';
									}
									
									?>


					<tr>

					<td style="border-bottom: double 2px;">
					<?php

						if($destination == '0')
						{ ?>
								<div class="installer-name" style="height:45px;"><?php echo '<b>Free Home Delivery </b>'; ?></div>
								<?php echo '<p style="margin-top:15px;text-align:right"><span class="item-name">SGST - '.$serviceGST_2.'% (OUTPUT)</span></p>'; ?>
			<?php echo '<p  style="text-align:right;margin:0;padding:0"> <span class="item-name">CGST - '.$serviceGST_2.'% (OUTPUT)</span> </p>'; ?>
					   <?php }
						else
						{
					?>


								<div class="installer-name"><?php echo '<b>'.$installer_name.'</b>'; ?></div>
							<?php //echo '<p style="margin-top:15px;text-align:right"><span class="item-name">SGST - 9% (OUTPUT)</span></p>'; ?>
					<?php //echo '<p  style="text-align:right;margin:0;padding:0"> <span class="item-name">CGST - 9% (OUTPUT)</span> </p>'; ?>
					<?php /* if($vehicle_name !='') { ?>
						<div class="vehicle-typre" style="margin-top: 15px">Vehicle Type : <b><?php echo $vehicle_name; ?></b></div>
					<?php } */ ?>

		<?php } ?>

				</td>
				<td style="width: 5%;border-bottom: double 2px;"> 9954 </td>
				<td style="width: 5%;border-bottom: double 2px;"> <?php echo $serviceGST; ?>% </td>
				<td style="border-bottom: double 2px;"></td>
				<td style="text-align:center;border-bottom: double 2px;">
					<?php
						if($destination != '0')
						{
							echo get_woocommerce_currency_symbol().number_format($service_taxable,2,'.','');
						}
						if($destination == '0')
						{

							$cart_item_qty = $item['quantity'];
							 //$product_id = $cart_item['variation_id'];
								$product_variation_new = wc_get_product( $product_id );
									$prd_attr_vehicle = '';
									$variation_data = $product_variation_new->get_data();
										if($variation_data['attributes']['pa_vehicle-type'] != 'car-tyre'){
										   $prd_attr_vehicle = $variation_data['attributes']['pa_vehicle-type'];
									}

									if($prd_attr_vehicle != ''){
										if($cart_item_qty >= 2 && $cart_item_qty <= 5 ){
											$home_delivery_charge = 200;
										}else if($cart_item_qty >= 6){
											$home_delivery_charge = 300;
										}else{
											$home_delivery_charge = 100;
										}
									}else{
										if($cart_item_qty >= 2 && $cart_item_qty <= 5 ){
											 $home_delivery_charge = 250;
										}else if($cart_item_qty >= 6){
											$home_delivery_charge = 400;
										}else{
											$home_delivery_charge = 150;
										}
									}
							$home_delivery_charge=0;
							if($delivery_charge){
								$home_delivery_charge = $home_delivery_charge;
								echo get_woocommerce_currency_symbol().number_format($service_taxable,2,'.','');
							}else{
								//$home_delivery_charge = 100 * $cart_item_qty;
								echo get_woocommerce_currency_symbol().number_format($home_delivery_charge,2,'.','');
							}

						}
					?>
				</td>

				<td colspan="2" style="width: 20%;text-align:right;border-bottom: double 2px;">
					<?php echo get_woocommerce_currency_symbol().number_format($service_taxable,2,'.',''); ?>
				<?php echo '<p style="margin-top:30px;text-align:right"><span class="item-name">SGST - '.$serviceGST_2.'% (OUTPUT)  '.get_woocommerce_currency_symbol(). number_format((float)$service_gst, 2, '.', '').'</span></p>'; ?>
				<?php echo '<p  style="text-align:right;margin:0;padding:0"><span class="item-name">CGST - '.$serviceGST_2.'% (OUTPUT)  '.get_woocommerce_currency_symbol(). number_format((float)$service_gst, 2, '.', '').'</span></p>'; ?>


				</td>

				</tr>
				<?php } 

			}else{  
			}

		}
?>
<?php endforeach; endif; ?>

<?php 

	$items = $this->get_order_items(); if( sizeof( $items ) > 0 ) : foreach( $items as $item_id => $item ) :
		$item_data = $item;
		//var_dump($item_data);

		global $wpdb,$woocommerce;
		$sku = 'service_voucher';
		$SQL="SELECT post_id FROM $wpdb->postmeta WHERE meta_key='_sku' AND meta_value='$sku' LIMIT 1";
		$service_voucher_prd = $wpdb->get_var($SQL);

		$taxable_value = wc_get_order_item_meta($item_id,'taxable_value',true);
		
		$tyreGST = wc_get_order_item_meta($item_id,'tyre_gst',true);
		$serviceGST = wc_get_order_item_meta($item_id,'service_gst',true);
		$serviceGST_2 = $serviceGST/2;
		$tyreGST_2 = $tyreGST/2;
		
		$cgst = wc_get_order_item_meta($item_id,'cgst',true);
		$sgst = wc_get_order_item_meta($item_id,'sgst',true);
		$serviceGST_2 = $serviceGST/2;
		$discount = wc_get_order_item_meta($item_id,'discount',true);
		$service_taxable = wc_get_order_item_meta($item_id,'service_taxable',true);
		$service_gst = wc_get_order_item_meta($item_id,'service_sgst',true);
		$delivery_charge = wc_get_order_item_meta($item_id,'delivery_charge',true);
		$tyre_price = number_format((float)$taxable_value, 2, '.', '');
		$order_price_per_unit = $tyre_price / $item['quantity'] ;

		$item_discount_rule = wc_get_order_item_meta($item_id,'_discount_rule',true);

		$item_discount_amount = wc_get_order_item_meta($item_id,'_discount_rule_amount',true);

		$installer_discount = wc_get_order_item_meta($item_id,'installer_discount',true);

		$rule_name = '';
		if($item_discount_rule != '')
		{
			$rule_sql = "SELECT * FROM th_discount_rule where rule_id = $item_discount_rule";
			$rule_data = $wpdb->get_results($rule_sql);
			$rule_name = $rule_data[0]->name;
		}

		if($item_data['variation_id'] != '')
		{
			$product_id = $item_data['variation_id'];
		}else{
			$product_id = $item_data['product_id'];
		}

		if($product_id == $service_voucher_prd){


		$service_voucher = "SELECT *
							FROM th_cart_item_service_voucher
							WHERE order_id = '$order_id' and product_id = '$product_id'";


					
				$voucher = $wpdb->get_results($service_voucher);
				
				$voucher=$voucher[$i];
				
				// Get an instance of the WC_Order object
				$order = wc_get_order($order_id);
				$coupon_name=$order->get_used_coupons();
				$post_obj = get_page_by_title($coupon_name[0], OBJECT, 'shop_coupon');
				$coupon_id = $post_obj->ID;

				  $coupon = new WC_Coupon($coupon_name[0]);
				//die;
				$order_discount_total = $order_data['discount_total'];

				/*if(!empty($row))
				{*/
					/*foreach ($row as $key => $voucher)
					{*/

						$voucher_id = $voucher->service_voucher_id;
						$vehicle_id = $voucher->vehicle_id;
						$rate = $voucher->rate;
						$vehicle_name = $wpdb->get_var("SELECT vehicle_type FROM th_vehicle_type WHERE vehicle_id=' $vehicle_id' LIMIT 1");
						$installer_id = $voucher->installer_id;

						$installer_name = $wpdb->get_var("SELECT business_name FROM th_installer_data WHERE installer_data_id='$installer_id' LIMIT 1" );

						$voucher_sgst = wc_get_order_item_meta($item_id, $voucher_id.'_service_sgst', true);
						$voucher_cgst = wc_get_order_item_meta($item_id, $voucher_id.'_service_sgst', true);
						$voucher_taxable_value = wc_get_order_item_meta($item_id, $voucher_id.'_service_taxable', true);
						?>
						<tr>
							<td style="width: 40%;">
								<span class="item-name" style="height:45px;">
									<div>Service Voucher</div>
									<div>Vehicle Type: <b><?php echo $vehicle_name; ?></b></div>
								</span>

							
								<div><?php echo $installer_name; ?></div>
								<div><?php echo $voucher->voucher_name; ?></div>
							</td>
							<td style="width: 2%;">9954</td>
							<td style="width: 2%;"><?php echo $serviceGST; ?>%</td>

							<td class="qty"  style="text-align: center; width:5%;"><?php echo $voucher->qty; ?></td>

							<td class="price" style="text-align:center;"><?php  echo get_woocommerce_currency_symbol(). number_format((float)$voucher_taxable_value, 2, '.', ''); ?></td>

							<td style="width: 5%;"><?php if($discount == ''){ echo ''; } else{ echo get_woocommerce_currency_symbol(). number_format((float)$discount, 2, '.', ''); } ?></td>
						<td style="width: 20%;text-align:center;"><?php echo get_woocommerce_currency_symbol(). number_format((float)$voucher_taxable_value, 2, '.', ''); ?></td>	
					

						<tr>
						<td colspan="7" style="width: 20%;text-align:right;border: none;">
							<?php echo '<span class="item-name">SGST - '.$serviceGST_2.'% (OUTPUT)  '.get_woocommerce_currency_symbol(). number_format((float)$voucher_cgst, 2, '.', '').'</span>'; ?><br>
							<?php echo '<span class="item-name">CGST - '.$serviceGST_2.'% (OUTPUT)  '.get_woocommerce_currency_symbol(). number_format((float)$voucher_cgst, 2, '.', '').'</span>'; ?>
						</td>
						</tr>
						<?php if($vehicleInfo->model_name){?>
					<tr>
					<td colspan="7" style="border: none;">					
						<strong>Vehicle Detail:</strong> <?=$vehicleInfo->model_name;?>, <?=$vehicleInfo->car_number;?>
						<br> <strong>Odo Meter-</strong><?=$vehicleInfo->odo_meter;?> <br>
						 
						<?php 
						$serial_number = array();
						foreach($TyreDetails as $key=>$value){ 
							$serial_number[] = $value->serial_number; 
						
						}
						if($serial_number){
							$serial=implode(',',$serial_number);
							//echo substr_replace($serial ,"",-1);
							echo '<strong>Tyre Serial Number:</strong>' .$serial;	
						}
						
						$serial=implode(',',$serial_number);
						//echo substr_replace($serial ,"",-1);
						echo $serial;
						?>
					<?php
						if($guarantee_text != '')
						{
							
							//echo '<div class="guarantee-info">*'.$guarantee_text.'</div>';
						}
						?>
					</td>
					</tr>
					<?php }?>
						<?php
					//}


					if($order_discount_total>0){?>
						<tr>
							<td style="width: 40%;">
								<span class="item-name" style="height:45px;">
									<div>Coupon Code: <?=strtoupper($coupon_name[0]);?></div>
									<div style="font-size: 10px;"><?=$coupon->get_description($context);?></div>
								</span>


							</td>
							<td style="width: 2%;"></td>
							<td style="width: 2%;"></td>

							<td class="qty"  style="text-align: center; width:5%;"></td>

							<td class="price" style="text-align:center;"></td>

							<td style="width: 5%;"></td>

							<td style="width: 25%;text-align:center;">-<?php echo get_woocommerce_currency_symbol(). number_format((float)$order_discount_total, 2, '.', ''); ?>

							</td>
						</tr>
					<?php }
				//}
			$i++; 
			  }

			endforeach; endif;

			?>









</tbody>
<tfoot>
	<tr class="no-borders">

		<?php if ($this->get_shipping_notes()){ ?>
		<td colspan="5">
				<?php do_action( 'wpo_wcpdf_before_customer_notes', $this->type, $this->order ); ?>
					<h3><?php _e( 'Customer Notes', 'woocommerce-pdf-invoices-packing-slips' ); ?></h3>
					<p style="font-size:8px!important;"><?php $this->shipping_notes(); ?></p>

				<?php do_action( 'wpo_wcpdf_after_customer_notes', $this->type, $this->order ); ?>
		</td>
		<?php }else{?>
		<td></td>
		<td></td>
		<td></td>
		<td></td>
		<td></td>
		<?php }?>
		<td class="" colspan="2">
			<table class="totals">
				<tfoot>
					<?php
					/*echo '<pre>';
					print_r($this->get_woocommerce_totals());
					die;*/
					foreach( $this->get_woocommerce_totals() as $key => $total ) :
						if($key != 'shipping' && $key !='fee_6269'){
					 ?>
					<tr class="<?php echo $key; ?>">
						<td class="no-borders"></td>
						<th class="description"><?php  echo $total['label'];  ?></th>
						<td class="price"><span class="totals-price"><?php echo $total['value']; ?></span></td>
					</tr>
					<?php }
				endforeach; ?>
				</tfoot>
			</table>
		</td>
	</tr>
</tfoot>
</table>

<?php do_action( 'wpo_wcpdf_after_order_details', $this->type, $this->order ); ?>
<!-- <div class="second-footer">
<strong>Your Sincerely<br> Tyrehub.com</strong>
</div> -->
<?php if ( $this->get_footer() ): ?>
<div id="footer">
<ul class="terms-conditions">
	<li>* Guarantee and Warranty are subject to Tyre Company’s terms and condition.</li>
	<li>* Customers are responsible to claim their Tyre’s warranty directly with manufacturers.</li>
	<li>* If consumer is submitting claim for tyre(s) Guarantee or warranty, consumer must wait till Inspector come from the Tyre Company and inspect the tyre.</li>
	<li>That tyre replacement it's depend on Tyre Company.</li>
	<li>* It is customer's responsibility to call tyre company and register guarantee and warranty.<!-- , for more details visit:<a href="<?php //echo get_site_url().'/guarantee-warranty/'; ?>" style="color: #474494;"> <?php //echo get_site_url().'/guarantee-warranty/'; ?></a> --></li>
</ul>
<div class="border" style="width: 100%;"></div>
<div class="name" style="width: 49%; float: left;"><?php $this->footer(); ?></div>
<div class="shop-phone" style="width: 49%; float: right; text-align: right;"><p style="margin:0">www.Tyrehub.com</p><p style="margin:0"> <?php echo $this->extra_1();?></p></div>
</div><!-- #letter-footer --></p>
<?php endif; ?>
<?php do_action( 'wpo_wcpdf_after_document', $this->type, $this->order ); ?>
