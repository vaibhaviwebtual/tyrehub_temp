<?php
/*
Plugin Name: Tyrehub Purchase Orders Management
Plugin URI: https://webtual.com/
Description: Suppliers reports and paid functionality.
Version: 1.1.1
Author: Webtual
Author URI: https://webtual.com/
*/
if( ! class_exists( 'WP_List_Table' ) ) {
    require_once( ABSPATH . 'wp-admin/includes/class-wp-list-table.php' );
}
include_once('add-new-supplier.php');
include_once('supplier-product-price-list.php');
include_once('product-price-list.php');
include_once('supplier-products-list.php');
include_once('supplier-products-assign-list.php');
include_once('supplier-price-change-log.php');
include_once('all-products-list.php');
include_once('purchase-orders.php');


add_action('wp_enqueue_scripts', 'tyrehub_supplier_discount_script');

function tyrehub_supplier_discount_script()
{
  global $wp_query;
  if(isset($wp_query->query['new-discount']) || isset($wp_query->query['update-discount']) || isset($wp_query->query['deals-discount'])){         
    wp_enqueue_script('tyrehub_supplier_discount_script', plugins_url('/js/discount_rule.js', __FILE__), array('jquery'));

     wp_enqueue_style('tyrehub_supplier_discount_css', plugins_url('/css/discount_rules.css', __FILE__));
  }

    

    //wp_enqueue_style('tyrehub_admin_discount_style', plugins_url('/css/discount_style.css', __FILE__));
    
}



class suppliers_List_Table extends WP_List_Table {

    function get_suppliers(){
    	$s=$_POST['s'];
    	global $wpdb;
	    $sql = "SELECT * FROM th_supplier_data";
	    if($s){
	    $sql.=" where business_name LIKE '%".$s."%' OR contact_no LIKE '%".$s."%' OR user_code LIKE '%".$s."%'";	
		}	    
	    $sql.=" order by supplier_data_id desc";
	    $supplier_data = $wpdb->get_results($sql);


   		$suppliers=array();
		foreach ($supplier_data as $key => $value)
		{
			$suppliers[$key]['ID'] = $value->supplier_data_id;
			$suppliers[$key]['user_code']= $value->user_code;
      $suppliers[$key]['business_name']= $value->business_name;
			$suppliers[$key]['mobile_no']= $value->contact_no;
			$suppliers[$key]['address']= $value->address;
      if($value->auto_approve==0){
    $suppliers[$key]['auto_approve']= '<a href="'.get_admin_url().'/admin.php?page=supplier-add-new&action=edit&supplier_id='.$value->supplier_data_id.'" class="button-secondary woocommerce-save-button add-installer-btn">No</a>';
      }else{
        $suppliers[$key]['auto_approve']= '<a href="'.get_admin_url().'/admin.php?page=supplier-add-new&action=edit&supplier_id='.$value->supplier_data_id.'" class="button-primary woocommerce-save-button add-installer-btn">Yes</a> ';
      }
			if($value->visibility==0){
			$suppliers[$key]['visibility']= '<a href="'.get_admin_url().'/admin.php?page=supplier-add-new&action=edit&supplier_id='.$value->supplier_data_id.'" class="button-secondary woocommerce-save-button add-installer-btn">No</a>';	
			}else{
			$suppliers[$key]['visibility']= '<a href="'.get_admin_url().'/admin.php?page=supplier-add-new&action=edit&supplier_id='.$value->supplier_data_id.'" class="button-primary woocommerce-save-button add-installer-btn">Yes</a> ';	
			}
      $pro=supplier_product_count($value->supplier_data_id);
      $suppliers[$key]['assigned']= '<a href="'.get_admin_url().'/admin.php?page=supplier-assigned-products&supplier_id='.$value->supplier_data_id.'" class="button-primary woocommerce-save-button add-installer-btn">Products ('.$pro.')</a>';

      $suppliers[$key]['newassign']= '<a href="'.get_admin_url().'/admin.php?page=supplier-product-add&action=product&supplier_id='.$value->supplier_data_id.'" class="button-primary woocommerce-save-button add-installer-btn">Assign Products</a>';
			
		}

		return $suppliers;
		die;
    }
    function __construct(){
    global $status, $page;
        parent::__construct( array(
            'singular'  => __( 'supplier', 'mylisttable' ),     //singular name of the listed records
            'plural'    => __( 'suppliers', 'mylisttable' ),   //plural name of the listed records
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
  function no_items() {
    _e( 'No supplier found, dude.' );
  }
  function column_default( $item, $column_name ) {
    switch( $column_name ) { 
        case 'user_code':
        case 'business_name':
        case 'mobile_no':
        case 'address':
        case 'auto_approve':
        case 'visibility':
        case 'assigned':
        case 'newassign':
            return $item[ $column_name ];
        default:
            return print_r( $item, true ) ; //Show the whole array for troubleshooting purposes
    }
  }
function get_sortable_columns() {
  $sortable_columns = array(
    'user_code'  => array('user_code',false),
    'business_name'  => array('business_name',false),
    'mobile_no' => array('mobile_no',false),
    'address'   => array('address',false),
    'auto_approve' => array('auto_approve',false),
    'visibility'   => array('visibility',false),
    'assigned'   => array('assigned',false),
    'newassign'   => array('newassign',false)
    
  );
  return $sortable_columns;
}
function get_columns(){
        $columns = array(
            'cb'        => '<input type="checkbox" />',
            'user_code' => __( 'User Code', 'mylisttable' ),
            'business_name' => __( 'Business Name', 'mylisttable' ),
            'mobile_no'    => __( 'Mobile No', 'mylisttable' ),
            'address'      => __( 'Address', 'mylisttable' ),
            'auto_approve'      => __('Auto Approve', 'mylisttable' ),
            'visibility'      => __('Account Status', 'mylisttable' ),
            'assigned'      => __( 'Assigned', 'mylisttable' ),
            'newassign'      => __( 'New Assign', 'mylisttable' )
        );
         return $columns;
    }
function usort_reorder( $a, $b ) {
  // If no sort, default to title
  $orderby = ( ! empty( $_GET['orderby'] ) ) ? $_GET['orderby'] : 'business_name';
  // If no order, default to asc
  $order = ( ! empty($_GET['order'] ) ) ? $_GET['order'] : 'asc';
  // Determine sort order
  $result = strcmp( $a[$orderby], $b[$orderby] );
  // Send final sort direction to usort
  return ( $order === 'asc' ) ? $result : -$result;
}
function column_user_code($item){
  $actions = array(
            'edit'      => sprintf('<a href="?page=%s&action=%s&supplier_id=%s">Edit</a>','supplier-add-new','edit',$item['ID']),
            'delete'    => sprintf('<a href="?page=%s&action=%s&supplier_id=%s">Delete</a>',$_REQUEST['page'],'delete',$item['ID']),
        );
  return sprintf('%1$s %2$s', $item['user_code'], $this->row_actions($actions) );
}
function get_bulk_actions() {
  $actions = array(
    'delete'    => 'Delete'
  );
  return $actions;
}


  function process_bulk_action() {
    //Detect when a bulk action is being triggered...
    global $wpdb;
    if($_GET['action']=='delete' && $_GET['supplier_id']!=''){
      
        $id=$_GET['supplier_id'];
        $SQL="SELECT * FROM `th_supplier_data` WHERE supplier_data_id=$id";
          $results=$wpdb->get_row($SQL);
          self::supplier_delete_by_admin($results->user_id);
         $wpdb->update( 
            'th_supplier_products', 
            array( 
              'supplier_id' =>0,
              'status' =>1
            ), 
            array('supplier_id'=>$results->supplier_data_id), 
            array( 
              '%d',
              '%d'
            ), 
            array('%d') 
          );
    }

    if ('delete' === $this->current_action()) {      
      // In our file that handles the request, verify the nonce.
      $nonce = esc_attr( $_REQUEST['_wpnonce']);
      $delete_ids = esc_sql( $_POST['supplier']);
      global $wpdb;
      // loop over the array of record IDs and delete them
      foreach ( $delete_ids as $id ) {
          $SQL="SELECT * FROM `th_supplier_data` WHERE supplier_data_id=$id";
          $results=$wpdb->get_row($SQL);
          self::supplier_delete_by_admin($results->user_id);
         $wpdb->update( 
            'th_supplier_products', 
            array( 
              'supplier_id' =>0,
              'status' =>1
            ), 
            array('supplier_id'=>$results->supplier_data_id), 
            array( 
              '%d',
              '%d'
            ), 
            array('%d') 
          );
    }
  }
   //add_action( 'admin_notices', 'my_error_notice' );
  }

 function supplier_delete_by_admin($user_id){
  global $wpdb;
  
  wp_delete_user($user_id);
  $wpdb->delete('th_supplier_data', array('user_id' =>$user_id));


}
function my_error_notice() {
    ?>
    <div class="error notice-success">
        <p><?php _e( 'Supplier deleted has been success!', 'my_plugin_textdomain'); ?></p>
    </div>
    <?php
}


function column_cb($item) {
        return sprintf(
            '<input type="checkbox" name="supplier[]" value="%s" />', $item['ID']
        );    
    }
function prepare_items() {
  $columns  = $this->get_columns();
  $hidden   = array();
  $sortable = $this->get_sortable_columns();
  $this->_column_headers = array( $columns, $hidden, $sortable );
  $this->process_bulk_action();
  $data=$this->get_suppliers();
 
  usort($data, array( &$this, 'usort_reorder' ) );
  
  $per_page = 30;
  $current_page = $this->get_pagenum();
  $total_items = count($data);
  // only ncessary because we have sample data
  $found_data = array_slice($data,( ( $current_page-1 )* $per_page ), $per_page );
  $this->set_pagination_args( array(
    'total_items' => $total_items,                  //WE have to calculate the total number of items
    'per_page'    => $per_page                     //WE have to determine how many items to show on a page
  ) );
  $this->items = $found_data;
}
} //class

add_action('admin_menu', 'supplier_menu');

function supplier_menu()
{
	$hook = add_menu_page('Suppliers Management', 'Suppliers', 'manage_options', 'supplier-manage', 'supplier_list_page', 'dashicons-admin-tools',62 );

	add_submenu_page('supplier-manage','Suppliers Management Page', 'Add New', 'manage_options', 'supplier-add-new', 'supplier_from', 'dashicons-media-text',63 );
  add_submenu_page('supplier-manage','Suppliers Product Price Change', 'Suppliers Product Price Change', 'manage_options', 'supplier-product-price-change-list', 'supplier_product_pending_price_change_list', 'dashicons-media-text',62 );

  add_submenu_page('supplier-product-price-change-list','Product Price List', 'Product Price List', 'manage_options', 'product-price-list', 'product_price_list', 'dashicons-media-text',62 );

  //add_submenu_page('supplier-manage','Price Approve Pending', 'Price Approve Pending', 'manage_options', 'supplier-product-price-pending-list', 'supplier_product_price_pending_list', 'dashicons-media-text',62 );

  add_submenu_page('supplier-product-price-change-list','Price Change Edit', 'Price Change Edit', 'manage_options', 'supplier-per-change', 'supplier_product_price_change_approve', 'dashicons-media-text',62 );

add_submenu_page('supplier-product-price-change-list','Poducts  List', 'Poducts  List', 'manage_options', 'supplier-product-add', 'supplier_products_list', 'dashicons-media-text',62 );

add_submenu_page('supplier-manage','Poducts  List', 'Poducts  List', 'manage_options', 'products-list', 'products_list', 'dashicons-media-text',62 );

add_submenu_page('supplier-product-price-change-list','Poducts  Assigned List', 'Poducts  Assigned List', 'manage_options', 'supplier-assigned-products', 'supplier_assigned_products_list', 'dashicons-media-text',62 );

add_submenu_page('supplier-manage', 'Change Price Log','Change Price Log', 'activate_plugins', 'change_price_log', 'change_price_log');
add_submenu_page('supplier-manage', 'Sync Price Log','Sync Price Log', 'activate_plugins', 'supplier-change-price-log', 'supplier_change_price_log');

add_submenu_page('supplier-manage', 'Purchase Orders','Purchase Orders', 'activate_plugins', 'purchase-orders', 'purchase_orders_fun');
/*add_submenu_page('supplier-product-price-change-list','Products Assign', 'Products Assign', 'manage_options', 'supplier-product-assign', 'supplier_product_assign', 'dashicons-media-text',62 );*/
  


	 //add_action( "load-$hook", 'add_options_supplier' );


}

//add_action('admin_menu', 'add_custom_link_into_appearnace_menu');
function add_custom_link_into_appearnace_menu() {
    global $submenu;
    $permalink = admin_url().'admin.php?page=supplier-product-price-change-list&status=pending';
    $submenu['supplier-manage'][] = array( 'Price Approve Pending', 'manage_options', $permalink );

    /*$permalink1 = admin_url().'admin.php?page=supplier-product-price-change-list&status=pending';
    $submenu['supplier-manage'][] = array( 'Price Change Log', 'manage_options', $permalink1);*/
}

/*function supplier_product_price_pending_list(){
  global $pending;
  $pending=1;
  supplier_product_price_change_list();
}*/

add_action('admin_enqueue_scripts', 'supplier_admin_style');

function supplier_admin_style()
{
    
     wp_enqueue_style('supplier_admin_stylea', plugins_url('/style.css', __FILE__));

     wp_enqueue_script('supplier_admin_scripta', plugins_url('/manage-supplier.js', __FILE__), array('jquery'));
}



function add_options_supplier() {
  global $suppliersListTable,$suppliersProductListTable;
  $option = 'per_page';
  $args = array(
         'label' => 'Suppliers',
         'default' => 30,
         'option' => 'suppliers_per_page'
         );
  add_screen_option( $option, $args );
  $suppliersListTable = new Suppliers_List_Table();
  
}


//add_action( 'admin_menu', 'my_add_menu_items' );
function supplier_list_page(){
  $suppliersListTable = new Suppliers_List_Table();
  ?>
  <div class="wrap"><h2>Suppliers Management 
 	<div class="admin-url" hidden=""><?php echo admin_url('admin-ajax.php'); ?></div></h2> <br>
  <a href="?page=supplier-add-new" class="page-title-action">Add New Supplier</a>
  <a href="?page=supplier-product-price-change-list" class="page-title-action">All Price Request</a>
  <a href="?page=supplier-product-price-change-list&status=2" class="page-title-action">All Price Pending Request</a>
  <a href="?page=change_price_log" class="page-title-action">Price Changes Logs</a>
 <?php $suppliersListTable->prepare_items(); 
?>
  <form method="post">
    <input type="hidden" name="page" value="supplier-manage">
    <?php
    $suppliersListTable->search_box( 'search', 'search_id' );
    $suppliersListTable->display(); 
    echo '</form></div>'; 
}


function supplier_from(){
	 $action=$_GET['action'];
	 if($action=='edit'){
	  include('update-supplier-data.php');
	   supplier_update();
	 }else{
	  	  tim_add_new_supplier();	
	 }	
}

