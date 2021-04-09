<div class="modal fade cart-item-service-model" id="<?php echo $current_prd; ?>_service_modal" role="dialog" style="pointer-events: none;">
  	<div class="modal-dialog">		    
    	<!-- Modal content-->
    	<div class="modal-content"  style="pointer-events: auto;">
    		 <div class="modal-header">
        		<!-- <button type="button" class="close" data-dismiss="modal">&times;</button> -->        
      		</div>
    		<div class="modal-body">
      		<span class="cart_item_id" hidden=""><?php echo $cart_item_key; ?></span>
      		<span class="product-id" hidden=""><?php echo $current_prd; ?></span>
      		<span class="prd_attr_vehicle" hidden="">
				<?php 
				$prd_attr_vehicle = '';
				if($current_prd != '')
				{
		    		$product_variation = new WC_Product_Variation( $current_prd );
		    		$variation_data = $product_variation->get_data();
		    		 
		    		if($variation_data['attributes']['pa_vehicle-type'] != 'car-tyre'){
		    			echo $prd_attr_vehicle = $variation_data['attributes']['pa_vehicle-type'];
		    		}
		    		
				} ?>
				
			</span>
			<?php 
				global $woocommerce , $wpdb; 

	  			// cart item array
	  			$cart_item_total = WC()->cart->get_cart_contents_count(); 
	  			$cart_item_arr = [];
				foreach(WC()->cart->get_cart() as $cart_item )
				{
					$cart_item_arr[] = $cart_item['product_id'];
				}
				// If Select product and installer both
				
			?>

<!-------------- select vehicle type Screen ---------------------->
				<div id="tab4-form" method="post" class="select-car-type screen">
					<div class="product-info"><span class="id"></span></div>     
					<div>Select Car Type</div> 
					    <div class="data-header inputGroup">             
					       	<label><span class="sname">Car Type</span></label>
					    </div>     
						<span class="selected-vehicle" hidden=""><?php echo $selected_vehicle_id; ?></span>
					    <?php			    	
					    	   						
							//echo $selected_vehicle_id;
					    	if($prd_attr_vehicle != ''){
								$row = $wpdb->get_results("SELECT * FROM th_vehicle_type WHERE wheel_type = '1'");
							}
							else{
								$row = $wpdb->get_results("SELECT * FROM th_vehicle_type WHERE wheel_type = '2'");
							}

							
							foreach ($row as $data) {
								
						?>      
		                <div class="inputGroup vehicle_type">
		                	
		                    <input id="<?php echo $current_prd.'vehicle_'.$data->vehicle_type ?>" name="<?php echo $current_prd.'_vehicle_type' ?>" type="radio" value="<?php echo $data->vehicle_id ?>" <?php if($selected_vehicle_id == $data->vehicle_id){echo 'checked';}?>/>
		                    <label for="<?php echo $current_prd.'vehicle_'.$data->vehicle_type ?>">
		                    	<?php echo $data->vehicle_type ?>
		                    		<?php 
		                              if($data->vehicle_type == 'Hatchback'){?>
		                                 <img class="car-img" src="<?php echo bloginfo('template_directory');?>/images/hatchback-car-img.png" >
		                              <?php }
		                              elseif($data->vehicle_type == 'Sedan'){?>
		                                 <img class="car-img" src="<?php echo bloginfo('template_directory');?>/images/sedan-car-img.png">
		                              <?php } 
		                              elseif($data->vehicle_type == 'Suv'){?>
		                                 <img class="car-img" src="<?php echo bloginfo('template_directory');?>/images/suv-car-img.png" >
		                              <?php }
		                              elseif($data->vehicle_type == 'Premium Car'){?>
		                                 <img class="" src="<?php echo bloginfo('template_directory');?>/images/audi-logo.png" style="width: 50px;">
		                                 <img class="" src="<?php echo bloginfo('template_directory');?>/images/mercedes-benz-logo.png" style="width: 50px;">
		                                 <img class="" style="width: 50px;" src="<?php echo bloginfo('template_directory');?>/images/bmw-logo.png" >
		                                
		                              <?php } 
		                              elseif($data->vehicle_type == 'Bike'){?>
		                                 <img class="car-img" src="<?php echo bloginfo('template_directory');?>/images/bike.png" >
		                              <?php }
		                              elseif($data->vehicle_type == 'Activa/Scooter'){?>
		                                 <img class="car-img" src="<?php echo bloginfo('template_directory');?>/images/activa.png" >
		                              <?php }
		                              elseif($data->vehicle_type == 'Autorickshaw'){?>
		                                 <img class="car-img" src="<?php echo bloginfo('template_directory');?>/images/auto-rickshaw.png" >
		                              <?php }
		                              elseif($data->vehicle_type == 'Premium Bike'){?>
		                                 <img class="" style="width: 50px;" src="<?php echo bloginfo('template_directory');?>/images/bmw-logo.png" >
		                                 <img class="" style="width: 50px;" src="<?php echo bloginfo('template_directory');?>/images/royal-enfield.png" >
		                              <?php } 

		                              ?>
		                    	</label>
		                </div>                          
                    	<?php } //foreach ?> 
		                <div class="modal-footer">
							<div class="right">
							</div>
							<div class="left" style="width: 100%;">
							    <div class="next-to-service-voucher btn btn-invert" disabled="">
							      <span>Next</span>
							   	</div>
							</div>
						</div>
				</div>

 <!----------------- vehicle Screen ------------------------>


<!-------------------------- Service Voucher Screen ---------------------------->
		<div class="select-service-voucher screen" style="display: none;">
		  	<h3>Select Service Voucher</h3>
		  	<div class="select-tyre-for-service">
		  	<?php						
				$cart_item_total = $cart_item['quantity'];				
			?>					  	
		  	</div>
		  	<div class="data-header inputGroup">                     
		    	<label>
		      		<span class="sname">Service Name</span>
		      		<span class="rate">Price</span>                    
		    	</label>
		  	</div>
		  
	  		<div class="data-body">
	  		<?php
	  			$row = $wpdb->get_results("SELECT * FROM th_service_data where service_data_id != 4");
	  			// $row = $wpdb->get_results('SELECT * from th_service_data_price as a join th_service_data as b on a.service_data_id = b.service_data_id');
				foreach ($row as $data)
				{
					$service_name = $data->service_name;
					if(array_key_exists($service_name, $service_list))
					{
						if($service_name != "Wheel alignment"){

							$selected_tyre = $service_list[$service_name];
						}
					}					
	  		?>
	  		<div class="service_list">
		        <div class="inputGroup service_type">
		            <input id="<?php echo $current_prd.'_'.$data->service_data_id ?>" name="servie_type" type="checkbox" value="1" class="service_name" <?php if(array_key_exists($data->service_name, $service_list)){echo 'checked';}?>>

		            <label for="<?php echo $current_prd.'_'.$data->service_data_id ?>">
		                <?php echo '<span class="sname">'.$data->service_name.'</span>' ?>
		                <?php                           
		                    $row = $wpdb->get_results("SELECT * FROM th_service_data_price where vehicle_id = $selected_vehicle_id and service_data_id = $data->service_data_id");
		                    foreach ($row as $data)
		                    {
		                        echo '<input class="service_rate" value="'.$data->rate.'" hidden>';
		                       // echo '<span class="rate">'.get_woocommerce_currency_symbol().$data->rate.' x </span>';
		                        if($service_name == 'Wheel alignment')
		                        {
		                            echo '<span class="rate">'.get_woocommerce_currency_symbol().$data->rate.'</span>';
		                        }
		                        else{
		                            echo '<span class="rate">'.get_woocommerce_currency_symbol().$data->rate.' x </span>';
		                        }
		                       // echo '<span class="select_tyre"></span>';
		                     //   echo '<span class="service-amount" data-amount=""></span>';

		                        if($service_name != "Wheel alignment")
		                        {		                        					                       
		                    ?>
		                    <select class="cart_tyre">
						  		<?php
						  			for($i=1; $i<=$cart_item_qty; $i++)
						  			{

						  				if($selected_tyre != '')
						  				{
						  		?>
						  					<option <?php if($i == $selected_tyre){echo 'selected';} ?>><?php echo $i; ?></option>
						  		<?php
						  				}
						  				else{					  		
						  		?>
						  					<option <?php if($i == $cart_item_total){echo 'selected';} ?>><?php echo $i; ?></option>
						  		<?php		
						  				}
						  			}
						  		?>
						  	</select>
		                    <?php
		                		} //if($service_name != "Wheel alignment")
		                    }
		                    ?>
		            </label>
		        </div>                        
		    </div> 
		<?php 	} ?>
	  	</div>

        <div class="service_voucher_total">
        	<b>Total Price</b><?php echo ' '.get_woocommerce_currency_symbol(); ?>
        	<span class="amount"></span>
        </div>

        <div class="modal-footer">
        	<div class="right">
        		<button class="prev-to-car-type btn btn-invert modal-btn"><span>Prev</span></button>
        	</div>
        	<div class="left">
        		<!-- <button class="next-to-review btn btn-invert use_services modal-btn"><span>Next</span></button>
        		 -->
        		 <div class="btn btn-invert confirm-services modal-btn">
        			<span>Confirm Service</span>
        		</div>
        	</div>
		</div>

    </div> 

 <!----------------- Service Screen ------------------------>



 <!----------------- review Screen ------------------------>
    <div class="review-installer screen" style="display: none;">
    	<h3>Review Selected Details</h3>
    	<div class="selected_installer_information block">
			<div class="title">
				<!-- <h4>Installer Information</h4> -->
			</div>
			<div class="content">
				<?php 
					//$selected_installer = $current_user.'_selected_installer'; 
				//	$installer_data_id = get_option($selected_installer); 
    
                $sql = "SELECT * 
                    	FROM th_installer_data
                    	WHERE installer_data_id = $installer_id";
                $row = $wpdb->get_results($sql);                    
                    
                               
               
				?>
			</div>
		</div>

		<div class="selected-service block">
			<div class="title">
				<h4>Service Information</h4>
			</div>
			<div class="content">
				<div class="service-name-list">
					<?php

						$services = "SELECT * 
                    	FROM th_cart_item_services
                    	WHERE cart_item_key = '$cart_item_key' and session_id = '$session_id'";
                    $row = $wpdb->get_results($services);

                    $service_name = '';
                    $amount = '';
                    foreach ($row as $key => $service) 
				    {
				    	$tyre_count = $service->tyre;
				    	$service_name = $service->service_name;
				    	$rate = $service->rate;
				    	$amount = $tyre_count * $rate;

				    	if($service_name == 'Wheel alignment'){
				    		echo '<div>'.$service_name.' - '.get_woocommerce_currency_symbol().$rate.'</div>';
				    	}
				    	else{
				    		echo '<div>'.$service_name.' x '.$tyre_count.' - '.get_woocommerce_currency_symbol().$amount.'</div>';
				    	}
				    } 
					?>
				</div>
				
				<div class="total-pay" style="text-align: right;"><b>Pay for Services </b><?php echo get_woocommerce_currency_symbol();?><span class="amount"><?php //echo get_option($service_voucher); ?></span>
			</div>
			</div>

		</div>
		<div class="modal-footer">
			<div class="right">
        		<button class="prev-to-service-voucher btn btn-invert modal-btn"><span>Prev</span></button>
        	</div>
			<div class="left">	                    		
                   		<div class="btn btn-invert confirm-services modal-btn">
        			<span>Confirm Service</span>
        		</div>
        	</div>	                    	
		</div>
    	
    </div>
             
<!---------------- Review Screen --------------------------------->
		        </div>
		        
		      </div>
		      
		    </div>
		  </div>