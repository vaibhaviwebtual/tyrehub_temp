<?php
if ( !is_user_logged_in() ) {
    wp_redirect(get_permalink( get_option('woocommerce_myaccount_page_id') ).'/installer-login' );
}
get_header();
?>
<div id="pageContent">
	<div class="container installer service-request-page">
		<div class="woocommerce">
			<?php
			if ( ! defined( 'ABSPATH' ) ) {
				exit;
			}
			wc_print_notices();
			?>
			<?php
			$user = wp_get_current_user();
		   	$role = ( array ) $user->roles;
		   	$current_user_role = $role[0];
		   	if($current_user_role != 'Installer') {
				//do_action( 'woocommerce_account_navigation' );
			}
			do_action( 'woocommerce_account_navigation' );
			//do_action( 'woocommerce_account_content' ); ?>

			<div class="woocommerce-MyAccount-content <?php if($current_user_role == 'Installer') { echo 'installer-account'; } ?>">
				<?php
				if(isset($_GET['service_id']) || isset($_GET['voucher_id'])) {
					include_once('templates/details-page.php');
				}
				else {
				?>
				<ul class="nav nav-tabs">
					<li class="active"><a href="#open"><img src="<?php echo get_stylesheet_directory_uri(); ?>/images/icon/installer/open.png" alt="" /> Open</a></li>
					<li><a href="#completed"><img src="<?php echo get_stylesheet_directory_uri(); ?>/images/icon/installer/complated.png" alt="" /> Completed</a></li>
					<?php
					global $wpdb;
					$user_id = get_current_user_id();
					$SQL="SELECT * FROM th_installer_data WHERE user_id='".$user_id."' AND is_franchise='yes'";
					$franchise=$wpdb->get_row($SQL);

		  			if(empty($franchise)){?>
					<li><a href="#paid"><img src="<?php echo get_stylesheet_directory_uri(); ?>/images/icon/installer/paid.png" alt="" /> Paid</a></li>
				<?php }?>
				</ul>
				<div class="tab-content">
					<div id="open" class="tab-pane fade in active">
						<?php include_once('templates/all-service-tab.php'); ?>
					</div>
					<div id="completed" class="tab-pane fade">
						<?php include_once('templates/completed-service-tab.php'); ?>
					</div>
					<?php if(empty($franchise)){?>
					<div id="paid" class="tab-pane fade">
						<?php include_once('templates/paid-service.php'); ?>
					</div>
				<?php }?>
				</div>

				<script>
				jQuery(document).ready(function(){
					jQuery(".nav-tabs a").click(function(){
						jQuery(this).tab('show');
					});
				});
				</script>
			<?php } ?>
			</div>
		</div>
	</div>
</div>
<?php
get_footer();
?>