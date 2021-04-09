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


			$product_array= array(23731,23732);

			if(in_array($value['product_id'],$product_array)){
				$tax=($value['line_total']*18)/118;
				$sgst=($tax / 2);
			    $cgst=($tax / 2);
			}else{
				$tax=($value['line_total']*28)/128;
				$sgst=($tax / 2);
			    $cgst=($tax / 2);
			}

			$data_to_be_inserted = array(
				array('order_item_id'   => $order_item_id,'meta_key'  => '_product_id','meta_value'=> $value['product_id'],),
			 	array('order_item_id'   => $order_item_id,'meta_key'  => '_variation_id','meta_value'=> $value['variation_id'],),
				array('order_item_id'   => $order_item_id,'meta_key'  => '_qty','meta_value'=> $value['product_quantity'],),
				array('order_item_id'   => $order_item_id,'meta_key'  => '_line_subtotal','meta_value'=> $value['line_subtotal'],),
				array('order_item_id'   => $order_item_id,'meta_key'  => '_line_total','meta_value'=> $value['line_total'],),
				array('order_item_id'   => $order_item_id,'meta_key'  => '_sgst','meta_value'=> $sgst,),
				array('order_item_id'   => $order_item_id,'meta_key'  => '_cgst','meta_value'=>$cgst,),
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


			$ch1 = curl_init();
			//$message = "We have received your request for ".$type.", Our team will contact you soon. Thank You Tyrehub Team";
			//$message = "New Order: Order No: ".$invoice_number." for Tyre item Is successfully placed by ".$billing_first_name.' '.$billing_last_nam.".";
			$big_invo_url = admin_url().'admin-ajax.php?action=offline_order_pdf&document_type=offline-invoice&order_ids='.$order_id.'&service_id='.$order_id.'&_wpnonce=04e74a5779';

			$invo_short_url = get_short_url($big_invo_url);
	$message = "Confirmed: Order is successfully placed, order number: ".$invoice_number." , to download invoice click here ".$invo_short_url." Track by login in or call to 18002335551";


			$message = str_replace(' ', '%20', $message);
			$url_string="http://nimbusit.co.in/api/swsend.asp?username=t1ankitshah&password=81957032&sender=TYREHB&sendto=91".$billing_phone."&message=".$message;
			curl_setopt($ch1, CURLOPT_URL, $url_string);
			curl_setopt($ch1, CURLOPT_RETURNTRANSFER, 1);
			curl_setopt($ch1, CURLOPT_CUSTOMREQUEST, "GET");
			$result1 = curl_exec($ch1);
			curl_close ($ch1);

			$to1 = $billing_email;
			$from1 = 'info@tyrehub.com';
			$subject1 = 'Tyrehub Place order';
		    $headers1[] = 'From: Tyrehub Offline Purchase <'.$from1.'>';
		    $headers1[] = 'Content-Type: text/html; charset=UTF-8';
		    // Compose a simple HTML email message
			//$message1 = 'Thank you for getting in touch! We appreciate your interest for franchise with Tyre Hub. Our team will contact you soon. Have a great day!';

					$order_number=$invoice_number;
					$limit =1;
					$user_id = get_current_user_id();
				    $SQL="SELECT * FROM th_installer_data WHERE user_id='".$user_id."' AND is_franchise='yes'";
				    $franchise=$wpdb->get_row($SQL);
					$franchise_id = $franchise->installer_data_id;
					$row = $wpdb->get_row("SELECT *, foi.order_item_id as itemid FROM wp_franchises_order as fo,wp_franchise_order_items as foi  where fo.order_id = foi.order_id AND fo.order_number='$order_number' AND franchise_id = '$franchise_id' ORDER BY fo.order_id DESC LIMIT 0,$limit");

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




$message1 .= '<table id="template_container" style="background-color:#ffffff; border:1px solid #dedede;border-radius:3px" width="600" cellspacing="0" cellpadding="0" border="0">
   		<tbody>
		      <tr>
		         <td valign="top" align="center">
		            <table id="template_header" style="background-color:#474494;color:#ffffff;border-bottom:0;font-weight:bold;line-height:100%;vertical-align:middle;font-family:&quot;Helvetica Neue&quot;,Helvetica,Roboto,Arial,sans-serif;border-radius:3px 3px 0 0" width="600" cellspacing="0" cellpadding="0" border="0">
		               <tbody>
		                  <tr>
		                     <td id="header_wrapper" style="display:block;padding:15px 48px">
		                        <div id="template_header_image">
		                           <p style="margin-top:0"><img src="https://www.tyrehub.com/wp-content/uploads/2018/09/2018-08-17.png" alt="Tyrehub" style="border:none;display:inline-block;font-size:14px;font-weight:bold;height:auto;outline:none;text-decoration:none;text-transform:capitalize;vertical-align:middle;margin-right:10px;width:150px" class="CToWUd"></p>
		                        </div>
		                        <h1 style="font-family:&quot;Helvetica Neue&quot;,Helvetica,Roboto,Arial,sans-serif;font-size:30px;font-weight:300;line-height:150%;margin:0;text-align:left;color:#ffffff">Thank you for your order</h1>

		                     </td>
		                     <td>
		                        <h2 style="display:block;font-family:&quot;Helvetica Neue&quot;,Helvetica,Roboto,Arial,sans-serif;font-size:18px;font-weight:bold;line-height:130%;margin:0 0 18px;text-align:left;color:#fff">Order Confirmation</h2>
		                        Order #25738
		                     </td>
		                  </tr>
		               </tbody>
		            </table>
		         </td>
		      </tr>';

$message1 .='<tr>
         <td valign="top" align="center">
            <table id="template_body" width="600" cellspacing="0" cellpadding="0" border="0">
               <tbody>
                  <tr>
                     <td id="body_content" style="background-color:#ffffff" valign="top">
                        <table width="100%" cellspacing="0" cellpadding="20" border="0">
                           <tbody>
                              <tr>
                                 <td style="padding:48px 48px 0" valign="top">
                                    <div id="body_content_inner" style="color:#636363;font-family:&quot;Helvetica Neue&quot;,Helvetica,Roboto,Arial,sans-serif;font-size:14px;line-height:150%;text-align:left">
                                       <p style="margin:0 0 16px">Hi '.$row->billing_first_name.' '.$row->billing_last_name.',</p>
                                       <p style="margin:0 0 16px">
                                         Thank you for your order. We’ll send a confirmation when your order ships. Your estimated delivery date is indicated below. If you would like to view the status of your order or make any changes to it, please visit  Your Orders on Tyrehub.com.
                                       </p>
                                       <h2 style="color:#474494;display:block;font-family:&quot;Helvetica Neue&quot;,Helvetica,Roboto,Arial,sans-serif;font-size:18px;font-weight:bold;line-height:130%;margin:0 0 18px;text-align:left">Order Details<br></h2>
                                       <div>Order #'.$invoice_number.'</div>
                                       <div>Placed on '.$order_date.'</div>
                                       <div style="margin-bottom:40px;margin-top:10px">';

					foreach ($product_array as $key => $value) {

							//$product_id = $value['product_id'];
				            //$product_variation = new WC_Product_Variation( $product_id );

							$product_id = $value['product_id'];

							$product_array= array(get_option("car_wash"),get_option("balancing_alignment"));
							if(in_array($product_id,$product_array)){

								//$product = wc_get_product($product_id);
								if($product_id==get_option("balancing_alignment")){
									 $variation_des = 'Balancing & Alignment';
								}else{
									 $variation_des = 'Car Wash';
								}


							}else{
								$product_variation = new WC_Product_Variation($product_id);
								$variation_des = $product_variation->get_description();

							}

				            $SQL="SELECT * FROM th_franchise_cart_item_services WHERE product_id='".$product_id."' AND order_id='$invoice_number'";
							$services=$wpdb->get_results($SQL);
							$product_id = $value['product_id'];
				            $product   = wc_get_product( $product_id );
							$image_id  = $product->get_image_id();
							$image_url = wp_get_attachment_image_url( $image_id, 'full' );
						ob_start();	
						$message1 .='<div style="width:100%;float:left;border:none;margin-bottom:10px">
                                             <div style="width:39%;float:left;border:none;height:150px;overflow:hidden">
                                                <img src='.$image_url.' style="border:none;display:inline-block;font-size:14px;font-weight:bold;height:auto;outline:none;text-decoration:none;text-transform:capitalize;vertical-align:middle;margin-right:10px" width="150px" class="CToWUd a6T" tabindex="0">
                                                <div class="a6S" dir="ltr" style="opacity: 0.01;">
                                                   <div id=":10l" class="T-I J-J5-Ji aQv T-I-ax7 L3 a5q" title="Download" role="button" tabindex="0" aria-label="Download attachment " data-tooltip-class="a1V">
                                                      <div class="aSK J-J5-Ji aYr"></div>
                                                   </div>
                                                </div>
                                             </div>
                                             <div style="color:#636363;vertical-align:middle;width:60%;border:none;float:left">
                                                <div style="width:70%;border:none;margin-bottom:10px;float:left">
                                                  '.$variation_des.'
                                                   <div style="color:#636363;vertical-align:middle;border:none">
                                                      <b> Qty: '.$value['qty'].'</b>
                                                   </div>
                                                </div>
                                                <div style="color:#636363;width:30%;float:left;border:none;text-align:left;vertical-align:middle;font-family:Helvetica Neue,Helvetica,Roboto,Arial,sans-serif">
                                                   <span><span><i></i></span>'.wc_price($value['_line_total']).'</span>
                                                </div>
                                                <div style="width:70%;float:left;margin-bottom:10px">
                                                  <div>';
                                                  foreach ($services as $key => $service) {
														 $message1 .='<div>'.$service->service_name.'<i></i> '.wc_price($service->rate).'</div>';
														 $asubtotal= $asubtotal + $service->rate;
                                                   }

                                                   $message1 .='</div>
                                                </div>
                                                <div style="width:30%;border:none;float:left">
                                                   <i></i>
                                                </div>
                                             </div>
                                  </div>';
                               $subtotal= $subtotal + $value['_line_subtotal'];
                            }


$message1 .='<table style="color:#636363;border:1px solid #e5e5e5;vertical-align:middle;width:100%;font-family:Helvetica Neue,Helvetica,Roboto,Arial,sans-serif" cellspacing="0" cellpadding="6" border="1">
              <thead></thead>
                    <tbody></tbody>
                                 <tfoot>
                                    <tr>
                                       <th scope="row" colspan="2" style="color:#636363;border:1px solid #e5e5e5;vertical-align:middle;padding:12px;text-align:left;border-top-width:4px">Subtotal:
                                       </th>
                                       <td style="color:#636363;border:1px solid #e5e5e5;vertical-align:middle;padding:12px;text-align:left;border-top-width:4px">
                                          <del></del> <ins><span><span><i></i></span>'.wc_price($row->sub_total).'</span></ins>
                                       </td>
                                    </tr>
                                    <tr>
                                       <th scope="row" colspan="2" style="color:#636363;border:1px solid #e5e5e5;vertical-align:middle;padding:12px;text-align:left">Payment method:
                                       </th>
                                       <td style="color:#636363;border:1px solid #e5e5e5;vertical-align:middle;padding:12px;text-align:left">
                                       	'. $payment_title.'
                                       </td>
                                    </tr>
                                 	<tr>
                                       <th scope="row" colspan="2" style="color:#636363;border:1px solid #e5e5e5;vertical-align:middle;padding:12px;text-align:left">Total:
                                       </th>
                                       <td style="color:#636363;border:1px solid #e5e5e5;vertical-align:middle;padding:12px;text-align:left">
                                          <del></del> <ins><span><span><i></i></span>'.wc_price($row->total).'</span></ins>
                                       </td>
                                    </tr>
                                 </tfoot>
                              </table>
                              <p style="margin:0 0 16px">
                              </p>

                           </div>
                           <table id="addresses" style="width:100%;vertical-align:top;margin-bottom:40px;padding:0" cellspacing="0" cellpadding="0" border="0">
                              <tbody>
                                 <tr>
                                    <td style="text-align:left;font-family:Helvetica Neue,Helvetica,Roboto,Arial,sans-serif;border:0;padding:0" width="50%" valign="top">
                                       <h2 style="color:#474494;display:block;font-family:&quot;Helvetica Neue&quot;,Helvetica,Roboto,Arial,sans-serif;font-size:18px;font-weight:bold;line-height:130%;margin:0 0 18px;text-align:left">Billing info</h2>
                                       <address style="padding:12px;color:#636363;border:1px solid #e5e5e5">
                                          Ankit Shah									<br>9898999066
                                          <p style="margin:0 0 16px"><a href="mailto:savaji.webtual@gmail.com" target="_blank">savaji.webtual@gmail.com</a></p>
                                       </address>
                                    </td>
                                 </tr>
                              </tbody>
                           </table>
                           <p style="margin:0 0 16px">
                              We hope to see you again soon.
                           </p>
                        </div>
                     </td>
                  </tr>
               </tbody>
            </table>
         </td>
      </tr>
   </tbody>
</table>
</td>
</tr>';

 $message1 .= '<tr>
         <td valign="top" align="center">
            <table id="template_footer" width="600" cellspacing="0" cellpadding="10" border="0">
               <tbody>
                  <tr>
                     <td style="padding:0;border-radius:6px" valign="top">
                        <table width="100%" cellspacing="0" cellpadding="10" border="0">
                           <tbody>
                              <tr style="text-align:center">
                                 <td style="padding:0;border-radius:6px">
                                    <p>Need to make changes to your order? Visit our&nbsp;Help page&nbsp;for more information.</p>
                                    <p>We hope to see you again soon.</p>
                                    <br>
                                 </td>
                              </tr>
                              <tr>
                                 <td colspan="2" id="credit" style="border-radius:6px;border:0;font-family:&quot;Helvetica Neue&quot;,Helvetica,Roboto,Arial,sans-serif;line-height:125%;text-align:center;padding:0 48px 48px 48px;color:#474494;font-size:20px;font-weight:600" valign="middle">
                                    <h2 style="color:#474494;display:block;font-family:&quot;Helvetica Neue&quot;,Helvetica,Roboto,Arial,sans-serif;font-size:18px;font-weight:bold;line-height:130%;margin:0 0 18px;text-align:left">
                                    </h2>
                                    <p>TyreHub.com</p>
                                 </td>
                              </tr>
                           </tbody>
                        </table>
                     </td>
                  </tr>
               </tbody>
            </table>
         </td>
      </tr>
   </tbody>
</table>';


			//wp_mail( $to1, $subject1, $message1, $headers1);

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
{
		global $woocommerce , $wpdb;
		$billing_first_name = $_POST['billing_first_name'];
        $billing_last_name = $_POST['billing_last_name'];
        $billing_phone = $_POST['billing_phone'];
        $billing_email = $_POST['billing_email'];

        $otp = rand(100000,999999);
			  if ( !username_exists( $billing_phone ))
			  {
					if(!email_exists($billing_email)){
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
						$new_user_id = wp_insert_user( $userdata );
						update_user_meta( $new_user_id, '_active', 0 );
						update_user_meta( $new_user_id, 'mobile_whatsapp', $billing_phone );
						update_user_meta( $new_user_id, 'custom_mobile', sanitize_text_field( $billing_phone ) );

						$ch1 = curl_init();
						$message = "We have receive your request for registration your otp is ".$otp." Thank You Tyrehub Team";
						$message = str_replace(' ', '%20', $message);
						$url_string="http://nimbusit.co.in/api/swsend.asp?username=t1ankitshah&password=81957032&sender=TYREHB&sendto=91".$billing_phone."&message=".$message;
						curl_setopt($ch1, CURLOPT_URL, $url_string);
						curl_setopt($ch1, CURLOPT_RETURNTRANSFER, 1);
						curl_setopt($ch1, CURLOPT_CUSTOMREQUEST, "GET");

						$result1 = curl_exec($ch1);
						curl_close ($ch1);
						$update = $wpdb->get_results("UPDATE `wp_users` SET otp = '$otp' WHERE ID = '$new_user_id'");
						$result = array('result' => 'success', 'message' => 'successfually user created!','user_id' => $new_user_id);
				      	echo json_encode($result);
				   }
				   else{
				      	$result = array('result' => 'error', 'message' => 'Email already exist!');
				      	echo json_encode($result);
				    }

			  }else{

    				$user = get_userdatabylogin($billing_phone);
    				$user_info = $user->data->Id ? new WP_User( $user->data->Id ) : wp_get_current_user();
    				$first_name = $user_info->first_name;
    				$last_name = $user_info->last_name;
    				$result = array('result' => 'error', 'message' => 'Username already exist!','userdata' => $user,'first_name' => $first_name,'last_name' => $last_name);
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

	global $woocommerce , $wpdb;
	$result = $wpdb->get_row("SELECT * from `wp_users` where otp = '$otp' AND ID = '$user_id'");
	//print_r($result);
	if($result){
		 echo 1;
		 update_user_meta( $user_id, '_active',1);

			$ch1 = curl_init();
			$message = "Dear ".$result->display_name.", Thank you for Registering with tyrehub.com";
			$message = str_replace(' ', '%20', $message);
			$url_string="http://nimbusit.co.in/api/swsend.asp?username=t1ankitshah&password=81957032&sender=TYREHB&sendto=91".$result->user_login."&message=".$message;
			curl_setopt($ch1, CURLOPT_URL, $url_string);
			curl_setopt($ch1, CURLOPT_RETURNTRANSFER, 1);
			curl_setopt($ch1, CURLOPT_CUSTOMREQUEST, "GET");
			$result1 = curl_exec($ch1);
			//var_dump($result1);
			curl_close ($ch1);

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
	$order_id = $_POST['order_id'];
	$order_status = $_POST['order_status'];
    $table_name  = $wpdb->prefix."franchises_order";
	$wpdb->query( $wpdb->prepare("UPDATE $table_name
                SET status = %s
             WHERE order_id = %s",$order_status,$order_id)
    );

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
?>
