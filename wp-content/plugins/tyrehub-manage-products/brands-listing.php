<?php
class Brands_List_Table extends WP_List_Table {

    function get_brands(){

     $terms = get_terms(array('taxonomy'=>'product_cat','hide_empty' => false));
      global $post;
      $brands=array();
      foreach ( $terms as $key=>$term ){
            
            $brands[$key]['ID'] = $term->term_id;
            $brands[$key]['brand_name']= $term->name;
            $brands[$key]['slug']=$term->slug;
            $thumbnail_id = get_woocommerce_term_meta( $term->term_id, 'thumbnail_id', true ); 
            $image = wp_get_attachment_url( $thumbnail_id ); 
            if($image){
              $imgurl =$image;
            }else{
              $imgurl=site_url().'/wp-content/themes/demo/images/no_img1.png';
            }

            $brands[$key]['image']= '<img src="'.$imgurl.'" width="100">';            
        }
     return $brands;
     die;
   
  }
    function __construct(){
    global $status, $page;
        parent::__construct( array(
            'singular'  => __( 'brand', 'mylisttable' ),     //singular name of the listed records
            'plural'    => __( 'brands', 'mylisttable' ),   //plural name of the listed records
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
    _e( 'No brands found, dude.' );
  }
  function column_default( $item, $column_name ) {
    switch( $column_name ) { 
        case 'ID':
        case 'brand_name':
        case 'slug':
        case 'image':
            return $item[ $column_name ];
        default:
            return print_r( $item, true ) ; //Show the whole array for troubleshooting purposes
    }
  }
function get_sortable_columns() {
  $sortable_columns = array(
    'ID'  => array('ID',false),
    'brand_name'  => array('brand_name',false),
    'slug' => array('slug',false),
    'image'   => array('image',false),
  );
  return $sortable_columns;
}
function get_columns(){
        $columns = array(
            'cb'        => '<input type="checkbox" />',
            'ID' => __( 'ID', 'mylisttable' ),
            'brand_name' => __( 'Brand Name', 'mylisttable' ),
            'slug'    => __( 'Slug', 'mylisttable' ),
            'image'      => __( 'Image', 'mylisttable' )
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
            'edit'      => sprintf('<a href="?page=%s&action=%s&term_id=%s">Edit</a>','add-new-brand','edit',$item['ID']),
            'delete'    => sprintf('<a href="?page=%s&action=%s&term_id=%s">Delete</a>',$_REQUEST['page'],'delete',$item['ID']),
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
    /*if($_GET['action']=='delete' && $_GET['post_id']!=''){
      
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
    }*/

    if ('delete' === $this->current_action()) {      
      // In our file that handles the request, verify the nonce.
      $nonce = esc_attr( $_REQUEST['_wpnonce']);
      $delete_ids = esc_sql( $_POST['brands']);
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

 function brands_delete_by_admin($user_id){
  global $wpdb;
  
 // wp_delete_user($user_id);
  //$wpdb->delete('th_supplier_data', array('user_id' =>$user_id));


}
function my_error_notice() {
    ?>
    <div class="error notice-success">
        <p><?php _e( 'Brands deleted has been success!', 'my_plugin_textdomain'); ?></p>
    </div>
    <?php
}


function column_cb($item) {
        return sprintf(
            '<input type="checkbox" name="brand[]" value="%s" />', $item['ID']
        );    
}
function prepare_items() {
  $columns  = $this->get_columns();
  $hidden   = array();
  $sortable = $this->get_sortable_columns();
  $this->_column_headers = array( $columns, $hidden, $sortable );
  $this->process_bulk_action();
  $data=$this->get_brands();
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




function add_options_brands() {
  global $brandsListTable;
  $option = 'per_page';
  $args = array(
         'label' => 'Brands',
         'default' => 30,
         'option' => 'brands_per_page'
         );
  add_screen_option( $option, $args );
  $brandsListTable = new Brands_List_Table();
  
}


//add_action( 'admin_menu', 'my_add_menu_items' );
function brands_list_fun(){
  $brandsListTable = new Brands_List_Table();
  ?>
  <div class="wrap"><h2>Brands Management 
 	<div class="admin-url" hidden=""><?php echo admin_url('admin-ajax.php'); ?></div></h2> <br>
  <a href="?page=product-add-new" class="page-title-action">Add New Product</a>
  <a href="?page=add-new-brand" class="page-title-action">Add New Brand</a>
  <a href="?page=product-add-new-variant" class="page-title-action">Add New Variant</a>

 <?php $brandsListTable->prepare_items(); 
?>
  <form method="post">
    <input type="hidden" name="page" value="brands-list">
    <?php
    //$brandsListTable->search_box( 'search', 'search_id' );
    $brandsListTable->display(); 
    echo '</form></div>'; 
}

