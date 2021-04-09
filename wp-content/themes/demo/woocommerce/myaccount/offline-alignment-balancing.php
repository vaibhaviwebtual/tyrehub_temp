<?php
cart_clear();
if ( !is_user_logged_in() )
 	{
      wp_redirect(get_permalink( get_option('woocommerce_myaccount_page_id') ).'/installer-login' );    
  	}
get_header();

$_SESSION['offline']='yes';
?>
<div id="pageContent">
	<div class="balancing-alignment-part">
		<div class="filter-sec">
			<div class="container">
				<div class="row">
					<div class="col-sm-12 col-sm-6 col-md-6 col-lg-6">
						<div class="service_filter">
							<div class="title_part">
								<div class="title">Balancing & Alignment</div>
							</div>
							<div class="img_part">
								<img class="normal" src="<?php echo get_stylesheet_directory_uri(); ?>/images/slide-icon/balancing_alignment_img.png" />
							</div>
							<div class="vehicle_part">
								<h3 class="vehicle_title">Select Vehicle Type</h3>
								<div id="error-msg" style="color: red;"></div>
								<form id="tab4-form" action="#" method="post">  
									<input type="hidden" name="service_type" id="service_type" value="4">                    
								<?php
								global $wpdb;
								$row = $wpdb->get_results("SELECT * FROM th_vehicle_type WHERE wheel_type = '2'");
								foreach ($row as $data) {
									$vehicle_id = $data->vehicle_id;
									$data1 = $wpdb->get_row("SELECT * FROM th_service_data_price where vehicle_id = $vehicle_id and service_data_id = '4'");
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

								<input type="button" class="btn btn-lg btn-full car-wash-add" value="SUBMIT">
								</form>
							</div>
						</div>
					</div>
				</div>
			</div>
		</div>
	</div>
</div>
<div class="modal" id="duplicate_service_m" role="dialog">
    <div class="modal-dialog">
    <!-- Modal content-->
    <div class="modal-content"  style="pointer-events: auto;">
         <div class="modal-header">       
          </div>
        <div class="modal-body">
            <p id="pro_msgs"></p>                               
        </div>
        <div class="modal-footer">
            <a href="<?php echo get_site_url().'/cart';?>" id="cartlink" style="display: none" class="btn btn-invert"><span>Cart</span></a>
            <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
        </div>
    </div>
</div>
</div>
<?php
get_footer();
?>