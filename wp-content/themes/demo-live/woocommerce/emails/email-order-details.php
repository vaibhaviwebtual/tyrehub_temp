<?php
/**
 * Order details table shown in emails.
 *
 * This template can be overridden by copying it to yourtheme/woocommerce/emails/email-order-details.php.
 *
 * HOWEVER, on occasion WooCommerce will need to update template files and you
 * (the theme developer) will need to copy the new files to your theme to
 * maintain compatibility. We try to do this as little as possible, but it does
 * happen. When this occurs the version of the template file will be bumped and
 * the readme will list any important changes.
 *
 * @see https://docs.woocommerce.com/document/template-structure/
 * @package WooCommerce/Templates/Emails
 * @version 3.3.1
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

$text_align = is_rtl() ? 'right' : 'left';

do_action( 'woocommerce_email_before_order_table', $order, $sent_to_admin, $plain_text, $email ); ?>

<h2>Order Details<br></h2>
<div>Order # <?php echo $order->get_order_number(); ?></div>
<div>Placed on <?php echo wc_format_datetime( $order->get_date_created() );?></div>
	<?php
	if ( $sent_to_admin ) {
		$before = '<a class="link" href="' . esc_url( $order->get_edit_order_url() ) . '">';
		$after  = '</a>';
	} else {
		$before = '';
		$after  = '';
	}
	/* translators: %s: Order ID. */
	// echo wp_kses_post( $before . sprintf( __( 'Order Details#%s', 'woocommerce' ) . $after . ' (<time datetime="%s">%s</time>)', $order->get_order_number(), $order->get_date_created()->format( 'c' ), wc_format_datetime( $order->get_date_created() ) ) );

	?>

<div style="margin-bottom: 40px;margin-top: 10px;">
	<?php
		echo wc_get_email_order_items( $order, array( // WPCS: XSS ok.
			'show_sku'      => $sent_to_admin,
			'show_image'    => false,
			'image_size'    => array( 32, 32 ),
			'plain_text'    => $plain_text,
			'sent_to_admin' => $sent_to_admin,
		) );
	?>

	<table class="td" cellspacing="0" cellpadding="6" style="width: 100%; font-family: 'Helvetica Neue', Helvetica, Roboto, Arial, sans-serif;" border="1" class="email-item">
		<thead>
			<!-- <tr>
				<th class="td" scope="col" style="text-align:<?php echo esc_attr( $text_align ); ?>;"><?php esc_html_e( 'Product', 'woocommerce' ); ?></th>
				<th class="td" scope="col" style="text-align:<?php echo esc_attr( $text_align ); ?>;"><?php esc_html_e( 'Quantity', 'woocommerce' ); ?></th>
				<th class="td" scope="col" style="text-align:<?php echo esc_attr( $text_align ); ?>;"><?php esc_html_e( 'Price', 'woocommerce' ); ?></th>
			</tr> -->
		</thead>
		<tbody>
			
		</tbody>
		<tfoot>
			<?php
			$totals = $order->get_order_item_totals();
			$customer = new WC_Customer( $order_id );
			$user = $order->get_user();
			$user_role = $user->roles[0];
			$user_id = $order->get_user_id();
		//	var_dump($customer);
			if($user_role != 'Installer')
			{
				if ( $totals )
				{
					$i = 0;
					foreach ( $totals as $total ) {
						if($total['label'] == 'Total:'){
							$Subtotal = $total['value'];
						}
					}
				}
			}
			

			if ( $totals ) {
				$i = 0;
				foreach ( $totals as $total )
				{
					
					if($total['label']!= 'Service Charges:' && $total['label']!= 'Shipping:')
					{
					 
					$i++;
					?>
					<tr>
						<th class="td" scope="row" colspan="2" style="text-align:<?php echo esc_attr( $text_align ); ?>; <?php echo ( 1 === $i ) ? 'border-top-width: 4px;' : ''; ?>"><?php echo wp_kses_post( $total['label'] ); ?>
							
						</th>
						
						<td class="td" style="text-align:<?php echo esc_attr( $text_align ); ?>; <?php echo ( 1 === $i ) ? 'border-top-width: 4px;' : ''; ?>"><?php 
							if($total['label'] == 'Subtotal:' && $user_role != 'Installer'){
								echo $Subtotal; }else{ echo wp_kses_post( $total['value'] ); } ?>
									
						</td>
					</tr>
					<?php
				}
				}
			}
			if ( $order->get_customer_note() ) {
				?>
				<tr>
					<th class="td" scope="row" colspan="2" style="text-align:<?php echo esc_attr( $text_align ); ?>;"><?php esc_html_e( 'Note:', 'woocommerce' ); ?></th>
					<td class="td" style="text-align:<?php echo esc_attr( $text_align ); ?>;"><?php echo wp_kses_post( wptexturize( $order->get_customer_note() ) ); ?></td>
				</tr>
				<?php
			}
			?>
		</tfoot>
	</table>

	<p>
		<div style="font-size: 16px; padding-top: 5px;">
			*It is customer's responsibility to call tyre company and register guarantee and warranty, for more details visit: <a href="<?php echo get_site_url().'/guarantee-and-warranty/'; ?>" style="color: #474494;"> <?php echo get_site_url().'/guarantee-and-warranty/'; ?></a>
		</div>
	</p>
	
</div>

<?php do_action( 'woocommerce_email_after_order_table', $order, $sent_to_admin, $plain_text, $email ); ?>
