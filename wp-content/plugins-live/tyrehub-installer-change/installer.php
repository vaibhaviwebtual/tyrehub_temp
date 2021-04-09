<?php
/*
Plugin Name: Tyrehub Installer Change
Plugin URI: https://acespritech.com/
Description: Brands count with sales product only.
Version: 1.1.1
Author: Acespritech
Author URI: https://acespritech.com/
*/

// Add menu and pages to WordPress admin area




add_action('admin_enqueue_scripts', 'admin_js_installer_change');

function admin_js_installer_change()
{
    wp_enqueue_script('admin_js_jquery_confirm', plugins_url('/jquery.confirm/jquery.confirm/jquery.confirm.js', __FILE__), array('jquery'));
    wp_enqueue_script('admin_js_dialog_modal', plugins_url('/jquery.confirm/js/script.js', __FILE__), array('jquery'),true);


     wp_enqueue_style('admin_css_jquery_confirm', plugins_url('/jquery.confirm/jquery.confirm/jquery.confirm.css', __FILE__));
     // wp_enqueue_style('admin_css_dialog_modal', plugins_url('/jquery.confirm/css/styles.css', __FILE__));
}

add_action('admin_menu', 'myplugin_create_top_level_menu');

function myplugin_create_top_level_menu() {
     add_submenu_page(
      null, 
      'Order Installer Change',
      'Order Installer Change', 
      'manage_options', 
      'order-installer-change', 
      'order_installer_change_callback'
     );
}

function order_installer_change_callback() {
     global $woocommerce, $wpdb;
    $order_id=$_GET['order_id'];
    $order = wc_get_order($order_id);
    if(empty($order) || $order_id != $order->get_id()){
        //do things
        wp_redirect(site_url('/wp-admin/edit.php?post_type=shop_order'));
    }
    $order_arr = [];

        $installer = "SELECT * FROM th_cart_item_installer WHERE order_id='$order_id' GROUP BY installer_id";
        $voucher_installer = "SELECT * FROM th_cart_item_service_voucher WHERE order_id='$order_id' GROUP BY installer_id";

    $row = $wpdb->get_results($installer); 
    if(!empty($row)) {
        foreach ($row as $key => $installer) {
            if($installer->order_id != 0){
                $order_arr1[$key]['order_id'] = $installer->order_id;
                $order_arr1[$key]['product_id'] = $installer->product_id;                
                $order_arr1[$key]['installer'] = $installer->installer_id;
                $order_arr1[$key]['supplier'] = $installer->installer_id;
              
            }
        }
    }

    $row1 = $wpdb->get_results($voucher_installer); 
    if(!empty($row1)) {
        foreach ($row1 as $key => $installer) {
            if($installer->order_id != 0) {
                $order_arr2[$key]['order_id'] = $installer->order_id;
                $order_arr2[$key]['product_id'] = $installer->product_id;                
                $order_arr2[$key]['installer'] = $installer->installer_id;
                $order_arr2[$key]['supplier'] = $installer->installer_id;
            }
        }
    }

    if(!empty($order_arr2) && !empty($order_arr1) ){
       $order_arr= array_merge($order_arr1,$order_arr2);    
    }elseif(empty($order_arr2) && !empty($order_arr1)){
        $order_arr=$order_arr1;  
    }elseif(empty($order_arr1) && !empty($order_arr2)){
        $order_arr=$order_arr2; 
    }
    
   ?>
   <div class="wrap">
    <h2>Change Order Installer </h2>
  <a href="/wp-admin/edit.php?post_type=shop_order" class="page-title-action">Orders</a>
 <div class="admin-url" hidden=""><?php echo admin_url('admin-ajax.php'); ?></div>
    <div class="franchise-data">
        <table class="installer-change-tbl">
            <thead>
                <tr>
                    <th>Order No.</th>
                    <th>Tyre</th>
                    <th>Customer</th>
                    <th>Supplier</th>
                    <th>Current Installer</th>
                    <th>New Installer</th>
                </tr>
            </thead>
                
                <tbody>
                <?php foreach ($order_arr as $key => $value) {
                    $installer_id=$value['installer'];

                    $sql = "SELECT * FROM th_installer_data WHERE  installer_data_id='$installer_id'";
                    $installer = $wpdb->get_row($sql);

                    $product=get_post_meta($value['product_id'],'_variation_description',true);
                    $order = wc_get_order($order_id);
                    $order_data = $order->get_data(); // The Order data
                    $first_name = $order_data['billing']['first_name'];
                    $last_name = $order_data['billing']['last_name'];

                    $supplier_id=get_post_meta($value['product_id'],'active_supplier',true);

                     $sql1 = "SELECT * FROM th_supplier_data WHERE  supplier_data_id='$supplier_id'";
                    $supplier = $wpdb->get_row($sql1);
                    
                    ?>
                    <tr>
                        <td><?=$value['order_id'];?></td>
                        <td><?=$product;?></td>
                        <td><?=$first_name.' '.$last_name;?></td>
                        <td><?php echo $supplier->business_name; ?></td>
                        <td><?php echo $installer->business_name; ?></td>
                        <td>
                            <?php 
                                $sql = "SELECT * FROM th_installer_data";
                                $row = $wpdb->get_results($sql);
                                ?>
                            <select class="installer-list" name="change_franchise" data-cur-instlr-id="<?=$installer_id?>" data-order="<?=$order_id?>" data-proid="<?=$value['product_id'];?>">
                                <option value="">Select Franchise</option>
                                <?php 
                                foreach ($row as $data) 
                                { ?>
                            <option value="<?php echo $data->installer_data_id; ?>"><?php echo $data->business_name; ?></option>
                                <?php
                                }
                                ?>
                            </select>
                        </td>
                    

                    </tr>
                <?php } ?>    
               
                </tbody>
        </table>
    </div>
</div>
   <?php
    
}


add_action('wp_ajax_installer_change_from_admin', 'installer_change_from_admin');
add_action('wp_ajax_nopriv_installer_change_from_adminf', 'installer_change_from_admin');
function installer_change_from_admin()
{
    global $wpdb;
    extract($_POST);
   
    installer_change_mail_send('invoice',$order_id,$_POST);
    die;
}


function installer_change_mail_send($document_type,$order_ids,$data){
         global $woocommerce,$wpdb;
         extract($data);
         $order_id=$order_ids;  
         //New Installer      
         $NewinstaSQL = "SELECT * FROM th_installer_data WHERE  installer_data_id='$insta_id'";
         $Newinsta = $wpdb->get_row($NewinstaSQL);
        
        //Current Installer
         $CurentInstaSQL = "SELECT * FROM th_installer_data WHERE  installer_data_id='$cur_instlr_id'";
         $CurentInsta = $wpdb->get_row($CurentInstaSQL);


        $installerSQL = "SELECT * FROM th_cart_item_installer WHERE order_id='$order_id' AND installer_id='$cur_instlr_id'";
        $voucher_installerSQL = "SELECT * FROM th_cart_item_service_voucher WHERE order_id='$order_id' AND installer_id='$cur_instlr_id'";


    $row = $wpdb->get_results($installerSQL); 
    if(!empty($row)) {
        foreach ($row as $key => $installer) {
            if($installer->order_id != 0){
                $product_arr1[$key]['order_id'] = $installer->order_id;
                $product_arr1[$key]['product_id'] = $installer->product_id;                
                $product_arr1[$key]['installer'] = $installer->installer_id;
                $product_arr1[$key]['supplier'] = $installer->installer_id;
                $product_arr1[$key]['vehicle_id'] = $installer->vehicle_id;
                $product_arr1[$key]['cart_item_key'] = $installer->cart_item_key;
              
            }
        }
    }

    $row1 = $wpdb->get_results($voucher_installerSQL); 
    if(!empty($row1)) {
        foreach ($row1 as $key => $installer) {
            if($installer->order_id != 0) {
                $product_arr2[$key]['order_id'] = $installer->order_id;
                $product_arr2[$key]['product_id'] = $installer->product_id;                
                $product_arr2[$key]['installer'] = $installer->installer_id;
                $product_arr2[$key]['supplier'] = $installer->installer_id;
                $product_arr2[$key]['cart_item_key'] = $installer->cart_item_key;
                $product_arr2[$key]['vehicle_id'] = $installer->vehicle_id;
            }
        }
    }

    if(!empty($product_arr2) && !empty($product_arr1) ){
       $order_pro= array_merge($product_arr1,$product_arr2);    
    }elseif(empty($product_arr2) && !empty($product_arr1)){
        $order_pro=$product_arr1;  
    }elseif(empty($product_arr1) && !empty($product_arr2)){
        $order_pro=$product_arr2; 
    }
               

                $where=array('installer_id'=>$cur_instlr_id,'order_id'=>$order_id);
                $data= array('installer_id' =>$insta_id);

                $updated = $wpdb->update('th_cart_item_installer', $data, $where );
                $updated = $wpdb->update('th_cart_item_service_voucher', $data,$where);

                    $where=array('franchise_id'=>$cur_instlr_id,'order_id'=>$order_id);
                    $data= array('franchise_id' =>$insta_id);
                    $updated = $wpdb->update('th_franchise_profit', $data, $where );

                /*if($CurentInsta->is_franchise=='yes'){
                     $where=array('franchise_id'=>$cur_instlr_id,'order_id'=>$order_id);
                     $data= array('franchise_id' =>$insta_id);
                    $updated = $wpdb->update('th_franchise_profit', $data, $where );   
                }else{
                    $wpdb->query('DELETE  FROM th_franchise_profit    WHERE franchise_id = "'.$cur_instlr_id.'" AND order_id = "'.$order_id.'"'); 
                }*/

                $order = wc_get_order($order_id);
                $order_data = $order->get_data(); // The Order data
                $order_date=$order->get_date_created();
                $order_id = $order_data['id'];
                $name = $order->get_billing_first_name().' '.$order->get_billing_last_name();

                $mobile=$order->get_billing_phone();
                $tyrehub_text = "New Installer has been assigned to your order number: ".$order_id.", Please check your email for your new service voucher or call to 18002335551";
                $tyrehub_text = trim(preg_replace('/\s+/', ' ', $tyrehub_text));
                $ch1 = curl_init();
                $tyrehub_text = str_replace(' ', '%20', $tyrehub_text);
                $url_string="http://nimbusit.co.in/api/swsend.asp?username=t1ankitshah&password=81957032&sender=TYREHB&sendto=91".$mobile."&message=".$tyrehub_text;

                curl_setopt($ch1, CURLOPT_URL, $url_string);
                curl_setopt($ch1, CURLOPT_RETURNTRANSFER, 1);
                curl_setopt($ch1, CURLOPT_CUSTOMREQUEST, "GET");
                $result1 = curl_exec($ch1);
                curl_close ($ch1);

                foreach ($order_pro as $key => $value) {
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
                     $allprodu[]=$variation_des;
                      $cart_item_key=$value['cart_item_key'];
            $QTYSQL = "SELECT * FROM th_cart_item_services WHERE order_id='$order_id' AND cart_item_key='$cart_item_key'";
                    $ResQTY=$wpdb->get_row($QTYSQL);

                    $finQty = $finQty + $ResQTY->tyre;
                 }

                 $prolist=implode(', ',$allprodu);
                $old_installer_monbile=$CurentInsta->contact_no;
                 $tyrehub_text = "Service order number ".$order_id.", for Tyre/Services ". $prolist." has been reassigned to some other service partner, order has been removed from your service list. If you have any concern, please call to 18002335551";
                $tyrehub_text = trim(preg_replace('/\s+/', ' ', $tyrehub_text));
                $ch1 = curl_init();
                $tyrehub_text = str_replace(' ', '%20', $tyrehub_text);
                $url_string="http://nimbusit.co.in/api/swsend.asp?username=t1ankitshah&password=81957032&sender=TYREHB&sendto=91".$old_installer_monbile."&message=".$tyrehub_text;

                curl_setopt($ch1, CURLOPT_URL, $url_string);
                curl_setopt($ch1, CURLOPT_RETURNTRANSFER, 1);
                curl_setopt($ch1, CURLOPT_CUSTOMREQUEST, "GET");
                $result1 = curl_exec($ch1);
                curl_close ($ch1);



                 $installer_mobile=$Newinsta->contact_no;
                 $installer_message = "Confirmed: Order for ".$prolist." and ".$finQty." Tyres is successfully placed by ".$name." for your store.";
                            $installer_message = trim(preg_replace('/\s+/', ' ', $installer_message));
                            $installer_message = str_replace(' ', '%20', $installer_message);

                            $ch1 = curl_init();
                            $url_string="http://nimbusit.co.in/api/swsend.asp?username=t1ankitshah&password=81957032&sender=TYREHB&sendto=91".$installer_mobile."&message=".$installer_message;
                            curl_setopt($ch1, CURLOPT_URL, $url_string);
                            curl_setopt($ch1, CURLOPT_RETURNTRANSFER, 1);
                            curl_setopt($ch1, CURLOPT_CUSTOMREQUEST, "GET");
                            $result1 = curl_exec($ch1);
                            curl_close ($ch1);


            $document_type = sanitize_text_field($document_type);            
            $order_ids = (array) array_map( 'absint', explode( 'x',$order_ids) );
            $document = wcpdf_get_document( $document_type, $order_ids, true );

            if ( $document ) {
                
                $tmp_path =  WPO_WCPDF()->main->get_tmp_path('attachments');
                $pdf_data1 = $document->get_pdf1();
                $filename1 = $document->get_filename1();
                $pdf_path1 = $tmp_path . $filename1;
                file_put_contents ( $pdf_path1, $pdf_data1 );
                $attachments[] = $pdf_path1;
               // print_r($attachments);
                // load the mailer class
                $mailer = WC()->mailer();
                
                //format the email
                $recipient = $order->get_billing_email();
                $subject = __("New Installer Details!", 'Tyrehub');
                //$content = get_custom_email_html( $order, $subject, $mailer );
                //ob_start();

                $template = untrailingslashit( plugin_dir_path(__FILE__) ) . '/email/customer-installer-change-mail-order.php';
                 include($template); //Template File Path
                 ob_get_contents();
                 ob_end_clean();
                $headers = "Content-Type: text/html\r\n";

                //send the email through wordpress
                $mailer->send( $recipient, $subject, $message1, $headers,$pdf_path1);

                die;
               
            } 

}
