<?php
 /* Template Name: installer-home */
 $user = wp_get_current_user();
       $role = $user->roles[0];
 if ( !is_user_logged_in() && $role != 'Installer')
{
     wp_redirect(get_permalink( get_option('woocommerce_myaccount_page_id') ).'/installer-login' );
     die();
  }
get_header();

?>
<div id="pageContent">
	<div class="admin_url" hidden="">https://www.tyrehub.com/wp-admin/admin-ajax.php</div>
	<div class="container installer-home">
		<div style="text-align: left;" class="search-bar">
			<span style="text-align: left;">Width: <input type="text" name="" class="width"></span>
			<span style="text-align: left;">Ratio: <input type="text" name="" class="ratio"></span>
			<span style="text-align: left;">Diameter: <input type="text" name="" class="diameter"></span>
			<button class="searchbywidth btn btn-invert"><span>Search</span></button>
		</div>
		
		
 <!-- Modal -->
 
		<div class="product-container easyPaginate" id="easyPaginate">
			
		<?php  
		    /*$args = array(
		        'post_type'      => 'product',
		        'posts_per_page' => -1
		    );*/

		    $loop = new WP_Query( $args );

		    while ( $loop->have_posts() ) : $loop->the_post();
		        global $product;
		        $id = $loop->post->ID; 	
		        if ( $product->is_type( 'variable' ) ) 
		        {
		        	/*$args = array(
					'post_type'     => 'product_variation',
					'post_status'   => array( 'private', 'publish' ),
					'numberposts'   => -1,
					'orderby'       => 'menu_order',
					'order'         => 'asc',
					'post_parent'   => get_the_ID() // get parent post-ID
					);*/
					$variations = get_posts( $args );
					 $product->get_description();
					foreach ( $variations as $variation ) 
					{
						$variation_ID = $variation->ID;

						$product_variation = new WC_Product_Variation( $variation_ID );

						$variation_des = $product_variation->get_description();
						$variation_price = $product_variation->get_price();
		        		 
			            $sku = get_post_meta($id, '_sku', true);
			            $variation_sku = '';

			            $variation = wc_get_product( $variation_ID );

						// Loop through variation attributes "taxonomy" / "terms" pairs
						foreach( $variation->get_variation_attributes() as $attribute => $value ){
						    $taxonomy = str_replace( 'attribute_', '', $attribute );
						    $term = get_term_by( 'slug', $value, $taxonomy );		    
						    
						    $variation_sku .= $term->description; // <= HERE the Description
						}
						
						?>
			        	<div class="single-product" data-id="<?php echo $variation_ID; ?>">
			        		<div class="image"><?php echo woocommerce_get_product_thumbnail(); ?></div>
			        	
			        		<div class="name"><?php echo $variation_des; ?></div>
			        		<div class="price"><?php echo $variation_price; ?></div>
			        		<div class="qty">
			        			<select>
			        				<option>1</option>
			        				<option>2</option>
			        				<option>3</option>
			        				<option>4</option>
			        				<option>5</option>
			        				<option>6</option>
			        				<option>7</option>
			        				<option>8</option>
			        				<option>9</option>
			        				<option>10</option>
			        			</select>
			        		</div>
			        		<div class="add-to-cart">
			        			<a href="/installer-home/?add-to-cart=<?php echo $variation_ID; ?>" class="btn btn-invert button product_type_simple add_to_cart_button ajax_add_to_cart" data-product_id="<?php echo $variation_ID; ?>" data-product_sku="" aria-label="Add “ceat” to your cart" rel="nofollow"><span><i class="fa fa-shopping-cart"></i>Add to cart</span></a>
			        		</div>
			        	</div>
		        <?php
					}
		        } 
		        else{
			     //      	echo '<br /><a href="'.get_permalink().'">' . woocommerce_get_product_thumbnail().' '.get_the_title().'</a>';
		        
    
	            $id = $loop->post->ID;             
	            $sku = get_post_meta($id, '_sku', true);
	            $selling_price = get_post_meta($id, '_sale_price', true);
	            $regular_price = get_post_meta($id, '_regular_price', true);
	            $description=get_the_content();
	            $thetitle = get_the_title();			              

		        ?>
		        	<div class="single-product" data-id="<?php echo $id; ?>">
		        		<div class="image"><?php echo woocommerce_get_product_thumbnail(); ?></div>
		        		<div class="name"><?php echo get_the_title(); ?></div>
		        		<div class="price"><?php echo get_woocommerce_currency_symbol().number_format($regular_price,2,'.',''); ?></div>
		        		<div class="qty">
		        			<select>
		        				<option>1</option>
		        				<option>2</option>
		        				<option>3</option>
		        				<option>4</option>
		        				<option>5</option>
		        				<option>6</option>
		        				<option>7</option>
		        				<option>8</option>
		        				<option>9</option>
		        				<option>10</option>
		        			</select>
		        		</div>
		        		<div class="add-to-cart">
		        			<a href="/dev/installer-home/?add-to-cart=<?php echo $id; ?>" class="btn btn-invert button product_type_simple add_to_cart_button ajax_add_to_cart" data-product_id="<?php echo $id; ?>" data-product_sku="" aria-label="Add “ceat” to your cart" rel="nofollow"><span><i class="fa fa-shopping-cart"></i>Add to cart</span></a>
		        		</div>
		        	</div>
		        <?php
		    }
		    endwhile;

		    wp_reset_query();
		?>
	</div>
	</div>

</div>
<?php
get_footer();
?>