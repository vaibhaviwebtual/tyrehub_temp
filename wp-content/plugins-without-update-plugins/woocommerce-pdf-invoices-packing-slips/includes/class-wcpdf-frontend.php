<?php
namespace WPO\WC\PDF_Invoices;

use WPO\WC\PDF_Invoices\Compatibility\WC_Core as WCX;
use WPO\WC\PDF_Invoices\Compatibility\Order as WCX_Order;
use WPO\WC\PDF_Invoices\Compatibility\Product as WCX_Product;

if ( ! defined( 'ABSPATH' ) ) {
	exit; // Exit if accessed directly
}

if ( !class_exists( '\\WPO\\WC\\PDF_Invoices\\Frontend' ) ) :

class Frontend {

	function __construct()	{

		add_filter( 'woocommerce_my_account_my_orders_actions', array( $this, 'my_account_pdf_link' ), 10, 2 );
		add_filter( 'woocommerce_my_account_my_orders_actions', array( $this, 'my_account_credit_pdf_link' ), 100, 2 );
		//add_filter( 'woocommerce_api_order_response', array( $this, 'woocommerce_api_invoice_number' ), 10, 2 );
		//add_filter( 'woocommerce_api_order_response', array( $this, 'woocommerce_api_credit_notes_number' ), 10, 2 );
	}

	/**
	 * Display download link on My Account page
	 */
	public function my_account_pdf_link( $actions, $order ) {

		$invoice = wcpdf_get_invoice( $order );
		if ( $invoice && $invoice->is_enabled() ) {

			$pdf_url = wp_nonce_url( admin_url( 'admin-ajax.php?action=generate_wpo_wcpdf&document_type=invoice&order_ids=' . WCX_Order::get_id( $order ) . '&my-account'), 'generate_wpo_wcpdf' );

			/*$upload_dir = wp_upload_dir();			
			$upload_base = trailingslashit( $upload_dir['baseurl'] );
			$document_type='invoice';
			$document = wcpdf_get_document( $document_type, (array) WCX_Order::get_id($order), true );			
				// get pdf data & store
				$pdf_data = $document->get_pdf();
				$filename = $document->get_filename();
			$tmp_base = $upload_base . 'wpo_wcpdf/attachments/'.$filename;		
			$pdf_url =$tmp_base;*/
			
			// check my account button settings
			$button_setting = $invoice->get_setting('my_account_buttons', 'available');
			switch ($button_setting) {
				case 'available':
					$invoice_allowed = $invoice->exists();
					break;
				case 'always':
					$invoice_allowed = true;
					break;
				case 'never':
					$invoice_allowed = false;
					break;
				case 'custom':
					$allowed_statuses = $button_setting = $invoice->get_setting('my_account_restrict', array());
					if ( !empty( $allowed_statuses ) && in_array( WCX_Order::get_status( $order ), array_keys( $allowed_statuses ) ) ) {
						$invoice_allowed = true;
					} else {
						$invoice_allowed = false;
					}
					break;
			}

			// Check if invoice has been created already or if status allows download (filter your own array of allowed statuses)
			if ( $invoice_allowed || in_array(WCX_Order::get_status( $order ), apply_filters( 'wpo_wcpdf_myaccount_allowed_order_statuses', array() ) ) ) {
				$document_title = array_filter( $invoice->get_setting( 'title', array() ) );
				if ( !empty($document_title) ) {
					$button_text = sprintf ( __( 'Download %s ', 'woocommerce-pdf-invoices-packing-slips' ), $invoice->get_title() );
				} else {
					$button_text = __('Invoice ', 'woocommerce-pdf-invoices-packing-slips' );
				}

				$actions['invoice'] = array(
					'url'  => $pdf_url,
					'name' => apply_filters( 'wpo_wcpdf_myaccount_button_text', $button_text, $invoice )
				);
				
			}
			
		}

		return $actions;
	}


	/**
	 * Display download link on My Account page
	 */
	public function my_account_credit_pdf_link( $actions, $order ) {

		$credit_notes = wcpdf_get_credit_notes($order);
		if ($credit_notes && $credit_notes->is_enabled() ) {


			$credit_pdf_url = wp_nonce_url( admin_url('admin-ajax.php?action=generate_wpo_wcpdf&document_type=credit-notes&order_ids=' . WCX_Order::get_id( $order ) . '&my-account'), 'generate_wpo_wcpdf' );

			// check my account button settings
			$button_setting = $credit_notes->get_setting('my_account_buttons', 'available');
			switch ($button_setting) {
				case 'available':
					$credit_notes_allowed = $credit_notes->exists();
					break;
				case 'always':
					$credit_notes_allowed = true;
					break;
				case 'never':
					$credit_notes_allowed = false;
					break;
				case 'custom':
					$allowed_statuses = $button_setting = $credit_notes->get_setting('my_account_restrict', array());
					if ( !empty( $allowed_statuses ) && in_array( WCX_Order::get_status( $order ), array_keys( $allowed_statuses ) ) ) {
						$credit_notes_allowed = true;
					} else {
						$credit_notes_allowed = false;
					}
					break;
			}

			// Check if invoice has been created already or if status allows download (filter your own array of allowed statuses)
			if ( $credit_notes_allowed && in_array(WCX_Order::get_status($order), apply_filters('webtual_credit_notes_wcpdf_myaccount_allowed_order_statuses', array() ) ) ) {
				$document_title = array_filter( $credit_notes->get_setting( 'title', array() ) );
				if ( !empty($document_title) ) {
					$button_text = sprintf ( __( 'Download %s ', 'woocommerce-pdf-credit-notes-packing-slips' ), $credit_notes->get_title() );
				} else {
					$button_text = __( 'Credit Note ', 'woocommerce-pdf-credit-notes-packing-slips' );
				}
				
				$actions['credit-notes'] = array(
					'url'  => $credit_pdf_url,
					'name' => apply_filters( 'wpo_wcpdf_myaccount_button_text', $button_text, $credit_notes )
				);
			}
		}
		return $actions;
	}

	/**
	 * Add invoice number to WC REST API
	 */
	public function woocommerce_api_invoice_number ( $data, $order ) {
		$data['wpo_wcpdf_invoice_number'] = '';
		if ( $invoice = wcpdf_get_invoice( $order ) ) {
			$invoice_number = $invoice->get_number();
			if ( !empty( $invoice_number ) ) {
				$data['wpo_wcpdf_invoice_number'] = $invoice_number->get_formatted();
			}
		}

		return $data;
	}
	/**
	 * Add Credit Notes number to WC REST API
	 */
	public function woocommerce_api_credit_notes_number ( $data, $order ) {
		
		$data['wpo_wcpdf_credit_notes_number'] = '';
		if ( $credit_notes = wcpdf_get_credit_notes( $order ) ) {
			$credit_notes_number = $credit_notes->get_number();
			if ( !empty( $credit_notes_number ) ) {
				$data['wpo_wcpdf_credit_notes_number'] = $credit_notes_number->get_formatted();
			}
		}

		return $data;
	}

}

endif; // class_exists

return new Frontend();