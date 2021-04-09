<?php
/**
 * Orders
 *
 * Shows orders on the account page.
 *
 * This template can be overridden by copying it to yourtheme/woocommerce/myaccount/orders.php.
 *
 * HOWEVER, on occasion WooCommerce will need to update template files and you
 * (the theme developer) will need to copy the new files to your theme to
 * maintain compatibility. We try to do this as little as possible, but it does
 * happen. When this occurs the version of the template file will be bumped and
 * the readme will list any important changes.
 *
 * @see 	https://docs.woocommerce.com/document/template-structure/
 * @author  WooThemes
 * @package WooCommerce/Templates
 * @version 3.2.0
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

global $wpdb;
$SQL="SELECT * FROM th_installer_data WHERE is_franchise='yes' AND user_id='".get_current_user_id()."'";
$franchise=$wpdb->get_row($SQL);

do_action( 'woocommerce_before_account_orders', $has_orders ); ?>

<?php if ( $has_orders ) : ?>
	<!-- <table class="woocommerce-orders-table woocommerce-MyAccount-orders shop_table shop_table_responsive my_account_orders account-orders-table table table-bordered"> -->
	<div class="my_order_list">
		<table class="woocommerce-orders-table woocommerce-MyAccount-orders shop_table shop_table_responsive my_account_orders account-orders-table">
			<thead>
				<tr>
					<?php foreach ( wc_get_account_orders_columns() as $column_id => $column_name ) : ?>
						<th class="woocommerce-orders-table__header woocommerce-orders-table__header-<?php echo esc_attr( $column_id ); ?>"><span class="nobr"><?php echo esc_html( $column_name ); ?></span></th>
					<?php endforeach; ?>
				</tr>
			</thead>

			<tbody>
				<?php foreach ( $customer_orders->orders as $customer_order ) :
					$order      = wc_get_order( $customer_order );
					$item_count = $order->get_item_count();
					if($order->get_status() == 'completed'){
					?>
					<tr class="woocommerce-orders-table__row woocommerce-orders-table__row--status-<?php echo esc_attr( $order->get_status() ); ?> order">
						<?php foreach ( wc_get_account_orders_columns() as $column_id => $column_name ) : ?>
							<td class="woocommerce-orders-table__cell woocommerce-orders-table__cell-<?php echo esc_attr( $column_id ); ?>" data-title="<?php echo esc_attr( $column_name ); ?>">
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
									//echo $order->get_status();
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

									/*if($order->get_status()=='refunded'){
									echo esc_html( wc_get_order_status_name( $order->get_status() ) );	
									}else{
										echo esc_html('Order '.ucwords($order->get_status()));
									}
									echo esc_html( wc_get_order_status_name( $order->get_status() ) );*/ ?>

								<?php elseif ( 'order-total' === $column_id ) : ?>
									<?php
									/* translators: 1: formatted order total 2: total order items */
									printf( _n( '%1$s for %2$s item', '%1$s for %2$s items', $item_count, 'woocommerce' ), $order->get_formatted_order_total(), $item_count );
									?>
								<?php elseif ( 'order-actions' === $column_id ) : ?>
									<?php
									$actions = wc_get_account_orders_actions( $order );
									$order_status = wc_get_order_status_name( $order->get_status());
									if($action['name'] = "Invoice" && $order_status == 'Failed'){ array_pop($actions); } else {  }
									if ( ! empty( $actions ) ) {
										foreach ( $actions as $key => $action ) {
											
											/*echo '<a href="' . esc_url( $action['url'] ) . '" class="woocommerce-button button btn-invert ' . sanitize_html_class( $key ) . '">' . esc_html($action['name']).'</a>';*/
											if(empty($franchise)){
											
											echo '<a href="' . esc_url( $action['url'] ) . '" class="woocommerce-button button btn-invert ' . sanitize_html_class( $key ) . '">' . esc_html( $action['name'] ) . '</a>';
											}else{
												echo '<a href="'.site_url().'/pdf-view/?document_type=invoice&&order_ids='.$order->get_order_number().'" class="woocommerce-button button btn-invert ' . sanitize_html_class( $key ) . '">' . esc_html( $action['name'] ) . '</a>';
											}
											
										}

									}
									?>
								<?php endif; ?>
							</td>
						<?php  endforeach; ?>
					</tr>
				<?php } endforeach; ?>
			</tbody>
		</table>
	</div>
	

	<?php do_action( 'woocommerce_before_account_orders_pagination' ); ?>

	<?php if ( 1 < $customer_orders->max_num_pages ) : ?>
		<div class="woocommerce-pagination woocommerce-pagination--without-numbers woocommerce-Pagination">
			<?php if ( 1 !== $current_page ) : ?>
				<a class="woocommerce-button woocommerce-button--previous woocommerce-Button woocommerce-Button--previous button btn-invert" href="<?php echo esc_url( wc_get_endpoint_url( 'orders', $current_page - 1 ) ); ?>"><?php _e( 'Previous', 'woocommerce' ); ?></a>
			<?php endif; ?>

			<?php if ( intval( $customer_orders->max_num_pages ) !== $current_page ) : ?>
				<a class="woocommerce-button woocommerce-button--next woocommerce-Button woocommerce-Button--next button btn-invert" href="<?php echo esc_url( wc_get_endpoint_url( 'orders', $current_page + 1 ) ); ?>"><?php _e( 'Next', 'woocommerce' ); ?></a>
			<?php endif; ?>
		</div>
	<?php endif; ?>

<?php else : ?>
	<div class="woocommerce-message woocommerce-message--info woocommerce-Message woocommerce-Message--info woocommerce-info">
		<?php 
		if( current_user_can('Installer'))
		{
			$account_page = get_site_url().'/my-account/purchase';
			?>
			<a class="woocommerce-Button button btn-invert" href="<?php echo $account_page; ?>">
				<?php _e( 'Go shop', 'woocommerce' ) ?>
			</a>
		<?php
		}
		else
		{
		?>
			<a class="woocommerce-Button button btn-invert" href="<?php echo esc_url( apply_filters( 'woocommerce_return_to_shop_redirect', wc_get_page_permalink( 'shop' ) ) ); ?>">
			<?php _e( 'Go shop', 'woocommerce' ) ?>
		</a>
		<?php
		}
		?>
		<?php _e( 'No order has been made yet.', 'woocommerce' ); ?>
	</div>
<?php endif; ?>

<?php do_action( 'woocommerce_after_account_orders', $has_orders ); ?>
