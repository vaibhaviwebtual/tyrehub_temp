<?php
/* Template Name: Testing Page */ 

get_header(); ?>
<div>
    <ul class="Category-list">
        <?php 
        global $wpdb;
        
        $results = $wpdb->get_results("
            SELECT
            p.order_id,
            p.order_item_id,
            p.order_item_name,
            p.order_item_type,
            max( CASE WHEN pm.meta_key = '_product_id' and p.order_item_id = pm.order_item_id THEN pm.meta_value END ) as productID,
            max( CASE WHEN pm.meta_key = '_qty' and p.order_item_id = pm.order_item_id THEN pm.meta_value END ) as Qty,
            max( CASE WHEN pm.meta_key = '_variation_id' and p.order_item_id = pm.order_item_id THEN pm.meta_value END ) as variationID,
            max( CASE WHEN pm.meta_key = '_line_total' and p.order_item_id = pm.order_item_id THEN pm.meta_value END ) as lineTotal,
            max( CASE WHEN pm.meta_key = '_line_subtotal_tax' and p.order_item_id = pm.order_item_id THEN pm.meta_value END ) as subTotalTax,
            max( CASE WHEN pm.meta_key = '_line_tax' and p.order_item_id = pm.order_item_id THEN pm.meta_value END ) as Tax,
            max( CASE WHEN pm.meta_key = '_tax_class' and p.order_item_id = pm.order_item_id THEN pm.meta_value END ) as taxClass,
            max( CASE WHEN pm.meta_key = '_line_subtotal' and p.order_item_id = pm.order_item_id THEN pm.meta_value END ) as subtotal
            from
            wp_woocommerce_order_items as p,
            wp_woocommerce_order_itemmeta as pm
             where order_item_type = 'line_item' and
             p.order_item_id = pm.order_item_id
             group by
            p.order_item_id order by pm.order_item_id DESC
        ");
        
      
        
        foreach($results as $resultsnew)
        {
            $product_id =  $resultsnew->variationID;
            $order_item_id =  $resultsnew->order_item_id;
            $order_id =  $resultsnew->order_id;
            
            $sql_itemmeta = $wpdb->get_results("SELECT * from wp_woocommerce_order_itemmeta where order_item_id = ".$order_item_id."");
             
           $data = wc_get_order_item_meta( $order_item_id, 'pa_brand', true );
                
                if($data == '' && $product_id>0)
                {
                    //echo $order_item_id;
                    //wc_update_order_item_meta();
                    $brand=get_post_meta($product_id,'attribute_pa_brand',true);

                    //wc_add_order_item_meta($order_item_id,'pa_brand',$brand,false);
                    echo $product_id.'--'.$brand.'<br>';

                }
        }
        ?>
    </ul>
</div>
<?php
get_footer(); ?>