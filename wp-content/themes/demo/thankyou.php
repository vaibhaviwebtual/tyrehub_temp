<?php
/**
 * Thankyou page
 *
 * This template can be overridden by copying it to yourtheme/woocommerce/checkout/thankyou.php.
 *
 * HOWEVER, on occasion WooCommerce will need to update template files and you
 * (the theme developer) will need to copy the new files to your theme to
 * maintain compatibility. We try to do this as little as possible, but it does
 * happen. When this occurs the version of the template file will be bumped and
 * the readme will list any important changes.
 *
 * @see         https://docs.woocommerce.com/document/template-structure/
 * @author      WooThemes
 * @package     WooCommerce/Templates
 * @version     3.2.0
 */

if ( ! defined( 'ABSPATH' ) ) {
    exit;
}
?>
<?php
    global $wpdb , $woocommerce;
    $SQL="SELECT * FROM th_installer_data WHERE is_franchise='yes' AND user_id='".get_current_user_id()."'";
    $franchise=$wpdb->get_row($SQL);
     $tyreGST = get_option('tyre_gst');
     $tyreGSTD = (100 + $tyreGST);
     $serviceGST = get_option('service_gst');
     $serviceGSTD = (100 + $serviceGST);
    /* ------------------   Sms code ------------------ */
    unset($_SESSION['make_id']);
    unset($_SESSION['model_id']);
    unset($_SESSION['sub_model_id']);
    $order_data = $order->get_data();
    $order_status = $order->get_status();
   $payment_method = $order->get_payment_method();
    $item_data = $order->get_items();
    /*echo '<pre>';
    print_r($item_data);*/

    $user = $order->get_user();

$user_idd = $user->ID;
$user_role = $user->roles[0];

if($user_role == "Installer"){
    $installer = "SELECT * FROM th_installer_data WHERE user_id = '$user_idd'";
    $row = $wpdb->get_results($installer);
    foreach ($row as $key => $value)
    {
        $business_name = $value->business_name;
    }
}

    $order_id = $order_data['id'];
    $first_name = $order_data['billing']['first_name'];
    $last_name = $order_data['billing']['last_name'];
    if($order_status == 'on-hold' || $order_status == 'processing' || $order_status == 'customprocess' || $payment_method == 'cod' || $payment_method == 'pos' || $payment_method == 'bacs' || $payment_method == 'wallet')
    {


        if($franchise){
            $order = wc_get_order($order_id);

             $deposite=$order->get_total();

        $franchise_id=$franchise->installer_data_id;
         $SQL="SELECT * FROM th_franchise_payment WHERE 1=1 AND order_id='$order_id' AND franchise_id = '$franchise_id'  ORDER BY id DESC LIMIT 0,1";
        $balanceChk = $wpdb->get_row($SQL);

        if($balanceChk<=0){
        $SQL="SELECT * FROM th_franchise_payment WHERE 1=1 AND  franchise_id = '$franchise_id'  ORDER BY id DESC LIMIT 0,1";
        $balance = $wpdb->get_row($SQL);

 
        if($balance){
            $description='Payment debited for order - ' .$order_id;
             $data=array(
                        'franchise_id' => $franchise_id,
                        'order_id'  => $order_id,
                        'user_id'  => $franchise->user_id,
                        'transaction_details'=>$description,
                        'amount' =>$deposite,
                        'tran_type' => 'dr',
                        'close_balance' => ($balance->close_balance - $deposite),
                        'status' =>1,
                       );   
             $wpdb->insert('th_franchise_payment',$data);

        //echo $wpdb->last_query;


            $table_install = 'th_installer_data';
            $insert_wallet =  $wpdb->query("UPDATE ".$table_install." SET wallet_balance ='".($balance->close_balance - $deposite)."' WHERE installer_data_id = '$franchise_id'");
            }
        }
        }

        $woocommerce->cart->empty_cart();

        $current_user = wp_get_current_user();
        $mobile_no = $current_user->user_login;
        $mobile_no = $order_data['billing']['phone'];

        $total_item = count($item_data);
        $total_item_msg = $total_item - 1;
        $total_item_count = 0;
        /*echo '<pre>';
        print_r($item_data);*/
        global $wpdb;
        foreach ($item_data as $item_key => $item_values)
        {
            if($item_values['variation_id'] != ''){
                $item_id = $item_values['variation_id'];
            }
            else{
                $item_id = $item_values['product_id'];
            }

            if($franchise){

                    $SQL="DELETE FROM `th_franchise_wishlist` WHERE product_id='".$item_id."' AND franchise_id='".$franchise->installer_data_id."'";
                    $wpdb->query($SQL);
            }


            $total_item_count = $total_item_count + $item_values->get_quantity();
            /*$product_variation = wc_get_product( $item_id);
            $variation_des = $product_variation->description;*/
            $variable_product1= wc_get_product($item_id);
            $variation_des1=$variable_product1->description;
            $variation_des.= substr($variation_des1, 0, 25);
            $supplier_id=get_post_meta($item_id,'active_supplier',true);

           $SQL="SELECT * FROM th_supplier_products_final WHERE product_id='".$item_id."' AND supplier_id='".$supplier_id."'";
            $POdata=$wpdb->get_row($SQL);

            $SQL="SELECT * FROM `th_suuplier_product_order` WHERE `order_id` ='$order_id' AND product_id='".$item_id."'";
            $porderChk=$wpdb->get_row($SQL);
            if(empty($porderChk)){
                 $orderData=array(
                    'order_id'=>$order_id,
                    'product_id'=>$item_id,
                    'supplier_id'=>$supplier_id,
                    'tube_price'=>$POdata->tube_price,
                    'tyre_price'=>$POdata->tyre_price,
                    'mrp'=>$POdata->mrp,
                    'total_price'=>$POdata->total_price,
                );

                $wpdb->insert('th_suuplier_product_order',$orderData);
            }

        }

        $invoice=get_post_meta($order_id,'_wcpdf_invoice_number',true);
        $invo_pdfname='invoice-'.$invoice.'.pdf';
        $big_invo_url=site_url().'/download/?filename='.$invo_pdfname;
        //test it out!
        $invo_short_url = get_short_url($big_invo_url);

        if($total_item > 1)
        {
             /* $customer_text = "Confirmed: Order for Tyre ".$variation_des." and ".$total_item_count." item Is successfully placed, order number: ".$order_id." , to download invoice click here ".$invo_short_url." Track by login in or call to 18002335551";*/
             $customer_text = "Confirmed: Order for Tyre ".$variation_des." and ".$total_item_count." item Is successfully placed, order number: ".$order_id." , Track by login in or call to 18002335551";
        }
        else
        {
              /*$customer_text = "Confirmed: Order for Tyre ".$variation_des."  Is successfully placed, order number: ".$order_id." , to download invoice click here ".$invo_short_url." Track by login in or call to 18002335551";*/
              $customer_text = "Confirmed: Order for Tyre ".$variation_des."  Is successfully placed, order number: ".$order_id." , Track by login in or call to 18002335551";

        }

        // ----------- Customer Message ---------------//
        $customer_message = get_post_meta($order_id, 'customer_message', true );
        $tyrehub_message = get_post_meta($order_id, 'tyrehub_message', true );
       $customer_text = trim(preg_replace('/\s+/', ' ', $customer_text));

        if($customer_message == '')
        {

            $customer_text = str_replace(' ', '%20', $customer_text);
            sms_send_to_customer($customer_text,$mobile_no,$templateID=1);
            
            //PDF download SMS
        //global $wpdb;
        //$order_id=5082;
        //$mobile_no=9909225311;
        $voucher_pdfname='invoice-voucher-'.$order_id.'.pdf';
        $big_voucher_url=site_url().'/download/?filename='.$voucher_pdfname;
        //test it out!
         $vouc_short_url = get_short_url($big_voucher_url);

         $voucher_qry = "SELECT cii.*,vt.vehicle_type FROM th_cart_item_installer cii LEFT JOIN th_vehicle_type as vt ON vt.vehicle_id=cii.vehicle_id WHERE cii.order_id =".$order_id;
         $voucherNo = $wpdb->get_results($voucher_qry);

         $services_qry = "SELECT service_name FROM th_cart_item_services  WHERE order_id =".$order_id;
         $services = $wpdb->get_results($services_qry);
         if($services){
         	 foreach ($services as $key => $service) {
                    $service_name[]=$service->service_name;
	         }

	        $service_type=implode(',', $service_name);
         }

        if(isset($voucherNo)){
                //$vouchers=array();
               foreach ($voucherNo as $key => $instal_cart) {
                    # code...
                    @$vouchers[$key]->order_id=$order_id;
                    $vouchers[$key]->session_id=$instal_cart->session_id;
                    $vouchers[$key]->barcode=$instal_cart->barcode;
                    $vouchers[$key]->voucher_name=$service_type;
                    $vouchers[$key]->vehicle_type=$instal_cart->vehicle_type;
             }
        }


         $serv_voucher_qry = "SELECT csv.*, vt.vehicle_type FROM th_cart_item_service_voucher csv LEFT JOIN th_vehicle_type as vt ON vt.vehicle_id=csv.vehicle_id WHERE csv.order_id =".$order_id;
         $servVoucherNo = $wpdb->get_results($serv_voucher_qry);
         if($servVoucherNo){
            //$vouchers=(object)array();
             foreach ($servVoucherNo as $key => $servivoucher) {
                    # code...
                    if($vouchers){$key1=1;}else{$key1=0;}
                    @$vouchers[$key1]->order_id=$order_id;
                    $vouchers[$key1]->session_id=$servivoucher->session_id;
                    $vouchers[$key1]->barcode=$servivoucher->barcode;
                    $vouchers[$key1]->voucher_name=$servivoucher->voucher_name;
                    $vouchers[$key1]->vehicle_type=$servivoucher->vehicle_type;
              }
        }
    if($vouchers){
         foreach ($vouchers as $key => $voucher) {

        $customer_pdf='';
        $customer_pdf="Service Voucher for your  order number ".$order_id." has been generated, your voucher(".$voucher->barcode.") for ".$voucher->vehicle_type." (".$voucher->voucher_name.") , click here to download ".$vouc_short_url;

        $customer_pdf1 = trim(preg_replace('/\s+/', ' ', $customer_pdf));
        $customer_pdf = str_replace( array('&'), 'and', $customer_pdf1);

                $customer_pdf = str_replace(' ', '%20', $customer_pdf);
                sms_send_to_customer($customer_pdf,$mobile_no,$templateID=1);


        }
    }

            $order->update_meta_data( 'customer_message', 'send' );
            $order->save();
        }
        // ----------- Customer Message End ---------------//

        // ----------- tyrehub admin Message ---------------//
            if($tyrehub_message == '')
            {
                 $tyrehub_text = "New Order: Order No: ".$order_id." for Tyre ".$variation_des." and ".$total_item_count." item Is successfully placed by ".$first_name.' '.$last_name.".";
                $tyrehub_text = trim(preg_replace('/\s+/', ' ', $tyrehub_text));
                $tyrehub_text = str_replace(' ', '%20', $tyrehub_text);
                $mobile_no=9978619860;
                sms_send_to_customer($tyrehub_text,$mobile_no,$templateID=1);

                
                $order->update_meta_data( 'tyrehub_message', 'send' );
                $order->save();
            }
        // ----------- tyrehub admin Message End---------------//

    // ----------- Installer Message End---------------//

        $sku = 'service_voucher';
        $service_voucher_prd = $wpdb->get_var( $wpdb->prepare( "SELECT post_id FROM $wpdb->postmeta WHERE meta_key='_sku' AND meta_value='%s' LIMIT 1", $sku ) );

        foreach ($item_data as $item_key => $item_values)
        {
            if($item_values['variation_id'] != ''){
                $item_id = $item_values['variation_id'];
            }
            else{
                $item_id = $item_values['product_id'];
            }

            $quantity = $item_values['quantity'];
            $product_variation = wc_get_product( $item_id );
            $variation_des = $product_variation->get_description();

            $item_name = substr($variation_des, 0, 25);


            $installer = "SELECT * FROM th_cart_item_installer WHERE order_id = '$order_id' and product_id = '$item_id'";
            $row = $wpdb->get_results($installer);

            if(!empty($row))
            {
                foreach ($row as $key => $installer)
                {
                    $destination = $installer->destination;
                    $installer_id =  $installer->installer_id;
                    $message_status = $installer->message;
                    $installer_data_id = $installer->cart_item_installer_id;
                    if($destination == 1)
                    {
                        $installer_mobile = $wpdb->get_var( $wpdb->prepare( "SELECT contact_no FROM th_installer_data WHERE installer_data_id='%s' LIMIT 1" , $installer_id) );

                         $installer_message = "Confirmed: Order for ".$item_name." and ".$quantity." Tyres is successfully placed by ".$first_name.' '.$last_name." for your store.";
                        if($message_status == '')
                        {
                            $installer_message = trim(preg_replace('/\s+/', ' ', $installer_message));
                            $installer_message = str_replace(' ', '%20', $installer_message);

                            sms_send_to_customer($installer_message,$installer_mobile,$templateID=1);
                            
                            $update_message = $wpdb->get_results("UPDATE th_cart_item_installer SET message = 'sent' WHERE  product_id = '$item_id' and order_id = '$order_id'");
                        }

                    }
                }
            }
        // ----------- installer  Message ---------------//

        }
    }
    elseif($order_status == 'on-hold')
    {
            $woocommerce->cart->empty_cart();
            $current_user = wp_get_current_user();
            $mobile_no = $current_user->user_login;
            $mobile_no = $order_data['billing']['phone'];

            $total_item = count($item_data);
            $total_item_msg = $total_item - 1;
            $total_item_count = 0;
             foreach ($item_data as $item_key => $item_values)
            {
                if($item_values['variation_id'] != ''){
                    $item_id = $item_values['variation_id'];
                }
                else{
                    $item_id = $item_values['product_id'];
                }
                $total_item_count = $total_item_count + $item_values->get_quantity();
                $product_variation = wc_get_product( $item_id );
                $variation_des1 = $product_variation->get_description();
                $variation_des.= substr($variation_des1, 0, 25);
            }

            if($total_item > 1)
            {
                   $customer_text = "Confirmed: Order for Tyre ".$variation_des." and ".$total_item_count." item Is successfully placed But Waiting for your payment. Track by login in or call to 18002335551";
            }else{
                  $customer_text = "Confirmed: Order for Tyre ".$variation_des."  Is successfully placed But Waiting for your payment. Track by login in or call to 18002335551";
            }

            // ----------- Customer Message ---------------//
            $customer_message = get_post_meta($order_id, 'customer_message', true );
            $tyrehub_message = get_post_meta($order_id, 'tyrehub_message', true );
            $customer_text = trim(preg_replace('/\s+/', ' ', $customer_text));

            if($customer_message == '')
            {
                $ch1 = curl_init();
                $customer_text = str_replace(' ', '%20', $customer_text);

                sms_send_to_customer($customer_text,$mobile_no,$templateID=1);

                
                $order->update_meta_data( 'customer_message', 'send' );
                $order->save();
            }
            // ----------- tyrehub admin Message ---------------//
            if($tyrehub_message == '')
            {
                if(isset($business_name)){
                   $tyrehub_text = "New Order: Order No: ".$order_id." for Tyre ".$variation_des." and ".$total_item_count." item Is successfully placed by ".$business_name;
                }else{
                    $tyrehub_text = "New Order: Order No: ".$order_id." for Tyre ".$variation_des." and ".$total_item_count." item Is successfully placed by ".$first_name.' '.$last_name.".";
                }

                    $tyrehub_text = trim(preg_replace('/\s+/', ' ', $tyrehub_text));
                    
                    $tyrehub_text = str_replace(' ', '%20', $tyrehub_text);
                    $mobile_no=9978619860;
                    sms_send_to_customer($tyrehub_text,$mobile_no,$templateID=1);

                    
                    $order->update_meta_data( 'tyrehub_message', 'send' );
                    $order->save();
            }

            // ----------- Customer Message End ---------------//

            // ----------- installer  Message ---------------//
                $installer = "SELECT * FROM th_cart_item_installer WHERE order_id = '$order_id' and product_id = '$item_id'";
                $row = $wpdb->get_results($installer);

                if(!empty($row))
                {
                    foreach ($row as $key => $installer)
                    {
                        $destination = $installer->destination;
                        $installer_id =  $installer->installer_id;
                        $message_status = $installer->message;
                        $installer_data_id = $installer->cart_item_installer_id;
                        if($destination == 1)
                        {
                             $installer_mobile = $wpdb->get_var( $wpdb->prepare( "SELECT contact_no FROM th_installer_data WHERE installer_data_id='%s' LIMIT 1" , $installer_id) );

                             $installer_message = "Confirmed: Order for ".$variation_des." and ".$total_item_count." Tyres is successfully placed by ".$first_name.' '.$last_name." for your store but waiting for payment we will update you when order dispatched";
                            if($message_status == '')
                            {
                                $installer_message = trim(preg_replace('/\s+/', ' ', $installer_message));
                                $installer_message = str_replace(' ', '%20', $installer_message);

                                sms_send_to_customer($installer_message,$installer_mobile,$templateID=1);

                              
                                $update_message = $wpdb->get_results("UPDATE th_cart_item_installer SET message = 'sent' WHERE  product_id = '$item_id' and order_id = '$order_id'");
                            }

                        }
                    }
                }
            // ----------- installer  Message ---------------//

    }
    /* ------------------   Sms code ------------------ */


   /* ----------- Discount Rule -------------*/

    date_default_timezone_set('Asia/Kolkata');
    $today_date = date('Y-m-d G:i');
    $today_date = strtotime($today_date);

    $rule_sql = "SELECT * FROM th_discount_rule where status = 'on'";
    $rule_data = $wpdb->get_results($rule_sql);

    $prd_discount_arr = [];
    if(!empty($rule_data))
    {
        foreach ($rule_data as $key => $rule_row)
        {
            $start_date = strtotime($rule_row->start_date);
            $end_date = strtotime($rule_row->end_date);
            if($today_date > $start_date && $today_date < $end_date)
            {
                $rule_row->name;
                $rule_id = $rule_row->rule_id;
                $rule_img = $rule_row->rule_img;
                $list_sql = "SELECT * FROM th_discount_product_list where rule_id = $rule_id and status = 'on'";

                $list_result = $wpdb->get_results($list_sql);


                if(!empty($list_result))
                {
                    //var_dump($list_result);
                    foreach ($list_result as $key => $list_row)
                    {
                        $list_prd_id = $list_row->product_id;
                        $list_prd_amount = $list_row->amount;
                        if(array_key_exists($list_prd_id, $prd_discount_arr))
                        {
                            $old_value = $prd_discount_arr[$list_prd_id];
                            if($list_prd_amount > $old_value)
                            {
                                $prd_discount_arr[$list_prd_id] = array($list_prd_amount , $rule_id);
                            }
                        }
                        else{
                            $prd_discount_arr[$list_prd_id] = array($list_prd_amount , $rule_id);
                        }
                    }
                }
            }
        }
    }
  //  var_dump($prd_discount_arr);
    foreach ($item_data as $item_id => $item_values)
    {
        if($item_values['variation_id'] != ''){
            $product_id = $item_values['variation_id'];
        }
        else{
            $product_id = $item_values['product_id'];
        }

        if(array_key_exists($product_id, $prd_discount_arr))
        {
            //echo $product_id;
             $item_rule_id = $prd_discount_arr[$product_id][1];
            $item_rule_amount = $prd_discount_arr[$product_id][0];
            wc_update_order_item_meta($item_id, '_discount_rule', $item_rule_id);
            wc_update_order_item_meta($item_id, '_discount_rule_amount', $item_rule_amount);

        }
    }
    /* --------------Discount Rule ----------*/



    // Save order item meta //
        foreach ($item_data as $item_id => $item_values)
        {
            $item_values['line_subtotal'];
            if($item_values['variation_id'] != ''){
                $product_id = $item_values['variation_id'];
            }
            else{
                $product_id = $item_values['product_id'];
            }

            $user = $order->get_user();
            $user_role = $user->roles[0];

            $product_variation = wc_get_product( $product_id );
            $variation_data = $product_variation->get_data();
            $variation_des = $product_variation->get_description();
            $price = $product_variation->get_price();
            $quantity = $item_values['quantity'];
            $cart_item_qty = $item_values['quantity'];
            $tyre_type = $variation_data['attributes']['pa_tyre-type'];


            if($product_id == '3550' && $order->get_data()!='failed')
            {

                $service_voucher = "SELECT *
                                FROM th_cart_item_service_voucher
                                WHERE order_id = '$order_id' and product_id = '$product_id'";
                $row = $wpdb->get_results($service_voucher);

                if(!empty($row))
                {
                    foreach ($row as $key => $voucher)
                    {
                        $voucher_id = $voucher->service_voucher_id;
                        $installer_id = $voucher->installer_id;
                        $message_status=$voucher->message;

                        $rate = $voucher->rate;
                        $qty = $voucher->qty;
                        $amount = $rate * $qty;

                        $gst = $amount * $serviceGST / $serviceGSTD;
                        $service_taxable = $amount - $gst;
                        $service_sgst = $gst / 2;

                        wc_update_order_item_meta($item_id, $voucher_id.'_service_sgst', $service_sgst);
                        wc_update_order_item_meta($item_id, $voucher_id.'_service_cgst', $service_sgst);
                        wc_update_order_item_meta($item_id, $voucher_id.'_service_taxable', $service_taxable);

                        /*Only service vourcher purchase SMS Send*/
                        $vehicel_type_get = "SELECT * FROM th_vehicle_type WHERE  vehicle_id =".$voucher->vehicle_id;
                        $vehitype = $wpdb->get_results($vehicel_type_get);

                        $installer_mobile = $wpdb->get_var( $wpdb->prepare( "SELECT contact_no FROM th_installer_data WHERE installer_data_id='%s' LIMIT 1" , $installer_id) );

                        $installer_message = "Confirmed: Service Order for ".$voucher->voucher_name." for ".$vehitype[0]->vehicle_type." Car is successfully placed by ".$first_name.' '.$last_name." for your store.";

                        if($message_status == '')
                        {
                            $installer_message1 = trim(preg_replace('/\s+/', ' ', $installer_message));
                            $installer_message2 = str_replace(' ', '%20', $installer_message1);
                            $installer_message = str_replace( array('&'), 'and', $installer_message2);
                            sms_send_to_customer($installer_message,$installer_mobile,$templateID=1);

                            

                             $update_message = $wpdb->get_results("UPDATE th_cart_item_service_voucher SET message = 'sent' WHERE  product_id = '$voucher->product_id' and order_id = '$voucher->order_id'");


                        }
                    }
                }
            }
            else
            {
                if($tyre_type == 'tubeless')
                {
                    $line_subtotal = $item_values['line_subtotal'];

                    if($user_role == 'Installer')
                    {   if(empty($franchise)){
                            $discount = $line_subtotal * 0.02;
                            $line_subtotal = $line_subtotal - $discount;
                        }
                        
                    }

                    $gst = round($line_subtotal * $tyreGST / $tyreGSTD);
                  //  echo "--------------------------------</br>";
                   $taxable_value = round($line_subtotal - $gst);
                    $sgst = $gst / 2;
                }

                if($tyre_type == 'tubetyre')
                {
                    $line_subtotal = $item_values['line_subtotal'];

                    $tyre_price = get_post_meta($product_id, 'tyre_price', true );
                    $tube_price = get_post_meta($product_id, 'tube_price', true );

                    if($user_role == 'Installer')
                    {
                        if(empty($franchise)){
                            $discount = $line_subtotal * 0.02;
                            $line_subtotal = $line_subtotal - $discount;
                            $tyre_price = $tyre_price - $discount;
                        }
                        
                    }

                    if($tyre_price == 0 && $tube_price == 0)
                    {
                            $gst = $line_subtotal * $tyreGST / $tyreGSTD;
                    }else{
                        //$tyre_gst = $tyre_price * $tyreGST / $tyreGSTD;
                        //$tube_gst = $tube_price * $tyreGST / $tyreGSTD;
                        //$gst = $tyre_gst + $tube_gst;
                        //$gst = $quantity * $gst;
                        $gst = round($line_subtotal * $tyreGST / $tyreGSTD);
                      //  echo "--------------------------------</br>";
                       $taxable_value = round($line_subtotal - $gst);
                        $sgst = $gst / 2;
                    }

                    //$taxable_value = $line_subtotal - $gst;
                     //$sgst = $gst / 2;
                    //$cart_item['data']->set_price($new_price);
                }

                // service charge gst
                $destination_data = "SELECT *
                            FROM th_cart_item_installer
                            WHERE product_id = '$product_id' and order_id = '$order_id'";

                $row = $wpdb->get_results($destination_data);

                $service_taxable = 0;
                $service_sgst = 0;
                if(!empty($row))
                {

                    foreach ($row as $key => $data)
                    {
                        $destination = $data->destination;
                    }

                    if($destination == 1)
                    {
                        $services = "SELECT *
                                FROM th_cart_item_services
                                WHERE product_id = '$product_id' and order_id = '$order_id'";
                         $row = $wpdb->get_results($services);
                         $total_service_charge = 0;
                         $total_home_charge = 0;
                         foreach ($row as $key => $service)
                        {
                           $service_name = $service->service_name;
                            $tyre_count = $service->tyre;
                            $rate = $service->rate;

                            $amount = $tyre_count * $rate;

                            $total_service_charge = $total_service_charge + $amount;
                        }

                    }

                    elseif($destination == 0)
                    {
                        $product_variation_new = wc_get_product( $product_id );
                        $prd_attr_vehicle = '';
                        $variation_data = $product_variation_new->get_data();
                            if($variation_data['attributes']['pa_vehicle-type'] != 'car-tyre'){
                                $prd_attr_vehicle = $variation_data['attributes']['pa_vehicle-type'];
                        }

                        if($prd_attr_vehicle != ''){
                            if($cart_item_qty >= 2 && $cart_item_qty <= 5 ){
                                $home_delivery_charge = 200;
                            }else if($cart_item_qty >= 6){
                                $home_delivery_charge = 300;
                            }else{
                                $home_delivery_charge = 100;
                            }
                        }else{
                            if($cart_item_qty >= 2 && $cart_item_qty <= 5 ){
                                 $home_delivery_charge = 250;
                            }else if($cart_item_qty >= 6){
                                $home_delivery_charge = 400;
                            }else{
                                $home_delivery_charge = 150;
                            }
                        }
                        $home_delivery_charge =0;

                       $total_home_charge =  $home_delivery_charge;
                       wc_update_order_item_meta($item_id, 'delivery_charge', $total_home_charge);
                    }

                    if($total_home_charge != 0){
                        $service_gst = round($total_home_charge * $serviceGST / $serviceGSTD);
                        $service_sgst = $service_gst / 2;
                        $service_taxable = round($total_home_charge - $service_gst);
                    }else{
                        $service_gst = round($total_service_charge * $serviceGST / $serviceGSTD);
                        $service_sgst = $service_gst / 2;
                        $service_taxable = round($total_service_charge - $service_gst);
                    }

                }
                    wc_update_order_item_meta($item_id, 'taxable_value', $taxable_value);
                    wc_update_order_item_meta($item_id, 'sgst', $sgst);
                    wc_update_order_item_meta($item_id, 'cgst', $sgst);
                    wc_update_order_item_meta($item_id, 'discount', $discount);
                    wc_update_order_item_meta($item_id, 'service_sgst', $service_sgst);
                    wc_update_order_item_meta($item_id, 'service_cgst', $service_sgst);
                    wc_update_order_item_meta($item_id, 'service_taxable', $service_taxable);

            }

        }
     // Save order item meta //

?>
<div class="main-breadcrumb">
       <?php main_breadcrumb('order-received');?>
    </div>
<div class="woocommerce-order">

    <?php if ( $order ) : ?>

        <?php if ( $order->has_status( 'failed' ) ) : ?>

            <p class="woocommerce-notice woocommerce-notice--error woocommerce-thankyou-order-failed"><?php _e( 'Unfortunately your order cannot be processed as the originating bank/merchant has declined your transaction. Please attempt your purchase again.', 'woocommerce' ); ?></p>

            <p class="woocommerce-notice woocommerce-notice--error woocommerce-thankyou-order-failed-actions">
                <a href="<?php echo esc_url( $order->get_checkout_payment_url() ); ?>" class="button pay"><?php _e( 'Pay', 'woocommerce' ) ?></a>
                <?php if ( is_user_logged_in() ) : ?>
                    <a href="<?php echo esc_url( wc_get_page_permalink( 'myaccount' ) ); ?>" class="button pay"><?php _e( 'My account', 'woocommerce' ); ?></a>
                <?php endif; ?>
            </p>

        <?php else : ?>
            <?php

                $order_id = $order->get_id();
                $session_id = WC()->session->get_customer_id();
                $SQL="SELECT * FROM th_vehicle_details WHERE order_id='".$order_id."'";
                $carInfo=$wpdb->get_row($SQL);

                foreach ( WC()->cart->get_cart() as $cart_item_key => $cart_item )
                        {
                           // echo $cart_item_key;
                        }
                foreach ( $order->get_items() as $item_id => $item )
                {
                  //  echo $item_id;
                  //  var_dump($item);
                    if($item['variation_id'] != ''){
                        $product_id = $item['variation_id'];
                     }
                     else{
                        $product_id = $item['product_id'];
                     }
                    $update = $wpdb->get_results("UPDATE th_cart_item_installer SET order_id = '$order_id' WHERE session_id = '$session_id' and product_id = '$product_id' and order_id = ''");

                    $update = $wpdb->get_results("UPDATE th_cart_item_services SET order_id = '$order_id' WHERE session_id = '$session_id' and product_id = '$product_id' and order_id = ''");
                }
                $update_voucher = $wpdb->get_results("UPDATE th_cart_item_service_voucher SET order_id = '$order_id' WHERE session_id = '$session_id' and voucher_name != '' and order_id = ''");
            ?>

            <p class="woocommerce-notice woocommerce-notice--success woocommerce-thankyou-order-received"><?php echo apply_filters( 'woocommerce_thankyou_order_received_text', __( 'Thank you. Your order has been received.', 'woocommerce' ), $order ); ?></p>
            <!--<p style="color:red;">Dear valuable customer, please take a note. All our Service Centres and Franchises will remain closed from Friday, 13th Nov to Thursday,19th Nov 2020 due to Diwali festival. Orders received during that time will be delivered after 20th Nov 2020.</p>-->

         
            <?php if((!isset($carInfo->order_id) && $user_role == "customer") || !is_user_logged_in()){?>

            <div id="carDetails" class="modal fade" role="dialog">
              <div class="modal-dialog">

                <!-- Modal content-->
            <div class="modal-content">
                  <div class="modal-header">
                    <button type="button" class="close" data-dismiss="modal">&times;</button>
                <h4 class="modal-title">Please enter car details</h4>
            </div>
            <div class="modal-body">
                <form id="tab1-form" class="vehicle_details">
                    <div class="row">
                        <div class="col-md-12">
                            <div class="form-group">
                                <p>Please fill out the car detail for which you are installing this Tyre,  the provided car detail will be registered for Tyre Guarantee and warranty purpose.</p>
                               
                               
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
                                        <option value="<?php echo $make_id; ?>" <?php if(isset($_SESSION['make_id']) && $_SESSION['make_id'] == $make_id){ echo 'selected'; }?>><?php echo $make_name; ?></option>
                                    <?php } ?>
                                    </select>
                                </div>
                            </div>
                        </div>
                        <div class="col-md-12">
                            <div class="form-group">
                                <div class="select-wrapper year-wrapper" >
                                    <select disabled="disabled" name="select1" class="input-custom select-model" required>
                                        <option value="" selected="">Model</option>
                                    </select>
                                </div>
                            </div>
                        </div>
                        <div class="col-md-12">
                            <div class="form-group">
                                <div class="select-wrapper model-wrapper">
                                    <select name="select3" disabled="disabled" class="input-custom select-sub-model" required>
                                        <option value="" disabled selected>Sub Model</option>
                                    </select>
                                </div>
                            </div>
                        </div>
                        <div class="col-md-12">
                            <div class="form-group">
                                <div class="select-wrapper input-wrapper model-wrapper">
                                    <label>Car Number</label>
                                    <input type="text" class="input-custom" name="car_number" id="car_number" value="" placeholder="" maxlength="12" size="12">
                                    <input type="hidden" name="user_id" value="<?=$session_id?>" id="user_id">
                                    <input type="hidden" name="order_id" value="<?=$order_id?>" id="order_id">
                                    
                                </div>
                            </div>
                        </div>

                    </div>
                   

                  </div>
                  <div class="modal-footer">
                    <button type="button" class="btn btn-default dcclose-btn" id="car_details_save"><span>Save</span></button>
                    <button type="button" class="btn btn-default dcclose-btn" data-dismiss="modal"><span>Skip</span></button>
                  </div>
              </form>
                </div>

              </div>
            </div>
            <script type="text/javascript">
                 //jQuery("#carDetails").modal();
                 jQuery(window).on('load', function(){ 
                  jQuery('#carDetails').modal('show');
            });
            </script>
        <?php }?>
            <ul class="woocommerce-order-overview woocommerce-thankyou-order-details order_details">

                <li class="woocommerce-order-overview__order order">
                    <?php _e( 'Order number:', 'woocommerce' ); ?>
                    <strong><?php echo $order->get_order_number(); ?></strong>
                </li>

                <li class="woocommerce-order-overview__date date">
                    <?php _e( 'Date:', 'woocommerce' ); ?>
                    <strong><?php echo wc_format_datetime( $order->get_date_created() ); ?></strong>
                </li>

                <?php if ( is_user_logged_in() && $order->get_user_id() === get_current_user_id() && $order->get_billing_email() ) : ?>
                    <li class="woocommerce-order-overview__email email">
                        <?php _e( 'Email:', 'woocommerce' ); ?>
                        <strong><?php echo $order->get_billing_email(); ?></strong>
                    </li>
                <?php endif; ?>

                <li class="woocommerce-order-overview__total total">
                    <?php _e( 'Total:', 'woocommerce' ); ?>
                    <strong><?php echo $order->get_formatted_order_total(); ?></strong>
                </li>

                <?php if ( $order->get_payment_method_title() ) : ?>
                    <li class="woocommerce-order-overview__payment-method method">
                        <?php _e( 'Payment method:', 'woocommerce' ); ?>
                        <strong><?php echo wp_kses_post( $order->get_payment_method_title() ); ?></strong>
                    </li>
                <?php endif; ?>

            </ul>

        <?php endif; ?>

        <?php do_action( 'woocommerce_thankyou_' . $order->get_payment_method(), $order->get_id() ); ?>
        <?php do_action( 'woocommerce_thankyou', $order->get_id() ); ?>

    <?php else : ?>

        <p class="woocommerce-notice woocommerce-notice--success woocommerce-thankyou-order-received"><?php echo apply_filters( 'woocommerce_thankyou_order_received_text', __( 'Thank you. Your order has been received.', 'woocommerce' ), null ); ?></p>
        <p style="color:red;">Due to Covid-19 pandemic, we may have shortage OR delivery issue. if we do not find your tyre our team will call you to offer other available options for your order else we will refund your money.</p>

    <?php endif; ?>

</div>


