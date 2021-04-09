<?php



if ( !is_user_logged_in() )

 	{

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

			<!-- <div class="toggle-menu">

				<i class="fa fa-bars" aria-hidden="true"></i>

			</div> -->

			<?php

			$user = wp_get_current_user();

		   	$role = ( array ) $user->roles;

		   	$current_user_role = $role[0];

		   	if($current_user_role != 'Installer')

		   	{

				//do_action( 'woocommerce_account_navigation' ); 

			}
			do_action( 'woocommerce_account_navigation' );

		//	do_action( 'woocommerce_account_content' ); ?>

			

			<div class="woocommerce-MyAccount-content <?php if($current_user_role == 'Installer'){echo 'installer-account';} ?>">

				<?php 



				if(isset($_GET['service_id']) || isset($_GET['voucher_id']))

				{

					include_once('templates/details-page.php');

				}

				else

				{

				?>

					<div class="tab">

					  	<button class="tablinks active" onclick="openCity(event, 'London')">Open</button>

					  	<button class="tablinks" onclick="openCity(event, 'Paris')">Completed</button>

					  	<button class="tablinks" onclick="openCity(event, 'Paid')">Paid</button>

					</div>



					<div id="London" class="tab-content active all-services">

					  	<?php include_once('templates/all-service-tab.php'); ?>

					</div>



					<div id="Paris" class="tab-content paid-services">

					  <?php include_once('templates/completed-service-tab.php'); ?> 

					</div>



					<div id="Paid" class="tab-content paid-services">

					  <?php include_once('templates/paid-service.php'); ?> 

					</div>



					<script>

						function openCity(evt, cityName) {

						  var i, tabcontent, tablinks;

						  tabcontent = document.getElementsByClassName("tab-content");

						  for (i = 0; i < tabcontent.length; i++) {

						    tabcontent[i].style.display = "none";

						  }

						  tablinks = document.getElementsByClassName("tablinks");

						  for (i = 0; i < tablinks.length; i++) {

						    tablinks[i].className = tablinks[i].className.replace(" active", "");

						  }

						  document.getElementById(cityName).style.display = "block";

						  evt.currentTarget.className += " active";

						}

					</script>

				

		<?php } ?>

			</div>

		</div>

	</div>

</div>



<?php

get_footer();

?>