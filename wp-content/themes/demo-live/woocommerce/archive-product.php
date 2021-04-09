<?php
/**
 * The Template for displaying product archives, including the main shop page which is a post type archive
 *
 * This template can be overridden by copying it to yourtheme/woocommerce/archive-product.php.
 *
 * HOWEVER, on occasion WooCommerce will need to update template files and you
 * (the theme developer) will need to copy the new files to your theme to
 * maintain compatibility. We try to do this as little as possible, but it does
 * happen. When this occurs the version of the template file will be bumped and
 * the readme will list any important changes.
 *
 * @see 	    https://docs.woocommerce.com/document/template-structure/
 * @author 		WooThemes
 * @package 	WooCommerce/Templates
 * @version     3.3.0
 */
if ( ! defined( 'ABSPATH' ) ) {
	exit;
}
$cate = get_queried_object();
$catslug = $cate->slug;
get_header( 'shop' );
?>
<div id="pageContent">
	<div class="container">
		<?php
		/**
		 * Hook: woocommerce_before_main_content.
		 *
		 * @hooked woocommerce_output_content_wrapper - 10 (outputs opening divs for the content)
		 * @hooked woocommerce_breadcrumb - 20
		 * @hooked WC_Structured_Data::generate_website_data() - 30
		 */

		if(is_shop()){
		?>
		<div class="row">
			<div class="main-breadcrumb">
				<?php main_breadcrumb('cartype');?>
			</div>
		</div>
				<?php

				if(isset($_GET['searchby'])){
			?>
			<div class="row">
				<div class="search-result">
				<strong>Result For :</strong>
				<?php
					if($_GET['searchby'] == 'size') {
						if(isset($_GET['filter_width'])) {
							echo '<span class="label"> Width: </span>'.$_GET['filter_width'];
						}
						if(isset($_GET['filter_ratio'])) {
							echo '<span class="label"> Ratio: </span>'.$_GET['filter_ratio'];
						} 
						if(isset($_GET['filter_diameter'])) {
							echo '<span class="label"> Diameter: </span>'.$_GET['filter_diameter'];
						}	
					}
					if($_GET['searchby'] == 'model') {
						if(isset($_SESSION['make_id'])) {
							$make_id = $_SESSION['make_id'];
							echo '<span class="label"> Make: </span>'.$model_value = $wpdb->get_var( $wpdb->prepare( "SELECT make_name FROM th_make WHERE make_id ='%s' LIMIT 1", $make_id ) );
						}
						if(isset($_SESSION['model_id'])) {
							$model_id = $_SESSION['model_id'];
							echo  '<span class="label"> Model: </span>'.$model_name = $wpdb->get_var( $wpdb->prepare( "SELECT model_name FROM th_model WHERE model_id ='%s' LIMIT 1", $model_id ) );
						}
						if(isset($_SESSION['sub_model_id'])) {
							$sub_modal_id = $_SESSION['sub_model_id'];
							echo '<span class="label"> Submodel: </span>'.$submodel_value = $wpdb->get_var( $wpdb->prepare( "SELECT submodel_name FROM th_submodel WHERE submodel_id ='%s' LIMIT 1", $sub_modal_id ) );
						}
					}

				if(isset($_GET['vehicle'])) {	
				?>
				<a class="btn btn-invert modify-search-pc" href="<?php echo get_site_url(); ?>/two-wheeler?modifysearch=yes" style="float: right;"><span>Modify Search</span></a>
			<?php } else {
				?>
				<a class="btn btn-invert modify-search-pc" href="<?php echo get_site_url().'?modifysearch=yes'; ?>" style="float: right;"><span>Modify Search</span></a>
			<?php
			} 
			?>
				</div>
			</div>
			<?php } }?>
			<div class="row">
				<div class="shop-page">
					<div class="header">
						<?php if(isset($_GET['vehicle'])) { ?>
							<a class="btn btn-invert modify-search-mobile" href="<?php echo get_site_url(); ?>/two-wheeler?modifysearch=yes"><span>Modify Search</span></a>
						<?php } else { ?>
							<a class="btn btn-invert modify-search-mobile" href="<?php echo get_site_url().'?modifysearch=yes'; ?>"><span>Modify Search</span></a>
						<?php } ?>
						<div class="show-filter btn btn-invert"><span>Show filter</span></div>
					</div>
					<?php if(is_active_sidebar('shop-page-widget-area')) { ?>
						<div class="col-md-3 column-left column-filters">
							<div class="header">
								<div class="close-filter btn btn-invert">X</div>
							</div>
							<div class="column-filters-inside sidebar-div">				    
									<?php dynamic_sidebar( 'shop-page-widget-area' ); ?>
							</div>
						</div>
					<?php } ?>
					<div class="col-md-9 tyre-list">
					<?php do_action( 'woocommerce_before_main_content' ); ?>
					<?php
						if($_GET['orderby']) {
							$orderby=$_GET['orderby'];
							$short=explode('-',$orderby);
							if($short[1]) {
								$sorting='DESC';	
							} else {
								$sorting='ASC';
							}
						} else {
							$sorting='ASC';
						}
						$paged = ( get_query_var('paged') ) ? get_query_var('paged') : 1;
						$args = array(
							'post_type' => 'product_variation',
							'posts_per_page' => 12,
							'paged' => $paged,
							'page' => $paged,
							'meta_query'=> array(            			
								'relation' => 'AND',
								array(
									'key'       => 'tyrehub_visible',
									'value'     => array('yes','contact-us'),
									'compare'   => 'IN',
								),
							)
						);
						$args['meta_key'] = '_sale_price';
						$args['orderby'] = 'meta_value_num';
						$args['order'] = $sorting;
						$products = new WP_Query( $args );	
						$num = $products->found_posts;	
						wc_set_loop_prop('total', $num);
						$total_pages = ceil($num/12);
						wc_set_loop_prop('total_pages', $total_pages);
						$width_arr = [];
						$ratio_arr = [];
						$diameter_arr =[];
						$brand_arr = [];
						if ( get_query_var( 'taxonomy' ) ) { 
							// If on a product taxonomy archive (category or tag)
							if($_GET['filter_width']){
								$width=str_replace(".","-",$_GET['filter_width']);
								$width_arr=explode(",", $width);
							}
							//var_dump($width_arr);
							if($_GET['filter_ratio']) {
								$ratio_arr=explode(",", $_GET['filter_ratio']);
							}
							if($_GET['filter_diameter']) {
								$diameter_arr=explode(",", $_GET['filter_diameter']);
							}
							if($_GET['filter_brand']) {
								$brand_arr = explode(",", $_GET['filter_brand']);
							}
							if($_GET['filter_vehicle-type']) {
								if($_GET['filter_vehicle-type'] == 'two-wheeler') {
									$vehicle_type_arr = array('two-wheeler','three-wheeler');
								}
								else {
									$vehicle_type_arr = explode(",", $_GET['filter_vehicle-type']);
								}
							}
							else {
								$vehicle_type_arr =  array('car-tyre','two-wheeler','three-wheeler' );
							}
							$meta_query[]=array(
								'key'       => 'tyrehub_visible',
								'value'     => array('yes','contact-us'),
								'compare'   => 'IN',
							);
							$meta_query[]=array(
								'key' => 'attribute_pa_vehicle-type',
								'value' => $vehicle_type_arr,
								'compare' => 'IN',
							);
							if($width_arr) {
								$meta_query[]=array(
									'key' => 'attribute_pa_width',
									'value' => $width_arr,
									'compare' => 'IN',
								);
							}
							if($diameter_arr){
								$meta_query[]=array(
									'key' => 'attribute_pa_diameter',
									'value' => $diameter_arr,
									'compare' => 'IN',
								);	
							}
							if($ratio_arr){
								$meta_query[]=array(
									'key' => 'attribute_pa_ratio',
									'value' => $ratio_arr,
									'compare' => 'IN',
								);
							}
							if($brand_arr || $catslug){
								if($catslug && empty($brand_arr)){
									$brand_arr=$catslug;
								}
								$meta_query[]=array(
									'key' => 'attribute_pa_brand',
									'value' => $brand_arr,
									'compare' => 'IN',
								);
							}
							$paged = ( get_query_var('paged') ) ? get_query_var('paged') : 1;
							$args = array(
								'post_type' => 'product_variation',
								'paged' => $paged,
								'posts_per_page' => -1,
								'meta_query'=> array(                       
								'relation' => 'AND',$meta_query
								 ),            
							);	
							$args['meta_key'] = '_sale_price';
							$args['orderby'] = 'meta_value_num';
							$args['order'] = $sorting;	
							$products = new WP_Query( $args );	

							$num = $products->found_posts;	
							wc_set_loop_prop('total', $num);
							wc_set_loop_prop('total_pages', 1);	
						}
						// Standard loop
						if ( $products->have_posts() ) {
							do_action( 'woocommerce_before_shop_loop' );
							//wp_reset_postdata();
							woocommerce_product_loop_start();
							
							while ( $products->have_posts() ) : $products->the_post();
								/**
								 * Hook: woocommerce_shop_loop.
								 *
								 * @hooked WC_Structured_Data::generate_product_data() - 10
								 */
								do_action( 'woocommerce_shop_loop' );
								wc_get_template_part( 'content', 'product' );
							endwhile;
							wp_reset_postdata();
							woocommerce_product_loop_end();
							/**
							 * Hook: woocommerce_after_shop_loop.
							 *
							 * @hooked woocommerce_pagination - 10
							 */
							do_action( 'woocommerce_after_shop_loop' );
						} 
						else {
							/**
							 * Hook: woocommerce_no_products_found.
							 *
							 * @hooked wc_no_products_found - 10
							 */
							do_action( 'woocommerce_no_products_found' );
						}
						/**
						 * Hook: woocommerce_after_main_content.
						 *
						 * @hooked woocommerce_output_content_wrapper_end - 10 (outputs closing divs for the content)
						 */
						do_action( 'woocommerce_after_main_content' );
						/**
						 * Hook: woocommerce_sidebar.
						 *
						 * @hooked woocommerce_get_sidebar - 10
						 */
						//do_action( 'woocommerce_sidebar' );	
					?>
				</div>
			</div>
		</div>
	</div>
</div>
<?php
get_footer( 'shop' );