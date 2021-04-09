<?php
global $wpdb, $woocommerce;
$service_price_id = $_GET['service_price_id'];

 $delete_service = $wpdb->get_results("DELETE from th_installer_service_price where service_price_id = '$service_price_id'");

 wp_redirect('?page=installer-fitment-charges');
?>