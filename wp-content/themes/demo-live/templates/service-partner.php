<?php
 /* Template Name: service-partner */
get_header();

if($_POST){
	$_SESSION['vehicle_type']=$_POST['vehicle_type'];
}else{

	if($_GET['product_id']!='' && $_GET['cart_item_id'] !=''){

	}else{
		wp_redirect(site_url());
	}
}
?>

<style>
/* The container */
.services {
  display: inline-block;
  position: relative;
  padding-left: 35px;
  margin-bottom: 0px;
  cursor: pointer;
  font-size: 22px;
  -webkit-user-select: none;
  -moz-user-select: none;
  -ms-user-select: none;
  user-select: none;
  padding-right: 15px;
  color: #2E3571;
}

/* Hide the browser's default checkbox */
.services input {
  position: absolute;
  opacity: 0;
  cursor: pointer;
  height: 0;
  width: 0;
}

.services-list.with-tyre {
	display: inline-block;
    width: 100%;	
}
.services-list.with-tyre .service_filter {
	display: inline-block;
	width: 100%;
    text-align: center;
	background-color: #3E4796;
	background-image: unset;
	border-radius: 0;
}
.services-list.with-tyre .service_filter .vehicle_part {
    display: inline-block;
    width: 100%;
    padding: 10px 0px;
}
.services-list.with-tyre .service_filter .vehicle_part .services {
	color: #fff;
}
/* Create a custom checkbox */
.checkmark {
	position: absolute;
	top: 0;
	left: 0;
	height: 22px;
	width: 22px;
	background-color: #fff;
    border: 2px solid #D1D7DC;
}
/* On mouse-over, add a grey background color */
.services:hover input ~ .checkmark,
.services input:checked ~ .checkmark {
  	background-color: #E4C24D;
	border: 2px solid #E4C24D;
}
/* Create the checkmark/indicator (hidden when not checked) */
.checkmark:after {
  content: "";
  position: absolute;
  display: none;
}
/* Show the checkmark when checked */
.services input:checked ~ .checkmark:after {
  display: block;
}

/* Style the checkmark/indicator */
.services .checkmark:after {
	left: 6px;
	top: 0px;
	width: 7px;
	height: 14px;
	border: solid #000;
	border-width: 0 3px 3px 0;
	-webkit-transform: rotate(45deg);
	-ms-transform: rotate(45deg);
	transform: rotate(45deg);
}
</style>
<div id="pageContent">
			<!-- Panel Block -->
	<!-- Admin url for ajax function call -->
	<?php 
		$product_id = '';
		global $woocomerce;
	?>
	<div class="site-url" hidden=""><?php echo get_site_url(); ?></div>
	<span class="product_id" hidden=""><?php if(isset($_GET['product_id'])){echo $product_id = $_GET['product_id']; } ?></span>
	<input  type="hidden" name="cart_tyre" id="cart_tyre" class='cart_tyre' value="<?php echo $_GET['total_qty']; ?>" />
	<input  type="hidden" name="" id="vehicle-type" class='vehicle-type' value="<?php if(isset($_SESSION['vehicle_type'])){ echo $_SESSION['vehicle_type']; }?>" /><span class="prd_attr_vehicle" hidden="">
		<?php 
		$prd_attr_vehicle = '';
		if(isset($_GET['product_id']))
		{
			$product_id = $_GET['product_id'];
    		$product_variation = new WC_Product_Variation( $product_id );    		
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
	  		/*$row = $wpdb->get_results("SELECT * FROM th_service_data_price where vehicle_id = $vehicle_id and service_data_id = '4'");*/
	  							$service_data_id=$_POST['service_type'];
                                  if($service_data_id==4){
                                 	$SQL="SELECT * FROM th_service_data_price where vehicle_id = $vehicle_id and service_data_id = '".$service_data_id."'";
								  $data1 = $wpdb->get_row($SQL);
 	
                                  }

                                  if($service_data_id==5){
                                 	$SQL="SELECT * FROM th_installer_service_price where vehicle_id = $vehicle_id and service_data_id = '".$service_data_id."'";
								  $data1 = $wpdb->get_row($SQL);
 	
                                  }
	         echo '<input class="service_rate" id="service_rate" value="'.$data1->rate.'" type="hidden">';
	         echo '<input class="service_id" id="service_id" value="'.$service_data_id.'" type="hidden">';
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
	<div class="service-partner-page">
		
		<?php if(isset($_GET['product_id'])) { ?>
			<div class="container">
				<div class="row">
					<div class="main-breadcrumb">
						<?php main_breadcrumb('service-partner');?>
					</div>
				</div>
			</div>
		<?php } ?>
		<div class="location_section1">
			<span class="current-lat" hidden=""></span>
			<span class="current-lon" hidden=""></span>
			<?php if(isset($_GET['product_id'])) { ?> 
				<section class="title_section">
					<div class="container">
						<div class="row">
							<div class="first-section">
								<div class="col-md-6 col-sm-6 left" id="example1">
									<?php include_once('service-partner/delivery_existing_prd.php'); ?>
								</div>

								<div class="col-md-6 col-sm-6 right" id="servi-partner-right">
									<div class="location_part2">
										<h2>Service Partner</h2>
										<p>Select one of our trusted and certified Service Partner for your selected tyre’s Fitting, Alignment and Balancing.</p>
										<div class="row">
											<div class="col-xs-7 col-sm-8">
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
											<div class="col-xs-5 col-sm-4">
												<button class="button searchbtn" id="service-partner-btn">Change</button>
												<div class="pincode-error" style="color:red"></div>
											</div>
										</div>
									</div>
								</div>
							</div>
							 
						</div>
					</div>
				</section>
			<?php } ?>
			<?php if(!isset($_GET['product_id'])) { ?>
			<section class="store_location_part">
				<div class="container filter-section">
					<form method="post" class="redirect-to-service-partner" action='<?php echo get_site_url().'/online-tyre-services-partner'; ?>'>
						<div class="row">
							<div class="service-column col-md-6 col-sm-6">
								<div class="inner service_filter">
									<div class="title_part">
										<div class="title">
											Select Vehicle
										</div>
									</div>
									<div class="vehicle_part">
										<?php
											global $wpdb;
											$row = $wpdb->get_results("SELECT * FROM th_vehicle_type WHERE wheel_type = '2'");
											foreach ($row as $data) {
												$vehicle_id = $data->vehicle_id;
												$service_data_id=$_POST['service_type'];
												if($service_data_id==4){
													$SQL="SELECT * FROM th_service_data_price where vehicle_id = $vehicle_id and service_data_id = '".$service_data_id."'";
													$data1 = $wpdb->get_row($SQL);
												}
												if($service_data_id==5){
													$SQL="SELECT * FROM th_installer_service_price where vehicle_id = $vehicle_id and service_data_id = '".$service_data_id."'";
													$data1 = $wpdb->get_row($SQL);
												}
											?>   
											<div class="inputGroup vehicle_type">
												<input id="<?php echo 'vehicle'.$data->vehicle_type ?>" name="vehicle_type" type="radio" value="<?php echo $data->vehicle_id ?>" required <?php if(isset($_POST['vehicle_type']) && $_POST['vehicle_type'] == $data->vehicle_id){ echo 'checked'; } ?>/>
												<input type="hidden" name="sprice" id="sprice<?=$data->vehicle_id;?>" value="<?=$data1->rate;?>">

												<label for="<?php echo 'vehicle'.$data->vehicle_type ?>">
													<?php if($data->vehicle_type == 'Hatchback') { ?>
														<img src="<?php echo get_stylesheet_directory_uri(); ?>/images/slide-icon/car_type/hatchback.png">
													<?php } elseif($data->vehicle_type == 'Sedan') { ?>
														<img src="<?php echo get_stylesheet_directory_uri(); ?>/images/slide-icon/car_type/sedan.png">
													<?php } elseif($data->vehicle_type == 'Suv') { ?>
														<img src="<?php echo get_stylesheet_directory_uri(); ?>/images/slide-icon/car_type/suv.png">
													<?php }	elseif($data->vehicle_type == 'Premium Car') { ?>
														<img src="<?php echo get_stylesheet_directory_uri(); ?>/images/slide-icon/car_type/premium.png">
													<?php } 
													echo $data->vehicle_type;
													echo '(<span class="rate">'.get_woocommerce_currency_symbol().$data1->rate.'</span>)';
													?>
												</label>
											</div>                          
										<?php } ?>
									</div>
								</div>
							</div>
							<div class="location-column col-md-6 col-sm-6">
								<div class="service_filter">
									<div class="title_part">
										<div class="title">
											Enter Location
										</div>
									</div>
									<div class="img_part">
										<img class="normal" src="<?php echo get_stylesheet_directory_uri(); ?>/images/slide-icon/location_img.png" />
									</div>
									<div class="vehicle_part">
										<div class="column search-box">
											<div class="search_box_inner">
												<i class="fa fa-search" aria-hidden="true"></i>						
												<input id="myInput" name="search_text" class="search_input" type="text" value="<?php echo $_SESSION['current_pincode'];?>" autocomplete="new-password" placeholder="Please enter pincode! " />
												<input type="button" class="btn searchbtn" name="move-to-installer" value="Search">
											</div>
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
									</div>
								</div>
							</div>
							<?php if($service_data_id==5) { 
									$class='disabled';
									$checked='checked'; ?> 
									<input type="hidden" name="services_id" id="services_id" value="5">
							<!-- <input type="checkbox" style="display: none;" name="services[]" value="5" checked=""> -->
								<?php } ?>


								<?php if($service_data_id==4) { 
									$class='disabled';
									$checked='checked'; ?> 
									<input type="hidden" name="services_id" id="services_id" value="4">
								<?php } ?>

							<!-- <div class="services-list col-md-4 col-sm-6">
								<div class="service_filter">
									<div class="title_part">
										<div class="title">
											Addtional Services
										</div>
									</div>
									<div class="vehicle_part">
										<?php if($service_data_id==5) { 
											$class='disabled';
											$checked='checked';  
										} ?>
										<div class="services-list">
											<div class="list-inner">
												<?php 
												global $wpdb;
												$SQL="SELECT * FROM `th_service_data` WHERE status=1 AND service_onoff_on_listing=1";
												$results=$wpdb->get_results($SQL);
												foreach ($results as $key => $value) { ?>
													<label class="services"><?=$value->service_name;?>
													<input type="checkbox" name="services[]" value="<?=$value->service_data_id;?>" <?=$class?> <?=$checked;?>>
													<span class="checkmark"></span>
													</label>
												<?php } ?>
											</div>
										</div>
									</div>
								</div>
							</div> -->
						</div>
						
					</form>
				</div>
			</section>
			 
			<?php }	?>
			<div class="container">
				<div class="row">
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

							<div class="installer-tab-content installer-list active">
								<!-- Result From Ajax call -->			
							</div>
							<div id="link_other_service_parter" style="display: none;">Find the other Nearest Service Partner, Please <a href="#" id="sc_top">click Here</a></div>

							<div class="installer-tab-content installer-map">
								<div style="width: 100%;">
									<div id="map" style="height: 500px;"></div>
								</div>
							</div>
						</div>


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