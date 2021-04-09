<?php

if ( !is_user_logged_in() )
 	{
      wp_redirect(get_permalink( get_option('woocommerce_myaccount_page_id') ).'/installer-login' );
    
  	}
get_header();
?>
<div id="pageContent">
	<div class="container installer-home">
		<div class="admin_url" hidden="">https://www.tyrehub.com/wp-admin/admin-ajax.php</div>
		<div class="woocommerce">
			<div style="text-align: left;" class="search-bar">
				<div class="column">
					<span style="text-align: left;">Width</span>
					<div><input type="text" name="" class="width"></div>
				</div>
				<div class="column">
					<span style="text-align: left;">Ratio</span>
					<div><input type="text" name="" class="ratio"></div>
				</div>
				<div class="column">
					<span style="text-align: left;">Diameter</span>
					<div><input type="text" name="" class="diameter"></div>
				</div>
				<div class="column">
					<span style="text-align: left;">Search</span>
					<div><input type="text" name="" class="searchbyname"></div>
				</div>
				<div class="column">
					<button class="searchbywidth btn btn-invert"><span>Search</span></button>
				</div>
			</div>
			
			<?php
			
			if ( ! defined( 'ABSPATH' ) ) {
				exit;
			}

			wc_print_notices();


			do_action( 'woocommerce_account_navigation' ); 
			do_action( 'woocommerce_account_content' ); ?>
			<div class="woocommerce-MyAccount-content">
				 <div class="product-container"> 
				<!-- <div class="product-container"> -->
					<?php 
				

						/*$args = array(
					        'post_type'      => array('product','product_variation'),
					        'posts_per_page' => -1,
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
										'numberposts'   => 10,
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
											//$variation_price = $product_variation->get_price_html();
											$variation_price =  $product_variation->get_price();
							        		 
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
								        		<!-- <div class="image"><?php // echo woocommerce_get_product_thumbnail(); ?></div> -->
								        		<div class="name"><?php echo $variation_des; ?>
								        			<?php 

								        			/*foreach( $variation->get_variation_attributes() as $attribute => $value ){
														if($attribute == 'attribute_pa_width'){
															echo '<strong>Width :</strong>';
														}
														echo '<div class="'.$attribute.'">'.$value.'</div>';
													    
													  
													   // <= HERE the Description
													}*/
								        			?>
								        		</div>
								        		<div class="price"><?php echo "₹".$variation_price; ?></div>
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
								        			<a href="/?add-to-cart=<?php echo $variation_ID; ?>" class="btn btn-invert button product_type_simple add_to_cart_button" data-product_id="<?php echo $variation_ID; ?>" data-product_sku="" aria-label="Add “ceat” to your cart" rel="nofollow"><span><i class="fa fa-shopping-cart"></i>Add to cart</span></a>
								        		</div>
								        	</div>
							        <?php
							}
						}
						


					endwhile;
				?>
				</div>
					
			
			</div>
		</div>
	</div>
</div>

<?php
get_footer();
?>
