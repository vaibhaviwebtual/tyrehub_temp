
<div class="car-wash-part">
    <div class="filter-sec">
		<div class="container">
			<div class="row">
				<div class="col-sm-12 col-sm-6 col-md-6 col-lg-6">
					<div class="service_filter">
						<div class="title_part">
							<a href="<?php echo get_home_url(); ?>" class="img-sec">
								<img class="normal" src="<?php echo get_stylesheet_directory_uri(); ?>/images/slide-icon/back_arrow.png" />
							</a>
							<div class="title">
								Car Wash
							</div>
						</div>
						<div class="img_part">
							<img class="normal" src="<?php echo get_stylesheet_directory_uri(); ?>/images/slide-icon/car_wash_img.png" />
						</div>
						<div class="vehicle_part">
							<h3 class="vehicle_title">Select Vehicle</h3>
							<form id="tab4-form" action="<?php echo get_site_url().'/online-tyre-services-partner'?>" method="post">  
								<input type="hidden" name="service_type" id="service_type" value="5">                    
							<?php
							global $wpdb;
							$row = $wpdb->get_results("SELECT * FROM th_vehicle_type WHERE wheel_type = '2'");
							foreach ($row as $data) {
								$vehicle_id = $data->vehicle_id;
								$data1 = $wpdb->get_row("SELECT * FROM th_installer_service_price where vehicle_id = $vehicle_id and service_data_id = 5");
							?>   
								<div class="inputGroup vehicle_type">
									<input id="<?php echo 'vehicle'.$data->vehicle_type ?>" name="vehicle_type" type="radio" value="<?php echo $data->vehicle_id ?>" required/>
									<label for="<?php echo 'vehicle'.$data->vehicle_type ?>">

									<?php if($data->vehicle_type == 'Hatchback') { ?>
										<img src="<?php echo get_stylesheet_directory_uri(); ?>/images/slide-icon/car_type/hatchback.png">
									<?php } elseif($data->vehicle_type == 'Sedan'){?>
										<img src="<?php echo get_stylesheet_directory_uri(); ?>/images/slide-icon/car_type/sedan.png">
									<?php } elseif($data->vehicle_type == 'Suv') { ?>
										<img src="<?php echo get_stylesheet_directory_uri(); ?>/images/slide-icon/car_type/suv.png">
									<?php } elseif($data->vehicle_type == 'Premium Car') { ?>
										<img src="<?php echo get_stylesheet_directory_uri(); ?>/images/slide-icon/car_type/premium.png">
									<?php } ?>
									<?php echo $data->vehicle_type ?>
									<?php echo '(<span class="rate">'.get_woocommerce_currency_symbol().$data1->rate.'</span>)'; ?>
									</label>
								</div>                          
							<?php } ?>

							<input type="submit" name="" class="btn btn-lg btn-full get-installer" value="SUBMIT">
							</form>
						</div>
					</div>
				</div>
			</div>
		</div>
  	</div>
</div>

<div class="slider-container">
      <div class="container marqueeContainer">
        <div class="row">
          <div class="site_url" hidden=""><?php echo get_site_url(); ?></div>


       <div class="col-sm-12 col-sm-4 col-md-4 col-lg-4">
        <div class="form_balancing">
        <div class="vertical-tab-container" id="tabForm">
               <div class="vertical-tab-menu">
                     <div class="list-group">                    
                     <a href="#" class="list-group-item active text-center">
                      
                      <i class="icon icon-disc-brake"></i>
                      <span class="vericaltext">Car Washing</span>
                      <i class="icon icon-disc-brake"></i>
                     </a>
                  </div>
               </div>
            <div class="vertical-tab-balancing">
               <img src="<?php
                  echo bloginfo('template_directory');
                  ?>/images/tyre-alignment.png" class="img_byvehicle active">
              
               <!-- Brakes section -->
               <div class="vertical-tab-content service active">
                  <h3>Select Vehicle Type</h3>
                  <div class="clearfix">
                    <!-- <div id="byinstaller" class="toggle-btn active" style="width: 100%;">By Installer</div> -->
                  </div>
                  <div class="byinstaller">
                     <form id="tab4-form" action="<?php echo get_site_url().'/online-tyre-services-partner'?>" method="post">  
                      <input type="hidden" name="service_type" id="service_type" value="5">                    
                           <?php
                              global $wpdb;

                              $row = $wpdb->get_results("SELECT * FROM th_vehicle_type WHERE wheel_type = '2'");
                              foreach ($row as $data) {
                                $vehicle_id = $data->vehicle_id;

                                /*$data1 = $wpdb->get_row("SELECT * FROM th_service_data_price where vehicle_id = $vehicle_id and service_data_id = '5'");*/

                                $data1 = $wpdb->get_row("SELECT * FROM th_installer_service_price where vehicle_id = $vehicle_id and service_data_id = 5");
                                  
                              
                           ?>   
                           <div class="inputGroup vehicle_type">
                            <input id="<?php echo 'vehicle'.$data->vehicle_type ?>" name="vehicle_type" type="radio" value="<?php echo $data->vehicle_id ?>" required/>
                            <label for="<?php echo 'vehicle'.$data->vehicle_type ?>">
                              
                                <?php 
                              if($data->vehicle_type == 'Hatchback'){?>
                                 <img src="<?php echo bloginfo('template_directory');?>/images/hatchback-car-img.png" class="slider-car-img">
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
                                     
                              <?php } ?>
                              <?php echo $data->vehicle_type ?>
                              <?php echo '(<span class="rate">'.get_woocommerce_currency_symbol().$data1->rate.'</span>)'; ?>
                              </label>
                           </div>                          
                           <?php } ?>
                        
                        <input type="submit" name="" class="btn btn-lg btn-full get-installer" value="Get Installer">
                     </form>
                    <!--  <button ><span>Get Brakes Pricing</span></button> -->
                  </div>
                  <!-- <p class="comment">All fields are required</p> -->
               </div>
            </div>
         </div>
</div>
      </div>

   </div>
      </div>
      <div id="mainSliderWrapper">
   <?php
     // echo do_shortcode('[smartslider3 slider=2]');
      ?>
</div>
</div>

<div class="modal fade" id="car_type_info" role="dialog">
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
