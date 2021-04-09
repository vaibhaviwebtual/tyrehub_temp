<?php


add_action('wp_ajax_search_product_csv_export_data', 'search_product_csv_export_data');
add_action('wp_ajax_nopriv_search_product_csv_export_data', 'search_product_csv_export_data');
function search_product_csv_export_data()
{
    $width = $_POST['width'];
    $ratio = $_POST['ratio'];
    $diameter = $_POST['diameter'];
    $name = $_POST['cat'];
    $name = strtolower($name);

    $args = array(
            'post_type' => 'product_variation',
            'posts_per_page' => -1,
            'numberposts'   => -1,
            'orderby'       => 'menu_order',
            'order'         => 'asc',
        );
  //  die();
    if($width != '' && $diameter !='' && $ratio != '')
    {
        $args = array(
            'post_type' => 'product_variation',
            'posts_per_page' => -1,
            'numberposts'   => -1,
            'orderby'       => 'menu_order',
            'order'         => 'asc',
            'meta_query'=> array(                       
                        'relation' => 'AND',
                        array(
                            'key' => 'attribute_pa_width',
                            'value' => $width,
                            'compare' => 'IN',
                        ),
                        array(
                            'key' => 'attribute_pa_ratio',
                            'value' => $ratio,
                            'compare' => 'IN',
                        ),
                        array(
                            'key' => 'attribute_pa_diameter',
                            'value' => $diameter,
                            'compare' => 'IN',
                        ),
                        array(
                            'key'       => 'tyrehub_visible',
                            'value'     => array('yes','contact-us'),
                            'compare'   => 'IN',
                        )
             ),            
            ); 
    }
    else if($width != '' && $ratio != '')
    {
        //echo 'yes';
        $args = array(
            'post_type' => 'product_variation',
            'posts_per_page' => -1,
            'numberposts'   => -1,
            'orderby'       => 'menu_order',
            'order'         => 'asc',
            'meta_query'=> array(                       
                        'relation' => 'AND',
                        array(
                            'key' => 'attribute_pa_width',
                            'value' => $width,
                            'compare' => 'IN',
                        ),
                        array(
                            'key' => 'attribute_pa_ratio',
                            'value' => $ratio,
                            'compare' => 'IN',
                        ),
                        array(
                            'key'       => 'tyrehub_visible',
                            'value'     => array('yes','contact-us'),
                            'compare'   => 'IN',
                        )
             ),            
            ); 
    }
    else if($width != '' && $diameter !='')
    {
        $args = array(
            'post_type' => 'product_variation',
            'posts_per_page' => -1,
            'numberposts'   => -1,
            'orderby'       => 'menu_order',
            'order'         => 'asc',
            'meta_query'=> array(                       
                        'relation' => 'AND',
                        array(
                            'key' => 'attribute_pa_width',
                            'value' => $width,
                            'compare' => 'IN',
                        ),
                        array(
                            'key' => 'attribute_pa_diameter',
                            'value' => $diameter,
                            'compare' => 'IN',
                        ),
                        array(
                            'key'       => 'tyrehub_visible',
                            'value'     => array('yes','contact-us'),
                            'compare'   => 'IN',
                        )
             ),            
            ); 
    }
    elseif($diameter !='' && $ratio != '')
    {
        $args = array(
            'post_type' => 'product_variation',
            'posts_per_page' => -1,
            'numberposts'   => -1,
            'orderby'       => 'menu_order',
            'order'         => 'asc',
            'meta_query'=> array(                       
                        'relation' => 'AND',
                        array(
                            'key' => 'attribute_pa_ratio',
                            'value' => $ratio,
                            'compare' => 'IN',
                        ),
                        array(
                            'key' => 'attribute_pa_diameter',
                            'value' => $diameter,
                            'compare' => 'IN',
                        ),
                        array(
                            'key'       => 'tyrehub_visible',
                            'value'     => array('yes','contact-us'),
                            'compare'   => 'IN',
                        )
             ),            
            ); 
    }
    else if($width != '')
    {
        
        $args = array(
            'post_type' => 'product_variation',
            'posts_per_page' => -1,
            'numberposts'   => -1,
            'orderby'       => 'menu_order',
            'order'         => 'asc',
            'meta_query'=> array(                       
                        'relation' => 'AND',
                        array(
                            'key' => 'attribute_pa_width',
                            'value' => $width,
                            'compare' => 'IN',
                        ),
                        /* array(
                            'key'       => 'tyrehub_visible',
                            'value'     => array('yes','contact-us'),
                            'compare'   => 'IN',
                        ),*/
            ),
        );
    }
    else if($ratio != '')
    {
        
        $args = array(
            'post_type' => 'product_variation',
            'posts_per_page' => -1,
            'numberposts'   => -1,
            'orderby'       => 'menu_order',
            'order'         => 'asc',
            'meta_query'=> array(                       
                        'relation' => 'AND',
                        array(
                            'key' => 'attribute_pa_ratio',
                            'value' => $ratio,
                            'compare' => 'IN',
                        ),
                         array(
                            'key'       => 'tyrehub_visible',
                            'value'     => array('yes','contact-us'),
                            'compare'   => 'IN',
                        ),
            ),
        );
    }
    else if($diameter != '')
    {
        $args = array(
            'post_type' => 'product_variation',
            'posts_per_page' => -1,
            'numberposts'   => -1,
            'orderby'       => 'menu_order',
            'order'         => 'asc',
            'meta_query'=> array(                       
                        'relation' => 'AND',
                        array(
                            'key' => 'attribute_pa_diameter',
                            'value' => $diameter,
                            'compare' => 'IN',
                        ),
                         array(
                            'key'       => 'tyrehub_visible',
                            'value'     => array('yes','contact-us'),
                            'compare'   => 'IN',
                        ),
            ),
        );
    }
    
    $variations = get_posts( $args );
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
                if (strpos($variation_des, $name) !== false)
                {
                    $product_arr[] = $variation_ID;
                }
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
    
    
           //   var_dump($variations);
    echo '<h2>Search Product List</h2>';     
    if(empty($variations))
    {
        echo 'No Product Found';
    }  
    else
    {
        ?>
        <button class="select-all">Select All</button>
            <div class="header">
                <div class="name"><strong>Name</strong></div>
                <div class="price"><strong>MRP</strong></div>
                <div class="price"><strong>Web Price</strong></div>
                <div class="add"><strong>Add</strong></div>
            </div>
        <?php
        foreach ( $variations as $variation ) 
        {
            $variation_ID = $variation->ID;
            //$variation_product_id[] = $variation_ID;
            $product_variation = new WC_Product_Variation( $variation_ID );

            $variation_des = $product_variation->get_description();
            $variation_price = $product_variation->get_price();
            $regular_price = $product_variation->get_regular_price();
            $sale_price = $product_variation->get_sale_price();

            $args = array(
                    'ex_tax_label'       => false,
                    'currency'           => '',
                    'decimal_separator'  => wc_get_price_decimal_separator(),
                    'thousand_separator' => wc_get_price_thousand_separator(),
                    'decimals'           => wc_get_price_decimals(),
                    'price_format'       => get_woocommerce_price_format(),
                  );

            $regular_price = wc_price( $regular_price, $args );
            if($sale_price == ''){
                $sale_price = '-';
            }else{
                $sale_price = wc_price( $sale_price, $args );
            }
            
            $parent_id = $product_variation->get_parent_id();
            $image = wp_get_attachment_image_src( get_post_thumbnail_id( $parent_id ), 'single-post-thumbnail' );
            $sku = get_post_meta($id, '_sku', true);       

            $variation = wc_get_product( $variation_ID );
    ?>
            <div class="single-product" data-id="<?php echo $variation_ID; ?>" id='<?php echo $variation_ID; ?>'>        

                <div class="name"><?php echo $variation_des; ?></div>
                <div class="price regular-price"><?php echo $regular_price; ?></div>
                <div class="price sale-price"><?php echo $sale_price; ?></div>
                
                <div class="send"><span>>></span></div>
            </div>
                <?php
        }
    }
die();
}


function products_csv_export(){
    ?>
    <div class="wrap product-csv-export" style="width: 95%;">        
        <div id="icon-users" class="icon32"><br/></div>
        <div class="admin-url" hidden=""><?php echo admin_url('admin-ajax.php'); ?></div>
        <h1 class="wp-heading-inline">Peoduct CSV Export</h1>
        
        <div class="">

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
            <button class="search-btn" id="csv_prodct_search">Search</button>
            <button class="search-btn" id="prodct_csv_export">CSV Export</button>
        <div class="message-block" style="width: 100%; float: left;"></div>
        </div>
        

        <div class="product-container ">
            <div class="product-details" style="width: 95%;">
                
                <h2>Search Results</h2>
                <div class="header">
                    <div class="id"><strong>id</strong></div>
                    <div class="name"><strong>Name</strong></div>
                    <div class="price"><strong>Tube price</strong></div>
                    <div class="price"><strong>Tyre Price</strong></div>
                    <div class="price"><strong>MRP</strong></div>
                    <div class="price"><strong>Web Price</strong></div>                
                </div>
                <div class="body"></div>
            </div>
            
        </div>
        
    </div>
    </div>
    <?php
}



//add_action('wp_ajax_change_product_price', 'change_product_price');
//add_action('wp_ajax_nopriv_change_product_price', 'change_product_price');
function change_product_price1()
{
    global $woocommerce , $wpdb;
    $prd_list = $_POST['prd_list'];
    $session_id = WC()->session->get_customer_id();
    $currentTime = date("Y-m-d h:i:sa");
    $result = [];
   
    $update_by = $_POST['update_by'];
    $tube_price_bulk = $_POST['tube_price_bulk'];
    $tyre_price_bulk = $_POST['tyre_price_bulk'];


    // for update M.R.P. price 
    foreach ($prd_list as $id => $list)
    {
        $product_id = $list['product_id'];
        $regular_price = get_post_meta( $product_id, '_regular_price', true );

        $new_regular_price = $list['price_list']['mrp_price_new'];

        if($new_regular_price != '' && $regular_price != $new_regular_price)
        {
            update_metadata( 'post', $product_id , '_regular_price' , $new_regular_price );  

            $insert = $wpdb->insert('th_change_product_price_log', array(
                                        'old_price' => $regular_price,
                                        'new_price' => $new_regular_price,
                                        'product_id' => $product_id,
                                        'price_type' => 'mrp_price',
                                        'date' => $currentTime,
                                        'user_id' => $session_id,
                                    ));          
        }
    }
    if($update_by == "bulk_update"){
        
        
        foreach ($prd_list as $id => $list)
        {
            $product_id = $list['product_id'];
            $sale_price = get_post_meta( $product_id, '_sale_price', true );
            $tyre_price = get_post_meta($product_id, 'tyre_price', true );
            $tube_price = get_post_meta($product_id, 'tube_price', true );
             
            $product_variation = new WC_Product_Variation( $product_id );

            $variation_data = $product_variation->get_data();

            $tyre_type = $variation_data['attributes']['pa_tyre-type'];

            $new_sale_price = $list['price_list']['sale_price'];
             
            $new_tyre_price = $list['price_list']['tyre_price'];
            $new_tube_price = $list['price_list']['tube_price'];

            if($sale_price != $new_sale_price)
            {
                if($tyre_type == 'tubetyre')
                {
                    update_metadata( 'post', $product_id , '_sale_price' , $new_sale_price );
                    update_metadata( 'post', $product_id , 'tyre_price' , $new_tyre_price );
                    update_metadata( 'post', $product_id , 'tube_price' , $new_tube_price );

                     $insert = $wpdb->insert('th_change_product_price_log', array(
                                        'old_price' => $sale_price,
                                        'new_price' => $new_sale_price,
                                        'product_id' => $product_id,
                                        'price_type' => 'sale_price',
                                        'date' => $currentTime,
                                        'user_id' => $session_id,
                                    ));
                    $insert = $wpdb->insert('th_change_product_price_log', array(
                                    'old_price' => $tube_price,
                                    'new_price' => $new_tube_price,
                                    'product_id' => $product_id,
                                    'price_type' => 'tube_price',
                                    'date' => $currentTime,
                                    'user_id' => $session_id,
                                ));
                    $insert = $wpdb->insert('th_change_product_price_log', array(
                                    'old_price' => $tyre_price,
                                    'new_price' => $new_tyre_price,
                                    'product_id' => $product_id,
                                    'price_type' => 'tyre_price',
                                    'date' => $currentTime,
                                    'user_id' => $session_id,
                                ));
                }
                else{
                    update_metadata( 'post', $product_id , '_sale_price' , $new_sale_price );
                       update_metadata( 'post', $product_id , 'tyre_price' , $new_tyre_price );

                    $insert = $wpdb->insert('th_change_product_price_log', array(
                                        'old_price' => $sale_price,
                                        'new_price' => $new_sale_price,
                                        'product_id' => $product_id,
                                        'price_type' => 'sale_price',
                                        'date' => $currentTime,
                                        'user_id' => $session_id,
                                    ));
                    
                    $insert = $wpdb->insert('th_change_product_price_log', array(
                                    'old_price' => $tyre_price,
                                    'new_price' => $new_tyre_price,
                                    'product_id' => $product_id,
                                    'price_type' => 'tyre_price',
                                    'date' => $currentTime,
                                    'user_id' => $session_id,
                                ));
                }
            }
             
        }

    }
    else{
        echo 'update by single';
        foreach ($prd_list as $id => $list)
        {
            
            echo $product_id = $list['product_id'];
            
            $sale_price = get_post_meta( $product_id, '_sale_price', true );
            $tyre_price = get_post_meta($product_id, 'tyre_price', true );
            $tube_price = get_post_meta($product_id, 'tube_price', true );
             
            $product_variation = new WC_Product_Variation( $product_id );
            $variation_data = $product_variation->get_data();
            $tyre_type = $variation_data['attributes']['pa_tyre-type'];

            $new_sale_price = $list['price_list']['sale_price'];             
            $new_tyre_price = $list['price_list']['tyre_price'];
            $new_tube_price = $list['price_list']['tube_price'];

            
            if($sale_price != $new_sale_price){
                echo 'sale price changed';

                if($tyre_type == 'tubetyre')
                {
                    update_metadata( 'post', $product_id , '_sale_price' , $new_sale_price );
                    update_metadata( 'post', $product_id , 'tyre_price' , $new_tyre_price );
                    update_metadata( 'post', $product_id , 'tube_price' , $new_tube_price );

                    $insert = $wpdb->insert('th_change_product_price_log', array(
                                        'old_price' => $sale_price,
                                        'new_price' => $new_sale_price,
                                        'product_id' => $product_id,
                                        'price_type' => 'sale_price',
                                        'date' => $currentTime,
                                        'user_id' => $session_id,
                                    ));
                    $insert = $wpdb->insert('th_change_product_price_log', array(
                                    'old_price' => $tube_price,
                                    'new_price' => $new_tube_price,
                                    'product_id' => $product_id,
                                    'price_type' => 'tube_price',
                                    'date' => $currentTime,
                                    'user_id' => $session_id,
                                ));
                    $insert = $wpdb->insert('th_change_product_price_log', array(
                                    'old_price' => $tyre_price,
                                    'new_price' => $new_tyre_price,
                                    'product_id' => $product_id,
                                    'price_type' => 'tyre_price',
                                    'date' => $currentTime,
                                    'user_id' => $session_id,
                                ));
                }
                else{
                    update_metadata( 'post', $product_id , '_sale_price' , $new_sale_price );
                    update_metadata( 'post', $product_id , 'tyre_price' , $new_tyre_price );

                    $insert = $wpdb->insert('th_change_product_price_log', array(
                                        'old_price' => $sale_price,
                                        'new_price' => $new_sale_price,
                                        'product_id' => $product_id,
                                        'price_type' => 'sale_price',
                                        'date' => $currentTime,
                                        'user_id' => $session_id,
                                    ));
                    
                    $insert = $wpdb->insert('th_change_product_price_log', array(
                                    'old_price' => $tyre_price,
                                    'new_price' => $new_tyre_price,
                                    'product_id' => $product_id,
                                    'price_type' => 'tyre_price',
                                    'date' => $currentTime,
                                    'user_id' => $session_id,
                                ));
                }
            }
            echo '--';
        }

    }    
    echo json_encode($result);
die();
}


add_action('wp_ajax_product_csv_data_search', 'product_csv_data_search');
add_action('wp_ajax_nopriv_product_csv_data_search', 'product_csv_data_search');
function product_csv_data_search()
{
    $width = $_POST['width'];
    $ratio = $_POST['ratio'];
    $diameter = $_POST['diameter'];
    $name = $_POST['cat'];
    $name = strtolower($name);

    $args = array(
            'post_type' => 'product_variation',
            'posts_per_page' => -1,
            'numberposts'   => -1,
            'orderby'       => 'menu_order',
            'order'         => 'asc',
        );
  //  die();
    if($width != '' && $diameter !='' && $ratio != '')
    {
        $args = array(
            'post_type' => 'product_variation',
            'posts_per_page' => -1,
            'numberposts'   => -1,
            'orderby'       => 'menu_order',
            'order'         => 'asc',
            'meta_query'=> array(                       
                        'relation' => 'AND',
                        array(
                            'key' => 'attribute_pa_width',
                            'value' => $width,
                            'compare' => 'IN',
                        ),
                        array(
                            'key' => 'attribute_pa_ratio',
                            'value' => $ratio,
                            'compare' => 'IN',
                        ),
                        array(
                            'key' => 'attribute_pa_diameter',
                            'value' => $diameter,
                            'compare' => 'IN',
                        ),
                        array(
                            'key'       => 'tyrehub_visible',
                            'value'     => array('yes','contact-us'),
                            'compare'   => 'IN',
                        )
             ),            
            ); 
    }
    else if($width != '' && $ratio != '')
    {
        //echo 'yes';
        $args = array(
            'post_type' => 'product_variation',
            'posts_per_page' => -1,
            'numberposts'   => -1,
            'orderby'       => 'menu_order',
            'order'         => 'asc',
            'meta_query'=> array(                       
                        'relation' => 'AND',
                        array(
                            'key' => 'attribute_pa_width',
                            'value' => $width,
                            'compare' => 'IN',
                        ),
                        array(
                            'key' => 'attribute_pa_ratio',
                            'value' => $ratio,
                            'compare' => 'IN',
                        ),
                        array(
                            'key'       => 'tyrehub_visible',
                            'value'     => array('yes','contact-us'),
                            'compare'   => 'IN',
                        )
             ),            
            ); 
    }
    else if($width != '' && $diameter !='')
    {
        $args = array(
            'post_type' => 'product_variation',
            'posts_per_page' => -1,
            'numberposts'   => -1,
            'orderby'       => 'menu_order',
            'order'         => 'asc',
            'meta_query'=> array(                       
                        'relation' => 'AND',
                        array(
                            'key' => 'attribute_pa_width',
                            'value' => $width,
                            'compare' => 'IN',
                        ),
                        array(
                            'key' => 'attribute_pa_diameter',
                            'value' => $diameter,
                            'compare' => 'IN',
                        ),
                        array(
                            'key'       => 'tyrehub_visible',
                            'value'     => array('yes','contact-us'),
                            'compare'   => 'IN',
                        )
             ),            
            ); 
    }
    elseif($diameter !='' && $ratio != '')
    {
        $args = array(
            'post_type' => 'product_variation',
            'posts_per_page' => -1,
            'numberposts'   => -1,
            'orderby'       => 'menu_order',
            'order'         => 'asc',
            'meta_query'=> array(                       
                        'relation' => 'AND',
                        array(
                            'key' => 'attribute_pa_ratio',
                            'value' => $ratio,
                            'compare' => 'IN',
                        ),
                        array(
                            'key' => 'attribute_pa_diameter',
                            'value' => $diameter,
                            'compare' => 'IN',
                        ),
                        array(
                            'key'       => 'tyrehub_visible',
                            'value'     => array('yes','contact-us'),
                            'compare'   => 'IN',
                        )
             ),            
            ); 
    }
    else if($width != '')
    {
        
        $args = array(
            'post_type' => 'product_variation',
            'posts_per_page' => -1,
            'numberposts'   => -1,
            'orderby'       => 'menu_order',
            'order'         => 'asc',
            'meta_query'=> array(                       
                        'relation' => 'AND',
                        array(
                            'key' => 'attribute_pa_width',
                            'value' => $width,
                            'compare' => 'IN',
                        ),
                        /* array(
                            'key'       => 'tyrehub_visible',
                            'value'     => array('yes','contact-us'),
                            'compare'   => 'IN',
                        ),*/
            ),
        );
    }
    else if($ratio != '')
    {
        
        $args = array(
            'post_type' => 'product_variation',
            'posts_per_page' => -1,
            'numberposts'   => -1,
            'orderby'       => 'menu_order',
            'order'         => 'asc',
            'meta_query'=> array(                       
                        'relation' => 'AND',
                        array(
                            'key' => 'attribute_pa_ratio',
                            'value' => $ratio,
                            'compare' => 'IN',
                        ),
                         array(
                            'key'       => 'tyrehub_visible',
                            'value'     => array('yes','contact-us'),
                            'compare'   => 'IN',
                        ),
            ),
        );
    }
    else if($diameter != '')
    {
        $args = array(
            'post_type' => 'product_variation',
            'posts_per_page' => -1,
            'numberposts'   => -1,
            'orderby'       => 'menu_order',
            'order'         => 'asc',
            'meta_query'=> array(                       
                        'relation' => 'AND',
                        array(
                            'key' => 'attribute_pa_diameter',
                            'value' => $diameter,
                            'compare' => 'IN',
                        ),
                         array(
                            'key'       => 'tyrehub_visible',
                            'value'     => array('yes','contact-us'),
                            'compare'   => 'IN',
                        ),
            ),
        );
    }
    
    $variations = get_posts( $args );
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
                if (strpos($variation_des, $name) !== false)
                {
                    $product_arr[] = $variation_ID;
                }
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
    }  
    else
    {
        foreach ( $variations as $variation ) 
        {
            $variation_ID = $variation->ID;
            //$variation_product_id[] = $variation_ID;
            $product_variation = new WC_Product_Variation( $variation_ID );

            

            $variation_data = $product_variation->get_data();

            $tyre_type = $variation_data['attributes']['pa_tyre-type'];


            $variation_des = $product_variation->get_description();
            $variation_price = $product_variation->get_price();
            $regular_price = $product_variation->get_regular_price();
            $sale_price = $product_variation->get_sale_price();

            $tyre_price = get_post_meta($variation_ID, 'tyre_price', true );
            $tube_price = get_post_meta($variation_ID, 'tube_price', true );

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
            $sale_price_original = $sale_price;
            $sale_price = wc_price( $sale_price, $args );
            $regular_price_html = wc_price( $regular_price, $args );

            $parent_id = $product_variation->get_parent_id();
            $image = wp_get_attachment_image_src( get_post_thumbnail_id( $parent_id ), 'single-post-thumbnail' );
            $sku = get_post_meta($id, '_sku', true);       

            $variation = wc_get_product( $variation_ID );

            global $wpdb;
            $last_update = 'SELECT * from th_change_product_price_log where product_id = "'.$variation_ID .'" ORDER BY log_id DESC LIMIT 1';
            $last_update_data = $wpdb->get_results($last_update);

    ?>
            <div class="single-product" data-id="<?php echo $variation_ID; ?>" id='<?php echo $variation_ID; ?>'>        
            <div class="id">
                    
                <?php echo $variation_ID; ?> 
                </div>

                <div class="name">
                    <?php
                            echo $variation_des; ?>
                                
                </div>

                <div class="price tube-price">
                    <?php  
                    if($tyre_type == 'tubetyre')
                    { 
                        echo wc_price( $tube_price, $args ); ?><br>
                        <input type="number" value="<?php  echo $tube_price; ?>" class="tube-price-real" name="tube_price_real" data-price="<?php  echo $tube_price; ?>">
                        <!-- <input type="number" name="new_tub_price" class="new-tube-price"> -->
                    <?php   }
                    else{
                        echo '-';
                        ?>
                         <input type="hidden" value="-" class="tube-price-real" name="tube_price_real">
                         <?php
                    } ?>
                </div>

                 <div class="price tyre-price">
                    <?php echo wc_price( $tyre_price, $args ); ?><br>
                     <input type="number" value="<?php  echo $tyre_price; ?>" class="tyre-price-real" name="tyre_price_real" data-price="<?php  echo $tyre_price; ?>">
                   <!--  <input type="number" name="new_tyre_price" class="new-tyre-price"> -->
                </div>


                <div class="price regular-price" data-price="<?php echo $regular_price; ?>">
                    <?php echo $regular_price_html; ?><br>
                    <input type="hidden" value="<?php echo $regular_price ?>" name="mrp_price" class="mrp-price">
                    <input type="number" name="new_mrp_price" class="new-mrp-price">
                </div>


                <div class="price sale-price">
                    <?php echo $sale_price; ?>

                    <span style="color: red;">(<?php echo $dis_per; ?>%)</span>
                    <input type="number" value="<?php echo $sale_price_original; ?>" class="sale-price-real" name="sale_price_real" data-price="<?php echo $sale_price_original; ?>" disabled>
                  <!--   <input type="number" name="new_sale_price" class="new-sale-price"> -->
                </div>
               
            </div>
                <?php
        }
    }
die();
}

