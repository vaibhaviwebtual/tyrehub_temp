<?php
/*
Plugin Name: Tyrehub Product Sold Out
Plugin URI: https://acespritech.com/
Description: Sold Out Particular Product.
Version: 1.4.1
Author: Acespritech
Author URI: https://acespritech.com/
*/

function sold_out_menu_items()
{
    add_submenu_page('woocommerce', 'Sold Out','Sold Out', 'activate_plugins', 'sold_out', 'sold_out_page');

    add_submenu_page('edit.php?post_type=product', 'Change Product Price','Change Product Price', 'activate_plugins', 'change_price', 'change_price');

    add_submenu_page('edit.php?post_type=product', 'Change Price Log','Change Price Log', 'activate_plugins', 'change_price_log', 'change_price_log');
}
add_action('admin_menu', 'sold_out_menu_items');

add_action('admin_enqueue_scripts', 'tyrehub_admin_soldout_script');

function tyrehub_admin_soldout_script()
{
    wp_enqueue_script('tyrehub_admin_soldout_script', plugins_url('/js/sold_out.js', __FILE__), array('jquery'));
}

function sold_out_page(){
?>
	<div class="wrap soldout">        
       	<div id="icon-users" class="icon32"><br/></div>
		<h1 class="wp-heading-inline">Product Sold Out</h1>
		<div class="admin-url" hidden=""><?php echo admin_url('admin-ajax.php'); ?></div>
		<table class="form-table">
			<tr>
				<th>Product List</th>
                <td>
                    <div class="selection-type">
                        <div class="prd-search">
                            <strong>Width</strong><input type="text" name="width" class="width">
                            <strong>Ration</strong><input type="text" name="ratio" class="ratio">
                            <strong>Diameter</strong><input type="text" name="diameter" class="diameter">
                            <select class="category">
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
                            <button class="search-btn">Search</button>
                            
                        </div>
                    </div>
                </td>
			</tr>
		</table>
		<div class="product-container ">
            <div class="product-details">
                <h2>Search Product List</h2>
                <div class="header">
                    <div class="name"><strong>Name</strong></div>
                    <div class="price"><strong>MRP</strong></div>
                    <div class="price"><strong>Web Price</strong></div>
                    <div class="add"><strong>Add</strong></div>
                </div>
            </div>

            <div class="selected-products"><h2>Selected Product List</h2>
                <div class="header">
                    <div class="remove"><strong>Remove</strong></div>
                    <div class="name"><strong>Name</strong></div>
                    <div class="price"><strong>MRP</strong></div>
                    <div class="price"><strong>Web Price</strong></div>
                </div>
                <?php 
                	global $wpdb , $woocommerce;
                	$list_sql = "SELECT * FROM th_soldout_product_list";
                    $list_data = $wpdb->get_results($list_sql);
                                //$rule_data = $rule_data[0];
                    //var_dump($list_data);
                    foreach ($list_data as $key => $list_row)
                    {
                    	$variation_ID = $list_row->product_id;
                        $product_variation = new WC_Product_Variation( $variation_ID );

                        $variation_des = $product_variation->get_description();

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

                        if($sale_price == ''){
                        	$sale_price = '-';
                        }
                        else{
                            $sale_price = wc_price( $sale_price, $args );
                        }
                        $regular_price = wc_price( $regular_price, $args );
                    ?>
                    <div class="single-product" data-id="<?php echo $list_row->product_id; ?>" id='<?php echo $list_row->product_id; ?>'>
                                <div class="remove"><span><<</span></div>               
                                <div class="name"><?php echo $variation_des; ?></div>
                                <div class="price regular-price"><?php echo $regular_price; ?></div>
                                <div class="price sale-price"><?php echo $sale_price; ?></div>
                                  
                                 <div class="status"><input type="checkbox" name="status" class="prd-status" <?php if($list_row->status == 'on'){ echo 'checked'; } ?>></div>                       
                            </div>

                    <?php
                    } 
                ?>
            </div>
                        </div>
                        <table class="form-table" style="width: 96%;">
                            <tr>
                                <td></td>
                                <td style="float: right;"><button class="save-list">Save List</button></td>
                            </tr>
                        </table>
                        <div class="message-block"></div>
	</div>
<?php
}

add_action('wp_ajax_soldout_product_list', 'soldout_product_list');
add_action('wp_ajax_nopriv_soldout_product_list', 'soldout_product_list');
function soldout_product_list()
{
	$prd_list = $_POST['prd_list'];
     
    var_dump($prd_list);
	global $woocommerce , $wpdb;

    $list_sql = "SELECT * FROM th_soldout_product_list";
    $list_data = $wpdb->get_results($list_sql);
               
    $list_arr = []; 
    foreach ($list_data as $key => $list_row)
    {
        $list_arr[] = $list_row->product_id;
    }
    $wpdb->get_results("DELETE FROM th_soldout_product_list;");
    foreach ($prd_list as $key => $product_id)
    {
       
        $insert = $wpdb->insert('th_soldout_product_list', array(
                                    'product_id' => $product_id,
                                ));
    }
    
   
die();
}

include_once('change-price.php');
include_once('change-price-log.php');
include_once('function.php');