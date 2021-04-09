
<?php 
add_action('wp_ajax_get_taxonomy_value', 'get_taxonomy_value');
add_action('wp_ajax_nopriv_get_taxonomy_value', 'get_taxonomy_value');
function get_taxonomy_value()
{
	global $woocommerce;
	$taxonomy = $_POST['taxonomy'];
	$terms=  get_terms($taxonomy);
	
            $terms_select="";
            foreach ($terms as $term)
            {
                echo $terms_select='<option value="'.$term->name.'">'.$term->name.'</option>';
                $values_arr[$term->term_id]=$term->name;
            }
}

add_action('wp_ajax_add_new_meta_line', 'add_new_meta_line');
add_action('wp_ajax_nopriv_add_new_meta_line', 'add_new_meta_line');
function add_new_meta_line()
{
	?>
	<div class="row">
        <select class="taxonomy-selector">
        <?php 
        global $product ,$woocommerce;                                
       
         $tax_terms = get_taxonomies(array(), 'objects');
          
           foreach ($tax_terms as $tax_key=>$tax_obj)
            {
                if(!in_array("product", $tax_obj->object_type))
   
                    continue;
              
                $taxonomy_name = $tax_obj->labels->singular_name;
                $slug = $tax_obj->name;
                
                echo '<option value="'.$slug.'">'.$taxonomy_name.'</option>';
            }       

        ?>
        </select>
        <select class="taxonomy-value"></select>
    </div>
<?php
	die();
}


add_action('wp_ajax_save_product_list', 'save_product_list');
add_action('wp_ajax_nopriv_save_product_list', 'save_product_list');
function save_product_list()
{
	$rule_id = $_POST['rule_id'];
    $discount_amount = $_POST['discount_amount'];
    $product_id = $_POST['product_id'];
    $prd_status = $_POST['prd_status'];
    
    // get array from database column 
    // $OrderInfo = unserialize($order_table['DataBlob']); 

	global $woocommerce , $wpdb;

    $insert = $wpdb->insert('th_discount_product_list', array(
                                'rule_id' => $rule_id,
                                'amount' => $discount_amount,
                                'product_id' => $product_id,
                                'status' => $prd_status,
                            ));
   	echo $lastid = $wpdb->insert_id;
    die();
}

add_action('wp_ajax_save_product_meta', 'save_product_meta');
add_action('wp_ajax_nopriv_save_product_meta', 'save_product_meta');
function save_product_meta()
{
	echo $product_list_id = $_POST['product_list_id'];
	$meta_key = $_POST['meta_key'];
	$meta_value = $_POST['meta_value'];

	global $woocommerce , $wpdb;

    $insert = $wpdb->insert('th_discount_product_meta', array(
                                
                                'meta_key' => $meta_key,
                                'meta_value' => $meta_value,
                                'product_list_id' => $product_list_id,
                            ));
    
    die();
}


add_action('wp_ajax_save_discount_rule', 'save_discount_rule');
add_action('wp_ajax_nopriv_save_discount_rule', 'save_discount_rule');
function save_discount_rule(){

    $name = $_POST['name'];
    $start_date = $_POST['start_date'];
    $end_date = $_POST['end_date'];
    $status = $_POST['status'];
    $rule_img = $_POST['rule_img'];

    global $woocommerce , $wpdb;

    $insert = $wpdb->insert('th_discount_rule', array(
                            'name' => $name,
                            'supplier_id' => get_current_user_id(),
                            'start_date' => $start_date,
                            'end_date' => $end_date,
                            'status' => $status,
                            'rule_img' => $rule_img,
                            ));
    echo $lastid = $wpdb->insert_id;
    die();
}

add_action('wp_ajax_update_discount_rule', 'update_discount_rule');
add_action('wp_ajax_nopriv_update_discount_rule', 'update_discount_rule');
function update_discount_rule()
{
    $rule_id = $_POST['rule_id'];
    $name = $_POST['name'];
    $start_date = $_POST['start_date'];
    $end_date = $_POST['end_date'];
    $status = $_POST['status'];
    $rule_img = $_POST['rule_img'];

    global $woocommerce , $wpdb;

    $update = $wpdb->get_results("UPDATE th_discount_rule SET name = '$name',start_date = '$start_date',end_date = '$end_date',status = '$status',rule_img='$rule_img' WHERE  rule_id = '$rule_id'");

    $delete_installer = $wpdb->get_results("DELETE from th_discount_product_list WHERE rule_id = $rule_id");
    die();
}


add_action('wp_ajax_product_data_byid', 'product_data_byid');
add_action('wp_ajax_nopriv_product_data_byid', 'product_data_byid');
function product_data_byid()
{
    $width = $_POST['width'];
    $ratio = $_POST['ratio'];
    $diameter = $_POST['diameter'];
    $name = $_POST['cat'];
    $name = strtolower($name);


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

    $variations = get_posts( $args );
  
    
    
           //   var_dump($variations);
    echo '<h2>Search Product List</h2>';     
    if(empty($variations))
    {
        echo 'No Product Found';
    }  
    else
    {
        ?>
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
                <div class="amount">
                    <input type="text" name="discount_amount" class="discount_amount">
                </div>
                <div class="send"><span>>></span></div>
            </div>
                <?php
        }
    }
die();
}