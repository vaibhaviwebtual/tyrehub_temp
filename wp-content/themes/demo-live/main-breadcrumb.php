<?php 
function main_breadcrumb($page) {
    if($page=='service-partner') {
        $servicepartner='active';
    } elseif($page=='cartype') {
      $cartype='active';  
    } elseif($page=='review-order') {
         $revieworder='active';
    } elseif($page=='checkout') {
         $checkout='active';
    } elseif($page=='order-received') {
         $orderreceived='active';
    }

?>	
<?php
					global $wpdb;
					$user_id = get_current_user_id();
					$SQL="SELECT * FROM th_installer_data WHERE user_id='".$user_id."' AND is_franchise='yes'";
					$franchise=$wpdb->get_row($SQL);
					if($franchise) { }else{
?>
	<ul class="breadcrumb-list-new">
		<li class="<?=$cartype;?>">
			<div class="stop-rounding">
				<span class="step-number">
					<img class="img-responsive" src="<?php echo get_stylesheet_directory_uri(); ?>/images/icon/breadcrumb/tyre_or_service.png" />
				</span>
			</div> 
			<?php if($_SESSION['shopURL']){?>
			<a href="<?=$_SESSION['shopURL'];?>" class="step-link"> Select Tyre or Services</a>
		<?php } else { ?><span class="step-link">Select Tyre or Services</span><?php } ?>
		</li>
		<li class="<?=$servicepartner;?>">
			<div class="stop-rounding">
				<span class="step-number">
					<img class="img-responsive" src="<?php echo get_stylesheet_directory_uri(); ?>/images/icon/breadcrumb/select_store.png" />
				</span>
			</div> 

			<?php if($_SESSION['service-partner']){?>
			<a href="<?=$_SESSION['service-partner'];?>" class="step-link"> Select Service Partner </a>
		<?php }else{?><span class="step-link">Select Service Partner</span><?php } ?>
		</li>
		<li class="<?=$revieworder?>">
			<div class="stop-rounding">
				<span class="step-number">
					<img class="img-responsive" src="<?php echo get_stylesheet_directory_uri(); ?>/images/icon/breadcrumb/review_order.png" />
				</span>
			</div> 
			<?php if($_SESSION['cartpage']){?>
			<a href="<?=$_SESSION['cartpage'];?>" class="step-link"> Review Order </a>
		<?php } else { ?><span class="step-link">Review Order</span><?php } ?>
		</li>
		<li class="<?=$checkout;?>">
			<div class="stop-rounding">
				<span class="step-number">
					<img class="img-responsive" src="<?php echo get_stylesheet_directory_uri(); ?>/images/icon/breadcrumb/check_out_pay.png" />
				</span>
			</div>
			<?php if($_SESSION['checkout']){?>
			<a href="<?=$_SESSION['checkout'];?>" class="step-link"> Check Out & Pay  </a>
		<?php } else { ?><span class="step-link">Check Out & Pay</span><?php } ?>
		</li>
		<li class="<?=$orderreceived;?>">
			<div class="stop-rounding">
				<span class="step-number">
					<img class="img-responsive" src="<?php echo get_stylesheet_directory_uri(); ?>/images/icon/breadcrumb/order_placed.png" />
				</span>
			</div> 
			<a href="#" class="step-link"> Order Placed </a>
		</li>
	</ul>
<?php } } ?>