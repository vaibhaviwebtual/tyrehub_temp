<ul id="menu-primary" class="menu">
	
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
	
	
	<li class="<?php if($installer_page == 'service-request') { echo 'current-menu-item'; } ?>">
		<a href="<?php echo get_home_url()?>/my-account/service-request/">
			<img src="<?php echo get_stylesheet_directory_uri(); ?>/images/icon/installer/service_request.png" alt="" />
			<span>Service Request</span>
		</a>
	</li>
	
	
	<li class="<?php if($installer_page == 'orders') { echo 'current-menu-item'; } ?>">
		<a href="<?php echo get_home_url()?>/my-account/orders/">
			<img src="<?php echo get_stylesheet_directory_uri(); ?>/images/icon/installer/order_tracking.png" alt="" />
			<span>Order Tracking</span>
		</a>
	</li>
	<li class="<?php if($installer_page == 'customer-register') { echo 'current-menu-item'; } ?>">
		<a href="<?php echo get_home_url()?>/my-account/customer-register/">
			<img src="<?php echo get_stylesheet_directory_uri(); ?>/images/icon/installer/customer_register.png" alt="" />
			<span>Customer Register</span>
		</a>
	</li>
	<li class="<?php if($installer_page == 'edit-account'){ echo 'current-menu-item';} ?>">
		<a href="<?php echo get_home_url()?>/my-account/edit-account/">
			<img src="<?php echo get_stylesheet_directory_uri(); ?>/images/icon/installer/settings.png" alt="" />
			<span>Settings</span>
		</a>
	</li>
	
	
	<!-- <li>
		<a href="<?php echo wp_logout_url( get_permalink( woocommerce_get_page_id( 'myaccount' ) ) ); ?>">
			<img src="<?php echo get_stylesheet_directory_uri(); ?>/images/icon/installer/logout.png" alt="" />
			<span>Logout</span>
		</a>
	</li> -->
</ul>