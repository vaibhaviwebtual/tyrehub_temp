<?php if ( ! defined( 'ABSPATH' ) ) exit; // Exit if accessed directly ?>

<?php

	global $wpdb, $woocommerce;
	$items = $this->get_order_items(); 
	if( sizeof( $items ) > 0 ){
		$i=0;
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
			$service_voucher_prd = $wpdb->get_var("SELECT post_id FROM $wpdb->postmeta WHERE meta_key='_sku' AND meta_value='".$sku."'");

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
				    if($destination == '1')
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
				<?php
				include('invoice1_header.php');

			?>

					<tr>

						<td style="text-align: center; border-top: 0;">

								<img  width="150px;" style="margin-top: 20px;" src="<?php echo $barcode_img; ?>">
								<p style="font-size: 18px; margin-top: 10px;"><?php echo $barcode_text; ?></p>						
						</td>

						<td style="border-top: 0;">
						<?php
						echo '<h3>'.$variation_des.'('.$item['quantity'].' Tyre)</h3>';
					
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
							    		
							    		echo '<div>'.$service_name.' - '.$tyre_count.' Tyre</div>';
							    	}elseif($service_name == 'Pickup & Drop Off Service'){
							    		echo '<div>'.$service_name.' Car</div>';
							    		echo '<div style="width: 100%; float: left;">';
										echo '<strong>Pickup Address: </strong>'.$service->pikcup_address;
										echo '</div>';
							    	}
							    	else{
							    		
							    		echo '<div>'.$service_name.'-'.$tyre_count.' Car</div>';
							    		}									    	
								}
								?>									    
							</div>										
												
					</td>					
				</tr>
				</tbody>
		</table>
		<?php do_action( 'wpo_wcpdf_after_order_details', $this->type, $this->order ); ?>

			<?php if ( $this->get_footer() ): ?>
			<div id="footer">
				<ul class="terms-conditions">
					<li>*Tyrehub.com is not responsible if any damage occurs to customer’s vehicle during the fitting, alignment or balancing at the service partner. </li>
				</ul>
				<div class="border" style="width: 100%;"></div>
			 	<div class="name" style="width: 49%; float: left;">SUBJECT TO AHMEDABAD JURISDICTION<br>This is a Computer Generated Service Voucher</div>
				<div class="shop-phone" style="width: 49%; float: right; text-align: right;"><p style="margin:0">www.Tyrehub.com</p><p style="margin:0"> Toll free: 1-800-233-5551</p></div>
			</div><!-- #letter-footer -->
			<?php endif; ?>


	</div>
	<?php }	?>
					<?php }	}   	

		?>
	<?php }elseif ($product_id == $service_voucher_prd){
		$service_voucher = "SELECT * 
			                FROM th_cart_item_service_voucher
	                		WHERE order_id = '$order_id' and product_id = '$product_id'";
		$voucher = $wpdb->get_results($service_voucher);
		$voucher=$voucher[$i];

		    	?>
				<div class="single_record">
				<table class="head container">
					<tr>
						
						<td class="shop-info">
							<div class="shop-name"><h3><?php $this->shop_name(); ?></h3></div>
							<div class="shop-address">
								ATOZ TYRE HUB PVT. LTD.<br>
								3RD EYE RESIDENCY, MOTERA STADIUM ROAD, MOTERA, AHMEDABAD-380005<br>
								E-Mail: info@tyrehub.com<br>
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
				<?php
		    	include('invoice1_header.php');
		    	$voucher_id = $voucher->service_voucher_id;
				$vehicle_id = $voucher->vehicle_id;
				$rate = $voucher->rate;
				$vehicle_name = $wpdb->get_var( $wpdb->prepare( "SELECT vehicle_type FROM th_vehicle_type WHERE vehicle_id='%s' LIMIT 1" , $vehicle_id) );
				$installer_id = $voucher->installer_id;
					    	
				$installer_name = $wpdb->get_var( $wpdb->prepare( "SELECT business_name FROM th_installer_data WHERE installer_data_id='%s' LIMIT 1" , $installer_id) );
				$inst_add = $wpdb->get_var( $wpdb->prepare( "SELECT address FROM th_installer_data WHERE installer_data_id='%s' LIMIT 1" , $installer_id) );
				$inst_contact = $wpdb->get_var( $wpdb->prepare( "SELECT contact_no FROM th_installer_data WHERE installer_data_id='%s' LIMIT 1" , $installer_id) );

				$barcode_text = $voucher->barcode;
				$barcode_img = $voucher->barcode_img;
		    	?>
		    	<tr>
		    		<td style="text-align: center; border-top: 0;">
		    			
							<img  width="150px;" src="<?php echo $barcode_img; ?>">
							<div><?php echo $barcode_text; ?></div>
					</td>
		    		<td style="border-top: 0;">
		    			
						<h2>Service Details</h2>
						<span class="item-name" style="padding-bottom: 15px;">
		    				<div>Service Voucher</div>		    				
		    			</span>
						<?php 
						if($vehicle_id != 11){
							?>
							<div>Vehicle Type: <?php echo $vehicle_name; ?></div>
							<?php
						}
						?>
						<div><?php echo $voucher->voucher_name; ?></div>
						<div><?php echo '<strong>Qty: </strong>'.$voucher->qty; ?></div>

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

						
		    		</td>		    				    		
		    	</tr>
		    </tbody>
		</table>
		<?php do_action( 'wpo_wcpdf_after_order_details', $this->type, $this->order ); ?>

			<?php if ( $this->get_footer() ): ?>
			<div id="footer">
				<ul class="terms-conditions">
					<li>*Tyrehub.com is not responsible if any damage occurs to customer’s vehicle during the fitting, alignment or balancing at the service partner. </li>
				</ul>
				<div class="border" style="width: 100%;"></div>
			 	<div class="name" style="width: 49%; float: left;">SUBJECT TO AHMEDABAD JURISDICTION<br>This is a Computer Generated Service Voucher</div>
				<div class="shop-phone" style="width: 49%; float: right; text-align: right;"><p style="margin:0">www.Tyrehub.com</p><p style="margin:0"> Toll free: 1-800-233-5551</p></div>
			</div><!-- #letter-footer -->
			<?php endif; ?>


	</div>
		    	<?php

		 
		    	$i++;
	}

	?>

		
			

			</div>
			<?php
		}
		
		//include('invoice1_footer.php');
		}
	}
?>