<?php

class Variants_List_Table extends WP_List_Table {

    function get_variants(){
        /*$args = array(
        'post_type' => 'product_variation',
        'posts_per_page' =>-1,
        'orderby'   => 'meta_value_num ID',
        'order' => 'DESC',
        'post_status' => array('publish', 'pending', 'draft', 'auto-draft', 'future', 'private', 'inherit', 'trash')    
    );
    $query = new WP_Query($args);*/
              $width=$_GET['width'];
              $ratio=$_GET['ratio'];
              $diameter=$_GET['diameter'];
              $brand=$_GET['brand'];
              if($width){
                $meta_query[]=array(
                  'key' => 'attribute_pa_width',
                  'value' => $width,
                  'compare' => '=',
                );
              }
              if($ratio){
                $meta_query[]=array(
                  'key' => 'attribute_pa_ratio',
                  'value' => $ratio,
                  'compare' => '=',
                );
              }
              if($diameter){
                $meta_query[]=array(
                  'key' => 'attribute_pa_diameter',
                  'value' => $diameter,
                  'compare' => '=',
                );
              }
              if($brand){
                $meta_query[]=array(
                  'key' => 'attribute_pa_brand',
                  'value' => $brand,
                  'compare' => '=',
                );
              }
              $args = array(
                'post_type' => 'product_variation',
                'posts_per_page' =>-1,
                'orderby'   => 'meta_value_num ID',
                'order' => 'DESC',
                'post_status' => array('publish', 'pending', 'draft', 'auto-draft', 'future', 'private', 'inherit', 'trash'),
                'meta_query'=> array(                       
                'relation' => 'AND',$meta_query
                 ),            
              );  
              $args['meta_key'] = '_sale_price';
              $args['orderby'] = 'meta_value_num';
              $args['order'] = $sorting;  
              $query = new WP_Query($args);


    global $post;
    //$franchise_orders = get_posts($my_course_query);
      $products=array();
      $key=0;
      while ($query->have_posts() ) : $query->the_post();     		
        /*$categories =wp_get_object_terms($post->ID, 'product_cat', array( 'fields' => 'names' ) );*/      
        $categories =get_post_meta($post->ID,'attribute_pa_brand',true);
        $description =get_post_meta($post->ID,'_variation_description',true);

  		  $products[$key]['ID'] = $post->ID;
        //$products[$key]['ID']= $post->ID;
        $products[$key]['product_name']= $description;
        $products[$key]['category']= $categories;
       $tyrehub_visible =get_post_meta($post->ID,'tyrehub_visible',true);

        if($tyrehub_visible=='no'){
        $products[$key]['visiblity']= '<a href="'.get_admin_url().'/admin.php?page=add-new-variant&action=edit&post_id='.$post->ID.'" class="button-secondary woocommerce-save-button add-installer-btn">No</a>'; 
        }else{
        $products[$key]['visiblity']= '<a href="'.get_admin_url().'/admin.php?page=add-new-variant&action=edit&post_id='.$post->ID.'" class="button-primary woocommerce-save-button add-installer-btn">Yes</a> '; 
        } 
        $key++;    
     endwhile;

     return $products;
     die;
   
  }
    function __construct(){
    global $status, $page;
        parent::__construct( array(
            'singular'  => __( 'variant', 'mylisttable' ),     //singular name of the listed records
            'plural'    => __( 'variants', 'mylisttable' ),   //plural name of the listed records
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
    _e( 'No variants found, dude.' );
  }
  function column_default( $item, $column_name ) {
    switch( $column_name ) { 
        case 'ID':
        case 'product_name':
        case 'category':
        case 'visiblity':
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
    'visiblity' => array('visiblity',false)
  );
  return $sortable_columns;
}
function get_columns(){
        $columns = array(
            'cb'        => '<input type="checkbox" />',
            'ID' => __( 'ID', 'mylisttable' ),
            'product_name' => __( 'Variant Name', 'mylisttable' ),
            'category'    => __( 'Brand', 'mylisttable' ),
            'visiblity'      => __('Visiblity', 'mylisttable' )
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
            'edit'      => sprintf('<a href="?page=%s&action=%s&post_id=%s">Edit</a>','product-add-new-variant','edit',$item['ID']),
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
   

   //add_action( 'admin_notices', 'my_error_notice' );
  }

function my_error_notice() {
    ?>
    <div class="error notice-success">
        <p><?php _e( 'Variant deleted has been success!', 'my_plugin_textdomain'); ?></p>
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
  $data=$this->get_variants();
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


function add_options_variants() {
  global $variantsListTable;
  $option = 'per_page';
  $args = array(
         'label' => 'Variants',
         'default' => 30,
         'option' => 'variants_per_page'
         );
  add_screen_option( $option, $args );
  $variantsListTable = new Variants_List_Table();
  
}


//add_action( 'admin_menu', 'my_add_menu_items' );
function variant_list(){
  $variantsListTable = new Variants_List_Table();
  ?>
  <div class="wrap"><h2>Variants Management 
 	<div class="admin-url" hidden=""><?php echo admin_url('admin-ajax.php'); ?></div></h2> <br>
  <a href="?page=product-add-new" class="page-title-action">Add New Product</a>
  <a href="?page=add-new-brand" class="page-title-action">Add New Brand</a>
  <a href="?page=product-add-new-variant" class="page-title-action">Add New Variant</a>

 <?php $variantsListTable->prepare_items(); 
?>
  <form method="get">
    <input type="hidden" name="page" value="variants-list">
    <select name="width" id="width">
               <option value="">Any width…</option>
                    <?php 
                    $terms = get_terms(array('taxonomy'=>'pa_width','hide_empty' => false));

                    foreach ( $terms as $term ){?>
                    <option value="<?php echo str_replace('.', '-',$term->name); ?>" <?php if($_GET['width']==str_replace('.', '-',$term->name)){ echo 'selected';} ?>><?php echo $term->name; ?></option>
                    <?php }?>
                </select>
                <select name="ratio" id="ratio">
               <option value="">Any ratio…</option>
                    <?php 
                    $terms = get_terms(array('taxonomy'=>'pa_ratio','hide_empty' => false));

                    foreach ( $terms as $term ){?>
                    <option value="<?php echo str_replace('.', '-',$term->name); ?>" <?php if($_GET['ratio']==str_replace('.', '-',$term->name)){ echo 'selected';} ?>><?php echo $term->name; ?></option>
                    <?php }?>
                </select>
                <select name="diameter" id="diameter">
               <option value="">Any diameter…</option>
                    <?php 
                    $terms = get_terms(array('taxonomy'=>'pa_diameter','hide_empty' => false));

                    foreach ( $terms as $term ){?>
                    <option value="<?php echo str_replace('.', '-',$term->name); ?>" <?php if($_GET['diameter']==str_replace('.', '-',$term->name)){ echo 'selected';} ?>><?php echo $term->name; ?></option>
                    <?php }?>
                </select>
                <select name="brand" id="brand">
               <option value="">Any brand…</option>
                    <?php 
                    $terms = get_terms(array('taxonomy'=>'pa_brand','hide_empty' => false));
                    foreach ( $terms as $term ){?>
                    <option value="<?php echo $term->slug; ?>" <?php if($_GET['brand']==$term->slug){ echo 'selected';} ?>><?php echo $term->name; ?></option>
                    <?php }?>
                </select>
                <input type="submit" id="" class="page-title-action waves-effect waves-light" name="filter" value="Filter">
    <?php
    $variantsListTable->display(); 
    echo '</form></div>'; 
}

function new_product_variant_from(){
   $action=$_GET['action'];
   if($action=='edit'){
    include('update-product-variant.php');
     product_variant_update();
   }else{
     tim_add_new_product_variant();  
   }  
}
