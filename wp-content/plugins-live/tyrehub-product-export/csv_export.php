<?php
require_once("../../../wp-load.php");

if($_GET['action']=='export'){
			
			global $wpdb, $woocommerce;
$SQL="SELECT pmt.meta_value as tube_price,pmt1.meta_value as tyre_price,pmt2.meta_value as sale_price, spf.* FROM `th_supplier_products_final` spf 
LEFT JOIN wp_postmeta as pmt ON pmt.post_id=spf.product_id
LEFT JOIN wp_postmeta as pmt1 ON pmt1.post_id=spf.product_id
LEFT JOIN wp_postmeta as pmt2 ON pmt2.post_id=spf.product_id
LEFT JOIN wp_postmeta as pmt3 ON pmt3.post_id=spf.product_id
WHERE (pmt.meta_key='tube_price' AND pmt1.meta_key='tyre_price' AND pmt2.meta_key='_sale_price') AND spf.updated_date>DATE_SUB(NOW(),INTERVAL 1 YEAR)   GROUP by spf.product_id HAVING MIN(spf.tyre_price + spf.tube_price) ORDER BY (spf.tyre_price+spf.tube_price) ASC";
$results=$wpdb->get_results($SQL);

    if(count($results) > 0){
       $delimiter = ",";
        $filename = "products_" . date('Y-m-d') . ".csv";               
        //create a file pointer
       $fh = @fopen( 'php://output', 'w' );
        
        //set column headers
        $fields = array('Product ID', 'Product Name','Brand', 'Tube Price', 'Tyre Price','MRP', 'Sale Price', 'Supplier', 'Supplier Tube Price', 'Supplier Tyre Price', 'Percentage', 'Margin','Spp MRP', 'Total','Visible');
        fputcsv($fh, $fields, $delimiter);
        
        //output each row of the data, format line as csv and write to file pointer
        //$filename = $sitename . '_product.' . date( 'Y-m-d-H-i-s' ) . '.csv';
        header("Content-type: application/force-download");
        header( 'Content-Description: File Transfer' );
        header( 'Content-Disposition: attachment; filename=' . $filename );
        header( 'Content-Type: text/csv; charset=' . get_option( 'blog_charset' ), true );

        foreach ($results as $key => $value) {
            $product_id=$value->product_id;
            $SQLSHIV="SELECT * FROM `th_supplier_products_final` where product_id='$product_id' AND updated_date>DATE_SUB(NOW(),INTERVAL 1 YEAR)  GROUP by id HAVING min(tyre_price+tube_price) ORDER BY (tyre_price+tube_price) ASC LIMIT 0,1";
            $productsshiv=$wpdb->get_row($SQLSHIV);
            $supplier_id=get_post_meta($product_id,'active_supplier',true);

            $SQL="SELECT * FROM th_supplier_data WHERE supplier_data_id='$supplier_id'";
            $supplier=$wpdb->get_row($SQL);

            $lineData = array(
                        $product_id,
                        get_post_meta($product_id,'_variation_description',true),
                        get_post_meta($product_id,'attribute_pa_brand',true),
                        get_post_meta($product_id,'tube_price',true),
                        get_post_meta($product_id,'tyre_price',true),
                        get_post_meta($product_id,'_regular_price',true),
                        get_post_meta($product_id,'_sale_price',true),
                        $supplier->business_name,
                        $productsshiv->tube_price,
                        $productsshiv->tyre_price,
                        $productsshiv->flat_percentage,
                        $productsshiv->margin_price,
                        $productsshiv->mrp,
                        $productsshiv->total_price,
                        get_post_meta($product_id,'tyrehub_visible',true)
                        
                    );
           fputcsv($fh, $lineData, $delimiter);

         }
         fclose( $fh );    
         ob_end_flush();
           
    }
    exit;
   //  	$width = $_GET['width'];
	  //   $ratio = $_GET['ratio'];
	  //   $diameter = $_GET['diameter'];
	  //   $name = $_GET['category'];
	  //   $name = strtolower($name);

   //  $args = array(
   //          'post_type' => 'product_variation',
   //          'posts_per_page' => -1,
   //          'numberposts'   => -1,
   //          'orderby'       => 'menu_order',
   //          'order'         => 'asc',
   //      );
   //  if($width){
	  //   	$meta_query[]=array(
   //                          'key' => 'attribute_pa_width',
   //                          'value' => $width,
   //                          'compare' => 'IN',
   //                      );
   //  }
  	// if($diameter){
	  // 	$meta_query[]=array(
   //                          'key' => 'attribute_pa_diameter',
   //                          'value' => $diameter,
   //                          'compare' => 'IN',
   //                      );	
  	// }

  	// if($ratio){
  	// 	$meta_query[]=array(
   //                          'key' => 'attribute_pa_ratio',
   //                          'value' => $ratio,
   //                          'compare' => 'IN',
   //                      );
  	// }

  	// $meta_query[]=array(
   //                          'key'       => 'tyrehub_visible',
   //                          'value'     => array('yes','no','contact-us'),
   //                          'compare'   => 'IN',
   //                      );

  	// $args = array(
   //          'post_type' => 'product_variation',
   //          'posts_per_page' => -1,
   //          'numberposts'   => -1,
   //          'orderby'       => 'menu_order',
   //          'order'         => 'asc',
   //          'meta_query'=> array(                       
   //                      'relation' => 'AND',$meta_query
   //           ),            
   //          ); 


    
   //  	$variations = get_posts( $args );
   //  	/*echo '<pre>';
   //  	print_r($variations);
   //  	die;*/
   //     // $message .= ' Category: '.$name;
   //      if(!empty($variations))
   //      {
                  
   //          foreach ( $variations as $variation ) 
   //          {
   //              $variation_ID = $variation->ID;

   //              $product_variation = new WC_Product_Variation( $variation_ID );

   //              $variation_des = $product_variation->get_description();
   //              $variation_des = strtolower($variation_des);
   //              if($name){
	  //               if (strpos($variation_des, $name) !== false)
	  //               {
	  //                  $product_arr[] = $variation_ID; 
	  //               }	
   //              }else{
   //              	$product_arr[] = $variation_ID;	
   //              }
                
                
   //          }

   //          if(!empty($product_arr))
   //          {
   //              $args = array(
   //                  'post__in' => $product_arr,
   //                  'post_type' => 'product_variation',
   //                  'posts_per_page' => -1,
   //                  'numberposts'   => -1,
   //                  'orderby'       => 'menu_order',
   //                  'order'         => 'asc',
   //              );
   //              $variations = get_posts( $args );
   //          }
   //          else{
               
   //              $variations = '';
   //          }        
   //      }       
   //          $products=array();
   //          //echo '<pre>';
   //  	//print_r($variations);
   //  foreach ( $variations as $key => $variation ) 
   //      {
   //          $variation_ID = $variation->ID;
   //          //$variation_product_id[] = $variation_ID;
   //          $product_variation = new WC_Product_Variation( $variation_ID );

   //        //echo '<pre>';
   //  		//print_r($product_variation);

   //          $variation_data = $product_variation->get_data();

   //          $tyre_type = $variation_data['attributes']['pa_tyre-type'];


   //          $variation_des = $product_variation->get_description();
   //          $variation_price = $product_variation->get_price();
   //          $regular_price = $product_variation->get_regular_price();
   //          $sale_price = $product_variation->get_sale_price();

   //          $tyre_price = get_post_meta($variation_ID, 'tyre_price', true );
   //          $tube_price = get_post_meta($variation_ID, 'tube_price', true );
   //          $tyrehub_visible = get_post_meta($variation_ID, 'tyrehub_visible', true );

          
            
   //          if($sale_price == '')
   //          {
   //              $sale_price = $regular_price;
   //          }

   //          $discount = $regular_price - $sale_price;
   //          $dis_per = 100 * $discount / $regular_price;

   //          $dis_per = number_format($dis_per,2,".",".");
   //          $sale_price_original = $sale_price;
   //          $sale_price =  $sale_price;        

    
   //          $products[$key]['ID'] = $variation_ID;
			// $products[$key]['product']=$variation_des;
			// $products[$key]['category']=strtoupper($variation_data['attributes']['pa_brand']);
			// $products[$key]['tube_price']=  $tube_price;
			// $products[$key]['tyre_price']= $tyre_price;
			// $products[$key]['mrp']= $regular_price;
			// $products[$key]['web_price']= $sale_price;
			// $products[$key]['width']=str_replace("-",".",$variation_data['attributes']['pa_width']);
			// $products[$key]['ration']=$variation_data['attributes']['pa_ratio'];
			// $products[$key]['diameter']=$variation_data['attributes']['pa_diameter'];
   //          $products[$key]['visiblity']=$tyrehub_visible;
   //          $products[$key]['vehicle_type']=$variation_data['attributes']['pa_vehicle-type'];
   //          $products[$key]['tyre_type']=$variation_data['attributes']['pa_tyre-type'];
            
			
        
   //  }


			// if(count($products) > 0){
			//    $delimiter = ",";
			//     $filename = "products_" . date('Y-m-d') . ".csv";			    
			//     //create a file pointer
			//    $fh = @fopen( 'php://output', 'w' );
			    
			//     //set column headers
			//     $fields = array('Product ID', 'Product', 'Category', 'Tube Price', 'Tyre Price', 'MRP','Web Price',' Width','Ration','Diameter','Visiblity','Vehicle Type','Tyre Type');
			//     fputcsv($fh, $fields, $delimiter);
			    
			//     //output each row of the data, format line as csv and write to file pointer
			//     $filename = $sitename . '_product.' . date( 'Y-m-d-H-i-s' ) . '.csv';
			// 	header("Content-type: application/force-download");
	  //          	header( 'Content-Description: File Transfer' );
			// 	header( 'Content-Disposition: attachment; filename=' . $filename );
			// 	header( 'Content-Type: text/csv; charset=' . get_option( 'blog_charset' ), true );

			//     foreach ($products as $key => $value) {
			//         $lineData = array(
			// 		        	$value['ID'],
			// 		        	$value['product'],
			// 		        	$value['category'],
			// 		        	$value['tube_price'],
			// 		        	$value['tyre_price'],
			// 		        	$value['mrp'],
			// 		        	$value['web_price'],
			// 		        	$value['width'],
			// 		        	$value['ration'],
			// 		        	$value['diameter'],
   //                              $value['visiblity'],
   //                              $value['vehicle_type'],
   //                              $value['tyre_type']
			// 		        );
			//        fputcsv($fh, $lineData, $delimiter);

			//    	 }
			//    	 fclose( $fh );    
   // 				 ob_end_flush();

			   
		
			// exit;
			    
			       
			// }
			// exit;
	}
				
			    
?>