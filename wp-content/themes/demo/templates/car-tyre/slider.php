<div class="car-tyre-part">
    <div class="filter-sec">
		<div class="container">
			<div class="row">
				<div class="col-sm-12 col-sm-6 col-md-6 col-lg-6">
					<div class="service_filter">
						<div class="title_part">
							<a href="<?php echo get_home_url(); ?>" class="img-sec">
								<img class="normal" src="<?php echo get_stylesheet_directory_uri(); ?>/images/slide-icon/back_arrow.png" />
							</a>
							<div class="title">Buy Car Tyre</div>
						</div>
						<div class="img_part">
							<img class="normal tab_img img_byvehicle active" src="<?php echo get_stylesheet_directory_uri(); ?>/images/slide-icon/buy_car_tyre_img.png"  />
							<img src="<?php echo get_stylesheet_directory_uri(); ?>/images/slide-icon/buy_car_tyre_size_img.png" class="tab_img img_bysize">
						</div>
						<span class="shop_page_url" hidden><?php echo  get_permalink( wc_get_page_id( 'shop' ) ); ?></span>
						<div class="vehicle_part">
							<h3 class="vehicle_title">Get The Right Tyres</h3>
							<div class="vertical-tab">
								<div class="vertical-tab-content active">
									<div class="tab_button">
										<a id="byvehicle" class="toggle-btn active">By Vehicle</a>
										<a id="bysize" class="toggle-btn">By Size</a>
									</div>
									<div class="form-tab byvehicle active">
										<form id="tab1-form">
											<div class="form-group">
												<div class="select-wrapper">
													<select name="select-car-cmp" class="select2 input-custom select-car-cmp" required>
														<option value="" disabled selected="">Make</option>
													<?php
													if(!isset($_GET['modifysearch'])) {
														unset($_SESSION['make_id']);
														unset($_SESSION['model_id']);
														unset($_SESSION['sub_model_id']);
													}
													global $wpdb , $woocommerce;
													$make_data = $wpdb->get_results("SELECT * FROM th_make where vehicle_type = '1' AND status =1 order by make_name asc");

													foreach ($make_data as $data) {
														$make_id = $data->make_id;
														$make_name = $data->make_name;
													?>    
														<option value="<?php echo $make_id; ?>" <?php if(isset($_SESSION['make_id']) && $_SESSION['make_id'] == $make_id){ echo 'selected'; }?>><?php echo $make_name; ?></option>
													<?php } ?>
													</select>
												</div>
											</div>
											<div class="form-group">
												<div class="select-wrapper year-wrapper" >
													<select disabled="disabled" name="select1" class=" select2 input-custom select-model" required>
														<option value="" selected="">Model</option>
													</select>
												</div>
											</div>
											<div class="form-group">
												<div class="select-wrapper model-wrapper">
													<select name="select3" disabled="disabled" class="select2 input-custom select-sub-model" required>
														<option value="" disabled selected>Sub Model</option>
													</select>
												</div>
											</div>

											<div class="form-group">
												<div class="select-wrapper">
													<select name="vehicle_type_model" id="vehicle_type_model"  class="select2 input-custom" required>
														<option value="">Select Vehicle Type</option>
														<?php
														global $wpdb;
														$row = $wpdb->get_results("SELECT * FROM th_vehicle_type WHERE wheel_type = '2'");
														foreach ($row as $data) {
														?>
															<option value="<?php echo $data->vehicle_id ?>"><?php echo $data->vehicle_type;?></option>
														<?php } ?>
													</select>
													<span id="fortooltip" data-toggle="tooltip" data-placement="right" title="How to know your car type" data-toggle="modal" data-target="#car_type_info" ><i class="fa fa-question-circle"></i></span>
												</div>
											</div>
										</form>
										<span class="select-error" style="display: none;">Please select all criteria</span>
										<button class="get-tyre-bymodel">
											<span>Get Tyre Pricing</span>
										</button>
									</div>
									<div class="form-tab bysize">
										<form id="tab1-form">
											<div class="form-group">
												<div class="select-wrapper">
													<select name="select1" class="select2 input-custom select-width">
														<option value="">Width</option>
													<?php
													$width_data = $wpdb->get_results("SELECT * FROM th_width WHERE car=1 AND status=1 ORDER by width_value ASC ");
													$width_arr = [];
													foreach ($width_data as $width_data) {
														$width_id = $width_data->width_id;
														$width_value = $width_data->width_value;
														if(!in_array($width_value, $width_arr)) {
															$width_arr[] = $width_data->width_value;
													?>    
														<option value="<?php echo $width_id; ?>"><?php echo $width_value; ?></option>
													<?php
														}
													}
													?>
													</select>
												</div>
											</div>
											<div class="form-group">
												<div class="select-wrapper ratio-wrapper">
													<select name="select1" disabled="disabled" class="select2 input-custom select-ratio" required>
														<option value="" selected="" disabled="">Ratio/Profile</option>
													</select>
												</div>
											</div>
											<div class="form-group">
												<div class="select-wrapper diameter-wrapper">
													<select name="select1" disabled="disabled" class="select2 input-custom select-diameter" required>
														<option value="" disabled="" selected="">Rim Diameter</option>
													</select>
												</div>
											</div>
											<div class="form-group">
												<div class="select-wrapper">
													<select name="vehicle_type_size" id="vehicle_type_size"  class="select2 input-custom" required>
														<option value="">Select Vehicle Type</option>
													<?php
													global $wpdb;
													$row = $wpdb->get_results("SELECT * FROM th_vehicle_type WHERE wheel_type = '2'");
													foreach ($row as $data) { ?>
														<option value="<?php echo $data->vehicle_id ?>"><?php echo $data->vehicle_type;?></option>
													<?php }?>
													</select>
													<span id="fortooltip" data-toggle="tooltip" data-placement="right" title="How to know your car type" data-toggle="modal" data-target="#car_type_info" ><i class="fa fa-question-circle"></i></span>
												</div>
											</div>
											
										</form>
										<span class="select-error-size" style="display: none;">Please select all criteria</span>
										<button class="get-tyre-bywidth">
											<span>Get Tyre Pricing</span>
										</button>
									</div>
								</div>
							</div>	
						</div>
					</div>
				</div>
			</div>
		</div>
  	</div>
</div>



<!-- <div class="modal fade" id="car_type_info" role="dialog"> -->
	<div class="modal" id="car_type_info" role="dialog">
    <div class="modal-dialog">        
      <!-- Modal content-->
      <div class="modal-content">
        <div class="modal-header">
            <button type="button" class="close" data-dismiss="modal">&times;</button>        
          </div>
          <div class="modal-body">

        <!-- select Car Tyre Screen -->
        <div id="tab4-form" method="post" class="select-car-type screen">
          
              <div class="data-header inputGroup">   
              </div>               
              <?php           
              
                                
              global $wpdb;             
              
                $row = $wpdb->get_results("SELECT * FROM th_vehicle_type WHERE wheel_type = '2'");
             
              
              foreach ($row as $data) {
            ?>      
                    <div class="vehicle_type" style="font-size: 18px;">
                        <label for="<?php echo $data->vehicle_type ?>" style="padding: 10px 10px;">
                          
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
                                  ?>
                                  <?php echo $data->vehicle_type ?>    
                        </label>
                    </div>                          
                      <?php } //foreach ?> 
                    <div class="modal-footer">
              <div class="right">
              </div>
              <div class="left" style="width: 100%;">
                  <button class="next-to-service-voucher btn btn-invert button modal-btn" data-dismiss="modal">
                    <span>OK</span>
                  </button>
              </div>
            </div>
        </div>
 
            </div>
            
          </div>
          
        </div>
      </div>  
