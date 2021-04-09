<?php
cart_clear();
if ( !is_user_logged_in() )
 	{
      wp_redirect(get_permalink( get_option('woocommerce_myaccount_page_id') ).'/installer-login' );    
  	}
get_header();

$_SESSION['offline']='yes';
?>
<div id="pageContent">
	<div class="container installer-home wishlist">
		
		<div class="procuct_purchase franshise-purchase">
			<div class="btn_part">
				<h3>Wishlist</h3>
			</div>
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

function wishlist_product_list(){

     var t = jQuery(".admin_url").text();
    var shop_page = jQuery('.shop_page_url').text();
     jQuery.ajax({
         type: "POST",
         url: t,
         data: {
             action: "get_wishlist_products"
         },
         beforeSend: function() {
             jQuery(".product-container").html("<div class='modal-loader'><img src='https://www.tyrehub.com/wp-content/themes/demo/images/loading.gif' width='150px;' /></div>")
         },
         success: function(t) {
             jQuery(".product-container ").html(t);

         },
         error: function(t) {}
     });

    
}
setTimeout(function() {
        wishlist_product_list();
    }, 100);



</script>