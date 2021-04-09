<?php
 /* Template Name: tracking-process */
get_header();
?>
<div id="pageContent" class="">
	<div class="admin-url" hidden=""><?php echo admin_url('admin-ajax.php');
	    ?></div>
	<div class="container tracking-process">
		<?php 
			if(is_user_logged_in())
			{
				wp_redirect( get_site_url().'/my-account/orders/' );
				exit;
			}
		?>
	<div class="row" style="margin: 0;">	
		<h2>Track Order</h2>
		<div class="track-line col-md-12">
			<div class="col-md-5">
				<h6>Using Mobile number</h6>
				<form method="post" action="" class="track-form">
					<span>Mobile No.</span><input type="text" name="mobileno" class="mobileno" placeholder="Enter mobile no"><button class="sendotp  btn-invert btn"><span>Send OTP</span></button>
				</form>
			</div> 
			<div class="col-md-7"> 
				<h6>Using Order Number</h6>
				<form method="post" action="" class="track-form">
					<span>Order Number</span>
					<input type="text" name="order_number" placeholder=" Order number" >
					<input type="submit" name="searchByname" class="searchByname  btn-invert btn" value="Search">
				</form>
			</div> 
		</div>
	</div>

	<div class="order-details col-md-12">
	<?php
	
		$my_orders_columns = apply_filters( 'woocommerce_my_account_my_orders_columns', array(
						'order-number'  => __( 'Order', 'woocommerce' ),
						'order-date'    => __( 'Date', 'woocommerce' ),
						'order-status'  => __( 'Status', 'woocommerce' ),
						'order-total'   => __( 'Total', 'woocommerce' ),
						'order-actions' => '&nbsp;',
						));

	
		global $wpdb , $woocommerce;
		if(isset($_POST['otp']))
		{
			$username = $_POST['mobileno'];
			$otp = $_POST['otp'];

			$user = get_user_by('login', $username);
			$user_id = $user->ID;
			$name = $user->display_name;
			$customer_id = $user_id;
			
			$filters = array(
		        'post_status' => 'any',
		        'post_type' => 'shop_order',
		        'numberposts' => -1,
		        'orderby' => 'modified',
		        'order' => 'DESC'
		    );

		    $loop = new WP_Query($filters);
		    $order_id_arr = [];
		    while ($loop->have_posts()) 
				    {
				        $loop->the_post();
				        $order = new WC_Order($loop->post->ID);	     
				        
				       	  $order_id = $order->get_id();
				      	 $order_user_id = get_post_meta($order_id, '_customer_user', true);

				        if($order && $order_user_id == $user_id)
				 		{
				 			$order_id_arr[] = $order_id;
				 		}
				 	}
			echo '<h2>Order Details</h2>';
		   	if(empty($order_id_arr))
		   	{
		   		echo '<h5>No Order Found</h5>';
		   	}
		   	else{
      	?>
		    	<table class="shop_table shop_table_responsive my_account_orders ">

					<thead>
						<tr>
							<?php foreach ( $my_orders_columns as $column_id => $column_name ) : ?>
								<th class="<?php echo esc_attr( $column_id ); ?>"><span class="nobr"><?php echo esc_html( $column_name ); ?></span></th>
							<?php endforeach; ?>
						</tr>
					</thead>

					<tbody>
		<?php
				    while ($loop->have_posts()) 
				    {
				        $loop->the_post();
				        $order = new WC_Order($loop->post->ID);	     
				        
				       	$order_id = $order->get_id();
				      	$order_user_id = get_post_meta($order_id, '_customer_user', true);

				        if($order && $order_user_id == $user_id)
				 		{
							
					?>					
							<tr class="order">
							<?php 
								foreach ( $my_orders_columns as $column_id => $column_name ) : ?>
								<td class="<?php echo esc_attr( $column_id ); ?>" data-title="<?php echo esc_attr( $column_name ); ?>">
									<?php if ( has_action( 'woocommerce_my_account_my_orders_column_' . $column_id ) ) : ?>
										<?php do_action( 'woocommerce_my_account_my_orders_column_' . $column_id, $order ); ?>

									<?php elseif ( 'order-number' === $column_id ) : ?>
										<a href="<?php echo esc_url( $order->get_view_order_url() ); ?>">
											<?php echo _x( '#', 'hash before order number', 'woocommerce' ) . $order->get_order_number(); ?>
										</a>

									<?php elseif ( 'order-date' === $column_id ) : ?>
										<time datetime="<?php echo esc_attr( $order->get_date_created()->date( 'c' ) ); ?>"><?php echo esc_html( wc_format_datetime( $order->get_date_created() ) ); ?></time>

									<?php elseif ( 'order-status' === $column_id ) : ?>
										<?php echo esc_html( wc_get_order_status_name( $order->get_status() ) ); ?>

									<?php elseif ( 'order-total' === $column_id ) : ?>
										<?php
										/* translators: 1: formatted order total 2: total order items */
										printf( _n( '%1$s for %2$s item', '%1$s for %2$s items', $item_count, 'woocommerce' ), $order->get_formatted_order_total(), $item_count );
										?>

									<?php elseif ( 'order-actions' === $column_id ) : ?>
										<?php
										$actions = wc_get_account_orders_actions( $order );
			
										if ( ! empty( $actions ) ) {
											foreach ( $actions as $key => $action ) {
												if($key != "invoice"){
													echo '<a href="' . esc_url( $action['url'] ) . '" class="button ' . sanitize_html_class( $key ) . '">' . esc_html( $action['name'] ) . '</a>';
												}
												
											}
										}
										?>
									<?php endif; ?>
								</td>
								<?php endforeach; ?>
							</tr>					
				<?php
						}
					}
			?>
					</tbody>
				</table>
			<?php
			}
		}
		

		if(isset($_POST['order_number']))
		{
			$order_number = $_POST['order_number'];	
			$order = wc_get_order( $order_number );	
			echo '<h2>Order Details</h2>';
			if($order)
			{
				$my_orders_columns = apply_filters( 'woocommerce_my_account_my_orders_columns', array(
					'order-number'  => __( 'Order', 'woocommerce' ),
					'order-date'    => __( 'Date', 'woocommerce' ),
					'order-status'  => __( 'Status', 'woocommerce' ),
					'order-total'   => __( 'Total', 'woocommerce' ),
					'order-actions' => '&nbsp;',
				));
			?>

			<table class="shop_table shop_table_responsive my_account_orders">

				<thead>
					<tr>
						<?php foreach ( $my_orders_columns as $column_id => $column_name ) : ?>
							<th class="<?php echo esc_attr( $column_id ); ?>"><span class="nobr"><?php echo esc_html( $column_name ); ?></span></th>
						<?php endforeach; ?>
					</tr>
				</thead>

				<tbody>
					<tr class="order">
							<?php foreach ( $my_orders_columns as $column_id => $column_name ) : ?>
								<td class="<?php echo esc_attr( $column_id ); ?>" data-title="<?php echo esc_attr( $column_name ); ?>">
									<?php if ( has_action( 'woocommerce_my_account_my_orders_column_' . $column_id ) ) : ?>
										<?php do_action( 'woocommerce_my_account_my_orders_column_' . $column_id, $order ); ?>

									<?php elseif ( 'order-number' === $column_id ) : ?>
										<a href="<?php echo esc_url( $order->get_view_order_url() ); ?>">
											<?php echo _x( '#', 'hash before order number', 'woocommerce' ) . $order->get_order_number(); ?>
										</a>

									<?php elseif ( 'order-date' === $column_id ) : ?>
										<time datetime="<?php echo esc_attr( $order->get_date_created()->date( 'c' ) ); ?>"><?php echo esc_html( wc_format_datetime( $order->get_date_created() ) ); ?></time>

									<?php elseif ( 'order-status' === $column_id ) : ?>
										<?php echo esc_html( wc_get_order_status_name( $order->get_status() ) ); ?>

									<?php elseif ( 'order-total' === $column_id ) : ?>
										<?php
										/* translators: 1: formatted order total 2: total order items */
										printf( _n( '%1$s for %2$s item', '%1$s for %2$s items', $item_count, 'woocommerce' ), $order->get_formatted_order_total(), $item_count );
										?>

									<?php elseif ( 'order-actions' === $column_id ) : ?>
										<?php
										$actions = wc_get_account_orders_actions( $order );
			
										if ( ! empty( $actions ) ) {
											foreach ( $actions as $key => $action ) {
												if($key != "invoice"){
													echo '<a href="' . esc_url( $action['url'] ) . '" class="button ' . sanitize_html_class( $key ) . '">' . esc_html( $action['name'] ) . '</a>';
												}
												
											}
										}
										?>
									<?php endif; ?>
								</td>
							<?php endforeach; ?>
						</tr>
				</tbody>
			</table>
			<?php
 			}
 			else{
 				echo '<h4 style="text-align: center;">No Order Found.</h4>';
 			}
		}
?>
</div>
</div>
</div>
<?php
get_footer();
?>