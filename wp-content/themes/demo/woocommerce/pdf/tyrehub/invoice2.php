<?php if ( ! defined( 'ABSPATH' ) ) exit; // Exit if accessed directly ?>

<?php

	global $wpdb, $woocommerce;
	$items = $this->get_order_items(); 
	$order_ids = $_GET['order_ids'];
	$order_ids = explode('x', $order_ids);
	$total_order = count($order_ids);
	foreach ($order_ids as $key => $value) {
		
		if($value == $order_id){
			echo  '<p style="text-align:center;">Page '.++$key.' of '.$total_order.'</p>';
		}
	}
	if( sizeof( $items ) > 0 ){
		foreach( $items as $item_id => $item )
		{
			$item_data = $item;
			do_action( 'wpo_wcpdf_before_document', $this->type, $this->order );
			if($item_data['variation_id'] != '')
			{
				$product_id = $item_data['variation_id'];
			}
			else{
				$product_id = $item_data['product_id'];
			}
			$installer = "SELECT * 
			                FROM th_cart_item_installer
			                WHERE order_id = '$order_id' and product_id = '$product_id'";
			$installer_row = $wpdb->get_results($installer);

			$service_voucher = "SELECT * 
				                FROM th_cart_item_service_voucher
				                WHERE order_id = '$order_id' and product_id = '$product_id'";
	    	$service_voucher_row = $wpdb->get_results($service_voucher);

			if(!empty($installer_row) || !empty($service_voucher_row))
		    {

		    

			
			?>
			<div class="single_record">
			<table class="head container">
				<tr>
					
					<td class="shop-info">
						<div class="shop-name"><h3><?php $this->shop_name(); ?></h3></div>
						<div class="shop-address">
							ATOZ TYRE HUB PVT. LTD.<br>
							3RD EYE RESIDENCY, MOTERA STADIUM ROAD, MOTERA, AHMEDABAD-380005<br>
							E-Mail: sales@tyrehub.com<br>
							Toll Free: 1-800-233-5551
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
			</table>
			<h1 class="document-type-label">Service voucher</h1>
			<?php do_action( 'wpo_wcpdf_after_document_label', $this->type, $this->order ); ?>
			<?php
				$user = $order->get_user();

				$user_idd = $user->ID;
				$user_role = $user->roles[0];
				global $wpdb;
				?>
				<table class="order-data-addresses">
				<tr>
					<td class="address billing-address" style="width: 50%;">
						<p style="font-size: 15px"> Tyrehub Promotion </p>	
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
						<!-- <h3><?php _e( 'Billing Address:', 'woocommerce-pdf-invoices-packing-slips' ); ?></h3> -->
						<?php do_action( 'wpo_wcpdf_before_billing_address', $this->type, $this->order ); ?>
						<?php //$this->billing_address(); ?>
						<?php do_action( 'wpo_wcpdf_after_billing_address', $this->type, $this->order ); ?>
						<?php //if ( isset($this->settings['display_email']) ) { ?>
						<div class="billing-email">
							<?php 
								$order = wc_get_order( $order_id );
								$order_data = $order->get_data();
								$order_billing_email = $order_data['billing']['email'];
								
								if($order_billing_email != 'sales@tyrehub.com' && $order_billing_email != 'admin@tyrehub.com')
								{						
									//$this->billing_email();
								}
							?>				
						</div>
						<?php// } ?>
						<?php //if ( isset($this->settings['display_phone']) ) { ?>
						<!-- <div class="billing-phone"><div> Mobile number : <?php echo  $this->billing_phone(); ?> </div></div> -->
						<?php //} ?>
						<div class="billing-phone" style="width: 100%;"> 
							<?php  
								
								if($cmp_name != '')
								{
									echo '<div> Company Name : '.$cmp_name.'</div>';
								}  
								if($cmp_add != '')
								{
									echo '<div> Company Address : '.$cmp_add.'</div>';
								}												
							?>
						</div>
					</td>
					<?php  ?>
					<td class="order-data">
						<table>
							<?php do_action( 'wpo_wcpdf_before_order_data', $this->type, $this->order ); ?>
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
							<tr class="order-number">
								<th><?php _e( 'Order Number:', 'woocommerce-pdf-invoices-packing-slips' ); ?></th>
								<td><?php $this->order_number(); ?></td>
							</tr>
							<tr class="order-date">
								<th><?php _e( 'Order Date:', 'woocommerce-pdf-invoices-packing-slips' ); ?></th>
								<td><?php $this->order_date(); ?></td>
							</tr>
							<?php do_action( 'wpo_wcpdf_after_order_data', $this->type, $this->order ); ?>
						</table>			
					</td>
				</tr>
			</table>
			<?php do_action( 'wpo_wcpdf_before_order_details', $this->type, $this->order ); ?>
			<table class="order-details" style="font-size:12px; border: 1px dashed #ccc;">
				<tbody>
		<?php  
			
			$tyre_price = number_format((float)$taxable_value, 2, '.', '');
			$order_price_per_unit = $tyre_price / $item['quantity'] ; 

			if($item_data['variation_id'] != '')
			{
				$product_id = $item_data['variation_id'];
			}
			else{
				$product_id = $item_data['product_id'];
			}

			$sku = 'service_voucher';
			$service_voucher_prd = $wpdb->get_var( $wpdb->prepare( "SELECT post_id FROM $wpdb->postmeta WHERE meta_key='_sku' AND meta_value='%s' LIMIT 1", $sku ) );

			if($product_id != $service_voucher_prd)
			{ 

				$product_variation = wc_get_product( $product_id );
				$variation_des = $product_variation->get_description();
				$variation_price = $product_variation->get_price();

				$parent_id = wp_get_post_parent_id($product_id);
				$guarantee_text = get_post_meta($parent_id, '_guarantee_cart', true );

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
				    	$inst_add = $wpdb->get_var( $wpdb->prepare( "SELECT address FROM th_installer_data WHERE installer_data_id='%s' LIMIT 1" , $installer_id) );
				    	$inst_contact = $wpdb->get_var( $wpdb->prepare( "SELECT contact_no FROM th_installer_data WHERE installer_data_id='%s' LIMIT 1" , $installer_id) );
				    	$vehicle_name = $wpdb->get_var( $wpdb->prepare( "SELECT vehicle_type FROM th_vehicle_type WHERE vehicle_id='%s' LIMIT 1" , $vehicle_id) );
				    	$selected_vehicle_id = $installer->vehicle_id;
				    	$barcode_text = $installer->barcode;
				    	$barcode_img = $installer->barcode_img;
			?>

					<tr>

						<td style="text-align: center; border-top: 0;">

								<img  width="150px;" style="margin-top: 20px;" src="<?php echo $barcode_img; ?>">
								<p style="font-size: 18px; margin-top: 10px;"><?php echo $barcode_text; ?></p>						
						</td>

						<td style="border-top: 0;">
						<?php
						echo '<h3>'.$variation_des.'('.$item['quantity'].' Tyre)</h3>';
					if($destination == '1')
					{
					?>
						<div class="installer-name" style="padding-top: 15px;padding-bottom: 15px;">
							<h2>Service Partner</h2>
							<?php echo '<b>'.$installer_name.'</b>'; ?>
							<div class="address"><strong>Address:</strong>
								<?php echo $inst_add; ?>
							</div>
							<div class="phone-no"><strong>Mobile No:</strong>
								<?php echo $inst_contact; ?>
							</div>
						</div>
						<h2>Service Details</h2>
					<?php 
					   	if($vehicle_name !='')
					   	{
					?>
							<div class="vehicle-typre">Vehicle Type : <?php echo $vehicle_name; ?></div>
			<?php 		}
						?>
							<div class="product-service-list">
								
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
							    	
							    	
							    	$service_list[$service_name] = $tyre_count;

							    	if($service_name == 'Tyre Fitment'){
							    		
							    		echo '<div>'.$service_name.' - '.$tyre_count.' tyre</div>';
							    	}
							    	else{
							    		
							    		echo '<div>'.$service_name.'-'.$tyre_count.' Car</div>';
							    		}									    	
								}
								?>									    
							</div>										
							<?php }	?>					
					</td>					
				</tr>
					<?php }	}   	

		?>
	<?php }
	
	elseif ($product_id == $service_voucher_prd)
	{
		$service_voucher = "SELECT * 
			                FROM th_cart_item_service_voucher
	                		WHERE order_id = '$order_id' and product_id = '$product_id'";
		$row = $wpdb->get_results($service_voucher);

		if(!empty($row))
	    {
		    foreach ($row as $key => $voucher) 
		    {
		    	$voucher_id = $voucher->service_voucher_id;
				$vehicle_id = $voucher->vehicle_id;
				$rate = $voucher->rate;
				$vehicle_name = $wpdb->get_var( $wpdb->prepare( "SELECT vehicle_type FROM th_vehicle_type WHERE vehicle_id='%s' LIMIT 1" , $vehicle_id) );
				$installer_id = $voucher->installer_id;
					    	
				$installer_name = $wpdb->get_var( $wpdb->prepare( "SELECT business_name FROM th_installer_data WHERE installer_data_id='%s' LIMIT 1" , $installer_id) );
				$barcode_text = $voucher->barcode;
				$barcode_img = $voucher->barcode_img;
		    	?>
		    	<tr>
		    		<td style="text-align: center; border-top: 0;">
							<img  width="150px;" src="<?php echo $barcode_img; ?>">
							<div><?php echo $barcode_text; ?></div>
					</td>
		    		<td style="border-top: 0;">
		    			<span class="item-name" style="padding-bottom: 15px;">
		    				<div>Service Voucher</div>		    				
		    			</span>
						<h2>Service Details</h2>
						<?php 
						if($vehicle_id != 11){
							?>
							<div>Vehicle Type: <?php echo $vehicle_name; ?></div>
							<?php
						}
						?>
						
						<div><?php echo $installer_name; ?></div>
						<div><?php echo $voucher->voucher_name; ?></div>
						<div><?php echo '<strong>Qty: </strong>'.$voucher->qty; ?></div>
		    		</td>		    				    		
		    	</tr>
		    	<?php
		 	}
		}	
	}
	?>
	</tbody>
</table>

			<?php do_action( 'wpo_wcpdf_after_order_details', $this->type, $this->order ); ?>
		
			<?php if ( $this->get_footer() ): ?>
			<div id="footer">

			 	<div class="name" style="width: 49%; float: left;">SUBJECT TO AHMEDABAD JURISDICTION<br>This is a Computer Generated Service Voucher</div>
				<div class="shop-phone" style="width: 49%; float: right; text-align: right;"><p style="margin:0">www.Tyrehub.com</p><p style="margin:0"> Toll free: 1-800-233-5551</p></div>
			</div><!-- #letter-footer -->
			<?php endif; ?>
			<?php //do_action( 'wpo_wcpdf_after_document', $this->type, $this->order ); ?>

			</div>
			<?php
		}
		}
	}
?>