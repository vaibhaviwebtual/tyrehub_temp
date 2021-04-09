<?php

/*
Plugin Name: Customer Listing
Plugin URI: https://www.sitepoint.com/using-wp_list_table-to-create-wordpress-admin-tables/
Description: Demo on how WP_List_Table Class works
Version: 1.0
Author: Collins Agbonghama
Author URI:  https://w3guy.com
*/

if ( ! class_exists( 'WP_List_Table' ) ) {
	require_once( ABSPATH . 'wp-admin/includes/class-wp-list-table.php' );
}

class Customers_List extends WP_List_Table {

	/**
	 * Retrieve customers data from the database
	 *
	 * @param int $per_page
	 * @param int $page_number
	 *
	 * @return mixed
	 */
	public static function get_customers( $per_page = 5, $page_number = 1 ) {

		global $wpdb;
		$result=array();
		$SQL="SELECT wp_users.*
FROM wp_users INNER JOIN wp_usermeta 
ON wp_users.ID = wp_usermeta.user_id";
$where=" WHERE 1=1";
if($_GET['start_date']!='' && $_GET['end_date']!=''){

	$where .=" AND DATE(wp_users.user_registered) BETWEEN '".$_GET['start_date']."' AND '".$_GET['end_date']."'";
}
$where .=" AND wp_usermeta.meta_key = 'wp_capabilities' 
AND wp_usermeta.meta_value LIKE '%customer%' 
ORDER BY wp_users.ID DESC";
$SQL .= $where;
//DATE(created_at) BETWEEN '2011-12-01' AND '2011-12-06'
		//$customers = get_users( [ 'role__in' => ['customer' ] ] );
		$customers=$wpdb->get_results($SQL);
		// Array of WP_User objects.
		foreach ( $customers as $key=> $customer ) {
		    
			$result[$key]['ID']=$customer->ID;
			$result[$key]['username']=$customer->user_login;
			$result[$key]['name']=$customer->display_name;
			$result[$key]['email']=$customer->user_email;
			$user_id=get_user_meta($customer->ID,'franchise_id',true);
			
			if($user_id!=''){
				$SQL="SELECT * FROM th_installer_data WHERE user_id='".$user_id."'";
				$franchiseData=$wpdb->get_row($SQL);
				
				if($referral_type=='organic' || ($referral_type=='' && $user_id==0)){
					$referral_type='Tyrehub';
				}else{
					if($franchiseData->is_franchise=='yes'){
						$referral_type='Franchise';
					}else{
						$referral_type='Installer';
					}
				}	
			}else{
				$referral_type='Tyrehub';
			}
			
			
			//$referral_type=get_user_meta($customer->ID,'referral_type',true);
			$user_meta = get_userdata($user_id);			
			$result[$key]['referral_name']=$user_meta->display_name;
			$result[$key]['referral_type']=$referral_type;			
			$result[$key]['register_date']=date('d-m-Y',strtotime($customer->user_registered));

		}
		return $result;
	}

    function __construct(){
    global $status, $page;
        parent::__construct( array(
            'singular'  => __( 'customer', 'mylisttable' ),     //singular name of the listed records
            'plural'    => __( 'customers', 'mylisttable' ),   //plural name of the listed records
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
    _e( 'No customer found, dude.' );
  }
  function column_default( $item, $column_name ) {
    switch( $column_name ) { 
        case 'ID':
        case 'username':
        case 'name':
        case 'email':
        case 'referral_name':
        case 'referral_type':        
        case 'register_date':
            return $item[ $column_name ];
        default:
            return print_r( $item, true ) ; //Show the whole array for troubleshooting purposes
    }
  }
function get_sortable_columns() {
  $sortable_columns = array(
    'ID'  => array('ID',false),
    'username'  => array('username',false),
    'name'  => array('name',false),
    'email' => array('email',false),
    'referral_name'   => array('referral_name',false),
    'referral_type'   => array('referral_type',false),
    'register_date'   => array('register_date',false)
    
  );
  return $sortable_columns;
}
function get_columns(){
        $columns = array(
            'cb'        => '<input type="checkbox" />',
            'ID' => __( 'ID', 'mylisttable' ),
            'username' => __( 'Username/Mobile', 'mylisttable' ),
            'name' => __( 'Customer', 'mylisttable' ),
            'email'    => __( 'Email', 'mylisttable' ),
            'referral_name'      => __( 'Referral Name', 'mylisttable' ),
            'referral_type'      => __( 'Referral Type', 'mylisttable' ),
            'register_date'      => __( 'Register Date', 'mylisttable' )
        );
         return $columns;
    }
function usort_reorder( $a, $b ) {
  // If no sort, default to title
  $orderby = ( ! empty( $_GET['orderby'] ) ) ? $_GET['orderby'] : 'name';
  // If no order, default to asc
  $order = ( ! empty($_GET['order'] ) ) ? $_GET['order'] : 'asc';
  // Determine sort order
  $result = strcmp( $a[$orderby], $b[$orderby] );
  // Send final sort direction to usort
  return ( $order === 'asc' ) ? $result : -$result;
}
function column_mobile($item){
  $actions = array(
            'edit'      => sprintf('<a href="?page=%s&action=%s&installer_id=%s">Edit</a>','installer-add-new','edit',$item['ID']),
            //'delete'    => sprintf('<a href="?page=%s&action=%s&installer_id=%s">Delete</a>',$_REQUEST['page'],'delete',$item['ID']),
        );
  //return sprintf('%1$s %2$s', $item['business_name'], $this->row_actions($actions) );
}
function get_bulk_actions() {
  $actions = array(
    'delete'    => 'Delete'
  );
  return $actions;
}
function column_cb($item) {
        return sprintf(
            '<input type="checkbox" name="customers[]" value="%s" />', $item['ID']
        );    
    }
function prepare_items() {
  $columns  = $this->get_columns();
  $hidden   = array();
  $sortable = $this->get_sortable_columns();
  $this->_column_headers = array( $columns, $hidden, $sortable );
  $data=$this->get_customers();
 if(isset($_GET['order'])){
 usort($data, array( &$this, 'usort_reorder' ) );	
 }
  
  
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

add_action('admin_menu', 'customer_menu');

function customer_menu()
{
	$hook = add_menu_page('Referral Customers', 'Referral Customers', 'manage_options', 'referral-customers', 'referral_customers_page', 'dashicons-admin-tools',62 );

	 add_action( "load-$hook", 'add_options_customers' );


}

function add_options_customers() {
  global $CustomersList;
  $option = 'per_page';
  $args = array(
         'label' => 'Customers',
         'default' => 10,
         'option' => 'customers_per_page'
         );
  add_screen_option( $option, $args );
  $CustomersList = new Customers_List();
}
//add_action( 'admin_menu', 'my_add_menu_items' );
function referral_customers_page(){
  global $CustomersList;?>
  <form method="get">
  <div class="wrap"><h2>Customers
 	<div class="admin-url" hidden=""><?php echo admin_url('admin-ajax.php'); ?></div></h2> 
 	Start Date <input type="date" name="start_date" value="<?=$_GET['start_date']?>" id="start_date">
 	End Date <input type="date" name="end_date" value="<?=$_GET['end_date']?>" id="end_date"> <input type="submit" id="" class="button action" value="Filter"> <a href="<?php echo plugin_dir_url( __FILE__ )?>csv_export.php?action=export&start_date=<?=$_GET['start_date'];?>&end_date=<?=$_GET['end_date'];?>" class="page-title-action">
  		CSV Export</a>
 <?php $CustomersList->prepare_items(); 
?>
  
    <input type="hidden" name="page" value="referral-customers">
    <?php
    //$CustomersList->search_box( 'search', 'search_id' );
    $CustomersList->display(); 
    echo '</form></div>'; 
}

