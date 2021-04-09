<?php

class Purchase_orders_List_Table extends WP_List_Table {

	function __construct(){
	    global $status, $page;
	        parent::__construct( array(
	            'singular'  => __( 'supplier_product', 'mylisttable' ),     //singular name of the listed records
	            'plural'    => __( 'supplier_products', 'mylisttable' ),   //plural name of the listed records
	            'ajax'      => true        //does this table support ajax?
	    ) );
	    add_action( 'admin_head', array( &$this, 'supplier_product_admin_header' ) );            
	    }

	    function get_purchase_orders(){
	    	$s=$_POST['s'];
	    	$supplier_id=$_POST['supplier'];
	    	global $wpdb;
	    	$width =$_POST['width'];
		    $width=str_replace(".","-",$width);
		    $ratio = $_POST['ratio'];
		    $diameter =$_POST['diameter'];
		    $name = $_POST['category'];

		    $vehicle_type = $_POST['vehicle_type'];
		    $visiblity = 'yes';
		    $name = strtolower($name);

		    $start_date=$_POST['start_date'];
		    $end_date=$_POST['end_date'];

		   


	  $SQL="SELECT   sp.* FROM th_suuplier_product_order AS sp ";

	  $SQL.=" LEFT JOIN wp_posts ON ( wp_posts.ID = sp.product_id )";

	  if($width){
	 		$SQL.=" LEFT JOIN wp_postmeta ON ( wp_posts.ID = wp_postmeta.post_id ) ";
		}
	  if($diameter){
	  $SQL.=" LEFT JOIN wp_postmeta AS mt1 ON ( wp_posts.ID = mt1.post_id )";
	  }
	  
	  if($ratio){  
	  $SQL.=" LEFT JOIN wp_postmeta AS mt2 ON ( wp_posts.ID = mt2.post_id )";
	  } 
	 
	 if($name){
	  	$SQL.=" LEFT JOIN wp_postmeta AS mt3 ON ( wp_posts.ID = mt3.post_id )";
	 } 

	  if($vehicle_type){
	  	$SQL.=" LEFT JOIN wp_postmeta AS mt5 ON ( wp_posts.ID = mt5.post_id )";
	 } 

	 $SQL.=" LEFT JOIN wp_postmeta AS mt4 ON ( wp_posts.ID = mt4.post_id )"; 

	 

	 //$SQL.=" LEFT JOIN th_supplier_data AS sd ON (sp.supplier_id = wp_postmeta.meta_key = 'active_supplier' AND wp_postmeta.meta_value IN ('".$width."'))"; 

		   
	$WHERE=" WHERE 1=1 "; 
	
	

	if($width){ 
		$WHERE.=" AND ( wp_postmeta.meta_key = 'attribute_pa_width' AND wp_postmeta.meta_value IN ('".$width."') )";
		}

	if($diameter){
	$WHERE.=" AND ( mt1.meta_key = 'attribute_pa_diameter' AND mt1.meta_value IN ('".$diameter."') )";
	}

    if($ratio){ 
    	$WHERE.=" AND ( mt2.meta_key = 'attribute_pa_ratio' AND mt2.meta_value IN ('".$ratio."') ) ";
    }
    
    if($name){
    	$WHERE.=" AND ( mt3.meta_key = 'attribute_pa_brand' AND mt3.meta_value IN ('".$name."') )";
    }

    if($vehicle_type){
    	$WHERE.=" AND ( mt5.meta_key = 'attribute_pa_vehicle-type' AND mt5.meta_value IN ('".$vehicle_type."') )";
    }

    $WHERE.=" AND ( mt4.meta_key = 'tyrehub_visible' AND mt4.meta_value IN ('yes','contact-us')) ";
     
 	if($supplier_id){
	 $WHERE.=" AND sp.supplier_id =".$supplier_id;
	}
	if($start_date!='' && $end_date!=''){
	 $WHERE.=" AND  (sp.update_date BETWEEN '".$start_date."' AND '".$end_date."') ";
	}

    $WHERE.=" AND wp_posts.post_type = 'product_variation' AND (wp_posts.post_status = 'publish' OR wp_posts.post_status = 'wc-deltoinstaller' OR wp_posts.post_status = 'wc-customprocess' OR wp_posts.post_status = 'future' OR wp_posts.post_status = 'draft' OR wp_posts.post_status = 'pending' OR wp_posts.post_status = 'private') GROUP BY wp_posts.ID ORDER BY sp.id desc";

   $SQL=$SQL.$WHERE;

    $supplierProductData=$wpdb->get_results($SQL);

	   		$suppliers_products=array();
			foreach ($supplierProductData as $key => $value)
			{
				
				$SQL="SELECT business_name FROM th_supplier_data WHERE supplier_data_id='".$value->supplier_id."'";
				$supplier=$wpdb->get_row($SQL);
				//$product_variation = new WC_Product_Variation($value->product_id);
				$tyre_type=get_post_meta($value->product_id,'attribute_pa_tyre-type',true);				
				$user_info = get_userdata($value->user_id);
				$suppliers_products[$key]['ID'] = $value->id;
				$suppliers_products[$key]['order_id']= '<a href="post.php?post='.$value->order_id.'&action=edit" target="_blank">#'.$value->order_id.'</a>';
				$suppliers_products[$key]['product_id']= $value->product_id;
				$suppliers_products[$key]['product']= get_post_meta($value->product_id,'_variation_description',true);
				$suppliers_products[$key]['user']= $user_info->user_login;
				$suppliers_products[$key]['supplier']= $supplier->business_name;
				//$suppliers_products[$key]['tube_price']= $value->old_tube_price;
				if($tyre_type == 'tubetyre')
	        	{
					$suppliers_products[$key]['tube_price']= wc_price($value->tube_price);			
				}
				else{
					$suppliers_products[$key]['tube_price']= '-';
				}

				$suppliers_products[$key]['tyre_price']=wc_price($value->tyre_price);
				$suppliers_products[$key]['mrp_price']= wc_price($value->mrp);
				$suppliers_products[$key]['total_price']= wc_price($value->tyre_price+$value->tube_price);
	      		$suppliers_products[$key]['updated_date']= $value->update_date;
				

				
			}
			
			return $suppliers_products;
			die;
	    }

		
	  

	  function supplier_product_admin_header() {
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
	    _e( 'No purchase orders found, dude.' );
	  }
	  function column_default( $item, $column_name ) {
	    switch( $column_name ) { 
	        case 'ID':
	        case 'order_id':
	        case 'product_id':
	        case 'supplier':
	        case 'product':
	        case 'tube_price':
	        case 'tyre_price':
	        case 'mrp_price':
	        case 'total_price':
	        case 'updated_date':
	            return $item[ $column_name ];
	        default:
	            return print_r( $item, true ) ; //Show the whole array for troubleshooting purposes
	    }
	  }
	function get_sortable_columns() {
	  $sortable_columns = array(
	    'order_id'  => array('order_id',false),
	    'product_id'  => array('product_id',false),
	    'product'  => array('product',false),
	    'supplier'  => array('supplier',false),
	    'tube_price'   => array('tube_price',false),
	    'tyre_price'   => array('tyre_price',false),
	    'mrp_price'   => array('mrp_price',false),
	    'total_price'   => array('total_price',false),
	    'updated_date'   => array('updated_date',false)
	  );
	  return $sortable_columns;
	}
	function get_columns(){
	        $columns = array(
	            'cb'        => '<input type="checkbox" />',
	            'order_id' => __( 'Order', 'mylisttable' ),
	            'product_id' => __( 'Product ID', 'mylisttable' ),
	            'supplier' => __( 'Supplier', 'mylisttable' ),
	            'product' => __( 'Product', 'mylisttable' ),
	            'tube_price'      => __('Tube Price', 'mylisttable' ),
	            'tyre_price'      => __('Tyre Price', 'mylisttable' ),
	            'mrp_price'      => __( 'MRP', 'mylisttable' ),
	            'total_price'      => __('Total', 'mylisttable' ),
	            'updated_date'      => __( 'Updated Date', 'mylisttable' ),
	        );
	         return $columns;
	    }
	function usort_reorder( $a, $b ) {
	  // If no sort, default to title
	  $orderby = ( ! empty( $_GET['orderby'] ) ) ? $_GET['orderby'] : 'status';
	  // If no order, default to asc
	  $order = ( ! empty($_GET['order'] ) ) ? $_GET['order'] : 'asc';
	  // Determine sort order
	  $result = strcmp( $a[$orderby], $b[$orderby] );
	  // Send final sort direction to usort
	  return ( $order === 'asc' ) ? $result : -$result;
	}
	function column_product_id1($item){
	  $actions = array(
	            'edit'=> sprintf('<a href="?page=%s&action=%s&supp_data_id=%s">Edit</a>','supplier-per-change','edit',$item['ID']),
	            //'delete'    => sprintf('<a href="?page=%s&action=%s&installer_id=%s">Delete</a>',$_REQUEST['page'],'delete',$item['ID']),
	        );
	  //return sprintf('%1$s %2$s', $item['product_id'], $this->row_actions($actions) );
	}
	function get_bulk_actions() {
	  $actions = array(
	    'assign'    => 'Assign'
	  );
	  //return $actions;
	}

	function process_bulk_action() {
		
	}

	function column_cb($item) {
	        return sprintf(
	            '<input type="checkbox" name="porders[]" value="%s" />', $item['ID']
	        );    
	    }

	function prepare_items() {
		
	  $columns  = $this->get_columns();
	  $hidden   = array();
	  $sortable = $this->get_sortable_columns();
	  $this->_column_headers = array( $columns, $hidden, $sortable );
	  //$this->process_bulk_action();
	  $data=$this->get_purchase_orders();
	 if($_GET['orderby']){
	 usort($data, array( &$this, 'usort_reorder' ) );	
	 }
	  //usort($data, array( &$this, 'usort_reorder' ) );
	  
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



function purchase_orders_fun(){
  
  $PUOrderstListTable = new Purchase_orders_List_Table();
  $supplier_id=$_REQUEST['supplier_id'];
  $res=get_supplier_data($supplier_id);
  ?>  
  <div class="wrap"><h2>Purchase Orders 
 	<div class="admin-url" hidden=""><?php echo admin_url('admin-ajax.php'); ?></div></h2> 
 <div style="margin-top: 10px;">
	<!--  <a href="?page=supplier-add-new" class="page-title-action">Add New Supplier</a>
	 <a href="?page=supplier-manage" class="page-title-action">All Supplier</a>
  <a href="?page=supplier-product-price-change-list" class="page-title-action">All Price Request</a>
  <a href="?page=supplier-product-price-change-list&status=2" class="page-title-action">All Price Pending Request</a>
  <a href="?page=change_price_log" class="page-title-action">Price Changes Logs</a>
  <a href="?page=supplier-change-price-log" class="page-title-action">Sync Price Logs</a>
  <a href="?page=supplier-assigned-products&supplier_id=<?php echo $_REQUEST['supplier_id']; ?>" class="page-title-action">Assigned Products</a> -->
</div>
 <!-- <div style="margin-top:10px;"><b>Supplier : <?=$res[0]->business_name;?></b> </div> -->
 
  <form method="post">
    <input type="hidden" name="page" value="<?php echo $_REQUEST['page'] ?>">
    <input type="hidden" name="supplier_id" value="<?php echo $_REQUEST['supplier_id'] ?>">
    <div class="prd-search" style="margin-top: 20px;">
            <strong>Select Options</strong>
            <strong>Width</strong><input type="text" name="width" class="select-width" value="<?=$_POST['width'];?>">
            <strong>Ration</strong><input type="text" name="ratio" class="select-ratio" value="<?=$_POST['ratio'];?>">
            <strong>Diameter</strong><input type="text" name="diameter" class="select-diameter" value="<?=$_POST['diameter'];?>">
            <select class="select-category" name="category">
                <option value="">Select Brand</option>
                <?php 
                $taxonomy = 'product_cat';
                $terms=  get_terms($taxonomy);

                $terms_select="";
                foreach ($terms as $term)
                {?>
                    <option value="<?=$term->name;?>" <?php if($term->name==$_POST['category']){ echo 'selected';}?>><?=$term->name;?></option>
               
                <?php 
            	}
                ?>
            </select>
            <select class="select-category" name="vehicle_type">
                <option value="">Vehicle Type</option>
                
                    <option value="car-tyre" <?php if($_POST['vehicle_type']=='car-tyre'){ echo 'selected';}?>>Four Wheeler</option>
                    <option value="two-wheeler" <?php if($_POST['vehicle_type']=='two-wheeler'){ echo 'selected';}?>>Two Wheeler</option>
                    <option value="three-wheeler" <?php if($_POST['vehicle_type']=='three-wheeler'){ echo 'selected';}?>>Three Wheeler</option>
               
               
            </select>
            <select class="select-category" name="supplier">
                <option value="">Select Supplier</option>
                
                    <?php 
                    global $wpdb;
                	$SQL="SELECT * FROM th_supplier_data";
                	$suppliers=$wpdb->get_results($SQL);

                foreach ($suppliers as $supplier)
                {?>
                    <option value="<?=$supplier->supplier_data_id;?>" <?php if($supplier->supplier_data_id==$_POST['supplier']){ echo 'selected';}?>><?=$supplier->business_name;?></option>
               
                <?php 
            	}
                ?>
               
               
            </select>
            <input type="date" name="start_date" class="start-date1" autocomplete="off" placeholder="Start Date" value="<?=$_POST['start_date']?>">
          <input type="date" name="end_date" class="end-date1" autocomplete="off" placeholder="End Date" value="<?=$_POST['end_date']?>">
            <button class="search-btn" id="csv_prodct_search">Search</button>            
        <div class="message-block" style="width: 100%; float: left;"></div>
     </div>
    <?php $PUOrderstListTable->prepare_items();?>
    <?php
    //$suppliersProductListTable->search_box( 'search', 'search_id' );
    $PUOrderstListTable->display(); 
    echo '</form></div>';
}



?>