<ul id="menu-primary" class="menu">
	<li class="<?php if($installer_page == 'franchise-home') { echo 'current-menu-item'; } ?>">
		<a href="<?php echo get_home_url()?>/my-account/franchise-home/">
			<img src="<?php echo get_stylesheet_directory_uri(); ?>/images/icon/installer/admin/home.png" alt="" />
			<span>Home</span>
		</a>
	</li>
	
	<li class="menu-item parent-menu">
		<a href="#">
			<img src="<?php echo get_stylesheet_directory_uri(); ?>/images/icon/installer/admin/buy_tyre.png" alt="" />
			<span>Buy Tyre</span>
		</a>
		<ul class="sub-menu">
			<li class="menu-item <?php if($cr_page == 'offline-cartyre-purchase'){ echo 'current-menu-item';} ?>">
				<a href="<?php echo get_home_url()?>/my-account/offline-cartyre-purchase/">
					<img src="<?php echo get_stylesheet_directory_uri(); ?>/images/icon/installer/admin/tyre.png" alt="" />
					<span>Buy Car Tyre</span>
				</a>
			</li>
			<li class="menu-item <?php if($cr_page == 'offline-twotyre-purchase'){ echo 'current-menu-item';} ?>">
				<a href="<?php echo get_home_url()?>/my-account/offline-twotyre-purchase/">
					<img src="<?php echo get_stylesheet_directory_uri(); ?>/images/icon/installer/admin/two-three.png" alt="" />
					<span class="fr-tyre">Buy Two - Three Wheeler Tyre</span>
				</a>
			</li>
		</ul>
	</li>
	
	<li class="menu-item parent-menu">
		<a href="#">
			<img src="<?php echo get_stylesheet_directory_uri(); ?>/images/icon/installer/admin/car_service.png" alt="" />
			<span>Buy Services</span>
		</a>
		<ul class="sub-menu">
			<li class="menu-item <?php if($cr_page == 'offline-alignment-balancing'){ echo 'current-menu-item';} ?>">
				<a href="<?php echo get_home_url()?>/my-account/offline-alignment-balancing/">
					<img src="<?php echo get_stylesheet_directory_uri(); ?>/images/icon/installer/admin/wheel_alignment.png" alt="" />
					<span class="fr-tyre">Alignment & Balancing</span>
				</a>
			</li>
			<li class="menu-item <?php if($cr_page == 'offline-car-wash'){ echo 'current-menu-item';} ?>">
				<a href="<?php echo get_home_url()?>/my-account/offline-car-wash/">
					<img src="<?php echo get_stylesheet_directory_uri(); ?>/images/icon/installer/admin/car_wash.png" alt="" />
					<span>Car Wash</span>
				</a>
			</li>
		</ul>
	</li>
	
	<li class="<?php if($installer_page == 'service-request') { echo 'current-menu-item'; } ?>">
		<a href="<?php echo get_home_url()?>/my-account/service-request/">
			<img src="<?php echo get_stylesheet_directory_uri(); ?>/images/icon/installer/service_request.png" alt="" />
			<span>Service Request</span>
		</a>
	</li>
	
	<li class="<?php if($installer_page == 'offline-order-history') { echo 'current-menu-item'; } ?>">
		<a href="<?php echo get_home_url()?>/offline-order-history/">
			<img src="<?php echo get_stylesheet_directory_uri(); ?>/images/icon/installer/order_tracking.png" alt="" />
			<span>Customer Order</span>
		</a>
	</li>
	
	
	<?php /*?><li class="<?php if($installer_page == 'orders') { echo 'current-menu-item'; } ?>">
		<a href="<?php echo get_home_url()?>/my-account/orders/">
			<img src="<?php echo get_stylesheet_directory_uri(); ?>/images/icon/installer/order_tracking.png" alt="" />
			<span>Order Tracking</span>
		</a>
	</li> <?php */?>
	
	<li class="<?php if($installer_page == 'customer-register') { echo 'current-menu-item'; } ?>">
		<a href="<?php echo get_home_url()?>/my-account/customer-register/">
			<img src="<?php echo get_stylesheet_directory_uri(); ?>/images/icon/installer/customer_register.png" alt="" />
			<span>Customer Register</span>
		</a>
	</li>
	<?php /*?><li class="<?php if($installer_page == 'edit-account'){ echo 'current-menu-item';} ?>">
		<a href="<?php echo get_home_url()?>/my-account/edit-account/">
			<img src="<?php echo get_stylesheet_directory_uri(); ?>/images/icon/installer/settings.png" alt="" />
			<span>Settings</span>
		</a>
	</li> <?php */?>
	<li class="<?php if($installer_page == 'offline-wishlist') { echo 'current-menu-item'; } ?>">
		<a href="<?php echo get_home_url()?>/my-account/offline-wishlist/">
			<img src="<?php echo get_stylesheet_directory_uri(); ?>/images/icon/installer/order_tracking.png" alt="" />
			<span>Wishlist</span>
		</a>
	</li>
<?php if(!isset($_SESSION['admin_access'])) {?>
	<li class="">
		<a href="javascript:void(0);" id="admin_access">
			<img src="<?php echo get_stylesheet_directory_uri(); ?>/images/icon/installer/admin_access.png" alt="" />
			<span>Admin Access</span>
		</a>
	</li>
<?php }?>
	
	<?php
	/*$_SESSION['admin_access'] = time();
	// later
	if ((time() - $_SESSION['admin_access']) > (60 * 1)) {
	    unset($_SESSION['admin_access']);
	} */
	if(isset($_SESSION['admin_access'])) {
	?>
	
	<li class="menu-item parent-menu current-menu-item">
		<a href="#">
			<img src="<?php echo get_stylesheet_directory_uri(); ?>/images/icon/installer/admin_access.png" alt="" />
			<span>Admin Access</span>
		</a>
		<ul class="sub-menu">
			<li class="menu-item <?php if($cr_page == 'franchise-cartyre-purchase'){ echo 'current-menu-item';} ?>">
				<a href="<?php echo get_home_url()?>/my-account/franchise-tyre-purchase/">
					<img src="<?php echo get_stylesheet_directory_uri(); ?>/images/icon/installer/admin/tyre.png" alt="" />
					<span>Buy Tyre</span>
				</a>
			</li>
			<li class="menu-item <?php if($cr_page == 'orders'){ echo 'current-menu-item';} ?>">
				<a href="<?php echo get_home_url()?>/my-account/orders/">
					<img src="<?php echo get_stylesheet_directory_uri(); ?>/images/icon/installer/admin/order_tracking.png" alt="" />
					<span>Order History</span>
				</a>
			</li>
			<li class="menu-item <?php if($cr_page == 'my-account'){ echo 'current-menu-item';} ?>">
				<a href="<?php echo get_home_url()?>/my-account/">
					<img src="<?php echo get_stylesheet_directory_uri(); ?>/images/icon/installer/admin/pending_order.png" alt="" />
					<span>Delivery Pending</span>
				</a>
			</li>
			<li class="menu-item <?php if($installer_page == 'edit-account'){ echo 'current-menu-item';} ?>">
				<a href="<?php echo get_home_url()?>/my-account/edit-account/">
					<?php /*?><img src="<?php echo get_stylesheet_directory_uri(); ?>/images/icon/installer/admin/settings.png" alt="" /><?php */?>
					<img src="<?php echo get_stylesheet_directory_uri(); ?>/images/icon/installer/admin/my_account.png" alt="" />
					<span>Settings</span>
				</a>
			</li>
			<li class="menu-item <?php if($installer_page == 'edit-account'){ echo 'current-menu-item';} ?>">
				<a href="<?php echo get_home_url()?>/my-account/payout-process/">
					
					<img src="<?php echo get_stylesheet_directory_uri(); ?>/images/icon/installer/admin/pending_order.png" alt="" />
					<span>Payout Process</span>
				</a>
			</li>
			<li class="menu-item <?php if($installer_page == 'edit-account'){ echo 'current-menu-item';} ?>">
				<a href="<?php echo get_home_url()?>/my-account/wallet-history/">
					
					<img src="<?php echo get_stylesheet_directory_uri(); ?>/images/icon/installer/admin/pending_order.png" alt="" />
					<span>Wallet History</span>
				</a>
			</li>
			<li class="menu-item <?php if($installer_page == 'edit-account'){ echo 'current-menu-item';} ?>">
				<a href="<?php echo get_home_url()?>/my-account/deleted-orders/">
					
					<img src="<?php echo get_stylesheet_directory_uri(); ?>/images/icon/installer/admin/order_tracking.png" alt="" />
					<span>Deleted Orders</span>
				</a
			</li>
			<li class="menu-item <?php if($installer_page == 'edit-account'){ echo 'current-menu-item';} ?>">
				<a href="javascript:void(0);" id="admin_access_logout">
					<img src="<?php echo get_stylesheet_directory_uri(); ?>/images/icon/installer/admin/logout.png" alt="" />
					<span>Admin Logout</span>
				</a>
			</li>
		</ul>
	</li>
<?php }?>

	
	
	<!-- <li>
		<a href="<?php echo wp_logout_url( get_permalink( woocommerce_get_page_id( 'myaccount' ) ) ); ?>">
			<img src="<?php echo get_stylesheet_directory_uri(); ?>/images/icon/installer/logout.png" alt="" />
			<span>Logout</span>
		</a>
	</li> -->
</ul>
