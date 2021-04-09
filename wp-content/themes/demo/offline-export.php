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
       /* $csv_fields[] = 'Date';
        $csv_fields[] = 'Order.No';
        $csv_fields[] = 'Franchise';
        $csv_fields[] = 'Customer';
        $csv_fields[] = 'Phone';        
        $csv_fields[] = 'Status';
        $csv_fields[] = 'Amount';*/
		
		$csv_fields[] = 'Order.No';
		$csv_fields[] = 'Month';
		$csv_fields[] = 'Date';
        $csv_fields[] = 'Franchise';
        $csv_fields[] = 'Customer';
        $csv_fields[] = 'Phone';
		$csv_fields[] = 'Brand';
		$csv_fields[] = 'Tyre Size';
		$csv_fields[] = 'Tyre Qty';
		$csv_fields[] = 'Alignment';
		$csv_fields[] = 'Balancing';
		$csv_fields[] = 'Car Wash';
		$csv_fields[] = 'Vehical Type';
        $csv_fields[] = 'Status';
        $csv_fields[] = 'Total';
                 

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
			$month = date("M-y", strtotime($post->date_completed));			
            $selected = '';
            $array1 = array('1' => 'Pending','2' => 'Completed');            
            $status=($post->status==1)? 'Pending' : 'Completed';
			
			if($franchise->business_name == "ATOZ TYRE HUB PRIVATE LIMITED (Store-1)"){
				$franchise->business_name = "ATOZ Tyrehub(store-1)";
			}
			if($franchise->business_name == "ATOZ TYRE HUB PRIVATE LIMITED (Store-2)"){
				$franchise->business_name = "ATOZ Tyrehub(store-2)";
			}
			$brand = get_franchise_meta( $post->order_item_id, "pa_brand" );
			if($brand !=''){$brand = $brand;}else{$brand = "-";}	
			
			$width = get_franchise_meta( $post->order_item_id, "pa_width" );
			$ratio = get_franchise_meta( $post->order_item_id, "pa_ratio" );
			$diameter = get_franchise_meta( $post->order_item_id, "pa_diameter" );
			
			if($width !='' && $ratio!='' && $diameter!=''){$size = $width.'-'.$ratio.'-'.$diameter;}
			else{$size = "-";}	
			
			$qtys = get_franchise_meta( $post->order_item_id, "_qty" );
			if($qtys !=''){$qtys = $qtys;}else{$qtys = "-";}

			$vtype = get_franchise_meta( $post->order_item_id, "pa_vehicle-type" );
			if($vtype == 'car-tyre'){$vtype = "4Wheeler";}
			elseif($vtype == 'two-wheeler'){$vtype = "2Wheeler";}
			elseif($vtype == 'three-wheeler'){$vtype = "3Wheeler";}
			else{$vtype = "-";}			
			
      
             $lineData = array( $post->order_number,$month,$date_done,
                              $franchise->business_name,
                              $post->billing_first_name.' '.$post->billing_last_name,
                              $post->billing_phone,
							  $brand,
							  $size,
							  $qtys,
							  get_alignment_offline_data($post->order_number,3),
							  get_alignment_offline_data($post->order_number,2),
							  get_alignment_offline_data($post->order_number,5),
							  $vtype,
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