<?php
/*
Plugin Name: Tyrehub Manage Products
Plugin URI: https://webtual.com/
Description: Suppliers reports and paid functionality.
Version: 1.1.1
Author: Webtual
Author URI: https://webtual.com/
*/
if( ! class_exists( 'WP_List_Table' ) ) {
    require_once( ABSPATH . 'wp-admin/includes/class-wp-list-table.php' );
}
include_once('add-new-brand.php');
include_once('add-new-product.php');
include_once('add-new-variant.php');
include_once('brands-listing.php');
include_once('variants-list.php');

/*include_once('add-new-brand.php');

include_once('add-new-product.php');
include_once('add-new-variant.php');*/

/*include_once('supplier-product-price-list.php');
include_once('product-price-list.php');
include_once('supplier-products-list.php');
include_once('supplier-products-assign-list.php');
include_once('supplier-price-change-log.php');
include_once('all-products-list.php');
include_once('purchase-orders.php');*/



class ProductsManage_List_Table extends WP_List_Table {

    function get_products(){
        $args = array(
        'post_type' => 'product',
        'posts_per_page' =>-1,
        'orderby'   => 'meta_value_num ID',
        'order' => 'DESC',
        'post_status' => array('publish', 'pending', 'draft', 'auto-draft', 'future', 'private', 'inherit', 'trash')    
    );
    $query = new WP_Query($args);

    global $post;
    //$franchise_orders = get_posts($my_course_query);
      $products=array();
      $key=0;
      while ($query->have_posts() ) : $query->the_post();
     		
        $categories =wp_get_object_terms($post->ID, 'product_cat', array( 'fields' => 'names' ) );
       

  		  $products[$key]['ID'] = $post->ID;
        //$products[$key]['ID']= $post->ID;
        $products[$key]['product_name']= $post->post_title;
        $products[$key]['category']= $categories[0];
        $product   = wc_get_product($post->ID);
        $image_id  = $product->get_image_id();
        $image_url = wp_get_attachment_image_url( $image_id, 'full' );
        if($image_url){
          $imgurl =$image_url;
        }else{
          $imgurl=site_url().'/wp-content/themes/demo/images/no_img1.png';
        }

        $products[$key]['image']= '<img src="'.$imgurl.'" width="100">';
        if(in_array($query->post_status,array('pending','draft','auto-draft','future','private','inherit','trash'))){
        $products[$key]['post_status']= '<a href="'.get_admin_url().'/admin.php?page=product-add-new&action=edit&post_id='.$post->ID.'" class="button-secondary woocommerce-save-button add-installer-btn">No</a>'; 
        }else{
        $products[$key]['post_status']= '<a href="'.get_admin_url().'/admin.php?page=product-add-new&action=edit&post_id='.$post->ID.'" class="button-primary woocommerce-save-button add-installer-btn">Yes</a> '; 
        } 
        $key++;    
     endwhile;

     return $products;
     die;
   
  }
    function __construct(){
    global $status, $page;
        parent::__construct( array(
            'singular'  => __( 'product', 'mylisttable' ),     //singular name of the listed records
            'plural'    => __( 'products', 'mylisttable' ),   //plural name of the listed records
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
    _e( 'No supplier found, dude.' );
  }
  function column_default( $item, $column_name ) {
    switch( $column_name ) { 
        case 'ID':
        case 'product_name':
        case 'category':
        case 'image':
        case 'post_status':
            return $item[ $column_name ];
        default:
            return print_r( $item, true ) ; //Show the whole array for troubleshooting purposes
    }
  }
function get_sortable_columns() {
  $sortable_columns = array(
    'ID'  => array('ID',false),
    'product_name'  => array('product_name',false),
    'category' => array('category',false),
    'image'   => array('image',false),
    'post_status' => array('post_status',false)
  );
  return $sortable_columns;
}
function get_columns(){
        $columns = array(
            'cb'        => '<input type="checkbox" />',
            'ID' => __( 'ID', 'mylisttable' ),
            'product_name' => __( 'Product Name', 'mylisttable' ),
            'category'    => __( 'Category', 'mylisttable' ),
            'image'      => __( 'Image', 'mylisttable' ),
            'post_status'      => __('Status', 'mylisttable' )
        );
         return $columns;
    }
function usort_reorder( $a, $b ) {
  // If no sort, default to title
  $orderby = ( ! empty( $_GET['orderby'] ) ) ? $_GET['orderby'] : 'ID';
  // If no order, default to asc
  $order = ( ! empty($_GET['order'] ) ) ? $_GET['order'] : 'asc';
  // Determine sort order
  $result = strcmp( $a[$orderby], $b[$orderby] );
  // Send final sort direction to usort
  return ( $order === 'asc' ) ? $result : -$result;
}
function column_ID($item){
  $actions = array(
            'edit'      => sprintf('<a href="?page=%s&action=%s&post_id=%s">Edit</a>','product-add-new','edit',$item['ID']),
            'delete'    => sprintf('<a href="?page=%s&action=%s&post_id=%s">Delete</a>',$_REQUEST['page'],'delete',$item['ID']),
        );
  return sprintf('%1$s %2$s', $item['ID'], $this->row_actions($actions) );
}
function get_bulk_actions() {
  $actions = array(
    'delete'    => 'Delete'
  );
  return $actions;
}


  function process_bulk_action() {
    //Detect when a bulk action is being triggered...
    global $wpdb;
    if($_GET['action']=='delete' && $_GET['post_id']!=''){
      
        $id=$_GET['post_id'];
        $SQL="SELECT * FROM `th_supplier_data` WHERE supplier_data_id=$id";
          $results=$wpdb->get_row($SQL);
          self::supplier_delete_by_admin($results->user_id);
         $wpdb->update( 
            'th_supplier_products', 
            array( 
              'supplier_id' =>0,
              'status' =>1
            ), 
            array('supplier_id'=>$results->supplier_data_id), 
            array( 
              '%d',
              '%d'
            ), 
            array('%d') 
          );
    }

    if ('delete' === $this->current_action()) {      
      // In our file that handles the request, verify the nonce.
      $nonce = esc_attr( $_REQUEST['_wpnonce']);
      $delete_ids = esc_sql( $_POST['supplier']);
      global $wpdb;
      // loop over the array of record IDs and delete them
      foreach ( $delete_ids as $id ) {
          $SQL="SELECT * FROM `th_supplier_data` WHERE supplier_data_id=$id";
          $results=$wpdb->get_row($SQL);
          self::supplier_delete_by_admin($results->user_id);
         $wpdb->update( 
            'th_supplier_products', 
            array( 
              'supplier_id' =>0,
              'status' =>1
            ), 
            array('supplier_id'=>$results->supplier_data_id), 
            array( 
              '%d',
              '%d'
            ), 
            array('%d') 
          );
    }
  }
   //add_action( 'admin_notices', 'my_error_notice' );
  }

 function supplier_delete_by_admin($user_id){
  global $wpdb;
  
  wp_delete_user($user_id);
  $wpdb->delete('th_supplier_data', array('user_id' =>$user_id));


}
function my_error_notice() {
    ?>
    <div class="error notice-success">
        <p><?php _e( 'Supplier deleted has been success!', 'my_plugin_textdomain'); ?></p>
    </div>
    <?php
}


function column_cb($item) {
        return sprintf(
            '<input type="checkbox" name="product[]" value="%s" />', $item['ID']
        );    
}
function prepare_items() {
  $columns  = $this->get_columns();
  $hidden   = array();
  $sortable = $this->get_sortable_columns();
  $this->_column_headers = array( $columns, $hidden, $sortable );
  $this->process_bulk_action();
  $data=$this->get_products();
 if($_GET['order']){
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

add_action('admin_menu', 'manage_product_menu');

function manage_product_menu()
{
	$hook = add_menu_page('Products', 'Products', 'manage_options', 'product-manage', 'product_list_page', 'dashicons-admin-tools',62 );

	add_submenu_page('product-manage','Products New', 'Add New', 'manage_options', 'product-add-new', 'new_product_from', 'dashicons-media-text',63 );
  add_submenu_page('product-manage','Variants', 'Variants', 'manage_options', 'variants-list', 'variant_list', 'dashicons-media-text',63 );
  add_submenu_page('product-manage','Products New Variant', 'Add New Variant', 'manage_options', 'product-add-new-variant', 'new_product_variant_from', 'dashicons-media-text',63 );
  add_submenu_page('product-manage','Brands', 'Brands', 'manage_options', 'brands-list', 'brands_list_fun', 'dashicons-media-text',62 );



  add_submenu_page('product-manage','New Brands', 'New Brands', 'manage_options', 'add-new-brand', 'new_brand_form_fun', 'dashicons-media-text',63 );



/*add_submenu_page('supplier-product-price-change-list','Products Assign', 'Products Assign', 'manage_options', 'supplier-product-assign', 'supplier_product_assign', 'dashicons-media-text',62 );*/
  


	 //add_action( "load-$hook", 'add_options_supplier' );


}

//add_action('admin_menu', 'add_custom_link_into_appearnace_menu');
function _product_add_custom_link_into_appearnace_menu() {
    global $submenu;
    $permalink = admin_url().'admin.php?page=supplier-product-price-change-list&status=pending';
    $submenu['supplier-manage'][] = array( 'Price Approve Pending', 'manage_options', $permalink );

    /*$permalink1 = admin_url().'admin.php?page=supplier-product-price-change-list&status=pending';
    $submenu['supplier-manage'][] = array( 'Price Change Log', 'manage_options', $permalink1);*/
}

/*function supplier_product_price_pending_list(){
  global $pending;
  $pending=1;
  supplier_product_price_change_list();
}*/

add_action('admin_enqueue_scripts', '_product_admin_style');

function _product_admin_style()
{
    
     wp_enqueue_style('supplier_admin_stylea', plugins_url('/style.css', __FILE__));
    wp_enqueue_style('supplier_admin_css', site_url().'/wp-content/plugins/woocommerce/assets/css/admin.css?ver=3.6.5');
     wp_enqueue_script('supplier_admin_product_scripta', plugins_url('/manage-products.js', __FILE__), array('jquery'));
}



function add_options_product() {
  global $suppliersListTable,$suppliersProductListTable;
  $option = 'per_page';
  $args = array(
         'label' => 'Products',
         'default' => 30,
         'option' => 'products_per_page'
         );
  add_screen_option( $option, $args );
  $productsListTable = new ProductsManage_List_Table();
  
}


//add_action( 'admin_menu', 'my_add_menu_items' );
function product_list_page(){
  $productsListTable = new ProductsManage_List_Table();
  ?>
  <div class="wrap"><h2>Products Management 
 	<div class="admin-url" hidden=""><?php echo admin_url('admin-ajax.php'); ?></div></h2> <br>
  <a href="?page=product-add-new" class="page-title-action">Add New Product</a>
  <a href="?page=add-new-brand" class="page-title-action">Add New Brand</a>
  <a href="?page=product-add-new-variant" class="page-title-action">Add New Variant</a>

 <?php $productsListTable->prepare_items(); 
?>
  <form method="post">
    <input type="hidden" name="page" value="supplier-manage">
    <?php
    $productsListTable->search_box( 'search', 'search_id' );
    $productsListTable->display(); 
    echo '</form></div>'; 
}


function new_product_from(){
	 $action=$_GET['action'];
	 if($action=='edit'){
	  include('update-product.php');
	   product_update();
	 }else{
	  	  tim_add_new_product();	
	 }	
}


function new_brand_form_fun(){
  $action=$_GET['action'];
   if($action=='edit'){
    include('update-brand.php');
     brand_update();
   }else{
        tim_add_new_brand();  
   } 
}

