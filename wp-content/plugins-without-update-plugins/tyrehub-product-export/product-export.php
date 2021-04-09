<?php
/*
Plugin Name: Tyrehub Product CSV Export
Plugin URI: https://webtual.com/
Description: Products csv export.
Version: 1.4.1
Author: Webtual Technologies
Author URI: https://webtual.com/
*/




if( ! class_exists( 'WP_List_Table' ) ) {
    require_once( ABSPATH . 'wp-admin/includes/class-wp-list-table.php' );
}
class Products_CSV_Export_List_Table extends WP_List_Table {
		
		
		function __construct(){
    global $status, $page;
        parent::__construct( array(
            'singular'  => __( 'product', 'mylisttable' ),     //singular name of the listed records
            'plural'    => __( 'products', 'mylisttable' ),   //plural name of the listed records
            'ajax'      => true        //does this table support ajax?
    ) );
    add_action( 'admin_head', array( &$this, 'admin_header' ) );   

    add_action( 'admin_init', array($this, 'generate_csv' ) );         
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

    function get_products(){
    	
    	global $wpdb;

    	$width = $_POST['width'];
	    $ratio = $_POST['ratio'];
	    $diameter = $_POST['diameter'];
	    $name = $_POST['category'];
	    $name = strtolower($name);

    $args = array(
            'post_type' => 'product_variation',
            'posts_per_page' => -1,
            'numberposts'   => -1,
            'orderby'       => 'menu_order',
            'order'         => 'asc',
        );
    if($width){
	    	$meta_query[]=array(
                            'key' => 'attribute_pa_width',
                            'value' => $width,
                            'compare' => 'IN',
                        );
    }
  	if($diameter){
	  	$meta_query[]=array(
                            'key' => 'attribute_pa_diameter',
                            'value' => $diameter,
                            'compare' => 'IN',
                        );	
  	}

  	if($ratio){
  		$meta_query[]=array(
                            'key' => 'attribute_pa_ratio',
                            'value' => $ratio,
                            'compare' => 'IN',
                        );
  	}

  	$meta_query[]=array(
                            'key'       => 'tyrehub_visible',
                            'value'     => array('yes','contact-us'),
                            'compare'   => 'IN',
                        );

  	$args = array(
            'post_type' => 'product_variation',
            'posts_per_page' => -1,
            'numberposts'   => -1,
            'orderby'       => 'menu_order',
            'order'         => 'asc',
            'meta_query'=> array(                       
                        'relation' => 'AND',$meta_query
             ),            
            ); 


    
    	$variations = get_posts( $args );
    	//echo '<pre>';
    	//print_r($variations);
       // $message .= ' Category: '.$name;
        if(!empty($variations))
        {
                  
            foreach ( $variations as $variation ) 
            {
                $variation_ID = $variation->ID;

                $product_variation = new WC_Product_Variation( $variation_ID );

                $variation_des = $product_variation->get_description();
                $variation_des = strtolower($variation_des);
                if($name){
	                if (strpos($variation_des, $name) !== false)
	                {
	                   $product_arr[] = $variation_ID; 
	                }	
                }else{
                	$product_arr[] = $variation_ID;	
                }
                
                
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
                $variations = get_posts( $args );
            }
            else{
               
                $variations = '';
            }        
        }       
            $products=array();
            //echo '<pre>';
    	//print_r($variations);
    foreach ( $variations as $key => $variation ) 
        {
            $variation_ID = $variation->ID;
            //$variation_product_id[] = $variation_ID;
            $product_variation = new WC_Product_Variation( $variation_ID );

          //echo '<pre>';
    		//print_r($product_variation);

            $variation_data = $product_variation->get_data();

            $tyre_type = $variation_data['attributes']['pa_tyre-type'];


            $variation_des = $product_variation->get_description();
            $variation_price = $product_variation->get_price();
            $regular_price = $product_variation->get_regular_price();
            $sale_price = $product_variation->get_sale_price();

            $tyre_price = get_post_meta($variation_ID, 'tyre_price', true );
            $tube_price = get_post_meta($variation_ID, 'tube_price', true );

            $args = array(
                    'ex_tax_label'       => false,
                    'currency'           => '',
                    'decimal_separator'  => wc_get_price_decimal_separator(),
                    'thousand_separator' => wc_get_price_thousand_separator(),
                    'decimals'           => wc_get_price_decimals(),
                    'price_format'       => get_woocommerce_price_format(),
                  );

            
            if($sale_price == '')
            {
                $sale_price = $regular_price;
            }

            @$discount = $regular_price - $sale_price;
            if($discount>0){
              $dis_per = 100 * $discount / $regular_price;

              $dis_per = number_format($dis_per,2,".",".");
            }
            
            $sale_price_original = $sale_price;
            $sale_price = wc_price( $sale_price, $args );
            $regular_price_html = wc_price( $regular_price, $args );

           

    
            $products[$key]['ID'] = $variation_ID;
			$products[$key]['product']=$variation_des;
			$products[$key]['category']=strtoupper($variation_data['attributes']['pa_brand']);
			$products[$key]['tube_price']=  wc_price( $tube_price, $args );
			$products[$key]['tyre_price']= wc_price( $tyre_price, $args );
			$products[$key]['mrp']= $regular_price;
			$products[$key]['web_price']= $sale_price;
			$products[$key]['width']=$variation_data['attributes']['pa_width'];
			$products[$key]['ratio']=$variation_data['attributes']['pa_ratio'];
			$products[$key]['diameter']=$variation_data['attributes']['pa_diameter'];
			
        
    }

		return $products;
		die;


    }
    
  function no_items() {
    _e( 'No product found, dude.' );
  }
  function column_default( $item, $column_name ) {
    switch( $column_name ) { 
        case 'product':
        case 'category':
        case 'tube_price':
        case 'tyre_price':
        case 'mrp':
        case 'web_price':
            return $item[ $column_name ];
        default:
            return print_r( $item, true ) ; //Show the whole array for troubleshooting purposes
    }
  }
function get_sortable_columns() {
  $sortable_columns = array(
    'product'  => array('product',false),
    'category'  => array('category',false),
    'tube_price' => array('tube_price',false),
    'tyre_price'   => array('tyre_price',false),
    'mrp'   => array('mrp',false),
    'web_price'   => array('web_price',false)
    
  );
  return $sortable_columns;
}
function get_columns(){
        $columns = array(
            'cb'        => '<input type="checkbox" />',
            'product' => __( 'Product', 'mylisttable' ),
            'category' => __( 'Category', 'mylisttable' ),
            'tube_price'    => __( 'Tube Price', 'mylisttable' ),
            'tyre_price'      => __( 'Tyre Price', 'mylisttable' ),
            'mrp'      => __( 'MRP', 'mylisttable' ),
            'web_price'      => __( 'Web Price', 'mylisttable' )
        );
         return $columns;
}
function usort_reorder( $a, $b ) {
  // If no sort, default to title
  $orderby = ( ! empty( $_GET['orderby'] ) ) ? $_GET['orderby'] : 'product';
  // If no order, default to asc
  $order = ( ! empty($_GET['order'] ) ) ? $_GET['order'] : 'asc';
  // Determine sort order
  $result = strcmp( $a[$orderby], $b[$orderby] );
  // Send final sort direction to usort
  return ( $order === 'asc' ) ? $result : -$result;
}
function column_product($item){
  /*$actions = array(
            'edit'      => sprintf('<a href="?page=%s&action=%s&installer_id=%s">Edit</a>','installer-add-new','edit',$item['ID']),
            //'delete'    => sprintf('<a href="?page=%s&action=%s&installer_id=%s">Delete</a>',$_REQUEST['page'],'delete',$item['ID']),
        );*/
  return sprintf('%1$s %2$s', $item['product'], $this->row_actions($actions) );

}
function get_bulk_actions() {
  $actions = array(
    'delete'    => 'Delete'
  );
  //return $actions;
}
	public function process_bulk_action() {

		//Detect when a bulk action is being triggered...
		if ( 'export' === $this->current_action() ) {

		}

		
	}

function column_cb($item) {
        return sprintf(
            '<input type="checkbox" name="products[]" value="%s" />', $item['ID']
        );    
    }
function prepare_items() {
  $columns  = $this->get_columns();
  /** Process bulk action */
  $this->process_bulk_action();
  $hidden   = array();
  $sortable = $this->get_sortable_columns();
  $this->_column_headers = array( $columns, $hidden, $sortable );
  $data=$this->get_products();
 
  usort($data, array( &$this, 'usort_reorder' ) );
  
  $per_page = 30;
  $current_page = $this->get_pagenum();
  $total_items = @count($data);
  // only ncessary because we have sample data
  $found_data = array_slice($data,( ( $current_page-1 )* $per_page ), $per_page );
  $this->set_pagination_args( array(
    'total_items' => $total_items,                  //WE have to calculate the total number of items
    'per_page'    => $per_page                     //WE have to determine how many items to show on a page
  ) );
  $this->items = $found_data;
}



} //class


function product_export_menu_items()
{
 
    $hook =add_submenu_page('edit.php?post_type=product', 'Products CSV Export','Products CSV Export', 'activate_plugins', 'products_csv_export_list', 'products_csv_export_list');
    add_action( "load-$hook", 'add_product_export_options' );

}
add_action('admin_menu', 'product_export_menu_items');

add_action('admin_enqueue_scripts', 'tyrehub_admin_product_export');

function tyrehub_admin_product_export()
{
    wp_enqueue_script('tyrehub_admin_product_export_script', plugins_url('/js/export.js', __FILE__), array('jquery'));
}


function add_product_export_options() {
  global $productsCSVListTable;
  $option = 'per_page';
  $args = array(
         'label' => 'Products',
         'default' => 30,
         'option' => 'products_per_page'
         );
  add_screen_option( $option, $args );
  $productsCSVListTable = new Products_CSV_Export_List_Table();
}
//add_action( 'admin_menu', 'my_add_menu_items' );
function products_csv_export_list(){
  global $productsCSVListTable;?>
  
  <div class="wrap"><h2>Products CSV Export 
  	<a href="<?php echo plugin_dir_url( __FILE__ )?>csv_export.php?action=export&width=<?=$_POST['width'];?>&ratio=<?=$_POST['ratio'];?>&diameter=<?=$_POST['diameter'];?>&category=<?=$_POST['category'];?>" class="page-title-action">
  		CSV Export</a></h2> 
  <!-- <a href="?page=installer-add-new" class="page-title-action">Add New Installer</a>
 	<a href="?page=installer-facilities" class="page-title-action">Manage Facilities</a> -->
 	<form method="post" id="product_list">
 	
 	<form method="post" id="product_list1" action="edit.php?post_type=product&page=products_csv_export_list">
 		<?php wp_nonce_field( 'pp-eu-export-product-page_export', '_wpnonce-pp-eu-export-product-page_export' ); ?>
  	<input type="hidden" name="post_type" value="product">
    <input type="hidden" name="page" value="products_csv_export_list">
    <input type="hidden" name="action" id="action" value="">
 	<div class="admin-url" hidden=""><?php echo admin_url('admin-ajax.php'); ?></div>
 	<div class="prd-search" style="margin-top: 20px;">
            <strong>Select Options</strong>
            <strong>Width</strong><input type="text" name="width" class="select-width" value="<?=$_POST['width'];?>">
            <strong>Ration</strong><input type="text" name="ratio" class="select-ratio" value="<?=$_POST['ratio'];?>">
            <strong>Diameter</strong><input type="text" name="diameter" class="select-diameter" value="<?=$_POST['diameter'];?>">
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
            <button class="search-btn" id="csv_prodct_search">Search</button>            
        <div class="message-block" style="width: 100%; float: left;"></div>
        </div>
 <?php $productsCSVListTable->prepare_items(); ?>
  
    

    <?php
    //$productsCSVListTable->search_box( 'search', 'search_id' );
    $productsCSVListTable->display(); 
    echo '</form></div>'; 
}



include_once('function.php');