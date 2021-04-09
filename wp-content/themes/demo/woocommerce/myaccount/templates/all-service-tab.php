<?php 
	global $woocommerce, $wpdb;
	$current_user = wp_get_current_user();
	$role = $current_user->roles[0];
	$mobile_no = $current_user->user_login;
	$current_inst_id = $wpdb->get_var("SELECT installer_data_id FROM th_installer_data WHERE user_id =".$current_user->ID." LIMIT 1");
	$order_arr = [];

	if($role == 'Installer'){
		$installer = "SELECT * FROM th_cart_item_installer WHERE installer_id = '$current_inst_id' AND order_id!=0";
		$voucher_installer = "SELECT * FROM th_cart_item_service_voucher WHERE installer_id = '$current_inst_id' AND order_id!=0";
	}	
	elseif($role == 'administrator') {
		$installer = "SELECT * FROM th_cart_item_installer";
	}
	$row = $wpdb->get_results($installer); 
	if(!empty($row)) {
		foreach ($row as $key => $installer) {
			if($installer->order_id != 0){
				$order_arr[] = $installer->order_id;
			}
		}
	}
	$row1 = $wpdb->get_results($voucher_installer); 
	if(!empty($row1)) {
		foreach ($row1 as $key => $installer) {
			if($installer->order_id != 0) {
				$order_arr[] = $installer->order_id;
			}
		}
	}
	
	$order_arr = array_unique($order_arr);
	// var_dump($order_arr);
	/*echo '<pre>';
	print_r($order_arr);
	echo '</pre>';
	die;*/
	if($order_arr){
	$flog = 0;
	$order_query = array(
		'post__in' => $order_arr,
		'post_type' => 'shop_order',
		'numberposts'   => -1,
		'posts_per_page' => -1,
		'post_status' => array('wc-processing', 'wc-deltoinstaller'),	        
	);
	
	$loop = new WP_Query($order_query);


    	// var_dump($loop);
	if($loop->have_posts()) {
		while ($loop->have_posts()) {
			$loop->the_post();
			$order_id = $loop->post->ID;				        
			$order = wc_get_order($order_id);
			$order_data = $order->get_data(); 
			$order_items = $order->get_items();
			$order_date = $order->order_date;
			$order_status = $order->get_status(); //processing

			$order_status_name = esc_html( wc_get_order_status_name( $order->get_status() ) );
			// customer
			$user = $order->get_user();
			$first_name = $order_data['billing']['first_name'];
			$last_name = $order_data['billing']['last_name'];
			$mobile_no = $order_data['billing']['phone'];   
			$email = $order_data['billing']['email']; 
			/*echo '<pre>';
			print_r($order_items);
			echo '</pre>';*/
			$i=1;
			$j=0;
			foreach ($order_items as $item_id => $item_data) {
			if($item_data['variation_id'] != '') {
				$order_prd_id = $item_data['variation_id'];
			}
			else {
				$order_prd_id = $item_data['product_id'];
			}
			$sku = 'service_voucher';
			$service_voucher_prd = $wpdb->get_var( $wpdb->prepare( "SELECT post_id FROM $wpdb->postmeta WHERE meta_key='_sku' AND meta_value='%s' LIMIT 1", $sku ) );
			$product_variation = wc_get_product( $order_prd_id );
			$variation_des = $product_variation->get_description();
			$parent_id = $product_variation->get_parent_id();
			$image = wp_get_attachment_image_src( get_post_thumbnail_id( $parent_id ), 'single-post-thumbnail' );
			$prd_qty = $item_data['quantity'];
			if($order_prd_id == $service_voucher_prd) {
			 $service_data = "SELECT * FROM th_cart_item_service_voucher WHERE product_id = '$order_prd_id' and installer_id = '$current_inst_id' and order_id = '$order_id' and status != 'completed' LIMIT $j,$i";
				$i++;
				$j++;
			}else {
				if($role == 'Installer') {
					$service_data = "SELECT * FROM th_cart_item_installer WHERE product_id = '$order_prd_id' and installer_id = '$current_inst_id' and order_id = '$order_id' and status != 'completed' and destination = 1";
				}	
				elseif($role == 'administrator') {
					$service_data = "SELECT * FROM th_cart_item_installer WHERE product_id = '$order_prd_id' and order_id = '$order_id' and status != 'completed' and destination = 1";
				}
			}

			//var_dump($service_data);

			$row = $wpdb->get_results($service_data);
			if(!empty($row)) {		
				$flog = 1;			    		        
				foreach ($row as $key => $data) {
					$destination = $data->destination;

					if($order_prd_id == $service_voucher_prd) {
						$service_voucher_id = $data->service_voucher_id;
						$vehicle_id = $data->vehicle_id;

						$vehicle_name = $wpdb->get_var( $wpdb->prepare( "SELECT vehicle_type FROM th_vehicle_type WHERE vehicle_id='%s' LIMIT 1" , $vehicle_id) ); 
						$voucher_name = $data->voucher_name;
						if($voucher_name == 'promotional' || $voucher_name == 'promotion') {
							$service_type = 'Promotion Voucher';
							$voucher_type = 'promotion';
						} else {
							$service_type = 'Service voucher for vehicle type: '.$vehicle_name;
							$voucher_type = 'service';
						}
					} else {
						$item_installer = $data->cart_item_installer_id;
					}
			?>
			<div class="single-service service-<?php echo $item_installer; ?>">
				<div class="image-block">
					<img src="<?php  if($image[0] != '') { echo $image[0]; } else { echo bloginfo('template_url').'/images/no_img1.png'; } ?>" data-id="<?php echo $loop->post->ID; ?>">
				</div>
				<div class="data-block">
					<div class="first-row">
						<div class="order-id"><strong>Order #<?php echo $order_id; ?></strong></div>
						<div class="date"><i class="fa fa-calendar"></i>
							<?php
							date_default_timezone_set("Asia/Kolkata");
							$order_date;
							echo $newDate = date("d-m-Y H:i a", strtotime($order_date));
							?>
						</div>
					</div>
					<?php 
					// voucher data
					if($order_prd_id == $service_voucher_prd && $voucher_type == 'promotion') { ?>
						<h3 class="tyre-name"><?php echo $service_type; ?></h3>
						
						<div class="customer-name"><i class="fa fa-user"></i>Tyre Hub</div>
						<a class="mobile-no" href="tel:1-800-233-5551"><i class="fa fa-phone"></i> 1-800-233-5551</a>
						
						<div class="service-details">
							<strong class="ser-sec-title">Services :</strong>
							<ul class="service-list">
								<li>
									<img class="service-img" src="<?php echo get_site_url(); ?>/wp-content/themes/demo/images/tyre_fitting.png"></img>
									<div>Fitment</div>
								</li>
								<li>
									<img class="service-img" src="<?php echo get_site_url(); ?>/wp-content/themes/demo/images/tyre_balancing.jpg"></img>
									<div>Balancing</div>
								</li>
								<li>
									<img class="service-img" src="<?php echo get_site_url(); ?>/wp-content/themes/demo/images/wheel-alignment.png"></img>
									<div>Alignment</div>
								</li>
							</ul>
						</div>
						<?php
					} elseif ($order_prd_id == $service_voucher_prd && $voucher_type == 'service') { ?>
						<h3 class="tyre-name"><?php echo $service_type; ?></h3>

						<div class="customer-name"><i class="fa fa-user"></i><?php echo $first_name.' '.$last_name; ?></div>
						<a class="mobile-no" href="tel:<?php echo $mobile_no; ?>">
							<i class="fa fa-phone"></i>
							<?php echo $mobile_no; ?>
						</a>

						<div class="service-details">
							<strong class="ser-sec-title">Services :</strong>
							<ul class="service-list">
								<li>
									<?php 
									//echo $voucher_name;
									if($voucher_name == 'Wheel alignment & balancing') { 
										echo '<img class="service-img" src="'.get_site_url().'/wp-content/themes/demo/images/service-icon/alignment_balance.png"></img>';
										echo '<div>Wheel Alignment & Balancing</div>';
									}
									elseif ($voucher_name == 'Car Washing') {
										echo '<img class="service-img" src="'.get_site_url().'/wp-content/themes/demo/images/service-icon/carwash.png"></img>';
										echo '<div>Car Washing</div>';
									}
									else {
										
										echo $voucher_name;
									}
									?>
								</li>
							</ul>
						</div>
						<?php
					}
					// service data
					else { ?>
						<h3 class="tyre-name"><?php echo $variation_des; ?> (<?php echo $prd_qty; ?>Tyre)</h3>

						<div class="customer-name"><i class="fa fa-user"></i><?php echo $first_name.' '.$last_name; ?></div>
						<a class="mobile-no" href="tel:<?php echo $mobile_no; ?>">
							<i class="fa fa-phone"></i><?php echo $mobile_no; ?>
						</a>

						<div class="service-details">
						<strong class="ser-sec-title">Services :</strong>
						<ul class="service-list">
						<?php
							$services = "SELECT * 
							FROM th_cart_item_services
							WHERE product_id = '$order_prd_id' and order_id = '$order_id'";
							$row = $wpdb->get_results($services);
							foreach ($row as $key => $service) {
								$service_id = $service->cart_item_services_id;
								$service_name = $service->service_name;
							?>
							<li>
							<?php
							if($service_name == 'Tyre Fitment') { 
								echo '<img class="service-img" src="'.get_site_url().'/wp-content/themes/demo/images/service-icon/tyre-services.png"></img>';
								echo '<div>Fitment</div>';
							}
							elseif($service_name == 'Wheel Balancing') {
								echo '<img class="service-img" src="'.get_site_url().'/wp-content/themes/demo/images/service-icon/balancing.png"></img>';
								echo '<div>Balancing</div>';
							}
							elseif($service_name == 'Wheel alignment') {
								echo '<img class="service-img" src="'.get_site_url().'/wp-content/themes/demo/images/service-icon/alignment.png"></img>';
								echo '<div>Alignment</div>';
							} 
							elseif($service_name == 'Car Washing') {
								echo '<img class="service-img" src="'.get_site_url().'/wp-content/themes/demo/images/service-icon/carwash.png"></img>';
								echo '<div>Car Washing</div>';
							} else {
								echo $service_name;
							}
							?>
							</li>
							<?php
							}
						?> 
					</ul>
			</div>
			<?php } ?>	
			</div>
				<?php if($order_prd_id == $service_voucher_prd) { ?>
					<div class="view-more"><a class="text" href="?voucher_id=<?php echo $service_voucher_id;?>" ><img class="service-img" src="<?php echo get_stylesheet_directory_uri(); ?>/images/service-icon/scan.png"></img> Select</a></div>
					<?php 
				} else { ?>

					<div class="view-more-info"><button id="view-more-info" data-id="<?=$order_id;?>">Inform Client</button></div>

					<div class="view-more"><a class="text" href="?service_id=<?php echo $item_installer;?>" ><img class="service-img" src="<?php echo get_stylesheet_directory_uri(); ?>/images/service-icon/scan.png"> Select</a></div>
				<?php } ?>
			</div>
			<?php }
			}
			}

		}

		if($flog == 0) {
			echo 'No Service Found!';
		}
	}
	else {
		echo 'No Service Found!';
	} 
}else{
	echo 'No Service Found!';
} 
?>

<style>
.single-service .view-more-info {
	position: absolute;
    right: 150px;
    bottom: 10px;
}
.single-service .view-more-info button {
	color: #2F3672;
    font-size: 20px;
    font-weight: 600;
    background-color: #E4C24D;
    border-radius: 10px;
    padding: 10px;
    display: inline-block;
    border: 0px;
    min-height: 52px;
    box-shadow: unset;
}
@media only screen and (min-width: 768px) and (max-width: 1299px) {
	.single-service .service-details {
    	padding-bottom: 60px;
	}
}
@media only screen and (max-width: 767px) {
	.single-service .view-more-info {
		display: inline-block;
		position: relative;
		width: 100%;
		text-align: center;
		right: 0;
		bottom: 0;
		margin-bottom: 10px;
	}
}
</style>
<script type="text/javascript">
	jQuery(document).ready(function(){
			jQuery('body').on('click', '#view-more-info', function(e) {
				e.preventDefault();
				jQuery('#infoConfirm').modal('show');				 
					var order_id = jQuery(this).attr('data-id');
					jQuery('#order_id').val(order_id);


				});
			jQuery('body').on('click', '.yes-inform', function(e) {
				jQuery('#cover-spin').show(); 
					e.preventDefault(); 
					//rowdelete=jQuery(this);   
					var admin_url=  jQuery('.admin_url').text();                     
					var order_id = jQuery('#order_id').val();
					var post_data = {
						order_id: order_id
					};

				   var data = {
						action: "ready_to_install",
						data: JSON.parse(JSON.stringify(post_data))
					};

					jQuery.post(admin_url, data, function(response) {
						jQuery('#infoConfirm').modal('hide');
						jQuery('#cover-spin').hide();
						//location.reload(); 
					});
				

				});
		});
</script>
<!-- Modal -->
<div class="modal fade" id="infoConfirm" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true">
  <div class="modal-dialog" role="document">
    <div class="modal-content">
      <div class="modal-header">
        <h5 class="modal-title" id="infoConfirm">Inform Client</h5>
      
      </div>
      <div class="modal-body">
      	<p>Have you received the Tyre of this client, do you want to inform customer to come and install the Tyre and services?</p>
      	<input type="hidden" name="order_id" id="order_id" value="">      	
      </div>
      <div class="modal-footer">
        <button type="button" class="btn btn-secondary" data-dismiss="modal">Cancel</button>
        <button type="button" class="btn btn-primary yes-inform">Yes</button>
      </div>
    </div>
  </div>
</div>