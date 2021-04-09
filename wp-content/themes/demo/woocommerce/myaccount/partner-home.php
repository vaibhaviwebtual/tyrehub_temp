<?php
cart_clear_franchise();
if ( !is_user_logged_in() )
 	{
      wp_redirect(get_permalink( get_option('woocommerce_myaccount_page_id') ).'/installer-login' );    
  	}
get_header();
?>
<div id="pageContent">
	<div class="container installer-home">
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
					<option>Apollo</option>
					<option>Bridgestone</option>
					<option>Cavendish</option>
					<option>Ceat</option>
					<option>Falken</option>
					<option>GoodYear</option>
					<option>MRF</option>
					<option>JK</option>
				</select>
			</div>
			<div class="column">
				<button class="searchbywidth btn btn-invert get-tyre-bymodel store-search"><span>Search</span></button>
			</div>
		</div>
		<div class="procuct_purchase franshise-purchase">
			<div class="btn_part">
				<button class="btn btn-invert button pending_orders_list"><span><i class="fa fa-clock-o" aria-hidden="true"></i> Pending Order</span></button>
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
<?php
get_footer();
?>