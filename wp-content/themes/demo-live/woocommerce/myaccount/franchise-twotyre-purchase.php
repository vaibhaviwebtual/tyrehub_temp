<?php
cart_clear_franchise();
if ( !is_user_logged_in() )
 	{
      wp_redirect(get_permalink( get_option('woocommerce_myaccount_page_id') ).'/installer-login' );    
  	}

get_header();
?>
<div id="pageContent">
	<div class="container installer-home">
		
		<div class="woocommerce">
			<div style="text-align: left;" class="search-bar">
				<div class="left">
				</div>
				<div class="right">
					<div class="column">
						<span style="text-align: left;">Width</span>
						<div><input type="text" name="" class="width"></div>
					</div>
					<div class="column">
						<span style="text-align: left;">Ratio</span>
						<div><input type="text" name="" class="ratio"></div>
					</div>
					<div class="column">
						<span style="text-align: left;">Diameter</span>
						<div><input type="text" name="" class="diameter"></div>
					</div>
					<div class="column" style="width: 20%;">
						<span style="text-align: left;">Search</span>
						<div><select class="searchbyname">
								<option selected disabled="" >Select Category</option>
								<option>Apollo</option>
								<option>Bridgestone</option>
								<option>Cavendish</option>
								<option>Ceat</option>
								<option>Falken</option>
								<option>GoodYear</option>
								<option>MRF</option>
								<option>JK</option>
							</select></div>
					</div>
					<div class="column" style="width: 20%;">
						<button class="searchbywidth btn btn-invert"><span>Search</span></button>
					</div>
				</div>
			</div>
			
			<?php /*?><!--<?php
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
				<div class="product-container"> 
					Please search for purchase
				</div>
			</div>--><?php */?>
			
			<div class="procuct_purchase">
				<div class="left_part" style="display: none;">
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
										<a id="byvehicle" class="toggle-btn active">By Vehicle</a>
										<a id="bysize" class="toggle-btn">By Size</a>
									</div>
									<div class="form-tab byvehicle active">
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
													<select class="searchbyname">
														<option value="">Select Category</option>
														<option value="Apollo">Apollo</option>
														<option value="Bridgestone">Bridgestone</option>
														<option value="Cavendish">Cavendish</option>
														<option value="Ceat">Ceat</option>
														<option value="Falken">Falken</option>
														<option value="GoodYear">GoodYear</option>
														<option value="mrf">MRF</option>
														<option value="jk">JK</option>
													</select>
													
												</div>
											</div>
										</form>
										<span class="select-error" style="display: none;">Please select all criteria</span>
										<button class="get-tyre-bymodel store-search">
											<span>Get Tyre Pricing</span>
										</button>
									</div>
									<div class="form-tab bysize">
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
													<select class="searchbyname">
														<option selected disabled="" >Select Category</option>
														<option value="Apollo">Apollo</option>
														<option value="Bridgestone">Bridgestone</option>
														<option value="Cavendish">Cavendish</option>
														<option value="Ceat">Ceat</option>
														<option value="Falken">Falken</option>
														<option value="GoodYear">GoodYear</option>
														<option value="mrf">MRF</option>
														<option value="jk">JK</option>
													</select>
												</div>
											</div>

										</form>
										<span class="select-error-size" style="display: none;">Please select all criteria</span>
										<button class="searchbywidth">
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
						<div class="product-container"> 
							Please search for purchase
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