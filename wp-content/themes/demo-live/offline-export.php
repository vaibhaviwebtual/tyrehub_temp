<?php
require_once("../../../wp-load.php");

if($_GET['act']=='export'){
			
			global $wpdb, $woocommerce;

    $franchise_id =$_GET['installer_id'];
    $start_date = $_GET['datefrom'];
    $end_date = $_GET['dateto'];

        $order_arr = [];
    $currency = get_woocommerce_currency_symbol();   

        $posts = $wpdb->prefix . "franchises_order";       
        $where_search = '';
        if($franchise_id){
          $where_search .= ' AND franchise_id = '.$franchise_id ;
        }
        
        if(!empty($start_date) && !empty($end_date) )
        {
            $where_search .= " AND DATE(date_completed) BETWEEN '".$start_date."' AND '".$end_date."'";
        }
   
        $SQL="SELECT * FROM $posts where 1=1  $where_search AND is_deleted=0 ORDER BY order_id DESC";
        $all_posts = $wpdb->get_results($SQL);
        $count = $wpdb->get_var($wpdb->prepare("SELECT COUNT(order_id) FROM " . $posts . " where 1=1 $where_search AND is_deleted=0", array() ) );
        $delimiter=',';
        $csv_fields=array();
        $csv_fields[] = 'Date';
        $csv_fields[] = 'Order.No';
        $csv_fields[] = 'Franchise';
        $csv_fields[] = 'Customer';
        $csv_fields[] = 'Phone';        
        $csv_fields[] = 'Status';
        $csv_fields[] = 'Amount';

        $output_filename = 'offlineOrder_' .$start_date.'-'.$end_date. '.csv';
        $output_handle = @fopen( 'php://output', 'w' );

        

        // Insert header row
        fputcsv( $output_handle, $csv_fields,$delimiter );
        header("Content-type: application/force-download");
        header( 'Content-Description: File Transfer' );
        header( 'Content-Disposition: attachment; filename=' . $output_filename );
        header( 'Content-Type: text/csv; charset=' . get_option( 'blog_charset' ), true );


            $row=array();
           foreach( $all_posts as $key => $post ):            
            $SQL="SELECT * FROM th_installer_data WHERE installer_data_id='".$post->franchise_id."' AND is_franchise='yes'";
            $franchise=$wpdb->get_row($SQL);
            $date_done = date("d-M-Y", strtotime($post->date_completed));        
            $selected = '';
            $array1 = array('1' => 'Pending','2' => 'Completed');            
            $status=($post->status==1)? 'Pending' : 'Completed';
      
             $lineData = array( $date_done,$post->order_number,
                              $franchise->business_name,
                              $post->billing_first_name.' '.$post->billing_last_name,
                              $post->billing_phone,
                              $status,
                              $post->total
                            );

            fputcsv( $output_handle, $lineData,$delimiter);
            
            endforeach;
                        
            fclose( $output_handle );    
            ob_end_flush();
        die();
  
				
}			    
?>