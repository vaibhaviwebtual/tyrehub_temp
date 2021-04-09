<?php 
add_action('admin_menu', 'tim_delivery_range_menu');

function tim_delivery_range_menu()
{
	$hook = add_menu_page('tyrehub-settings', 'Tyrehub Settings', 'manage_options', 'tyrehub-settings', 'tyrehub_settings_fun', 'dashicons-admin-home',62 );


	/*add_submenu_page('tyrehub-general-settings','Delivery Range', 'Add New', 'manage_options', 'delivery-range-add', 'delivery_range_add_fun', '',62 );*/
	add_submenu_page('tyrehub-settings','Tyrehub Settings', 'Delivery Range', 'manage_options', 'delivery-range', 'delivery_range_fun', '',62 );
	add_submenu_page('delivery-range','Delivery Range', 'Add New', 'manage_options', 'delivery-range-add', 'delivery_range_add_fun', '',62 );

	

	//add_action( "load-$hook", 'add_options_delivery_range' );
}

if( ! class_exists( 'WP_List_Table' ) ) {
    require_once( ABSPATH . 'wp-admin/includes/class-wp-list-table.php' );
}

class Delivery_Range_Table extends WP_List_Table {
	function get_delivery_rage(){
    	$s=$_POST['s'];
    	global $wpdb;
	    $sql = "SELECT dr.*,ct.city_name FROM  th_city_delivery_range as dr LEFT JOIN th_city as ct ON ct.city_id=dr.city_id";
	    if($s){
	    $sql.=" where dr.range_title LIKE '%".$s."%'";	
		}	    
	    $sql.=" order by dr.ct_del_rg_id desc";

	    $delivery_range_data = $wpdb->get_results($sql);


   		$delivery_range=array();
		foreach ($delivery_range_data as $key => $value)
		{
			$delivery_range[$key]['range_id'] = $value->ct_del_rg_id;
			$delivery_range[$key]['city_name']= $value->city_name;
			$delivery_range[$key]['range_title']= $value->range_title;
			$delivery_range[$key]['latitude']= $value->latitude;
			$delivery_range[$key]['longitude']= $value->longitude;
			$delivery_range[$key]['km_range']= $value->km_range;
			$delivery_range[$key]['km_distance']= $value->km_distance;
			$delivery_range[$key]['status']= ($value->status == 1 ? 'Active' : 'Inactive');
					
		}
		return $delivery_range;
		die;
    }
		function __construct(){
		    global $status, $page;
		        parent::__construct( array(
		            'singular'  => __( 'delivery', 'mylisttable' ),     //singular name of the listed records
		            'plural'    => __( 'delivery', 'mylisttable' ),   //plural name of the listed records
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
		        case 'range_id':
		        case 'city_name':
		        case 'range_title':
		        case 'latitude':
		        case 'longitude':
		        case 'km_range':
		        case 'km_distance':		        
		        case 'status':

		            return $item[ $column_name ];
		        default:
		            return print_r( $item, true ) ; //Show the whole array for troubleshooting purposes
		    }
	  	}

		function get_sortable_columns() {
		  $sortable_columns = array(
		  	'range_id' => array('range_id',false),
		    'city_name'  => array('city_name',true),
		    'range_title'  => array('range_title',true),
		    'latitude'  => array('latitude',false),
		    'longitude'  => array('longitude',false),
		    'km_range'  => array('km_range',true),
		    'km_distance'  => array('km_distance',true),		    
		    'status'  => array('status',true)

		  );
		  return $sortable_columns;
		}

		function get_columns(){
	        $columns = array(
	            'cb'        => '<input type="checkbox" />',
	            'range_id' =>  __('ID', 'mylisttable' ),
	            'city_name' =>  __('City Name', 'mylisttable' ),
	            'range_title' =>  __('Range Title', 'mylisttable' ),
	            'latitude' =>  __( 'Latitude', 'mylisttable' ),
	            'longitude' =>  __( 'Longitude', 'mylisttable' ),
	            'km_range' =>  __( 'KM Range(In Radius)', 'mylisttable' ),
	            'km_distance' =>  __( 'KM Range(In Distance)', 'mylisttable' ),
	            'status' => __( 'Status', 'mylisttable' )
	            
	        );
	         return $columns;
	    }

	    function usort_reorder( $a, $b ) {
		  // If no sort, default to title
		  $orderby = ( ! empty( $_GET['orderby'] ) ) ? $_GET['orderby'] : 'range_id';
		  // If no order, default to asc
		  $order = ( ! empty($_GET['order'] ) ) ? $_GET['order'] : 'asc';
		  // Determine sort order
		  $result = strcmp( $a[$orderby], $b[$orderby] );
		  // Send final sort direction to usort
		  return ( $order === 'asc' ) ? $result : -$result;
		}

		function column_range_id($item){
		  $actions = array(
		            'edit'      => sprintf('<a href="?page=%s&action=%s&range_id=%s">Edit</a>','delivery-range-add','edit',$item['range_id']),
		            'delete'    => sprintf('<a href="?page=%s&action=%s&range_id=%s">Delete</a>','delivery-range-add','delete',$item['range_id']),
		        );
		  return sprintf('%1$s %2$s', $item['range_id'], $this->row_actions($actions) );
		}

		function get_bulk_actions() {
		  $actions = array(
		    'delete'    => 'Delete'
		  );
		  return $actions;
		}

		function column_cb($item) {
	        return sprintf(
	            '<input type="checkbox" name="delivery_range[]" value="%s" />', $item['range_id']
	        );    
	    }

	    function prepare_items() {
			  $columns  = $this->get_columns();
			  $hidden   = array();
			  $sortable = $this->get_sortable_columns();
			  $this->_column_headers = array( $columns, $hidden, $sortable );
			  $data=$this->get_delivery_rage();
			 
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

		
}

function add_options_delivery_range() {
  global $DeliveryRangeTable;
  $option = 'per_page';
  $args = array(
         'label' => 'Delivery Range',
         'default' => 10,
         'option' => 'delivery_range_per_page'
         );
  add_screen_option( $option, $args );
  $DeliveryRangeTable = new Delivery_Range_Table();

}

function delivery_range_fun(){

	global $DeliveryRangeTable;
	$DeliveryRangeTable = new Delivery_Range_Table();
	
	?>
	 <div class="wrap"><h2>Delivery Range <a href="?page=delivery-range-add&action=add" class="page-title-action">Add New Range</a>
 	<div class="admin-url" hidden=""><?php echo admin_url('admin-ajax.php'); ?></div></h2>

 	 <?php $DeliveryRangeTable->prepare_items(); ?>

 	<form method="post">
    	<input type="hidden" name="page" value="delivery-range">
    <?php
	    $DeliveryRangeTable->search_box( 'search', 'search_id' );
	    $DeliveryRangeTable->display();
	?> 
	</form>
</div>

	<?php

}

function delivery_range_add_fun(){

	?>
	<div class="wrap">
		<h2>Add Delivery Range</h2>
		<?php 
		global $wpdb, $woocommerce;
		$range_id = $_GET['range_id'];

		if($_GET['action'] == 'delete'){
			$wpdb->get_results("DELETE from th_city_delivery_range WHERE ct_del_rg_id = '$range_id'");
			wp_redirect('?page=delivery-range');
		}
		

		$sql = "SELECT * FROM th_city ";
    	$city_data = $wpdb->get_results($sql);
    	

    	/*$sql = "SELECT * FROM th_city where city_id = '$range_id'";
    	$city_data = $wpdb->get_results($sql);
    	foreach ($city_data as $data) {   
    		$city_name = $data->city_name;
    		$std_code = $data->std_code;
    	}*/
    	$editrange = $wpdb->get_row( $wpdb->prepare( "SELECT * FROM th_city_delivery_range WHERE ct_del_rg_id ='".$range_id."'" ) );
		//echo $thepost->post_title; 
		?>
		<form method="post" action="">
			<table class="form-table">
				<tr>
					<td>City</td>
					<td>
					<select id="city_name" name="city_name">
						<option value="">--Choose City--</option>
						<?php foreach ($city_data as $data) {?> 				    	
						<option value="<?=$data->city_id;?>" <?php if($data->city_id==$editrange->city_id){ echo 'selected';}?>><?=$data->city_name;?></option>
						<?php }?>
					</select>
					</td>
				</tr>
				<tr>
					<td>Range Title</td>
					<td><input type="text" name="range_title" value="<?php echo $editrange->range_title; ?>"></td>
				</tr>
				<tr>
					<td>Latitude</td>
					<td><input type="text" name="latitude" value="<?php echo $editrange->latitude; ?>"></td>
				</tr>
				<tr>
					<td>Longitude</td>
					<td><input type="text" name="longitude" value="<?php echo $editrange->longitude; ?>"></td>
				</tr>
				<tr>
					<td>KM Range(In Radius)</td>
					<td><input type="text" name="km_range" value="<?php echo $editrange->km_range; ?>"></td>
				</tr>
				<tr>
					<td>KM Range(In Distance)</td>
					<td><input type="text" name="km_distance" value="<?php echo $editrange->km_distance; ?>"></td>
				</tr>
				<tr>
					<td>Status</td>
					<td>
					<select name="status" id="status">
						<option value="0" <?php if($editrange->status==0){ echo 'selected';}?>>Inactive</option>
						<option value="1" <?php if($editrange->status==1){ echo 'selected';}?>>Active</option>
					</select>
					</td>
				</tr>
				<tr>
					<td><input type="submit" name="save_range" value="Save Range"></td>
				</tr>
			</table>
		</form>

		<?php 

		if(isset($_POST['save_range'])){
			$city_name = $_POST['city_name'];
			$range_title = $_POST['range_title'];
			$longitude = $_POST['longitude'];
			$latitude = $_POST['latitude'];
			$km_range = $_POST['km_range'];
			$km_distance = $_POST['km_distance'];
			$status = $_POST['status'];


			if($_GET['action'] == 'add'){

				$insert = $wpdb->insert('th_city_delivery_range', array(
                                        'city_id' => $city_name,
                                        'range_title' => $range_title,
                                        'latitude' => $latitude,
                                        'longitude' => $longitude,
                                        'km_range' => $km_range,
                                        'km_distance' => $km_distance,
                                        'status' => $status,
                                        ));
			}
			elseif ($_GET['action'] == 'edit') {
				$range_id = $_GET['range_id'];
				

				$update_service = $wpdb->get_results("UPDATE th_city_delivery_range set city_id = '$city_name', range_title = '$range_title',latitude = '$latitude',longitude = '$longitude',km_range = '$km_range',km_distance = '$km_distance',status = '$status' WHERE ct_del_rg_id = '$range_id' ");
			}			

			wp_redirect('?page=delivery-range');
		}
		?>
	</div>
	<?php

}
?>