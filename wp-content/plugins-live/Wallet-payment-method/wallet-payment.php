<?php
/*
Plugin Name: Wallet Payment Gateway
Description: Wallet payment gateway 
Author: Webtual
Author URI: https://webtual.com/
*/

if ( ! defined( 'ABSPATH' ) ) {
    exit; // Exit if accessed directly
}

/**
 * Custom Payment Gateway.
 *
 * Provides a Custom Payment Gateway, mainly for testing purposes.
 */
add_action('plugins_loaded', 'init_wallet_gateway_class');
function init_wallet_gateway_class(){

    class WC_Gateway_Wallet extends WC_Payment_Gateway {

        public $domain;

        /**
         * Constructor for the gateway.
         */
        public function __construct() {

            $this->domain = 'wallet_payment';

            $this->id                 = 'wallet';
            $this->icon               = apply_filters('woocommerce_wallet_gateway_icon', '');
            $this->has_fields         = false;
            $this->method_title       = __( 'Wallet Payment', $this->domain );
            $this->method_description = __( 'Allows payments with wallet gateway.', $this->domain );

            // Load the settings.
            $this->init_wallet_form_fields();
            $this->init_settings();

            // Define user set variables
            $this->title        = $this->get_option( 'title' );
            $this->description  = $this->get_option( 'description' );
            $this->instructions = $this->get_option( 'instructions', $this->description );
            $this->order_status = $this->get_option( 'order_status', 'completed' );

            // Actions
            add_action( 'woocommerce_update_options_payment_gateways_' . $this->id, array( $this, 'process_admin_options' ) );
            add_action( 'woocommerce_thankyou_wallet', array( $this, 'wallet_thankyou_page' ) );

            // Customer Emails
            add_action( 'woocommerce_email_before_order_table', array( $this, 'wallet_email_instructions' ), 10, 3 );
        }

        /**
         * Initialise Gateway Settings Form Fields.
         */
        public function init_wallet_form_fields() {

            $this->form_fields = array(
                'enabled' => array(
                    'title'   => __( 'Enable/Disable', $this->domain ),
                    'type'    => 'checkbox',
                    'label'   => __( 'Enable Wallet Payment', $this->domain ),
                    'default' => 'yes'
                ),
                'title' => array(
                    'title'       => __( 'Title', $this->domain ),
                    'type'        => 'text',
                    'description' => __( 'This controls the title which the user sees during checkout.', $this->domain ),
                    'default'     => __( 'Wallet Payment', $this->domain ),
                    'desc_tip'    => true,
                ),
                'order_status' => array(
                    'title'       => __( 'Order Status', $this->domain ),
                    'type'        => 'select',
                    'class'       => 'wc-enhanced-select',
                    'description' => __( 'Choose whether status you wish after checkout.', $this->domain ),
                    'default'     => 'wc-completed',
                    'desc_tip'    => true,
                    'options'     => wc_get_order_statuses()
                ),
                'description' => array(
                    'title'       => __( 'Description', $this->domain ),
                    'type'        => 'textarea',
                    'description' => __( 'Payment method description that the customer will see on your checkout.', $this->domain ),
                    'default'     => __('Payment Information', $this->domain),
                    'desc_tip'    => true,
                ),
                'instructions' => array(
                    'title'       => __( 'Instructions', $this->domain ),
                    'type'        => 'textarea',
                    'description' => __( 'Instructions that will be added to the thank you page and emails.', $this->domain ),
                    'default'     => '',
                    'desc_tip'    => true,
                ),
            );
        }

        /**
         * Output for the order received page.
         */
        public function wallet_thankyou_page() {
            if ( $this->instructions )
                echo wpautop( wptexturize( $this->instructions ) );
        }

        /**
         * Add content to the WC emails.
         *
         * @access public
         * @param WC_Order $order
         * @param bool $sent_to_admin
         * @param bool $plain_text
         */
        public function wallet_email_instructions( $order, $sent_to_admin, $plain_text = false ) {
            if ( $this->instructions && ! $sent_to_admin && $this->id === $order->payment_method && $order->has_status( 'on-hold' ) ) {
                echo wpautop( wptexturize( $this->instructions ) ) . PHP_EOL;
            }
        }

        /*public function wallet_payment_fields(){

            if ( $description = $this->get_description() ) {
                echo wpautop( wptexturize( $description ) );
            }

            ?>
            <div id="custom_input">
               
                <p class="form-row form-row-wide">
                    <label for="transaction" class=""><?php _e('Transaction ID', $this->domain); ?></label>
                    <input type="text" class="" name="transaction" id="transaction" placeholder="" value="" style="min-width: 400px;">
                </p>
            </div>
            <?php
        }*/

        /**
         * Process the payment and return the result.
         *
         * @param int $order_id
         * @return array
         */
        public function process_payment( $order_id ) {

            $order = wc_get_order( $order_id );

            $status = 'wc-' === substr( $this->order_status, 0, 3 ) ? substr( $this->order_status, 3 ) : $this->order_status;

            // Set order status
            $order->update_status( $status, __( 'Checkout with wallet payment. ', $this->domain ) );

            // Reduce stock levels
            $order->reduce_order_stock();

            // Remove cart
            WC()->cart->empty_cart();

            // Return thankyou redirect
            return array(
                'result'    => 'success',
                'redirect'  => $this->get_return_url( $order )
            );
        }
    }
}

add_filter( 'woocommerce_payment_gateways', 'add_wallet_gateway_class' );
function add_wallet_gateway_class( $methods ) {
    $methods[] = 'WC_Gateway_Wallet'; 
    return $methods;
}



/**
 * Update the order meta with field value
 */
add_action( 'woocommerce_checkout_update_order_meta', 'wallet_payment_update_order_meta' );
function wallet_payment_update_order_meta( $order_id ) {

    if($_POST['payment_method'] != 'wallet')
        return;

     /*echo "<pre>";
     print_r($_POST);
     echo "</pre>";
     exit();*/

    update_post_meta( $order_id, 'mobile', $_POST['mobile'] );
    update_post_meta( $order_id, 'transaction', $_POST['transaction'] );
}

/**
 * Display field value on the order edit page
 */
add_action( 'woocommerce_admin_order_data_after_billing_address', 'wallet_checkout_field_display_admin_order_meta', 10, 1 );
function wallet_checkout_field_display_admin_order_meta($order){
    $method = get_post_meta( $order->get_id(), '_payment_method', true );
    if($method != 'wallet')
        return;
    $mobile = get_post_meta( $order->get_id(), 'mobile', true );
    $transaction = get_post_meta( $order->get_id(), 'transaction', true );

    echo '<p><strong>'.__( 'Mobile Number' ).':</strong> ' . $mobile . '</p>';
    echo '<p><strong>'.__( 'Transaction ID').':</strong> ' . $transaction . '</p>';
}