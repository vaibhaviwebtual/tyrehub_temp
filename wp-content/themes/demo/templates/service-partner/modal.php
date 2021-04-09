<div class="modal fade" id="service_modal" role="dialog">
  	<div class="modal-dialog">		    
    	<!-- Modal content-->
    	<div class="modal-content">
    		<div class="modal-header">
        		<button type="button" class="close" data-dismiss="modal">&times;</button>        
      		</div>
      		<div class="modal-body">
      			
			<?php 
				global $woocommerce; 

				$current_user = get_current_user_id();
				$service_voucher = $current_user.'_service_voucher';

				// service voucher product id
		    	$sku = 'service_voucher';
		    	
	  			$service_prd_id = $wpdb->get_var( $wpdb->prepare( "SELECT post_id FROM $wpdb->postmeta WHERE meta_key='_sku' AND meta_value='%s' LIMIT 1", $sku ) );

	  			// cart item array
	  			$cart_item_total = WC()->cart->get_cart_contents_count(); 
	  			$cart_item_arr = [];
				foreach(WC()->cart->get_cart() as $cart_item ){
								 $cart_item_arr[] = $cart_item['product_id'];
				}

				// If Select product and installer both
				if(!isset($_POST['vehicle_type'])){
			?>
				<!-- select Car Tyre Screen -->
				<div id="tab4-form" method="post" class="select-car-type screen">
					<div class="product-info"><span class="id"></span></div>     
					
					    <div class="data-header inputGroup">   
					    <?php
					    	if($prd_attr_vehicle == 'car-tyre'){

					    ?>          
					       	<label><span class="sname">Car Type</span></label>
					       <?php } 
					       else{
					       		?>          
					       		<label><span class="sname">Select Vehicle Type</span></label>
					       <?php
					       }?>
					    </div>               
					    <?php			    	
					    	$selected_vehicle = '';
					    	if(in_array($service_prd_id, $cart_item_arr))
					    	{
					    		$vehicle_type = $current_user.'_vehicle_type';
					    		$selected_vehicle = get_option($vehicle_type);
					    	}
					    	    						
							global $wpdb;							
							
							if($prd_attr_vehicle == 'car-tyre'){
								$row = $wpdb->get_results("SELECT * FROM th_vehicle_type WHERE wheel_type = '2'");
							}
							elseif($prd_attr_vehicle == 'two-wheeler' || $prd_attr_vehicle == 'three-wheeler'){
								$row = $wpdb->get_results("SELECT * FROM th_vehicle_type WHERE wheel_type = '1'");
							}						
							else{
								$row = $wpdb->get_results("SELECT * FROM th_vehicle_type WHERE wheel_type = '2'");
							}
							
							foreach ($row as $data) {
						?>      
		                <div class="inputGroup vehicle_type">
		                    <input id="<?php echo 'vehicle'.$data->vehicle_type ?>" name="vehicle_type" type="radio" value="<?php echo $data->vehicle_id ?>" />
		                    <label for="<?php echo 'vehicle'.$data->vehicle_type ?>">
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
		                                 <img class="" style="width: 50px;" src="<?php echo bloginfo('template_directory');?>/images/audi-logo.png" >
		                                 <img class="" style="width: 30px;" src="<?php echo bloginfo('template_directory');?>/images/mercedes-benz-logo.png" >
		                                 <img class="" style="width: 30px;" src="<?php echo bloginfo('template_directory');?>/images/bmw-logo.png" >
		                                
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
							    <button class="next-to-service-voucher btn btn-invert button modal-btn" disabled="">
							      <span>Next</span>
							   	</button>
							</div>
						</div>
				</div>

				<!-- Service Voucher Screen -->
					<div class="select-service-voucher screen" style="display: none;">
					  <h3>Select Service Voucher
					  </h3>
					  
					  <div class="data-header inputGroup">                     
					    <label>
					      <span class="sname">Service Name
					      </span>
					      <span class="rate">Price
					      </span>                    
					    </label>
					  </div>
					  
					  <div class="data-body">

					  </div>

	                    <div class="service_voucher_total" style="display: none;">
	                    	<b>Total Price</b><?php echo ' '.get_woocommerce_currency_symbol(); ?>
	                    	<span class="amount"></span>
	                    </div>
	                    <div class="barcode-image" style="display: none;"></div>
	                    <div class="modal-footer">
	                    	<div class="right">
	                    		<button class="prev-to-car-type btn btn-invert button modal-btn"><span>Prev</span></button>
	                    	</div>
	                    	<div class="left">
				        		<button class="btn btn-invert button confirm_installer modal-btn" >
				        		<span>Confirm Installer</span>
				        		</button>
				        	</div>
		        		</div>

                    </div> 

    	<!-- review Screen -->
	       <!--  <div class="review-installer screen" style="display: none;">
	        	<h3>Review Selected Details</h3>
	        	<div class="selected_installer_information block">
					<div class="title">
						
					</div>
					<div class="content">
						<?php 
							$selected_installer = $current_user.'_selected_installer'; 
							$installer_data_id = get_option($selected_installer); 
	                        global $wpdb;
	                        if(isset($installer_data_id))
	                        {
	                        	$sql = "SELECT * 
	                        			FROM th_installer_data
	                        			WHERE installer_data_id = $installer_data_id";
	                        	$row = $wpdb->get_results($sql);
	                        }			                        
	                       		                   
						?>
					</div>
				</div>

						<div class="selected-service block">
							<div class="title">
								<h4>Service Information</h4>
							</div>
							<div class="content">
								<div class="service-name-list">
									
								</div>
								
								<div class="total-pay" style="text-align: right;"><b>Pay for Services </b><?php echo get_woocommerce_currency_symbol();?><span class="amount"><?php echo get_option($service_voucher); ?></span>
							</div>
							</div>

						</div>
						
						<div class="modal-footer">
							<div class="right">
				        		<button class="prev-to-service-voucher btn btn-invert button modal-btn"><span>Prev</span></button>
				        	</div>
							<div class="left">	                    		
	                    		<button class="btn btn-invert button confirm_installer modal-btn" >
				        		<span>Confirm Installer</span>
				        		</a>
	                    	</div>	                    	
		        		</div>
                    	
                    </div> -->
                    <!-- review Screen End -->
		        <?php
		          	}
		          	elseif(!isset($_GET['product_id']))
		          	{
		        ?>
		          		<h3>Select Service Voucher</h3>
						<div class="data-header inputGroup">                     
		                    <label>
		                        <span class="sname">Service Name</span>
		                        <span class="rate">Price</span>
		                                            
		                    </label>
		                </div>
		          		<?php
		          		$vehicle_id = '';
		          		if(isset($_POST['vehicle_type'])){
		          			$vehicle_id = $_POST['vehicle_type'];
		          		}		          		
			          		

						    global $wpdb;
						    $row = $wpdb->get_results("SELECT * FROM th_service_data where service_data_id =4");
						    foreach ($row as $data){                         
					    ?> 
					
    					<div class="service_list">
    						
	                        <div class="inputGroup service_type">
	                            <input id="service_<?php echo $data->service_data_id ?>" name="servie_type" type="checkbox" value="1" class="service_name">
	                            <label for="service_<?php echo $data->service_data_id ?>">
	                                <?php echo '<span class="sname">'.$data->service_name.'</span>' ?>
	                                <?php                           
	                                    $row = $wpdb->get_results("SELECT * FROM th_service_data_price where vehicle_id = $vehicle_id and service_data_id = $data->service_data_id");
	                                    foreach ($row as $data)
	                                    {
	                                        echo '<input class="service_rate" value="'.$data->rate.'" hidden>';
	                                        echo '<span class="rate">'.get_woocommerce_currency_symbol().$data->rate.' x </span>';
	                                    }
	                                ?>
	                                <select class="cart_tyre">
										<?php for ($i=1; $i <= 10 ; $i++) { ?>
										<option <?php //if($cart_item_qty == $i){echo 'selected';}?>><?php echo $i; ?></option>
									<?php } ?>
								</select>
	                            </label>
	                        </div>                        
                    </div> 

                    <?php } ?>

   					<div class="service_voucher_total" style="display: none;">

	                    <b>Total Price</b><?php echo ' '.get_woocommerce_currency_symbol(); ?>
	                    	<span class="amount"></span>
	                </div>
	                <div class="barcode-image" style="display: none;"></div>
				 	<div class="modal-footer" style="text-align: right;">
						        	
						<a href="<?php echo get_site_url().'/shop?add-to-cart='.$service_prd_id; ?>" data-quantity="1" class="btn btn-invert button product_type_simple add_to_cart_button service-voucher-prd" data-product_id="<?php echo $service_prd_id ?>" rel="nofollow">
							<span>Confirm Service</span>
						</a>
							        		
					</div>

				<?php } // else ?>            
                   
		        </div>
		        
		      </div>
		      
		    </div>
		  </div>	