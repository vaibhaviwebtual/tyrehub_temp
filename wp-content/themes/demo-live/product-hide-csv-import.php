<?php
require_once("../../../wp-load.php");
global $wpdb;

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
        $fields = array('Product ID', 'Product Name','Brand', 'Tube Price', 'Tyre Price','MRP', 'Sale Price', 'Supplier', 'Supplier Tube Price', 'Supplier Tyre Price', 'Percentage', 'Margin','Spp MRP', 'Total');
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
                        $productsshiv->total_price
                        
                    );
           fputcsv($fh, $lineData, $delimiter);

         }
         fclose( $fh );    
         ob_end_flush();
           
    }
    exit;
    
$SQL="SELECT pmt.meta_value as tube_price,pmt1.meta_value as tyre_price,pmt2.meta_value as sale_price, spf.* FROM `th_supplier_products_final` spf 
LEFT JOIN wp_postmeta as pmt ON pmt.post_id=spf.product_id
LEFT JOIN wp_postmeta as pmt1 ON pmt1.post_id=spf.product_id
LEFT JOIN wp_postmeta as pmt2 ON pmt2.post_id=spf.product_id
LEFT JOIN wp_postmeta as pmt3 ON pmt3.post_id=spf.product_id
WHERE (pmt.meta_key='tube_price' AND pmt1.meta_key='tyre_price' AND pmt2.meta_key='_sale_price') AND spf.updated_date>DATE_SUB(NOW(),INTERVAL 1 YEAR)   GROUP by spf.product_id HAVING MIN(spf.tyre_price + spf.tube_price) ORDER BY (spf.tyre_price+spf.tube_price) ASC";
$results=$wpdb->get_results($SQL);
echo '<table border="1">';
echo '<tr>';
echo '<th>ProductID</th>';
echo '<th>Live Price</th>';
echo '<th>Supplier Price</th>';
echo '<th>Difference</th>';
echo '</tr>';
foreach ($results as $key => $value) {

$SQLSHIV="SELECT * FROM `th_supplier_products_final` where product_id='$value->product_id' AND updated_date>DATE_SUB(NOW(),INTERVAL 1 YEAR)  GROUP by id HAVING min(tyre_price+tube_price) ORDER BY (tyre_price+tube_price) ASC LIMIT 0,1";
   $productsshiv=$wpdb->get_row($SQLSHIV);
//echo $value->product_id.'#'.$productsshiv->total_price.'='.$value->sale_price;
echo '<tr>';
echo '<td>'.$value->product_id.'</td>';
echo '<td>'.$value->sale_price.'</td>';
echo '<td>'.$productsshiv->total_price.'</td>';
echo '<td>'.($productsshiv->total_price - $value->sale_price).'</td>';
echo '</tr>';
    //update_post_meta($value->product_id,'_sale_price',round($productsshiv->total_price));
    //update_post_meta($value->product_id,'tyre_price',$productsshiv->tyre_price);
    //update_post_meta($value->product_id,'tube_price',$productsshiv->tube_price);

}
echo '<table>';
?>
