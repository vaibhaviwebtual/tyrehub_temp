<?php
/*
Plugin Name: Tyrehub Manage Services
Plugin URI: https://webtual.com/
Description: Services.
Version: 1.1.1
Author: Webtual
Author URI: https://webtual.com/
*/
if( ! class_exists( 'WP_List_Table' ) ) {
    require_once( ABSPATH . 'wp-admin/includes/class-wp-list-table.php' );
}
include_once('add-new-services.php');
//include_once('business-history.php');


class services_List_Table extends WP_List_Table {

    function get_services(){
    	$s=$_POST['s'];
    	global $wpdb;
	    $sql = "SELECT * FROM th_service_data";
	    if($s){
	     $sql.=" where service_name LIKE '%".$s."%'";	
		  }	    
	    $sql.=" order by service_data_id desc";
	    $services_data = $wpdb->get_results($sql);


   		$services=array();
		foreach ($services_data as $key => $value)
		{
			$services[$key]['ID'] = $value->service_data_id;
      $services[$key]['service_name']= $value->service_name;
			$services[$key]['as_flag']= $value->as_flag;
			$services[$key]['icon']= $value->icon;
      //$services[$key]['status']= $value->status;
      if($value->as_flag==0){
      $services[$key]['as_flag']= '<a href="'.get_admin_url().'/admin.php?page=services-add-new&action=edit&services_id='.$value->service_data_id.'" class="button-secondary woocommerce-save-button add-installer-btn">No</a>';
      }else{
        $services[$key]['as_flag']= '<a href="'.get_admin_url().'/admin.php?page=services-add-new&action=edit&services_id='.$value->service_data_id.'" class="button-primary woocommerce-save-button add-installer-btn">Yes</a> '; 
      }
      if($value->service_onoff_on_listing==0){
      $services[$key]['service_onoff_on_listing']= '<a href="'.get_admin_url().'/admin.php?page=services-add-new&action=edit&services_id='.$value->service_data_id.'" class="button-secondary woocommerce-save-button add-installer-btn">No</a>';
      }else{
        $services[$key]['service_onoff_on_listing']= '<a href="'.get_admin_url().'/admin.php?page=services-add-new&action=edit&services_id='.$value->service_data_id.'" class="button-primary woocommerce-save-button add-installer-btn">Yes</a> '; 
      }
      
			if($value->status==0){
			$services[$key]['status']= '<a href="'.get_admin_url().'/admin.php?page=services-add-new&action=edit&services_id='.$value->service_data_id.'" class="button-secondary woocommerce-save-button add-installer-btn">No</a>';	
			}else{
			$services[$key]['status']= '<a href="'.get_admin_url().'/admin.php?page=services-add-new&action=edit&services_id='.$value->service_data_id.'" class="button-primary woocommerce-save-button add-installer-btn">Yes</a> ';	
			}

     
		}

		return $services;
		die;
    }
    function __construct(){
    global $status, $page;
        parent::__construct( array(
            'singular'  => __( 'service', 'mylisttable' ),     //singular name of the listed records
            'plural'    => __( 'services', 'mylisttable' ),   //plural name of the listed records
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
    _e( 'No services  found, dude.' );
  }
  function column_default( $item, $column_name ) {
    switch( $column_name ) { 
        case 'service_name':
        case 'as_flag':
        case 'icon':
        case 'service_onoff_on_listing':
        case 'status':
            return $item[ $column_name ];
        default:
            return print_r( $item, true ) ; //Show the whole array for troubleshooting purposes
    }
  }
function get_sortable_columns() {
  $sortable_columns = array(
    'service_name'  => array('service_name',false),
    'as_flag' => array('as_flag',false),
    'icon'   => array('icon',false),
    'service_onoff_on_listing' => array('service_onoff_on_listing',false),
    'status' => array('status',false)
  
  );
  return $sortable_columns;
}
function get_columns(){
        $columns = array(
            'cb'        => '<input type="checkbox" />',
            'service_name' => __( 'Services', 'mylisttable' ),
            'as_flag' => __( 'Additional Services', 'mylisttable' ),
            'icon'    => __( 'Icon', 'mylisttable' ),
            'service_onoff_on_listing'      => __( 'Display On Installer Page', 'mylisttable' ),
            'status'      => __('Status', 'mylisttable' )
        );
         return $columns;
    }
function usort_reorder( $a, $b ) {
  // If no sort, default to title
  $orderby = ( ! empty( $_GET['orderby'] ) ) ? $_GET['orderby'] : 'service_name';
  // If no order, default to asc
  $order = ( ! empty($_GET['order'] ) ) ? $_GET['order'] : 'asc';
  // Determine sort order
  $result = strcmp( $a[$orderby], $b[$orderby] );
  // Send final sort direction to usort
  return ( $order === 'asc' ) ? $result : -$result;
}
function column_service_name($item){
  $actions = array(
            'edit'      => sprintf('<a href="?page=%s&action=%s&services_id=%s">Edit</a>','services-add-new','edit',$item['ID']),
            //'delete'    => sprintf('<a href="?page=%s&action=%s&installer_id=%s">Delete</a>',$_REQUEST['page'],'delete',$item['ID']),
        );
  return sprintf('%1$s %2$s', $item['service_name'], $this->row_actions($actions) );
}
function get_bulk_actions() {
  $actions = array(
    'delete'    => 'Delete'
  );
  return $actions;
}
function column_cb($item) {
        return sprintf(
            '<input type="checkbox" name="services[]" value="%s" />', $item['ID']
        );    
    }
function prepare_items() {
  $columns  = $this->get_columns();
  $hidden   = array();
  $sortable = $this->get_sortable_columns();
  $this->_column_headers = array( $columns, $hidden, $sortable );
  $data=$this->get_services();
 
  usort($data, array( &$this, 'usort_reorder' ) );
  
  $per_page = 10;
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

add_action('admin_menu', 'services_menu');

function services_menu()
{
	$hook = add_menu_page('Services Management', 'Services Management', 'manage_options', 'services-manage', 'services_list_page', 'dashicons-admin-tools',62 );

	add_submenu_page('services-manage','Services Management Page', 'Add New', 'manage_options', 'services-add-new', 'services_from', 'dashicons-media-text',63 );
	 //add_action( "load-$hook", 'add_options_supplier' );


}

/*add_action('admin_enqueue_scripts', 'bpartner_admin_style');

function bpartner_admin_style()
{
    
     wp_enqueue_style('bpartner_admin_stylea', plugins_url('/style.css', __FILE__));

     wp_enqueue_script('bpartner_admin_scripta', plugins_url('/manage-bpartner.js', __FILE__), array('jquery'));
}
*/


//add_action( 'admin_menu', 'my_add_menu_items' );
function services_list_page(){
  $servicesListTable = new Services_List_Table();
  ?>
  <div class="wrap"><h2>Services Management <a href="?page=services-add-new" class="page-title-action">Add New Services</a>
 	<div class="admin-url" hidden=""><?php echo admin_url('admin-ajax.php'); ?></div></h2> 
 <?php $servicesListTable->prepare_items(); 
?>
  <form method="post">
    <input type="hidden" name="page" value="services-manage">
    <?php
    $servicesListTable->search_box( 'search', 'search_id' );
    $servicesListTable->display(); 
    echo '</form></div>'; 
}




function services_from(){
	 $action=$_GET['action'];
	 if($action=='edit'){
	  include('update-services-data.php');
	   services_update();
	 }else{
	  	  tim_add_new_services();	
	 }	
}

