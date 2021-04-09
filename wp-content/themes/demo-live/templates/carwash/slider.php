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

