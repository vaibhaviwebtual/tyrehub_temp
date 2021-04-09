<!DOCTYPE html>
<html lang="en" <?php language_attributes(); ?> >
	<head>
		<meta charset="utf-8">
		<meta name="google-site-verification" content="6rFLewnvCnGq3Ro0-sa5nxH6BKBYIeqDYVOr_2oSuJU" />
		<meta charset="<?php bloginfo( 'charset' ); ?>">
		<meta http-equiv="X-UA-Compatible" content="IE=edge">
		<meta name="viewport" content="width=device-width, initial-scale=1.0">
		<meta name="description" content="TyreHub is the one stop solution to buy Car Tyres, Bike and Activa tyres online with the best price offers in Ahmedabad and get them delivered for free at your doorstep">
		<meta name="author" content="www.tyrehub.com">
		<meta name="format-detection" content="telephone=no">
		<meta http-equiv='cache-control' content='no-cache'>
		<meta http-equiv='expires' content='0'>
		<meta http-equiv='pragma' content='no-cache'>
		<link rel="icon" type="image/png" href="<?php echo get_site_url(); ?>/favicon-32x32.png" sizes="32x32" />
		<?php 
		$actual_link = "http://$_SERVER[HTTP_HOST]$_SERVER[REQUEST_URI]";
		if (strpos($actual_link, 'page/') !== false || strpos($actual_link, '/') !== false) {
		    echo '<meta name="robots" content="noindex,nofollow">';
		}
		?>
		<?php wp_head(); ?>
		<!-- Bootstrap core CSS -->
		
		<link href="<?php echo bloginfo('template_directory').'/assest/css/plugins/bootstrap-submenu.css'; ?>?ver=<?=rand(111,999);?>" rel="stylesheet">
		<link href="<?php echo bloginfo('template_directory').'/assest/css/plugins/animate.min.css'; ?>?ver=<?=rand(111,999);?>" rel="stylesheet">
		<link href="<?php echo bloginfo('template_directory').'/assest/css/plugins/slick.css'; ?>?ver=<?=rand(111,999);?>" rel="stylesheet">
		<link href="<?php echo bloginfo('template_directory').'/assest/css/wp-default-norms.css'; ?>?ver=<?=rand(111,999);?>" rel="stylesheet">  
		
		<link href="<?php echo bloginfo('template_directory').'/assest/css/font-awesome.css'; ?>?ver=<?=rand(111,999);?>" rel="stylesheet">        
    	<link href="<?php echo bloginfo('template_directory').'/assest/css/bootstrap-datetimepicker.min.css'; ?>?ver=<?=rand(111,999);?>" rel="stylesheet">
    	<link href="<?php echo bloginfo('template_directory').'/assest/css/internal.css'; ?>?ver=<?=rand(111,999);?>" rel="stylesheet">
    	
		
		<link href="<?php echo bloginfo('template_directory').'/assest/iconfont/style.css'?>?ver=<?=rand(111,999);?>" rel="stylesheet">
		
		<link href="<?php echo bloginfo('template_directory').'/assest/css/plugins/bootstrap.min.css'; ?>?ver=<?=rand(111,999);?>" rel="stylesheet">
		
		<link href="<?php echo bloginfo('template_directory').'/assest/css/shop.css'; ?>?ver=<?=rand(111,999);?>" rel="stylesheet">
		
		<link href="<?php echo bloginfo('template_directory');?>/style.css?ver=<?=rand(111,999);?>" rel="stylesheet" />
		<link href="<?php echo bloginfo('template_directory').'/assest/css/custom_tyrehub.css?ver='.rand(111,999)?>" rel="stylesheet">
		<link href="<?php echo bloginfo('template_directory').'/assest/css/animate.min.css'; ?>?ver=<?=rand(111,999);?>" rel="stylesheet">  
		<meta http-equiv="X-UA-Compatible" content="IE=EmulateIE9">
	  	<!--[if IE]>
			This content is ignored in Internet Explorer 10 and other browsers.
			In older versions of Internet Explorer, it renders as part of the page.
	  	<![endif]-->
</head>
<?php
$user = wp_get_current_user();
 $role = $user->roles[0];
$myaccount_page_id = get_option( 'woocommerce_myaccount_page_id' );
if ( is_front_page() && $role == 'Installer')
{
     		global $wpdb;
			$user_id = get_current_user_id(); 
			$SQL="SELECT * FROM th_installer_data WHERE user_id='".$user_id."' AND is_franchise='yes'";
			$franchise=$wpdb->get_row($SQL);
			if($franchise){
				wp_redirect(get_permalink( $myaccount_page_id ).'/franchise-home/');
			}else{
				wp_redirect(get_permalink( $myaccount_page_id ).'/service-request/');
			}
           //wp_redirect(get_permalink( $myaccount_page_id ).'/purchase');
}
?>
<?php
if(isset($_GET['searchby'])){ 
	session_start();
	$actual_link = (isset($_SERVER['HTTPS']) && $_SERVER['HTTPS'] === 'on' ? "https" : "http") . "://$_SERVER[HTTP_HOST]$_SERVER[REQUEST_URI]";
	$_SESSION['shopURL'] = $actual_link;
}
global $post;
//echo $post->ID;
if($post->ID==11){ 
	session_start();
	$actual_link = (isset($_SERVER['HTTPS']) && $_SERVER['HTTPS'] === 'on' ? "https" : "http") . "://$_SERVER[HTTP_HOST]$_SERVER[REQUEST_URI]";
	$_SESSION['service-partner'] = $actual_link;
}
if($post->ID==26){ 
	session_start();
	$actual_link = (isset($_SERVER['HTTPS']) && $_SERVER['HTTPS'] === 'on' ? "https" : "http") . "://$_SERVER[HTTP_HOST]$_SERVER[REQUEST_URI]";
	$_SESSION['cartpage'] = $actual_link;
}
if($post->ID==27){ 
	session_start();
	$actual_link = (isset($_SERVER['HTTPS']) && $_SERVER['HTTPS'] === 'on' ? "https" : "http") . "://$_SERVER[HTTP_HOST]$_SERVER[REQUEST_URI]";
	$_SESSION['checkout'] = $actual_link;
}
if(isset($_GET['vehicle_type'])){ 
	session_start();
	$_SESSION['vehicle_type'] = $_GET['vehicle_type'];
}
?>
	<div id="overlay">&nbsp;</div>
	<div id="cover-spin"></div>
	<!-- <div style="background-color: #ff1414;padding: 8px 15px;color: #fff;text-align: center;font-weight: 600;"tyle="background-color: #ff1414;padding: 8px 10px;color: #fff;text-align: center;"><marquee>Dear valuable customer, please take a note… All Service Center will be closed on 14th and 15th Jan 2020 due to Uttarayan, They will reopen on 16th Jan 2020.</marquee></div> -->
	<header class="page-header">
		<div class="navbar" id="slide-nav">
			<div class="container">
				<div class="row">
					<div class="admin_url" hidden=""><?php echo admin_url('admin-ajax.php');?></div>
					<div class="header-row">
						<div class="logo">
							<a href="<?php echo get_site_url(); ?>"><img src="<?php echo get_option("head_logo"); ?>" alt="Logo"></a>
						</div>
						<div class="header-right">
							<div class="header-right-top">
								<button type="button" class="navbar-toggle"><i class="icon icon-lines-menu"></i></button>
								<div class="address">
									<span>
									<a href="mailto:info@tyrehub.com"><?php echo get_option("head_email"); ?></a>
									</span> 
									<div class="social-links">
										<ul>
											<li>
												<a class="icon icon-facebook-logo" target="_blank" href="<?php echo get_option("facebook"); ?>"></a>
												</li>
											<li>
												<a class="icon icon-twitter-logo" target="_blank" href="<?php echo get_option("twitter"); ?>"></a>
											</li>
											<li>
												<a class="icon icon-instagram-logo" target="_blank" href="<?php echo get_option("instagram"); ?>"></a>
											</li>
											<li>
												<a class="icon icon-linkedin-logo" target="_blank" href="<?php echo get_option("linkin"); ?>"></a>
											</li>
										</ul>
									</div>
								</div>
								<div class="head-cart-for-mobile">
									<span class="badge cart-contents-count">
										<?php 
										global $wpdb , $woocommerce;
										$sku = 'service_voucher';                
											$service_prd_id = $wpdb->get_var( $wpdb->prepare( "SELECT post_id FROM $wpdb->postmeta WHERE meta_key='_sku' AND meta_value='%s' LIMIT 1", $sku ) );
											$current_user = get_current_user_id();
											$session_id = '';
											$session_id = WC()->session->get_customer_id();				
											$cart_total = WC()->cart->get_cart_contents_count();
											$destination = [];
											$voucher_in_cart = 0;
											foreach ( WC()->cart->get_cart() as $cart_item_key => $cart_item ) 
											{           
												$product_id = $cart_item['product_id'];
												$services = "SELECT * 
															FROM th_cart_item_installer
															WHERE cart_item_key = '$cart_item_key' and session_id = '$session_id'
															and order_id = ''";
															 $row = $wpdb->get_results($services);
												foreach ($row as $data)
												{
													if($data->destination == 1)
													{       
														$destination[] = $data->destination;
													}
												}
												if($product_id == $service_prd_id)
												{
													$voucher_in_cart = 1;
													$voucher_info = "SELECT * 
																	FROM th_cart_item_service_voucher
																	WHERE product_id = '$service_prd_id' and session_id = '$session_id' and order_id = ''";
													$row = $wpdb->get_results($voucher_info);
												   // var_dump(count($row));
													$service_voucher_count = count($row);
												}
											}
											$service_total = count($destination);
											echo $cart_count = $cart_total + $service_total - $voucher_in_cart + $service_voucher_count;
									?>
									</span>
									<a href="<?php echo wc_get_cart_url(); ?>" class="fa fa-shopping-cart"></a>
								</div>		
								<div class="appointment" ><a href="tel:18002335551" target="_blank"><i class="fa fa-phone" aria-hidden="true" style=" margin-right: 8px;font-size: 27px;"></i><span>1800-233-5551</span></a></div>
							</div>
							<div class="logo mobile_logo">
								<a href="<?php echo get_site_url(); ?>"><img src="<?php echo get_option("head_logo"); ?>" alt="Logo"></a>
							</div>
							<div class="header-right-bottom">
								<div class="login-container">
									<?php if($role != 'Installer'){
										if($_SESSION['current_pincode']==''){
											  $_SESSION['current_pincode'] ='380001';
											}
											if(isset($_SESSION['current_pincode']))
												{
													$pincode = $_SESSION['current_pincode'];
													$url ='https://maps.googleapis.com/maps/api/geocode/json?address=' . urlencode($pincode) . '&sensor=true&key='.GOOGLE_API_KEY.'&libraries';
													 $ch = curl_init();
														curl_setopt( $ch, CURLOPT_URL, $url );
														curl_setopt( $ch, CURLOPT_RETURNTRANSFER, true );
														$response = curl_exec( $ch );
													   $coordinates = json_decode($response);
													 //echo $add = $coordinates->results[0]->formatted_address;
														$cityname=explode(',',$add);
														if($cityname[0]!=''){
														$_SESSION['current_city']=$cityname[0];
														}else{
														unset($_SESSION['current_city']);
														}
														if($cityname[1]!=''){
															$_SESSION['current_state']=$cityname[1];
														}else{
															unset($_SESSION['current_state']);
														}
												} else{
													//echo 'Select Your Location';
												}

									?>
									<?php /*?><button class="header-current-location" data-toggle="modal" data-target="#current_pincode">
										<i class="fa fa-map-marker" aria-hidden="true"></i>&nbsp;<span>
											<?php 
											if($_SESSION['current_pincode']==''){
											  $_SESSION['current_pincode'] ='380001';
											}
												if(isset($_SESSION['current_pincode']))
												{
													$pincode = $_SESSION['current_pincode'];
													$url ='https://maps.googleapis.com/maps/api/geocode/json?address=' . urlencode($pincode) . '&sensor=true&key=AIzaSyCmMCGIlJKPcPn_hjlgo6j-RMfN4ZtOH70&libraries';
													 $ch = curl_init();
														curl_setopt( $ch, CURLOPT_URL, $url );
														curl_setopt( $ch, CURLOPT_RETURNTRANSFER, true );
														$response = curl_exec( $ch );
													   $coordinates = json_decode($response);
													 echo $add = $coordinates->results[0]->formatted_address;
														$cityname=explode(',',$add);
														if($cityname[0]!=''){
														$_SESSION['current_city']=$cityname[0];
														}else{
														unset($_SESSION['current_city']);
														}
														if($cityname[1]!=''){
															$_SESSION['current_state']=$cityname[1];
														}else{
															unset($_SESSION['current_state']);
														}
												} else{
													echo 'Select Your Location';} 
											?>
										</span>
									</button><?php */?>
								<?php } ?>
									<!-- <a href="<?php //echo get_site_url(); ?>/tracking-process/ "><i class="fa fa-location-arrow" aria-hidden="true"></i>&nbsp;Track Your Order</a> -->
							<?php 
									if(is_user_logged_in())
									{
										$user = wp_get_current_user();
										//var_dump($user);
										$name = $user->display_name;
							?>		
									<a href="<?php echo get_permalink( get_option('woocommerce_myaccount_page_id') ); ?>" title="<?php _e('My Account',''); ?>"><i class="fa fa-user-circle-o" aria-hidden="true"></i>&nbsp;<?php echo $name; ?></a>
									<a href="<?php echo wp_logout_url( get_permalink( woocommerce_get_page_id( 'myaccount' ) ) ); ?>"><i class="fa fa-sign-out" aria-hidden="true"></i>&nbsp;Log Out</a> 
							<?php
									}else{													
							?>
									<span class="login">
										<i class="fa fa-user" aria-hidden="true"></i>
										<a href="<?php echo get_permalink( get_option('woocommerce_myaccount_page_id') ); ?>/registration/" title="<?php _e('My Account',''); ?>">Create account</a>
										<i class="fa fa-sign-in" aria-hidden="true"></i>&nbsp;<a href="<?php echo get_permalink( get_option('woocommerce_myaccount_page_id') ); ?>" title="<?php _e('My Account',''); ?>">Sign in</a>
									</span>
									<?php } ?>
								</div>
							</div>
						</div>
					</div>
					<div id="slidemenu">
						<div class="menu_part">
							<div class="close-menu">Menu <i class="icon-close-cross"></i></div>
							<?php 
							if( is_user_logged_in() ) {
								$user = wp_get_current_user();
								$role = ( array ) $user->roles[0];
								$role = ( array ) $user->roles;
								$current_user_role = $role[0];
								if($current_user_role == 'Installer') {
							?> 


								<?php 
									$link = $_SERVER['REQUEST_URI'];
									$link_array = explode('/',$link);
									$numSegments = count($link_array); 
									if($numSegments >= 5){
										$installer_page = $link_array[$numSegments - 3];
									}
									else
									{
										$installer_page = $link_array[$numSegments - 2];
									}
									

									//echo "<pre>";
									

									global $wpdb;
									$user_id = get_current_user_id(); 
									$SQL="SELECT * FROM th_installer_data WHERE user_id='".$user_id."' AND is_franchise='yes'";
									$franchise=$wpdb->get_row($SQL);
								?>
								<nav class="menu main">		    		
									<div class="site-menu">

										<?php 
										if($franchise){
											include('menu/franchise.php');
										}else{
											include('menu/installer.php');	
										}
										?>
										<!-- <ul id="menu-primary" class="menu">
											<li class="<?php if($installer_page == 'franchise-home') { echo 'current-menu-item'; } ?>">
												<a href="<?php echo get_home_url()?>/my-account/franchise-home/">
													<img src="<?php echo get_stylesheet_directory_uri(); ?>/images/icon/installer/admin/home.png" alt="" />
													<span>Home</span>
												</a>
											</li>

											<li class="menu-item parent-menu">
												<a href="#">
													<img src="<?php echo get_stylesheet_directory_uri(); ?>/images/icon/installer/admin/buy_tyre.png" alt="" />
													<span>Buy Tyre</span>
												</a>
												<ul class="sub-menu">
													<li class="menu-item <?php if($cr_page == 'offline-cartyre-purchase'){ echo 'current-menu-item';} ?>">
														<a href="<?php echo get_home_url()?>/my-account/offline-cartyre-purchase/">
															<img src="<?php echo get_stylesheet_directory_uri(); ?>/images/icon/installer/admin/tyre.png" alt="" />
															<span>Buy Car Tyre</span>
														</a>
													</li>
													<li class="menu-item <?php if($cr_page == 'offline-twotyre-purchase'){ echo 'current-menu-item';} ?>">
														<a href="<?php echo get_home_url()?>/my-account/offline-twotyre-purchase/">
															<img src="<?php echo get_stylesheet_directory_uri(); ?>/images/icon/installer/admin/two-three.png" alt="" />
															<span class="fr-tyre">Buy Two - Three Wheeler Tyre</span>
														</a>
													</li>
												</ul>
											</li>

											<li class="menu-item parent-menu">
												<a href="#">
													<img src="<?php echo get_stylesheet_directory_uri(); ?>/images/icon/installer/admin/car_service.png" alt="" />
													<span>Buy Services</span>
												</a>
												<ul class="sub-menu">
													<li class="menu-item <?php if($cr_page == 'offline-alignment-balancing'){ echo 'current-menu-item';} ?>">
														<a href="<?php echo get_home_url()?>/my-account/offline-alignment-balancing/">
															<img src="<?php echo get_stylesheet_directory_uri(); ?>/images/icon/installer/admin/wheel_alignment.png" alt="" />
															<span class="fr-tyre">Alignment & Balancing</span>
														</a>
													</li>
													<li class="menu-item <?php if($cr_page == 'offline-car-wash'){ echo 'current-menu-item';} ?>">
														<a href="<?php echo get_home_url()?>/my-account/offline-car-wash/">
															<img src="<?php echo get_stylesheet_directory_uri(); ?>/images/icon/installer/admin/car_wash.png" alt="" />
															<span>Car Wash</span>
														</a>
													</li>
												</ul>
											</li>

											<li class="<?php if($installer_page == 'service-request') { echo 'current-menu-item'; } ?>">
												<a href="<?php echo get_home_url()?>/my-account/service-request/">
													<img src="<?php echo get_stylesheet_directory_uri(); ?>/images/icon/installer/service_request.png" alt="" />
													<span>Service Request</span>
												</a>
											</li>

											<?php /*?><li class="<?php if($installer_page == 'purchase') { echo 'current-menu-item'; } ?>">
												<a href="<?php echo get_home_url()?>/my-account/purchase/">
													<img src="<?php echo get_stylesheet_directory_uri(); ?>/images/icon/installer/purchase.png" alt="" />
													<span>Purchase</span>
												</a>
											</li><?php */?>

											<li class="<?php if($installer_page == 'orders') { echo 'current-menu-item'; } ?>">
												<a href="<?php echo get_home_url()?>/my-account/orders/">
													<img src="<?php echo get_stylesheet_directory_uri(); ?>/images/icon/installer/order_tracking.png" alt="" />
													<span>Order Tracking</span>
												</a>
											</li>
											<li class="<?php if($installer_page == 'customer-register') { echo 'current-menu-item'; } ?>">
												<a href="<?php echo get_home_url()?>/my-account/customer-register/">
													<img src="<?php echo get_stylesheet_directory_uri(); ?>/images/icon/installer/customer_register.png" alt="" />
													<span>Customer Register</span>
												</a>
											</li>
											<li class="<?php if($installer_page == 'edit-account'){ echo 'current-menu-item';} ?>">
												<a href="<?php echo get_home_url()?>/my-account/edit-account/">
													<img src="<?php echo get_stylesheet_directory_uri(); ?>/images/icon/installer/settings.png" alt="" />
													<span>Settings</span>
												</a>
											</li>
											<li class="menu-item parent-menu">
												<a href="#">
													<img src="<?php echo get_stylesheet_directory_uri(); ?>/images/icon/installer/admin_access.png" alt="" />
													<span>Admin Access</span>
												</a>
												<ul class="sub-menu">
													<li class="menu-item <?php if($cr_page == 'franchise-cartyre-purchase'){ echo 'current-menu-item';} ?>">
														<a href="<?php echo get_home_url()?>/my-account/franchise-cartyre-purchase/">
															<img src="<?php echo get_stylesheet_directory_uri(); ?>/images/icon/installer/admin/tyre.png" alt="" />
															<span>Buy Car Tyre</span>
														</a>
													</li>
													<li class="menu-item <?php if($cr_page == 'franchise-twotyre-purchase'){ echo 'current-menu-item';} ?>">
														<a href="<?php echo get_home_url()?>/my-account/franchise-twotyre-purchase/">
															<img src="<?php echo get_stylesheet_directory_uri(); ?>/images/icon/installer/admin/two-three.png" alt="" />
															<span class="fr-tyre">Buy Two - Three Wheeler Tyre</span>
														</a>
													</li>
													<li class="menu-item <?php if($cr_page == 'orders'){ echo 'current-menu-item';} ?>">
														<a href="<?php echo get_home_url()?>/my-account/orders/">
															<img src="<?php echo get_stylesheet_directory_uri(); ?>/images/icon/installer/admin/order_tracking.png" alt="" />
															<span>Order Tracking</span>
														</a>
													</li>
													<li class="menu-item <?php if($cr_page == 'my-account'){ echo 'current-menu-item';} ?>">
														<a href="<?php echo get_home_url()?>/my-account/">
															<img src="<?php echo get_stylesheet_directory_uri(); ?>/images/icon/installer/admin/my_account.png" alt="" />
															<span>My Account</span>
														</a>
													</li>
												</ul>
											</li>

											<li>
												<a href="<?php echo wp_logout_url( get_permalink( woocommerce_get_page_id( 'myaccount' ) ) ); ?>">
													<img src="<?php echo get_stylesheet_directory_uri(); ?>/images/icon/installer/logout.png" alt="" />
													<span>Logout</span>
												</a>
											</li>
										</ul> -->
									</div>
								</nav>

								<?php
								} else {
								?>
								<?php 
									$link = $_SERVER['REQUEST_URI'];
									$link_array = explode('/',$link);
									$numSegments = count($link_array); 
									$cr_page = $link_array[$numSegments - 2];
								?>
								<nav class="menu main">		    		
									<div class="site-menu">
										<ul id="menu-primary" class="menu">
											<li class="menu-item <?php if($cr_page == 'buy-car-tyre'){ echo 'current-menu-item';} ?>">
												<a href="<?php echo get_home_url()?>/buy-car-tyre/">
													<img src="<?php echo get_stylesheet_directory_uri(); ?>/images/icon/menu/car_tyre.png" alt="" />
													<span>Buy Car Tyre</span>
												</a>
											</li>
											<li class="menu-item <?php if($cr_page == 'two-wheeler-tyres'){ echo 'current-menu-item';} ?>">
												<a href="<?php echo get_home_url()?>/two-wheeler-tyres/">
													<img src="<?php echo get_stylesheet_directory_uri(); ?>/images/icon/menu/buy_two.png" alt="" />
													<span>Two - Three Wheeler</span>
												</a>
											</li>
											<li class="menu-item <?php if($cr_page == 'balancing-alignment'){ echo 'current-menu-item';} ?>">
												<a href="<?php echo get_home_url()?>/balancing-alignment/">
													<img src="<?php echo get_stylesheet_directory_uri(); ?>/images/icon/menu/wheel_alignment.png" alt="" />
													<span>Alignment & Balancing</span>
												</a>
											</li>
											<li class="menu-item parent-menu">
												<a href="#">
													<img src="<?php echo get_stylesheet_directory_uri(); ?>/images/icon/menu/car_service.png" alt="" />
													<span>Car Services</span>
												</a>
												<ul class="sub-menu">
													<li class="menu-item <?php if($cr_page == 'free-tyre-inspection'){ echo 'current-menu-item';} ?>">
														<a href="<?php echo get_home_url()?>/free-tyre-inspection/">
															<img src="<?php echo get_stylesheet_directory_uri(); ?>/images/icon/menu/tyre_inspection.png" alt="" />
															<span class="fr-tyre">Free Car<br/>Tyre Inspection</span>
														</a>
													</li>
													<li class="menu-item <?php if($cr_page == 'car-wash'){ echo 'current-menu-item';} ?>">
														<a href="<?php echo get_home_url()?>/car-wash/">
															<img src="<?php echo get_stylesheet_directory_uri(); ?>/images/icon/menu/car_wash.png" alt="" />
															<span>Car Wash</span>
														</a>
													</li>
													<li class="menu-item <?php if($cr_page == 'flat-tyre'){ echo 'current-menu-item';} ?>">
														<a href="<?php echo get_home_url()?>/flat-tyre/">
															<img src="<?php echo get_stylesheet_directory_uri(); ?>/images/icon/menu/flat_tyre.png" alt="" />
															<span class="fr-tyre">Car Flat Tyre /<br/>Jump Start</span>
														</a>
													</li>
													<li class="menu-item <?php if($cr_page == 'towing-services'){ echo 'current-menu-item';} ?>">
														<a href="<?php echo get_home_url()?>/towing-services/">
															<img src="<?php echo get_stylesheet_directory_uri(); ?>/images/icon/menu/towing_services.png" alt="" />
															<span>Towing Services</span>
														</a>
													</li>
												</ul>
											</li>
											<li class="menu-item <?php if($cr_page == 'franchise-opportunity'){ echo 'current-menu-item';} ?>">
												<a href="<?=site_url('/franchise-opportunity/');?>">
													<img src="<?php echo get_stylesheet_directory_uri(); ?>/images/icon/menu/franchise.png" alt="" />
													<span>Franchise Opportunity</span>
												</a>
											</li>
											<li class="menu-item <?php if($cr_page == 'faq'){ echo 'current-menu-item';} ?>">
												<a href="<?=site_url('/faq/');?>">
													<img src="<?php echo get_stylesheet_directory_uri(); ?>/images/icon/menu/faq.png" alt="" />
													<span>FAQ</span>
												</a>
											</li>
											<li class="menu-item <?php if($cr_page == 'contact-us'){ echo 'current-menu-item';} ?>">
												<a href="<?=site_url('/contact-us/');?>">
													<img src="<?php echo get_stylesheet_directory_uri(); ?>/images/icon/menu/contact.png" alt="" />
													<span>Contact Us</span>
												</a>
											</li>
										</ul>
									</div>
								</nav>
							<?php
								}
							} else { ?> 

							<?php 
								$link = $_SERVER['REQUEST_URI'];
								$link_array = explode('/',$link);
								$numSegments = count($link_array); 
								$cr_page = $link_array[$numSegments - 2];
							?>
							<nav class="menu main">
								<div class="site-menu">
									<ul id="menu-primary" class="menu">
										<li class="menu-item <?php if($cr_page == 'buy-car-tyre'){ echo 'current-menu-item';} ?>">
											<a href="<?php echo get_home_url()?>/buy-car-tyre/">
												<img src="<?php echo get_stylesheet_directory_uri(); ?>/images/icon/menu/car_tyre.png" alt="" />
												<span>Buy Car Tyre</span>
											</a>
										</li>
										<li class="menu-item <?php if($cr_page == 'two-wheeler-tyres'){ echo 'current-menu-item';} ?>">
											<a href="<?php echo get_home_url()?>/two-wheeler-tyres/">
												<img src="<?php echo get_stylesheet_directory_uri(); ?>/images/icon/menu/buy_two.png" alt="" />
												<span>Two - Three Wheeler</span>
											</a>
										</li>
										<li class="menu-item <?php if($cr_page == 'balancing-alignment'){ echo 'current-menu-item';} ?>">
											<a href="<?php echo get_home_url()?>/balancing-alignment/">
												<img src="<?php echo get_stylesheet_directory_uri(); ?>/images/icon/menu/wheel_alignment.png" alt="" />
												<span>Alignment & Balancing</span>
											</a>
										</li>
										<li class="parent-menu">
											<a href="#">
												<img src="<?php echo get_stylesheet_directory_uri(); ?>/images/icon/menu/car_service.png" alt="" />
												<span>Car Services</span>
											</a>
											<ul class="sub-menu">
												<li class="menu-item <?php if($cr_page == 'free-tyre-inspection'){ echo 'current-menu-item';} ?>">
													<a href="<?php echo get_home_url()?>/free-tyre-inspection/">
														<img src="<?php echo get_stylesheet_directory_uri(); ?>/images/icon/menu/tyre_inspection.png" alt="" />
														<span class="fr-tyre">Free Car<br/>Tyre Inspection</span>
													</a>
												</li>
												<li class="menu-item <?php if($cr_page == 'car-wash'){ echo 'current-menu-item';} ?>">
													<a href="<?php echo get_home_url()?>/car-wash/">
														<img src="<?php echo get_stylesheet_directory_uri(); ?>/images/icon/menu/car_wash.png" alt="" />
														<span>Car Wash</span>
													</a>
												</li>
												<li class="menu-item <?php if($cr_page == 'flat-tyre'){ echo 'current-menu-item';} ?>">
													<a href="<?php echo get_home_url()?>/flat-tyre/">
														<img src="<?php echo get_stylesheet_directory_uri(); ?>/images/icon/menu/flat_tyre.png" alt="" />
														<span class="fr-tyre">Car Flat Tyre /<br/>Jump Start</span>
													</a>
												</li>
												<li class="menu-item <?php if($cr_page == 'towing-services'){ echo 'current-menu-item';} ?>">
													<a href="<?php echo get_home_url()?>/towing-services/">
														<img src="<?php echo get_stylesheet_directory_uri(); ?>/images/icon/menu/towing_services.png" alt="" />
														<span>Towing Services</span>
													</a>
												</li>
											</ul>
										</li>
										<li class="menu-item <?php if($cr_page == 'franchise-opportunity'){ echo 'current-menu-item';} ?>">
											<a href="<?=site_url('/franchise-opportunity/');?>">
												<img src="<?php echo get_stylesheet_directory_uri(); ?>/images/icon/menu/franchise.png" alt="" />
												<span>Franchise Opportunity</span>
											</a>
										</li>
										<li class="menu-item <?php if($cr_page == 'faq'){ echo 'current-menu-item';} ?>">
											<a href="<?=site_url('/faq/');?>">
												<img src="<?php echo get_stylesheet_directory_uri(); ?>/images/icon/menu/faq.png" alt="" />
												<span>FAQ</span>
											</a>
										</li>
										<li class="menu-item <?php if($cr_page == 'contact-us'){ echo 'current-menu-item';} ?>">
											<a href="<?=site_url('/contact-us/');?>">
												<img src="<?php echo get_stylesheet_directory_uri(); ?>/images/icon/menu/contact.png" alt="" />
												<span>Contact Us</span>
											</a>
										</li>
									</ul>
								</div>
							</nav>
							<?php } ?>
						</div>
						<?php  if(current_user_can('supplier') || current_user_can('btobpartner')) { ?>
							<div class="search-section">
								<?php  if(current_user_can('supplier')) { ?>
								<div class="dropdown supplier-menu">
									<button class="dropdown-toggle" type="button" data-toggle="dropdown"><i class="fa fa-cog"></i></button>
									<ul class="dropdown-menu">
									  <li><a href="<?php echo get_home_url() ?>/my-account/">My account</a></li>
									  <li><a href="<?php echo get_home_url() ?>/my-account/tyre-products/">Product Price</a></li>
									  <li><a href="<?php echo get_home_url() ?>/my-account/deals-discount/">Deals & Discount</a></li>
									  <li><a href="<?php echo get_home_url() ?>/my-account/edit-account/">Settings</a></li>
									  <li><a href="<?php echo esc_url( wc_logout_url( wc_get_page_permalink( 'myaccount' ) ) );?>">Logout</a></li>
									</ul>
								</div>
							<?php } ?>
							<?php  if(current_user_can('btobpartner')){ ?>
								<div class="dropdown supplier-menu">
									<button class="dropdown-toggle" type="button" data-toggle="dropdown"><i class="fa fa-cog"></i></button>
									<ul class="dropdown-menu">
									  <li><a href="<?php echo get_home_url() ?>/my-account/">My account</a></li>
									  <li><a href="<?php echo get_home_url() ?>/my-account/orders/">Order Tracking</a></li>
									  <li><a href="<?php echo get_home_url() ?>/my-account/edit-address/">Addresses</a></li>
									  <li><a href="<?php echo get_home_url() ?>/my-account/edit-account/">Settings</a></li>
									  <li><a href="<?php echo esc_url( wc_logout_url( wc_get_page_permalink( 'myaccount' ) ) );?>">Logout</a></li>
									</ul>
								</div>
						<?php }?>
						</div>
						<?php } ?>
						<div class="cart_part">
							<div class="head-cart">
								<span class="badge cart-contents-count">
									<?php echo $cart_count; ?>
								</span>
								<a href="<?php echo wc_get_cart_url(); ?>" class="fa fa-shopping-cart"></a>
							</div>
						</div>
					</div>
				</div>
			</div>
		</div>
		</header>
	<body <?php body_class(); ?>>
<!-- Google Tag Manager (noscript) -->
<noscript><iframe src="https://www.googletagmanager.com/ns.html?id=GTM-PH2PRDX"
height="0" width="0" style="display:none;visibility:hidden"></iframe></noscript>
<!-- End Google Tag Manager (noscript) -->

<!-- Loader -->
<div id="loader-wrapper">
	<div class="loader">
		<div class="line"></div>
		<div class="line"></div>
		<div class="line"></div>
		<div class="line"></div>
		<div class="line"></div>
		<div class="line"></div>
		<div class="subline"></div>
		<div class="subline"></div>
		<div class="subline"></div>
		<div class="subline"></div>
		<div class="subline"></div>
		<div class="loader-circle-1">
			<div class="loader-circle-2"></div>
		</div>
		<div class="needle"></div>
		<div class="loading">Loading</div>
	</div>
</div>
		
<!-- Modal -->
<div id="adminAccess" class="admin_access_model modal fade" role="dialog">
  <div class="modal-dialog modal-sm">
    <!-- Modal content-->
    <div class="modal-content">
      <div class="modal-header">
        <button type="button" class="close" data-dismiss="modal">&times;</button>
        <h4 class="modal-title">Enter Verification Code</h4>
      </div>
      <div class="modal-body">
        <h3>Enter OTP</h3>
        <p>We Have Sent OTP Your Register Mobile Number</p>
		<div class="otp_send_part">
			<input type="text" name="admin_verify_otp" id="admin_verify_otp" value="" maxlength="6" placeholder="Enter OTP">
			<button type="button" class="btn btn-invert" id="admin_redend_otp"><span>Resend OTP</span></button>
		</div>
        
      </div>
      <div class="modal-footer">
         <button type="submit" class="btn btn-invert admin-access-verify"><span>Verify OTP</span></button>
      </div>
    </div>
  </div>
</div>		