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
    /* ------------------   Sms code ------------------ */
    unset($_SESSION['make_id']);
    unset($_SESSION['model_id']);
    unset($_SESSION['sub_model_id']);
    $order_data = $order->get_data();  
    $order_status = $order->get_status();
   $payment_method = $order->get_payment_method();   
    $item_data = $order->get_items();
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
    if($order_status == 'processing' || $order_status == 'customprocess' || $payment_method == 'cod' || $payment_method == 'pos')
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
            $product_variation = new WC_Product_Variation( $item_id );
            $variation_des = $product_variation->get_description();
            $variation_des = substr($variation_des, 0, 25);
        }
        
        if($total_item > 1)
        {
              $customer_text = "Confirmed: Order for Tyre ".$variation_des." and ".$total_item_count." item Is successfully placed. Track by login in or call to 18002335551";
        }
        else
        {
              $customer_text = "Confirmed: Order for Tyre ".$variation_des."  Is successfully placed. Track by login in or call to 18002335551";
        }
    
        // ----------- Customer Message ---------------//
        $customer_message = get_post_meta($order_id, 'customer_message', true );
        $tyrehub_message = get_post_meta($order_id, 'tyrehub_message', true );
        $customer_text = trim(preg_replace('/\s+/', ' ', $customer_text));

        if($customer_message == '')
        {
            
            $ch1 = curl_init();
            $customer_text = str_replace(' ', '%20', $customer_text);
            $url_string="http://nimbusit.co.in/api/swsend.asp?username=t1ankitshah&password=81957032&sender=TYREHB&sendto=91".$mobile_no."&message=".$customer_text;
            curl_setopt($ch1, CURLOPT_URL, $url_string); 
            curl_setopt($ch1, CURLOPT_RETURNTRANSFER, 1); 
            curl_setopt($ch1, CURLOPT_CUSTOMREQUEST, "GET");
            $result1 = curl_exec($ch1);
           // var_dump($result1);
            curl_close ($ch1);
            $order->update_meta_data( 'customer_message', 'send' );
            $order->save();
        }  
        // ----------- Customer Message End ---------------// 

        // ----------- tyrehub admin Message ---------------//
            if($tyrehub_message == '')
            {
                 $tyrehub_text = "New Order: Order No: ".$order_id." for Tyre ".$variation_des." and ".$total_item_count." item Is successfully placed by ".$first_name.' '.$last_name.".";
                $tyrehub_text = trim(preg_replace('/\s+/', ' ', $tyrehub_text));
                $ch1 = curl_init();
                $tyrehub_text = str_replace(' ', '%20', $tyrehub_text);
                $url_string="http://nimbusit.co.in/api/swsend.asp?username=t1ankitshah&password=81957032&sender=TYREHB&sendto=919978619860&message=".$tyrehub_text;

                curl_setopt($ch1, CURLOPT_URL, $url_string); 
                curl_setopt($ch1, CURLOPT_RETURNTRANSFER, 1); 
                curl_setopt($ch1, CURLOPT_CUSTOMREQUEST, "GET");
                $result1 = curl_exec($ch1);
                curl_close ($ch1);
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
            $product_variation = new WC_Product_Variation( $item_id );
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

                            $ch1 = curl_init();
                            $url_string="http://nimbusit.co.in/api/swsend.asp?username=t1ankitshah&password=81957032&sender=TYREHB&sendto=91".$installer_mobile."&message=".$installer_message;
                            curl_setopt($ch1, CURLOPT_URL, $url_string); 
                            curl_setopt($ch1, CURLOPT_RETURNTRANSFER, 1); 
                            curl_setopt($ch1, CURLOPT_CUSTOMREQUEST, "GET");
                            $result1 = curl_exec($ch1);
                            curl_close ($ch1);           

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
            $product_variation = new WC_Product_Variation( $item_id );
            $variation_des = $product_variation->get_description();
            $variation_des = substr($variation_des, 0, 25);
        }
        
        if($total_item > 1)
        {
               $customer_text = "Confirmed: Order for Tyre ".$variation_des." and ".$total_item_count." item Is successfully placed But Waiting for your payment. Track by login in or call to 18002335551";
        }
        else
        {
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
            $url_string="http://nimbusit.co.in/api/swsend.asp?username=t1ankitshah&password=81957032&sender=TYREHB&sendto=91".$mobile_no."&message=".$customer_text;
            curl_setopt($ch1, CURLOPT_URL, $url_string); 
            curl_setopt($ch1, CURLOPT_RETURNTRANSFER, 1); 
            curl_setopt($ch1, CURLOPT_CUSTOMREQUEST, "GET");
            $result1 = curl_exec($ch1);
           // var_dump($result1);
            curl_close ($ch1);
            $order->update_meta_data( 'customer_message', 'send' );
            $order->save();
        }  
        // ----------- tyrehub admin Message ---------------//
        if($tyrehub_message == '')
        {
            if(isset($business_name)){
               echo $tyrehub_text = "New Order: Order No: ".$order_id." for Tyre ".$variation_des." and ".$total_item_count." item Is successfully placed by ".$business_name;
            }else{
                $tyrehub_text = "New Order: Order No: ".$order_id." for Tyre ".$variation_des." and ".$total_item_count." item Is successfully placed by ".$first_name.' '.$last_name.".";
            }
             
                $tyrehub_text = trim(preg_replace('/\s+/', ' ', $tyrehub_text));
                $ch1 = curl_init();
                $tyrehub_text = str_replace(' ', '%20', $tyrehub_text);
                 $url_string="http://nimbusit.co.in/api/swsend.asp?username=t1ankitshah&password=81957032&sender=TYREHB&sendto=919978619860&message=".$tyrehub_text;

                curl_setopt($ch1, CURLOPT_URL, $url_string); 
                curl_setopt($ch1, CURLOPT_RETURNTRANSFER, 1); 
                curl_setopt($ch1, CURLOPT_CUSTOMREQUEST, "GET");
                $result1 = curl_exec($ch1);
                curl_close ($ch1);
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

                         $installer_message = "Confirmed: Order for ".$item_name." and ".$quantity." Tyres is successfully placed by ".$first_name.' '.$last_name." for your store but waiting for payment we will update you when order dispatched";              
                        if($message_status == '')
                        {
                            $installer_message = trim(preg_replace('/\s+/', ' ', $installer_message));
                            $installer_message = str_replace(' ', '%20', $installer_message);

                            $ch1 = curl_init();
                            $url_string="http://nimbusit.co.in/api/swsend.asp?username=t1ankitshah&password=81957032&sender=TYREHB&sendto=91".$installer_mobile."&message=".$installer_message;
                            curl_setopt($ch1, CURLOPT_URL, $url_string); 
                            curl_setopt($ch1, CURLOPT_RETURNTRANSFER, 1); 
                            curl_setopt($ch1, CURLOPT_CUSTOMREQUEST, "GET");
                            $result1 = curl_exec($ch1);
                            curl_close ($ch1);                

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

            $product_variation = new WC_Product_Variation( $product_id );
            $variation_data = $product_variation->get_data();
            $variation_des = $product_variation->get_description();
            $price = $product_variation->get_price();
            $quantity = $item_values['quantity'];
            $cart_item_qty = $item_values['quantity'];
            $tyre_type = $variation_data['attributes']['pa_tyre-type'];


            if($product_id == '3550')
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
                        $rate = $voucher->rate;
                        $qty = $voucher->qty;
                        $amount = $rate * $qty;

                        $gst = $amount * 18 / 118;
                        $service_taxable = $amount - $gst;
                        $service_sgst = $gst / 2;

                        wc_update_order_item_meta($item_id, $voucher_id.'_service_sgst', $service_sgst);
                        wc_update_order_item_meta($item_id, $voucher_id.'_service_cgst', $service_sgst);
                        wc_update_order_item_meta($item_id, $voucher_id.'_service_taxable', $service_taxable);
                    }
                }               
            }
            else
            {
                if($tyre_type == 'tubeless')
                {
                    $line_subtotal = $item_values['line_subtotal'];         

                    if($user_role == 'Installer')
                    {
                        $discount = $line_subtotal * 0.02;
                        $line_subtotal = $line_subtotal - $discount;     
                    }

                    $gst = round($line_subtotal * 28 / 128);
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
                        $discount = $line_subtotal * 0.02;              
                        $line_subtotal = $line_subtotal - $discount;
                        $tyre_price = $tyre_price - $discount;
                    }   
                    
                    if($tyre_price == 0 && $tube_price == 0)
                    {
                            $gst = $line_subtotal * 28 / 128;
                    }
                    else{
                        $tyre_gst = $tyre_price * 28 / 128;
                        $tube_gst = $tube_price * 28 / 128;
                        $gst = $tyre_gst + $tube_gst;
                        $gst = $quantity * $gst;
                    }                            

                    $taxable_value = $line_subtotal - $gst;
                 $sgst = $gst / 2;
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
                        $product_variation_new = new WC_Product_Variation( $product_id );
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

                       $total_home_charge =  $home_delivery_charge;
                       wc_update_order_item_meta($item_id, 'delivery_charge', $total_home_charge);
                    }

                    if($total_home_charge != 0){
                        $service_gst = round($total_home_charge * 18 / 118);
                        $service_sgst = $service_gst / 2;
                        $service_taxable = round($total_home_charge - $service_gst);
                    }else{
                        $service_gst = round($total_service_charge * 18 / 118);
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
        <ul class="breadcrumb-list-new">
            <li class="">
                <div class="stop-rounding">
                    <span class="step-number">
                        <img class="" src="<?php echo bloginfo('template_directory');?>/images/select_tyre.png" >
                    </span>
                </div> 
                <a href="#" class="step-link"> Select Tyre </a>
            </li>
            <li class="">
                                    <!-- <img src="" alt="step1" />    -->
                <div class="stop-rounding">
                    <span class="step-number">
                         <img class="" src="<?php echo bloginfo('template_directory');?>/images/select_service.png" >
                    </span>
                </div> 
                <a href="#" class="step-link"> Select Service Partner </a>
            </li>
            <li class="">
                                    <!-- <img src="" alt="step1" />    -->
                <div class="stop-rounding">
                    <span class="step-number">
                        
                        <img class="" src="<?php echo bloginfo('template_directory');?>/images/review.png" >
                    </span>
                </div> 
                <a href="#" class="step-link"> Review Order </a>
            </li>
            <li class="">
                <div class="stop-rounding">
                    <span class="step-number">
                        <img class="car-img" src="<?php echo bloginfo('template_directory');?>/images/pay.png" >
                    </span>
                </div> 
                <a href="#" class="step-link"> Check Out & Pay </a>
            </li>
            <li class="active">
                <div class="stop-rounding">
                    <span class="step-number">
                        <img class="" src="<?php echo bloginfo('template_directory');?>/images/order_placed.png" >
                    </span>
                </div> 
                <a href="#" class="step-link"> Order Placed </a>
            </li>
        </ul>
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

    <?php endif; ?>

</div>
