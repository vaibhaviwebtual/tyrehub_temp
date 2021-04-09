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
	do_action( 'woocommerce_before_main_content' );

	?>
	<div class="row">
<div class="col-md-12">
<div class="search-result">
<strong>Result For :</strong>
<?php


if(isset($_GET['searchby']))
{
if(isset($_GET['filter_width']))
{
echo '<span class="label"> Width: </span>'.$_GET['filter_width'];
}
if(isset($_GET['filter_ration']))
{
echo '<span class="label"> Ratio: </span>'.$_GET['filter_ration'];
}
if(isset($_GET['filter_diameter']))
{
echo '<span class="label"> Diameter: </span>'.$_GET['filter_diameter'];
}
}
else
{
if(isset($_SESSION['make_id']))
    {
    $make_id = $_SESSION['make_id'];

    echo '<span class="label"> Make: </span>'.$model_value = $wpdb->get_var( $wpdb->prepare( "SELECT make_name FROM th_make WHERE make_id ='%s' LIMIT 1", $make_id ) );
    }
 
   if(isset($_SESSION['model_id']))
{
$model_id = $_SESSION['model_id'];
echo  '<span class="label"> Model: </span>'.$model_name = $wpdb->get_var( $wpdb->prepare( "SELECT model_name FROM th_model WHERE model_id ='%s' LIMIT 1", $model_id ) );
}

    if(isset($_SESSION['sub_model_id']))
    {

    $sub_modal_id = $_SESSION['sub_model_id'];

  echo '<span class="label"> Submodel: </span>'.$submodel_value = $wpdb->get_var( $wpdb->prepare( "SELECT submodel_name FROM th_submodel WHERE submodel_id ='%s' LIMIT 1", $sub_modal_id ) );
  }
}

?>
&nbsp;<a class="btn btn-invert" href="<?php echo get_site_url(); ?>"><span>Start New Search</span></a>
</div>
</div>
</div>
	<div class="row">
	
			<?php  
				  
				if (   is_active_sidebar( 'shop-page-widget-area'  )  ){

			?>
					 
					
						<div class="col-md-4 col-lg-3 column-left column-filters">
							<div class="column-filters-inside sidebar-div">				    
							        <?php dynamic_sidebar( 'shop-page-widget-area' ); ?>
							</div>
					 	</div>
					 	<?php } ?>
	
		<div class="col-md-9 tyre-list">
		<!-- 	<header class="woocommerce-products-header"> -->
				<?php //if ( apply_filters( 'woocommerce_show_page_title', true ) ) : ?>
					<!-- <h1 class="woocommerce-products-header__title page-title"><?php //woocommerce_page_title(); ?></h1> -->
				<?php //endif; ?>

				<?php
				/**
				 * Hook: woocommerce_archive_description.
				 *
				 * @hooked woocommerce_taxonomy_archive_description - 10
				 * @hooked woocommerce_product_archive_description - 10
				 */
				//do_action( 'woocommerce_archive_description' );
				?>
			<!-- </header> -->
			
			<?php

				if ( have_posts() ) {

					/**
					 * Hook: woocommerce_before_shop_loop.
					 *
					 * @hooked wc_print_notices - 10
					 * @hooked woocommerce_result_count - 20
					 * @hooked woocommerce_catalog_ordering - 30
					 */
					do_action( 'woocommerce_before_shop_loop' );

					woocommerce_product_loop_start();

					if ( wc_get_loop_prop( 'total' ) ) {
						while ( have_posts() ) {
							the_post();

							/**
							 * Hook: woocommerce_shop_loop.
							 *
							 * @hooked WC_Structured_Data::generate_product_data() - 10
							 */
							do_action( 'woocommerce_shop_loop' );

							wc_get_template_part( 'content', 'product' );
						}
					}

					woocommerce_product_loop_end();

					/**
					 * Hook: woocommerce_after_shop_loop.
					 *
					 * @hooked woocommerce_pagination - 10
					 */
					do_action( 'woocommerce_after_shop_loop' );
				} else {
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
				do_action( 'woocommerce_sidebar' );

				
				?>
		</div>
	</div>
</div>
</div>

<?php
get_footer( 'shop' );
