<?php 

if( ! class_exists( 'WP_List_Table' ) ) {
    require_once( ABSPATH . 'wp-admin/includes/class-wp-list-table.php' );
}

class Supplier_change_price_log_Table extends WP_List_Table {
	function get_supplier_change_price_log(){
   	$s=$_POST['s'];
	global $woocommerce , $wpdb;

    	$product_name = $_POST['product_name'];
    	$months = $_POST['months'];
    	$brand = $_POST['brand_name'];
	
	
	    $sql = "SELECT DISTINCT ppl.*,sd.business_name FROM th_supplier_products_final_log as ppl";

	    $sql .= " LEFT JOIN th_supplier_data as sd  on sd.supplier_data_id = ppl.supplier_id";

	    //$sql .= " LEFT JOIN th_supplier_products as sp  on sp.id = ppl.supp_pro_id";

	    if($s!='' || $brand!=''){
	    	$sql.=" INNER JOIN wp_postmeta as mt1  on ppl.product_id = mt1.post_id";	

		}

		$sqlwhere=' WHERE 1=1';
		if($months!='' && $months!='all'){	
   			$sqlwhere.="  AND ppl.`updated_date`>= DATE_FORMAT(CURRENT_DATE - INTERVAL $months MONTH, '%Y-%m-%d' )";
		}elseif($months=='all'){
			
		}else{
			$sqlwhere.=" AND ppl.`updated_date`>= DATE_FORMAT(CURRENT_DATE - INTERVAL 1 MONTH, '%Y-%m-%d' )";
		}
		 
		 if($product_name!=''){
		 	$sqlwhere.=" AND ppl.`product_id`='".$product_name."' ";
		 }

		 if($brand!=''){
			$sqlwhere.=" AND (mt1.meta_key='attribute_pa_brand' AND mt1.meta_value='".$brand."')";
		 }

		 if($s){
		 $sqlwhere.=" AND mt1.meta_value LIKE '%".$s."%'";	
		 }

	    $sql.=$sqlwhere;
	    $sql.=" order by ppl.updated_date desc";

	    //echo $sql;
	    $price_log_data = $wpdb->get_results($sql);


	    $price_args = array(
	                    'ex_tax_label'       => false,
	                    'currency'           => '',
	                    'decimal_separator'  => wc_get_price_decimal_separator(),
	                    'thousand_separator' => wc_get_price_thousand_separator(),
	                    'decimals'           => wc_get_price_decimals(),
	                    'price_format'       => get_woocommerce_price_format(),
	                  );

   		$price_log = array();
		foreach ($price_log_data as $key => $value)
		{
			$price_log[$key]['id'] = $value->id;
			$product_id = $value->product_id;

			$product_variation = wc_get_product( $product_id );
	        $variation_data = $product_variation->get_data();
	        $tyre_type = $variation_data['attributes']['pa_tyre-type'];
	        $variation_des = $product_variation->get_description();

	        $price_log[$key]['product_id'] = $product_id;
	        $price_log[$key]['supplier'] =$value->business_name;
	        $price_log[$key]['product']= $variation_des;

	        if($tyre_type == 'tubetyre')
        	{
				//wc_price( $log_data->old_price, $price_args );
				if($value->new_tube_price>0){
				$price_log[$key]['tube_price']= wc_price( $value->new_tube_price, $price_args );
				$tube_price	=$value->tube_price;
				}else{				
				$price_log[$key]['tube_price']=wc_price(get_post_meta($product_id , 'tube_price' , true), $price_args );
				$tube_price	=$tube_price;
				}
			
			}
			else{
				$price_log[$key]['tube_price']= '-';
			}

			$tyre_price = $price_log[$key]['tyre_price']= $value->tyre_price;
			if($value->tyre_price==0){
			$price_log[$key]['tyre_price']=wc_price( $tyre_price, $price_args );
			$tyre_price=$old_tyre_price;	
			}else{
				$price_log[$key]['tyre_price']= wc_price($value->tyre_price, $price_args );
				$tyre_price=$value->tyre_price;
			}

			$price_log[$key]['percentage']=wc_price($value->flat_percentage, $price_args );	
			$price_log[$key]['margin']=wc_price($value->margin_price, $price_args );


			


			$price_log[$key]['sale_price']=wc_price($value->total_price, $price_args );
			$price_log[$key]['mrp']=wc_price($value->mrp, $price_args );
			

			$price_log[$key]['new_price']= $value->new_price;
			//$price_log[$key]['date']= $value->updated_date;	

			$userdata = get_user_by( 'ID', $value->user_id );
			$approve_by=$userdata->user_login;

			if($value->status==1){
				$status='<span  class="approved">A</span>'; //Approved
			}elseif($value->status==2){
				$status='<span class="pending">P</span>'; // Pending
			}elseif($value->status==3){
				$status='<span class="auto">AA</span>';    //Auto Approve
			}elseif($value->status==4){
				$status='<span class="cancel">C</span>';   // Cancel
			}else{
				$status='';   // Cancel	
			}

			$price_log[$key]['user_id']= $approve_by.'('.date('d-m-Y',strtotime($value->updated_date)).') ';	

		}
		return $price_log;
		die;
    }

    function column_default( $item, $column_name ) {
	    switch( $column_name ) { 
	    	//case 'log_id':
	        case 'product_id':
	        case 'supplier':
	        case 'product':
	        case 'tube_price':
	        case 'tyre_price':
	        case 'percentage':
	        case 'margin':
	        case 'sale_price':
	        case 'mrp':
	        case 'user_id':
	            return $item[ $column_name ];
	        default:
	            return print_r( $item, true ) ; //Show the whole array for troubleshooting purposes
	    }
  	}

    function get_columns(){
        $columns = array(
           	'product_id' => __( 'Product ID', 'mylisttable' ),
            'product' =>  __( 'Product Name', 'mylisttable' ),
            'supplier' =>  __( 'Supplier', 'mylisttable' ),
            'tube_price' => __( 'Tube Price', 'mylisttable' ),
            'tyre_price' => __( 'Tyre Price', 'mylisttable' ),
            'percentage' => __( 'Per(%)', 'mylisttable' ),
            'margin' => __( 'Margin', 'mylisttable' ),
            'sale_price' => __( 'Sale Price', 'mylisttable' ),
            'mrp' => __( 'M.R.P. Price', 'mylisttable' ),
            'user_id' => __( 'Approve By', 'mylisttable' ),
        );
         return $columns;
    }

    function prepare_items() {
	  $columns  = $this->get_columns();
	  $hidden   = array();
	  $sortable = $this->get_sortable_columns();
	  $this->_column_headers = array( $columns, $hidden, $sortable );
	  $data=$this->get_supplier_change_price_log();
	 
	 // usort($data, array( &$this, 'usort_reorder' ) );
	  
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
function supplier_change_price_log(){

	global $priceLogTable;

	$priceLogTable = new Supplier_change_price_log_Table();
	global $woocommerce , $wpdb;
    $log_sql = $wpdb->get_results("SELECT DISTINCT product_id from th_supplier_products_final_log order by id desc");
    foreach ($log_sql as $key => $value) {
    	# code...
    	$product_arr[]=$value->product_id;
    }

	$args = array(
                    'post__in' => $product_arr,
                    'post_type' => 'product_variation',
                    'posts_per_page' => -1,
                    'numberposts'   => -1,
                    'orderby'       => 'menu_order',
                    'order'         => 'asc',
                );
		$variations = get_posts($args);
		$brands = get_terms('pa_brand',$get_terms_args);

?>
	<style type="text/css">
		.column-user_id span {
			display: block;
			background-color: #1c650f;
			width: 32px;
			height: 32px;
			line-height: 32px;
			text-align: center;
			border-radius: 50px;
			color: #fff;
		}
		.column-user_id span.pending {
			background-color: #ffd642;
		}
		.column-user_id span.auto {
			background-color: #3e4796;
		}
		.column-user_id span.cancel {
			background-color: #8B0000;
		}
	</style>
	<div class="wrap">      
		

       	<div id="icon-users" class="icon32"><br/></div>
       	<div class="admin-url" hidden=""><?php echo admin_url('admin-ajax.php'); ?></div>
       	<div class="product_id" id="search_proid" hidden=""><?php echo $_POST['product_name']; ?></div>
		<h1 class="wp-heading-inline">Sync Price Logs</h1>		
		
		 <div style="margin-top: 10px;">
		 <a href="?page=supplier-add-new" class="page-title-action">Add New Supplier</a>
		  <a href="?page=supplier-product-price-change-list" class="page-title-action">Supplier Price Request</a>
		  <a href="?page=supplier-product-price-change-list&status=2" class="page-title-action">Supplier Price Request Pending</a>
		  <a href="?page=change_price_log" class="page-title-action">Price Changes Logs</a>
		  <a href="?page=supplier-change-price-log" class="page-title-action">Sync Price Logs</a>
		  <a href="?page=supplier-assigned-products&supplier_id=<?php echo $_REQUEST['supplier_id']; ?>" class="page-title-action">Supplier Assigned Products List</a>
		</div>

		<?php $priceLogTable->prepare_items(); ?>
		<form method="post">
			<select name="brand_name" id="brand_name">
				<option value="">--Choose Brand--</option>
				
				<?php 
				foreach ($brands as $brand ) 
		        {
		            
		            if($_POST['brand_name']==$brand->slug){
		            	$selected='selected="selected"';
		            }else{
		            	$selected='';
		            }
		            echo '<option value="'.$brand->slug.'" '.$selected.'>'.$brand->name.'</option>';
		        }
				?>
			</select>
			
			<select name="product_name" id="product_name">
				<option value="">Select Product</option>
				<?php 
				foreach ( $variations as $variation ) 
		        {
		            $variation_ID = $variation->ID;
		            $product_variation = wc_get_product( $variation_ID );
		            $variation_des = $product_variation->get_description();
		            if($_POST['product_name']==$variation_ID){
		            	$selected='selected="selected"';
		            }else{
		            	$selected='';
		            }
		            echo '<option value="'.$variation_ID.'" '.$selected.'>'.$variation_des.'</option>';
		        }
				?>

			</select>
			<select name="months" id="months">
				<option value="all" <?php if($_POST['months']=='all'){ echo "selected";}?>>All</option>
				<option value="1" <?php if($_POST['months']=='1' || $_POST['months']==''){ echo "selected";}?>>Last 1 Month</option>
				<option value="3" <?php if($_POST['months']=='3'){ echo "selected";}?>>Last 3 Months</option>
				<option value="6" <?php if($_POST['months']=='6'){ echo "selected";}?>>Last 6 Months</option>
				<option value="12" <?php if($_POST['months']=='12'){ echo "selected";}?>>Last 1 Year</option>

			</select>
			<input type="submit" name="apply" value="Apply">
	    	<input type="hidden" name="page" value="installer-manage">
		    <?php
			    $priceLogTable->search_box( 'search', 'search_id' );
			    $priceLogTable->display();
			?> 
		</form>

		<div class="price-change-log">
          
            </div>
        </div>
        
        <script type="text/javascript">
        	// A $( document ).ready() block.
			$( document ).ready(function() {
			    $( "#brand_name" ).trigger( "change" );
			});
        	
        </script>
    
	<?php
}

add_action('wp_ajax_search_prd_for_final_log', 'search_prd_for_final_log');
add_action('wp_ajax_nopriv_search_prd_for_final_log', 'search_prd_for_final_log');
function search_prd_for_final_log()
{
	$name = $_POST['name'];
	$name = strtolower($name);
	$months = $_POST['months'];
	global $woocommerce , $wpdb;
	if($months=='all'){
	
    $log_sql = $wpdb->get_results("SELECT * from th_supplier_products_final_log  order by id DESC");
	}else{
    $log_sql = $wpdb->get_results("SELECT * from th_supplier_products_final_log WHERE `updated_date`>= DATE_FORMAT(CURRENT_DATE - INTERVAL $months MONTH, '%Y-%m-%d' ) order by id DESC");	
	}
	 
	foreach ($log_sql as $key => $log_data)
   	{
   		$product_arr[]=$log_data->product_id;
   	}

    if(!empty($product_arr))
    {
        $args = array(
            'post__in' => $product_arr,
            'post_type' => 'product_variation',
            'posts_per_page' => -1,
            'numberposts'   => -1,
            'orderby'       => 'menu_order',
            'order'         => 'asc',
        );
        //$variations = get_posts( $args );


    }

    foreach ($log_sql as $key => $log_data)
   	{
   		$variation_ID = $log_data->product_id;
		$product_variation = wc_get_product( $variation_ID );

		$variation_des = $product_variation->get_description();
		$variation_des = strtolower($variation_des);
		if(strpos($variation_des, $name) !== false)
		{
			?>
			<tr>
				<td>
					<?php
						$variation_ID = $log_data->product_id;
						 $product_variation = wc_get_product( $variation_ID );

						echo $variation_des = $product_variation->get_description();

						$price_args = array(
			                    'ex_tax_label'       => false,
			                    'currency'           => '',
			                    'decimal_separator'  => wc_get_price_decimal_separator(),
			                    'thousand_separator' => wc_get_price_thousand_separator(),
			                    'decimals'           => wc_get_price_decimals(),
			                    'price_format'       => get_woocommerce_price_format(),
			                  );
					?>
				</td>
				<td><?php echo wc_price( $log_data->old_price, $price_args ); ?></td>
				<td><?php echo wc_price( $log_data->new_price, $price_args ); ?></td>
				<td><?php echo $log_data->date; ?></td>
				<td><?php 
					 	$userdata = get_user_by( 'ID', $log_data->user_id );
					 	echo $userdata->user_login;	
					?>
				</td>
			</tr>
			<?php
		}
	}
}
add_action('wp_ajax_supplier_product_name_dropdown', 'supplier_product_name_dropdown');
add_action('wp_ajax_nopriv_supplier_product_name_dropdown', 'supplier_product_name_dropdown');
function supplier_product_name_dropdown()
{
	$name = $_POST['brand_name'];
	$name = strtolower($name);
	global $woocommerce , $wpdb;


	   $months = $_POST['months'];
	
		$sql = "SELECT DISTINCT ppl.product_id FROM th_supplier_products_final_log as ppl";

	    $sql.=" INNER JOIN wp_postmeta as mt1  on ppl.product_id = mt1.post_id";	


		$sqlwhere=' WHERE 1=1';
		
		 
		$sqlwhere.=" AND (mt1.meta_key='attribute_pa_brand' AND mt1.meta_value='".$name."')";

	    $sql.=$sqlwhere;
	    $sql.=" order by ppl.id desc";

	    //echo $sql;
	    $price_log_data = $wpdb->get_results($sql);
	    foreach ($price_log_data as $key => $value) {
	    	# code...
	    		    $product_variation = wc_get_product($value->product_id);
					$variation_des = $product_variation->get_description();
			//echo '<option value="'.$value->product_id.'">'.$variation_des.'</option>';
					$dataArray[$key]['product_id']=$value->product_id;
					$dataArray[$key]['product']=$variation_des;
	    }

	    echo json_encode($dataArray);
	    die;
	}