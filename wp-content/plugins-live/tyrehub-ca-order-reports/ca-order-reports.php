<?php
/*
Plugin Name: CA Order Reports
Plugin URI: https://webtual.com/
Description: CA Order Reports.
Version: 1.4.1
Author: Webtual Technologies
Author URI: https://webtual.com/
*/



function ca_order_reports_menu_items()
{
 //add_submenu_page('woocommerce', 'Sold Out','Sold Out', 'activate_plugins', 'sold_out', 'sold_out_page');
    add_submenu_page('woocommerce', 'CA Order Reports','CA Order Reports', 'activate_plugins', 'ca_order_reports_csv_export_list', 'ca_order_reports_csv_export_list');
    //add_action( "load-$hook", 'add_ca_order_reports_export_options' );

}

add_action('admin_menu', 'ca_order_reports_menu_items');

if( ! class_exists( 'WP_List_Table' ) ) {
    require_once( ABSPATH . 'wp-admin/includes/class-wp-list-table.php' );
}



add_action( 'admin_enqueue_scripts', 'report_backend_scripts_styles'  );
function report_backend_scripts_styles($hook) {
    
    wp_register_script( 'webtual-ca-report-wcpdf', plugins_url( 'js/report-script.js', __FILE__ ),'',rand(),false);

    wp_enqueue_script('webtual-ca-report-wcpdf');
      $bulk_actions = array();
      $bulk_actions = array('printinvoice','printcredit');
      
      wp_enqueue_script('webtual-wcpdf');
      wp_localize_script(
        'webtual-wcpdf',
        'webtual_wcpdf_ajax',
        array(
          'ajaxurl'     => admin_url( 'admin-ajax.php' ), // URL to WordPress ajax handling page  
          'nonce'       => wp_create_nonce('generate_wpo_wcpdf'),
          'bulk_actions'    => array_keys( $bulk_actions ),
          'confirm_delete'  => __( 'Are you sure you want to delete this document? This cannot be undone.', 'woocommerce-pdf-invoices-packing-slips'),
        )
      );
    }  

class CA_Order_Reports_List_Table extends WP_List_Table {
		
		
		function __construct(){
    global $status, $page;
        parent::__construct( array(
            'singular'  => __( 'report', 'mylisttable' ),     //singular name of the listed records
            'plural'    => __( 'reports', 'mylisttable' ),   //plural name of the listed records
            'ajax'      => true        //does this table support ajax?
    ) );
    add_action( 'admin_head', array( &$this, 'admin_header' ) );   
    
    
    }

  

  function admin_header() {
    $page = ( isset($_GET['page'] ) ) ? esc_attr( $_GET['page'] ) : false;
    if( 'my_list_test' != $page )
    return;
    echo '<style type="text/css">';
    echo '.wp-list-table .column-id { width: 5%; }';
    echo '.wp-list-table .column-booktitle { width: 40%; }';
    echo '.wp-list-table .column-author { width: 35%; }';
    echo '.wp-list-table .column-isbn { width: 20%;}';
    echo '</style>';
  }

    function get_orders(){
    	
    	global $wpdb;

    	$status = 'wc-'.$_GET['status'];

      $paged = ( $_GET['paged'] ) ? $_GET['paged'] : 1;
      if($_GET['filter_action']){
        $paged=1;
      }


      $args = array(
          'post_type'=>'shop_order', // Your post type name
          'posts_per_page' => 30,        
          'paged' => $paged,
      );
    if($_GET['status']){
      $ordstatus=array($status);
    }else{
      $ordstatus=array('wc-on-hold','wc-pending','wc-completed','wc-refunded','wc-failed','wc-processing','wc-deltoinstaller','wc-customprocess');
    }
    $args['post_status']=$ordstatus;

   
    $gst_no=$_GET['gst_no'];

    if($_GET['datefrom']!='' && $_GET['dateto']!=''){
      $start_date=$_GET['datefrom'];
      $end_date=$_GET['dateto'];  
    }else{
      $start_date=date('Y-m-01');
      $end_date=date('Y-m-d');
    }

    if($start_date && $end_date){

      $meta_query[]=array(
                'key' =>'_wcpdf_invoice_date_formatted',
                // value should be array of (lower, higher) with BETWEEN
                'value' => array($start_date,$end_date),
                'compare' => 'BETWEEN',
                'type' => 'DATE'
               );
    }
    
    if($gst_no=='gst_yes'){
      $meta_query[]=array(
            'key' => '_gst_no',
            'value' => '',
            'compare' => '!='
            );
    }
    if($gst_no=='gst_no'){
      $meta_query[]=array(
            'key' => '_gst_no',
            'value' => '',
            'compare' => '='
            );
    }
      
      $args['meta_query']=array('relation' => 'AND',$meta_query);
      $the_query = new WP_Query( $args );

$args1 = array(
          'post_type'=>'shop_order', // Your post type name
          'posts_per_page' => -1
      );
    $args1['post_status']=$ordstatus;

   $args1['meta_query']=array('relation' => 'AND',$meta_query);

$catPost = get_posts($args1); //change these to suit your registered custom post type, taxonomies, post status, etc.

foreach ($catPost as $post) {
    $ids[] = $post->ID;
          $order = wc_get_order( $post->ID);        
          $order_items= $order->get_items();
        foreach ($order_items as $item_id => $item_data) {            
            $sgst = $sgst + $order->get_item_meta($item_id, 'sgst', true);
            $cgst = $cgst + $order->get_item_meta($item_id, 'cgst', true);
            $service_sgst = $service_sgst + $order->get_item_meta($item_id, 'service_sgst', true);
            $service_cgst = $service_cgst + $order->get_item_meta($item_id, 'service_cgst', true);

        }
          $gst_total=($sgst+$cgst+$service_sgst+$service_cgst);
}

//we now have an array of ids from that category, so then
$idList = implode(",", $ids); //turn the array into a comma delimited list

$meta_key = '_order_total';//set this to your custom field meta key
$allmiles = $wpdb->get_var($wpdb->prepare("
                                  SELECT sum(meta_value) 
                                  FROM $wpdb->postmeta 
                                  WHERE meta_key = %s 
                                  AND post_id in (" . $idList . ")", $meta_key));
echo '<div style="margin-left:407px; float:left; margin-bottom:-10px; font-size:15px;">Total : '.wc_price($allmiles). '</div>';

          

echo '<div style="margin-left:65px; float:left; margin-bottom:-10px; font-size:15px;">GST : '.wc_price($gst_total). '</div>';




      //echo '<pre>';
      //print_r($the_query);
    	//echo $the_query->max_num_pages;
      
        if( $the_query->have_posts())
        {      
            $orders=array();
            $key=0;
        while($the_query->have_posts() ) : $the_query->the_post();  
          $order_id = get_the_ID();  
          $order = wc_get_order($order_id);
          //echo '<pre>';
          //print_r($order->get_items());

            $order_items= $order->get_items();
          foreach ($order_items as $item_id => $item_data) {
              
              $sgst = $order->get_item_meta($item_id, 'sgst', true);
              $cgst = $order->get_item_meta($item_id, 'cgst', true);
              $service_sgst = $order->get_item_meta($item_id, 'service_sgst', true);
              $service_cgst = $order->get_item_meta($item_id, 'service_cgst', true);

          }
          $gst_total=($sgst+$cgst+$service_sgst+$service_cgst);
         $invoice= get_post_meta($order_id,'_wcpdf_invoice_number',true);
         $invoice_date= get_post_meta($order_id,'_wcpdf_invoice_date_formatted',true);
         $_order_total= get_post_meta($order_id,'_order_total',true);
         $_gst_no= get_post_meta($order_id,'_gst_no',true);
         $payment_type= get_post_meta($order_id,'_payment_method_title',true);         
         
         $credit_notes= get_post_meta($order_id,'_wcpdf_credit_notes_number',true);
         $credit_notes_date= get_post_meta($order_id,'_wcpdf_credit_note_date_formatted',true);
              if($order->get_status() == 'customprocess')
                {

                  $ord_status= esc_html('Order Processing');
                }elseif($order->get_status() == 'processing')
                {
                  $ord_status= esc_html('Order Dispatched'); 
                }elseif($order->get_status() == 'completed')
                {
                  $ord_status= esc_html('Order Complete');
                }elseif($order->get_status() == 'on-hold')
                {
                  $ord_status= esc_html('Order Received');
                }else{
                  $ord_status= esc_html( wc_get_order_status_name( $order->get_status() ) );
                }

            $orders[$key]['ID'] = $order_id;
            $orders[$key]['order_number'] = '<a href="'.admin_url( 'post.php?post='.$order_id.'&action=edit').'" target="_blank">'.$order_id.'</a>'; 
              $orders[$key]['invoice'] = '<a href="'.wp_nonce_url( admin_url( 'admin-ajax.php?action=generate_wpo_wcpdf&document_type=invoice&order_ids='.$order_id.'&my-account'), 'generate_wpo_wcpdf' ).'" target="_blank">'.$invoice.'</a>';       
      			
      			$orders[$key]['invoice_date']=$invoice_date;
      			$orders[$key]['total']=wc_price($_order_total);
      			$orders[$key]['gst_value']=wc_price($gst_total);
            $orders[$key]['gst_no']=$_gst_no;
            $orders[$key]['order_status']=$ord_status;
      			$orders[$key]['credit_note'] = '<a href="'.wp_nonce_url( admin_url( 'admin-ajax.php?action=generate_wpo_wcpdf&document_type=credit-notes&order_ids='.$order_id.'&my-account'), 'generate_wpo_wcpdf' ).'" target="_blank">'.$credit_notes.'</a>';

            $orders[$key]['credit_notes_date']=$credit_notes_date;
            $orders[$key]['payment_type']=$payment_type;
            
      			$key++;
          //Order num, date, inv no, inv date, total Total value, GST value,Credit not No
        endwhile;
        $orders['found_posts']=$the_query->found_posts;
    		return $orders;
    		die;
      }
  }

  function no_items() {
    _e( 'No orders found, dude.' );
  }
  function column_default( $item, $column_name ) {
    switch( $column_name ) { 
        case 'order_number':
        case 'invoice':
        case 'invoice_date':
        case 'total':
        case 'gst_value':
        case 'gst_no':
        case 'order_status':
        case 'credit_note':
        case 'credit_notes_date':
        case 'payment_type':        
            return $item[$column_name];
        default:
            return print_r($item,true) ; //Show the whole array for troubleshooting purposes
    }
  }
function get_sortable_columns() {
  $sortable_columns = array(
    'order_number'  => array('order_number',false),
    'invoice'  => array('invoice',true),
    'invoice_date' => array('invoice_date',false),
    'total'   => array('total',false),
    'gst_value'   => array('gst_value',false),
    'gst_no'   => array('gst_no',false),
    'order_status'   => array('order_status',false),
    'credit_note'   => array('credit_note',false),
    'credit_notes_date'   => array('credit_notes_date',false),
    'payment_type'   => array('payment_type',false)
    
  );
  return $sortable_columns;
}
function get_columns(){
        $columns = array(
            'cb'        => '<input type="checkbox" />',
            'order_number' => __( 'Order', 'mylisttable' ),
            'invoice' => __( 'Invoice (PDF)', 'mylisttable' ),
            'invoice_date'    => __( 'Invoice Date', 'mylisttable' ),
            'total'      => __( 'Total', 'mylisttable' ),
            'gst_value'      => __('GST Amount', 'mylisttable' ),
            'gst_no'      => __( 'GST No', 'mylisttable' ),
            'order_status'      => __('Status', 'mylisttable' ),
            'credit_note'      => __('Credit Note (PDF)', 'mylisttable' ),
            'credit_notes_date'      => __('Credit Note Date', 'mylisttable' ),
            'payment_type'      => __('Payment Type', 'mylisttable' )
        );
         return $columns;
}
function usort_reorder( $a, $b ) {
  // If no sort, default to title
  $orderby = ( ! empty( $_GET['orderby'] ) ) ? $_GET['orderby'] : 'order';
  // If no order, default to asc
  $order = ( ! empty($_GET['order'] ) ) ? $_GET['order'] : 'asc';
  // Determine sort order
  $result = strcmp( $a[$orderby], $b[$orderby] );
  // Send final sort direction to usort
  return ( $order === 'asc' ) ? $result : -$result;
}
function column_product($item){
  /*$actions = array(
            'edit'      => sprintf('<a href="?page=%s&action=%s&installer_id=%s">Edit</a>','installer-add-new','edit',$item['ID']),
            //'delete'    => sprintf('<a href="?page=%s&action=%s&installer_id=%s">Delete</a>',$_REQUEST['page'],'delete',$item['ID']),
        );*/
  return sprintf('%1$s %2$s', $item['order'], $this->row_actions($actions) );

}
function get_bulk_actions() {
  $actions = array(
    'invoice'    => 'Print All Invoice',
    'credit-notes'    => 'Print All Credti Note'
  );
  return $actions;
}
function process_bulk_action() {

		//Detect when a bulk action is being triggered...
		if ('invoice' === $this->current_action() ) {

     
      global $wpdb;
      $orders=$_GET['orders'];
      
         

      //we now have an array of ids from that category, so then
      $idList = implode("x",$orders);
      
      $padfurl=wp_nonce_url(admin_url( 'admin-ajax.php?action=generate_wpo_wcpdf&document_type=invoice&order_ids='.$idList.'&my-account'), 'generate_wpo_wcpdf' );
      //header('Location:'.$padfurl);
      //wp_redirect($padfurl);
   
		}

    if ('credit-notes' === $this->current_action() ) {

    }

		
	}

function column_cb($item) {
        return sprintf(
            '<input type="checkbox" name="orders[]" value="%s" />', $item['ID']
        );    
    }
function prepare_items() {
  $columns  = $this->get_columns();
  /** Process bulk action */
  $this->process_bulk_action();
  $hidden   = array();
  $sortable = $this->get_sortable_columns();
  $this->_column_headers = array( $columns, $hidden, $sortable );
  $data=$this->get_orders();
  $found_posts=$data['found_posts'];
  unset($data['found_posts']);
  if($_GET['orderby']){
   usort($data, array( &$this, 'usort_reorder' ) ); 
  }
  
  $per_page = 30;
  
  $current_page = $paged;//$this->get_pagenum();
  $total_items =$found_posts;//count($data);
  // only ncessary because we have sample data
  $found_data = array_slice($data,( ( $current_page-1 )* $per_page ), $per_page );
  $this->set_pagination_args( array(
    'total_items' => $total_items,                  //WE have to calculate the total number of items
    'per_page'    => $per_page                     //WE have to determine how many items to show on a page
  ) );
  $this->items = $found_data;
}



} //class





//add_action( 'admin_menu', 'my_add_menu_items' );
function ca_order_reports_csv_export_list(){
  if($_GET['datefrom']!='' && $_GET['dateto']!=''){
    $datefrom=$_GET['datefrom'];
    $dateto=$_GET['dateto'];  
  }else{
    $datefrom=date('Y-m-01');
    $dateto=date('Y-m-d');
  }
  
  global $CAOrderReportsListTable;
  $CAOrderReportsListTable = new CA_Order_Reports_List_Table();
  ?>
  
  <div class="wrap"><h2>CA Order Reports 
  	<a href="<?php echo plugin_dir_url( __FILE__ )?>csv_export.php?action=export&status=<?=$_GET['status'];?>&gst_no=<?=$_GET['gst_no'];?>&datefrom=<?=$_GET['datefrom'];?>&dateto=<?=$_GET['dateto'];?>" class="page-title-action">
  		CSV Export</a>
    
    </h2> 
  <!-- <a href="?page=installer-add-new" class="page-title-action">Add New Installer</a>
 	<a href="?page=installer-facilities" class="page-title-action">Manage Facilities</a> -->
 	<form method="get" id="product_list">
 	
 	<form method="post" id="product_list1" action="admin.php?page=ca_order_reports_csv_export_list">
 		<?php //wp_nonce_field( 'pp-eu-export-order-page_export', '_wpnonce-pp-eu-export-order-page_export' ); ?>
    <input type="hidden" name="page" value="ca_order_reports_csv_export_list">
    <!-- <input type="hidden" name="paged" value="<?=$_GET['paged'];?>"> -->

 	<div class="admin-url" hidden=""><?php echo admin_url('admin-ajax.php'); ?></div>
  <div class="get-nonce" hidden=""><?php echo wp_create_nonce('generate_wpo_wcpdf'); ?></div>
  
 	<div class="prd-search" style="margin-top: 20px;">
  <div class="tablenav top">
  <div class="alignleft actions">
    
    <?php //echo $_GET['status'];?>
              <select name="status" id="status">
              <option value="">All Status</option>
              <option value="on-hold" <?php if($_GET['status'] =='on-hold'){ echo 'selected="selected"';}?>>Order Received</option>
              <option value="pending" <?php if($_GET['status'] =='pending'){ echo 'selected="selected"';}?>>Payment Pending</option>
              <option value="completed" <?php if($_GET['status'] =='completed'){ echo 'selected="selected"';}?>>Order Completed</option>
              <option value="refunded" <?php if($_GET['status'] =='refunded'){ echo 'selected="selected"';}?>>Cancel & Refunded</option>
              <option value="failed" <?php if($_GET['status'] =='failed'){ echo 'selected="selected"';}?>>Failed</option>
              <option value="processing" <?php if($_GET['status'] =='processing'){ echo 'selected="selected"';}?>>Order Dispatched</option>
              <option value="deltoinstaller" <?php if($_GET['status'] =='deltoinstaller'){ echo 'selected="selected"';}?>>Order Ready to Install</option>
              <option value="customprocess" <?php if($_GET['status'] =='customprocess'){ echo 'selected="selected"';}?>>Order Processing</option>
               
              </select>
               <select name="gst_no" id="gst_no">
              <option value="">All GST Invoice</option>
              <option value="gst_yes" <?php if($_GET['gst_no'] =='gst_yes'){ echo 'selected="selected"';}?>>Invoice With GST#</option>
              <option value="gst_not" <?php if($_GET['gst_no'] =='gst_not'){ echo 'selected="selected"';}?>>Invoice Without GST#</option>
               
              </select>

              <label> Start Date</label>
            <input type="date" class="datepicker1" name="datefrom" id="datefrom" placeholder="Start Date" value="<?=$datefrom;?>">
            <label> End Date</label>
            <input type="date" class="datepicker1" name="dateto" id="dateto" placeholder="End Date" value="<?=$dateto;?>">
      <input type="submit" name="filter_action" id="post-query-submit" class="button" value="Filter">   
</div>
</div>
        <div class="message-block" style="width: 100%; float: left;"></div>
        </div>
 <?php $CAOrderReportsListTable->prepare_items(); ?>
  
   <?php
    //$productsCSVListTable->search_box( 'search', 'search_id' );
    $CAOrderReportsListTable->display(); 
    echo '</form></div>'; 
}

//include_once('function.php');
?>


