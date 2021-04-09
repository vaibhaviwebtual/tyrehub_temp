<?php
require_once("../../../wp-load.php");

global $wpdb;

$row = 1;
if (($handle = fopen("csv/products_warranty.csv", "r")) !== FALSE) {

    
    while (($data = fgetcsv($handle, 1000, ",")) !== FALSE) {
        $num = count($data);
        
        if($row!=1){
            $product_id=$data[0];
            $product_desc=$data[1];
            $_guarantee=$data[3];
            $_guarantee_cart=$data[4];
            update_post_meta($product_id,'_guarantee',$_guarantee);
            update_post_meta($product_id,'_guarantee_cart',$_guarantee_cart);

	    
    	}

        $row++;
    }


    fclose($handle);
}

die;
$SQL="SELECT * FROM wp_posts  WHERE post_type='product' AND post_status='publish'";

$results=$wpdb->get_results($SQL);

	if(count($results) > 0){
	   $delimiter = ",";
	    $filename = "products_" . date('Y-m-d') . ".csv";			    
	    //create a file pointer
	   $fh = @fopen( 'php://output', 'w' );
	    
	    //set column headers
	    $fields = array('Product ID', 'Name','Brand', 'G/W Tyre profile info','G/W Cart & Invoice info.');
	    fputcsv($fh, $fields, $delimiter);
	    
	    //output each row of the data, format line as csv and write to file pointer
	    //$filename = $sitename . '_product.' . date( 'Y-m-d-H-i-s' ) . '.csv';
		header("Content-type: application/force-download");
       	header( 'Content-Description: File Transfer' );
		header( 'Content-Disposition: attachment; filename=' . $filename );
		header( 'Content-Type: text/csv; charset=' . get_option( 'blog_charset' ), true );

	    foreach ($results as $key => $value) {
	    	$term_list = wp_get_post_terms($value->ID, 'product_cat', array('fields' => 'all'));

	        $lineData = array(
			        	$value->ID,
			        	$value->post_title,
			        	$term_list[0]->name,
			        	get_post_meta($value->ID,'_guarantee',true),
			        	get_post_meta($value->ID,'_guarantee_cart',true)
			        	
			        );
	       fputcsv($fh, $lineData, $delimiter);

	   	 }
	   	 fclose( $fh );    
		 ob_end_flush();
	       
	}
	exit;
			    
?>