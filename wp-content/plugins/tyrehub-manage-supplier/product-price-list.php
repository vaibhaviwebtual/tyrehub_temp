<?php

class suppliers_product_List_Table extends WP_List_Table {

	function __construct(){
	    global  $page;
	        parent::__construct( array(
	            'singular'  => __( 'supplier_product', 'mylisttable' ),     //singular name of the listed records
	            'plural'    => __( 'supplier_products', 'mylisttable' ),   //plural name of the listed records
	            'ajax'      => true        //does this table support ajax?
	    ) );
	    add_action( 'admin_head', array( &$this, 'supplier_product_admin_header' ) );            
	    }

	    function get_active_product_details(){
	    	global $wpdb;
	    	$product_id=$_GET['product_id'];
	    	$supplier_id=get_post_meta($product_id,'active_supplier',true);
	    	$product=get_post_meta($product_id,'_variation_description',true);

	    	$SQL="SELECT * FROM th_supplier_products_final spf LEFT JOIN th_supplier_data as sd ON sd.supplier_data_id=spf.supplier_id WHERE spf.product_id='".$product_id."' AND spf.supplier_id='".$supplier_id."'";

	    	$PriceDetails=$wpdb->get_row($SQL);
	    	$PriceDetails->supplier=$PriceDetails->business_name;
	    	$PriceDetails->product=$product;
	    	$PriceDetails->tube_price=wc_price($PriceDetails->tube_price);
	    	$PriceDetails->tyre_price=wc_price($PriceDetails->tyre_price);
	    	$PriceDetails->flat_percentage=$PriceDetails->flat_percentage;
	    	$PriceDetails->margin_price=wc_price($PriceDetails->margin_price);
	    	$PriceDetails->mrp=wc_price($PriceDetails->mrp);
	    	$PriceDetails->total_price=wc_price($PriceDetails->total_price);
	    	return $PriceDetails;
	    }

	    function get_suppliers_product(){

	    	$s=$_POST['s'];
	    	$status=$_GET['status'];
	    	if($status){
			  	$status=$status;
			  }else{
			  	$status=2;
			  }
	    	$product_id=$_GET['product_id'];
	    	
	    	global $wpdb;

	    	$width = '';
		    $width=str_replace(".","-",$width);
		    $ratio = '';
		    $diameter ='';
		    $name = '';
		    $visiblity = 'yes';
		    $name = strtolower($name);

	  $SQL="SELECT   sp.*,sd.business_name FROM wp_posts";
	  if($width){
	 		$SQL.=" INNER JOIN wp_postmeta ON ( wp_posts.ID = wp_postmeta.post_id ) ";
		}
	  if($diameter){
	  $SQL.=" INNER JOIN wp_postmeta AS mt1 ON ( wp_posts.ID = mt1.post_id )";
	  }
	  
	  if($ratio){  
	  $SQL.=" INNER JOIN wp_postmeta AS mt2 ON ( wp_posts.ID = mt2.post_id )";
	  } 
	 
	 if($name){
	  	$SQL.=" INNER JOIN wp_postmeta AS mt3 ON ( wp_posts.ID = mt3.post_id )";
	 } 

	 $SQL.=" INNER JOIN wp_postmeta AS mt4 ON ( wp_posts.ID = mt4.post_id )"; 

	 $SQL.=" INNER JOIN th_supplier_products_list AS sp ON ( wp_posts.ID = sp.product_id )";

	 $SQL.=" LEFT JOIN th_supplier_data AS sd ON (sp.supplier_id = sd.supplier_data_id)"; 
	   
	$WHERE="WHERE 1=1 "; 
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

    $WHERE.=" AND ( mt4.meta_key = 'tyrehub_visible' AND mt4.meta_value IN ('yes','contact-us')) ";
    
    if($status){
   	$WHERE.=" AND sp.status='".$status."' AND sp.common_status='".$status."'";
	}
	 if($product_id){
	 	$WHERE.=" AND sp.product_id='".$product_id."'";
	 }

    $WHERE.=" AND wp_posts.post_type = 'product_variation' AND (wp_posts.post_status = 'publish' OR wp_posts.post_status = 'wc-deltoinstaller' OR wp_posts.post_status = 'wc-customprocess' OR wp_posts.post_status = 'future' OR wp_posts.post_status = 'draft' OR wp_posts.post_status = 'pending' OR wp_posts.post_status = 'private')  ORDER BY sp.updated_date DESC";

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
				$suppliers_products[$key]['margin_price']= '<a href="'.get_admin_url().'/admin.php?page=supplier-per-change&action=edit&supp_data_id='.$value->id.'" class="woocommerce-save-button add-installer-btn">'.wc_price($value->margin_price).'</a>';
			
			if($value->status==1){
				$suppliers_products[$key]['status']= '<button type="button" class="button-primary woocommerce-save-button add-installer-btn"><b>Accept</b></button>';
			}elseif($value->status==2){
				$suppliers_products[$key]['status']= '<a href="'.get_admin_url().'/admin.php?page=supplier-per-change&action=edit&supp_data_id='.$value->id.'" class="woocommerce-save-button add-installer-btn"><button type="button" class="button-primary woocommerce-save-button add-installer-btn">Pending</button></a>';

				//<a href="'.get_admin_url().'/admin.php?page=supplier-per-change&action=edit&supp_data_id='.$value->id.'" class="woocommerce-save-button add-installer-btn"><button type="button" class="button-primary woocommerce-save-button add-installer-btn">Pending</button></a>
			}elseif($value->status==3){
				$suppliers_products[$key]['status']= '<b>Auto</b>';	
			}elseif($value->status==7){
				$suppliers_products[$key]['status']= '<button type="button" class="button-primary woocommerce-save-button add-installer-btn"><b>N/A</b></button>';	
			}else{
				$suppliers_products[$key]['status']= '<b>Cancel</b>';	
			}
				//$suppliers_products[$key]['status']= $value->status;
	      		$suppliers_products[$key]['updated_date']= $value->updated_date;
				

				
			}
			
			return $suppliers_products;
			die;
	    }

		function supplier_product_approve_by_admin($ids){

				global $wpdb, $woocommerce;				
				$product_id=$_GET['product_id'];
				if($ids){
				$ids=implode(',',$ids);
				/*$SQL="SELECT * FROM  th_supplier_products_list  WHERE  id IN ($ids) AND common_status=2 AND status=2";
				$prod=$wpdb->get_row($SQL);
				$product_id=$prod->product_id;
				$supplier_id=$prod->supplier_id;*/
				$SQL3="SELECT * FROM  th_supplier_products_list  WHERE  id IN ($ids) AND product_id='$product_id' AND common_status=2 AND status=2";
				$products=$wpdb->get_results($SQL3);

				supplier_price_sync_to_product($products,0,$product_id);
				$flag='select';

				}else{					
				$SQL="SELECT * FROM  th_supplier_products_list  WHERE  product_id='$product_id' AND common_status=2 AND status=2";
					$products=$wpdb->get_results($SQL);
		    		supplier_price_sync_to_product($products,0,$product_id);
		    		$flag='all';
				 }

	 			 
			if($flag=='select'){
				wp_redirect(site_url('/wp-admin/admin.php?page=product-price-list&action=edit&product_id='.$product_id));
			}else{
				wp_redirect(site_url('/wp-admin/admin.php?page=supplier-product-price-change-list'));	
			}
					
					
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
	        case 'user':
	        case 'supplier':
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
	    'supplier'   => array('supplier',false),
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
	            /*'product' => __( 'Product', 'mylisttable' ),*/
	            'supplier'      => __( 'Supplier', 'mylisttable' ),
	            'tube_price'      => __('Tube Price', 'mylisttable' ),
	            'tyre_price'      => __('Tyre Price', 'mylisttable' ),
	            'percentage'      => __( 'Per%', 'mylisttable' ),
	            'margin_price'      => __( 'Margin', 'mylisttable' ),
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
	function column_product_id($item){
	  $actions = array(
	            'edit'=> sprintf('<a href="?page=%s&action=%s&supp_data_id=%s">Edit</a>','supplier-per-change','edit',$item['ID']),
	            //'delete'    => sprintf('<a href="?page=%s&action=%s&installer_id=%s">Delete</a>',$_REQUEST['page'],'delete',$item['ID']),
	        );
	  return sprintf('%1$s %2$s', $item['product_id'], $this->row_actions($actions) );
	}
	function get_bulk_actions() {
	  $actions = array(
	    'approve'    => 'Approve'
	  );
	  return $actions;
	}

	function process_bulk_action() {
		
			global $wpdb;
			$delete_ids = esc_sql( $_POST['supplier'] );
			
			if($_POST){
				self::supplier_product_approve_by_admin($delete_ids);	
			}
			//self::supplier_product_approve_by_admin($delete_ids);
		
	}

	function column_cb($item) {
	        return sprintf(
	            '<input type="checkbox" id="supplier" name="supplier[]" value="%s" />', $item['ID']
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



function product_price_list(){
  
  $suppliersProductPriceListTable = new suppliers_product_List_Table();

  $status=$_GET['status'];

  if($status=='pending'){
  $title='Pending';
  }else{
  	$title='Change';
  }
  ?>
  
  <div class="wrap"><h2>Suppliers - Price <?=$title;?> Request
 	<div class="admin-url" hidden=""><?php echo admin_url('admin-ajax.php'); ?></div></h2> 
 <br>
 <div>
 	<style type="text/css">
 		input[type="radio"] {
  -webkit-appearance: checkbox; /* Chrome, Safari, Opera */
  -moz-appearance: checkbox;    /* Firefox */
  -ms-appearance: checkbox;     /* not currently supported */

}
 	</style>
 	<div style="margin-top: 10px;">
 		<a href="?page=supplier-add-new" class="page-title-action">Add New Supplier</a>
 		<a href="?page=supplier-manage" class="page-title-action">All Supplier</a>
 		<a href="?page=supplier-product-price-change-list" class="page-title-action" >Price All List</a> 
 		<a href="?page=change_price_log" class="page-title-action">Price Changes Logs</a>
		
 	</div>
 	<div style="clear: both;"></div>
 	<div style="margin-top: 10px;">
 		<?php 
 		$details=$suppliersProductPriceListTable->get_active_product_details();
 		?>
 		<table class="wp-list-table widefat fixed striped supplier_products">
 		<tr>
 			<th>Supplier</th><th>Product</th><th>Tube</th><th>Tyre</th><th>%</th><th>Margin</th><th>MRP</th><th>Web Price</th>
 		</tr>
 		<tr>
 			<td><?=$details->supplier;?></td><td><?=$details->product;?></td><td><?=$details->tube_price;?></td><td><?=$details->tyre_price;?></td><td><?=$details->flat_percentage;?></td><td><?=$details->margin_price;?></td><td><?=$details->mrp;?></td><td><?=$details->total_price;?></td>
 		</tr>
 	   </table>
 		
		
 	</div>
</div>

  <form method="post" name="" id="price_change_request">
  <style type="text/css">
  	#doaction{
  		border: 0px;color:#fff; 
  		font-style: normal; 
  		text-transform: uppercase; 
  		height: 36px; 
  		margin-bottom: 15px;
  		padding: 0 2rem;
  		line-height: 36px;
  		background-color:#8ac249 !important;
	}

#bulk-action-selector-top{display: none;}
  </style>
    <input type="hidden" name="page" value="<?php echo $_REQUEST['page'] ?>">
    <input type="hidden" id="action" name="action" value="approve">
    <?php $suppliersProductPriceListTable->prepare_items();?>
    <?php
    //$suppliersProductPriceListTable->search_box( 'search', 'search_id' );
    $suppliersProductPriceListTable->display(); ?>
    </form>
</div>
    <script type="text/javascript">
    	jQuery(document).ready(function() {

    		jQuery('#doaction').val('All Price Accept');
    		//jQuery(".bulkactions").append('<input type="submit" class="page-title-action waves-effect waves-light waves-input-wrapper" value="Price Approve" name="price-approve">');

	jQuery("#price_change_request #doaction").click(function (event) {
		var actionselected = jQuery(this).attr("id").substr(2);
		//var action = jQuery('select[name="' + actionselected + '"]').val();
		var action = jQuery('#action').val();
		
		if (action!=-1) {
			event.preventDefault();
			var template = action;
			var checked = [];
			jQuery('tbody th.check-column input[type="checkbox"]:checked').each(
				function() {
					checked.push(jQuery(this).val());
				}
			);
			
			
			if(checked.length>0 && checked.length<2){

				  jQuery("#price_change_request").submit();

			}else{
				/*alert('You have to select only one prduct!');
				return;*/
				jQuery("#price_change_request").submit();
			}

			/*if (!checked.length) {
				alert('You have to select order(s) first!');
				return;
			}*/
			
			var order_ids=checked.join('x');

			/*if (wpo_wcpdf_ajax.ajaxurl.indexOf("?") != -1) {
				url = wpo_wcpdf_ajax.ajaxurl+'&action=generate_wpo_wcpdf&document_type='+template+'&order_ids='+order_ids+'&_wpnonce='+wpo_wcpdf_ajax.nonce;
			} else {
				url = wpo_wcpdf_ajax.ajaxurl+'?action=generate_wpo_wcpdf&document_type='+template+'&order_ids='+order_ids+'&_wpnonce='+wpo_wcpdf_ajax.nonce;
			}

			window.open(url,'_blank');*/
		}else{
			alert('Please selete action!');
			return false;
		}

	});

	
	
		});

		jQuery(document).ready(function() {
	    jQuery('input:checkbox').change(function() {

	    	 	if (jQuery('input[type=checkbox]:checked').length <=0) {
                   jQuery('#doaction').val('All Price Accept');
                }else{
                	 jQuery('#doaction').val('Selected Accept Price');					
                }

	    		       
	    });
	});
		/*jQuery("#supplier").click(function () {
			alert('asdasdasd');
		    alert(jQuery('input:radio[name=supplier]:checked').val());
				
    		});*/
    </script>
<?php }



?>