
<?php do_action( 'wpo_wcpdf_after_document_label', $this->type, $this->order ); ?>
			<?php
				$user = $order->get_user();

				$user_idd = $user->ID;
				$user_role = $user->roles[0];
				global $wpdb;
				?>
				<table class="order-data-addresses">
				<tr>
					<td class="address billing-address" style="width: 50%;">
						<p style="font-size: 15px"> Customer </p>	
						<?php 
						$gst_no = get_post_meta( $order_id, '_gst_no', true );
								$cmp_name = get_post_meta( $order_id, '_cmp_name', true );
								$cmp_add = get_post_meta( $order_id, '_cmp_add', true );
						if($user_role == "Installer"){ 
									$installer = "SELECT * FROM th_installer_data WHERE user_id = '$user_idd'";
							        $row = $wpdb->get_results($installer);
							        foreach ($row as $key => $value)
							        {
							        	$gst_no = $value->gst_no;
							        	$cmp_name = $value->company_name;
							        	$cmp_add = $value->company_add;
							        	$business_name = $value->business_name;
							        }


							?>
							<p><strong><?php echo $business_name; ?></strong></p>
						<?php
						}
						?>
						<!-- <h3><?php _e( 'Billing Address:', 'woocommerce-pdf-invoices-packing-slips' ); ?></h3> -->
						<?php do_action( 'wpo_wcpdf_before_billing_address', $this->type, $this->order ); ?>
						<?php $this->billing_address(); ?>
						<?php do_action( 'wpo_wcpdf_after_billing_address', $this->type, $this->order ); ?>
						<?php //if ( isset($this->settings['display_email']) ) { ?>
						<div class="billing-email">
							<?php 
								$order = wc_get_order( $order_id );
								$order_data = $order->get_data();
								$order_billing_email = $order_data['billing']['email'];
								
								if($order_billing_email != 'sales@tyrehub.com' && $order_billing_email != 'admin@tyrehub.com' && $order_billing_email != 'tyrehub-admin2@test.com' && $order_billing_email != 'tyrehub-admin3@test.com' && $order_billing_email != 'tyrehub-admin@test.com')
								{						
									echo $this->billing_email();
								}
							?>				
						</div>
						<?php // } ?>
						<?php //if ( isset($this->settings['display_phone']) ) { ?>
						<div class="billing-phone"><div> Mobile number : <?php echo  $this->billing_phone(); ?> </div></div>
						<?php //} ?>
						<div class="billing-phone" style="width: 100%;"> 
							<?php  
								
								if($cmp_name != '')
								{
									echo '<div> Company Name : '.$cmp_name.'</div>';
								}  
								if($cmp_add != '')
								{
									echo '<div> Company Address : '.$cmp_add.'</div>';
								}												
							?>
						</div>
					</td>
					<?php  ?>
					<td class="order-data">
						<table>
							<?php do_action( 'wpo_wcpdf_before_order_data', $this->type, $this->order ); ?>
							<?php if ( isset($this->settings['display_number']) ) { ?>
							<tr class="invoice-number">
								<th><?php _e( 'Invoice Number:', 'woocommerce-pdf-invoices-packing-slips' ); ?></th>
								<td><?php $this->invoice_number(); ?></td>
							</tr>
							<?php } ?>
							<?php if ( isset($this->settings['display_date']) ) { ?>
							<tr class="invoice-date">
								<th><?php _e( 'Invoice Date:', 'woocommerce-pdf-invoices-packing-slips' ); ?></th>
								<td><?php $this->invoice_date(); ?></td>
							</tr>
							<?php } ?>
							<tr class="order-number">
								<th><?php _e( 'Order Number:', 'woocommerce-pdf-invoices-packing-slips' ); ?></th>
								<td><?php $this->order_number(); ?></td>
							</tr>
							<tr class="order-date">
								<th><?php _e( 'Order Date:', 'woocommerce-pdf-invoices-packing-slips' ); ?></th>
								<td><?php $this->order_date(); ?></td>
							</tr>
							<?php do_action( 'wpo_wcpdf_after_order_data', $this->type, $this->order ); ?>
						</table>			
					</td>
				</tr>
			</table>
			<?php do_action( 'wpo_wcpdf_before_order_details', $this->type, $this->order ); ?>
			<table class="order-details" style="font-size:12px; border: 1px dashed #ccc;">
				<tbody>