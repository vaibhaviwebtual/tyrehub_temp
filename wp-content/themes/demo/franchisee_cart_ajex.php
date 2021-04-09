<?php
// save_service_info
add_action('wp_ajax_save_franchies_info_from_cart', 'save_franchies_info_from_cart');
add_action('wp_ajax_nopriv_save_franchies_info_from_cart', 'save_franchies_info_from_cart');
function save_franchies_info_from_cart()
{
		global $woocommerce , $wpdb;
		date_default_timezone_set('Asia/Kolkata');
		$items = WC()->cart->get_cart();
		$total_cart = WC()->cart->total;
		$newsubtotal = WC()->cart->get_subtotal();



		foreach($items as $item => $values) {
			$_product =  wc_get_product( $values['data']->get_id());
            $price = get_post_meta($values['product_id'] , '_price', true);

        	$product_data[$item]['product_id'] = $values['data']->get_id();
        	$product_data[$item]['product_name'] = $_product->get_title();
        	$product_data[$item]['product_price'] = $price;
        	$product_data[$item]['product_quantity'] = $values['quantity'];
        	$product_data[$item]['line_subtotal'] = $values['line_subtotal'];
        	$product_data[$item]['line_total'] = $values['line_total'];
        	$product_data[$item]['variation'] = $values['variation'];
		}

		global $woocommerce,$wpdb;
	     $user_id = get_current_user_id();
	     //$SQL="SELECT * FROM th_installer_data WHERE user_id='".$user_id."'";
	     $SQL="SELECT * FROM th_installer_data WHERE user_id='".$user_id."' AND is_franchise='yes'";
	     $franchise=$wpdb->get_row($SQL);

		$franchise_id = $franchise->installer_data_id;
		$created_user_id = $_POST['created_user_id'];
		$billing_first_name = $_POST['billing_first_name'];
		$billing_last_name = $_POST['billing_last_name'];
		$billing_phone = $_POST['billing_phone'];
		$billing_email = $_POST['billing_email'];

		$cmp_name  = $_POST['cmp_name'];
		$cmp_add = $_POST['cmp_add'];
		$gst_no = $_POST['gst_no'];
		$gst_email = $_POST['gst_email'];
		$payment_type = $_POST['payment_type'];
		$serviceTotal=0;
		foreach(WC()->cart->get_cart() as $key => $values) {

		$SQL="SELECT SUM(rate) as servi_total FROM `th_franchise_cart_item_services` WHERE cart_item_key='".$key."' AND order_id=''";
		 $add_servi=$wpdb->get_row($SQL);
		 $serviceTotal=$serviceTotal + $add_servi->servi_total;

		 }
		//echo "<pre>";
		$items = WC()->cart->get_cart();
		$subtotal = WC()->cart->subtotal + $serviceTotal;
		$total = WC()->cart->total + $serviceTotal;
		//print_r($total);
		$table = $wpdb->prefix.'franchises_order';
		$table2 = $wpdb->prefix.'franchise_order_items';
		$table3 = $wpdb->prefix.'francise_order_itemmeta';
		//$cart_total=WC()->cart->get_cart_total();
		$order_item_type = "line_item";
		$order_item_status = 0;
		date_default_timezone_set('Asia/Kolkata');
		$order_date = date('Y-m-d H:i:s');
		$invoice_number = gen_invoice_num();
		$data = array('order_number' => $invoice_number,'customer_id' => $created_user_id, 'franchise_id' => $franchise_id, 'billing_first_name' => $billing_first_name, 'billing_last_name' => $billing_last_name, 'billing_phone' => $billing_phone, 'billing_email' => $billing_email,'cmp_name' => $cmp_name,'cmp_add' => $cmp_add,'gst_no' => $gst_no,'gst_email' => $gst_email, 'total' => $total_cart, 'sub_total' => $newsubtotal,'status' => $order_item_status, 'date_completed' => $order_date,'payment_method'=>$payment_type);
		$wpdb->insert($table,$data);
		$order_id = $wpdb->insert_id;
		
		$SQL="SELECT * FROM $table  WHERE order_id='$order_id'";
		$get_order_no=$wpdb->get_row($SQL);


		foreach(WC()->cart->get_cart() as $key => $values) {
$update = $wpdb->query("UPDATE th_franchise_cart_item SET order_id = '$get_order_no->order_number' WHERE cart_item_key = '$key' AND order_id=0");
		$SQL="UPDATE th_franchise_cart_item_services SET order_id = '$get_order_no->order_number' WHERE cart_item_key = '$key' AND order_id=0";
		$update = $wpdb->query($SQL);


		 }

		foreach ($product_data as $key => $value) {

				$data = array('order_item_name' => $value['product_name'], 'order_item_type' => 'line_item', 'order_id' => $order_id);
				$wpdb->insert($table2,$data);
				$order_item_id = $wpdb->insert_id;


			//$product_array= array(23731,23732);
			$product_array= array(get_option("balancing_alignment"), get_option("car_wash"));
			$tyreGST = get_option('tyre_gst');
			 $tyreGSTD = (100 + $tyreGST);
			 $serviceGST = get_option('service_gst');
			 $serviceGSTD = (100 + $serviceGST);

			if(in_array($value['product_id'],$product_array)){
				$tax=($value['line_total'] * $serviceGST) / $serviceGSTD;
				$sgst=($tax / 2);
			    $cgst=($tax / 2);
			}else{
				$tax=($value['line_total']*$tyreGST)/$tyreGSTD;
				$sgst=($tax / 2);
			    $cgst=($tax / 2);
			}

			$parent_id = wp_get_post_parent_id($value['product_id']);
			$guarantee_text = get_post_meta($parent_id, '_guarantee_cart', true );

			$data_to_be_inserted = array(
				array('order_item_id'   => $order_item_id,'meta_key'  => '_product_id','meta_value'=> $value['product_id'],),
			 	array('order_item_id'   => $order_item_id,'meta_key'  => '_variation_id','meta_value'=> $value['variation_id'],),
				array('order_item_id'   => $order_item_id,'meta_key'  => '_qty','meta_value'=> $value['product_quantity'],),
				array('order_item_id'   => $order_item_id,'meta_key'  => '_line_subtotal','meta_value'=> $value['line_subtotal'],),
				array('order_item_id'   => $order_item_id,'meta_key'  => '_line_total','meta_value'=> $value['line_total'],),
				array('order_item_id'   => $order_item_id,'meta_key'  => '_sgst','meta_value'=> $sgst,),
				array('order_item_id'   => $order_item_id,'meta_key'  => '_cgst','meta_value'=>$cgst,),
				array('order_item_id'   => $order_item_id,'meta_key'  => 'tyre_gst','meta_value'=>$tyreGST,),
				array('order_item_id'   => $order_item_id,'meta_key'  => 'service_gst','meta_value'=>$serviceGST,),
				array('order_item_id'   => $order_item_id,'meta_key'  => 'guarantee_text','meta_value'=>$guarantee_text,),
				array('order_item_id'   => $order_item_id,'meta_key'  => '_discount','meta_value'=>0,),
				array('order_item_id'   => $order_item_id,'meta_key'  => '_service_taxable','meta_value'=>0,),
				array('order_item_id'   => $order_item_id,'meta_key'  => '_discount_rules','meta_value'=>0,),
				array('order_item_id'   => $order_item_id,'meta_key'  => '_discount_rules_amount','meta_value'=>0,),
				array('order_item_id'   => $order_item_id,'meta_key'  => '_installer_discount','meta_value'=>0,),
				array('order_item_id'   => $order_item_id,'meta_key'  => 'pa_width','meta_value'=> $value['variation']['attribute_pa_width'],),
				array('order_item_id'   => $order_item_id,'meta_key'  => 'pa_ratio','meta_value'=> $value['variation']['attribute_pa_ratio'],),
				array(
						'order_item_id'   => $order_item_id,
						'meta_key'  => 'pa_diameter',
						'meta_value'=> $value['variation']['attribute_pa_diameter'],
					),
				array(
						'order_item_id'   => $order_item_id,
						'meta_key'  => 'pa_tyre-type',
						'meta_value'=> $value['variation']['attribute_pa_tyre-type'],
					),
				array('order_item_id'   => $order_item_id,'meta_key'  => 'pa_brand','meta_value'=> $value['variation']['attribute_pa_brand'],),
				array(
						'order_item_id'   => $order_item_id,
						'meta_key'  => 'pa_vehicle-type',
						'meta_value'=> $value['variation']['attribute_pa_vehicle-type'],
					),
				array(
						'order_item_id'   => $order_item_id,
						'meta_key'  => 'vehicle_name',
						'meta_value'=> $value['variation']['vehicle_name'],
					)


			);


				 if(count($data_to_be_inserted) > 0) {
    					foreach($data_to_be_inserted as $key  =>  $data) {

    						$data = array('order_item_id' => $order_item_id, 'meta_key' => $data['meta_key'],'meta_value' => $data['meta_value']);
							$wpdb->insert($table3,$data);
    					}
    				}

    			// Offline Car Services Save
    				$product_array= array(get_option('car_wash'),get_option('balancing_alignment'));
    				if(in_array($value['product_id'],$product_array)){

		    			$dataServices = array(
		    						  'customer_id' => $created_user_id,
		    						  'cart_item_key' => $key,
		    						  'product_id' => $value['product_id'],
		    						  'vehicle_id' => $value['variation']['vehicle_id'],
		    						  'franchise_id' => $franchise_id,
		    						  'service_data_id' => $value['variation']['service_data_id'],
		    						  'voucher_name' => $value['variation']['voucher_name'],
		    						  'rate' =>$value['variation']['services_price'],
		    						  'order_id'=>$order_id,
		    						  'message' => '',
		    						  'status' =>'completed',
		    						  'completed_date' =>date('Y-m-d H:i:s'),
		    						  'paid' => 'yes'
		    						 );

						$wpdb->insert('th_offline_car_services',$dataServices);
					}
				//echo $wpdb->last_query;
			}


			//$message = "We have received your request for ".$type.", Our team will contact you soon. Thank You Tyrehub Team";
			//$message = "New Order: Order No: ".$invoice_number." for Tyre item Is successfully placed by ".$billing_first_name.' '.$billing_last_nam.".";
			$upload_dir = wp_get_upload_dir();			
			$upload_base = trailingslashit($upload_dir['baseurl']);
			
			$big_invo_url = $upload_base.'wpo_wcpdf/attachments/offline-invoice-'.$order_id.'.pdf';

			$invo_short_url = get_short_url($big_invo_url);

	/*$message = "Confirmed: Order is successfully placed, your order number: ".$invoice_number.", you will receive the Invoice in your email. You can also request a printed copy at our Frenchies Counter and your invoice download click this link ".$invo_short_url.". If you need more help call to 18002335551";*/

	$message = "Confirmed: Order is successfully placed, your order number: ".$invoice_number.", you will receive the Invoice in your email. You can also request a printed copy at our Frenchies Counter. If you need more help call to 18002335551";

			 $message = trim(preg_replace('/\s+/', ' ', $message));
			  $message = str_replace( array('&'), 'and', $message);
			$message = str_replace(' ', '%20', $message);
			sms_send_to_customer($message,$billing_phone,$templateID=1);

			
			$to1 = $billing_email;
			$from1 = 'sales@tyrehub.com';
			$subject1 = 'Tyrehub Place order';
		   

					$order_number=$invoice_number;
					$limit =1;
					$user_id = get_current_user_id();
				    $SQL="SELECT * FROM th_installer_data WHERE user_id='".$user_id."' AND is_franchise='yes'";
				    $franchise=$wpdb->get_row($SQL);
					$franchise_id = $franchise->installer_data_id;
					$row = $wpdb->get_row("SELECT *, foi.order_item_id as itemid FROM wp_franchises_order as fo,wp_franchise_order_items as foi  where fo.order_id = foi.order_id AND fo.order_number='$order_number' AND franchise_id = '$franchise_id' ORDER BY fo.order_id DESC LIMIT 0,$limit");

					$SQL="SELECT * FROM wp_franchises_payment_method WHERE id='$row->payment_method'";
						$payment=$wpdb->get_row($SQL);
						$payment_title=$payment->payment_method;

					$od_meta_id = $row->itemid;
					$od_order_id = $row->order_id;
					$total = $row->total;
					$payment_method = $row->payment_method;
					


					$order_meta_product = $wpdb->get_results("SELECT * FROM wp_franchise_order_items as oi, wp_francise_order_itemmeta as om where oi.order_id = '$od_order_id' and om.order_item_id = oi.order_item_id");
					$p_count = count($order_meta_product);

					$product_array = array();
					$qty_array = array();

					foreach ($order_meta_product as $key => $value) {

						if($value->meta_key == '_product_id')
						{
							$product_array[$value->order_item_id]['product_id'] = $value->meta_value;
						}
						if($value->meta_key == '_qty')
						{
							$product_array[$value->order_item_id]['qty'] = $value->meta_value;
						}
						if($value->meta_key == '_line_subtotal')
						{
							$product_array[$value->order_item_id]['_line_subtotal'] = $value->meta_value;
						}
						if($value->meta_key == '_line_total')
						{
							$product_array[$value->order_item_id]['_line_total'] = $value->meta_value;
						}
						if($value->meta_key == '_sgst')
						{
							$product_array[$value->order_item_id]['_sgst'] = $value->meta_value;
						}
						if($value->meta_key == '_cgst')
						{
							$product_array[$value->order_item_id]['_cgst'] = $value->meta_value;
						}

					}
					$product_array = array_values($product_array);

				$SQL="SELECT * FROM th_franchise_cart_item_services WHERE order_id='".$order_number."'";
				$services=$wpdb->get_results($SQL);
				ob_start();
				 
 				$headers = "From: Tyrehub Place order <sales@tyrehub.com>" . "\r\n";
 				$headers .= "MIME-Version: 1.0\r\n";
        		//$headers .= "Content-Type: text/html; charset=ISO-8859-1\r\n";
        		$headers.= 'Content-type: text/html; charset="UTF-8' . "\r\n";

				 include(get_stylesheet_directory() . '/templates/offline_email_template.php'); //Template File Path

/*				 	$document_type = sanitize_text_field('offline-invoice');
				    $local_order=$order_id;
				 	$order_ids = (array) array_map( 'absint', explode( 'x', $local_order ) );
				    $order_ids = array_reverse( $order_ids );
				    $filename=$document_type.'-'.$local_order.'.pdf';
    
			   try {
			            $document = wcpdf_get_document( $document_type, $order_ids, true );

			            if ( $document ) {
			                $output_format = WPO_WCPDF()->settings->get_output_format( $document_type );

			                switch ( $output_format ) {
			                    case 'html':
			                        add_filter( 'wpo_wcpdf_use_path', '__return_false' );
			                        $document->output_html();
			                        break;
			                    case 'pdf':

			                    default:
			                        if ( has_action( 'wpo_wcpdf_created_manually' ) ) {
			                            do_action( 'wpo_wcpdf_created_manually', $document->get_pdf(), $filename);
			                        }
			                        $output_mode = WPO_WCPDF()->settings->get_output_mode($document_type);
			                        $output_mode ='inline';
			                        $upload_dir = wp_upload_dir();
			                        $upload_base = trailingslashit( $upload_dir['basedir'] );
			                        $tmp_base = $upload_base . 'wpo_wcpdf/attachments/';

			                        $tmp_path = $tmp_base;
			                        // get pdf data & store
			                        $pdf_data = $document->get_pdf();
			                        $filename = $filename;
			                       $pdf_path = $tmp_path . $filename;
			                       
			                        file_put_contents ($pdf_path, $pdf_data );

			                       //$document->output_offline_pdf($output_mode,$filename);

			                }
			            } else {
			                wp_die( sprintf( __( "Document of type '%s' for the selected order(s) could not be generated", 'woocommerce-pdf-invoices-packing-slips' ), $document_type ) );
			            }
			        } catch ( \Dompdf\Exception $e ) {
			            $message = 'DOMPDF Exception: '.$e->getMessage();
			            wcpdf_log_error( $message, 'critical', $e );
			            wcpdf_output_error( $message, 'critical', $e );
			        } catch ( \Exception $e ) {
			            $message = 'Exception: '.$e->getMessage();
			            wcpdf_log_error( $message, 'critical', $e );
			            wcpdf_output_error( $message, 'critical', $e );
			        } catch ( \Error $e ) {
			            $message = 'Fatal error: '.$e->getMessage();
			            wcpdf_log_error( $message, 'critical', $e );
			            wcpdf_output_error( $message, 'critical', $e );
			        }
      

					$upload_dir = wp_get_upload_dir();			
					$upload_base = trailingslashit($upload_dir['basedir']);
			
				 $tmp_base = $upload_base.'wpo_wcpdf/attachments/offline-invoice-'.$order_id.'.pdf';	
				 $attachments = array($tmp_base);*/
				 ob_get_contents();
				 ob_end_clean();

				 wp_mail($to1,$subject1,$message1,$headers);
				 //wp_mail($to1,$subject1,$message1,$headers,$attachments);
			
			global $woocommerce;
			//echo json_encode($returnData);
			WC()->cart->empty_cart();

			unset($_SESSION['fran_user_id']);
			unset($_SESSION['cust_type']);
			unset($_SESSION['mobile_no']);

			echo site_url('/thank-you/?order_id='.base64_encode($get_order_no->order_number));
	die();
}

function do_insert($place_holders, $values) {

    global $wpdb;

	$query = "INSERT INTO wp_francise_order_itemmeta (`order_item_id`, `meta_key`, `meta_value`) VALUES ";
    $query .= implode( ', ', $place_holders );
    $sql = $wpdb->prepare( "$query ", $values );

    if ( $wpdb->query( $sql ) ) {

        return true;
    } else {
        return false;
    }

}

add_action('wp_ajax_save_create_account_frachise', 'save_create_account_frachise');
add_action('wp_ajax_nopriv_save_create_account_frachise', 'save_create_account_frachise');
function save_create_account_frachise()
{		session_start();
		global $woocommerce , $wpdb;
		$billing_first_name = $_POST['billing_first_name'];
        $billing_last_name = $_POST['billing_last_name'];
        $billing_phone = $_POST['billing_phone'];
        $billing_email = $_POST['billing_email'];

        $otp = rand(100000,999999);
			  if (!username_exists( $billing_phone ))
			  {
					if(!email_exists($billing_email)){
					      

						/*$ch1 = curl_init();
						$message = "We have receive your request for registration your otp is ".$otp." Thank You Tyrehub Team";
						$message = str_replace(' ', '%20', $message);
						$url_string="http://nimbusit.co.in/api/swsend.asp?username=t1ankitshah&password=81957032&sender=TYREHB&sendto=91".$billing_phone."&message=".$message;
						curl_setopt($ch1, CURLOPT_URL, $url_string);
						curl_setopt($ch1, CURLOPT_RETURNTRANSFER, 1);
						curl_setopt($ch1, CURLOPT_CUSTOMREQUEST, "GET");

						$result1 = curl_exec($ch1);
						curl_close ($ch1);*/
						/*$update = $wpdb->get_results("UPDATE `wp_users` SET otp = '$otp' WHERE ID = '$new_user_id'");*/
						/*$result = array('result' => 'success', 'message' => 'successfually user created!','user_id' => $new_user_id);*/
						//$_SESSION['OTP_CHECK']=$otp;
						$userdata = array (
					      'user_login' =>$billing_phone,
					      'user_pass' =>$billing_phone,
					      'user_email' =>$billing_email,
					      'role' => 'customer',
					      'user_nicename' =>$billing_first_name.' '.$billing_last_name,
					      'first_name' =>$billing_first_name,
					      'last_name'=>$billing_last_name,
					      'display_name' =>$billing_first_name.' '.$billing_last_name,
					      'nickname' =>$billing_first_name.' '.$billing_last_name,
					    );
						$new_user_id = wp_insert_user($userdata);

						update_user_meta( $new_user_id, '_active', 1);
						update_user_meta( $new_user_id, 'mobile_whatsapp', $billing_phone );
						update_user_meta( $new_user_id, 'custom_mobile', sanitize_text_field( $billing_phone ) );

						update_user_meta( $new_user_id, 'franchise_id',get_current_user_id());
						update_user_meta( $new_user_id, 'register_date',date('Y-m-d'));

						 //echo $new_user_id;
						 //update_user_meta( $user_id, '_active',1);

							$message = "Dear ".$billing_first_name.' '.$billing_last_name.", Thank you for Registering with tyrehub.com";
							$message = str_replace(' ', '%20', $message);
							sms_send_to_customer($message,$billing_phone,$templateID=1);

							

						$result = array('result' => 'success', 'message' => 'successfually user created!','user_id'=>$new_user_id);

				      	echo json_encode($result);
				   }else{
				      	$result = array('result' => 'error', 'message' => 'Email already exist!');
				      	echo json_encode($result);
				    }

			  }else{

    				$user = get_userdatabylogin($billing_phone);
    				$user_info = $user->data->Id ? new WP_User( $user->data->Id ) : wp_get_current_user();
    				$first_name = $user_info->first_name;
    				$last_name = $user_info->last_name;
    				$result = array('result' => 'error', 'message' => 'Mobile number is already registered.','userdata' => $user,'first_name' => $first_name,'last_name' => $last_name);
					echo json_encode($result);
  			  }

  	die();
}

add_action('wp_ajax_verify_otp_chk', 'verify_otp_chk');
add_action('wp_ajax_nopriv_verify_otp_chk', 'verify_otp_chk');
function verify_otp_chk()
{

				$otp = $_POST['otp'];
				$user_id = $_POST['user_id'];

				$billing_first_name = $_POST['billing_first_name'];
		        $billing_last_name = $_POST['billing_last_name'];
		        $billing_phone = $_POST['billing_phone'];
		        $billing_email = $_POST['billing_email'];



	//
						
	//

	global $woocommerce , $wpdb;
	$result = $wpdb->get_row("SELECT * from `wp_users` where otp = '$otp' AND ID = '$user_id'");
	//print_r($result);
	if($_SESSION['OTP_CHECK']==$otp){
		$userdata = array (
					      'user_login' =>$billing_phone,
					      'user_pass' =>$billing_phone,
					      'user_email' =>$billing_email,
					      'role' => 'customer',
					      'user_nicename' =>$billing_first_name.' '.$billing_last_name,
					      'first_name' =>$billing_first_name,
					      'last_name'=>$billing_last_name,
					      'display_name' =>$billing_first_name.' '.$billing_last_name,
					      'nickname' =>$billing_first_name.' '.$billing_last_name,
					    );
						$new_user_id = wp_insert_user($userdata);

						update_user_meta( $new_user_id, '_active', 1);
						update_user_meta( $new_user_id, 'mobile_whatsapp', $billing_phone );
						update_user_meta( $new_user_id, 'custom_mobile', sanitize_text_field( $billing_phone ) );

						update_user_meta( $new_user_id, 'franchise_id',get_current_user_id());
						update_user_meta( $new_user_id, 'register_date',date('Y-m-d'));

		 echo $new_user_id;
		 //update_user_meta( $user_id, '_active',1);

			$message = "Dear ".$billing_first_name.' '.$billing_last_name.", Thank you for Registering with tyrehub.com";
			$message = str_replace(' ', '%20', $message);
			sms_send_to_customer($message,$billing_phone,$templateID=1);

			unset($_SESSION['OTP_CHECK']);

	}
	else{
		echo 0;
	}
die();
}

add_action('wp_ajax_change_the_offline_status', 'change_the_offline_status');
add_action('wp_ajax_nopriv_change_the_offline_status', 'change_the_offline_status');
function change_the_offline_status()
{

	global $wpdb;
	extract($_POST);
	$order_id = $_POST['order_id'];
	$order_status = $_POST['order_status'];
    $table_name  = $wpdb->prefix."franchises_order";
	$wpdb->query("UPDATE $table_name  SET status ='".$order_status."' WHERE  order_number = '".$order_id."'");


//Testing
	if($order_status==2){
			
			$table = 'th_vehicle_details';
			$data = array('order_id' =>$order_id,
				 'user_id' => $user_id,
				 'make' => $make,
				 'model' =>$model,
				 'submodel' =>$sub_modal,
				 'car_number' => $car_number,
				 'odo_meter' => $odo_meter,
				 'franchise_id' => $franchise_id,
				 'order_type' =>1,
				 'insert_date' => date('Y-m-d'));
				$wpdb->insert($table,$data);
				$my_id = $wpdb->insert_id;

			foreach ($serial_number as $key => $value) {
				$data1 = array(
				 'vehicle_details_id' =>$my_id,
				 'order_id' => $order_id,
				 'user_id' =>$user_id,
				 'serial_number' =>$value,
				 'insert_date' => date('Y-m-d'));
				$wpdb->insert('th_vehicle_tyre_information',$data1);
				}

			offline_order_mail_and_invoice_send($order_id);
	}	
	


	die();
}
add_action('wp_ajax_change_order_pending_status', 'change_order_pending_status');
add_action('wp_ajax_nopriv_change_order_pending_status', 'change_order_pending_status');
function change_order_pending_status()
{

	global $woocommerce , $wpdb;
	$order_id = $_POST['order_id'];
	$order_status = $_POST['order_status'];
    $order = new WC_Order($order_id);
 	if (!empty($order)) {
    	$order->update_status( 'completed' );
	}

	die();
}


add_action( 'init', 'gen_invoice_num' );
function gen_invoice_num(){
		  global $wpdb;
          $row = $wpdb->get_results("SELECT * FROM wp_franchises_order ORDER BY order_id DESC limit 1");
			$initial_invoice_num = ($row == null) ? date('Y').'0000' : $row[0]->order_number;
            $expNum = substr($initial_invoice_num,0,4);
            $newstring = substr($initial_invoice_num,4,8);
			if($expNum<date('Y')){
               $nextInvoiceNumber = date('Y').'0001';
             }else{
                //$nextInvoiceNumber = $expNum.($newstring+1);
                 $len=max(strlen($newstring), strlen(1));
                 $nextInvoiceNumber=str_pad($expNum.$newstring+1, $len, '0', STR_PAD_LEFT);
             }

         return $nextInvoiceNumber;
    }


add_action( 'init', 'GetImageUrlsByProductId' );
function GetImageUrlsByProductId( $productId){

	$product = new WC_product($productId);
	$attachmentIds = $product->get_gallery_attachment_ids();
	$imgUrls = array();
	foreach( $attachmentIds as $attachmentId )
	{
		$imgUrls[] = wp_get_attachment_url( $attachmentId );
	}

	return $imgUrls;
}
add_action( 'wp_ajax_offline_order_delete', 'offline_order_delete' );
add_action( 'wp_ajax_nopriv_offline_order_delete', 'offline_order_delete' );
function offline_order_delete() {
	global $woocommerce , $wpdb;
	extract($_POST);
	$table_name = $wpdb->prefix.'franchises_order';

	$wpdb->update( $table_name, array( 'is_deleted' => 1, 'deleted_reason' =>$data['deleted_reason']),array('order_id'=>$data['order_id']));
	echo $order_id;
die;
}
add_action( 'wp_ajax_offline_admin_order_delete', 'offline_admin_order_delete' );
add_action( 'wp_ajax_nopriv_offline_admin_order_delete', 'offline_admin_order_delete' );
function offline_admin_order_delete() {
	global $woocommerce , $wpdb;
	extract($_POST);
	$table_name = $wpdb->prefix.'franchises_order';

	$wpdb->update( $table_name, array( 'is_deleted' => 2),array('order_id'=>$data['order_id']));
	echo $order_id;
die;
}

add_action( 'wp_ajax_demo_load_my_posts', 'demo_load_my_posts' );
add_action( 'wp_ajax_nopriv_demo_load_my_posts', 'demo_load_my_posts' );
function demo_load_my_posts() {
       
    				global $woocommerce , $wpdb;
					$franchise_id = get_current_user_id();
					$user_id = get_current_user_id();
					$SQL="SELECT * FROM th_installer_data WHERE user_id='".$user_id."' AND is_franchise='yes'";
					$franchise=$wpdb->get_row($SQL);
					$franchise_id=$franchise->installer_data_id;
   	$msg = '';   
    if( isset( $_POST['data']['page'] ) ){
        // Always sanitize the posted fields to avoid SQL injections
        $page = sanitize_text_field($_POST['data']['page']); // The page we are currently at
        //$name = sanitize_text_field($_POST['data']['th_name']); // The name of the column name we want to sort
        $name = 'order_id';
        $sort = sanitize_text_field($_POST['data']['th_sort']); // The order of our sort (DESC or ASC)
        $cur_page = $page;
        $page -= 1;
        $per_page = 10; // Number of items to display per page
        $previous_btn = true;
        $next_btn = true;
        $first_btn = true;
        $last_btn = true;
        $start = $page * $per_page;
       	// The table we are querying from  
        $posts = $wpdb->prefix . "franchises_order";
       
        $where_search = '';
       
      	if( ! empty( $_POST['data']['search']) )
      	{
          	$where_search = 'AND (billing_phone LIKE "%%' . $_POST['data']['search'] . '%%" OR order_number LIKE "%%' . $_POST['data']['search'] . '%%" OR billing_first_name LIKE "%%' . $_POST['data']['search'] . '%%" OR billing_last_name LIKE "%%' . $_POST['data']['search'] . '%%") ';
        }
		if($_POST['data']['search_type'] == 'status')
        {
        	if($_POST['data']['th_name']){
        		if($_POST['data']['th_name']==1){
        		$where_search .= ' AND (status='.$_POST['data']['th_name'].' OR status=0)';
	        	}else{
	        		$where_search .= ' AND (status='.$_POST['data']['th_name'].')';	
	        	}
        	}
        	
        	
        }
        if($_POST['data']['search_type'] == 'deleted')
        {
        	$where_search .= ' AND is_deleted=1 ';
        }else{
        	$where_search .= ' AND is_deleted=0 ';
        }
     	
     	//echo "SELECT * FROM $posts where franchise_id = '$franchise_id' $where_search ORDER BY $name $sort LIMIT $start, $per_page";

     	//echo "SELECT * FROM $posts where franchise_id = '$franchise_id' $where_search ORDER BY $name $sort LIMIT $start, $per_page";

        $SQL="SELECT * FROM $posts where franchise_id = '$franchise_id' $where_search ORDER BY $name $sort LIMIT $start, $per_page";
        $all_posts = $wpdb->get_results($SQL);
       

       	$count = $wpdb->get_var($wpdb->prepare("SELECT COUNT(order_id) FROM " . $posts . " where is_deleted=0 AND franchise_id = '$franchise_id' $where_search", array() ) );

					if($_POST['data']['search_type'] == 'deleted'){
                    	$title='Delete Reason';
                    }else{
                    	$title='Status';
                    }
       
       	if( $all_posts ):
            $msg .= '<table class = "table table-striped table-hover table-file-list shop_table shop_table_responsive my_account_orders">';
            $msg .= '<tr>
						<th>Order.No</th>
						<th>Name</th>
						<th>Phone</th>
						<th>Date</th>
						<th>'.$title.'</th>
						<th>Total</th>
						<th class="action">Action</th>
					</tr>';

           foreach( $all_posts as $key => $post ):
           	$date_done = date("d-M-Y", strtotime($post->date_completed));
           	$siturl = site_url('/thank-you/?order_id='.base64_encode($post->order_number));
           	/*$pdfurl= admin_url().'/admin-ajax.php?action=offline_order_pdf&document_type=offline-invoice&order_ids='.$post->order_id.'&service_id='.$post->order_id.'&_wpnonce=04e74a5779';*/
           	$pdfurl=site_url().'/pdf-view/?document_type=offline-invoice&order_ids='.$post->order_id.'&service_id='.$post->order_id;
           	$selected = '';
           	//if($post->status == 1 || $post->status == 0){ $selected = "selected"; } 
           	//if ($post->status == 2) { $selected = "selected"; } 


           	 $SQLQTY="SELECT SUM(foim.meta_value) as total_qty FROM wp_franchises_order as fo LEFT JOIN wp_franchise_order_items as foi ON foi.order_id=fo.order_id LEFT JOIN wp_francise_order_itemmeta as foim ON foim.order_item_id=foi.order_item_id WHERE fo.order_number='".$post->order_number."' AND  foim.meta_key='_qty'";

           	$getQTY=$wpdb->get_row($SQLQTY);
           	$array1 = array('1' => 'Pending','2' => 'Completed');
           	
           	  $qryforgetservicetid = "SELECT * FROM wp_franchises_order as wfo 
            LEFT JOIN th_offline_car_services as ocs ON    ocs.`order_id` = wfo.`order_id` where wfo.`order_number` = '".$post->order_number."' and wfo.`franchise_id` = '".$post->franchise_id."'";
            
            $getsertypeid=$wpdb->get_row($qryforgetservicetid);

             $getProductId = "SELECT foim.meta_value as product_id FROM wp_franchise_order_items as foi 
            LEFT JOIN wp_francise_order_itemmeta as foim ON    foim.`order_item_id` = foi.`order_item_id` where foi.`order_id` = '".$post->order_id."' AND (foim.meta_key='_product_id' OR foim.meta_key='_variation_id')";
            
            $getProID=$wpdb->get_row($getProductId);
            $vehicle_type=get_post_meta($getProID->product_id,'attribute_pa_vehicle-type',true);
            if($vehicle_type=='two-wheeler'){
            	$vehicle_type_id=2;
            }else{
				$vehicle_type_id=1;
            }

                $msg .= '
                <tr class="order" id="odr'.$post->order_id.'">
                    <td>' .$post->order_number.'</a></td>
                    <td>' .$post->billing_first_name.' '.$post->billing_last_name.'</td>
                    <td>' .$post->billing_phone.'</td>
                    <td>' .$date_done.'</td>
                    <td>';
                    if($_POST['data']['search_type'] == 'deleted'){
                    	$msg .= $post->deleted_reason;
                    }else{
	                    $msg .= '<select name="status_changes" class="status_ch" data-service-type-id="'.$getsertypeid->service_data_id.'" data-qty="'.$getQTY->total_qty.'" data-sel="'.$post->order_id.'" data-order="'.$post->order_number.'" data-customer-id="'.$post->customer_id.'" data-franchise_id="'.$post->franchise_id.'" id="statuschanges_'. $post->order_id.'" data-vehicle-type="'.$vehicle_type_id.'">';
						/*foreach ($array1 as $key => $value) {
							if($post->status==0){
								$key=1;
							}else{
								$key= $key;
							}
							if($post->status == $key){ 	$selected = 'selected'; } else { $selected = " "; }
								$msg .= '<option value="'.$key.'" '.$selected.'>'.$value.'</option>';
							
						}*/

						  if($post->status== 0  || $post->status== 1){
								$selected1 = 'selected';
								$selected2 = '';
							}else{
								$selected2 = 'selected';
								$selected1 = '';
							}

						$msg .= '<option value="1" '.$selected1.'>Pending</option>';
						$msg .= '<option value="2" '.$selected2.'>Completed</option>';
					}		
					$msg .= '</select></td>
                    <td>' .wc_price($post->total).'</td>
                    <td class="action">
                    			<a href="javascript:void();" class="ord-delete" data-order="'.$post->order_id.'">
								<i class="fa fa-trash" aria-hidden="true"></i>
								</a>
								<a href="'.$pdfurl.'">
								<i class="fa fa-file-pdf-o" aria-hidden="true"></i>
								</a>								
								<a href="'.$siturl.'">
									<i class="fa fa-eye" aria-hidden="true"></i>
					</a></td>
                </tr>';        
            endforeach;
         	$msg .= '</table>';
       	// If the query returns nothing, we throw an error message
        else:
            $msg .= '<p class = "bg-danger">No posts matching your search criteria were found.</p>';
        endif;
		$msg = "<div class='cvf-universal-content'>" . $msg . "</div><br class = 'clear' />";
       	$no_of_paginations = ceil($count / $per_page);
		if ($cur_page >= 7) {
            $start_loop = $cur_page - 3;
            if ($no_of_paginations > $cur_page + 3)
                $end_loop = $cur_page + 3;
            else if ($cur_page <= $no_of_paginations && $cur_page > $no_of_paginations - 6) {
                $start_loop = $no_of_paginations - 6;
                $end_loop = $no_of_paginations;
            } else {
                $end_loop = $no_of_paginations;
            }
        } else {
            $start_loop = 1;
            if ($no_of_paginations > 7)
                $end_loop = 7;
            else
                $end_loop = $no_of_paginations;
        }
        if($count > 10) {
        $pag_container .= "
        <div class='cvf-universal-pagination offvb'>
            <ul>";

        if ($first_btn && $cur_page > 1) {
            $pag_container .= "<li p='1' class='active'>First</li>";
        } else if ($first_btn) {
            $pag_container .= "<li p='1' class='inactive'>First</li>";
        }

        if ($previous_btn && $cur_page > 1) {
            $pre = $cur_page - 1;
            $pag_container .= "<li p='$pre' class='active'>Previous</li>";
        } else if ($previous_btn) {
            $pag_container .= "<li class='inactive'>Previous</li>";
        }
        for ($i = $start_loop; $i <= $end_loop; $i++) {

            if ($cur_page == $i)
                $pag_container .= "<li p='$i' class = 'selected' >{$i}</li>";
            else
                $pag_container .= "<li p='$i' class='active'>{$i}</li>";
        }
       
        if ($next_btn && $cur_page < $no_of_paginations) {
            $nex = $cur_page + 1;
            $pag_container .= "<li p='$nex' class='active'>Next</li>";
        } else if ($next_btn) {
            $pag_container .= "<li class='inactive'>Next</li>";
        }

        if ($last_btn && $cur_page < $no_of_paginations) {
            $pag_container .= "<li p='$no_of_paginations' class='active'>Last</li>";
        } else if ($last_btn) {
            $pag_container .= "<li p='$no_of_paginations' class='inactive'>Last</li>";
        }

        $pag_container = $pag_container . "
            </ul>
        </div>";
    }
       
        echo
        '<div class = "cvf-pagination-content">' . $msg . '</div>' .
        '<div class = "cvf-pagination-nav">' . $pag_container . '</div>';
       }
  	   exit();
 }
 add_action( 'wp_ajax_ready_to_install', 'ready_to_install' );
add_action( 'wp_ajax_nopriv_ready_to_install', 'ready_to_install' );
function ready_to_install() {
	global $woocommerce , $wpdb;
	$data=$_POST['data'];
	$order = new WC_Order($data['order_id']);
	$order->update_status('deltoinstaller');
	echo $data['order_id'];
	die;
}

add_action( 'wp_ajax_get_make_model_by_vehicle', 'get_make_model_by_vehicle' );
add_action( 'wp_ajax_nopriv_get_make_model_by_vehicle', 'get_make_model_by_vehicle' );
function get_make_model_by_vehicle() {
	global $woocommerce , $wpdb;
	extract($_POST);
        if(!isset($_GET['modifysearch'])) {
            unset($_SESSION['make_id']);
            unset($_SESSION['model_id']);
            unset($_SESSION['sub_model_id']);
        }
        global $wpdb , $woocommerce;
        $SQL="SELECT * FROM th_make where vehicle_type='$vehicle_type' AND status =1 order by make_name asc";
        $make_data = $wpdb->get_results($SQL,ARRAY_A);

        /*foreach ($make_data as $data) {
            $make_id = $data->make_id;
            $make_name = $data->make_name;
        }*/
        echo json_encode($make_data);
        die;
}
add_action('wp_ajax_carwash_offline_order_complated_without_popup_status', 'carwash_offline_order_complated_without_popup_status');
add_action('wp_ajax_nopriv_carwash_offline_order_complated_without_popup_status', 'carwash_offline_order_complated_without_popup_status');
function carwash_offline_order_complated_without_popup_status()
{

	global $wpdb;
	extract($_POST);
	$order_id = $_POST['order_id'];
	$order_status = $_POST['order_status'];
    $table_name  = $wpdb->prefix."franchises_order";
	$wpdb->query("UPDATE $table_name  SET status ='".$order_status."' WHERE  order_number = '".$order_id."'");


//Testing
	if($order_status==2){
			
			$table = 'th_vehicle_details';
			$data = array('order_id' =>$order_id,
				 'user_id' => $user_id,
				 'make' => $make,
				 'model' =>$model,
				 'submodel' =>$sub_modal,
				 'car_number' => $car_number,
				 'odo_meter' => $odo_meter,
				 'franchise_id' => $franchise_id,
				 'order_type' =>1,
				 'insert_date' => date('Y-m-d'));
				$wpdb->insert($table,$data);
				$my_id = $wpdb->insert_id;

			foreach ($serial_number as $key => $value) {
				$data1 = array(
				 'vehicle_details_id' =>$my_id,
				 'order_id' => $order_id,
				 'user_id' =>$user_id,
				 'serial_number' =>$value,
				 'insert_date' => date('Y-m-d'));
				$wpdb->insert('th_vehicle_tyre_information',$data1);
				}

			offline_order_mail_and_invoice_send($order_id);
	}	
	


	die();
}
?>
