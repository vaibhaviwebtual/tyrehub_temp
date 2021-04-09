<?php


add_action('wp_ajax_search_product_data', 'search_product_data');
add_action('wp_ajax_nopriv_search_product_data', 'search_product_data');
function search_product_data()
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

                $product_variation = wc_get_product( $variation_ID );

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
        <button class="select-all-data">Select All</button>
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
            $product_variation = wc_get_product( $variation_ID );

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