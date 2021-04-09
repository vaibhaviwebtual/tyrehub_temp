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
	<div class="installer-home franchise_banner">
		<div class="container">
			<div class="woocommerce">
				<div class="procuct_purchase franchise-product-list">
					<div class="left_part">
						<div class="service_filter">
							<div class="title_part">
								<?php /*?><a href="<?php echo get_home_url(); ?>" class="img-sec">
									<img class="normal" src="<?php echo get_stylesheet_directory_uri(); ?>/images/slide-icon/back_arrow.png" />
								</a><?php */?>
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
											<a id="bysize" class="toggle-btn active">By Size</a>
											<a id="byvehicle" class="toggle-btn">By Vehicle</a>

										</div>
										<div class="form-tab byvehicle">
											<form id="tab1-form">
												<div class="form-group">
													<div class="select-wrapper">
														<select name="select-car-cmp" class="input-custom select-car-cmp" required>
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
															<option value="<?php echo $make_id; ?>"><?php echo $make_name; ?></option>
														<?php } ?>
														</select>
													</div>
												</div>
												<div class="form-group">
													<div class="select-wrapper year-wrapper" >
														<select disabled="disabled" name="select1" class="input-custom select-model" required>
															<option value="" selected="">Model</option>
														</select>
													</div>
												</div>
												<div class="form-group">
													<div class="select-wrapper model-wrapper">
														<select name="select3" disabled="disabled" class="input-custom select-sub-model" required>
															<option value="" disabled selected>Sub Model</option>
														</select>
													</div>
												</div>

												<div class="form-group">
													<div class="select-wrapper">


														<select name="vehicle_type_model" id="vehicle_type_model"  class="input-custom">
															<option value="">Select Vehicle Type</option>
															<?php
															global $wpdb;
															$row = $wpdb->get_results("SELECT * FROM th_vehicle_type WHERE wheel_type = '2'");
															foreach ($row as $data) {
															?>
																<option value="<?php echo $data->vehicle_id ?>"><?php echo $data->vehicle_type;?></option>
															<?php } ?>
														</select>

													</div>
												</div>
											</form>
											<button class="offline-customer-bymodel">
												<span>Get Tyre Pricing</span>
											</button>
										</div>
										<div class="form-tab bysize active">
											<form id="tab1-form">
												<div class="form-group">
													<div class="select-wrapper">
														<select name="select1" class="input-custom select-width">
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
														<select name="select1" disabled="disabled" class="input-custom select-ratio" required>
															<option value="" selected="" disabled="">Ratio/Profile</option>
														</select>
													</div>
												</div>
												<div class="form-group">
													<div class="select-wrapper diameter-wrapper">
														<select name="select1" disabled="disabled" class="input-custom select-diameter" required>
															<option value="" disabled="" selected="">Rim Diameter</option>
														</select>
													</div>
												</div>
												<div class="form-group">
													<div class="select-wrapper">
														<select name="vehicle_type_width" id="vehicle_type_width"  class="input-custom">
															<option value="">Select Vehicle Type</option>
															<?php
															global $wpdb;
															$row = $wpdb->get_results("SELECT * FROM th_vehicle_type WHERE wheel_type = '2'");
															foreach ($row as $data) {
															?>
																<option value="<?php echo $data->vehicle_id ?>"><?php echo $data->vehicle_type;?></option>
															<?php } ?>
														</select>
													</div>
												</div>

											</form>

											<button class="offline-customer-width">
												<span>Get Tyre Pricing</span>
											</button>
										</div>
									</div>
								</div>	
							</div>
						</div>
					</div>
					<div class="right_part">
						<?php
						if ( ! defined( 'ABSPATH' ) ) {
							exit;
						}
						wc_print_notices();

						$user = wp_get_current_user();
						$role = ( array ) $user->roles;
						$current_user_role = $role[0];
						if($current_user_role != 'Installer') {
							do_action( 'woocommerce_account_navigation' ); 
						} 
						//do_action( 'woocommerce_account_content' ); ?>
						<div class="woocommerce-MyAccount-content <?php if($current_user_role == 'Installer'){echo 'installer-account';} ?>">
							<div class="product-container franchise-product-list"> 
								
							</div>
						</div>
					</div>
				</div>
			</div>
		</div>
	</div>
</div>
<?php
get_footer();
?>