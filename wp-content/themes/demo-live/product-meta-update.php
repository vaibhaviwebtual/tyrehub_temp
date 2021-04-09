<?php
require_once("../../../wp-load.php");

global $wpdb;

$SQL="SELECT * FROM th_supplier_products_list";
$products=$wpdb->get_results($SQL);

foreach ($products as $key => $value) {
    # code...

    if($value->new_total_price){
    $tyre_total=$value->new_total_price;
    }else{
        $tyre_total=$value->old_total_price;
    }

    if($value->new_mrp){
        $mrp=$value->new_mrp;
    }else{
        $mrp=$value->old_mrp;
    }

    if($value->new_tube_price){
        $tube_price=$value->new_tube_price;
    }else{
        $tube_price=$value->old_tube_price;
    }

    if($value->new_tyre_price){
        $tyre_price=$value->new_tyre_price;
    }else{
        $tyre_price=$value->old_tyre_price;
    }
                            
    $update_data['product_id']=$value->product_id;
    $update_data['user_id']=get_current_user_id();
    $update_data['supplier_id']=$value->supplier_id;                            
    $update_data['tube_price']=$tube_price;
    $update_data['tyre_price']=$tyre_price;
    $update_data['flat_percentage']=$value->flat_percentage;
    $update_data['margin_price']=$value->margin_price;
    $update_data['mrp']=$mrp;
    $update_data['total_price']=$tyre_total;                                            
    $update_data['status']=1;
    $update_data['common_status']=1;
    $update_data['price_approved']=0;
    $update_data['visiblity']=0;                            
    $update_data['updated_date']=date('Y-m-d H:i:s');

    
    
    $wpdb->insert('th_supplier_products_final',$update_data);


    update_post_meta($value->product_id,'active_supplier',$value->supplier_id);
    update_post_meta($value->product_id,'active_date',date('d-m-Y'));
    update_post_meta($value->product_id,'update_date',date('d-m-Y'));
}

?>
