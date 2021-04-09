<?php
cart_clear_franchise();
if ( !is_user_logged_in() )
 	{
      wp_redirect(get_permalink( get_option('woocommerce_myaccount_page_id') ).'/installer-login' );
  	}

get_header();
	if(!isset($_SESSION['admin_access'])) {
		wp_redirect(site_url('/my-account/franchise-home/'));
	}
?>
<div id="pageContent">
	<div class="container installer-home franchise-po-generate">
		<div class="search-bar">
			<div class="column">
				<span class="title">Width</span>
				<input type="text" name="" class="width">
			</div>
			<div class="column">
				<span class="title">Ratio</span>
				<input type="text" name="" class="ratio">
			</div>
			<div class="column">
				<span class="title">Diameter</span>
				<input type="text" name="" class="diameter">
			</div>
			<div class="column">
				<span class="title">Search</span>
				<select class="searchbyname">
					<option value="">Select Category</option>
                    <?php 
                    $terms = get_terms(array('taxonomy'=>'pa_brand','hide_empty' => false));
                    foreach ( $terms as $term ){?>
                    <option value="<?php echo $term->name; ?>"><?php echo $term->name; ?></option>
                    
                    <?php }?>
					<!-- <option>Apollo</option>
					<option>Bridgestone</option>
					<option>Cavendish</option>
					<option>Ceat</option>
					<option>Falken</option>
					<option>GoodYear</option>
					<option>MRF</option>
					<option>JK</option>
                    <option>TVS</option> -->
				</select>
			</div>
			<div class="column">
				<button class="searchbywidth btn btn-invert get-tyre-bymodel store-search"><span>Search</span></button>
			</div>
		</div>
		<div class="procuct_purchase franshise-purchase">
			<div class="btn_part">
				<button class="btn btn-invert button pending_orders_list"><span><i class="fa fa-clock-o" aria-hidden="true"></i> Pending Order</span></button>
				<button class="btn btn-invert button wishlist_products"><span><i class="fa fa-clock-o" aria-hidden="true"></i> Admin Cart</span></button>
				<button class="btn btn-invert button add_to_cart_button"><span><i class="fa fa-shopping-cart"></i>Add to cart</span></button>
			</div>
			<div class="all-totalprice">Total Price: <i class="fa fa-inr" aria-hidden="true"></i> <span id="all-totalprice">00.00</span></div>
			<div class="right_part">
				<?php
				if ( ! defined( 'ABSPATH' ) ) {
					exit;
				}
				wc_print_notices();

				$user = wp_get_current_user();
				$role = ( array ) $user->roles;
				$current_user_role = $role[0];
				if($current_user_role != 'Installer') {
					do_action( 'woocommerce_account_navigation' );
				}
				//do_action( 'woocommerce_account_content' ); ?>
				<div class="woocommerce-MyAccount-content <?php if($current_user_role == 'Installer'){echo 'installer-account';} ?>">
					<div class="product-container">
						Please search for purchase
					</div>
				</div>
			</div>
		</div>
	</div>
</div>
	<div class="modal fade" id="dupplicate_prd_cart" role="dialog">
  	<div class="modal-dialog">  
    	<!-- Modal content-->
    	<div class="modal-content"  style="pointer-events: auto;">
    		 <div class="modal-header">        
      		</div>
    		<div class="modal-body">
    			<p id="dupplicate_pro_msg">
    				
    			</p>				    			
    		</div>
    		<div class="modal-footer">
    			<button type="button" class="btn btn-default" id="btnclose" data-dismiss="modal">Ok</button>
    			<a href="<?php echo get_site_url().'/cart';?> " class="btn btn-invert"><span>Cart</span></a>
    		</div>
    	</div>
    </div>
</div>
<?php
get_footer();
?>
<script type="text/javascript">

function pending_orders_list(){

     var t = jQuery(".admin_url").text();
    var shop_page = jQuery('.shop_page_url').text();
     jQuery.ajax({
         type: "POST",
         url: t,
         data: {
             action: "get_pending_orders"
         },
         beforeSend: function() {
             jQuery(".product-container").html("<div class='modal-loader'><img src='https://www.tyrehub.com/wp-content/themes/demo/images/loading.gif' width='150px;' /></div>")
         },
         success: function(t) {
             jQuery(".product-container ").html(t);

         },
         error: function(t) {}
     });

     jQuery.ajax({
         type: "POST",
         url: t,
         data: {
             action: "get_pending_orders_total"
         },
         beforeSend: function() {
         },
         success: function(t) {
             jQuery("#all-totalprice").html(t);

         },
         error: function(t) {}
     })
}
setTimeout(function() {
        jQuery('.pending_orders_list').trigger('click');
    }, 100);


jQuery(document).on("click", ".pending_orders_list", function() {
    pending_orders_list();
 });


function wishlist_products_list(){

     var t = jQuery(".admin_url").text();
    var shop_page = jQuery('.shop_page_url').text();
     jQuery.ajax({
         type: "POST",
         url: t,
         data: {
             action: "wishlist_products_list"
         },
         beforeSend: function() {
             jQuery(".product-container").html("<div class='modal-loader'><img src='https://www.tyrehub.com/wp-content/themes/demo/images/loading.gif' width='150px;' /></div>")
         },
         success: function(t) {
             jQuery(".product-container ").html(t);

         },
         error: function(t) {}
     });

     jQuery.ajax({
         type: "POST",
         url: t,
         data: {
             action: "get_wishlist_total"
         },
         beforeSend: function() {
         },
         success: function(t) {
             jQuery("#all-totalprice").html(t);

         },
         error: function(t) {}
     })
}

jQuery(document).on("click", ".wishlist_products", function() {
    wishlist_products_list();
 });
</script>