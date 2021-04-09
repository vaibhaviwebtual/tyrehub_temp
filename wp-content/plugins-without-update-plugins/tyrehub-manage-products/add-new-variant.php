<?php
   function tim_add_new_product_variant(){
   ?>
   <style type="text/css">
       .form-row select{height:auto!important;}
   </style>
<div class="wrap">
   <h1 class="wp-heading-inline">Add New Variant</h1>
   <span hidden="" class="admin-url"><?php echo admin_url('admin-ajax.php'); ?></span><br>
   <a href="?page=add-new-brand" class="page-title-action">Add New Brand</a>
   <a href="?page=product-add-new" class="page-title-action">Add New Product</a>  
  <a href="?page=product-add-new-variant" class="page-title-action">Add New Variant</a>
   <div class="row">
    <div class="col-md-8">
   <form action="" method="post" autocomplete="off">
      <div class="woocommerce_variation wc-metabox variation-needs-update open ">
         <div class="variant-name-binding-area">              
                
                <div>
                  <p class="form-field variable_description0_field form-row form-row-first">
                     <label for="product">Product Title (Sagment)</label>
                     <select name="product" id="product" required>
                        <option value="">Product Title (Sagment)...</option>
                        <?php 
                        global $post;
                        $args = array(
                                'post_type' => 'product',
                                'posts_per_page' => -1
                            );
                            $wp_query = new WP_Query($args);
                       while ($wp_query->have_posts()) : $wp_query->the_post(); ?>

                      <option value="<?php echo $post->ID; ?>" <?php if($post->ID==$_POST['product']){ echo 'selected';}?>><?php echo the_title(); ?></option>

                            <?php
                        endwhile; ?>
                    </select>
                  </p>
               </div>
                <div>
                  <p class="form-field variable_description0_field form-row form-row-last">
                     <label for="variable_description">Variant Product Title</label>
                     <textarea class="short" style="" required name="variable_description" id="variable_description" placeholder="" rows="2" cols="20"></textarea> 
                  </p>
               </div>
               <div>
                  <p class="form-field variable_description0_field form-row form-row-full">
                    <h3>
             <select name="width" id="width" required>
               <option value="">Any width…</option>
                    <?php 
                    $terms = get_terms(array('taxonomy'=>'pa_width','hide_empty' => false));

                    foreach ( $terms as $term ){?>
                    <option value="<?php echo str_replace('.', '-',$term->name); ?>"><?php echo $term->name; ?></option>
                    <?php }?>
                </select>
                <select name="ratio" id="ratio" required>
               <option value="">Any ratio…</option>
                    <?php 
                    $terms = get_terms(array('taxonomy'=>'pa_ratio','hide_empty' => false));

                    foreach ( $terms as $term ){?>
                    <option value="<?php echo str_replace('.', '-',$term->name); ?>"><?php echo $term->name; ?></option>
                    <?php }?>
                </select>
                <select name="diameter" id="diameter" required>
               <option value="">Any diameter…</option>
                    <?php 
                    $terms = get_terms(array('taxonomy'=>'pa_diameter','hide_empty' => false));

                    foreach ( $terms as $term ){?>
                    <option value="<?php echo str_replace('.', '-',$term->name); ?>"><?php echo $term->name; ?></option>
                    <?php }?>
                </select>
      
            
            <select name="tyre_type" id="tyre_type" required>
               <option value="">Any Tyre Type…</option>
               <option  value="tubetyre">Tubetyre</option>
               <option  value="tubeless">Tubeless</option>

            </select>
            <select name="vehicle_type" id="vehicle_type" required>
               <option value="">Any vehicle type…</option>
               <option value="car-tyre">Car Tyre</option>
               <option value="three-wheeler">three wheeler</option>
               <option value="two-wheeler">two wheeler</option>
            </select>

            <select name="brand" id="brand" required>
               <option value="">Any brand…</option>
                    <?php 
                    $terms = get_terms(array('taxonomy'=>'pa_brand','hide_empty' => false));
                    foreach ( $terms as $term ){?>
                    <option value="<?php echo $term->slug; ?>"><?php echo $term->name; ?></option>
                    <?php }?>
                </select>
         </h3>  
                  </p>
               </div>
        </div>
         <div class="woocommerce_variable_attributes wc-metabox-content" style="display: block;">
            <div class="data">
               <div class="variable_pricing">
                <div class="sale_pricing">
                  <p class="form-field variable_regular_price_0_field form-row form-row-first">
                     <label for="regular_price">M.R.P. price (₹)</label>
                     <input type="text" class="short wc_input_price" style="" name="regular_price" id="regular_price" required value="" placeholder="Variation price (required)"> 
                  </p>
                  <p class="form-field variable_sale_price0_field form-row form-row-last">
                     <label for="sale_price">Sale price (₹) </label>
                     <input type="text" class="short wc_input_price" required  style="" name="sale_price" id="sale_price" value="" placeholder=""> 
                  </p>
                  <p class="form-field tyre_price[0]_field form-row form-row-first">
                     <label for="tyre_price">Tyre Price</label>
                     <input type="text" class="short" required style="" name="tyre_price" id="tyre_price" value="" placeholder=""> 
                  </p>
                  <p class="form-field tube_price[0]_field form-row form-row-last">
                     <label for="tube_price">Tube Price</label><input type="text" class="short" style="" name="tube_price" id="tube_price" value="" placeholder=""> 
                  </p>

                  
                  <p class="form-field percentage[0]_field form-row form-row-first">
                     <label for="percentage">Percentage</label>
                     <input type="text" class="short" required style="" name="percentage" id="percentage" value="4" placeholder=""> 
                  </p>
                  <p class="form-field margin_price[0]_field form-row form-row-last">
                     <label for="margin_price">Margin Price</label><input type="text" class="short" style="" name="margin_price" id="margin_price" value="70" placeholder=""> 
                  </p>
              </div>
                  <p class=" form-field tyrehub_visible[0]_field form-row form-row-first">
                     <label for="tyrehub_visible">Visible</label>
                     <select style="" id="tyrehub_visible" name="tyrehub_visible" class="select short" required>
                        <option value="yes">Yes</option>
                        <option value="no">No</option>
                        <option value="contact-us">Contact-us</option>
                     </select>
                  </p>
                  <p class="form-field rcp_price[0]_field form-row form-row-last">
                     <label for="rcp_price">RCP Price</label><input type="text" class="short"  style="" name="rcp_price" id="rcp_price" value="" placeholder=""> 
                  </p>
                  <p class="form-field net_lending_price[0]_field form-row form-row-first">
                     <label for="net_lending_price">NET LENDING</label><input type="text" class="short" style="" name="net_lending_price" id="net_lending_price" value="" placeholder=""> 
                  </p>
                  <p class="form-field point[0]_field form-row form-row-last">
                     <label for="point">Point</label><input type="text" class="short" style="" name="point" id="point" value="" placeholder=""> 
                  </p>
                  <p class="form-field company_name[0]_field form-row form-row-first">
                     <label for="company_name">Company Name</label><input type="text" class="short" style="" name="company_name" id="company_name" value="" placeholder=""> 
                  </p>
                  
               </div>
         
             
              
            </div>
         </div>
      </div>

      <button class="button-primary woocommerce-save-button add-installer-btn" type="submit" name="submit">
      Save Changes</button> 
      <a href="<?=get_admin_url();?>/admin.php?page=product-manage" class="button-primary woocommerce-save-button add-installer-btn">Back</a> 

   </form>
    </div>
   </div>
   <?php
      if(isset($_POST['submit']))
      {
        global $woocommerce, $wpdb;
        extract($_POST);

        $prod = wc_get_product($product);
        $variation_post = array(
            'post_title'  => $prod->get_name(),
            'post_name'   => 'product-'.$product.'-variation',
            'post_status' => 'publish',
            'post_parent' => $product,
            'post_type'   => 'product_variation'
        );

    // Creating the product variation
    $variation_id = wp_insert_post( $variation_post );

    //Supplier Price sync
    $data=array(
        'product_id'=>$variation_id,
        'user_id'=>0,
        'supplier_id'=>8,
        'new_tube_price'=>0,
        'old_tube_price'=>$tube_price,
        'new_tyre_price'=>0,
        'old_tyre_price'=>$tyre_price,
        'flat_percentage'=>$percentage,
        'margin_price'=>$margin_price,
        'new_mrp'=>0,
        'old_mrp'=>$regular_price,
        'new_total_price'=>0,
        'old_total_price'=>$sale_price

        );
    $wpdb->insert('th_supplier_products', $data);
    $wpdb->insert('th_supplier_products_list', $data);

    $data1=array(
        'product_id'=>$variation_id,
        'user_id'=>0,
        'supplier_id'=>8,
        'tube_price'=>$tube_price,
        'tyre_price'=>$tyre_price,
        'flat_percentage'=>$percentage,
        'margin_price'=>$margin_price,
        'mrp'=>$regular_price,
        'total_price'=>$sale_price,
        'common_status'=>1,

        );
    $wpdb->insert('th_supplier_products_final', $data1);

    $parent_id = $product; // Or get the variable product id dynamically

    // The variation data
    $variation_data =  array(
            'attributes' => array(
                'brand'  =>$brand,
                'width' => $width,
                'ratio' =>$ratio,
                'diameter' =>$diameter,
                'tyre-type' =>$tyre_type,
                'vehicle-type' =>$vehicle_type
            ),
            '_variation_description'=>$variable_description,
            '_regular_price' =>$regular_price,
            '_sale_price'    =>$sale_price,
            '_tax_status'=>'taxable',
            '_tax_class'=>'parent',
            '_manage_stock'=>'no',
            '_backorders'=>'no',
            '_sold_individually'=>'no',
            '_virtual'=>'no',
            '_downloadable'=>'no',
            '_stock'     =>0,
            '_stock_status'=>'instock',
            'point'=>$point,
            'rcp_price'=>$rcp_price,
            'net_lending_price'=>$net_lending_price,
            'company_name'=>$company_name,
            'tyre_price'=>$tyre_price,
            'tube_price'=>$tube_price,
            'tyrehub_visible'=>$tyrehub_visible,
            'active_supplier'=>8,
            'active_date'=>date('Y-m-d'),
            'update_date'=>''
    );
        $variation = new WC_Product_Variation( $variation_id );
        $i=0;
        foreach ($variation_data['attributes'] as $attribute => $term_name )
            {
                $taxonomy = 'pa_'.$attribute; // The attribute taxonomy
                // Check if the Term name exist and if not we create it.
                if(!term_exists( $term_name, $taxonomy ) ){
                  wp_insert_term( $term_name, $taxonomy ); // Create the term
                }
                    

                $term_slug = get_term_by('name',$term_name,$taxonomy)->slug; // Get the term slug

                // Get the post Terms names from the parent variable product.
                $post_term_names = wp_get_post_terms($parent_id,$taxonomy, array('fields'=>'names'));

                // Check if the post term exist and if not we set it in the parent variable product.
                if(!in_array( $term_name, $post_term_names)){
                  //wp_set_object_terms($parent_id,$term_name, $taxonomy,true);
                  wp_set_post_terms($parent_id, $term_name, $taxonomy,true);
                  // $term_taxonomy_ids = wp_set_object_terms( $product->get_id(), '0.25m', 'diameter', true );
                }

                // Set/save the attribute data in the product variation
                update_post_meta( $variation_id, 'attribute_'.$taxonomy,$term_name);

              $product_attributes[$taxonomy]= array (
                    'name' => $taxonomy,
                    'value' => '',
                    'position' => $i,
                    'is_visible' => '1',
                    'is_variation' => '1',
                    'is_taxonomy' => '1',
                  );
                $i++;
            }
            $product_attributes =$product_attributes;

            ## Set/save all other data
            update_post_meta($parent_id, '_product_attributes',$product_attributes);
       
            // SKU
           /* if(!empty( $variation_data['_variation_description'] ) )
                $variation->set_sku( $variation_data['_variation_description'] );
*/
            // Prices
            if( empty( $variation_data['_sale_price'] ) ){
                $variation->set_price( $variation_data['_regular_price'] );
            } else {
                $variation->set_price( $variation_data['_sale_price'] );
                $variation->set_sale_price( $variation_data['_sale_price'] );
            }
            $variation->set_regular_price( $variation_data['_regular_price'] );

            // Stock
            if(!empty($variation_data['_stock']) ){
                $variation->set_stock_quantity( $variation_data['_stock'] );
                $variation->set_manage_stock(false);
                $variation->set_stock_status('instock');
            } else {
                $variation->set_manage_stock(false);
            }

            //$variation->set_weight(''); // weight (reseting)
            
            update_post_meta( $variation_id, '_variation_description', $variation_data['_variation_description']);
             update_post_meta( $variation_id, '_backorders', $variation_data['_backorders']);
            update_post_meta( $variation_id, '_sold_individually', $variation_data['_sold_individually']);
            update_post_meta( $variation_id, '_virtual',$variation_data['_virtual']);
            update_post_meta( $variation_id, '_downloadable',$variation_data['_downloadable']);
            update_post_meta( $variation_id, 'point', $variation_data['point']);
            update_post_meta( $variation_id, 'rcp_price', $variation_data['rcp_price']);
            update_post_meta( $variation_id, 'net_lending_price', $variation_data['net_lending_price']);
            
            update_post_meta( $variation_id, 'company_name', $variation_data['company_name'] );
            update_post_meta( $variation_id, 'tyre_price', $variation_data['tyre_price'] );
            update_post_meta( $variation_id, 'tube_price', $variation_data['tube_price'] );
            update_post_meta( $variation_id, 'tyrehub_visible', $variation_data['tyrehub_visible'] );
            update_post_meta( $variation_id, 'active_supplier', $variation_data['active_supplier'] );
            update_post_meta( $variation_id, 'active_date', $variation_data['active_date'] );
            update_post_meta( $variation_id, 'update_date', $variation_data['update_date'] );
            $variation->save(); // Save the data

            ?>

            <div class="notice notice-success is-dismissible"> 
                <p><strong>New Variant added successfuly.</strong></p>
            </div>
            <?php 


      }

      ?>
</div>
<?php
}
