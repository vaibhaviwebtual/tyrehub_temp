<?php


if(!empty($row))
{
	$installer_name = '';
    $selected_vehicle_id = '';
    $vehicle_name = '';
    foreach ($row as $key => $installer) 
    {
    	$destination = $installer->destination;
    	
    	$installer_table_id = $installer->cart_item_installer_id;
    	$installer_id = $installer->installer_id;
    	$vehicle_id = $installer->vehicle_id;
    	$installer_name = $wpdb->get_var( $wpdb->prepare( "SELECT business_name FROM th_installer_data WHERE installer_data_id='%s' LIMIT 1" , $installer_id) );

    	

    	$vehicle_name = $wpdb->get_var( $wpdb->prepare( "SELECT vehicle_type FROM th_vehicle_type WHERE vehicle_id='%s' LIMIT 1" , $vehicle_id) );
    	$selected_vehicle_id = $installer->vehicle_id;
    }
	
     $SQL="SELECT * FROM th_installer_addi_service ids 
		     LEFT JOIN th_service_data as sd ON sd.service_data_id=ids.service_data_id 
		     WHERE sd.status=1 and ids.installer_id='".$installer_id."'";
    	$addi_service = $wpdb->get_results($SQL);
	  		
?>
	<tr class="service-row product-name">
		<td class="product-thumbnail">
		<?php				
		    if($destination == '0')
		    {
		?>
				<img width="300" height="300" src="<?php echo bloginfo('template_directory').'/images/icons8-home-100.png'; ?>" class="attachment-woocommerce_thumbnail size-woocommerce_thumbnail wp-post-image" alt=""/>
		<?php    		
		    }
		    else{
		?>
				<img width="300" height="300" src="<?php echo bloginfo('template_directory').'/images/icons8-car-service-filled-100.png'; ?>" class="attachment-woocommerce_thumbnail size-woocommerce_thumbnail wp-post-image" alt=""/>
	<?php 	} 
	?>
		</td>
		<td colspan="2">
		<?php										
		    if($destination == '0')
		    {
		    		echo 'Free Home Delivery';
		    }
		    else
		    {
		?>
				<div class="installer-name"><?php echo '<b>'.$installer_name.'</b>'; ?></div>
			<?php 
			    if($vehicle_name !=''){
			?>
			    	<div class="vehicle-typre"><b>Vehicle Type : </b><?php echo $vehicle_name; ?></div>
		<?php 	}
			?>
				<div class="product-service-list abc">
			    <?php

			    $services = "SELECT * 
                    	FROM th_cart_item_services
                    	WHERE cart_item_key = '$cart_item_key' and session_id = '$session_id' and order_id = ''";
                $row = $wpdb->get_results($services);

                $service_name = '';
                $service_list = [];
                $amount = '';
                $total_amout = 0;
                $service_data_id_arr=array();
                foreach ($row as $key => $service) 
			    {
			    	$tyre_count = $service->tyre;
			    	$service_data_id_arr[] = $service->service_data_id;
			    	$service_name = $service->service_name;
			    	$rate = $service->rate;
			    	
			    	$service_list[$service_name] = $tyre_count;

			    	if($service_name == 'Tyre Fitment')
			    	{
			    		$amount = $tyre_count * $rate;
			    		if($rate == 0){
			    			echo '<div>'.$service_name.' - free </div>';
			    		}
			    		else{
			    			echo '<div>'.$service_name.' '.get_woocommerce_currency_symbol().$rate.' x '.$tyre_count.' - '.get_woocommerce_currency_symbol().$amount.'</div>';
			    		}
			    		
			    	}
			    	else{
			    		$amount = $tyre_count * $rate;
			    		//echo '<div>'.$service_name.' - '.get_woocommerce_currency_symbol().$rate.'</div>';

			    	}
			    	
			    	$total_amout = $total_amout + $amount;
			    }
			    ?>			    
			</div>
			<div class="tyre-name"><strong>For Tyre: </strong>
			    	<?php 
			    	 $_product->get_name();
			    	$cart_item['variation_id'];
			    	if($cart_item['variation_id'])
			    	{

			    		$product_variation = new WC_Product_Variation( $cart_item['variation_id'] );
						echo ' '.$variation_des = $product_variation->get_description();
						$pa_vehicletype=$product_variation->get_attributes();
						
			    	} 
			    	?></div>
			
			
			<!-- <span class="selection-btn change-services <?php echo $current_prd; ?>-change-service" data-toggle="modal" data-target="#<?php echo $current_prd; ?>_service_modal">Change Service</span> -->

		<?php 
				//include('cart-item-service-modal.php');  
			}
		?>
		</td>

		
		<td class="total">

			<?php 
				if($destination != '0')
			    {
			    	echo get_woocommerce_currency_symbol().number_format($total_amout,2,'.','');
			    }
			    if($destination == '0')
			    {
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
				    	$home_delivery_charge =0;
			    	echo get_woocommerce_currency_symbol().number_format($home_delivery_charge,2,'.','');
			    }
			?>
				
			</td>
		
		<td class="custom-remove">
			<a href="<?php echo get_site_url();?>/cart" class="delete remove-service" aria-label="Remove this item" data-cart-item-installer-id="<?php echo $installer_table_id; ?>" data-cart_key="<?php echo $cart_item_key;?>" data-session_id="<?php echo $session_id; ?>">Change</a>
		</td>
	</tr>
	
	<?php if($pa_vehicletype['pa_vehicle-type']!='two-wheeler'){?>
			<tr class="service-list-added">
				<td>&nbsp;</td>
				<td colspan="2" class="title-sec">Car Wash (₹350)</td>	
				<td class="price-sec">₹180</td>
				<td class="remove-service">
					<button class="btn btn-invert"><span>Remove</span></button>
				</td>
			</tr>
	<?php }?>
<?php
	} 
?>
