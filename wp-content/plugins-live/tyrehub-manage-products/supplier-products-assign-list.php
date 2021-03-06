<?php

class suppliers_product_assigned_List_Table extends WP_List_Table {

	function __construct(){
	    global $status, $page;
	        parent::__construct( array(
	            'singular'  => __( 'supplier_product', 'mylisttable' ),     //singular name of the listed records
	            'plural'    => __( 'supplier_products', 'mylisttable' ),   //plural name of the listed records
	            'ajax'      => true        //does this table support ajax?
	    ) );
	    add_action( 'admin_head', array( &$this, 'supplier_product_admin_header' ) );            
	    }

	    function get_suppliers_product(){
    	$s=$_POST['s'];
    	$supplier_id=$_GET['supplier_id'];
    	global $wpdb;
    	$width =$_GET['width'];
	    $width=str_replace(".","-",$width);
	    $ratio = $_GET['ratio'];
	    $diameter =$_GET['diameter'];
	    $name = $_GET['category'];
	    $vehicle_type = $_GET['vehicle_type'];
	    $visiblity = 'yes';
	    $name = strtolower($name);

	  $SQL="SELECT   sp.*,sd.business_name FROM th_supplier_products_list AS sp ";

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

	 
	 

	 $SQL.=" LEFT JOIN th_supplier_data AS sd ON (sp.supplier_id = sd.supplier_data_id)"; 
	   
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
		$WHERE.=" AND (sp.supplier_id =".$supplier_id.")";
		}
    $WHERE.=" AND wp_posts.post_type = 'product_variation' AND (wp_posts.post_status = 'publish' OR wp_posts.post_status = 'wc-deltoinstaller' OR wp_posts.post_status = 'wc-customprocess' OR wp_posts.post_status = 'future' OR wp_posts.post_status = 'draft' OR wp_posts.post_status = 'pending' OR wp_posts.post_status = 'private') GROUP BY wp_posts.ID ORDER BY sp.status desc";

    $SQL=$SQL.$WHERE;

    $supplierProductData=$wpdb->get_results($SQL);

	   		$suppliers_products=array();
			foreach ($supplierProductData as $key => $value)
			{
				//$product_variation = new WC_Product_Variation($value->product_id);
				$tyre_type=get_post_meta($value->product_id,'attribute_pa_tyre-type',true);				
				$user_info = get_userdata($value->user_id);
				$suppliers_products[$key]['ID'] = $value->id;
				$suppliers_products[$key]['product_id']= $value->product_id;
				$suppliers_products[$key]['product']= get_post_meta($value->product_id,'_variation_description',true);
				$suppliers_products[$key]['user']= $user_info->user_login;
				$suppliers_products[$key]['supplier']= $value->business_name;
				//$suppliers_products[$key]['tube_price']= $value->old_tube_price;
				if($tyre_type == 'tubetyre')
	        	{
					if($value->new_tube_price){
					$suppliers_products[$key]['tube_price']= 'New :'.wc_price($value->new_tube_price).'<br>Old :'.wc_price($value->old_tube_price);	
					}else{					
					$suppliers_products[$key]['tube_price']=wc_price($value->old_tube_price);
					}			
				}
				else{
					$suppliers_products[$key]['tube_price']= '-';
				}

				if($value->new_tyre_price){
					$suppliers_products[$key]['tyre_price']= 'New :'.wc_price($value->new_tyre_price).'<br>Old :'.wc_price($value->old_tyre_price);	
				}else{					
				$suppliers_products[$key]['tyre_price']=wc_price($value->old_tyre_price);
				}

				if($value->new_mrp){
					$suppliers_products[$key]['mrp_price']= 'New :'.wc_price($value->new_mrp).'<br>Old :'.wc_price($value->old_mrp);	
				}else{					
				$suppliers_products[$key]['mrp_price']=wc_price($value->old_mrp);
				}

				if($value->new_total_price){
					$suppliers_products[$key]['total_price']= 'New :'.wc_price($value->new_total_price).'<br>Old :'.wc_price($value->old_total_price);	
				}else{					
				$suppliers_products[$key]['total_price']=wc_price($value->old_total_price);
				}

				//$suppliers_products[$key]['tyre_price']= $value->old_tyre_price;
				$suppliers_products[$key]['percentage']= '<a href="'.get_admin_url().'/admin.php?page=supplier-per-change&action=edit&supp_data_id='.$value->id.'" class="woocommerce-save-button add-installer-btn">'.$value->flat_percentage.'</a>';
				$suppliers_products[$key]['margin_price']= '<a href="'.get_admin_url().'/admin.php?page=supplier-per-change&action=edit&supp_data_id='.$value->id.'" class="woocommerce-save-button add-installer-btn">'.$value->margin_price.'</a>';
				
				if($supplier_id==$value->supplier_id && $value->price_approved!=1){
			$suppliers_products[$key]['status']= '<a href="'.get_admin_url().'/admin.php?page=supplier-assigned-products&action=remove_pro&supplier_id='.$supplier_id.'&supp_data_id='.$value->id.'" class="button-secondary woocommerce-save-button add-installer-btn">Remove</a>';	
			}elseif($value->price_approved!=1){
			$suppliers_products[$key]['status']= '<a href="'.get_admin_url().'/admin.php?page=supplier-per-change&action=edit&supp_data_id='.$value->id.'" class="button-primary woocommerce-save-button add-installer-btn">Assign This</a> ';	
			}else{
				$suppliers_products[$key]['status']= '<b>Approved Product</b>';
			}
				//$suppliers_products[$key]['status']= $value->status;
	      		$suppliers_products[$key]['updated_date']= $value->updated_date;
				

				
			}
			
			return $suppliers_products;
			die;
	    }

		function supplier_product_remove_by_admin($id,$supplier_id){

				global $wpdb, $woocommerce;
	 			$supp_pro_id=$id;
 				/*$wpdb->update('th_supplier_products_list',array('supplier_id'=>0,'status'=>1), array('id' =>$supp_pro_id,'supplier_id' =>$supplier_id));*/

 				$wpdb->query('DELETE  FROM th_supplier_products_list WHERE id = "'.$supp_pro_id.'" AND supplier_id="'.$supplier_id.'"' );
					
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
	    _e( 'No supplier found, dude.' );
	  }
	  function column_default( $item, $column_name ) {
	    switch( $column_name ) { 
	        case 'ID':
	        case 'product_id':
	        case 'product':
	        case 'tube_price':
	        case 'tyre_price':
	        case 'percentage':
	        case 'margin_price':
	        case 'mrp_price':
	        case 'total_price':
	        case 'status':
	        case 'updated_date':
	            return $item[ $column_name ];
	        default:
	            return print_r( $item, true ) ; //Show the whole array for troubleshooting purposes
	    }
	  }
	function get_sortable_columns() {
	  $sortable_columns = array(
	    'product_id'  => array('product_id',false),
	    'product'  => array('product',false),
	    'tube_price'   => array('tube_price',false),
	    'tyre_price'   => array('tyre_price',false),
	    'percentage'   => array('percentage',false),
	    'margin_price'   => array('margin_price',false),
	    'mrp_price'   => array('mrp_price',false),
	    'total_price'   => array('total_price',false),
	    'status'   => array('status',false),
	    'updated_date'   => array('updated_date',false)
	  );
	  return $sortable_columns;
	}
	function get_columns(){
	        $columns = array(
	            'cb'        => '<input type="checkbox" />',
	            'product_id' => __( 'Product ID', 'mylisttable' ),
	            'product' => __( 'Product', 'mylisttable' ),
	            'tube_price'      => __('Tube Price', 'mylisttable' ),
	            'tyre_price'      => __('Tyre Price', 'mylisttable' ),
	            'percentage'      => __('Percentage', 'mylisttable' ),
	            'margin_price'      => __('Margin Price', 'mylisttable' ),
	            'mrp_price'      => __( 'MRP', 'mylisttable' ),
	            'total_price'      => __('Total', 'mylisttable' ),
	            'status'      => __( 'Status', 'mylisttable' ),
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
	    'remove'    => 'Remove Assigned Product'
	  );
	  return $actions;
	}

	function process_bulk_action() {
		
		//Detect when a bulk action is being triggered...
		if ('remove' === $this->current_action() ) {
			
			// In our file that handles the request, verify the nonce.
			$nonce = esc_attr( $_REQUEST['_wpnonce'] );

			$delete_ids = esc_sql( $_POST['supplier'] );
			$supplier_id=$_POST['supplier_id'];
			// loop over the array of record IDs and delete them
			foreach ( $delete_ids as $id ) {
				self::supplier_product_remove_by_admin( $id,$supplier_id );


			}

		}

		if ('remove_pro' === $this->current_action() ) {			
			// In our file that handles the request, verify the nonce.
			$nonce = esc_attr( $_REQUEST['_wpnonce'] );
			$id = $_GET['supp_data_id'];
			$supplier_id=$_GET['supplier_id'];
			self::supplier_product_remove_by_admin($id,$supplier_id);
		}

		

		
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
	  $data=$this->get_suppliers_product();
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



function supplier_assigned_products_list(){
  
  $suppliersProductAssignListTable = new suppliers_product_assigned_List_Table();

  $supplier_id=$_REQUEST['supplier_id'];
  $res=get_supplier_data($supplier_id);

  ?>
  
  <div class="wrap"><h2>Suppliers Product Assign List 
 	<div class="admin-url" hidden=""><?php echo admin_url('admin-ajax.php'); ?></div></h2> 
 	 <div style="margin-top: 10px;">
	 
  <a href="?page=supplier-product-add&supplier_id=<?php echo $_REQUEST['supplier_id']; ?>" class="page-title-action">Back to products list</a>
  <a href="?page=supplier-add-new" class="page-title-action">Add New Supplier</a>
  <a href="?page=supplier-manage" class="page-title-action">All Supplier</a>
  <a href="?page=supplier-product-price-change-list" class="page-title-action">All Price Request</a>
  <a href="?page=supplier-product-price-change-list&status=2" class="page-title-action">All Price Pending Request</a>
  <a href="?page=change_price_log" class="page-title-action">Price Changes Logs</a>
	 
	</div>
	 <div style="margin-top:10px;"><b>Supplier : <?=$res[0]->business_name;?></b> </div>
	 
  <form method="get">
    <input type="hidden" name="page" value="<?php echo $_REQUEST['page'] ?>">
    <input type="hidden" name="supplier_id" value="<?php echo $_REQUEST['supplier_id'];?>">
    <div class="prd-search" style="margin-top: 20px;">
            <strong>Select Options</strong>
            <strong>Width</strong><input type="text" name="width" class="select-width" value="<?=$_GET['width'];?>">
            <strong>Ration</strong><input type="text" name="ratio" class="select-ratio" value="<?=$_GET['ratio'];?>">
            <strong>Diameter</strong><input type="text" name="diameter" class="select-diameter" value="<?=$_GET['diameter'];?>">
            <select class="select-category" name="category">
                <option value="">Select Category</option>
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
            <button class="search-btn" id="csv_prodct_search">Search</button>          
        <div class="message-block" style="width: 100%; float: left;"></div>
        </div>
    <?php $suppliersProductAssignListTable->prepare_items();?>
    <?php
    //$suppliersProductAssignListTable->search_box( 'search', 'search_id' );
    $suppliersProductAssignListTable->display(); 
    echo '</form></div>';
}


?>