<?php 
function change_price(){
    ?>
    <div class="wrap change-price">        
        <div id="icon-users" class="icon32"><br/></div>
        <div class="admin-url" hidden=""><?php echo admin_url('admin-ajax.php'); ?></div>
        <h1 class="wp-heading-inline">Change Price</h1>
        
        <div class="">

            <?php 
               
            ?>
        <div class="prd-search">
            <strong>Select Options</strong>
            <strong>Width</strong><input type="text" name="width" class="select-width">
            <strong>Ration</strong><input type="text" name="ratio" class="select-ratio">
            <strong>Diameter</strong><input type="text" name="diameter" class="select-diameter">
            <select class="select-category">
                <option value="">Select Category</option>
                <?php 
                $taxonomy = 'product_cat';
                $terms=  get_terms($taxonomy);

                $terms_select="";
                foreach ($terms as $term)
                {
                    echo $terms_select='<option value="'.$term->name.'">'.$term->name.'</option>';
                }
                ?>
            </select>
            <select class="select-visiblity">
                <option value="">Select All Products</option>
                <option value="no">Hide Products</option>
                <option value="yes">Visible Products</option>
            </select>
            <button class="search-btn">Search</button>
        </div>
        

        <div class="product-container ">
            <div class="product-details">
                <div class="price-update-section">
               
                <div class="update-by">
                    <p>Update Tyre or Tube Price by </p>
                      <select class="select-update-by">
                        <option value="">Select option</option>
                        <option value="productvalue">By Value</option>
                        <option value="percentage">By Percentage</option>
                      </select> 
                </div> 
                <div class="update-tube-price">
                    <p>Tube Price</p>
                     <input type="number" name="new_tub_price" class="bulk-tube-price" placeholder="Enter value">

                </div> 
                <div class="update-tyre-price">
                    <p>Tyre Price</p>
                     <input type="number" name="new_tyre_price" class="bulk-tyre-price" placeholder="Enter value">                     
                </div> 
                
                </div>
                <h2>Search Results-(Price change will be applied to the following results product only)</h2>
                <div class="header">
                    <div class="id"><strong>id</strong></div>
                    <div class="name" style="width: 20%;"><strong>Name</strong></div>
                    <div class="price"><strong>Tube price</strong></div>
                    <div class="price"><strong>Tyre Price</strong></div>
                    <div class="price"><strong>Percentage</strong></div>
                    <div class="price"><strong>Margin Price</strong></div>
                    <div class="price"><strong>MRP</strong></div>
                    <div class="price"><strong>Web Price</strong></div>                
                    <div class="amount"style="text-align: center;width: 7%;"><strong>Visiblity</strong></div>
                    <div class="price"><strong>Last Updated</strong></div>
                </div>
                <div class="body"></div>
            </div>
            
        </div>
        <button class="change-price">Submit New Price</button>
        <div class="message-block" style="width: 100%; float: left;"></div>
    </div>
    </div>
    <?php
}

add_action('wp_ajax_change_product_price', 'change_product_price');
add_action('wp_ajax_nopriv_change_product_price', 'change_product_price');
function change_product_price()
{
    global $woocommerce , $wpdb;
    $prd_list = $_POST['prd_list'];
    $session_id = WC()->session->get_customer_id();
    $currentTime = date("Y-m-d h:i:sa");
    $result = [];
   
    $update_by = $_POST['update_by'];
    $tube_price_bulk = $_POST['tube_price_bulk'];
    $tyre_price_bulk = $_POST['tyre_price_bulk'];
    
foreach ($prd_list as $id => $list)
        {
            $update = 'no';
            $product_id = $list['product_id'];
            $spid = $list['spid'];
            $total = $list['price_list']['sale_price'];             
            $tyre_price = $list['price_list']['tyre_price'];
            $tube_price = $list['price_list']['tube_price'];
            $mrp_price_new = $list['price_list']['mrp_price_new'];
            $mrp_price = $list['price_list']['mrp_price'];
                        
            $percentage = $list['price_list']['percentage'];
            $margin_price = $list['price_list']['margin_price'];

            /*if($mrp_price_new){
                $mrp=$mrp_price_new;
            }else{
                $mrp=$mrp_price;
            }*/
           $mrp=$mrp_price_new;

           //if($mrp>=$total){
product_price_change_by_admin_and_supplier($spid,$tube_price,$tyre_price,$percentage,$margin_price,$mrp,$total,'adminprice');
           //}
    

        }
        
    echo json_encode($result);
    die();
}


add_action('wp_ajax_product_data_for_changeprice', 'product_data_for_changeprice');
add_action('wp_ajax_nopriv_product_data_for_changeprice', 'product_data_for_changeprice');
function product_data_for_changeprice()
{
     $width = $_POST['width'];
     $width=str_replace(".","-",$width);
    $ratio = $_POST['ratio'];
    $diameter = $_POST['diameter'];
    $name = $_POST['cat'];
    $visiblity = $_POST['visiblity'];
    $name = strtolower($name);

   $args = array(
            'post_type' => 'product_variation',
            'posts_per_page' => -1,
            'numberposts'   => -1,
            'orderby'       => 'menu_order',
            'order'         => 'asc',
        );
    if($width){
            $meta_query[]=array(
                            'key' => 'attribute_pa_width',
                            'value' => $width,
                            'compare' => 'IN',
                        );
    }
    if($diameter){
        $meta_query[]=array(
                            'key' => 'attribute_pa_diameter',
                            'value' => $diameter,
                            'compare' => 'IN',
                        );  
    }

    if($ratio){
        $meta_query[]=array(
                            'key' => 'attribute_pa_ratio',
                            'value' => $ratio,
                            'compare' => 'IN',
                        );
    }

    if($name){
        $meta_query[]=array(
                            'key' => '  attribute_pa_brand',
                            'value' => $name,
                            'compare' => 'IN',
                        );
    }
     $visiblityArra=array();

    if($visiblity=='yes'){
        $visiblityArra[]=$visiblity;
        $visiblityArra[]='contact-us';
    }elseif($visiblity=='no'){
       $visiblityArra[]=$visiblity; 
    }else{
        $visiblityArra[]='yes';
        $visiblityArra[]='no';
        $visiblityArra[]='contact-us';
    }
    

    $meta_query[]=array(
                            'key'       => 'tyrehub_visible',
                            'value'     => $visiblityArra,
                            'compare'   => 'IN',
                        );

    $args = array(
            'post_type' => 'product_variation',
            'posts_per_page' => -1,
            'numberposts'   => -1,
            'orderby'       => 'menu_order',
            'order'         => 'asc',
            'meta_query'=> array(                       
                        'relation' => 'AND',$meta_query
             ),            
            ); 
    
    $variations = get_posts($args);


    if($name != '')
    {
       // $message .= ' Category: '.$name;
        if(!empty($variations))
        {
                  
            foreach ( $variations as $variation ) 
            {
                $variation_ID = $variation->ID;

                $product_variation = new WC_Product_Variation( $variation_ID );

                $variation_des = $product_variation->get_description();
                $variation_des = strtolower($variation_des);
               
                /*if (strpos($variation_des, $name) !== false)
                {
                    $product_arr[] = $variation_ID;
                }*/

                $product_arr[] = $variation_ID;
                


            }
         
            if(!empty($product_arr))
            {
                $args = array(
                    'post__in' => $product_arr,
                    'post_type' => 'product_variation',
                    'posts_per_page' => -1,
                    'numberposts'   => -1,
                    'orderby'       => 'menu_order',
                    'order'         => 'asc',
                );
                $variations = get_posts( $args );


            }
            else{
               
                $variations = '';
            }        
        }       

    }
    
       
        if(empty($variations))
        {
            echo 'No Product Found';
        }else
        {
        
        foreach ( $variations as $variation ) 
        {
            $variation_ID = $variation->ID;
            //$variation_product_id[] = $variation_ID;
            $product_variation = new WC_Product_Variation( $variation_ID );
                global $wpdb;
                $SQL = "SELECT * FROM th_supplier_products where product_id = '$variation_ID'";
                $supplier_product = $wpdb->get_row($SQL);
                $product_id=$supplier_product->product_id;
                $spid=$supplier_product->id;
                $percentage=$supplier_product->flat_percentage;
                $margin_price=$supplier_product->margin_price;
            

            $variation_data = $product_variation->get_data();

            $tyre_type = $variation_data['attributes']['pa_tyre-type'];


            $variation_des = $product_variation->get_description();
            /*$variation_price = $product_variation->get_price();
            $regular_price = $product_variation->get_regular_price();
            $sale_price = $product_variation->get_sale_price();*/
                if($supplier_product->new_mrp){
                    $mrp=$supplier_product->new_mrp;
                }else{
                    $mrp=$supplier_product->old_mrp;
                }

                $new_tube_price =$supplier_product->new_tube_price;
                $old_tube_price =$supplier_product->old_tube_price;
                

                if($new_tube_price){
                    $tube_price=$new_tube_price;
                }else{
                    $tube_price=$old_tube_price;
                }
                $new_tyre_price =$supplier_product->new_tyre_price;
                $old_tyre_price =$supplier_product->old_tyre_price;

                if($new_tyre_price){
                    $tyre_price=$new_tyre_price;
                }else{
                    $tyre_price=$old_tyre_price;
                }

                $perce_price=(($tyre_price+$tube_price)*$percentage)/100;
                $margin=$margin_price;
                
                $tyre_total=($tyre_price+$tube_price+$perce_price+$margin);

            $variation_price =$tyre_total;
            $regular_price = $mrp;
            $sale_price = $tyre_total;


            $tyre_price = $tyre_price;
            $tube_price =$tube_price;

            $tyrehub_visible = get_post_meta($variation_ID,'tyrehub_visible',true);

            $args = array(
                    'ex_tax_label'       => false,
                    'currency'           => '',
                    'decimal_separator'  => wc_get_price_decimal_separator(),
                    'thousand_separator' => wc_get_price_thousand_separator(),
                    'decimals'           => wc_get_price_decimals(),
                    'price_format'       => get_woocommerce_price_format(),
                  );

            
            if($sale_price == '')
            {
                $sale_price = $regular_price;
            }

            $discount = $regular_price - $sale_price;
            $dis_per = 100 * $discount / $regular_price;

            $dis_per = number_format($dis_per,2,".",".");
            $sale_price_original = round($sale_price);
            $sale_price = wc_price(round($sale_price), $args );
            $regular_price_html = wc_price( $regular_price, $args );

            $parent_id = $product_variation->get_parent_id();
            $image = wp_get_attachment_image_src( get_post_thumbnail_id( $parent_id ), 'single-post-thumbnail' );
            $sku = get_post_meta($id, '_sku', true);       

            $variation = wc_get_product( $variation_ID );

            global $wpdb;
            $last_update = 'SELECT * from th_change_product_price_log where product_id = "'.$variation_ID .'" ORDER BY log_id DESC LIMIT 1';
            $last_update_data = $wpdb->get_results($last_update);

    ?>
            <div class="single-product" data-spid="<?php echo $spid; ?>" data-id="<?php echo $variation_ID; ?>" id='<?php echo $variation_ID; ?>'>        
                <div class="id">
                    
                            <?php echo $variation_ID; ?> 
                                
                </div>

                <div class="name" style="width: 20%;">
                    <?php
                            echo $variation_des; ?>
                                
                </div>

                <div class="price tube-price">
                    <?php  
                    if($tyre_type == 'tubetyre')
                    { 
                        echo wc_price( $tube_price, $args ); ?><br>
                        <input type="number" value="" class="tube-price-real" name="tube_price_real" data-price="<?php  echo $tube_price; ?>">
                        
                    <?php  }
                    else{
                        echo '-';
                        ?>
                         <input type="hidden" value="-" class="tube-price-real" name="tube_price_real">
                         <?php
                    } ?>
                </div>

                 <div class="price tyre-price">
                    <?php echo wc_price( $tyre_price, $args ); ?><br>                     
                      <input type="number" value="" class="tyre-price-real" name="tyre_price_real" data-price="<?php  echo $tyre_price; ?>">                   
                </div>
                <div class="price percentage tyre-price">
                    <?php echo $percentage; ?>%<br>                    
                      <input type="number" value="" class="flat_percentage" name="percentage" data-percentage="<?php  echo $percentage; ?>">                   
                </div>
                <div class="price tyre-price">
                    <?php echo wc_price( $margin_price, $args ); ?><br>                     
                      <input type="number" value="" class="margin-price-real" name="margin_price_real" data-margin="<?php  echo $margin_price; ?>">                   
                </div>


                <div class="price regular-price" data-price="<?php echo $regular_price; ?>">
                    <?php echo $regular_price_html; ?><br>
                    <input type="hidden" value="<?php echo $regular_price ?>" name="mrp_price" class="mrp-price">
                    <input type="number" name="new_mrp_price" class="new-mrp-price" data-mrp-price="<?php  echo $regular_price; ?>">
                </div>


                <div class="price sale-price">
                    <?php echo $sale_price; ?>

                    <span style="color: red;">(<?php echo $dis_per; ?>%)</span>
                    <input type="number" value="" class="sale-price-real" name="sale_price_real" data-sale-price="<?php echo $sale_price_original; ?>" readonly>
                    <span style="color: red; float: right;">(<span id="runtime_per"><?php echo $dis_per; ?></span>%)</span>
                    <!-- <input type="number" value="<?php echo $sale_price_original; ?>" class="sale-price-real" name="sale_price_real" data-price="<?php echo $sale_price_original; ?>" disabled> -->
                  <!--   <input type="number" name="new_sale_price" class="new-sale-price"> -->
                </div>
               
                
          
                <div class="amount" style="text-align: center; width: 7%;">
                    <?php echo $tyrehub_visible; ?>
                </div>
                <!-- <div class="visiblity"><?php echo $tyrehub_visible; ?></div> -->
                 <div class="price"><?php echo $last_update_data[0]->date; ?></div>
            </div>
                <?php
        }
    }
die();
}

