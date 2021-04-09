
<?php
add_action('wp_ajax_installer_report_pdf', 'installer_report_pdf');
add_action('wp_ajax_nopriv_installer_report_pdf', 'installer_report_pdf');
function installer_report_pdf()
{
	$document_type = sanitize_text_field( $_GET['document_type'] );
 	$order_ids = (array) array_map( 'absint', explode( 'x', $_GET['order_ids'] ) );
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
                            do_action( 'wpo_wcpdf_created_manually', $document->get_pdf(), $document->get_filename() );
                        }
                        //$output_mode = WPO_WCPDF()->settings->get_output_mode( $document_type );
                        $document->output_pdf3('');
                        break;
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
        exit;
}

add_action('wp_ajax_installer_report_pdf1', 'installer_report_pdf1');
add_action('wp_ajax_nopriv_installer_report_pdf1', 'installer_report_pdf1');
function installer_report_pdf1()
{
    $document_type = sanitize_text_field( $_GET['document_type'] );
    $order_ids = (array) array_map( 'absint', explode( 'x', $_GET['order_ids'] ) );
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
                            do_action( 'wpo_wcpdf_created_manually', $document->get_pdf(), $document->get_filename() );
                        }
                        //$output_mode = WPO_WCPDF()->settings->get_output_mode( $document_type );
                        $document->output_pdf3();
                        break;
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
        exit;
}

add_action('wp_ajax_tir_installer_info', 'tir_installer_info');
add_action('wp_ajax_nopriv_tir_installer_info', 'tir_installer_info');
function tir_installer_info()
{
    global $woocommerce,$wpdb;
    $installer_id = $_POST['installer_id'];
    $month = $_POST['month'];
    $year = $_POST['year'];
    $order_arr = [];
    $currency = get_woocommerce_currency_symbol();

    $installer_city_id = $wpdb->get_var("SELECT city_id FROM th_installer_data WHERE installer_data_id ='".$installer_id."' LIMIT 1");

    //$installer = "SELECT * FROM th_cart_item_installer WHERE installer_id = '$installer_id'";
    //$voucher_installer = "SELECT * FROM th_cart_item_service_voucher WHERE installer_id = '$installer_id'";
    $start_date=$year.'-'.$month.'-01';
                //$end_date=$year.'-'.$month.'-31';
    $end_date=date("Y-m-t", strtotime($start_date));
     $voucher_installer = "SELECT * FROM th_cart_item_service_voucher
                                    WHERE installer_id = '$installer_id' and order_id!=0 and status = 'completed' and paid != 'yes' and CONVERT(STR_TO_DATE(substr(completed_date,1,10), '%d-%m-%Y'), DATE) BETWEEN '".$start_date."' AND '".$end_date."'";

        $installer = "SELECT * 
    FROM th_cart_item_installer
    WHERE   installer_id = '$installer_id' and  order_id!=0 and status = 'completed' and destination = 1 and paid != 'yes' and CONVERT(STR_TO_DATE(substr(completed_date,1,10), '%d-%m-%Y'), DATE) BETWEEN '".$start_date."' AND '".$end_date."' ";

    $row = $wpdb->get_results($installer); 



    if(!empty($row))
    {   
        foreach ($row as $key => $installer) 
        {
            if($installer->order_id != 0){
                $order_arr[$key]['order_id'] = $installer->order_id;
                $order_arr[$key]['product_id'] = $installer->product_id;
                $order_arr[$key]['cart_item_key'] = $installer->cart_item_key;
            }
            
        }
    }
    $row1 = $wpdb->get_results($voucher_installer); 
    if(!empty($row1))
    {
        foreach ($row1 as $key => $installer) 
        {
            if($installer->order_id != 0){
                $order_arr1[$key]['order_id'] = $installer->order_id;
                $order_arr1[$key]['product_id'] = $installer->product_id;
                $order_arr1[$key]['cart_item_key'] = $installer->cart_item_key;
    
            }
            
        }
    }
    $single_order_arr = array_merge($order_arr,$order_arr1);
    /*echo '<pre>';
    print_r($single_order_arr);
    echo '</pre>';*/
     //$order_arrOne = array_unique($single_order_arr);

    if($single_order_arr){
        foreach ($single_order_arr as $ordid) {
            # code...
             $order_id = $ordid['order_id'];
             $product_id = $ordid['product_id'];
            $cart_item_key=$ordid['cart_item_key'];
            $order = wc_get_order($order_id);
            $order_data = $order->get_data(); 
            $order_items = $order->get_items();
            //echo '<pre>';
            //print_r($order_items);
            
            $order_date = $order->order_date;
            $order_status = $order->get_status();
            $order_status_name = esc_html( wc_get_order_status_name( $order->get_status() ) );

            // customer
            $user = $order->get_user();
            $first_name = $order_data['billing']['first_name'];
            $last_name = $order_data['billing']['last_name'];
            $mobile_no = $order_data['billing']['phone'];   
            $email = $order_data['billing']['email'];

            // Rathod Savaji



                $sku = 'service_voucher';
                $service_voucher_prd = $wpdb->get_var("SELECT post_id FROM $wpdb->postmeta WHERE meta_key='_sku' AND meta_value='".$sku."' LIMIT 1");

                $product_variation = new WC_Product_Variation( $product_id );
                $variation_des = $product_variation->get_description();

                $parent_id = $product_variation->get_parent_id();
                $image = wp_get_attachment_image_src( get_post_thumbnail_id( $parent_id ), 'single-post-thumbnail' );
                //$prd_qty = $item_data['quantity'];
           
            if($product_id == $service_voucher_prd){
                    $service_data = "SELECT * 
                                    FROM th_cart_item_service_voucher
                                    WHERE cart_item_key = '$cart_item_key'";
            }else{
                $service_data = "SELECT * 
                                FROM th_cart_item_installer
                                WHERE cart_item_key = '$cart_item_key'";
            }   
                //echo $service_data;
                $data = $wpdb->get_row($service_data);
                $get_tyre_qty = "SELECT * 
                                FROM th_cart_item_services
                                WHERE cart_item_key = '$cart_item_key' LIMIT 0,1";
                $getQty = $wpdb->get_row($get_tyre_qty);
                $prd_qty = (!empty($getQty))? $getQty->tyre : 1;
                $flag = 1;            
                     $destination = $data->destination;
                    $completed_date = $data->completed_date;
                    $vehicle_id = $data->vehicle_id;
                    
                    $vehicle_name = $wpdb->get_var("SELECT vehicle_type FROM th_vehicle_type WHERE vehicle_id='".$vehicle_id."' LIMIT 1" );
 

                    if($product_id == $service_voucher_prd)
                    {
                        $service_voucher_id = $data->service_voucher_id;
                        $class =  'voucher';
                        $voucher_rate = $data->rate;
                        $qty = $data->qty;
                        $attr_value = $data->service_voucher_id;
                        $voucher_name = $data->voucher_name;

                        if($voucher_name == 'promotional' || $voucher_name == 'promotion'){
                            $service_type = 'Promotion Voucher';
                        }else{
                            $service_type = $vehicle_name;
                        }
                    }else{
                        $item_installer = $data->cart_item_installer_id;
                        $class =  'service';
                        $attr_value = $data->cart_item_installer_id;
                        $service_type = $vehicle_name;
                    }                          
                         
                ?>
                <tr class="one-service <?php echo $class; ?>" data-id="<?php echo $attr_value; ?>">
                    
                        <td class="order-id">
                            <input type="checkbox" name="" class="service-select">
                            <strong>Order #<a target="_blank" href="<?php echo admin_url( 'post.php?post='.$order_id.'&action=edit'); ?>" ><?php echo $order_id; ?></a></strong>

                        </td>

                        <td>
                            <?php                       
                            date_default_timezone_set("Asia/Kolkata");
                            echo $newDate = date("d-m-Y H:i a", strtotime($order_date));
                            ?>                              
                        </td>

                        <td class="date">
                            <?php echo $completed_date; ?>
                        </td>

                        <td>
                            <?php
                            echo $first_name.' '.$last_name;
                            ?>
                        </td>

                        <td>
                            <?php echo $service_type; ?>
                        </td>
                        <div class="data-block">
                            <?php 

                            if($product_id == $service_voucher_prd)
                            {
                        ?>      <td><?php echo $voucher_name; ?></td>  
                         <td><?=$prd_qty;?></td>                             
                                <td><div class="final-price" hidden=""><?php echo $voucher_rate*$qty; ?></div><?php echo $currency.$voucher_rate*$qty; ?></td>
                                <?php
                            }
                            else
                            {
                                $services = "SELECT * 
                                FROM th_cart_item_services
                                WHERE product_id = '$product_id' and order_id = '$order_id'";

                                $row = $wpdb->get_results($services);
                                $total = 0;
                                echo '<td>';
                                foreach ($row as $key => $service) 
                                {
                                    $service_id = $service->cart_item_services_id;
                                    $service_name = $service->service_name;
                                    $rate = $service->rate;
                                    $tyre = $service->tyre;
                                    $service_id = $service->service_data_id;
                                    

                                    if($service_name == 'Tyre Fitment')
                                    {
                                        if($rate == 0){
                                                                                
                                        $fitting_charge = $wpdb->get_var("SELECT rate FROM th_installer_service_price WHERE service_data_id='$service_id' and vehicle_id = '$vehicle_id' and city_id = '$installer_city_id' LIMIT 1");
                                          
                                        $rate = $fitting_charge;
                                        }
                                    }
                                    
                                    
                                    $charg = $rate * $tyre;
                                    $total = $total + $charg; 
                                    
                                    echo $service_name.'</br>'; 
                                }
                                echo '</td>';
                                //$final_total = $final_total + $total;
                            ?> 
                            <td><?=$prd_qty;?></td>
                            
                            <td><div class="final-price" hidden=""><?php echo $total; ?></div><?php echo $currency.$total; ?></td>          

                    <?php } ?>  
                    
                    </div>
                </tr>
                <?php

        }

    }

if($flag == 0){
    ?>
        <tr>
            <td colspan="6">No data found!</td>
        </tr>
    <?php
}
 die();

}

add_action('wp_ajax_tir_invoice_report_data', 'tir_invoice_report_data');
add_action('wp_ajax_nopriv_tir_invoice_report_data', 'tir_invoice_report_data');
function tir_invoice_report_data(){
    $installer_id = $_POST['installer_id'];
    global $wpdb;
    $currency = get_woocommerce_currency_symbol();
    $sql = "SELECT * FROM th_paid_service where installer_id = '$installer_id' order by id desc";
    $row = $wpdb->get_results($sql);
    if($row)
    {
        foreach ($row as $key => $value)
        {
            $date = $value->date;
            $invoice_no = $value->invoice_no;

            $createdby = $value->created_by; 
            $user_meta=get_userdata($createdby);
            $user_login = $user_meta->user_login;

            $installer_id = $value->installer_id;
            $installer_name = $wpdb->get_var( $wpdb->prepare( "SELECT business_name FROM th_installer_data WHERE installer_data_id='%s' LIMIT 1" , $installer_id) );

            $amount = $value->amount;
            $id = $value->id;
            ?>
            <tr>
                <td><?php echo $invoice_no; ?></td>
                    <td><?php echo $date; ?></td>
                    <td style="text-align: center;"><?php echo $user_login; ?></td>
                    <td><?php echo $installer_name; ?></td>
                    <td style="text-align: center;">
                        <a href="<?=get_admin_url();?>admin-ajax.php?action=installer_report_pdf&document_type=invoice&order_ids=3759&service_id=<?php echo $id; ?>&_wpnonce=04e74a5779" target="_blank">Download PDF</a>
                    </td>
                    <td style="text-align: center;"><?php echo $currency.number_format((float)$amount, 2, '.', ''); ?></td>
                     <td><a onclick="return confirm('Are you sure?')" href="?page=invoice-report-delete&invoice_id=<?php echo $id; ?>">Delete</a></td>
            </tr>
            <?php
        }
    }
    else{
        ?>
        <tr>
            <td colspan="6">No invoice created for selected installer!</td>
        </tr>
        <?php        
    }

die();
}
?>