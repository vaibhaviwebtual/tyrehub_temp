<?php
/*
Plugin Name: Tyrehub Sales Report
Plugin URI: https://acespritech.com/
Description: Brands count with sales product only.
Version: 1.1.1
Author: Acespritech
Author URI: https://acespritech.com/
*/

if( ! class_exists( 'WP_List_Table' ) ) {
    require_once( ABSPATH . 'wp-admin/includes/class-wp-list-table.php' );
}

class Sales_Brand_ReportList_Table extends WP_List_Table
{
    function __construct() {
        global $status, $page;

        //Set parent defaults
        parent::__construct(array(
            'singular' => 'Sales Report', //singular name of the listed records
            'plural' => 'Sales Report', //plural name of the listed records
            'ajax' => false        //does this table support ajax?
        ));
    }
    
    function column_default($item, $column_name) {
        
        switch ($column_name)
        {
            case  "brand_name" :
            case  "tyre_count" :
            return $item[ $column_name ];
            default:
            return print_r( $item, true ) ; //Show the whole array for troubleshooting purposes
        }
        
    }

    function get_columns() {
        return $columns = array(
            'brand_name' => __('Brand Name'),
            'tyre_count' => __('Brand Count')
        );
    }
  
    function prepare_items() {
        global $wpdb; //This is used only if making any database queries
            
            if(isset($_POST['datsearch']))
            {
                $pastmonth = $_POST['fromdate'];
                $todaydate = date('Y-m-d');
                
            } else {
                
                $pastmonth = date("Y-m-d", strtotime( date( "Y-m-d", strtotime( date("Y-m-d") ) ) . "-1 month" ) );
                $todaydate = date('Y-m-d');
            }
            

            $wcatTerms = get_terms('product_cat', array('hide_empty' => 0, 'parent' =>0));
            $count = $category->category_count;

            foreach($wcatTerms as $key=>$wcatTerm) : 
                
               $SQL="SELECT
				    SUM(im1.meta_value) as count ,
				    i.order_item_name,count(*)
				from
				    wp_posts as p
				    inner join
				    wp_woocommerce_order_items as i
				    on p.id = i.order_id
				    inner join
				    wp_woocommerce_order_itemmeta as im
				    on i.order_item_id = im.order_item_id
				    inner join
				    wp_woocommerce_order_itemmeta as im1
				    on i.order_item_id = im1.order_item_id
                                    where
				    p.post_type = 'shop_order'
				    AND DATE_FORMAT(p.post_date,'%Y-%m-%d') BETWEEN '".$pastmonth."' AND '".$todaydate."'
				    and p.post_status = 'wc-completed' AND (im.meta_key = 'pa_brand' AND im.meta_value = '".$wcatTerm->slug."') AND  (im1.meta_key = '_qty')";
		   		    
                                    $product_count=$wpdb->get_row($SQL);
                                    
                                    
                                    if($product_count->count > 0)
                                    {
                                        $pr_count = $product_count->count;
                                    } else {
                                        $pr_count = 0;
                                    }
                                    $array_barnd[] = $wcatTerm->name . '  ('.$pr_count.')';

                     $brands[$key]['brand_name']=$wcatTerm->name;
                     $brands[$key]['tyre_count']=$pr_count; 

                endforeach;
                                    
        
        
asort($brands); 

$tyre_count = array_column($brands, 'tyre_count');
array_multisort($tyre_count, SORT_DESC, $brands);

        $totalitems = count($brands);
        
        /**
         * First, lets decide how many records per page to show
         */
        $perpage = 10;

        //Which page is this?
        $paged = !empty($_GET["paged"]) ? mysql_real_escape_string($_GET["paged"]) : '';
        //Page Number
        if (empty($paged) || !is_numeric($paged) || $paged <= 0) {
            $paged = 1;
        }


        //How many pages do we have in total?
        $totalpages = ceil($totalitems / $perpage);
        //adjust the query to take pagination into account
        if (!empty($paged) && !empty($perpage)) {
            $offset = ($paged - 1) * $perpage;
            $query.=' LIMIT ' . (int) $offset . ',' . (int) $perpage;
        }

        /* -- Register the pagination -- */
        $this->set_pagination_args(array(
            "total_items" => $totalitems,
            "total_pages" => $totalpages,
            "per_page" => $perpage,
        ));

        $columns = $this->get_columns();
        $hidden = array();
        $sortable = array();

        $this->_column_headers = array($columns, $hidden, $sortable);

        $this->items = $brands;

        
    }


}

function call_sales_report_menu()
{
    add_menu_page('Sales Brand Report','Sales Brand Report', 'manage_options', 'sales-brand_report', 'callback_sales_report','dashicons-format-aside');
}

add_action('admin_menu', 'call_sales_report_menu');

function callback_sales_report()
{
            global $wpdb;

            $table = new Sales_Brand_ReportList_Table();
            $table->prepare_items();
            $message = '';
            
            ?>
            <div class="wrap">
                <h2>Sales Report</h2>
                <?php echo $message; ?>
                <h2>Serach By Date:</h2>
                <form name="frmSearch" method="post" action="">
                        <p class="search_input">
                               <input type="date" placeholder="From Date" id="post_at" name="fromdate"  value="<?php echo $post_at; ?>" class="input-control" />
                               <input type="date" placeholder="To Date" id="post_at_to_date" name="lastdate" style="margin-left:10px"  value="<?php echo $post_at_to_date; ?>" class="input-control"  />			 
                               <input type="submit" name="datsearch" value="Search" >
                       </p>
               </form>
                <form id="persons-table" method="GET">
                    <input type="hidden" name="page" value="<?php echo $_REQUEST['page'] ?>"/>
                    <?php if(!isset($_POST['datsearch']))
                    {?>
                    <h3> Last 30 days records</h3>  
                    <?php
                    } else {?>
                    <h3> Records between <?php echo $_POST['fromdate'];?> & <?php echo $_POST['lastdate'];?></h3>
                    <?php 
                    } ?>
                    <?php $table->display() ?>
                </form>

            </div>
            <?php

}