<?php
add_action('wp_ajax_offline_order_pdf', 'offline_order_pdf');
add_action('wp_ajax_nopriv_offline_order_pdf', 'offline_order_pdf');
function offline_order_pdf()
{
	$document_type = sanitize_text_field( $_GET['document_type'] );
    $download = sanitize_text_field( $_GET['download'] );
    $local_order=$_GET['order_ids'];
 	$order_ids = (array) array_map( 'absint', explode( 'x', $local_order ) );
    $order_ids = array_reverse( $order_ids );
    if($document_type=='admin-offline-invoice'){
        $filename='offline-invoice-'.$local_order.'.pdf';
    }else{
       $filename=$document_type.'-'.$local_order.'.pdf';  
    }
    
   try {
            $document = wcpdf_get_document( $document_type, $order_ids, true );
            if ( $document ) {
                $output_format = WPO_WCPDF()->settings->get_output_format( $document_type );
                switch ( $output_format ) {
                    case 'html':
                        add_filter( 'wpo_wcpdf_use_path', '__return_false' );
                        $document->output_html();
                        break;
                    case 'pdf':
                    default:
                        if ( has_action( 'wpo_wcpdf_created_manually' ) ) {
                            do_action( 'wpo_wcpdf_created_manually', $document->get_pdf(), $filename);
                        }
                        $output_mode = WPO_WCPDF()->settings->get_output_mode($document_type);
                        $output_mode ='inline';
                        $upload_dir = wp_upload_dir();
                        $upload_base = trailingslashit( $upload_dir['basedir'] );
                        $tmp_base = $upload_base . 'wpo_wcpdf/attachments/';
                        $tmp_path = $tmp_base;
                        // get pdf data & store
                        $pdf_data = $document->get_pdf();
                        $filename = $filename;
                       $pdf_path = $tmp_path . $filename;
                       
                        file_put_contents ($pdf_path, $pdf_data );
                       $document->output_offline_pdf($output_mode,$filename);
                       /* if($download=='yes'){
                            $document->output_pdf();
                        }else{
                            $document->output_pdf($document_type);
                        }*/
                        
                        break;
                }
            } else {
                wp_die( sprintf( __( "Document of type '%s' for the selected order(s) could not be generated", 'woocommerce-pdf-invoices-packing-slips' ), $document_type ) );
            }
        } catch ( \Dompdf\Exception $e ) {
            $message = 'DOMPDF Exception: '.$e->getMessage();
            wcpdf_log_error( $message, 'critical', $e );
            wcpdf_output_error( $message, 'critical', $e );
        } catch ( \Exception $e ) {
            $message = 'Exception: '.$e->getMessage();
            wcpdf_log_error( $message, 'critical', $e );
            wcpdf_output_error( $message, 'critical', $e );
        } catch ( \Error $e ) {
            $message = 'Fatal error: '.$e->getMessage();
            wcpdf_log_error( $message, 'critical', $e );
            wcpdf_output_error( $message, 'critical', $e );
        }
        exit;
}
add_action('wp_ajax_franchise_report_pdf', 'franchise_report_pdf');
add_action('wp_ajax_nopriv_franchise_report_pdf', 'franchise_report_pdf');
function franchise_report_pdf()
{
    $document_type = sanitize_text_field( $_GET['document_type'] );
    $download = sanitize_text_field( $_GET['download'] );
    
    $payout_id = (array) array_map( 'absint', explode( 'x', $_GET['payout_id'] ) );
    $filename = 'paid-invoice-franchise-'.$_GET['payout_id'].'.pdf';
    
       try {
            $document = wcpdf_get_document( $document_type, $payout_id, true );
            if ( $document ) {
                $output_format = WPO_WCPDF()->settings->get_output_format( $document_type );
                switch ( $output_format ) {
                    case 'html':
                        add_filter( 'wpo_wcpdf_use_path', '__return_false' );
                        $document->output_html();
                        break;
                    case 'pdf':
                    default:
                        if ( has_action( 'wpo_wcpdf_created_manually' ) ) {
                            do_action( 'wpo_wcpdf_created_manually', $document->get_pdf(), $filename);
                        }
                        $output_mode = WPO_WCPDF()->settings->get_output_mode( $document_type );
                        $upload_dir = wp_upload_dir();
                        $upload_base = trailingslashit( $upload_dir['basedir'] );
                        $tmp_base = $upload_base . 'wpo_wcpdf/attachments/';
                        $tmp_path = $tmp_base;
                        // get pdf data & store
                        $pdf_data = $document->get_pdf();
                        $filename = $filename;
                       $pdf_path = $tmp_path . $filename;
                       
                        file_put_contents ( $pdf_path, $pdf_data );
                        
                        $document->output_paid_invoice_pdf($output_mode,$filename);
                        
                        break;
                }
            } else {
                wp_die( sprintf( __( "Document of type '%s' for the selected order(s) could not be generated", 'woocommerce-pdf-invoices-packing-slips' ), $document_type ) );
            }
        } catch ( \Dompdf\Exception $e ) {
            $message = 'DOMPDF Exception: '.$e->getMessage();
            wcpdf_log_error( $message, 'critical', $e );
            wcpdf_output_error( $message, 'critical', $e );
        } catch ( \Exception $e ) {
            $message = 'Exception: '.$e->getMessage();
            wcpdf_log_error( $message, 'critical', $e );
            wcpdf_output_error( $message, 'critical', $e );
        } catch ( \Error $e ) {
            $message = 'Fatal error: '.$e->getMessage();
            wcpdf_log_error( $message, 'critical', $e );
            wcpdf_output_error( $message, 'critical', $e );
        }
        exit;
}
function get_cust_ord_meta($order_id,$meta_key,$flag=true){
    global $wpdb;
    $SQLITEM="SELECT * FROM wp_francise_order_itemmeta WHERE order_item_id='".$order_id."' AND meta_key='".$meta_key."'";
    $ordermeta=$wpdb->get_row($SQLITEM);
    return $ordermeta->meta_value;
}
add_action('wp_ajax_franchise_product_add_to_cart', 'franchise_product_add_to_cart');
add_action('wp_ajax_nopriv_franchise_product_add_to_cart', 'franchise_product_add_to_cart');
function franchise_product_add_to_cart(){
    global $woocommerce,$wpdb;
    extract($_POST);
   
        foreach ($products as $key => $value) {
        # code...
        $product_id=$value['id'];
        $quantity=$value['qty'];
        
            $cart_qty=get_qty_by_product($product_id);
            $qty=($cart_qty + $quantity);
            if($qty>10){
               $product[]=$product_id;
               $qtyar[]=$cart_qty;
               break;
            }
        }
            if($product[0]==''){
                foreach ($products as $key => $value) {
                    # code...
                    $product_id=$value['id'];
                    $quantity=$value['qty'];
                     WC()->cart->add_to_cart($product_id,$quantity);
                }
            }
            
            $product_variation = new WC_Product_Variation($product[0]);            
            $variation_des = $product_variation->get_description();
            $returnData=array();
            $returnData['QTY']=$qtyar[0];
            $returnData['product']=$variation_des; 
            $returnData['cartQTY']=WC()->cart->get_cart_contents_count();           
            echo json_encode($returnData);
    die();
}
function get_qty_by_product($product_id){
    foreach(WC()->cart->get_cart() as $key=>$val)
        {
            $_product = $val['data'];
            /*$product_variation = new WC_Product_Variation( $_product->get_id() );            
            $variation_des = $product_variation->get_description();
            $qty = $val['quantity'];*/
            if($product_id == $_product->get_id())
            {
             return $val['quantity'];   
            }
        }
}
add_action('wp_ajax_get_pending_orders', 'get_pending_orders');
add_action('wp_ajax_nopriv_get_pending_orders', 'get_pending_orders');
function get_pending_orders()
{
    global $woocommerce,$wpdb;
     $user_id = get_current_user_id();
     $SQL="SELECT * FROM th_installer_data WHERE user_id='".$user_id."' AND is_franchise='yes'";
     $franchise=$wpdb->get_row($SQL);
     $car_wash=get_option("car_wash");
     $balancing_alignment=get_option("balancing_alignment");
     $SQLPENDING="SELECT fo.*,foi.*,foim.meta_value as product_id,foim1.meta_value as qty FROM `wp_franchises_order` fo
LEFT JOIN wp_franchise_order_items as foi ON fo.order_id=foi.order_id
LEFT JOIN wp_francise_order_itemmeta as foim ON foim.order_item_id=foi.order_item_id
LEFT JOIN wp_francise_order_itemmeta as foim1 ON foim1.order_item_id=foi.order_item_id WHERE fo.franchise_id='".$franchise->installer_data_id."' AND (foim.meta_key='_product_id' AND foim.meta_value!='$car_wash' AND foim.meta_key='_product_id' AND foim.meta_value!='$balancing_alignment')  AND foim1.meta_key='_qty' AND fo.is_deleted=0 AND (fo.status=1 OR fo.status=0) GROUP BY foim.meta_value";
     $pendingOrders=$wpdb->get_results($SQLPENDING);
    if($pendingOrders)
    {
        //  var_dump($prd_discount_arr);
        echo '<h5>Pending Order</h5>';
        foreach ( $pendingOrders as $key=>$variation )
        {
            $SQLPENDING="SELECT SUM(foim1.meta_value) as qty FROM `wp_franchises_order` fo
LEFT JOIN wp_franchise_order_items as foi ON fo.order_id=foi.order_id
LEFT JOIN wp_francise_order_itemmeta as foim ON foim.order_item_id=foi.order_item_id
LEFT JOIN wp_francise_order_itemmeta as foim1 ON foim1.order_item_id=foi.order_item_id WHERE fo.franchise_id='".$franchise->installer_data_id."' AND  foim1.meta_key='_qty' AND (fo.status=1 OR fo.status=0) AND (foim.meta_key='_product_id' AND foim.meta_value=".$variation->product_id." AND foim.meta_value!='$car_wash' AND foim.meta_key='_product_id' AND foim.meta_value!='$balancing_alignment') AND fo.is_deleted=0";
    $get_qty=$wpdb->get_row($SQLPENDING);
            $variation_ID = $variation->product_id;
            $qty = $get_qty->qty;
            $visiblity = get_post_meta($variation_ID, 'tyrehub_visible', true  );
            $product_variation = new WC_Product_Variation( $variation_ID );
            $variation_des = $product_variation->get_description();
            $_sale_price=get_post_meta($variation_ID,'_sale_price',true);
        if(!empty($franchise)){
/*$SQLSHIV="SELECT * FROM `th_supplier_products_final` where product_id='$variation_ID' AND updated_date>DATE_SUB(NOW(),INTERVAL 1 YEAR)  GROUP by id HAVING min(tyre_price+tube_price) ORDER BY (tyre_price+tube_price) ASC LIMIT 0,1";*/
$SQLSHIV="SELECT * FROM `th_supplier_products_final` where product_id='$variation_ID'  ORDER BY (tyre_price+tube_price) ASC LIMIT 0,1";

            $productsshiv=$wpdb->get_row($SQLSHIV);
            $tube_price = $productsshiv->tube_price;
            $tyre_price = $productsshiv->tyre_price;
            $variation_price=($tube_price +$tyre_price);
        }else{
            if($product_variation->get_price()<=$_sale_price){
                $variation_price = $_sale_price;
            }else{
                $variation_price = $product_variation->get_price();
            }
        }
       
            $args = array(
                'ex_tax_label'       => false,
                'currency'           => '',
                'decimal_separator'  => wc_get_price_decimal_separator(),
                'thousand_separator' => wc_get_price_thousand_separator(),
                'decimals'           => wc_get_price_decimals(),
                'price_format'       => get_woocommerce_price_format(),
                );
            $parent_id = $product_variation->get_parent_id();
            $image = wp_get_attachment_image_src( get_post_thumbnail_id( $parent_id ),'single-post-thumbnail');
            $sku = get_post_meta($id,'_sku',true);
            $variation = wc_get_product($variation_ID);
            $variation_price1 = wc_price($variation_price,$args);
            $variation = wc_get_product($variation_ID);
            ?>
                <div class="single-product demo" data-id="<?php echo $variation_ID; ?>">
                    <div class="image">
                        <img src="<?php  if($image[0] != ''){ echo $image[0]; }else{ echo bloginfo('template_url').'/images/no_img1.png'; } ?>" data-id="<?php echo $loop->post->ID; ?>">
                    </div>
                    <div class="name"><?php echo $variation_des; ?></div>
                    <div class="price" id="price<?php echo $variation_ID; ?>" data-price="<?=$variation_price?>"><?php echo $variation_price1; ?></div>
                    <div class="qty">
                        <select name="fran_quantity[]" id="fran_quantity<?php echo $variation_ID; ?>" data-proid="<?php echo $variation_ID; ?>">
                            <?php
                            for($i=0; $i<=10;$i++){?>
                            <option value="<?=$i;?>" <?php if($qty==$i) { echo 'selected'; }?>><?=$i?></option>
                            <?php } ?>
                        </select>
                    </div>
                    <?php if($visiblity == "contact-us"){ ?>
                            <a href="#" class="btn btn-invert button product_type_simple add_to_cart_button ajax_add_to_cart" rel="nofollow"><span>Please call us</span></a>
                        <?php
                            }else{ ?>
                                <input type="checkbox" class="frabaddtocart" id="addcart<?php echo $variation_ID; ?>" name="franaddtocart[]"  value="<?php echo $variation_ID; ?>" checked>
                        <?php  } ?>
                    <div class="price-total"><i class="fa fa-inr" aria-hidden="true"></i> <span class="price-total<?php echo $variation_ID; ?>"><?php  echo $variation_price*$qty; ?></span></div>
                </div>
                <?php
        }
    }
    die();
}
add_action('wp_ajax_get_wishlist_products', 'get_wishlist_products');
add_action('wp_ajax_nopriv_get_wishlist_products', 'get_wishlist_products');
function get_wishlist_products()
{
    global $woocommerce,$wpdb;
     $user_id = get_current_user_id();
     $SQL="SELECT * FROM th_installer_data WHERE user_id='".$user_id."' AND is_franchise='yes'";
     $franchise=$wpdb->get_row($SQL);
    $SQLPENDING="SELECT * FROM th_franchise_wishlist WHERE franchise_id='".$franchise->installer_data_id."'";
   
     $wishlist=$wpdb->get_results($SQLPENDING);
    if($wishlist)
    {
        //  var_dump($prd_discount_arr);
        foreach ($wishlist as $key=>$wish )
        {
            $_sale_price=get_post_meta($wish->product_id,'_sale_price',true);
      
            $visiblity = get_post_meta($wish->product_id, 'tyrehub_visible', true  );
            $product_variation = new WC_Product_Variation($wish->product_id);
            $variation_des = $product_variation->get_description();
            
            $args = array(
                'ex_tax_label'       => false,
                'currency'           => '',
                'decimal_separator'  => wc_get_price_decimal_separator(),
                'thousand_separator' => wc_get_price_thousand_separator(),
                'decimals'           => wc_get_price_decimals(),
                'price_format'       => get_woocommerce_price_format(),
                );
            $parent_id = $product_variation->get_parent_id();
            $image = wp_get_attachment_image_src( get_post_thumbnail_id( $parent_id ),'single-post-thumbnail');
            $sku = get_post_meta($id,'_sku',true);
            $variation = wc_get_product($wish->product_id);
            $variation_price1 = wc_price($_sale_price,$args);
            $variation = wc_get_product($wish->product_id);
            ?>
                <div class="single-product demo" data-id="<?php echo $wish->product_id; ?>">
                    <div class="image">
                        <img src="<?php  if($image[0] != ''){ echo $image[0]; }else{ echo bloginfo('template_url').'/images/no_img1.png'; } ?>" data-id="<?php echo $wish->product_id; ?>">
                    </div>
                    <div class="name"><?php echo $variation_des; ?></div>
                    <div class="price" id="price<?php echo $wish->product_id; ?>" data-price="<?=$variation_price?>"><?php echo $variation_price1; ?></div>
                    <div class="qty">
                        <select name="fran_quantity[]" id="fran_quantity<?php echo $wish->product_id; ?>" data-proid="<?php echo $wish->product_id; ?>">
                            <?php
                            for($i=0; $i<=10;$i++){?>
                            <option value="<?=$i;?>" <?php if($wish->qty==$i) { echo 'selected'; }?>><?=$i?></option>
                            <?php } ?>
                        </select>
                    </div>
                  
                    <div class="price-total"><a href="javascript:void();" class="wish-delete" id="wish-delete<?php echo $wish->product_id; ?>" data-id="<?php echo $wish->product_id; ?>" data-wishid="<?=$wish->wish_id;?>">Delete</a></div>
                </div>
                <?php
        }
    }
    die();
}
add_action('wp_ajax_wishlist_products_list', 'wishlist_products_list');
add_action('wp_ajax_nopriv_wishlist_products_list', 'wishlist_products_list');
function wishlist_products_list()
{
    global $woocommerce,$wpdb;
     $user_id = get_current_user_id();
     $SQL="SELECT * FROM th_installer_data WHERE user_id='".$user_id."' AND is_franchise='yes'";
     $franchise=$wpdb->get_row($SQL);
    $SQLPENDING="SELECT * FROM th_franchise_wishlist WHERE franchise_id='".$franchise->installer_data_id."'";
   
     $wishlist=$wpdb->get_results($SQLPENDING);
    if($wishlist)
    {
        //  var_dump($prd_discount_arr);
        echo '<h5>Admin Cart</h5>';
        foreach ($wishlist as $key=>$wish )
        {
            
             $_sale_price=get_post_meta($wish->product_id,'_sale_price',true);
        if(!empty($franchise)){
$SQLSHIV="SELECT * FROM `th_supplier_products_final` where product_id='$wish->product_id' AND updated_date>DATE_SUB(NOW(),INTERVAL 1 YEAR)  GROUP by id HAVING min(tyre_price+tube_price) ORDER BY (tyre_price+tube_price) ASC LIMIT 0,1";
            $productsshiv=$wpdb->get_row($SQLSHIV);
            $tube_price = $productsshiv->tube_price;
            $tyre_price = $productsshiv->tyre_price;
            $variation_price=($tube_price +$tyre_price);
        }
            $visiblity = get_post_meta($wish->product_id, 'tyrehub_visible', true  );
            $product_variation = new WC_Product_Variation($wish->product_id);
            $variation_des = $product_variation->get_description();
            $args = array(
                'ex_tax_label'       => false,
                'currency'           => '',
                'decimal_separator'  => wc_get_price_decimal_separator(),
                'thousand_separator' => wc_get_price_thousand_separator(),
                'decimals'           => wc_get_price_decimals(),
                'price_format'       => get_woocommerce_price_format(),
                );
            $parent_id = $product_variation->get_parent_id();
            $image = wp_get_attachment_image_src( get_post_thumbnail_id( $parent_id ),'single-post-thumbnail');
            $sku = get_post_meta($id,'_sku',true);
            $variation = wc_get_product($wish->product_id);
            $variation_price1 = wc_price($variation_price,$args);
            $variation = wc_get_product($wish->product_id);
            ?>
                <div class="single-product demo" data-id="<?php echo $wish->product_id; ?>">
                    <div class="image">
                        <img src="<?php  if($image[0] != ''){ echo $image[0]; }else{ echo bloginfo('template_url').'/images/no_img1.png'; } ?>" data-id="<?php echo $wish->product_id; ?>">
                    </div>
                    <div class="name"><?php echo $variation_des; ?></div>
                    <div class="price" id="price<?php echo $wish->product_id; ?>" data-price="<?=$variation_price?>"><?php echo $variation_price1; ?></div>
                    <div class="qty">
                        <select name="fran_quantity[]" id="fran_quantity<?php echo $wish->product_id; ?>" data-proid="<?php echo $wish->product_id; ?>">
                            <?php
                            for($i=0; $i<=10;$i++){?>
                            <option value="<?=$i;?>" <?php if($wish->qty==$i) { echo 'selected'; }?>><?=$i?></option>
                            <?php } ?>
                        </select>
                    </div>
                    <?php if($visiblity == "contact-us"){ ?>
                            <a href="#" class="btn btn-invert button product_type_simple add_to_cart_button ajax_add_to_cart" rel="nofollow"><span>Please call us</span></a>
                        <?php
                            }else{ ?>
                                <input type="checkbox" class="frabaddtocart" id="addcart<?php echo $wish->product_id; ?>" name="franaddtocart[]"  value="<?php echo $wish->product_id; ?>" checked>
                        <?php  } ?>
                      
                    <div class="price-total"><a href="javascript:void();" class="wish-delete" id="wish-delete<?php echo $wish->product_id; ?>" data-id="<?php echo $wish->product_id; ?>" data-wishid="<?=$wish->wish_id;?>">Delete</a></div>
                     <div class="price-total"><i class="fa fa-inr" aria-hidden="true"></i> <span class="price-total<?php echo $wish->product_id; ?>"><?php  echo $variation_price*$wish->qty; ?></span></div> 
                </div>
                <?php
        }
    }
    die();
}
add_action('wp_ajax_get_wishlist_total', 'get_wishlist_total');
add_action('wp_ajax_nopriv_get_wishlist_total', 'get_wishlist_total');
function get_wishlist_total()
{
    global $woocommerce,$wpdb;
     $user_id = get_current_user_id();
     $SQL="SELECT * FROM th_installer_data WHERE user_id='".$user_id."' AND is_franchise='yes'";
     $franchise=$wpdb->get_row($SQL);
  
 $SQLPENDING="SELECT * FROM th_franchise_wishlist WHERE franchise_id='".$franchise->installer_data_id."'";
   
     $wishlist=$wpdb->get_results($SQLPENDING);
    if($wishlist)
    {
        //  var_dump($prd_discount_arr);
        foreach ($wishlist as $key=>$wish )
        {
            
           
             if(!empty($franchise)){
$SQLSHIV="SELECT * FROM `th_supplier_products_final` where product_id='$wish->product_id' AND updated_date>DATE_SUB(NOW(),INTERVAL 1 YEAR)  GROUP by id HAVING min(tyre_price+tube_price) ORDER BY (tyre_price+tube_price) ASC LIMIT 0,1";
            $productsshiv=$wpdb->get_row($SQLSHIV);
            $tube_price = $productsshiv->tube_price;
            $tyre_price = $productsshiv->tyre_price;
            $variation_price=($tube_price +$tyre_price);
        }
            
       
            $shiv_total = $shiv_total + ($variation_price * $wish->qty);
            
        }
        echo $shiv_total;
    }
    die();
}
add_action('wp_ajax_update_wishlist_qty', 'update_wishlist_qty');
add_action('wp_ajax_nopriv_update_wishlist_qty', 'update_wishlist_qty');
function update_wishlist_qty()
{
     global $woocommerce,$wpdb;
     $user_id = get_current_user_id();
     $SQL="SELECT * FROM th_installer_data WHERE user_id='".$user_id."' AND is_franchise='yes'";
     $franchise=$wpdb->get_row($SQL);
     extract($_POST);
 $SQL="UPDATE  th_franchise_wishlist SET qty='".$qty."' WHERE product_id='".$proid."' AND franchise_id='".$franchise->installer_data_id."'";
$wpdb->query($SQL);
die;
}
add_action('wp_ajax_delete_wishlist_qty', 'delete_wishlist_qty');
add_action('wp_ajax_nopriv_delete_wishlist_qty', 'delete_wishlist_qty');
function delete_wishlist_qty()
{
     global $woocommerce,$wpdb;
     $user_id = get_current_user_id();
     $SQL="SELECT * FROM th_installer_data WHERE user_id='".$user_id."' AND is_franchise='yes'";
     $franchise=$wpdb->get_row($SQL);
     extract($_POST);
    echo $SQL="DELETE FROM th_franchise_wishlist WHERE wish_id='".$wish_id."' AND franchise_id='".$franchise->installer_data_id."'";
    $wpdb->query($SQL);
    die;
}
add_action('wp_ajax_get_pending_orders_total', 'pending_orders_total');
add_action('wp_ajax_nopriv_get_pending_orders_total', 'pending_orders_total');
function pending_orders_total()
{
    global $woocommerce,$wpdb;
     $user_id = get_current_user_id();
     $SQL="SELECT * FROM th_installer_data WHERE user_id='".$user_id."' AND is_franchise='yes'";
     $franchise=$wpdb->get_row($SQL);
     $car_wash=get_option("car_wash");
     $balancing_alignment=get_option("balancing_alignment");
     $SQLPENDING="SELECT fo.*,foi.*,foim.meta_value as product_id,foim1.meta_value as qty FROM `wp_franchises_order` fo
LEFT JOIN wp_franchise_order_items as foi ON fo.order_id=foi.order_id
LEFT JOIN wp_francise_order_itemmeta as foim ON foim.order_item_id=foi.order_item_id
LEFT JOIN wp_francise_order_itemmeta as foim1 ON foim1.order_item_id=foi.order_item_id WHERE fo.franchise_id='".$franchise->installer_data_id."' AND (foim.meta_key='_product_id' AND foim.meta_value!='$car_wash' AND foim.meta_key='_product_id' AND foim.meta_value!='$balancing_alignment') AND foim1.meta_key='_qty' AND fo.is_deleted=0 AND (fo.status=1 OR fo.status=0) GROUP BY foim.meta_value";
     $pendingOrders=$wpdb->get_results($SQLPENDING);
    if($pendingOrders)
    {
        //  var_dump($prd_discount_arr);
        foreach ( $pendingOrders as $key=>$variation )
        {
            $SQLPENDING="SELECT SUM(foim1.meta_value) as qty FROM `wp_franchises_order` fo
LEFT JOIN wp_franchise_order_items as foi ON fo.order_id=foi.order_id
LEFT JOIN wp_francise_order_itemmeta as foim ON foim.order_item_id=foi.order_item_id
LEFT JOIN wp_francise_order_itemmeta as foim1 ON foim1.order_item_id=foi.order_item_id WHERE fo.franchise_id='".$franchise->installer_data_id."' AND  foim1.meta_key='_qty' AND (fo.status=1 OR fo.status=0) AND (foim.meta_key='_product_id' AND foim.meta_value=".$variation->product_id." AND foim.meta_value!='$car_wash' AND foim.meta_key='_product_id' AND foim.meta_value!='$balancing_alignment') AND fo.is_deleted=0";
     $get_qty=$wpdb->get_row($SQLPENDING);
            $variation_ID = $variation->product_id;
            $qty = $get_qty->qty;
            $visiblity = get_post_meta($variation_ID, 'tyrehub_visible', true  );
            $product_variation = new WC_Product_Variation( $variation_ID );
            $variation_des = $product_variation->get_description();
            $_sale_price=get_post_meta($variation_ID,'_sale_price',true);
        if(!empty($franchise)){
$SQLSHIV="SELECT * FROM `th_supplier_products_final` where product_id='$variation_ID' AND updated_date>DATE_SUB(NOW(),INTERVAL 1 YEAR)  GROUP by id HAVING min(tyre_price+tube_price) ORDER BY (tyre_price+tube_price) ASC LIMIT 0,1";
            $productsshiv=$wpdb->get_row($SQLSHIV);
            $tube_price = $productsshiv->tube_price;
            $tyre_price = $productsshiv->tyre_price;
            $variation_price=($tube_price +$tyre_price) + (($tube_price +$tyre_price)*0)/100;
        }else{
            if($product_variation->get_price()<=$_sale_price){
                $variation_price = $_sale_price;
            }else{
                $variation_price = $product_variation->get_price();
            }
        }
       
        $shiv_total = $shiv_total + ($variation_price * $qty);
            
        }
        echo $shiv_total;
    }
    die();
}
add_action('wp_ajax_installer_admin_access_menu', 'installer_admin_access_menu');
add_action('wp_ajax_nopriv_installer_admin_access_menu', 'installer_admin_access_menu');
function installer_admin_access_menu(){ 
    global $woocommerce,$wpdb;
    extract($_POST);
    $otp = rand(100000,999999);
    $user_id = get_current_user_id();
	 $SQL_for_mail="SELECT * FROM th_installer_data as thid INNER JOIN wp_users as wu WHERE user_id='".$user_id."' AND is_franchise='yes' and thid.user_id = wu.ID ";
		 $franchise_m=$wpdb->get_row($SQL_for_mail);
		 $franchise_email=$franchise_m->user_email;
		
		//mail($franchise_email,"Tyrehub",$message);
		$headers = "From: Tyrehub Completed your order <sales@tyrehub.com>" . "\r\n";
 		$headers .= "MIME-Version: 1.0\r\n";
        $headers .= "Content-Type: text/html; charset=ISO-8859-1\r\n";
		$headers .= "CC: pdixit@tyrehub.com";
		 $message1 = "Dear Franchise Admin, your OTP for admin login is ".$otp." . Do not share with anyone. Thank You Tyrehub Team";
		
		//mail($franchise_email,"Tyrehub",$message1,$headers);
		wp_mail($franchise_email,"Tyrehub",$message1,$headers);
    $SQL="SELECT * FROM th_installer_data WHERE user_id='".$user_id."' AND is_franchise='yes'";
    $franchise=$wpdb->get_row($SQL);
    $mobile=$franchise->contact_no;
        $ch1 = curl_init();
        $message = "Dear Franchise Admin, your OTP for admin login is ".$otp." . Do not share with anyone. Thank You Tyrehub Team";
        $message = str_replace(' ', '%20', $message);
       $url_string="http://nimbusit.co.in/api/swsend.asp?username=t1ankitshah&password=81957032&sender=TYREHB&sendto=91".$mobile."&message=".$message;
        curl_setopt($ch1, CURLOPT_URL, $url_string);
        curl_setopt($ch1, CURLOPT_RETURNTRANSFER, 1);
        curl_setopt($ch1, CURLOPT_CUSTOMREQUEST, "GET");
        $result1 = curl_exec($ch1);
        curl_close ($ch1);
        $update = $wpdb->get_results("UPDATE `wp_users` SET otp = '$otp' WHERE ID = '$user_id'");
    die();
}
add_action('wp_ajax_admin_verify_otp', 'admin_verify_otp');
add_action('wp_ajax_nopriv_admin_verify_otp', 'admin_verify_otp');
function admin_verify_otp()
{   
    session_start();
    global $woocommerce,$wpdb;
    extract($_POST);
    $user_id = get_current_user_id();
    $otp = $verify_otp;
    $result = $wpdb->get_row("SELECT * from `wp_users` where otp = '$otp' AND ID = '$user_id'");
    //print_r($result);
    if($result){     
        $_SESSION['admin_access'] = time();
        echo 1;
    }
    else{
        echo 0;
    }
    die();
}
add_action('wp_ajax_admin_access_logout', 'admin_access_logout');
add_action('wp_ajax_nopriv_admin_access_logout', 'admin_access_logout');
function admin_access_logout()
{   
    session_start();
    unset($_SESSION['admin_access']);
    WC()->cart->empty_cart();
    die();
}
function profit_pending($order_id) {
        $status='pending';
        profit_order_satus_update($order_id,$status);
    }
    function profit_failed($order_id) {
        $status='failed';
        profit_order_satus_update($order_id,$status);
    }
    function profit_hold($order_id) {
        $status='hold';
        profit_order_satus_update($order_id,$status);
    }
    function profit_processing($order_id) {
        $status='processing';
        profit_order_satus_update($order_id,$status);
    }
    function profit_completed($order_id) {
        
        $status='completed';
        profit_order_satus_update($order_id,$status);
    }
    function profit_refunded($order_id) {
        $status='refunded';
        profit_order_satus_update($order_id,$status);
    }
    function profit_cancelled($order_id) {
        $status='cancelled';
        profit_order_satus_update($order_id,$status);
    }
    add_action( 'woocommerce_order_status_pending', 'profit_pending');
    add_action( 'woocommerce_order_status_failed', 'profit_failed');
    add_action( 'woocommerce_order_status_on-hold', 'profit_hold');
    // Note that it’s woocommerce_order_status_on-hold, not on_hold.
    add_action( 'woocommerce_order_status_processing', 'profit_processing');
    add_action( 'woocommerce_order_status_completed', 'profit_completed');
    add_action( 'woocommerce_order_status_refunded', 'profit_refunded');
    add_action( 'woocommerce_order_status_cancelled', 'profit_cancelled');
    function  profit_order_satus_update($order_id,$status){
        global $wpdb;
        $SQL="UPDATE th_franchise_profit SET status='".$status."',completed_date='".date('Y-m-d H:i:s')."' WHERE order_id='".$order_id."'";
        $wpdb->query($SQL);
    }
add_action('wp_ajax_profit_view_by_row', 'profit_view_by_row');
add_action('wp_ajax_nopriv_profit_view_by_row', 'profit_view_by_row');
function profit_view_by_row()
{
    global $wpdb;
    $SQL="SELECT * FROM th_franchise_profit WHERE profit_id='".$_POST['profit_id']."'";
    $rowdata=$wpdb->get_row($SQL,ARRAY_A);
    echo json_encode($rowdata);
    die;
}
add_action( 'wp_ajax_get_wallete_history', 'get_wallete_history' );
add_action( 'wp_ajax_nopriv_get_wallete_history', 'get_wallete_history' );
function get_wallete_history() {
       
    global $woocommerce , $wpdb;
    $franchise_id = get_current_user_id();
    $user_id = get_current_user_id();
    $SQL="SELECT * FROM th_installer_data WHERE user_id='".$user_id."' AND is_franchise='yes'";
    $franchise=$wpdb->get_row($SQL);
    $franchise_id=$franchise->installer_data_id;
    $msg = '';
   
    if( isset( $_POST['data']['page'] ) ){
        // Always sanitize the posted fields to avoid SQL injections
        $page = sanitize_text_field($_POST['data']['page']); // The page we are currently at
        //$name = sanitize_text_field($_POST['data']['th_name']); // The name of the column name we want to sort
        $name = 'id';
        $sort = sanitize_text_field($_POST['data']['th_sort']); // The order of our sort (DESC or ASC)
        $cur_page = $page;
        $page -= 1;
        $per_page = 10; // Number of items to display per page
        $previous_btn = true;
        $next_btn = true;
        $first_btn = true;
        $last_btn = true;
        $start = $page * $per_page;
        // The table we are querying from  
        $posts = "th_franchise_payment";
       
        $where_search = '';
       
        if($_POST['data']['search_type'] == 'status')
        {
            $where_search .= ' AND (tran_type LIKE "%%' . $_POST['data']['th_name'] . '%%") ';
            //$where_search = 'AND (status='.$_POST['data']['th_name'].')';
        }
        
        if(!empty( $_POST['data']['startdate']) && !empty( $_POST['data']['enddate']) )
        {
            $where_search .= " AND  DATE_FORMAT(created_at, '%Y-%m-%d') BETWEEN '".date('Y-m-d',strtotime($_POST['data']['startdate']))."' AND '".date('Y-m-d',strtotime($_POST['data']['enddate']))."'";
        }
        //echo "SELECT * FROM $posts where franchise_id = '$franchise_id' $where_search ORDER BY $name $sort LIMIT $start, $per_page";
        //echo "SELECT * FROM $posts where franchise_id = '$franchise_id' $where_search ORDER BY $name $sort LIMIT $start, $per_page";
         $SQL="SELECT * FROM $posts WHERE 1=1 AND franchise_id = '$franchise_id' $where_search ORDER BY $name $sort LIMIT $start, $per_page";
        $all_posts = $wpdb->get_results($SQL);
       
        $count = $wpdb->get_var($wpdb->prepare("SELECT COUNT(id) FROM " . $posts . " WHERE 1=1 AND  franchise_id = '$franchise_id' $where_search", array() ) );
       
        if( $all_posts ):
            $msg .= '<table class = "table table-striped table-hover table-file-list shop_table shop_table_responsive my_account_orders">';
            $msg .= '<tr>
                        <th>No</th>
                        <th>Transation Details</th>
                        <th>Date</th>
                        <th>Type</th>                       
                        <th>Debit</th>
                         <th>Credit</th>
                        <th>Available Balance</th>
                        
                    </tr>';
                    $i=1;
           foreach( $all_posts as $key => $post ):
            $date_done = date("d-M-Y", strtotime($post->created_at));
  
            $selected = '';
            //if($post->status == 1 || $post->status == 0){ $selected = "selected"; } 
            //if ($post->status == 2) { $selected = "selected"; } 
            $array1 = array('1' => 'Pending','2' => 'Completed');
                if($post->tran_type=='cr'){
                   $credit='<i class="fa fa-inr"></i> '.(int)$post->amount;
                   $trans_details='Payment received';
                   $type='CR';
                }else{
                   $credit='-'; 
                }
                if($post->tran_type=='dr'){
                   $debit='<i class="fa fa-inr"></i> '.(int)$post->amount;
                   $trans_details='Payment debited for order - ' .$post->order_id;
                   $type='DR';
                }else{
                   $debit='-'; 
                }
                $msg .= '
                <tr class="order ">
                    <td>' .$i.'</a></td>
                    <td>'.$post->transaction_details.'</td>
                    <td>' .$date_done.'</td>
                     <td>'.$type.'</td>
                    <td>'.$debit.'</td>
                    <td>'.$credit.'</td>
                    <td><i class="fa fa-inr"></i>' .(int)$post->close_balance.'</td>
                   
                    
                </tr>';
                $i++;        
            endforeach;
            $msg .= '</table>';
        // If the query returns nothing, we throw an error message
        else:
            $msg .= '<p class = "bg-danger">No records matching your search criteria were found.</p>';
        endif;
        $msg = "<div class='cvf-universal-content'>" . $msg . "</div><br class = 'clear' />";
        $no_of_paginations = ceil($count / $per_page);
        if ($cur_page >= 7) {
            $start_loop = $cur_page - 3;
            if ($no_of_paginations > $cur_page + 3)
                $end_loop = $cur_page + 3;
            else if ($cur_page <= $no_of_paginations && $cur_page > $no_of_paginations - 6) {
                $start_loop = $no_of_paginations - 6;
                $end_loop = $no_of_paginations;
            } else {
                $end_loop = $no_of_paginations;
            }
        } else {
            $start_loop = 1;
            if ($no_of_paginations > 7)
                $end_loop = 7;
            else
                $end_loop = $no_of_paginations;
        }
        if($count > 10) {
        $pag_container .= "
        <div class='cvf-universal-pagination'>
            <ul>";
        if ($first_btn && $cur_page > 1) {
            $pag_container .= "<li p='1' class='active'>First</li>";
        } else if ($first_btn) {
            $pag_container .= "<li p='1' class='inactive'>First</li>";
        }
        if ($previous_btn && $cur_page > 1) {
            $pre = $cur_page - 1;
            $pag_container .= "<li p='$pre' class='active'>Previous</li>";
        } else if ($previous_btn) {
            $pag_container .= "<li class='inactive'>Previous</li>";
        }
        for ($i = $start_loop; $i <= $end_loop; $i++) {
            if ($cur_page == $i)
                $pag_container .= "<li p='$i' class = 'selected' >{$i}</li>";
            else
                $pag_container .= "<li p='$i' class='active'>{$i}</li>";
        }
       
        if ($next_btn && $cur_page < $no_of_paginations) {
            $nex = $cur_page + 1;
            $pag_container .= "<li p='$nex' class='active'>Next</li>";
        } else if ($next_btn) {
            $pag_container .= "<li class='inactive'>Next</li>";
        }
        if ($last_btn && $cur_page < $no_of_paginations) {
            $pag_container .= "<li p='$no_of_paginations' class='active'>Last</li>";
        } else if ($last_btn) {
            $pag_container .= "<li p='$no_of_paginations' class='inactive'>Last</li>";
        }
        $pag_container = $pag_container . "
            </ul>
        </div>";
    }
       
        echo
        '<div class = "cvf-pagination-content">' . $msg . '</div>' .
        '<div class = "cvf-pagination-nav">' . $pag_container . '</div>';
       }
       exit();
 }
add_action('wp_ajax_get_franchise_amount', 'get_franchise_amount');
add_action('wp_ajax_nopriv_get_franchise_amount', 'get_franchise_amount');
function get_franchise_amount(){ 
    global $woocommerce,$wpdb;
    extract($_POST);
    $SQL="SELECT * FROM th_franchise_payment WHERE 1=1 AND franchise_id = '$franchise_id'  ORDER BY id DESC LIMIT 0,1";
    $balance = $wpdb->get_row($SQL);
    $SQL1="SELECT * FROM th_installer_data WHERE 1=1 AND installer_data_id = '$franchise_id'";
    $franchiseData = $wpdb->get_row($SQL1);
    $data=array();
    $data['franchise_name']=$franchiseData->business_name;
    $data['franchise_address']=$franchiseData->address;
    $data['contact_person']=$franchiseData->contact_person;
    $data['contact_number']=$franchiseData->contact_no;
    $data['balance']=(int)$balance->close_balance;
    echo json_encode($data);
    die();
}
add_action('wp_ajax_save_franchise_wallet_amount', 'save_franchise_wallet_amount');
add_action('wp_ajax_nopriv_save_franchise_wallet_amount', 'save_franchise_wallet_amount');
function save_franchise_wallet_amount(){ 
    global $woocommerce,$wpdb;
    extract($_POST);
    extract($_POST);
    $SQL="SELECT * FROM th_installer_data WHERE installer_data_id='".$franchise_id."' AND is_franchise='yes'";
    $franchiseData=$wpdb->get_row($SQL);
    $franchise_id=$franchiseData->installer_data_id;
    $SQL="SELECT * FROM th_franchise_payment WHERE 1=1 AND franchise_id = '$franchise_id'  ORDER BY id DESC LIMIT 0,1";
    $balance = $wpdb->get_row($SQL);
 
 if($tran_type=='cr'){
    $close_balance=($balance->close_balance + $amountamount);
    $msg='Amount ('.$amountamount.') credit successfully';
 }
 if($tran_type=='dr'){
    $close_balance=($balance->close_balance - $amountamount);
    $msg='Amount ('.$amountamount.') debit successfully';
 }
 $data=array(
            'franchise_id' => $franchise_id,
            'user_id'  => $franchiseData->user_id,
            'amount' =>$amountamount,
            'transaction_details'=>$description,
            'tran_type' => $tran_type,
            'close_balance' =>$close_balance,
            'status' =>1,
           );   
 $wpdb->insert('th_franchise_payment',$data);
    //echo $wpdb->last_query;
        $table_install = 'th_installer_data';
        $insert_wallet =  $wpdb->query("UPDATE ".$table_install." SET wallet_balance ='".$close_balance."' WHERE installer_data_id = '$franchise_id'");
        
        if($insert_wallet)
        {
            echo $msg;
        }
    die();
}
?>