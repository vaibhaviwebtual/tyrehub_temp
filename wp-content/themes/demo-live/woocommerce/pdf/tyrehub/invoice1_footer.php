</tbody>
</table>

			<?php do_action( 'wpo_wcpdf_after_order_details', $this->type, $this->order ); ?>
		
			<?php if ( $this->get_footer() ): ?>
			<div id="footer">

			 	<div class="name" style="width: 49%; float: left;">SUBJECT TO AHMEDABAD JURISDICTION<br>This is a Computer Generated Service Voucher</div>
				<div class="shop-phone" style="width: 49%; float: right; text-align: right;"><p style="margin:0">www.Tyrehub.com</p><p style="margin:0"> Toll free: 1-800-233-5551</p></div>
			</div><!-- #letter-footer -->
			<?php endif; ?>

			</div>
<?php