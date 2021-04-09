<?php
add_action('wp_ajax_tir_franchise_info', 'tir_franchise_info');
add_action('wp_ajax_nopriv_tir_franchise_info', 'tir_franchise_info');
function tir_franchise_info()
{
    global $woocommerce,$wpdb;
    $installer_id = $_POST['installer_id'];
    $month = $_POST['month'];
    $year = $_POST['year'];
    $order_arr = [];
    $currency = get_woocommerce_currency_symbol();

  
            
                $start_date=$year.'-'.$month.'-01';
                //$end_date=$year.'-'.$month.'-31';
                $end_date=date("Y-m-t", strtotime($start_date));
                     
                
                 $SQL="SELECT * FROM `th_franchise_profit` WHERE franchise_id = '$installer_id' AND status='completed' AND payout_status='pending' AND DATE(completed_date) BETWEEN '".$start_date."' AND '".$end_date."'";
                 
                $profit_data=$wpdb->get_results($SQL);
        if($profit_data){
                        
                 foreach ($profit_data as $key => $value) {

                    $order = wc_get_order($value->order_id);
            $order_data = $order->get_data(); 
            
            // customer
            $first_name = $order_data['billing']['first_name'];
            $last_name = $order_data['billing']['last_name'];
            $mobile_no = $order_data['billing']['phone'];   
            $email = $order_data['billing']['email'];

                                 $variation_des='';
                                 $product_variation = new WC_Product_Variation($value->product_id);
                                    $variation_des = $product_variation->get_description();
                                     if(empty($variation_des)){
                                       $variation_des ='*No Tyre Purchase*';
                                     }
                                   
                                    $serviArra=array();
                                    $service='';
                                    if($value->balancing_price>0){
                                        $serviArra[0]['name']='Balancing';
                                        $serviArra[0]['image']=get_template_directory_uri().'/images/service-icon/balancing.png';
                                    }
                                    if($value->alignment_price>0){
                                        $serviArra[1]['name']='Alignment';
                                        $serviArra[1]['image']=get_template_directory_uri().'/images/service-icon/alignment.png';
                                    }
                                    if($value->carwash_price>0){
                                        $serviArra[2]['name']='Carwash';
                                        $serviArra[2]['image']=get_template_directory_uri().'/images/service-icon/carwash.png';
                                    }
                                    if($value->balancing_alignment>0){
                                        $serviArra[3]['name']='Balancing & Alignment';
                                        $serviArra[3]['image']=get_template_directory_uri().'/images/service-icon/alignment_balance.png';
                                    }
                                    if($value->single_carwash>0){
                                        $serviArra[4]['name']='Carwash';
                                        $serviArra[4]['image']=get_template_directory_uri().'/images/service-icon/carwash.png';
                                    }



                        $vehicle_id = $value->vehicle_id;
                    
                $vehicle_name = $wpdb->get_var("SELECT vehicle_type FROM th_vehicle_type WHERE vehicle_id='".$vehicle_id."' LIMIT 1" );           
                ?>
                <tr class="one-service <?php echo $class; ?>" data-id="<?php echo $value->profit_id; ?>">
                    
                        <td class="order-id">
                            <input type="checkbox" name="" class="service-select">
                            <strong>#<a target="_blank" href="<?php echo admin_url( 'post.php?post='.$order_id.'&action=edit'); ?>" ><?php echo $value->order_id; ?></a></strong>

                        </td>

                        <td>
                            <?php echo $variation_des; ?>                             
                        </td>

                        <td class="date">
                            <?php                       
                            date_default_timezone_set("Asia/Kolkata");
                            echo $newDate = date("d-m-Y H:i a", strtotime($value->completed_date));
                            ?> 
                        </td>

                        <td>
                            <?php
                            echo $first_name.' '.$last_name;
                            ?>
                        </td>
                        <td>
                            <?php 
                           echo $vehicle_name;
                            ?>
                        </td>
                        <td>
                            <?php 
                           foreach ($serviArra as $value1) {
                             echo $value1['name'].'<br>';
                            }
                            ?>
                        </td>
                        <td>
                            <?php 
                           echo $value->qty;
                            ?>
                        </td>
                            <td><div class="final-price" hidden=""><?php echo (int)$value->profit_with_gst; ?></div><?php echo $currency.(int)$value->profit_with_gst; ?></td>  
                            <td><a href="javascript:void(0);" id="<?=$value->profit_id;?>" class="single_view"><i class="fa fa-eye" style="font-size: 25px;"></i></a></td>        

                       
            </tr>
      <?php }?>
       
<?php }else{
    ?>
        <tr>
            <td colspan="6">No data found!</td>
        </tr>
    <?php
}

 die();

}

add_action('wp_ajax_tir_franchise_payout_history', 'tir_franchise_payout_history');
add_action('wp_ajax_nopriv_tir_franchise_payout_history', 'tir_franchise_payout_history');
function tir_franchise_payout_history()
{
    global $woocommerce,$wpdb;
    $installer_id = $_POST['installer_id'];
    $month = $_POST['month'];
    $year = $_POST['year'];
    $order_arr = [];
    $currency = get_woocommerce_currency_symbol();

  
                $start_date=$year.'-'.$month.'-01';
                //$end_date=$year.'-'.$month.'-31';
                $end_date=date("Y-m-t", strtotime($start_date));
                     
                if($installer_id){
                 $SQL="SELECT * FROM `th_franchise_profit` WHERE franchise_id = '$installer_id' AND status='completed' AND payout_status='paid' AND DATE(completed_date) BETWEEN '".$start_date."' AND '".$end_date."'";
                }else{
                   $SQL="SELECT * FROM `th_franchise_profit` WHERE  status='completed' AND payout_status='paid' AND DATE(completed_date) BETWEEN '".$start_date."' AND '".$end_date."'";  
                }
                $profit_data=$wpdb->get_results($SQL);
        if($profit_data){
                        
                 foreach ($profit_data as $key => $value) {

                    $SQL="SELECT pp.* FROM th_payout_history ph LEFT JOIN th_profit_payout as pp ON pp.payout_id=ph.payout_id WHERE ph.profit_id='".$value->profit_id."'";
                                $invoice=$wpdb->get_row($SQL);

                    $order = wc_get_order($value->order_id);
                    $order_data = $order->get_data(); 
            
            // customer
            $first_name = $order_data['billing']['first_name'];
            $last_name = $order_data['billing']['last_name'];
            $mobile_no = $order_data['billing']['phone'];   
            $email = $order_data['billing']['email'];

                                 $variation_des='';
                                 $product_variation = new WC_Product_Variation($value->product_id);
                                    $variation_des = $product_variation->get_description();
                                     if(empty($variation_des)){
                                       $variation_des ='*No Tyre Purchase*';
                                     }
                                   
                                    $serviArra=array();
                                    $service='';
                                    if($value->balancing_price>0){
                                        $serviArra[0]['name']='Balancing';
                                        $serviArra[0]['image']=get_template_directory_uri().'/images/service-icon/balancing.png';
                                    }
                                    if($value->alignment_price>0){
                                        $serviArra[1]['name']='Alignment';
                                        $serviArra[1]['image']=get_template_directory_uri().'/images/service-icon/alignment.png';
                                    }
                                    if($value->carwash_price>0){
                                        $serviArra[2]['name']='Carwash';
                                        $serviArra[2]['image']=get_template_directory_uri().'/images/service-icon/carwash.png';
                                    }
                                    if($value->balancing_alignment>0){
                                        $serviArra[3]['name']='Balancing & Alignment';
                                        $serviArra[3]['image']=get_template_directory_uri().'/images/service-icon/alignment_balance.png';
                                    }
                                    if($value->single_carwash>0){
                                        $serviArra[4]['name']='Carwash';
                                        $serviArra[4]['image']=get_template_directory_uri().'/images/service-icon/carwash.png';
                                    }



                        $vehicle_id = $value->vehicle_id;
                    
                $vehicle_name = $wpdb->get_var("SELECT vehicle_type FROM th_vehicle_type WHERE vehicle_id='".$vehicle_id."' LIMIT 1" );           
                ?>
                <tr class="one-service <?php echo $class; ?>" data-id="<?php echo $value->profit_id; ?>">
                    
                       <!--  <td class="order-id">
                            <strong><a target="_blank" href="<?php echo admin_url( 'post.php?post='.$order_id.'&action=edit'); ?>" ><?php echo $value->order_id; ?></a></strong>
 -->
                        </td>
                        <td><?=$invoice->invoice_no;?></td>
                        <td>
                            <?php echo $variation_des; ?>                             
                        </td>

                        <td class="date">
                            <?php                       
                            date_default_timezone_set("Asia/Kolkata");
                            echo $newDate = date("d-m-Y", strtotime($value->payout_date));
                            ?> 
                        </td>

                        <td>
                            <?php
                            echo $first_name.' '.$last_name;
                            ?>
                        </td>
                        <td>
                            <?php 
                           echo $vehicle_name;
                            ?>
                        </td>
                        <td>
                            <?php 
                           foreach ($serviArra as $value1) {
                             echo $value1['name'].'<br>';
                            }
                            ?>
                        </td>
                        <td>
                            <?php 
                           echo $value->qty;
                            ?>
                        </td>
                            <td><div class="final-price" hidden=""><?php echo $value->profit_with_gst; ?></div><?php echo $currency.$value->profit_with_gst; ?></td>          

                    <td><a href="javascript:void(0);" id="<?=$value->profit_id;?>" class="single_view"><i class="fa fa-eye" style="font-size: 25px;"></i></a></td>   
            </tr>
      <?php }?>
       
<?php }else{
    ?>
        <tr>
            <td colspan="6">No data found!</td>
        </tr>
    <?php
}

 die();

}


add_action('wp_ajax_admin_franchise_offline_orders', 'admin_franchise_offline_orders');
add_action('wp_ajax_nopriv_admin_franchise_offline_orders', 'admin_franchise_offline_orders');
function admin_franchise_offline_orders()
{
    global $woocommerce,$wpdb;
    $franchise_id =$_POST['installer_id'];
    $mobile =$_POST['mobile'];
    $start_date = $_POST['start_date'];
    $end_date = $_POST['end_date'];
    $order_arr = [];
    $currency = get_woocommerce_currency_symbol();   

        $posts = $wpdb->prefix . "franchises_order";       
        $where_search = '';
        if($franchise_id){
          $where_search .= ' AND franchise_id = '.$franchise_id ;
        }
        if($mobile){
          $where_search .= ' AND billing_phone = '.$mobile ;
        }
        
        if(!empty($_POST['start_date']) && !empty($_POST['end_date']) )
        {
            $where_search .= " AND DATE(date_completed) BETWEEN '".$start_date."' AND '".$end_date."'";
        }
   
        $SQL="SELECT * FROM $posts where 1=1  $where_search AND is_deleted='0' ORDER BY order_id DESC LIMIT 0,50";
        //echo $SQL;
        $all_posts = $wpdb->get_results($SQL);
       

        $count = $wpdb->get_var($wpdb->prepare("SELECT COUNT(order_id) FROM " . $posts . " where  where 1=1  $where_search", array() ) );

         $msg='';       
        if( $all_posts ):
            $msg .= '<table class = "offline-list table table-striped table-hover table-file-list shop_table shop_table_responsive my_account_orders">';
         
           foreach( $all_posts as $key => $post ):            
            $SQL="SELECT * FROM th_installer_data WHERE installer_data_id='".$post->franchise_id."' AND is_franchise='yes'";
            $franchise=$wpdb->get_row($SQL);

            $date_done = date("d-M-Y", strtotime($post->date_completed));
            $siturl = site_url('/thank-you/?order_id='.base64_encode($post->order_number));
            $pdfurl= admin_url().'/admin-ajax.php?action=offline_order_pdf&document_type=admin-offline-invoice&order_ids='.$post->order_id.'&service_id='.$post->order_id.'&_wpnonce=04e74a5779';
            $selected = '';


            $array1 = array('1' => 'Pending','2' => 'Completed');
            
            $status=($post->status==1)? 'Pending' : 'Completed';
                $msg .= '
                <tr class="order" id="odr'.$post->order_id.'">
                    <td>' .$post->order_number.'</a></td>
                    <td>' .$franchise->business_name.'</td>
                     <td>' .$post->billing_first_name.' '.$post->billing_last_name.'</td>
                    <td>' .$post->billing_phone.'</td>
                    <td>' .$date_done.'</td>
                    <td>'.$status.'</td>
                    <td>' .$currency.''.$post->total.'</td>
                    <td class="action">
                                
                                <a href="'.$pdfurl.'" target="_blank">
                                <i class="fa fa-file-pdf-o" aria-hidden="true"></i>
                                </a>                                
                    </td>
                </tr>';        
            endforeach;
            $msg .= '</table>';
        // If the query returns nothing, we throw an error message
        else:
            $msg .= '<p class = "bg-danger">No posts matching your search criteria were found.</p>';
        endif;
      echo $msg;
       exit();
  
}
/*<a href="javascript:void();" class="ord-delete" data-order="'.$post->order_id.'">
                                <i class="fa fa-trash" aria-hidden="true"></i>
                                </a>*/

add_action('wp_ajax_tir_franchise_invoice_report_data', 'tir_franchise_invoice_report_data');
add_action('wp_ajax_nopriv_tir_franchise_invoice_report_data', 'tir_franchise_invoice_report_data');
function tir_franchise_invoice_report_data(){
    $installer_id = $_POST['installer_id'];
    global $wpdb;
    $currency = get_woocommerce_currency_symbol();
   
if($installer_id){
 $sql = "SELECT * FROM  th_profit_payout  WHERE franchise_id='".$installer_id."' order by payout_id DESC";
}else{
   $sql = "SELECT * FROM  th_profit_payout  order by payout_id DESC";  
}
   
    $row = $wpdb->get_results($sql);

    if($row)
    {
        foreach ($row as $key => $value)
        {
            $date = $value->insert_date;
            $invoice_no = $value->invoice_no;
            $massage = $value->massage;
            $createdby = $value->created_by; 
            $user_meta=get_userdata($createdby);
            $user_login = $user_meta->user_login;

            $franchise_id = $value->franchise_id;
        $installer_name = $wpdb->get_var("SELECT business_name FROM th_installer_data WHERE installer_data_id='$franchise_id'" );

            $amount = $value->amount;
            $id = $value->payout_id;
            ?>
            <tr>
                <td><?php echo $invoice_no; ?></td>
                    <td><?php echo $date; ?></td>
                     <td><?php echo $massage; ?></td>
                    <td style="text-align: center;"><?php echo $user_login; ?></td>
                    <td><?php echo $installer_name; ?></td>
                    <td style="text-align: center;">
                        <a href="<?=get_admin_url();?>admin-ajax.php?action=franchise_report_pdf&document_type=paid-invoice-franchise&order_ids=<?php echo $id; ?>&payout_id=<?php echo $id; ?>&_wpnonce=04e74a5779" target="_blank">Download PDF</a>
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
            <td colspan="6">No invoice created for selected franchise!</td>
        </tr>
        <?php        
    }

die();
}

add_action('wp_ajax_profit_view_by_row_admin', 'profit_view_by_row_admin');
add_action('wp_ajax_nopriv_profit_view_by_row_admin', 'profit_view_by_row_admin');
function profit_view_by_row_admin()
{
    global $wpdb;
    $SQL="SELECT * FROM th_franchise_profit WHERE profit_id='".$_POST['profit_id']."'";
    $rowdata=$wpdb->get_row($SQL,ARRAY_A);
    echo json_encode($rowdata);
    die;
}
?>