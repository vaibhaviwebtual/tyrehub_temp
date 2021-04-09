<ul id="menu-primary" class="menu">
	<li class="<?php if($installer_page == 'edit-account'){ echo 'current-menu-item';} ?>">
		<a href="<?php echo get_home_url()?>/my-account/">
			<img src="<?php echo get_stylesheet_directory_uri(); ?>/images/icon/installer/my_account.png" alt="" />
			<span>My Account</span>
		</a>
	</li>
	<li class="<?php if($installer_page == 'tyre-products') { echo 'current-menu-item'; } ?>">
		<a href="<?php echo get_home_url()?>/my-account/tyre-products/">
			<img src="<?php echo get_stylesheet_directory_uri(); ?>/images/icon/supplier/tyre_price.png" alt="" />
			<span>Product Price</span>
		</a>
	</li>
	
	
	<li class="<?php if($installer_page == 'deals-discount') { echo 'current-menu-item'; } ?>">
		<a href="<?php echo get_home_url()?>/my-account/deals-discount/">
			<img src="<?php echo get_stylesheet_directory_uri(); ?>/images/icon/supplier/deals.png" alt="" />
			<span>Deals Discount</span>
		</a>
	</li>
	
	<li class="<?php if($installer_page == 'edit-account'){ echo 'current-menu-item';} ?>">
		<a href="<?php echo get_home_url()?>/my-account/edit-account/">
			<img src="<?php echo get_stylesheet_directory_uri(); ?>/images/icon/installer/settings.png" alt="" />
			<span>Settings</span>
		</a>
	</li>
	
	<li>
		<a href="<?php echo wp_logout_url( get_permalink( woocommerce_get_page_id( 'myaccount' ) ) ); ?>">
			<img src="<?php echo get_stylesheet_directory_uri(); ?>/images/icon/installer/logout.png" alt="" />
			<span>Logout</span>
		</a>
	</li> 
</ul>