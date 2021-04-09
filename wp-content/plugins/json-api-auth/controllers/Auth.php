<?php
/*
Controller Name: Auth
Controller Description: Authentication add-on controller for the Wordpress JSON API plugin
Controller Author: Matt Berg, Ali Qureshi
Controller Author Twitter: @parorrey
*/
class JSON_API_Auth_Controller {
public function __construct() {
		global $json_api;
		// allow only connection over https. because, well, you care about your passwords and sniffing.
		// turn this sanity-check off if you feel safe inside your localhost or intranet.
		// send an extra POST parameter: insecure=cool
		/*if (empty($_SERVER['HTTPS']) ||
		    (isset($_SERVER['HTTPS']) && $_SERVER['HTTPS'] == 'off')) {
			if (empty($_REQUEST['insecure']) || $_REQUEST['insecure'] != 'cool') {
				$json_api->error("I'm sorry Dave. I'm afraid I can't do that. (use _https_ please)");
			}
		}*/
		$allowed_from_post = array( 'cookie', 'username', 'password', 'seconds', 'nonce');
		foreach ( $allowed_from_post as $param ) {
			if ( isset( $_POST[ $param ] ) ) {
				$json_api->query->$param = $_POST[ $param ];
			}
		}
	}
	public 	function validate_auth_cookie() {
		global $json_api;
		if ( !$json_api->query->cookie ) {
			$json_api->error( "You must include a 'cookie' authentication cookie. Use the `create_auth_cookie` Auth API method." );
		}
		$valid = wp_validate_auth_cookie( $json_api->query->cookie, 'logged_in' ) ? true : false;
		return array(
			"valid" => $valid
		);
	}
	public	function login() {
		global $json_api;
		if ( !$json_api->query->username ) {
			$json_api->error( "You must include a 'mobile' var in your request." );
		}
		if ( !$json_api->query->password ) {
			$json_api->error( "You must include a 'password' var in your request." );
		}
		$user = wp_authenticate( $json_api->query->username, $json_api->query->password );
		if ( is_wp_error( $user ) ) {
			remove_action( 'wp_login_failed', $json_api->query->username );
			$json_api->error( "Invalid mobile and/or password.", 'error', '401' );
		}
		global $wpdb;
		$SQL = "SELECT * FROM th_installer_data where user_id='".$user->ID."'";
		$installer = $wpdb->get_row($SQL);
		$store = $installer->business_name;
		$add = $installer->address;
		$gstin = $installer->gst_no;
		$contact_person = $installer->contact_person;
		$user = array( 'installer' => array(
			"id" => $user->ID,
			"installer_id" => (int) $installer->installer_data_id,
			"username" => $user->user_login,
			"email" => $user->user_email,
			"business_name" => $installer->business_name,
			"address" => $installer->address,
			"gstin" => $installer->gst_no,
			"contact_person" => $installer->contact_person
		) );
		$respond[ 'message' ] = 'Successfully';
		$respond[ 'data' ] = $user;
		return $respond;
	}
	public function lostPassSendOtp() {
		global $json_api;
		global $wpdb, $woocommerce;
		$otp = rand( 100000, 999999 );
		if ( !$json_api->query->mobile) {
			$json_api->error( "You must include a 'mobile' var in your request." );
		}
		$user = get_userdatabylogin( $json_api->query->mobile );
		$SQL = "SELECT * FROM th_installer_data where user_id='".$user->ID."'";
		$installer = $wpdb->get_row($SQL);
		if (empty($user)) {
			$json_api->error( "You are not registered with this number.", 'error', '401' );
		}
		if ( !empty( $user ) ) {
			$delSQL= "DELETE from th_lost_password WHERE mobile_no = '".$json_api->query->mobile."'";
		    $wpdb->query($delSQL);
			
			$insert = $wpdb->insert('th_lost_password',array('mobile_no'=>$json_api->query->mobile,'otp'=>$otp));
			$reset_pass_msg = "Tyrehub reset your account password using OTP " . $otp . " Thank You Tyrehub Team";
			$reset_pass_msg = str_replace( ' ', '%20', $reset_pass_msg );

			$mobile=$json_api->query->mobile;
			sms_send_to_customer($reset_pass_msg,$mobile,$templateID=1);

			$respond[ 'message' ] = 'Successfully';
			$respond[ 'data' ] = array( 'id' => $user->ID, 'installer_id' => $installer->installer_data_id,'otp' => $otp);
			return $respond;
		}
	}
	public	function passwordUpdate() {
		global $json_api;
		global $wpdb, $woocommerce;
		if ( !$json_api->query->id ) {
			$json_api->error( "You must include a 'id' var in your request." );
		}
		if ( !$json_api->query->newpass ) {
			$json_api->error( "You must include a 'newpass' var in your request." );
		}
		wp_set_password( $json_api->query->newpass, $json_api->query->id );
		$respond[ 'message' ] = 'Successfully';
		$respond[ 'data' ] = array( 'id' => $json_api->query->id );
		return $respond;
	}
public	function passwordChange() {
		global $json_api;
		global $wpdb, $woocommerce;
		if (!$json_api->query->login_id) {
			$json_api->error( "You must include a 'login_id' var in your request." );
		}
		if ( !$json_api->query->oldpass ) {
			$json_api->error( "You must include a 'oldpass' var in your request." );
		}
		if ( !$json_api->query->newpass ) {
			$json_api->error( "You must include a 'newpass' var in your request." );
		}
		$user = get_user_by('id',$json_api->query->login_id);
		if ($user && wp_check_password($json_api->query->oldpass,$user->data->user_pass, $user->ID)) {
			$flag=1;
		    wp_set_password( $json_api->query->newpass, $json_api->query->login_id);
		     $message='Successfully';
		     $status='ok';
		} else {
		    $flag=0;
		    $status='error';
		    $message='Old password is not match!';
		}
		
		$respond[ 'status' ] = $status;
		$respond[ 'message' ] = $message;
		$respond[ 'data' ] = array();
		return $respond;
}
public	function openServices() {
		global $json_api, $wpdb, $woocommerce;
		$installer_id = $json_api->query->installer_id;
		if ( !$installer_id ) {
			$json_api->error( "You must include a 'installer_id' var in your request." );
		}
		$sql = "SELECT * FROM th_cart_item_installer WHERE installer_id = '$installer_id'";
		$sql_voucher = "SELECT * FROM th_cart_item_service_voucher WHERE installer_id = '$installer_id'";
		$row = $wpdb->get_results( $sql );
		if ( !empty( $row ) ) {
			foreach ( $row as $key => $installer ) {
				if ( $installer->order_id != 0 ) {
					$order_arr[] = $installer->order_id;
				}
			}
		}
		$row1 = $wpdb->get_results( $sql_voucher );
		if ( !empty( $row1 ) ) {
			foreach ( $row1 as $key => $installer ) {
				if ( $installer->order_id != 0 ) {
					$order_arr[] = $installer->order_id;
				}
			}
		}
		$order_arr = array_unique( $order_arr );
		
		$flog = 0;
		$order_query = array(
			'post__in' => $order_arr,
			'post_type' => 'shop_order',
			'numberposts' => -1,
			'posts_per_page' => -1,
			'post_status' => array( 'wc-processing', 'wc-deltoinstaller' ),
		);
		$loop = new WP_Query( $order_query );
		// var_dump($loop);
		
		if ( $loop->have_posts() ) {
			$i=0;
			$services_list=array();
			while ( $loop->have_posts() ) {
				$loop->the_post();
				$order_id = $loop->post->ID;
				$order = wc_get_order( $order_id );
				$order_data = $order->get_data();
				$order_items = $order->get_items();
				$order_date = $order->order_date;
				$order_status = $order->get_status();
				$order_status_name = esc_html( wc_get_order_status_name( $order->get_status() ) );
				// customer
				$user = $order->get_user();
				$first_name = $order_data[ 'billing' ]['first_name'];
				$last_name = $order_data[ 'billing' ]['last_name'];
				$mobile_no = $order_data[ 'billing' ]['phone'];
				$email = $order_data[ 'billing' ][ 'email' ];
				foreach ( $order_items as $item_id => $item_data ) {
					if ( $item_data[ 'variation_id' ] != '' ) {
						$order_prd_id = $item_data[ 'variation_id' ];
					} else {
						$order_prd_id = $item_data[ 'product_id' ];
					}
					$sku = 'service_voucher';
					$service_voucher_prd = $wpdb->get_var("SELECT post_id FROM $wpdb->postmeta WHERE meta_key='_sku' AND meta_value='".$sku."' LIMIT 1");
					$product_variation = wc_get_product( $order_prd_id );
					$variation_des = $product_variation->get_description();
					$parent_id = $product_variation->get_parent_id();
					$image = wp_get_attachment_image_src( get_post_thumbnail_id( $parent_id ), 'single-post-thumbnail' );
					$prd_qty = $item_data[ 'quantity' ];
					if ( $order_prd_id == $service_voucher_prd ) {
						$service_data = "SELECT * 
							        FROM th_cart_item_service_voucher
							        WHERE product_id = '$order_prd_id' and installer_id = '$installer_id' and order_id = '$order_id' and status != 'completed'";
							        $voucher_type ='voucher';
					} else {
						$service_data = "SELECT * 
								        FROM th_cart_item_installer
								        WHERE product_id = '$order_prd_id' and installer_id = '$installer_id' and order_id = '$order_id' and status != 'completed' and destination = 1";
								        $voucher_type ='service';
					}
					//var_dump($service_data);
					$row = $wpdb->get_results( $service_data );
					
					if ( !empty( $row ) ) {
						
						$flog = 1;
						/*echo '<pre>';
						print_r($row);*/
						foreach ( $row as $key => $data ) {
							//$services_id=$data->
							$service_voucher_id = $data->service_voucher_id;
							$vehicle_id = $data->vehicle_id;
							$voucher_name=$data->voucher_name;
							$service_data_id=$data->service_data_id;
							$SQL="SELECT vehicle_type FROM th_vehicle_type WHERE vehicle_id='".$vehicle_id."'";
							$vehicle_name = $wpdb->get_row($SQL);
							$destination = $data->destination;
							if ( $order_prd_id == $service_voucher_prd ) {
								
								//$voucher_type = $data->vehicle_type;
								if ( $voucher_name == 'promotional' || $voucher_name == 'promotion' ) {
									$service_type = 'Promotion Voucher';
									$voucher_type = 'promotion';
								} else {
									
									$voucher_type = $voucher_type;
								}
							} else {
								$item_installer = $data->cart_item_installer_id;
							}
							$service_type = $vehicle_name->vehicle_type; //Service voucher for vehicle type
							$order =$order_id;
							date_default_timezone_set( "Asia/Kolkata" );
							$OrdernewDate = date( "d-m-Y H:i a", strtotime( $order_date ) );
							if ( $image[0] != '' ) {
								$pro_img=$image[0];
							} else {
								$pro_img=get_template_directory_uri().'/images/no_img1.png';
							}
							//echo $voucher_type;
							// voucher data
							if ($order_prd_id == $service_voucher_prd && $voucher_type == 'promotion' ) {
								$customername = 'Tyrehub';
								$mobile_no = '1-800-233-5551';
								$voucher_name = $voucher_name;
						$services = "SELECT * 
                    FROM th_service_data WHERE as_flag =0 and status =1 AND show_on_cart=1";
									$row = $wpdb->get_results( $services );
									$servicelist=array();
									foreach ( $row as $key => $service ) {
										$service_name='';
										$service_id = $service->cart_item_services_id;
										$service_name = $service->sd_name;
										$tyre_fitment = get_template_directory_uri().'/images/service-icon/'.$service->sd_name;
										$servicelist[$key] = array(
											'name' => $service->service_name,
											'img_url' => get_template_directory_uri(). '/images/service-icon/'.$service->services_image
										);
										
										
									}
								
							} elseif ( $order_prd_id == $service_voucher_prd && $voucher_type == 'service' ) {
									$customername = $first_name . ' ' . $last_name;
									$mobile_no = $mobile_no;
									//Selected Services
			$services = "SELECT * FROM th_service_data WHERE as_flag =0 and status =1 AND show_on_cart=1 LIMIT 1,50";
									$row = $wpdb->get_results($services);
									$servicelist=array();
									foreach ($row as $key =>$service) {
										$service_name='';
										$service_id = $service->cart_item_services_id;
										$tyre_fitment = get_template_directory_uri().'/images/service-icon/'.$service->sd_name;
										$servicelist[$key] = array(
											'name' => $service->service_name,
											'img_url' => get_template_directory_uri(). '/images/service-icon/'.$service->services_image
										);
										
										
									}
									$voucher_name = $voucher_name;
								} elseif ($order_prd_id == $service_voucher_prd && $voucher_type == 'voucher' ) {
									$customername = $first_name . ' ' . $last_name;
									$mobile_no = $mobile_no;
									//Selected Services
									if($service_data_id==5){
										 $services = "SELECT * FROM th_service_data WHERE as_flag =1 AND status =1 AND show_on_cart=1 AND service_data_id=".$data->service_data_id;
										
									}else{
										$services = "SELECT * FROM th_service_data WHERE service_data_id!=1 AND as_flag =0 AND status =1 AND show_on_cart=1";	
									}
			
		
									$row = $wpdb->get_results($services);
									$servicelist=array();
									foreach ($row as $key =>$service) {
										$service_name='';
										$service_id = $service->cart_item_services_id;
										$tyre_fitment = get_template_directory_uri().'/images/service-icon/'.$service->sd_name;
										$servicelist[$key] = array(
											'name' => $service->service_name,
											'img_url' => get_template_directory_uri(). '/images/service-icon/'.$service->services_image
										);
										
										
									}
									$voucher_name = $voucher_name;
								}else {
									$voucher_name='';
									// service data
									$tyrename = $variation_des;
									$customername = $first_name . ' ' . $last_name;
									$mobile_no = $mobile_no;
$services = "SELECT sd.service_name as sd_name,sd.services_image,cis.* 
                    FROM th_cart_item_services cis LEFT JOIN th_service_data as sd ON sd.service_data_id=cis.service_data_id
                    WHERE cis.product_id = '$order_prd_id' and cis.order_id = '$order_id'";
									$row = $wpdb->get_results( $services );
									$servicelist=array();
									foreach ( $row as $key => $service ) {
										$service_name='';
										$service_id = $service->cart_item_services_id;
										$service_name = $service->sd_name;
										$tyre_fitment = get_template_directory_uri().'/images/service-icon/'.$service->sd_name;
										$servicelist[$key] = array(
											'name' => $service->sd_name,
											'img_url' => get_template_directory_uri(). '/images/service-icon/'.$service->services_image
										);
										
										
									}
								} //promotion
								
								if ( $order_prd_id == $service_voucher_prd ) {
									$id = $service_voucher_id;
									$type='voucher';
								} else {
									$id = $item_installer;
									$type='service';
								}
								if($tyrename){
									$tyrename=$tyrename;
								}else{
									$tyrename='';
								}
								$services_list[$i]['services']['id']=$id;
								$services_list[$i]['services']['type']=$type;
								$services_list[$i]['services']['orderno']=$order;
							    $services_list[$i]['services']['pro_img']=$pro_img;
					$services_list[$i]['services']['service_type']=(!empty($service_type) ? $service_type :'');
							    $services_list[$i]['services']['product_name']=$tyrename;
							    $services_list[$i]['services']['customername']=$customername;
							    $services_list[$i]['services']['mobile_no']=$mobile_no;
							    $services_list[$i]['services']['voucher_name']=$voucher_name;
							    $services_list[$i]['services']['order_date']=$OrdernewDate;
							    $services_list[$i]['addiservices']=$servicelist;
						
						} //end for services data
					} // end if services data
				
				} // end $order_items
				$i++;
			} //end while 
			
		} else {
			//echo 'No Service Found!';
			$services_list=array();
		}
		$respond[ 'message' ] = 'Successfully';
		$respond[ 'data' ] = array_values($services_list);
		return $respond;
	}
public	function completedServices() {
		global $json_api, $wpdb, $woocommerce;
		$installer_id = $json_api->query->installer_id;
		if ( !$installer_id ) {
			$json_api->error( "You must include a 'installer_id' var in your request." );
		}
		 $sql = "SELECT * FROM th_cart_item_installer WHERE installer_id = '$installer_id' AND status = 'completed' and paid != 'yes'";
		$sql_voucher = "SELECT * FROM th_cart_item_service_voucher WHERE installer_id = '$installer_id' AND status = 'completed' and paid != 'yes'";
		$row = $wpdb->get_results( $sql );
		if ( !empty( $row ) ) {
			foreach ( $row as $key => $installer ) {
				if ( $installer->order_id != 0 ) {
					$order_arr[] = $installer->order_id;
				}
			}
		}
		$row1 = $wpdb->get_results( $sql_voucher );
		if ( !empty( $row1 ) ) {
			foreach ( $row1 as $key => $installer ) {
				if ( $installer->order_id != 0 ) {
					$order_arr[] = $installer->order_id;
				}
			}
		}
		$order_arr = array_unique($order_arr);
		//  var_dump($order_arr);
		if($order_arr){
			$flog = 0;
			$order_query = array(
	    	'post__in' => $order_arr,
	        'post_type' => 'shop_order',
	        'numberposts'   => -1,
	        'posts_per_page' => -1,
	        'post_status' => 'any',
	   		);
			$loop = new WP_Query( $order_query );
			// var_dump($loop);
			//echo '<pre>';
			//print_r($loop);
			if ( $loop->have_posts() ) {
				$i=0;
				$services_list=array();
				while ( $loop->have_posts() ) {
					$loop->the_post();
					$order_id = $loop->post->ID;
					$order = wc_get_order( $order_id );
					$order_data = $order->get_data();
					$order_items = $order->get_items();
					$order_date = $order->order_date;
					$order_status = $order->get_status();
					$order_status_name = esc_html( wc_get_order_status_name( $order->get_status() ) );
					// customer
					$user = $order->get_user();
					$first_name = $order_data[ 'billing' ]['first_name'];
					$last_name = $order_data[ 'billing' ]['last_name'];
					$mobile_no = $order_data[ 'billing' ]['phone'];
					$email = $order_data[ 'billing' ][ 'email' ];
					foreach ( $order_items as $item_id => $item_data ) {
						if ( $item_data[ 'variation_id' ] != '' ) {
							$order_prd_id = $item_data[ 'variation_id' ];
						} else {
							$order_prd_id = $item_data[ 'product_id' ];
						}
						$sku = 'service_voucher';
						$service_voucher_prd = $wpdb->get_var( $wpdb->prepare( "SELECT post_id FROM $wpdb->postmeta WHERE meta_key='_sku' AND meta_value='%s' LIMIT 1", $sku ) );
						$product_variation = wc_get_product( $order_prd_id );
						$variation_des = $product_variation->get_description();
						$parent_id = $product_variation->get_parent_id();
						$image = wp_get_attachment_image_src( get_post_thumbnail_id( $parent_id ), 'single-post-thumbnail' );
						$prd_qty = $item_data[ 'quantity' ];
						if ( $order_prd_id == $service_voucher_prd ) {
							$service_data = "SELECT * 
								        FROM th_cart_item_service_voucher
								        WHERE product_id = '$order_prd_id' and installer_id = '$installer_id' and order_id = '$order_id' and status = 'completed' and paid != 'yes'";
								          $voucher_type ='voucher';
						} else {
							$service_data = "SELECT * 
									        FROM th_cart_item_installer
									        WHERE product_id = '$order_prd_id' and installer_id = '$installer_id' and order_id = '$order_id' and status = 'completed' and paid != 'yes'";
									          $voucher_type ='service';
						}
						//var_dump($service_data);
						$row = $wpdb->get_results( $service_data );
						
						if ( !empty( $row ) ) {
							
							$flog = 1;
							foreach ( $row as $key => $data ) {
								
								$destination = $data->destination;
								$completed_date = $data->completed_date;
								$vehicle_id = $data->vehicle_id;
									
									
								//$services_id=$data->
							$service_voucher_id = $data->service_voucher_id;
							$vehicle_id = $data->vehicle_id;
							$voucher_name=$data->voucher_name;
							$service_data_id=$data->service_data_id;
							$SQL="SELECT vehicle_type FROM th_vehicle_type WHERE vehicle_id='".$vehicle_id."'";
							$vehicle_name = $wpdb->get_row($SQL);
							$destination = $data->destination;
							if ( $order_prd_id == $service_voucher_prd ) {
								
								//$voucher_type = $data->vehicle_type;
								if ( $voucher_name == 'promotional' || $voucher_name == 'promotion' ) {
									$service_type = 'Promotion Voucher';
									$voucher_type = 'promotion';
								} else {
									
									$voucher_type = $voucher_type;
								}
							} else {
								$item_installer = $data->cart_item_installer_id;
							}
							$service_type = $vehicle_name->vehicle_type; //Service voucher for vehicle type
								$order =$order_id;
								date_default_timezone_set( "Asia/Kolkata" );
								$OrdernewDate = date( "d-m-Y H:i a", strtotime( $order_date ) );
								if ( $image[0] != '' ) {
									$pro_img=$image[0];
								} else {
									$pro_img=get_template_directory_uri().'/images/no_img1.png';
								}
								
								// voucher data
								if ($order_prd_id == $service_voucher_prd && $voucher_type == 'promotion' ) {
									$customername = 'Ankit Shah';
									$mobile_no = $mobile_no;
									$voucher_name = $voucher_name;
							$services = "SELECT * 
	                    FROM th_service_data WHERE as_flag =0 and status =1 AND show_on_cart=1";
										$row = $wpdb->get_results( $services );
										$servicelist=array();
										foreach ( $row as $key => $service ) {
											$service_name='';
											$service_id = $service->cart_item_services_id;
											$tyre_fitment = get_template_directory_uri().'/images/service-icon/'.$service->sd_name;
											$servicelist[$key] = array(
												'name' => $service->service_name,
												'img_url' => get_template_directory_uri(). '/images/service-icon/'.$service->services_image
											);
											
											
										}
									
								} elseif ( $order_prd_id == $service_voucher_prd && $voucher_type == 'service' ) {
										$customername = $first_name . ' ' . $last_name;
										$mobile_no = $mobile_no;
										//Selected Services
				$services = "SELECT * FROM th_service_data WHERE as_flag =0 and status =1 AND show_on_cart=1 LIMIT 1,50";
										$row = $wpdb->get_results( $services );
										$servicelist=array();
										foreach ( $row as $key => $service ) {
											$service_id = $service->cart_item_services_id;
											$tyre_fitment = get_template_directory_uri().'/images/service-icon/'.$service->sd_name;
											$servicelist[$key] = array(
												'name' => $service->service_name,
												'img_url' => get_template_directory_uri(). '/images/service-icon/'.$service->services_image
											);
											
											
										}
										$voucher_name = $voucher_name;
									}elseif ( $order_prd_id == $service_voucher_prd && $voucher_type == 'voucher' ) {
										$customername = $first_name . ' ' . $last_name;
										$mobile_no = $mobile_no;
										//Selected Services
									if($service_data_id==5){
										 $services = "SELECT * FROM th_service_data WHERE as_flag =1 AND status =1 AND show_on_cart=1 AND service_data_id=".$service_data_id;
										
									}else{
										$services = "SELECT * FROM th_service_data WHERE service_data_id!=1 AND as_flag =0 AND status =1 AND show_on_cart=1";	
									}
										$row = $wpdb->get_results( $services );
										$servicelist=array();
										foreach ( $row as $key => $service ) {
											$service_id = $service->cart_item_services_id;
											$tyre_fitment = get_template_directory_uri().'/images/service-icon/'.$service->sd_name;
											$servicelist[$key] = array(
												'name' => $service->service_name,
												'img_url' => get_template_directory_uri(). '/images/service-icon/'.$service->services_image
											);
											
											
										}
										$voucher_name = $voucher_name;
									}  else {
										$voucher_name='';
										// service data
										$tyrename = $variation_des;
										$customername = $first_name . ' ' . $last_name;
										$mobile_no = $mobile_no;
	$services = "SELECT sd.service_name as sd_name,sd.services_image,cis.* 
	                    FROM th_cart_item_services cis LEFT JOIN th_service_data as sd ON sd.service_data_id=cis.service_data_id
	                    WHERE cis.product_id = '$order_prd_id' and cis.order_id = '$order_id'";
										$row = $wpdb->get_results( $services );
										$servicelist=array();
										foreach ( $row as $key => $service ) {
											$service_id = $service->cart_item_services_id;
											$service_name = $service->sd_name;
											$tyre_fitment = get_template_directory_uri().'/images/service-icon/'.$service->sd_name;
											$servicelist[$key] = array(
												'name' => $service->sd_name,
												'img_url' => get_template_directory_uri(). '/images/service-icon/'.$service->services_image
											);
											
											
										}
									} //promotion
									$services_list[$i]['services']['orderno']=$order;
								    $services_list[$i]['services']['pro_img']=$pro_img;
								    $services_list[$i]['services']['service_type']=$service_type;
								    $services_list[$i]['services']['product_name']=(!empty($tyrename)) ? $tyrename : '';
								    $services_list[$i]['services']['customername']=$customername;
								    $services_list[$i]['services']['mobile_no']=$mobile_no;
								    $services_list[$i]['services']['voucher_name']=$voucher_name;
								    $services_list[$i]['services']['completed_date']=$completed_date;							    
								    $services_list[$i]['addiservices']=$servicelist;
								    
									if ( $order_prd_id == $service_voucher_prd ) {
										$voucher_id = $service_voucher_id;
									} else {
										$service_id = $item_installer;
									}
							} //end for services data
						} // end if services data
					
					} // end $order_items
					$i++;
				} //end while 
				
			} else {
				//echo 'No Service Found!';
				$services_list=array();
			}
		}else{
				$services_list=array();	
		}
		$respond[ 'message' ] = 'Successfully';
		$respond[ 'data' ] =  array_values($services_list);
		return $respond;
	}
public	function updateVoucherStatus() {
		global $json_api, $wpdb, $woocommerce;
		date_default_timezone_set('Asia/Kolkata');
	$date = date('d-m-Y h:i:s a', time());
		if ( !$json_api->query->order_id) {
			$json_api->error( "You must include a 'order_id' var in your request." );
		}
		if ( !$json_api->query->voucher_code) {
			$json_api->error( "You must include a 'voucher_code' var in your request." );
		}
		if ( !$json_api->query->type) {
			$json_api->error( "You must include a 'type' var in your request." );
		}
		$vcode = $json_api->query->voucher_code;
		$tyre_status = 'completed';
		if($vcode && $vcode>0 && $json_api->query->type=='voucher'){
				  
				$SQL="SELECT * FROM th_cart_item_service_voucher WHERE barcode='".$vcode."'";
				$barcode=$wpdb->get_row($SQL);
				
				if($barcode->barcode)
				{	
					$service_voucher_id=$barcode->barcode;
					$services = "UPDATE th_cart_item_service_voucher set status = '$tyre_status' , completed_date = '$date' WHERE barcode = '$service_voucher_id'";
					$row = $wpdb->query($services);
					
					$flag=1;     
				}else{
					$flag=0;
				}
		}
		if($vcode && $vcode>0 && $json_api->query->type=='service'){
				
			    $SQL="SELECT * FROM th_cart_item_installer WHERE barcode='".$vcode."'";
				$barcode=$wpdb->get_row($SQL);
				
				if($barcode->barcode)
				{
					
			        $tyre_installer_id=$barcode->tyre_installer_id;
			        $services = "UPDATE th_cart_item_installer set status = '$tyre_status' , completed_date = '$date' WHERE cart_item_installer_id = '$tyre_installer_id'";
					$row = $wpdb->query($services);
			        $installer_id=$barcode->installer_id;
					$product_id =$barcode->product_id;
					
					$product_variation = wc_get_product( $product_id);
					$variation_des = $product_variation->get_description();
					$variation_des = trim(preg_replace('/\s+/', ' ', $variation_des));
					$variation_des = substr($variation_des, 0, 25);
					$SQL="SELECT * FROM th_installer_data WHERE installer_data_id='$installer_id'";
					$installer=$wpdb->get_row($SQL);
					$mobile_no=$installer->contact_no;
					$installer_name = $installer->business_name;
					
					$service_complete_msg = "Tyrehub.com your service for ".$variation_des." is completed by ".$installer_name." Thank You Tyrehub Team";
					$reset_pass_msg = str_replace(' ', '%20', $service_complete_msg);
					sms_send_to_customer($service_complete_msg,$mobile_no,$templateID=1);

					$flag=1;
				}else{
					$flag=0; 
				}
		}
		if($flag !=0 ){
			$order_id=$json_api->query->order_id;
			$SQL = "UPDATE th_cart_item_installer set status = 'completed' WHERE order_id = '$order_id'";			
			$row = $wpdb->query($SQL);	
			$order = new WC_Order($order_id); 
			if (!empty($order)) {
			    $order->update_status('completed');
			}
			$respond[ 'message' ] = 'Successfully';
			$respond[ 'data' ] = array();
		
		}else{
			$respond[ 'status' ] = 'error';
			$respond[ 'message' ] = 'Voucher number is not valid';
			$respond[ 'data' ] = array();
		
		}
	return $respond;
}
public	function verifyVoucherCode() {
		global $json_api, $wpdb, $woocommerce;
		if (!$json_api->query->order_id) {
			$json_api->error( "You must include a 'order_id' var in your request." );
		}
		if (!$json_api->query->voucher_code) {
			$json_api->error( "You must include a 'voucher_code' var in your request." );
		}
		if (!$json_api->query->type) {
			$json_api->error( "You must include a 'type' var in your request." );
		}
		$order_id=$json_api->query->order_id;
		$vcode = $json_api->query->voucher_code;
		$tyre_status = 'completed';
		date_default_timezone_set('Asia/Kolkata');
		$date = date('d-m-Y h:i:s a', time());  
		//Vouchare Status Update
		if($vcode && $vcode>0 && $json_api->query->type=='voucher'){
				  
				$SQL="SELECT * FROM th_cart_item_service_voucher WHERE barcode='".$vcode."' AND order_id='".$order_id."'";
				$barcode=$wpdb->get_row($SQL);
				
				if($barcode->barcode)
				{
					$serv_id=$barcode->service_voucher_id;		
					$type='voucher';
					$flag=1;     
				}else{
					$flag=0;
				}
		}
		if($vcode && $vcode>0 && $json_api->query->type=='service'){
				
			    $SQL="SELECT * FROM th_cart_item_installer WHERE barcode='".$vcode."' AND order_id='".$order_id."'";
				$barcode=$wpdb->get_row($SQL);
				
				if($barcode->barcode)
				{
					
				     $destination = $barcode->destination;
			        $serv_id = $barcode->cart_item_installer_id;
			        //$service_id=$barcode->service_voucher_id;
			        //$tyre_status = $barcode->status;
			        $type='service';
					$flag=1;
					
				}else{
					$flag=0; 
				}
		}
		if($flag!=0){
					$order_id = $barcode->order_id;
			        $order = wc_get_order($order_id);
			        $order_data = $order->get_data();
			        $order_items = $order->get_items();
			        $order_date = $order->order_date;
			        $first_name = $order_data['billing']['first_name'];
			        $last_name = $order_data['billing']['last_name'];
			        $mobile_no = $order_data['billing']['phone'];   
			        $email = $order_data['billing']['email']; 
			        
			            
			        $order_prd_id = $barcode->product_id;
			        foreach ($order_items as $item_id => $item_data)
			        {         
			            if($item_data['variation_id'] != ''){
			                $temp_prd_id = $item_data['variation_id'];
			            }
			            else{
			                $temp_prd_id = $item_data['product_id'];
			            }   
			            if($order_prd_id == $temp_prd_id){
			                $quantity = $item_data['quantity'];
			            }
			            
			        }
			        $product_variation = wc_get_product( $order_prd_id );
			        $variation_des = $product_variation->get_description();
			        $parent_id = $product_variation->get_parent_id();
			        $image = wp_get_attachment_image_src( get_post_thumbnail_id( $parent_id ), 'single-post-thumbnail' );
			       $vehicle_id = $barcode->vehicle_id;
                   $vehiSQL="SELECT vehicle_type FROM th_vehicle_type WHERE vehicle_id='%s' LIMIT 1";
            	   $vehicle_name = $wpdb->get_var( $wpdb->prepare($vehiSQL, $vehicle_id));
		           $voucher_name = $barcode->voucher_name;
		            if($voucher_name == 'promotional' || $voucher_name == 'promotion'){
		                $service_type = 'Promotion Voucher';
		                $voucher_type = 'promotion';
		            }else{
		                $service_type = $vehicle_name;
		                $voucher_type = 'service';
		            }
		            		$order =$order_id;
							date_default_timezone_set("Asia/Kolkata");
							$OrdernewDate = date("d-m-Y H:i a",strtotime($order_date));
							if ($image[0] != '') {
								$pro_img=$image[0];
							} else {
								$pro_img=get_template_directory_uri().'/images/no_img1.png';
							}
							
						// voucher data
						if ($voucher_type == 'promotion' ) {
								$customername = 'Tyrehub';
								$mobile_no = $mobile_no;
								$voucher_name = $voucher_name;
						$services = "SELECT * 
                    FROM th_service_data WHERE as_flag =0 and status =1 AND show_on_cart=1";
									$row = $wpdb->get_results( $services );
									$servicelist=array();
									foreach ( $row as $key => $service ) {
										$service_name='';
										$service_id = $service->cart_item_services_id;
										$service_name = $service->sd_name;
										$tyre_fitment = get_template_directory_uri().'/images/service-icon/'.$service->sd_name;
										$servicelist[$key] = array(
											'name' => $service->service_name,
											'img_url' => get_template_directory_uri(). '/images/service-icon/'.$service->services_image
										);
									
									}
								
							} elseif ($voucher_type == 'voucher' ) {
									$customername = $first_name . ' ' . $last_name;
									$mobile_no = $mobile_no;
									//Selected Services
			$services = "SELECT sd.service_name as sd_name,sd.services_image,cis.* 
                    FROM th_cart_item_services cis LEFT JOIN th_service_data as sd ON sd.service_data_id=cis.service_data_id
                    WHERE cis.product_id = '$order_prd_id' and cis.order_id = '$order_id'";
									$row = $wpdb->get_results( $services );
									$servicelist=array();
									foreach ( $row as $key => $service ) {
										$service_name='';
										$service_id = $service->cart_item_services_id;
										$tyre_fitment = get_template_directory_uri().'/images/service-icon/'.$service->sd_name;
										$servicelist[$key] = array(
											'name' => $service->service_name,
											'img_url' => get_template_directory_uri(). '/images/service-icon/'.$service->services_image
										);
										
										
									}
									$voucher_name = $voucher_name;
								}elseif ($voucher_type == 'service' ) {
									$customername = $first_name . ' ' . $last_name;
									$mobile_no = $mobile_no;
									//Selected Services
			$services = "SELECT sd.service_name as sd_name,sd.services_image,cis.* 
                    FROM th_cart_item_services cis LEFT JOIN th_service_data as sd ON sd.service_data_id=cis.service_data_id
                    WHERE cis.product_id = '$order_prd_id' and cis.order_id = '$order_id'";
									$row = $wpdb->get_results( $services );
									$servicelist=array();
									foreach ( $row as $key => $service ) {
										$service_name='';
										$service_id = $service->cart_item_services_id;
										$tyre_fitment = get_template_directory_uri().'/images/'.$service->sd_name;
										$servicelist[$key] = array(
											'name' => $service->service_name,
											'img_url' => get_template_directory_uri(). '/images/'.$service->services_image
										);
										
										
									}
									$voucher_name = $voucher_name;
								} else {
									$voucher_name='';
									// service data
									$tyrename = $variation_des;
									$customername = $first_name . ' ' . $last_name;
									$mobile_no = $mobile_no;
$services = "SELECT sd.service_name as sd_name,sd.services_image,cis.* 
                    FROM th_cart_item_services cis LEFT JOIN th_service_data as sd ON sd.service_data_id=cis.service_data_id
                    WHERE cis.product_id = '$order_prd_id' and cis.order_id = '$order_id'";
									$row = $wpdb->get_results( $services );
									$servicelist=array();
									foreach ( $row as $key => $service ) {
										$service_name='';
										$service_id = $service->cart_item_services_id;
										$service_name = $service->sd_name;
										$tyre_fitment = get_template_directory_uri().'/images/service-icon/'.$service->sd_name;
										$servicelist[$key] = array(
											'name' => $service->sd_name,
											'img_url' => get_template_directory_uri(). '/images/service-icon/'.$service->services_image
										);
									}
								} //promotion
								
								
								if($tyrename){
									$tyrename=$tyrename;
								}else{
									$tyrename='';
								}
			$services_list['services']['id']=$serv_id;
			$services_list['services']['type']=$type;
			$services_list['services']['orderno']=$order;
		    $services_list['services']['pro_img']=$pro_img;
		    $services_list['services']['service_type']=$service_type;
		    $services_list['services']['product_name']=$tyrename;
		    $services_list['services']['customername']=$customername;
		    $services_list['services']['mobile_no']=$mobile_no;
		    $services_list['services']['voucher_name']=(!empty($voucher_name)) ? $voucher_name: '';
		    $services_list['services']['order_date']=$OrdernewDate;
		    $services_list['addiservices']=$servicelist;
			$respond[ 'message' ] = 'Successfully';
			$respond[ 'data' ] = (object)$services_list;
			
		}else{
			$respond[ 'status' ] = 'error';
			$respond[ 'message' ] = 'Voucher number is not valid';
			$respond[ 'data' ] = (object)array();
		
		}
		
		return $respond;
	}
public	function installerPaidHistory() {
		global $json_api, $wpdb, $woocommerce;
		if (!$json_api->query->installer_id) {
			$json_api->error( "You must include a 'installer_id' var in your request." );
		}
		$installer_id=$json_api->query->installer_id;
	    $currency = get_woocommerce_currency_symbol();
	 
	    $sql = "SELECT * FROM th_paid_service where installer_id = '$installer_id' order by id desc";
	    $row = $wpdb->get_results($sql);
		
		if($row){
				$paidhistory=array();
	 			foreach ($row as $key => $value)
	 			{
	 				$id = $value->id;
	 				$invoice_no = $value->invoice_no;
	 				$date = $value->date;
	 				
	 				$amount = $value->amount;
	 				$invoice_link= admin_url()."/admin-ajax.php?action=installer_report_pdf&document_type=invoice&order_ids=3759&service_id=".$id."&_wpnonce=04e74a5779";
	 				$paidhistory[$key]['invoices']['id']=$id;
	 				$paidhistory[$key]['invoices']['invoice_no']=$invoice_no;
	 				$paidhistory[$key]['invoices']['date']=$date;
	 				$paidhistory[$key]['invoices']['invoice']=$invoice_link;
	 				$paidhistory[$key]['invoices']['price']= number_format((float)$amount, 2, '.', '');
	 			$flag=1;
	 			}
	 		}else{
	 			$paidhistory=array();
	 			$flag=1;
	 		}
		
		if($flag !=0 ){
			$respond[ 'message' ] = 'Successfully';
			$respond[ 'data' ] = $paidhistory;
		}else{
			$respond[ 'status' ] = 'error';
			$respond[ 'message' ] = 'No paid invoices!';
			$respond[ 'data' ] = array();
		
		}
	return $respond;
}
public	function getInstallerProfile() {
		global $json_api,$wpdb;
		if ( !$json_api->query->installer_id) {
			$json_api->error( "You must include a 'installer_id' var in your request." );
		}
		
		$installer_id=$json_api->query->installer_id;
		$SQL = "SELECT * FROM th_installer_data WHERE installer_data_id = '$installer_id'";		
        $installer = $wpdb->get_row($SQL);
        if($installer){
				$current_user = get_user_by( 'id', $installer->user_id); // 54 is a user ID
				//	var_dump($current_user);
				$user_id = $current_user->ID;
				$name = $current_user->display_name;
				$number =  $current_user->user_login;
				$email_id =  $current_user->user_email;
				
				if (\strpos($email_id, 'test') !== false) {
				     $flag =  'true';
				}else{
					 $flag =  'false';
				}
				$profile=array();
				$profile['service_center']=array(
					'center_name'=>$installer->business_name,
					'store_phone'=>$installer->store_phone,
					'address'=>$installer->address,
					'lattitude'=>$installer->location_lattitude,
					'longitude'=>$installer->location_longitude			
				);
				$profile['primary_ontact']=array(
					'contact_person_name'=>$installer->contact_person,
					'mobile_no'=>$installer->contact_no,
					'email'=>(!empty($email_id))? $email_id :'',
				);
				$profile['additional_information']=array(
					'gst_no'=>(!empty($installer->gst_no))? $installer->gst_no :'',
					'company_name'=>(!empty($installer->company_name)) ? $installer->company_name : '',
						'company_add'=>(!empty($installer->company_add)) ? $installer->company_add : '');
				$flag=1;
		}else{
			$flag=0;
		}
		if($flag !=0 ){
			$respond[ 'message' ] = 'Successfully';
			$respond[ 'data' ] = $profile;
		}else{
			$respond[ 'status' ] = 'error';
			$respond[ 'message' ] = 'No found data!';
			$respond[ 'data' ] = array();
		
		}
	return $respond;
	}
	public	function updateInstallerProfile() {
		global $json_api,$wpdb;
		$installer_id=$json_api->query->installer_id;
		$center_name=$json_api->query->center_name;
		$store_phone=$json_api->query->store_phone;
		$contact_person_name=$json_api->query->contact_person_name;
		$mobile_no=$json_api->query->mobile_no;
		$email_id=$json_api->query->email_id;
		$gst_no=$json_api->query->gst_no;
		$company_name=$json_api->query->company_name;
		$company_add=$json_api->query->company_add;
		if (!$installer_id) {
			$json_api->error( "You must include a 'installer_id' var in your request." );
		}
		if (!$center_name) {
			$json_api->error( "You must include a 'center_name' var in your request." );
		}
		if (!$store_phone) {
			$json_api->error( "You must include a 'store_phone' var in your request." );
		}
		if (!$contact_person_name) {
			$json_api->error( "You must include a 'contact_person_name' var in your request." );
		}
		if (!$mobile_no) {
			$json_api->error( "You must include a 'mobile_no' var in your request." );
		}
			$SQLUser="SELECT * FROM th_installer_data WHERE installer_data_id='".$installer_id."'";
			$getUser=$wpdb->get_row($SQLUser);
			$SQL="SELECT * FROM wp_users WHERE ID!='".$getUser->user_id."' AND user_login='".$mobile_no."'";
			$checkUser=$wpdb->get_row($SQL);
			if(empty($checkUser->ID)){
					$flag=1;
					$updateData=array(
					'business_name'=>$center_name,
					'store_phone'=>$store_phone,
					'contact_person'=>$contact_person_name,
					'contact_no'=>$mobile_no,
					'gst_no'=>$gst_no,
					'company_name'=>$company_name,
					'company_add'=>$company_add
				    );
				$where =array('installer_data_id' =>$installer_id); // NULL value in WHERE clause.
				$wpdb->update('th_installer_data',$updateData,$where); // Also works in this case.
				
				wp_update_user(array('ID' =>$getUser->user_id,'user_login'=>$mobile_no));
				$user_data = wp_update_user( array( 'ID' =>$getUser->user_id, 'user_login' => $mobile_no,'user_email' =>$email_id));
 
				if (is_wp_error($user_data)) {
				    //There was an error; possibly this user doesn't exist.
				    $flag=0;
				    $message='Email is already exist!.';
				} else {
				    // Success!
					$SQL = "SELECT * FROM th_installer_data WHERE installer_data_id = '$installer_id'";		
			        $installer = $wpdb->get_row($SQL);
			        if($installer){
							$current_user = get_user_by( 'id', $installer->user_id); // 54 is a user ID
					//	var_dump($current_user);
					$user_id = $current_user->ID;
					$name = $current_user->display_name;
					$number =  $current_user->user_login;
					$email_id =  $current_user->user_email;
					
					if (\strpos($email_id, 'test') !== false) {
					     $flag =  'true';
					}else{
						 $flag =  'false';
					}
					$profile=array();
					$profile['service_center']=array(
						'center_name'=>$installer->business_name,
						'store_phone'=>$installer->store_phone,
						'address'=>$installer->address,
						'lattitude'=>$installer->location_lattitude,
						'longitude'=>$installer->location_longitude			
					);
					$profile['primary_ontact']=array(
						'contact_person_name'=>$installer->contact_person,
						'mobile_no'=>$installer->contact_no,
						'email'=>(!empty($email_id))? $email_id :'',
					);
					$profile['additional_information']=array(
						'gst_no'=>(!empty($installer->gst_no))? $installer->gst_no :'',
						'company_name'=>(!empty($installer->company_name)) ? $installer->company_name : '',
						'company_add'=>(!empty($installer->company_add)) ? $installer->company_add : '');
					}
					update_user_meta($installer->user_id, 'cmp_name', $installer->company_name);
		            update_user_meta($installer->user_id, 'cmp_add', $installer->company_add);
	            	update_user_meta($installer->user_id, 'first_name',$installer->contact_person);
				   $flag=1;
				}
			}else{
			 $flag=0;
			  $message='Mobile Number is already exist!.';
			}
		
		if($flag !=0 ){
			$respond[ 'message' ] = 'Successfully';
			$respond[ 'data' ] = $profile;
		}else{
			$respond[ 'status' ] = 'error';
			$respond[ 'message' ] = $message;
			$respond[ 'data' ] = (object) array();	
		}
	return $respond;
	}
public	function getServicesList() {
		global $json_api, $wpdb, $woocommerce;
		
		$installer_id = $json_api->query->installer_id;
		if (!$installer_id) {
			$json_api->error( "You must include a 'installer_id' var in your request." );
		}
		
		$SQLUser="SELECT * FROM th_installer_data WHERE installer_data_id='".$installer_id."'";
		$getUser=$wpdb->get_row($SQLUser);
		$SQL="SELECT * FROM wp_users WHERE ID!='".$getUser->user_id."' AND user_login='".$mobile_no."'";
		$checkUser=$wpdb->get_row($SQL);
			$fc_sql = "SELECT * from th_installer_facilities where type = 'f'";
	        $fc_data = $wpdb->get_results($fc_sql);
	        $sfc_sql = $wpdb->get_row("SELECT meta_value from th_installer_meta where installer_id = '$installer_id' and meta_name = 'facilities'");
		        $sfc_sql_arr = unserialize($sfc_sql->meta_value); 
		       
	        $facilities = array();
	        foreach ($fc_data as $key => $fc_row)
	        {
	            $name = $fc_row->name;
	            $f_id = $fc_row->f_id;
	            $facilities[$key]['id']=$f_id;
	            $facilities[$key]['name']=$name;
	            $facilities[$key]['is_checked']=(in_array($f_id, $sfc_sql_arr)) ? 1 : 0;
	           
	        }
		$fc_sql = "SELECT * from th_service_data where as_flag =1 AND status=1";
        $services_data = $wpdb->get_results($fc_sql);
        $SQL="SELECT service_data_id from th_installer_addi_service where installer_id = '$installer_id'";
	        $sas_sql_arr = $wpdb->get_results($SQL);                        
	       	$servdata=array();
	        foreach ($sas_sql_arr as $key => $seleservice){
	        	$servdata[]=$seleservice->service_data_id;
	        }
        $services = array();
        foreach ($services_data as $key => $service){
        	$services[$key]['id']=$service->service_data_id;
	        $services[$key]['name']=$service->service_name;
	        $services[$key]['is_checked']=(in_array($service->service_data_id,$servdata)) ? 1 : 0;
        }
		$faciServiList['facilities']=$facilities;
		$faciServiList['services']=$services;
		$respond[ 'message' ] = 'Successfully';
		$respond[ 'data' ] = $faciServiList;
	return $respond;
	die;
	}
	public	function updateServices() {
		global $json_api,$wpdb;
		$installer_id=$json_api->query->installer_id;
		
		if (!$installer_id) {
			$json_api->error( "You must include a 'installer_id' var in your request." );
		}
		
		$facilities=explode(',',$json_api->query->facilities);
		if(!empty($facilities)) {
            $facility_arr = serialize($facilities);
        }
    	 $fccount = $wpdb->get_var("SELECT COUNT(*) from th_installer_meta WHERE installer_id = '$installer_id' and meta_name = 'facilities'");
			if($fccount != 0){
				 $wpdb->query("UPDATE th_installer_meta SET meta_value = '$facility_arr' WHERE installer_id = '$installer_id' and meta_name = 'facilities'");
			}
			else{
				$insert = $wpdb->insert('th_installer_meta', array( 
                'installer_id' => $installer_id,
                'meta_name' => 'facilities',
                'meta_value' => $facility_arr
                 ));
			}	
			global $wpdb;
	        $wpdb->query('DELETE  FROM th_installer_addi_service WHERE installer_id = "'.$installer_id.'"');
	        
	        $services=explode(',',$json_api->query->services);
	        if($services){
		        foreach ($services as $key => $value) {
		            $insert = $wpdb->insert('th_installer_addi_service', array( 
		                'installer_id' => $installer_id,
		                'service_data_id' =>$value
		            ));
		        }
	        }
	        $SQLUser="SELECT * FROM th_installer_data WHERE installer_data_id='".$installer_id."'";
		$getUser=$wpdb->get_row($SQLUser);
		$SQL="SELECT * FROM wp_users WHERE ID!='".$getUser->user_id."' AND user_login='".$mobile_no."'";
		$checkUser=$wpdb->get_row($SQL);
			$fc_sql = "SELECT * from th_installer_facilities where type = 'f'";
	        $fc_data = $wpdb->get_results($fc_sql);
	        $sfc_sql = $wpdb->get_row("SELECT meta_value from th_installer_meta where installer_id = '$installer_id' and meta_name = 'facilities'");
		        $sfc_sql_arr = unserialize($sfc_sql->meta_value); 
		        
	        $facilities = array();
	        foreach ($fc_data as $key => $fc_row)
	        {
	            $name = $fc_row->name;
	            $f_id = $fc_row->f_id;
	            $facilities[$key]['id']=$f_id;
	            $facilities[$key]['name']=$name;
	            $facilities[$key]['is_checked']=(in_array($f_id, $sfc_sql_arr)) ? 1 : 0;
	           
	        }
		$fc_sql = "SELECT * from th_service_data where as_flag =1 AND status=1";
        $services_data = $wpdb->get_results($fc_sql);
        $SQL="SELECT service_data_id from th_installer_addi_service where installer_id = '$installer_id'";
	        $sas_sql_arr = $wpdb->get_results($SQL);                        
	       	$servdata=array();
	        foreach ($sas_sql_arr as $key => $seleservice){
	        	$servdata[]=$seleservice->service_data_id;
	        }
        $services = array();
        foreach ($services_data as $key => $service){
        	$services[$key]['id']=$service->service_data_id;
	        $services[$key]['name']=$service->service_name;
	        $services[$key]['is_checked']=(in_array($service->service_data_id,$servdata)) ? 1 : 0;
        }
		$faciServiList['facilities']=$facilities;
		$faciServiList['services']=$services;
		$respond[ 'message' ] = 'Successfully';
		$respond[ 'data' ] = $faciServiList;
	return $respond;
	}
	public	function generate_auth_cookie() {
		global $json_api;
		if ( !$json_api->query->username ) {
			$json_api->error( "You must include a 'username' var in your request." );
		}
		if ( !$json_api->query->password ) {
			$json_api->error( "You must include a 'password' var in your request." );
		}
		if ( $json_api->query->seconds )$seconds = ( int )$json_api->query->seconds;
		else $seconds = 1209600; //14 days
		$user = wp_authenticate( $json_api->query->username, $json_api->query->password );
		if ( is_wp_error( $user ) ) {
			remove_action( 'wp_login_failed', $json_api->query->username );
			$json_api->error( "Invalid username and/or password.", 'error', '401' );
		}
		$expiration = time() + apply_filters( 'auth_cookie_expiration', $seconds, $user->ID, true );
		$cookie = wp_generate_auth_cookie( $user->ID, $expiration, 'logged_in' );
		preg_match( '|src="(.+?)"|', get_avatar( $user->ID, 32 ), $avatar );
		return array(
			"cookie" => $cookie,
			"cookie_name" => LOGGED_IN_COOKIE,
			"user" => array(
				"id" => $user->ID,
				"username" => $user->user_login,
				"nicename" => $user->user_nicename,
				"email" => $user->user_email,
				"url" => $user->user_url,
				"registered" => $user->user_registered,
				"displayname" => $user->display_name,
				"firstname" => $user->user_firstname,
				"lastname" => $user->last_name,
				"nickname" => $user->nickname,
				"description" => $user->user_description,
				"capabilities" => $user->wp_capabilities,
				"avatar" => $avatar[ 1 ]
			),
		);
	}
	public	function get_currentuserinfo() {
		global $json_api;
		if ( !$json_api->query->cookie ) {
			$json_api->error( "You must include a 'cookie' var in your request. Use the `generate_auth_cookie` Auth API method." );
		}
		$user_id = wp_validate_auth_cookie( $json_api->query->cookie, 'logged_in' );
		if ( !$user_id ) {
			$json_api->error( "Invalid authentication cookie. Use the `generate_auth_cookie` Auth API method." );
		}
		$user = get_userdata( $user_id );
		preg_match( '|src="(.+?)"|', get_avatar( $user->ID, 32 ), $avatar );
		return array(
			"user" => array(
				"id" => $user->ID,
				"username" => $user->user_login,
				"nicename" => $user->user_nicename,
				"email" => $user->user_email,
				"url" => $user->user_url,
				"registered" => $user->user_registered,
				"displayname" => $user->display_name,
				"firstname" => $user->user_firstname,
				"lastname" => $user->last_name,
				"nickname" => $user->nickname,
				"description" => $user->user_description,
				"capabilities" => $user->wp_capabilities,
				"avatar" => $avatar[ 1 ]
			)
		);
	}
public	function instaCustomerRegister() {
		global $json_api,$wpdb,$woocommerce;
		$user_id=$json_api->query->user_id;
		$installer_id=$json_api->query->installer_id;		
		$first_name=$json_api->query->first_name;
		$last_name=$json_api->query->last_name;
		$mobile=$json_api->query->mobile;
		$email=$json_api->query->email;
		$vehicle_type=$json_api->query->vehicle_type;
		/*if (!$user_id) {
			$json_api->error( "You must include a 'user_id' var in your request." );
		}*/
		if (!$installer_id) {
			$json_api->error( "You must include a 'installer_id' var in your request." );
		}
		if (!$first_name) {
			$json_api->error( "You must include a 'first_name' var in your request." );
		}
		if (!$last_name) {
			$json_api->error( "You must include a 'last_name' var in your request." );
		}
		if (!$mobile) {
			$json_api->error( "You must include a 'mobile' var in your request." );
		}
		if (!$vehicle_type) {
			$json_api->error( "You must include a 'vehicle_type' var in your request." );
		}
		
		$otp = rand(100000,999999);
		if (!username_exists($mobile))
		{
			
			if(!email_exists($email)){
				$userdata = array (
				'user_login' =>$mobile,
				'user_pass' =>$mobile,
				'user_email' =>$email,
				'role' => 'customer',
				'user_nicename' =>$first_name.' '.$last_name,
				'first_name' =>$first_name,
				'last_name'=>$last_name,
				'display_name' =>$first_name.' '.$last_name,
				'nickname' =>$first_name.' '.$last_name,
			);
			$new_user_id = wp_insert_user( $userdata );
			update_user_meta( $new_user_id, '_active', 0 );
			update_user_meta( $new_user_id, 'vehicle_type',$vehicle_type);
			update_user_meta( $new_user_id, 'custom_mobile', sanitize_text_field($mobile));
			update_user_meta( $new_user_id, 'franchise_id',$user_id);
    		update_user_meta( $new_user_id, 'referral_type','installer');
			$message = "We have receive your request for registration your otp is ".$otp." Thank You Tyrehub Team";
			$message = str_replace(' ', '%20', $message);
			sms_send_to_customer($message,$mobile,$templateID=1);

			
			$update = $wpdb->get_results("UPDATE `wp_users` SET otp = '$otp' WHERE ID = '$new_user_id'");
			$SQL="SELECT * FROM th_installer_data WHERE installer_data_id='".$installer_id."'";
			$insta=$wpdb->get_row($SQL);
			$wpdb->insert('th_customer_register',array (
					'user_id' => $new_user_id,
					'parent_id' =>$insta->user_id,
					'installer_id' =>$installer_id,
					'first_name' =>$first_name,
					'last_name'=>$last_name,
					'mobile' =>$mobile,
					'email' =>$email,
					'campaing_name' =>'installer',
					'vehicle_type'=>$vehicle_type
					));
				$flag=1;
				$message='Successfully';
			}else{	
				$flag=0;
				$message='Email already exist!';
			}
			
		}else{
			$flag=0;
			$message='Mobile/Username already exist!';
			
		}
		if($flag !=0 ){
			$respond[ 'status' ] = 'ok';
			$respond[ 'message' ] = $message;
		}else{
			$respond[ 'status' ] = 'error';
			$respond[ 'message' ] = $message;
		
		}
	return $respond;
	die;
	}
	public function customerRegisterOTPVerify()
	{
		global $json_api,$wpdb,$woocommerce;
		$verify_otp = $json_api->query->verify_otp;
		$user_id = $json_api->query->user_id;
		
		if (!$verify_otp) {
			$json_api->error( "You must include a 'verify_otp' var in your request." );
		}
		
		
		$result = $wpdb->get_row("SELECT * from `wp_users` where otp = '$verify_otp'");
		//print_r($result);
		if($result){
			 $flag=1;
			 $update = $wpdb->get_results("UPDATE `th_customer_register` SET is_verify =1 WHERE user_id = '$result->ID'");
			 update_user_meta($result->ID, '_active',1);
			$message = "Dear ".$result->display_name.", Thank you for Registering, Claim your discount voucher worth of Rs.100 with tyrehub.com use coupon code FIRST100.";
			$message = str_replace(' ', '%20', $message);

			sms_send_to_customer($message,$result->user_login,$templateID=1);

		}
		else{
			$flag=0;
		}
		if($flag !=0 ){
			$respond[ 'status' ] = 'ok';
			$respond[ 'message' ] = 'OTP is Verified';
		}else{
			$respond[ 'status' ] = 'error';
			$respond[ 'message' ] = 'OTP is Not Verified';
		
		}
	return $respond;
			die();
	}
public function customerLinkShare()
	{
		global $json_api,$wpdb,$woocommerce;
		$share_mobile = $json_api->query->mobile;
		$installer_id = $json_api->query->installer_id;
		if (!$share_mobile) {
			$json_api->error( "You must include a 'mobile' var in your request." );
		}
		if (!$installer_id) {
			$json_api->error( "You must include a 'installer_id' var in your request." );
		}
		
		
		$SQL="SELECT * FROM th_installer_data WHERE installer_data_id='".$installer_id."'";
		$insta=$wpdb->get_row($SQL);
		
		$shareLink=site_url('create-new-account/?uid='.$insta->user_id.'&instaid='.$insta->installer_id);
		///$shorlink=get_short_url_api($shareLink);
		$ch = curl_init();
		$timeout = 5;
		curl_setopt($ch,CURLOPT_URL,'http://tinyurl.com/api-create.php?url='.urlencode($shareLink));
		curl_setopt($ch,CURLOPT_RETURNTRANSFER,1);
		curl_setopt($ch,CURLOPT_CONNECTTIMEOUT,$timeout);
		$shorlink = curl_exec($ch);
		curl_close($ch);
		//gets the data from a URL
		$message = "Hurry! Register with Gujarats NO.1 Car Tyre and Service selling portal www.Tyrehub.com Claim your discount voucher worth of Rs.100. to buy any brand Tyre or Alignment and Balancing services on discounted price. To Register Click ".$shorlink;
		$message = str_replace(' ', '%20', $message);


		sms_send_to_customer($message,$share_mobile,$templateID=1);

		$respond[ 'status' ] = 'ok';
		$respond[ 'message' ] = 'Register link sent ot customer mobile number!';
	return $respond;
	die();
	}
	public function get_short_url_api($url)  {
		$ch = curl_init();
		$timeout = 5;
		curl_setopt($ch,CURLOPT_URL,'http://tinyurl.com/api-create.php?url='.urlencode($url));
		curl_setopt($ch,CURLOPT_RETURNTRANSFER,1);
		curl_setopt($ch,CURLOPT_CONNECTTIMEOUT,$timeout);
		$data = curl_exec($ch);
		curl_close($ch);
		return $data;
	}
public	function readyToInstall() {
		global $json_api, $wpdb, $woocommerce;
		if ( !$json_api->query->order_id) {
			$json_api->error( "You must include a 'order_id' var in your request." );
		}
		$order_id=$json_api->query->order_id;
		$order = new WC_Order($order_id); 
	$order_status = $order->get_status();
	if($order_status!='deltoinstaller'){
		if (!empty($order)) {				
			
			$order->update_status('deltoinstaller');
			$respond[ 'message' ] = 'Successfully';
			$respond[ 'data' ] = (object) array();
		
		}else{
			$respond[ 'status' ] = 'error';
			$respond[ 'message' ] = 'Ready to install status not change';
			//$respond[ 'data' ] = array();
		
		}
	}else{
			sms_after_deltoinstaller($order_id);
			$respond[ 'message' ] = 'Successfully';
			$respond[ 'data' ] = (object) array();
	}
		
	return $respond;
	}

	public	function get_make() {
		global $json_api, $wpdb, $woocommerce;
		if ($json_api->query->make_id) {
			$make_data = $wpdb->get_results("SELECT * FROM th_make where make_id='".$json_api->query->make_id."' AND vehicle_type = '1' AND status =1 order by make_name asc");
		}else{
			$make_data = $wpdb->get_results("SELECT * FROM th_make where vehicle_type = '1' AND status =1 order by make_name asc");
		}
		

			$respond[ 'message' ] = 'Successfully';
			$respond[ 'data' ] = $make_data;
		
	return $respond;
	}

	public	function get_model() {
		global $json_api, $wpdb, $woocommerce;
		if ( !$json_api->query->make_id) {
			$json_api->error( "You must include a 'make_id' var in your request." );
		}
		$model_data = $wpdb->get_results("SELECT * FROM th_model where make_rid=".$json_api->query->make_id." order by model_name asc");
		
		if($model_data){
			$respond[ 'message' ] = 'Successfully';
			$respond[ 'data' ] = $model_data;
		}else{
			$respond[ 'message' ] = 'Successfully';
			$respond[ 'data' ] = (object) array();
		}
			
		
	return $respond;
	}

	public	function get_submodel() {
		global $json_api, $wpdb, $woocommerce;
		if ( !$json_api->query->model_id) {
			$json_api->error( "You must include a 'model_id' var in your request." );
		}
		$submodel_data = $wpdb->get_results("SELECT * FROM th_submodel where model_rid=".$json_api->query->model_id." order by submodel_name asc");
		if($submodel_data){
			$respond[ 'message' ] = 'Successfully';
			$respond[ 'data' ] = $submodel_data;
		}else{
			$respond[ 'message' ] = 'Successfully';
			$respond[ 'data' ] = (object) array();
		}

		
	return $respond;
	}

	public	function car_details_save() {
		global $json_api, $wpdb, $woocommerce;
		if ( !$json_api->query->order_id) {
			$json_api->error( "You must include a 'order_id' var in your request." );
		}

		if ( !$json_api->query->user_id) {
			$json_api->error( "You must include a 'user_id' var in your request." );
		}
		if ( !$json_api->query->make_id) {
			$json_api->error( "You must include a 'make_id' var in your request." );
		}
		if ( !$json_api->query->model_id) {
			$json_api->error( "You must include a 'model_id' var in your request." );
		}
		if ( !$json_api->query->submodel_id) {
			$json_api->error( "You must include a 'submodel_id' var in your request." );
		}
		if ( !$json_api->query->car_number) {
			$json_api->error( "You must include a 'car_number' var in your request." );
		}
		
			$table = 'th_vehicle_details';
			$data = array('order_id' =>$json_api->query->order_id,
			 'user_id' => $json_api->query->user_id,
			 'make' =>$json_api->query->make_id,
			 'model' =>$json_api->query->model_id,
			 'submodel' =>$json_api->query->submodel_id,
			 'car_number' =>$json_api->query->car_number,
			 'insert_date' => date('Y-m-d'));
			$wpdb->insert($table,$data);
			$my_id = $wpdb->insert_id;

		$respond[ 'message' ] = 'Successfully';
		$respond[ 'data' ] = (object) array();

		
	return $respond;
	}
	public	function car_details_page_save() {
		global $json_api, $wpdb, $woocommerce;
		if ( !$json_api->query->order_id) {
			$json_api->error( "You must include a 'order_id' var in your request." );
		}

		if ( !$json_api->query->user_id) {
			$json_api->error( "You must include a 'user_id' var in your request." );
		}
		if ( !$json_api->query->make_id) {
			$json_api->error( "You must include a 'make_id' var in your request." );
		}
		if ( !$json_api->query->model_id) {
			$json_api->error( "You must include a 'model_id' var in your request." );
		}
		if ( !$json_api->query->submodel_id) {
			$json_api->error( "You must include a 'submodel_id' var in your request." );
		}
		if ( !$json_api->query->car_number) {
			$json_api->error( "You must include a 'car_number' var in your request." );
		}
		if ( !$json_api->query->tyre_info_id) {
			$json_api->error( "You must include a 'tyre_info_id' var in your request." );
		}
		if (!$json_api->query->serial_number) {
			$json_api->error( "You must include a 'serial_number' var in your request." );
		}
		
		$serial_number=explode(',',$json_api->query->serial_number);
		$tyre_info_id=explode(',',$json_api->query->tyre_info_id);
	$table = 'th_vehicle_details';

	$SQL="SELECT * FROM th_vehicle_details WHERE order_id='".$json_api->query->order_id."'";
	$vehicle=$wpdb->get_row($SQL);
	$data = array('order_id' =>$json_api->query->order_id,
		 'user_id' =>$json_api->query->user_id,
		 'make' =>$json_api->query->make_id,
		 'model' =>$json_api->query->model_id,
		 'submodel' =>$json_api->query->submodel_id,
		 'car_number' =>$json_api->query->car_number,
		 'odo_meter' => $odo_meter,
		 'insert_date' => date('Y-m-d'));

	if($vehicle){
		
		$wpdb->update($table,$data,array('order_id' =>$json_api->query->order_id));
		$my_id = $vehicle->id;
	}else{
		$wpdb->insert($table,$data);
		$my_id = $wpdb->insert_id;
	}
	
	$SQL="SELECT * FROM th_vehicle_tyre_information WHERE order_id='".$json_api->query->order_id."'";
	$tyreInfo=$wpdb->get_results($SQL);
	if($tyreInfo){
		foreach ($serial_number as $key => $value) {
			$data = array(
			 'vehicle_details_id' =>$my_id,
			 'order_id' =>$json_api->query->order_id,
			 'user_id' =>$json_api->query->user_id,
			 'serial_number' => $value,
			 'insert_date' => date('Y-m-d'));
			$wpdb->update('th_vehicle_tyre_information',$data,array('id' =>$tyre_info_id[$key]));
			
		}		
	}else{
		foreach ($serial_number as $key => $value) {
		$data = array(
		 'vehicle_details_id' =>$my_id,
		 'order_id' =>$json_api->query->order_id,
		 'user_id' =>$json_api->query->user_id,
		 'serial_number' =>$value,
		 'insert_date' => date('Y-m-d'));
		$wpdb->insert('th_vehicle_tyre_information',$data);
		}
	}
		$respond[ 'message' ] = 'Successfully';
		$respond[ 'data' ] = (object) array();

		
	return $respond;
	}
}
