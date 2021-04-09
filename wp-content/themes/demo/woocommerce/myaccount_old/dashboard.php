<?php
/**
 * My Account Dashboard
 *
 * Shows the first intro screen on the account dashboard.
 *
 * This template can be overridden by copying it to yourtheme/woocommerce/myaccount/dashboard.php.
 *
 * HOWEVER, on occasion WooCommerce will need to update template files and you
 * (the theme developer) will need to copy the new files to your theme to
 * maintain compatibility. We try to do this as little as possible, but it does
 * happen. When this occurs the version of the template file will be bumped and
 * the readme will list any important changes.
 *
 * @see         https://docs.woocommerce.com/document/template-structure/
 * @author      WooThemes
 * @package     WooCommerce/Templates
 * @version     2.6.0
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit; // Exit if accessed directly
}
?>

<p><?php
	/* translators: 1: user display name 2: logout url */
	printf(
		__( 'Hello %1$s (not %1$s? <a href="%2$s">Log out</a>)', 'woocommerce' ),
		'<strong>' . esc_html( $current_user->display_name ) . '</strong>',
		esc_url( wc_logout_url( wc_get_page_permalink( 'myaccount' ) ) )
	);
?></p>

<p><?php
	printf(
		__( 'From your account dashboard you can view your <a href="%1$s">recent orders</a>, manage your <a href="%2$s">shipping and billing addresses</a>, and <a href="%3$s">edit your password and account details</a>.', 'woocommerce' ),
		esc_url( wc_get_endpoint_url( 'orders' ) ),
		esc_url( wc_get_endpoint_url( 'edit-address' ) ),
		esc_url( wc_get_endpoint_url( 'edit-account' ) )
	);
?></p>

<?php
	/**
	 * My Account dashboard.
	 *
	 * @since 2.6.0
	 */
	do_action( 'woocommerce_account_dashboard' );

	/**
	 * Deprecated woocommerce_before_my_account action.
	 *
	 * @deprecated 2.6.0
	 */

	 if(current_user_can('supplier')){ ?>
		 	<?php
				global $wpdb;
				$current_user_id = get_current_user_id();
				$current_user = get_user_by( 'id', $current_user_id ); // 54 is a user ID
				//	var_dump($current_user);
				$user_id = $current_user->ID;
				$name = $current_user->display_name;
				$number =  $current_user->user_login;
				$email_id =  $current_user->user_email;
				if(current_user_can('supplier')){
					$installer = "SELECT * FROM th_supplier_data WHERE user_id = '$user_id'";
				}

			
				
		        $row = $wpdb->get_results($installer);
		     //   var_dump($row); 
		        foreach ($row as $key => $value)
		        {
		        	$store = $value->business_name;
		        	$add = $value->address;
		        	$gstin = $value->gst_no;
		        	//$email_id = $value->email;
		        }
				if (\strpos($email_id, 'test') !== false) {
				     $flag =  'true';
				}else{
					 $flag =  'false';
				}
			?>
		 	<div class="my-account installer-info">
				<div><strong>Contact Person Name: </strong><?php echo $name; ?></div>		
				<div><strong>Supplier/Shop Name: </strong><?php echo $store; ?></div>
				<div><strong>Contact Person No: </strong><?php echo $number; ?></div>
				<div><strong>Shop Address: </strong><?php echo $add; ?></div>
				<div><strong>GSTIN: </strong><?php if($gstin != ""){echo $gstin; }else{ echo "Not Available"; } ?></div>
				<?php if($flag == "false"){ ?>
					<div><strong>Email ID: </strong><?php echo $email_id; ?></div>
				<?php } ?>
				<a href="<?=esc_url( wc_get_endpoint_url( 'edit-account' ) );?>"><button class="search-btn">Edit</button></a>
			</div>
			
		<?php  } 
		
	do_action( 'woocommerce_before_my_account' );

	/**
	 * Deprecated woocommerce_after_my_account action.
	 *
	 * @deprecated 2.6.0
	 */
	do_action( 'woocommerce_after_my_account' );

/* Omit closing PHP tag at the end of PHP files to avoid "headers already sent" issues. */

$my_orders_columns = apply_filters( 'woocommerce_my_account_my_orders_columns', array(
	'order-number'  => __( 'Order', 'woocommerce' ),
	'order-date'    => __( 'Date', 'woocommerce' ),
	'order-status'  => __( 'Status', 'woocommerce' ),
	'order-total'   => __( 'Total', 'woocommerce' ),
	'order-actions' => '&nbsp;',
) );

$customer_orders = get_posts( apply_filters( 'woocommerce_my_account_my_orders_query', array(
	'numberposts' => -1,
	'meta_key'    => '_customer_user',
	'meta_value'  => get_current_user_id(),
	'post_type'   => wc_get_order_types( 'view-orders' ),
	'post_status' => 'wc-on-hold',
) ) );

if ( $customer_orders ) : ?>

	<h2><?php echo apply_filters( 'woocommerce_my_account_my_orders_title', __( 'Pending orders', 'woocommerce' ) ); ?></h2>

	<table class="shop_table shop_table_responsive my_account_orders">

		<thead>
			<tr>
				<?php foreach ( $my_orders_columns as $column_id => $column_name ) : ?>
					<th class="<?php echo esc_attr( $column_id ); ?>"><span class="nobr"><?php echo esc_html( $column_name ); ?></span></th>
				<?php endforeach; ?>
			</tr>
		</thead>

		<tbody>
			<?php foreach ( $customer_orders as $customer_order ) :
				$order      = wc_get_order( $customer_order );
				$item_count = $order->get_item_count();
				?>
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
								<?php 
								if($order->get_status() == 'customprocess')
								{

									echo esc_html('Order Processing');
								}elseif($order->get_status() == 'processing')
								{
									echo esc_html('Order Dispatched'); 
								}elseif($order->get_status() == 'completed')
								{
									echo esc_html('Order Complete');
								}elseif($order->get_status() == 'on-hold')
								{
									echo esc_html('Order Received');
								}else{
									echo esc_html( wc_get_order_status_name( $order->get_status() ) );
								}
								//echo esc_html( wc_get_order_status_name( $order->get_status() ) ); ?>

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
										echo '<a href="' . esc_url( $action['url'] ) . '" class="btn-invert button ' . sanitize_html_class( $key ) . '">' . esc_html( $action['name'] ) . '</a>';
									}
								}
								?>
							<?php endif; ?>
						</td>
					<?php endforeach; ?>
				</tr>
			<?php endforeach; ?>
		</tbody>
	</table>
<?php endif; 
