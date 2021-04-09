<?php
require_once("../../../wp-load.php");

global $wpdb;


$row = 1;
if (($handle = fopen("csv/product-price-supplier.csv", "r")) !== FALSE) {

    ?>
    <table border="1">
        <tr>
            <th>Product ID</th>
            <th>Tube Price</th>
            <th>Tyre Price</th>
            <th>Percentage</th>
            <th>Per Price</th>
            <th>Margin Price</th>
            <th>MRP Before</th>
             <th>MRP Per 10</th>
            <th>MRP</th>
            <th>Total</th>
        </tr>
  
          

    <?php
    while (($data = fgetcsv($handle, 1000, ",")) !== FALSE) {
        $num = count($data);
        
        if($row!=1){?>
            <tr>
                <td><?=$data[0];?></td>
                <td><?=$tube_price=$data[3];?></td>
                
               
            <?php 
            $product_id=$data[0];
            $tube_price=$data[3];
            $tyre_price=$data[4];
            $webprice=$data[6];
            $mrp=$data[5];

            if($tyre_price<2000){
                    $mar=70;
                    @$wprice=$tyre_price-$mar;
            }elseif($tyre_price>2001 && $tyre_price<5000){
                $mar=70;
                $wprice=$tyre_price-$mar;
            }else{
                $mar=70;
                $wprice=$tyre_price-$mar;
            }

            if($tube_price!=''){
                @$wprice=$wprice+$tube_price;
            }

            $per=number_format((round($wprice) * 4 / 104),2);
            $fwprice=round($wprice-$per);

            @$total=round(($fwprice+$mar+$per+$tube_price));

            if($total>$mrp){
              $ten_per=round((($total*10)/100));
              $mrp1=$mrp+$ten_per;  
            }else{
                $ten_per='';
              $mrp1=$mrp;  
            }

            //round($webprice * 28 / 128);
                echo '<td>'.$fwprice.'</td>'; 
                echo '<td>4</td>';
                echo '<td>'.$per.'</td>';
                echo '<td>'.$mar.'</td>';
                echo '<td>'.$mrp.'</td>';
                echo '<td>'.$ten_per.'</td>';
                echo '<td>'.$mrp1.'</td>';
                echo '<td>'.$total.'</td>';
               
            
            //echo '<br>';
            //update_post_meta($product_id, '_sale_price',$price);
            //update_post_meta($product_id, '_price',$price);
           //update_post_meta($product_id, 'tyre_price',$tyre_price);
             update_post_meta($product_id,'_regular_price',$mrp1); 
             
            /*$insert = $wpdb->insert('th_supplier_products', array(
                    'product_id' => $product_id,
                    'old_tube_price' => $tube_price,
                    'new_tube_price' =>0,
                    'new_tyre_price' =>0,
                    'old_tyre_price' =>$fwprice,
                    'old_mrp' => $mrp1,
                    'new_mrp' =>0,
                    'flat_percentage' =>4,
                    'margin_price' =>$mar,
                    'old_total_price' =>$total,                    
                    'updated_date' => date("Y-m-d h:i:sa"),
                    'user_id' =>get_current_user_id(),
                    'supplier_id' =>0,
                ));*/            
         
        }

        $row++;
    }?>
    </table>
    <?php

    fclose($handle);
}
?>
