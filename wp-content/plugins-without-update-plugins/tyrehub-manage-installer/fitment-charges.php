<?php 
add_action('admin_menu', 'tim_fitment_menu');

function tim_fitment_menu()
{
	$hook = add_menu_page('Installer Fitment Charges', 'Installer Fitment Charges', 'manage_options', 'installer-fitment-charges', 'installers_fitment_charges', 'dashicons-admin-home',62 );

	//add_submenu_page('installer-cities','Installer Cities', 'Add New', 'manage_options', 'installer-cities-add', 'instller_add_city', '',62 );

	add_action( "load-$hook", 'add_options_fitmentcharges' );
}


if( ! class_exists( 'WP_List_Table' ) ) {
    require_once( ABSPATH . 'wp-admin/includes/class-wp-list-table.php' );
}

class Installers_fitment_charges_Table extends WP_List_Table {

	function get_installers_fitting_charges(){
    	$city_id=$_GET['city_id'];
    	global $wpdb;
	    $sql = "SELECT * FROM th_installer_service_price";
	    if($city_id){
	   	$sql.=" where city_id='$city_id'";	
		}	    
	    $sql.=" order by service_price_id desc";

	    $installer_city_data = $wpdb->get_results($sql);


   		$installers_charges=array();

   		$args = array(
	                'ex_tax_label'       => false,
	                'currency'           => '',
	                'decimal_separator'  => wc_get_price_decimal_separator(),
	                'thousand_separator' => wc_get_price_thousand_separator(),
	                'decimals'           => wc_get_price_decimals(),
	                'price_format'       => get_woocommerce_price_format(),
	              );

		foreach ($installer_city_data as $key => $value)
		{
			$installers_charges[$key]['service_price_id'] = $value->service_price_id;

			$city_id = $value->city_id;
			$cityname_sql ="SELECT city_name from th_city where city_id = '$city_id'";
            $city_name = $wpdb->get_var($cityname_sql);

            $installers_charges[$key]['city_id'] = $city_name;

            $service_data_id = $value->service_data_id;
            $sername_sql ="SELECT service_name from th_service_data where service_data_id = '$service_data_id'";
            $service_name = $wpdb->get_var($sername_sql);

			$installers_charges[$key]['service_data_id']= $service_name;

			$vehicle_id = $value->vehicle_id;
			$vehname_sql ="SELECT vehicle_type from th_vehicle_type where vehicle_id = '$vehicle_id'";
            $vehicle_name = $wpdb->get_var($vehname_sql);
			$installers_charges[$key]['vehicle_id'] = $vehicle_name;

			$installers_charges[$key]['rate'] =  wc_price( $value->rate, $args);			
			$installers_charges[$key]['upto_km'] = $value->upto_km;
			$installers_charges[$key]['from_km'] = $value->from_km;
			$installers_charges[$key]['to_km'] =  $value->to_km;
			$installers_charges[$key]['per_km_price'] = wc_price( $value->per_km_price, $args);
					
		}
		return $installers_charges;
		die;
    }

    function __construct(){
	    global $status, $page;
	        parent::__construct( array(
	            'singular'  => __( 'fitmentCharge', 'fitmentchargestable' ),     //singular name of the listed records
	            'plural'    => __( 'fitmentCharges', 'fitmentchargestable' ),   //plural name of the listed records
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
		        //case 'service_price_id':
		        case 'vehicle_id':
		        case 'service_data_id':
		        case 'city_id':
		        case 'rate':
		        case 'upto_km':		        
		        case 'from_km':
		        case 'to_km':
		        case 'per_km_price':
		            return $item[ $column_name ];
		        default:
		            return print_r( $item, true ) ; //Show the whole array for troubleshooting purposes
		    }
	  	}

	function get_sortable_columns() {
		  $sortable_columns = array(
		  	//'service_price_id' => array('service_price_id',false),
		  	'city_id' => array('city_id',false),
		    'vehicle_id'  => array('vehicle_id',false),
		    'service_data_id'  => array('service_data_id',false),
		    'rate' => array('rate',false),
		    'upto_km' => array('upto_km',false),
		    'from_km' => array('from_km',false),
		    'to_km' => array('to_km',false),
		    'per_km_price' => array('per_km_price',false),

		  );
		  return $sortable_columns;
		}

	function get_columns(){
        $columns = array(
            'cb'        => '<input type="checkbox" />',
           // 'service_price_id' => __( 'Service price id', 'fitmentchargestable' ),
            'city_id' =>  __( 'City Name', 'fitmentchargestable' ),
            'vehicle_id' =>  __( 'Vehicle Name', 'fitmentchargestable' ),
            'service_data_id' =>  __( 'Service Name', 'fitmentchargestable' ),
            'rate' =>  __( 'Rate', 'fitmentchargestable' ),
            'upto_km' =>  __( 'Upto km', 'fitmentchargestable' ),
            'from_km' =>  __( 'From km', 'fitmentchargestable' ),
            'to_km' =>  __( 'To km', 'fitmentchargestable' ),
            'per_km_price' =>  __( 'Per km price', 'fitmentchargestable' ),
            
        );
         return $columns;
    }

    function usort_reorder( $a, $b ) {
		  // If no sort, default to title
		//  $orderby = ( ! empty( $_GET['orderby'] ) ) ? $_GET['orderby'] : 'service_price_id';
		  // If no order, default to asc
		 // $order = ( ! empty($_GET['order'] ) ) ? $_GET['order'] : 'asc';
		  // Determine sort order
		  $result = strcmp( $a[$orderby], $b[$orderby] );
		  // Send final sort direction to usort
		  return ( $order === 'asc' ) ? $result : -$result;
		}

    function column_city_id($item){
		  $actions = array(
		            'edit'      => sprintf('<a href="?page=%s&action=%s&service_price_id=%s">Edit</a>','installer-fitment-charges','edit',$item['service_price_id']),
		            'delete'    => sprintf('<a href="?page=%s&action=%s&service_price_id=%s">Delete</a>','installer-fitment-charges','delete',$item['service_price_id']),
		        );
		  return sprintf('%1$s %2$s', $item['city_id'], $this->row_actions($actions) );
	}

	function get_bulk_actions() {
	  $actions = array(
	    'delete'    => 'Delete'
	  );
	  return $actions;
	}

	function column_cb($item) {
        return sprintf(
            '<input type="checkbox" name="installers_city[]" value="%s" />', $item['service_price_id']
        );    
    }

	 function prepare_items() {
			  $columns  = $this->get_columns();
			  $hidden   = array();
			  $sortable = $this->get_sortable_columns();
			  $this->_column_headers = array( $columns, $hidden, $sortable );
			  $this->process_bulk_action();
			  $data=$this->get_installers_fitting_charges();
			 
			  //usort($data, array( &$this, 'usort_reorder' ) );
			  
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

	public function process_bulk_action() {

	    // security check!
	    if ( isset( $_POST['_wpnonce'] ) && ! empty( $_POST['_wpnonce'] ) ) {

	        $nonce  = filter_input( INPUT_POST, '_wpnonce', FILTER_SANITIZE_STRING );
	        $action = 'bulk-' . $this->_args['plural'];

	        if ( ! wp_verify_nonce( $nonce, $action ) )
	            wp_die( 'Nope! Security check failed!' );

	    }

	    $action = $this->current_action();

	    switch ( $action ) {

	        case 'delete':
	        $bulk_del_id = $_POST['installers_city'];
	        global $wpdb, $woocommerce;
	        foreach ($bulk_del_id as $key => $service_price_id) {	        	
	        	
				$delete_service = $wpdb->get_results("DELETE from th_installer_service_price where service_price_id = '$service_price_id'");
	        }
	            wp_die( 'Delete something' );

	            break;

	        case 'save':
	            wp_die( 'Save something' );
	            break;

	        default:
	            // do nothing or something else
	            return;
	            break;
    	}

    	return;
    }


}

function add_options_fitmentcharges() {
	global $instfitchargeTable;
	$option = 'per_page';
	$args = array(
	         'label' => 'Installers Fitment Charges',
	         'default' => 10,
	         'option' => 'installers_per_page'
	         );
	add_screen_option( $option, $args );
	$instfitchargeTable = new Installers_fitment_charges_Table();
}

function installers_fitment_charges(){
	global $instfitchargeTable;	
	echo '<div class="wrap">';
	if(isset($_GET['action'])){
		$action = $_GET['action'];
		if($action == 'add'){
			include('add-fitting-charges.php');
		}
		elseif($action == 'edit'){
			include('update-fitting-charges.php');
		}
		elseif($action == 'delete'){
			include('delete-fitting-charges.php');
		}
	}
	else
	{
	?>		
	 	<h2>Installer Services Charges 
	 		<a href="?page=installer-fitment-charges&action=add" class="page-title-action">Add New</a>
 			<div class="admin-url" hidden=""><?php echo admin_url('admin-ajax.php'); ?></div>
 		</h2>
 		<div style="margin-top: 10px;">
		 <form method="get">
		 	<input type="hidden" name="page" value="<?php echo $_REQUEST['page'] ?>">
		 <select name="city_id" id="city_id">
						<option value="">--Choose City--</option>
						
						<?php
						global $wpdb;
						$sql = "SELECT * FROM th_city";
						$city_data = $wpdb->get_results($sql); 
						foreach ($city_data as $city ) 
				        {
				            
				            if($_GET['city_id']==$city->city_id){
				            	$selected='selected="selected"';
				            }else{
				            	$selected='';
				            }
				            echo '<option value="'.$city->city_id.'" '.$selected.'>'.$city->city_name.'</option>';
				        }
						?>
					</select>

				
			<input type="submit" name="Filter" value="Filter" class="button">
		</form>
</div>

 	 	<?php $instfitchargeTable->prepare_items(); ?>

 		<form method="post">
    		<input type="hidden" name="page" value="installer-manage">
		    <?php
			    $instfitchargeTable->search_box( 'search', 'search_id' );
			    $instfitchargeTable->display();
			?> 
		</form>
<?php
	}
	echo '</div>';
}