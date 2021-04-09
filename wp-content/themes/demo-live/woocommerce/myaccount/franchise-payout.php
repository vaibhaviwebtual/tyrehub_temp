<?php
cart_clear_franchise();
if ( !is_user_logged_in() || !isset($_SESSION['admin_access'])) {
  wp_redirect(get_permalink( get_option('woocommerce_myaccount_page_id') ).'/installer-login' );    
}
get_header();
?>
<link rel="stylesheet" href="//netdna.bootstrapcdn.com/bootstrap/3.0.0/css/bootstrap-glyphicons.css">
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
		   
            $SQL="SELECT * FROM th_installer_data WHERE user_id='".$user->ID."'";
            $franchise=$wpdb->get_row($SQL);

		//	do_action( 'woocommerce_account_content' ); ?>
			<style>
				.deals-top {
					background-color: #f2f2f2;
					padding: 15px;
				}
				.deals-top h3 {
					font-size: 26px;
					margin-bottom: 0px;
				}
				.add-discount {
					background-color: #2f3672;
					color: #fff;
					border: 0px;
					height: 35px;
					padding: 0px 20px;
					cursor: pointer;
					display: inline-block;
					vertical-align: top;
					line-height: 35px;
				    
				    display: inline-block;
				}
				.add-discount:hover {
					background-color: #ffd642;
				}
				.active {
					background-color: #ffd642;
				}
				.active:hover {
					background-color: #2f3672;
				}
				#example_wrapper {
				    display: inline-block;
    				width: 100%;
					background-color: #f2f2f2;
    				padding: 15px;
				}
				#example_wrapper input[type="search"] {
					padding: 0px;
				}
				#example_wrapper a {
					color: #000;
					text-transform: capitalize;
				}
				#example_wrapper .pagination>.active>a,
				#example_wrapper .pagination>.active>a:focus,
				#example_wrapper .pagination>.active>a:hover,
				#example_wrapper .pagination>.active>span,
				#example_wrapper .pagination>.active>span:focus,
				#example_wrapper .pagination>.active>span:hover {
					color: #fff;
					background-color: #2f3672;
    				border-color: #2f3672;
				}
			</style>
				<div class="woocommerce-MyAccount-content" style="width: 100%;">
					<div class="deals-top row">
						
						<div class="col-md-6">
							<a href="<?=site_url('/my-account/payout-process/');?>" class="add-discount">Completed</a>
							<a href="<?=site_url('/my-account/franchise-payout/');?>" class="add-discount active">Payout</a>
							<a href="<?=site_url('/my-account/payout-history/');?>" class="add-discount">History</a>
						</div>
						<div class="col-md-6">
							<h3>Payout</h3>
						</div>
					</div>
					<link rel="stylesheet" type="text/css" href="https://cdn.datatables.net/1.10.19/css/dataTables.bootstrap.min.css">
					<script type="text/javascript" src="https://cdn.datatables.net/1.10.19/js/jquery.dataTables.min.js"></script>
					<script type="text/javascript" src="https://cdn.datatables.net/1.10.19/js/dataTables.bootstrap.min.js"></script>

					<table id="example" class="table table-striped table-bordered" style="width:100%">
						<thead>
							<tr>
								<th>Pay Order No</th>
								<th>Date</th>
                                <th>Description</th>
                                <th>Price</th>
								<th>Invoice</th>
							</tr>
						</thead>
						<tbody>
							<?php 
							global $wpdb;
							$SQL="SELECT * FROM `th_profit_payout` WHERE franchise_id='".$franchise->installer_data_id."'   ORDER BY payout_id DESC";
							$results=$wpdb->get_results($SQL);
                            
							foreach ($results as $key => $res) {
                                 echo '<span style="margin-right:12px;"><img class="service-img"  title="'.$value['name'].'" src="'.$value['image'].'"></span>';
                                ?>
							<tr>
								<td><?=$res->invoice_no;?></td>
                                <td><?=date('d-m-Y H:i:s',strtotime($res->insert_date));?></td>
								<td><?=$res->massage;?></td>
                                <td><i class="fa fa-inr"></i> <?=$res->amount;?></td>
                                <td style="text-align: center;"><a href="<?=site_url();?>/wp-admin/admin-ajax.php?action=franchise_report_pdf&document_type=paid-invoice-franchise&payout_id=<?=$res->payout_id;?>&order_ids=<?=$res->payout_id;?>&_wpnonce=04e74a5779" target="_blank"  class="single_view"><i class="fa fa-file-pdf-o" style="font-size: 25px;"></i></a> </td>
							</tr>
							<?php } ?>
						</tbody>
						
					</table>
				</div>
			</div>
		</div>
</div>

<?php
get_footer();
?>
