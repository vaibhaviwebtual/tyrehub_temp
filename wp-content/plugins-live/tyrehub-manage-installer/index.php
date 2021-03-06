<?php
/*
Plugin Name: Tyrehub Installer Management
Plugin URI: https://acespritech.com/
Description: Installer reports and paid functionality.
Version: 1.1.1
Author: Acespritech
Author URI: https://acespritech.com/
*/
if( ! class_exists( 'WP_List_Table' ) ) {
    require_once( ABSPATH . 'wp-admin/includes/class-wp-list-table.php' );
}
include_once('add-new-installer.php');

class Installers_List_Table extends WP_List_Table {

    function get_installers(){
    	$s=$_POST['s'];
      $franchise=$_GET['franchise'];
    	global $wpdb;
	    $sql = "SELECT * FROM th_installer_data";
      $whereserch='';
      if($franchise=='yes'){
        $whereserch.=" AND is_franchise='yes'";
      }
	    if($s){	    
		    $whereserch.=" AND business_name LIKE '%".$s."%' OR contact_no LIKE '%".$s."%' OR user_code LIKE '%".$s."%'";
      }	    
      $sql.=" where 1=1 ".$whereserch;  
	    $sql.=" order by installer_data_id desc";
	    $installer_data = $wpdb->get_results($sql);


   		$installers=array();
		foreach ($installer_data as $key => $value)
		{
			$installers[$key]['ID'] = $value->installer_data_id;
			$installers[$key]['user_code']= $value->user_code;
      $installers[$key]['business_name']= $value->business_name;
			$installers[$key]['mobile_no']= $value->contact_no;
			$installers[$key]['address']= $value->address;
      $installers[$key]['wallet_balance']='<i class="fa fa-inr"></i> '.$value->wallet_balance;
			if($value->visibility==0){
			$installers[$key]['visibility']= '<a href="'.get_admin_url().'/admin.php?page=installer-add-new&action=edit&installer_id='.$value->installer_data_id.'" class="button-secondary woocommerce-save-button add-installer-btn">No</a>';	
			}else{
			$installers[$key]['visibility']= '<a href="'.get_admin_url().'/admin.php?page=installer-add-new&action=edit&installer_id='.$value->installer_data_id.'" class="button-primary woocommerce-save-button add-installer-btn">Yes</a> ';	
			}
			
		}

		return $installers;
		die;
    }
    function __construct(){
    global $status, $page;
        parent::__construct( array(
            'singular'  => __( 'installer', 'mylisttable' ),     //singular name of the listed records
            'plural'    => __( 'installers', 'mylisttable' ),   //plural name of the listed records
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
    _e( 'No installer found, dude.' );
  }
  function column_default( $item, $column_name ) {
    switch( $column_name ) { 
        case 'user_code':
        case 'business_name':
        case 'mobile_no':
        case 'address':
        case 'wallet_balance':        
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
    'wallet_balance'   => array('wallet_balance',false),
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
            'wallet_balance'      => __( 'Balance', 'mylisttable' ),
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
function column_business_name($item){
  $actions = array(
            'edit'      => sprintf('<a href="?page=%s&action=%s&installer_id=%s">Edit</a>','installer-add-new','edit',$item['ID']),
            //'delete'    => sprintf('<a href="?page=%s&action=%s&installer_id=%s">Delete</a>',$_REQUEST['page'],'delete',$item['ID']),
        );
  return sprintf('%1$s %2$s', $item['business_name'], $this->row_actions($actions) );
}
function get_bulk_actions() {
  $actions = array(
    'delete'    => 'Delete'
  );
  return $actions;
}
function column_cb($item) {
        return sprintf(
            '<input type="checkbox" name="installer[]" value="%s" />', $item['ID']
        );    
    }
function prepare_items() {
  $columns  = $this->get_columns();
  $hidden   = array();
  $sortable = $this->get_sortable_columns();
  $this->_column_headers = array( $columns, $hidden, $sortable );
  $data=$this->get_installers();
 
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

add_action('admin_menu', 'tim_menu');

function tim_menu()
{
	$hook = add_menu_page('Installer Management', 'Installer', 'manage_options', 'installer-manage', 'installers_list_page', 'dashicons-admin-tools',62 );

	add_submenu_page('installer-manage','Installer Management Page', 'Add New', 'manage_options', 'installer-add-new', 'instller_from', 'dashicons-media-text',62 );

	//add_submenu_page('installer-manage','Installer Management Page', 'Installer Update', 'manage_options', 'installer-update-data', 'tim_update');

	add_submenu_page('installer-manage','Installer Management Page', 'Facilities', 'manage_options', 'installer-facilities', 'tim_facilities');
  add_submenu_page('installer-manage','Services Charges', 'Services Charges', 'manage_options', 'installer-services-charge', 'installer_services_charge_fun', '',62 );

	//add_submenu_page('installer-manage','Installer Management Page', 'Installer facilities', 'manage_options', 'installer-del-fac', 'tim_del_fac');

	 add_action( "load-$hook", 'add_options' );


}

add_action('admin_enqueue_scripts', 'tim_admin_style');

function tim_admin_style()
{
    
     wp_enqueue_style('tim_admin_stylea', plugins_url('/style.css', __FILE__));

     wp_enqueue_script('tim_admin_scripta', plugins_url('/manage-installer.js', __FILE__), array('jquery'));
}

/*function my_add_menu_items(){
  $hook = add_menu_page( 'My Plugin List Table', 'My List Table Example', 'activate_plugins', 'my_list_test', 'my_render_list_page' );
  add_action( "load-$hook", 'add_options' );
}*/
function add_options() {
  global $installersListTable;
  $option = 'per_page';
  $args = array(
         'label' => 'Installers',
         'default' => 10,
         'option' => 'installers_per_page'
         );
  add_screen_option( $option, $args );
  $installersListTable = new Installers_List_Table();
}
//add_action( 'admin_menu', 'my_add_menu_items' );
function installers_list_page(){
  global $installersListTable;?>
  <div class="wrap"><h2>Installer Management <a href="?page=installer-add-new" class="page-title-action">Add New Installer</a>
 	<a href="?page=installer-facilities" class="page-title-action">Manage Facilities</a>
 	<div class="admin-url" hidden=""><?php echo admin_url('admin-ajax.php'); ?></div></h2> 
 <?php $installersListTable->prepare_items(); 
?>
  <form method="post">
    <input type="hidden" name="page" value="installer-manage">
    <?php
    $installersListTable->search_box( 'search', 'search_id' );
    $installersListTable->display(); 
    echo '</form></div>'; 
}


function instller_from(){
	 $action=$_GET['action'];
	 if($action=='edit'){
	  include('update-installer-data.php');
	  tim_update();
	 }else{
	  	  tim_add_new();	
	 }	
}




function tim_facilities(){
	?>
	<div class="wrap installer-fac">
 	
 	<div class="admin-url" hidden=""><?php echo admin_url('admin-ajax.php'); ?></div>
 	<div class="column left">
 		<h1 class="wp-heading-inline">Facilities</h1>
 		<table class="wp-list-table widefat fixed striped posts">	
	 		<th>Facility Name</th>
	 		<th>Actions</th>
		 	<?php
			 	global $wpdb;
			    $fc_sql = "SELECT * from th_installer_facilities where type = 'f'";
			    $fc_data = $wpdb->get_results($fc_sql);
			    foreach ($fc_data as $key => $fc_row)
			    {
			    	$f_id = $fc_row->f_id;
			    	$name = $fc_row->name;	    	
			    	echo '<tr><td>';
			    	echo $name;
			    	echo '</td><td><a href="?page=installer-del-fac&f_id='.$f_id.'">Delete</td></tr>';
			    }
			?>
		</table>
 	</div>
 	<div class="column right">
 		
 		<h1 class="wp-heading-inline">Additional Services</h1>
 		<table class="wp-list-table widefat fixed striped posts">	
	 		<th>Facility Name</th>
	 		<th>Actions</th>
		 	<?php
			 	global $wpdb;
			    $as_sql = "SELECT * from th_installer_facilities where type = 'as'";
			    $as_data = $wpdb->get_results($as_sql);
			    foreach ($as_data as $key => $as_row)
			    {
			    	$f_id = $as_row->f_id;
			    	$name = $as_row->name;	    	
			    	echo '<tr><td>';
			    	echo $name;
			    	echo '</td><td><a href="?page=installer-del-fac&f_id='.$f_id.'">Delete</a></td></tr>';
			    }
			?>
		</table>
 	</div>
 	
	<?php
}

function tim_del_fac(){

	$f_id = $_GET['f_id'];
	global $wpdb;
	$sql = "DELETE FROM th_installer_facilities WHERE f_id = $f_id" ;
	$wpdb->get_results($sql);
	wp_redirect( '?page=installer-facilities' );
}

add_action('wp_ajax_save_additional_service', 'save_additional_service');
add_action('wp_ajax_nopriv_save_additional_service', 'save_additional_service');
function save_additional_service(){
    $name = $_POST['name'];
    $installer_id = $_POST['installer_id'];

    global $wpdb;

    $insert = $wpdb->insert('th_service_data', array(
        'service_name' => $name,
        'as_flag' =>1,
        'status' =>1
        ));

    $fc_sql = "SELECT * from th_service_data where as_flag =1 AND status=1";
    $services_data = $wpdb->get_results($fc_sql);

    $SQL="SELECT service_data_id from th_installer_addi_service where installer_id = '$installer_id'";
    $sas_sql_arr = $wpdb->get_results($SQL);                        
                       
      foreach ($sas_sql_arr as $key => $seleservice){
        $servdata[]=$seleservice->service_data_id;
      }

    

    foreach ($services_data as $key => $service)
      {
          $name = $fc_row->name;
          $f_id = $fc_row->f_id;
          ?>
          <p class="woocommerce-form-row woocommerce-form-row--wide form-row form-row-wide">
              <input type="checkbox" class="" name="services[]" value="<?php $service->service_data_id; ?>" <?php if(in_array($service->service_data_id,$servdata)){ echo 'checked';}  ?>/>&nbsp;<?php esc_html_e($service->service_name, 'woocommerce' ); ?>      
          </p>
          <?php
      }
    die();
}

add_action('wp_ajax_save_facilities', 'save_facilities');
add_action('wp_ajax_nopriv_save_facilities', 'save_facilities');
function save_facilities(){
    $name = $_POST['name'];
    $installer_id = $_POST['installer_id'];
    global $wpdb;

     $insert = $wpdb->insert('th_installer_facilities', array(
        'name' => $name,
        'type' => 'f'
        ));

    $fc_sql = "SELECT * from th_installer_facilities where type = 'f'";
    $fc_data = $wpdb->get_results($fc_sql);

    $sfc_sql = $wpdb->get_var( $wpdb->prepare("SELECT meta_value from th_installer_meta where installer_id = '$installer_id' and meta_name = 'facilities'"));
                        

    $sfc_sql_arr = unserialize($sfc_sql); 

    if($installer_id != '')
    {
      foreach ($fc_data as $key => $fc_row)
        {
            $name = $fc_row->name;
            $f_id = $fc_row->f_id;
            ?>
            <p class="woocommerce-form-row woocommerce-form-row--wide form-row form-row-wide">
                <input type="checkbox" class="" name=fc-check[]" id="<?php echo $f_id; ?>" value="<?php echo $f_id; ?>" <?php if(in_array($f_id, $sfc_sql_arr)){ echo 'checked';}  ?>/>&nbsp;<?php esc_html_e( $name , 'woocommerce' ); ?>      
            </p>
            <?php
        }
    }
    else{
      foreach ($fc_data as $key => $fc_row)
      {
          $name = $fc_row->name;
          $f_id = $fc_row->f_id;
          ?>
          <p class="woocommerce-form-row woocommerce-form-row--wide form-row form-row-wide">
              <input type="checkbox" class="" name="fc-check[]" value="<?php $f_id; ?>" />&nbsp;<?php esc_html_e( $name , 'woocommerce' ); ?>      
          </p>
          <?php
      }
    }
    die();
}


include('add-city.php');
include('fitment-charges.php');
include('services-charges.php');