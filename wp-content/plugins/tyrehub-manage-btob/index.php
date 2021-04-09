<?php
/*
Plugin Name: Tyrehub BTOB Partner Management
Plugin URI: https://webtual.com/
Description: BTOB Partner reports and paid functionality.
Version: 1.1.1
Author: Webtual
Author URI: https://webtual.com/
*/
if( ! class_exists( 'WP_List_Table' ) ) {
    require_once( ABSPATH . 'wp-admin/includes/class-wp-list-table.php' );
}
include_once('add-new-business.php');
include_once('business-history.php');


class bpartner_List_Table extends WP_List_Table {

    function get_bpartner(){
    	$s=$_POST['s'];
    	global $wpdb;
	    $sql = "SELECT * FROM th_business_partner_data";
	    if($s){
	    $sql.=" where business_name LIKE '%".$s."%' OR contact_no LIKE '%".$s."%' OR user_code LIKE '%".$s."%'";	
		}	    
	    $sql.=" order by bpartner_data_id desc";
	    $supplier_data = $wpdb->get_results($sql);


   		$bpartner=array();
		foreach ($supplier_data as $key => $value)
		{
			$bpartner[$key]['ID'] = $value->bpartner_data_id;
			$bpartner[$key]['user_code']= $value->user_code;
      $bpartner[$key]['business_name']= $value->business_name;
			$bpartner[$key]['mobile_no']= $value->contact_no;
			$bpartner[$key]['address']= $value->address;
      $bpartner[$key]['percentage']= $value->percentage;
      $bpartner[$key]['commission']= $value->commission_percentage;
      
			if($value->visibility==0){
			$bpartner[$key]['visibility']= '<a href="'.get_admin_url().'/admin.php?page=bpartner-add-new&action=edit&bpartner_id='.$value->bpartner_data_id.'" class="button-secondary woocommerce-save-button add-installer-btn">No</a>';	
			}else{
			$bpartner[$key]['visibility']= '<a href="'.get_admin_url().'/admin.php?page=bpartner-add-new&action=edit&bpartner_id='.$value->bpartner_data_id.'" class="button-primary woocommerce-save-button add-installer-btn">Yes</a> ';	
			}

     
		}

		return $bpartner;
		die;
    }
    function __construct(){
    global $status, $page;
        parent::__construct( array(
            'singular'  => __( 'bpartner', 'mylisttable' ),     //singular name of the listed records
            'plural'    => __( 'bpartners', 'mylisttable' ),   //plural name of the listed records
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
    _e( 'No business partner found, dude.' );
  }
  function column_default( $item, $column_name ) {
    switch( $column_name ) { 
        case 'user_code':
        case 'business_name':
        case 'mobile_no':
        case 'address':
        case 'percentage':
        case 'commission':
        case 'visibility':
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
    'percentage' => array('percentage',false),
    'commission' => array('commission',false),    
    'visibility'   => array('visibility',false)
  
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
            'percentage'      => __('Percentage', 'mylisttable' ),
            'commission'      => __('Commission %', 'mylisttable' ),
            'visibility'      => __( 'Visibility', 'mylisttable' )
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
            'edit'      => sprintf('<a href="?page=%s&action=%s&bpartner_id=%s">Edit</a>','bpartner-add-new','edit',$item['ID']),
            //'delete'    => sprintf('<a href="?page=%s&action=%s&installer_id=%s">Delete</a>',$_REQUEST['page'],'delete',$item['ID']),
        );
  return sprintf('%1$s %2$s', $item['user_code'], $this->row_actions($actions) );
}
function get_bulk_actions() {
  $actions = array(
    'delete'    => 'Delete'
  );
  return $actions;
}
function column_cb($item) {
        return sprintf(
            '<input type="checkbox" name="bpartner[]" value="%s" />', $item['ID']
        );    
    }
function prepare_items() {
  $columns  = $this->get_columns();
  $hidden   = array();
  $sortable = $this->get_sortable_columns();
  $this->_column_headers = array( $columns, $hidden, $sortable );
  $data=$this->get_bpartner();
 
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

add_action('admin_menu', 'bpartner_menu');

function bpartner_menu()
{
	$hook = add_menu_page('Business Partner Management', 'Business Partner Management', 'manage_options', 'bpartner-manage', 'bpartner_list_page', 'dashicons-admin-tools',62 );

	add_submenu_page('bpartner-manage','Business Partner Management Page', 'Add New', 'manage_options', 'bpartner-add-new', 'bpartner_from', 'dashicons-media-text',63 );
  

add_submenu_page('bpartner-manage','Commission History', 'Commission History', 'manage_options', 'commission-history', 'commission_history_fun', 'dashicons-media-text',62 );
  


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
function bpartner_list_page(){
  $bpartnerListTable = new Bpartner_List_Table();
  ?>
  <div class="wrap"><h2>Business Partner Management <a href="?page=bpartner-add-new" class="page-title-action">Add New Business Partner</a>
 	<div class="admin-url" hidden=""><?php echo admin_url('admin-ajax.php'); ?></div></h2> 
 <?php $bpartnerListTable->prepare_items(); 
?>
  <form method="post">
    <input type="hidden" name="page" value="bpartner-manage">
    <?php
    $bpartnerListTable->search_box( 'search', 'search_id' );
    $bpartnerListTable->display(); 
    echo '</form></div>'; 
}




function bpartner_from(){
	 $action=$_GET['action'];
	 if($action=='edit'){
	  include('update-business-data.php');
	   bpartner_update();
	 }else{
	  	  tim_add_new_bpartner();	
	 }	
}

