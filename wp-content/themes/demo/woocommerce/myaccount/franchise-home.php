<?php
if ( !is_user_logged_in() )
{
	 wp_redirect(get_permalink( get_option('woocommerce_myaccount_page_id') ).'/installer-login' );    
}
get_header();
$_SESSION['offline']='yes';
?>
<div id="pageContent">
	<div class="franchise_home">
		<div class="filter-sec">
			<div class="container">
				<div class="main_filter">
					<a href="<?php echo get_home_url()?>/my-account/offline-cartyre-purchase/" class="service_box">
						<div class="img-sec">
							<img class="normal" src="<?php echo get_stylesheet_directory_uri(); ?>/images/slide-icon/tyre.png" />
							<img class="hover" src="<?php echo get_stylesheet_directory_uri(); ?>/images/slide-icon/hover/tyre.png" />
						</div>
						<h3 class="title">Buy Car Tyre <i class="fa fa-angle-right" aria-hidden="true"></i></h3>
					</a>
					<a href="<?php echo get_home_url()?>/my-account/offline-twotyre-purchase/" class="service_box">
						<div class="img-sec">
							<img class="normal" src="<?php echo get_stylesheet_directory_uri(); ?>/images/slide-icon/two-three.png" />
							<img class="hover" src="<?php echo get_stylesheet_directory_uri(); ?>/images/slide-icon/hover/two-three.png" />
						</div>
						<h3 class="title">Buy Two - Three<br/>Wheeler Tyre <i class="fa fa-angle-right" aria-hidden="true"></i></h3>
					</a>
					<a href="<?php echo get_home_url()?>/my-account/offline-alignment-balancing/" class="service_box">
						<div class="img-sec">
							<img class="normal" src="<?php echo get_stylesheet_directory_uri(); ?>/images/slide-icon/balancing.png" />
							<img class="hover" src="<?php echo get_stylesheet_directory_uri(); ?>/images/slide-icon/hover/balancing.png" />
						</div>
						<h3 class="title">Alignment Balancing <i class="fa fa-angle-right" aria-hidden="true"></i></h3>
					</a>
					<a href="<?php echo get_home_url()?>/my-account/offline-car-wash/" class="service_box">
						<div class="img-sec">
							<img class="normal" src="<?php echo get_stylesheet_directory_uri(); ?>/images/slide-icon/car_wash.png" />
							<img class="hover" src="<?php echo get_stylesheet_directory_uri(); ?>/images/slide-icon/hover/car_wash.png" />
						</div>
						<h3 class="title">Car Wash <i class="fa fa-angle-right" aria-hidden="true"></i></h3>
					</a>
				</div>
			</div>
		</div>
	</div>
</div>
<?php
get_footer();
?>