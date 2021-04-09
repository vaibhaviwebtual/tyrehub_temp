<?php
/*
Plugin Name: Tyrehub Voucher Multiple Order
Plugin URI: https://acespritech.com/
Description: Multiple voucher order in single click.
Version: 1.1.1
Author: Acespritech
Author URI: https://acespritech.com/
*/

add_action('admin_menu', 'voucher_order_menu_item');

function voucher_order_menu_item()
{
    add_submenu_page('woocommerce', 'Promotion Voucher','Promotion Voucher', 'activate_plugins', 'promotions_voucher', 'promotions_voucher_main_screen');
}

add_action('admin_enqueue_scripts', 'tvo_admin_js');

function tvo_admin_js()
{
    wp_enqueue_script('tvo_admin_js', plugins_url('/promotion.js', __FILE__), array('jquery'));

     wp_enqueue_script('tvo_admin_js1', plugins_url('/jquery.easyPaginate.js', __FILE__), array('jquery'));
}
function promotions_voucher_main_screen(){
	global $woocommerce,$wpdb;
?>
<div class="promotion-voucher">
    <h2>Promotion Voucher</h2>
    <div class="message-block"></div>
     <div class="admin-url" hidden=""><?php echo admin_url('admin-ajax.php'); ?></div>
    <p>This functionality using for Promotion voucher for multiple installer</p>
    <p>Please select installer from list to create order with promotion voucher</p>
    <p style="font-size: 16px;">Note:Order create from here directly go to the dispatch status and also display to installer.</p>
    
    
    	<div class="row-voucher-price">
    		<strong>New Voucher Price: </strong>
    		<input type="text" name="" class="voucher-price">
    	</div>
    	<div class="row-search">
    		<strong>Search by name or mobile no. </strong>
    		<input type="text" name="" class="serch-text">
    		
    		<span class="selected-installer"></span>
    	</div>
    
 
    <div class="installer-data">
    <?php
       
	    $sql = "SELECT * FROM th_installer_data";
	    $row = $wpdb->get_results($sql);
	    foreach ($row as $data) 
	    {
	        ?>
	        <div class="single-installer" data-id="<?php echo $data->installer_data_id; ?>">
	        	<div class="first-column">
	        		<img src="<?php echo bloginfo('template_directory')?>/images/select_service.png" class="img-responsive">
	        	</div>
	        	<div class="second-column">
		        	<div class="first-row">		        		
		        		<strong><?php echo $data->business_name; ?></strong>
		        	</div>		        	
		            <div><?php echo $data->address; ?></div>
		            <div><?php echo $data->city.'-'.$data->pincode; ?></div>
		            <div><?php echo $data->state; ?></div>
		            <div class="mobile-no"><?php echo $data->contact_no; ?></div>
		            
		        </div>
		        <div class="third-column">
		        	<input type="checkbox" name="installer" class="installer-selection" >
		        </div>
		        <div class="barcode-image" style="display: none;"></div>
	        </div>
	        <?php
	    }
	?>
	</div>

	<div class="right-section">
		<table class="wp-list-table widefat fixed striped">
			<thead>
				<tr>
					<td>Date</td>
					<td>No of Installer</td>
					<td>Individual Price</td>					
					<td>Download Promotion voucher</td>
					<td>Created By</td>
				</tr>
			</thead>
			<tbody class="pagnation">
				<?php 
					$voucher_sql = "SELECT * FROM th_promotion_voucher_info ORDER BY promotion_id DESC limit 20";
					$voucher_data = $wpdb->get_results($voucher_sql);
					if($voucher_data)
				    {
				        foreach ($voucher_data as $key => $voucher)
				        {
				        	$date = $voucher->date;
				        	$price = $voucher->price;
				        	$order_count = $voucher->order_count;
				        	$user = $voucher->user_ref;
				        	$order_id = $voucher->order_ids;
				        	$array = unserialize( $order_id );
				        
				        	$text = implode("x", $array);
				        	
				        	?>
				        	<tr class="newsbox">
				        		<td><?php echo $date; ?></td>		        		
				        		<td><?php echo $order_count; ?></td>
				        		<td><?php echo $price; ?></td>
				        		<td>
				        			<?php 
				        			 $url = wp_nonce_url( admin_url( 'admin-ajax.php?action=generate_wpo_wcpdf2&document_type=invoice&order_ids='.$text.'&my-account'), 'generate_wpo_wcpdf2' );
				        			?>
				        			<a href="<?php echo $url; ?>" target="_blank">PDF</a>
				        		</td>
				        		<td>
				        		<?php
				        			$userdata = get_user_by( 'ID', $user );
									echo $userdata->user_login;
    							  ?>
				        		</td>
				        	</tr>
				        	<?php
				        }
				    }
				?>
			</tbody>
		</table>
		<div class="page-info"></div>
		<div class="controls"></div>
		<button class="btn create-bulk-promotional-voucher">Create Order</button>
	</div>

		<!-- Modal -->
		<div id="after_voucher_order" style="display: none;">
			<div class="inner">
				<div class="body">
			  		<h3>Order Created for selected Installer!</h3>
			  		<?php 
			  		$url = wp_nonce_url( admin_url( 'admin-ajax.php?action=generate_wpo_wcpdf2&document_type=invoice&my-account'), 'generate_wpo_wcpdf2' );
			  		?>
			  		<button><a href="<?php echo $url;?>" class="download-pdf" traget="_blank">Download Voucher</a></button>
			  		<button class="close">Close</button>
			  	</div>
			</div>		  
		</div>
</div>
<?php

}

add_action('wp_ajax_create_bulk_prmotion_voucher_order', 'create_bulk_prmotion_voucher_order');
add_action('wp_ajax_nopriv_create_bulk_prmotion_voucher_order', 'create_bulk_prmotion_voucher_order');
function create_bulk_prmotion_voucher_order()
{
	global $wpdb, $woocommerce;
	$insataller = $_POST['installer'];
	$pirce = $_POST['price'];

	$customer_id = get_current_user_id();
	$session_id = WC()->session->get_customer_id();

	$sku = 'service_voucher';            
    $service_prd_id = $wpdb->get_var( $wpdb->prepare( "SELECT post_id FROM $wpdb->postmeta WHERE meta_key='_sku' AND meta_value='%s' LIMIT 1", $sku ) );

	$order_create = wc_create_order(array('status' => 'processing', 'customer_id' => $customer_id));
	$order_data = $order_create->get_data();
    $data_id = $order_data['id'];
	
	$total = 1 * $pirce;
	$prices = array('totals' => array(
            'subtotal' => $pirce,
            'total' => $pirce
        ));

	$order_create->add_product(get_product($service_prd_id), 1, $prices);

	$order_create->calculate_totals();

	update_post_meta( $order_create->id, '_payment_method', 'pos' );
    update_post_meta( $order_create->id, '_payment_method_title', 'pos' );

	date_default_timezone_set('Asia/Kolkata');
    $date = date('ymdhis', time());

    //$barcode_string = $date.$service_prd_id.$insataller.$vehicle_id;
    $barcode_string=barcode_generate();
	$insert = $wpdb->insert('th_cart_item_service_voucher', array(
                                    'product_id' => $service_prd_id,
                                    'session_id' => $session_id,
                                    'voucher_name' => 'promotion',
                                    'vehicle_id' => 11,
                                    'qty' => 1,
                                    'rate' => $pirce,
                                    'installer_id' => $insataller,
                                    'barcode' => $barcode_string,
                                    'order_id' => $data_id,
                                    ));
	$last_id = $wpdb->insert_id;
	$result_arr = array($last_id, $barcode_string,$data_id);

	$item_data = $order_create->get_items();

	foreach ($item_data as $item_id => $item_values)
    {
    	$service_voucher = "SELECT * 
                                FROM th_cart_item_service_voucher
                                WHERE order_id = '$data_id' and product_id = '$service_prd_id'";
        $row = $wpdb->get_results($service_voucher);

        if(!empty($row))
        {
            foreach ($row as $key => $voucher)
            {
                $voucher_id = $voucher->service_voucher_id;
                $rate = $voucher->rate;
                $qty = $voucher->qty;
                $amount = $rate * $qty;

                $gst = $amount * 18 / 118;
                $service_taxable = $amount - $gst;
                $service_sgst = $gst / 2;

                wc_update_order_item_meta($item_id, $voucher_id.'_service_sgst', $service_sgst);
                wc_update_order_item_meta($item_id, $voucher_id.'_service_cgst', $service_sgst);
                wc_update_order_item_meta($item_id, $voucher_id.'_service_taxable', $service_taxable);
            }
        }   
    }
    
    echo json_encode($result_arr);
    die();
}



add_action('wp_ajax_save_bulk_voucher_info', 'save_bulk_voucher_info');
add_action('wp_ajax_nopriv_save_bulk_voucher_info', 'save_bulk_voucher_info');
function save_bulk_voucher_info()
{
	global $wpdb, $woocommerce;
	$count = $_POST['count'];
	$price = $_POST['price'];
	$order_ids = $_POST['order_ids'];
	$order_ids = serialize( $order_ids );


	$customer_id = get_current_user_id();

	date_default_timezone_set('Asia/Kolkata');
    $date = date('d-m-Y', time());

	$insert = $wpdb->insert('th_promotion_voucher_info', array(
                                    'date' => $date,
                                    'price' => $price,
                                    'order_count' => $count,                           
                                    'user_ref' => $customer_id,
                                    'order_ids' => $order_ids,                         
                                    ));
}

