<?php
require_once("../../../wp-load.php");

if($_GET['action']=='export'){
			
			global $wpdb, $woocommerce;

    	$status = $_GET['status'];
	    $gst_no = $_GET['gst_no'];
	    $start_date = $_GET['datefrom'];
	    $end_date = $_GET['dateto'];

    $args = array(
            'post_type' => 'shop_order',
            'posts_per_page' => -1,
            'numberposts'   => -1,
        );

    if($_GET['status']){
      $ordstatus=array('wc-'.$status);
    }else{
      $ordstatus=array('wc-on-hold','wc-pending','wc-completed','wc-refunded','wc-failed','wc-processing','wc-deltoinstaller','wc-customprocess');
    }
    $args['post_status']=$ordstatus;

    if($gst_no=='gst_yes'){
	    	$meta_query[]=array(
                            'key' => '_gst_no',
                            'value' =>'',
                            'compare' => '!=',
                        );
    }
    if($gst_no=='gst_no'){
            $meta_query[]=array(
                            'key' => '_gst_no',
                            'value' =>'',
                            'compare' => '=',
                        );
    }

  	if($start_date && $end_date){

      $meta_query[]=array(
                'key' =>'_wcpdf_invoice_date_formatted',
                // value should be array of (lower, higher) with BETWEEN
                'value' => array($start_date,$end_date),
                'compare' => 'BETWEEN',
                'type' => 'DATE'
               );
    }

    $args['meta_query']=array('relation' => 'AND',$meta_query);
    $the_query = new WP_Query( $args );

        if( $the_query->have_posts() )
        {      
            $orders=array();
            $key=0;
            $delimiter = ",";
                $filename = "order-report-" . date('Y-m-d') . ".csv";               
                //create a file pointer
               $fh = @fopen( 'php://output', 'w' );
                
                //set column headers
                $fields = array('Order Number', 'Invoice', 'Invoice Date', 'Total Amount', 'GST Total Amount', 'Order Status','Credit Note No',' Credit Note Date','Payment Type');
                fputcsv($fh, $fields, $delimiter);
                
                //output each row of the data, format line as csv and write to file pointer
                //$filename = 'productlist.' . date( 'Y-m-d-H-i-s' ) . '.csv';
                header("Content-type: application/force-download");
                header( 'Content-Description: File Transfer' );
                header( 'Content-Disposition: attachment; filename=' . $filename );
                header( 'Content-Type: text/csv; charset=' . get_option( 'blog_charset' ), true );


        while($the_query->have_posts() ) : $the_query->the_post();  
          $order_id = get_the_ID();  
          $order = wc_get_order($order_id);
          //echo '<pre>';
          //print_r($order->get_items());

            $order_items= $order->get_items();
          foreach ($order_items as $item_id => $item_data) {
              
              $sgst = $order->get_item_meta($item_id, 'sgst', true);
              $cgst = $order->get_item_meta($item_id, 'cgst', true);
              $service_sgst = $order->get_item_meta($item_id, 'service_sgst', true);
              $service_cgst = $order->get_item_meta($item_id, 'service_cgst', true);

          }
            $gst_total=($sgst+$cgst+$service_sgst+$service_cgst);
             $invoice= get_post_meta($order_id,'_wcpdf_invoice_number',true);
             $invoice_date= get_post_meta($order_id,'_wcpdf_invoice_date_formatted',true);
             $_order_total= get_post_meta($order_id,'_order_total',true);
             $_gst_no= get_post_meta($order_id,'_gst_no',true);
             $payment_type= get_post_meta($order_id,'_payment_method_title',true);         
             
             $credit_notes= get_post_meta($order_id,'_wcpdf_credit_notes_number',true);
             $credit_notes_date= get_post_meta($order_id,'_wcpdf_credit_notes_date_formatted',true);
              if($order->get_status() == 'customprocess')
                {

                  $ord_status= esc_html('Order Processing');
                }elseif($order->get_status() == 'processing')
                {
                  $ord_status= esc_html('Order Dispatched'); 
                }elseif($order->get_status() == 'completed')
                {
                  $ord_status= esc_html('Order Complete');
                }elseif($order->get_status() == 'on-hold')
                {
                  $ord_status= esc_html('Order Received');
                }else{
                  $ord_status= esc_html( wc_get_order_status_name( $order->get_status() ) );
                }

            
            
            $lineData = array($order_id,
                              $invoice,
                              $invoice_date,
                              number_format($_order_total,2),
                              number_format($gst_total,2),
                              $ord_status,
                              $credit_note,
                              $credit_notes_date,
                              $payment_type
                            );
                   fputcsv($fh, $lineData, $delimiter);
                   $key++;
          //Order num, date, inv no, inv date, total Total value, GST value,Credit not No
        endwhile;
            fclose( $fh );    
            ob_end_flush();
      
			exit;
	}
				
}			    
?>