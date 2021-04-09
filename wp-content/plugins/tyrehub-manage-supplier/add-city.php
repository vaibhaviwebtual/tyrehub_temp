<?php 
add_action('admin_menu', 'tim_city_menu');

function tim_city_menu()
{
	$hook = add_menu_page('Installer Cities', 'Installer Cities', 'manage_options', 'installer-cities', 'installers_cities_page', 'dashicons-admin-home',62 );

	add_submenu_page('installer-cities','Installer Cities', 'Add New', 'manage_options', 'installer-cities-add', 'instller_add_city', '',62 );

	add_action( "load-$hook", 'add_options_city' );
}

if( ! class_exists( 'WP_List_Table' ) ) {
    require_once( ABSPATH . 'wp-admin/includes/class-wp-list-table.php' );
}

class Installers_city_Table extends WP_List_Table {
	function get_installers(){
    	$s=$_POST['s'];
    	global $wpdb;
	    $sql = "SELECT * FROM th_city";
	    if($s){
	    $sql.=" where city_name LIKE '%".$s."%'";	
		}	    
	    $sql.=" order by city_id desc";
	    $installer_city_data = $wpdb->get_results($sql);


   		$installers_city=array();
		foreach ($installer_city_data as $key => $value)
		{
			$installers_city[$key]['city_id'] = $value->city_id;
			$installers_city[$key]['city_name']= $value->city_name;
			$installers_city[$key]['std_code']= $value->std_code;
					
		}
		return $installers_city;
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
		        case 'city_name':
		        case 'std_code':
		        case 'city_id':
		            return $item[ $column_name ];
		        default:
		            return print_r( $item, true ) ; //Show the whole array for troubleshooting purposes
		    }
	  	}

		function get_sortable_columns() {
		  $sortable_columns = array(
		  	'city_id' => array('city_id',false),
		    'city_name'  => array('city_name',false),
		    'std_code'  => array('std_code',false)

		  );
		  return $sortable_columns;
		}

		function get_columns(){
	        $columns = array(
	            'cb'        => '<input type="checkbox" />',
	            'city_id' =>  __( 'City Id', 'mylisttable' ),
	            'std_code' => __( 'STD Code', 'mylisttable' ),
	            'city_name' => __( 'City Name', 'mylisttable' )
	            
	        );
	         return $columns;
	    }

	    function usort_reorder( $a, $b ) {
		  // If no sort, default to title
		  $orderby = ( ! empty( $_GET['orderby'] ) ) ? $_GET['orderby'] : 'city_id';
		  // If no order, default to asc
		  $order = ( ! empty($_GET['order'] ) ) ? $_GET['order'] : 'asc';
		  // Determine sort order
		  $result = strcmp( $a[$orderby], $b[$orderby] );
		  // Send final sort direction to usort
		  return ( $order === 'asc' ) ? $result : -$result;
		}

		function column_city_name($item){
		  $actions = array(
		            'edit'      => sprintf('<a href="?page=%s&action=%s&city_id=%s">Edit</a>','installer-cities-add','edit',$item['city_id']),
		            'delete'    => sprintf('<a href="?page=%s&action=%s&city_id=%s">Delete</a>','installer-cities-add','delete',$item['city_id']),
		        );
		  return sprintf('%1$s %2$s', $item['city_name'], $this->row_actions($actions) );
		}

		function get_bulk_actions() {
		  $actions = array(
		    'delete'    => 'Delete'
		  );
		  return $actions;
		}

		function column_cb($item) {
	        return sprintf(
	            '<input type="checkbox" name="installers_city[]" value="%s" />', $item['city_id']
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

		
}

function add_options_city() {
  global $installersCitiesTable;
  $option = 'per_page';
  $args = array(
         'label' => 'Installers',
         'default' => 10,
         'option' => 'installers_per_page'
         );
  add_screen_option( $option, $args );
  $installersCitiesTable = new Installers_city_Table();
}

function installers_cities_page(){
	global $installersCitiesTable;
	
	?>
	 <div class="wrap"><h2>Installer Cities <a href="?page=installer-cities-add&action=add" class="page-title-action">Add New City</a>
 	<div class="admin-url" hidden=""><?php echo admin_url('admin-ajax.php'); ?></div></h2></div>

 	 <?php $installersCitiesTable->prepare_items(); ?>

 	<form method="post">
    	<input type="hidden" name="page" value="installer-manage">
    <?php
	    $installersCitiesTable->search_box( 'search', 'search_id' );
	    $installersCitiesTable->display();
	?> 
	</form></div>

	<?php

}

function instller_add_city(){
	?>
	<div class="wrap">
		<h2>Add Installer Cities</h2>
		<?php 
		global $wpdb, $woocommerce;
		$city_id = $_GET['city_id'];

		if($_GET['action'] == 'delete'){
			$wpdb->get_results("DELETE from th_city WHERE city_id = '$city_id'");
			wp_redirect('?page=installer-cities');
		}
		

		$sql = "SELECT * FROM th_city where city_id = '$city_id'";
    	$city_data = $wpdb->get_results($sql);
    	foreach ($city_data as $data) {   
    		$city_name = $data->city_name;
    		$std_code = $data->std_code;
    	}
		?>
		<form method="post" action="">
			<table class="form-table">
				<tr>
					<td>City Name</td>
					<td><input type="text" name="city_name" value="<?php echo $city_name; ?>"></td>
				</tr>
				<tr>
					<td>STD Code</td>
					<td><input type="text" name="std_code" value="<?php echo $std_code; ?>"></td>
				</tr>
				<tr>
					<td><input type="submit" name="save_city" value="Save City"></td>
				</tr>
			</table>
		</form>

		<?php 
		if(isset($_POST['save_city'])){
			$city_name = $_POST['city_name'];
			$std_code = $_POST['std_code'];
			if($_GET['action'] == 'add'){

				$insert = $wpdb->insert('th_city', array(
                                        'city_name' => $city_name,
                                        'std_code' => $std_code,
                                        ));
			}
			elseif ($_GET['action'] == 'edit') {
				$city_id = $_GET['city_id'];
				

				$update_service = $wpdb->get_results("UPDATE th_city set city_name = '$city_name', std_code = '$std_code' WHERE city_id = '$city_id' ");
			}			

			wp_redirect('?page=installer-cities');
		}
		?>
	</div>
	<?php

}
?>