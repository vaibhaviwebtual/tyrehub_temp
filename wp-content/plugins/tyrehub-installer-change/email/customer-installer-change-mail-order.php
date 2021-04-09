
<?php
	
 
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
                              <h2 style="display:block;font-family:&quot;Helvetica Neue&quot;,Helvetica,Roboto,Arial,sans-serif;font-size:18px;font-weight:bold;line-height:130%;margin:0 0 18px;text-align:left;color:#fff">New Installer Detail</h2>
                              Order #'.$order_id.'
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
                                       <p style="margin:0 0 16px">Hello '.$name.',</p>
                                       <p style="margin:0 0 16px">
                                         Thank you for your order. Your Installer change request updated successfully! You have been assigned new Installer for your Tyre Installation or services. New voucher for your service is attached in this email.
                                       </p>
                                       <h2 style="color:#474494;display:block;font-family:&quot;Helvetica Neue&quot;,Helvetica,Roboto,Arial,sans-serif;font-size:18px;font-weight:bold;line-height:130%;margin:0 0 18px;text-align:left">Order Details<br></h2>
                                       <div>Order #'.$order_id.'</div>
                                       <div>Placed on '.$order_date.'</div>
                                       <div style="margin-bottom:40px;margin-top:10px">';
                
               foreach ($order_pro as $key => $value) {

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
                        $product_variation = wc_get_product($product_id);
                        $variation_des = $product_variation->get_description();

                     }

                     $SQLV="SELECT * FROM `th_vehicle_type`  WHERE vehicle_id='".$value['vehicle_id']."'";
                     $vehicle=$wpdb->get_row($SQLV);

                      $SQL="SELECT * FROM th_cart_item_services WHERE product_id='".$product_id."' AND order_id='$order_id'";
                     $services=$wpdb->get_results($SQL);

                     $product_id = $value['product_id'];
                        $product   = wc_get_product( $product_id );
                     $image_id  = $product->get_image_id();
                     $image_url = wp_get_attachment_image_url( $image_id, 'full' );
                  	 //ob_start(); 
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
                                                   
                                                </div>
                                                <div style="color:#636363;width:30%;float:left;border:none;text-align:left;vertical-align:middle;font-family:Helvetica Neue,Helvetica,Roboto,Arial,sans-serif">
                                                   <span><span><i></i></span></span>
                                                </div>
                                                <div style="width:70%;float:left;margin-bottom:10px">
                                                  <div>';
                                                  $message1 .='<div style="color:#636363;vertical-align:middle;border:none">
                                                      <b> Vehicle Type : '.$vehicle->vehicle_type.'</b>
                                                   </div>';
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
                               //$subtotal= $subtotal + $value['_line_subtotal'];
                            }
$message1 .='</div> <p style="margin:0 0 16px; font-weight:bold;">
	                       New Store: '.$Newinsta->business_name.' ('.$Newinsta->address.')
	                       </p>
	                       
                        </div>';
$message1 .='</div> <p style="margin:0 0 16px">
	                         If you would like to view the status of your order or make any changes to it, please visit  Your Orders on Tyrehub.com.
	                       </p>
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

