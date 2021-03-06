<?php
 /* Template Name: service-partner */
get_header();
?>


<div id="pageContent">
			<!-- Panel Block -->
	<!-- Admin url for ajax function call -->
	<?php 
		$product_id = '';
		global $woocomerce;

	?>

	<div class="site-url" hidden=""><?php echo get_site_url(); ?></div>

	<span class="product_id" hidden=""><?php if(isset($_GET['product_id'])){echo $product_id = $_GET['product_id']; } ?></span>

	<input  type="hidden" name="" id="vehicle-type" class='vehicle-type' value="<?php if(isset($_SESSION['vehicle_type'])){ echo $_SESSION['vehicle_type']; }?>" />	
	

	<span class="prd_attr_vehicle" hidden=""><?php 
		$prd_attr_vehicle = '';
		if(isset($_GET['product_id']))
		{
			$product_id = $_GET['product_id'];
    		$product_variation = wc_get_product( $product_id );    		
    		$variation_data = $product_variation->get_data(); 
    			echo $prd_attr_vehicle = $variation_data['attributes']['pa_vehicle-type'];    		
		} ?>			
	</span>

	<span class="session-key" hidden><?php echo WC()->session->get_customer_id(); ?></span>
	<span class="cart_item_id" hidden=""><?php echo $cart_item_id = $_GET['cart_item_id']; ?></span>
	<span class="current-pincode-text" hidden=""><?php echo $_SESSION['current_pincode']; ?></span>
	<?php
		$vehicle_id = '';
  		if(isset($_POST['vehicle_type']))
  		{
  			$vehicle_id = $_POST['vehicle_type'];  		
  			echo '<span class="vehicle-id" hidden="">'.$vehicle_id.'</span>';
	  		$row = $wpdb->get_results("SELECT * FROM th_service_data_price where vehicle_id = $vehicle_id and service_data_id = '4'");
	        foreach ($row as $data)
	        {
	            echo '<input class="service_rate" id="service_rate" value="'.$data->rate.'" type="hidden">';
	        
	        }
	    }else{
	    	echo '<span class="vehicle-id" hidden=""></span>';
	    	echo '<input class="service_rate" id="service_rate" value="" type="hidden">';
	    }
	?>
	<!-- Service Voucher product id -->
	    <?php 
	    	$sku = 'service_voucher';
  			$service_voucher_prd = $wpdb->get_var( $wpdb->prepare( "SELECT post_id FROM $wpdb->postmeta WHERE meta_key='_sku' AND meta_value='%s' LIMIT 1", $sku ) );
	    ?>
	<div class="container service-partner-page">
		<?php 
		if(isset($_GET['product_id']))
		{
		?>
		<div class="main-breadcrumb">
		<ul class="breadcrumb-list-new">
            <li class="">
                <div class="stop-rounding">
                    <span class="step-number">
                        <img class="car-img" src="<?php echo bloginfo('template_directory');?>/images/select_tyre.png" >
                    </span>
                </div> 
                <a href="#" class="step-link"> Select Tyre </a>
            </li>
            <li class="active">
                                    <!-- <img src="" alt="step1" />    -->
                <div class="stop-rounding">
                    <span class="step-number">
                         <img class="car-img" src="<?php echo bloginfo('template_directory');?>/images/select_service.png" >
                    </span>
                </div> 
                <a href="#" class="step-link"> Select Service Partner </a>
            </li>
            <li class="">
                                    <!-- <img src="" alt="step1" />    -->
                <div class="stop-rounding">
                    <span class="step-number">
                        
                        <img class="car-img" src="<?php echo bloginfo('template_directory');?>/images/review.png" >
                    </span>
                </div> 
                <a href="#" class="step-link"> Review Order </a>
            </li>
            <li class="">
                <div class="stop-rounding">
                    <span class="step-number">
                        <img class="car-img" src="<?php echo bloginfo('template_directory');?>/images/pay.png" >
                    </span>
                </div> 
                <a href="#" class="step-link"> Check Out & Pay </a>
            </li>
            <li class="">
                <div class="stop-rounding">
                    <span class="step-number">
                        <img class="car-img" src="<?php echo bloginfo('template_directory');?>/images/order_placed.png" >
                    </span>
                </div> 
                <a href="#" class="step-link"> Order Placed </a>
            </li>
        </ul>
    </div>
<?php } ?>
		<div class="location_section1">
			<span class="current-lat" hidden=""></span>
			<span class="current-lon" hidden=""></span>
		  	<?php 
		  	if(isset($_GET['product_id']))
			{?> 
		  	
		  	
		<div class="container title_section">
			
			<div class="col-md-12 first-section">
				
				<div class="col-md-6 col-sm-6 left" id="example1">
					<?php include_once('service-partner/delivery_existing_prd.php'); ?>
				</div>

				<div class="col-md-6 col-sm-6 right" id="servi-partner-right">
					<div class="location_part2">
						<h2>Service Partner</h2>
						<p>Select one of our trusted and certified Service Partner for your selected tyre???s Fitting, Alignment and Balancing. </p>
					</div>
					<div class="row">
		<div class="col-md-8 col-sm-8">
		<i class="fa fa-search" aria-hidden="true"></i>
		<input id="myInput" class="search_input" type="text" value="<?php echo $_SESSION['current_pincode']; ?>" placeholder="Enter Pincode or Location" autocomplete="new-password"/>

		<table id="address" style="display: none">
		     <tr>
		     	<td class="slimField"><input class="field" id="street_number"
		             disabled="true"></td>
		       	<td class="wideField" colspan="2"><input class="field" id="route"
		             disabled="true"></td>
		     </tr>
		     <tr>		       
		       	<td class="wideField" colspan="3"><input class="field" id="locality"
		             disabled="true"></td>
		     </tr>
		     <tr>
		       <td class="slimField"><input class="field"
		             id="administrative_area_level_1" disabled="true"></td>
		       <td class="wideField"><input class="field" id="postal_code"
		             disabled="true" value="<?php echo $_SESSION['current_pincode']; ?>"></td>
		     </tr>
		     <tr>
		       <td class="label">Country</td>
		       <td class="wideField" colspan="3"><input class="field"
		             id="country" disabled="true"></td>
		     </tr>
	   	</table>
	   </div>
	   <div class="col-md-4 col-sm-4">

	 
			<button class="button searchbtn" id="service-partner-btn">Change</button>
			<div class="pincode-error" style="color:red"></div>
		</div>

	</div>
				</div>
			</div>
		</div>
		<?php } ?>
		<?php
		if(!isset($_GET['product_id'])){
			?>
		 <div class="col-md-12 col-sm-12 filter-section">
				
				<form method="post" class="redirect-to-service-partner" action='<?php echo get_site_url().'/online-tyre-services-partner'; ?>'>
					<div class="main-container">

						<div class="service-column col-md-4 col-sm-6">
							<div class="inner">
								<h4>Select Vehicle Type</h4>
						<?php
                              global $wpdb;

                              $row = $wpdb->get_results("SELECT * FROM th_vehicle_type WHERE wheel_type = '2'");
                              foreach ($row as $data)
                              {
                                $vehicle_id = $data->vehicle_id;

                                  $price_row = $wpdb->get_results("SELECT * FROM th_service_data_price where vehicle_id = $vehicle_id and service_data_id = '4'");
                                  foreach ($price_row as $data1)
                                  {
                                     
                                  }
                           ?>   
                           <div class="inputGroup vehicle_type">
                            <input id="<?php echo 'vehicle'.$data->vehicle_type ?>" name="vehicle_type" type="radio" value="<?php echo $data->vehicle_id ?>" required <?php if(isset($_POST['vehicle_type']) && $_POST['vehicle_type'] == $data->vehicle_id){ echo 'checked'; } ?>/>
                            <input type="hidden" name="sprice" id="sprice<?=$data->vehicle_id;?>" value="<?=$data1->rate;?>">
                           	
                            <label for="<?php echo 'vehicle'.$data->vehicle_type ?>">
                              
                              <?php 
                              if($data->vehicle_type == 'Hatchback'){?>
                                 <img class="slider-car-img" src="<?php echo bloginfo('template_directory');?>/images/hatchback-car-img.png">

                              <?php }
                              elseif($data->vehicle_type == 'Sedan'){?>
                                 <img src="<?php echo bloginfo('template_directory');?>/images/sedan-car-img.png" class="slider-car-img">
                              <?php } 
                              elseif($data->vehicle_type == 'Suv'){?>
                                 <img src="<?php echo bloginfo('template_directory');?>/images/suv-car-img.png" class="slider-car-img">
                              <?php }
                              elseif($data->vehicle_type == 'Premium Car'){?>
                                  <img src="<?php echo bloginfo('template_directory');?>/images/audi-logo.png" style="width: 25px;">
                                     <img src="<?php echo bloginfo('template_directory');?>/images/mercedes-benz-logo.png" style="width: 25px;">
                                     
                              <?php } 
                               echo $data->vehicle_type;
                               echo '(<span class="rate">'.get_woocommerce_currency_symbol().$data1->rate.'</span>)';
                              ?>
                              
                                
                            </label>
                           </div>                          
                           <?php } ?>
                           </div>
                        </div>

						<div class="location-column col-md-4 col-sm-6">
							<div class="inner">
							<h4>Enter Location</h4>					
							<div class="column search-box">
								<i class="fa fa-search" aria-hidden="true"></i>						
								<input id="myInput" name="search_text" class="search_input" type="text" value="<?php echo $_SESSION['current_pincode'];?>" autocomplete="new-password" placeholder="Please enter pincode! " />
								<table id="address" style="display: none">
								     <tr>
								     	<td class="slimField"><input class="field" id="street_number"
								             disabled="true"></td>
								       	<td class="wideField" colspan="2"><input class="field" id="route"
								             disabled="true"></td>
								     </tr>
								     <tr>		       
								       	<td class="wideField" colspan="3"><input class="field" id="locality"
								             disabled="true"></td>
								     </tr>
								     <tr>
								       <td class="slimField"><input class="field"
								             id="administrative_area_level_1" disabled="true"></td>
								       <td class="wideField"><input class="field" id="postal_code" name="search_pincode"
								             disabled="true" value=""></td>
								     </tr>
								     <tr>
								       <td class="label">Country</td>
								       <td class="wideField" colspan="3"><input class="field"
								             id="country" disabled="true"></td>
								     </tr>
							   	</table>					   
								<div class="pincode-error" style="color:red"></div>
							</div>

							<div class="column">
								<input type="button" class="btn btn-invert searchbtn" name="move-to-installer" value="Search"> 
							</div>
							</div>
						</div>
</div>
				</form>	
			</div> 
		<?php
		}

		?>
		<div class="container">
			<?php if(isset($_GET['product_id'])){
			 		include('service-partner/search-map-section.php'); 
				} ?>
			
			<div class="col-md-12 col-sm-12">
				<div id="tab-1" class="tab-content current">
				<?php
				 	global $wpdb;

                   	if($prd_attr_vehicle == 'two-wheeler' || $prd_attr_vehicle == 'three-wheeler'){
			 			$row = $wpdb->get_results("SELECT * FROM th_installer_data where user_id = 55 || user_id = 61");
			 		}
			 		else{
			 			$row = $wpdb->get_results("SELECT * FROM th_installer_data");
			 		}
                    foreach ($row as $data) {
                    	?>
                    	<div class="single-list-for-map" data-id="<?php echo $data->installer_data_id; ?>">
                    		<div class="address" hidden><strong><?php echo $data->business_name.'</strong><br>'.$data->address.'<br>'.$data->state.'<br>'.$data->city.'-'.$data->pincode; ?>
                			</div>
                			<span class="lattitude" hidden=""><?php echo $data->location_lattitude; ?></span>
							<span class="longitude" hidden=""><?php echo $data->location_longitude; ?></span>
                		</div>
                    	<?php
                    }
                ?>

				<div class="col-md-12 col-sm-12 installer-tab-content installer-list active">
						<!-- Result From Ajax call -->			
				</div>

				<div class="installer-tab-content installer-map col-md-12 col-sm-12">
					<div style="width: 80%;">
						<div id="map" style="height: 500px;"></div>
					</div>
				</div>

				</div>

<!-- Second Tab -->
	<div id="tab-2" class="tab-content">
		Duis aute irure dolor in reprehenderit in voluptate velit esse cillum dolore eu fugiat nulla pariatur. Excepteur sint occaecat cupidatat non proident, sunt in culpa qui officia deserunt mollit anim id est laborum.
	</div>


</div>
</div>
</div>
			</div>
			
			<div class="block offset-sm">
				<div class="container">
					
				</div>
			</div>
			<!-- //Panel Block -->
		</div>

<?php
get_footer();
?>