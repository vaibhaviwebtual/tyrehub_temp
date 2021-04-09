<?php
/*
Plugin Name: Tyrehub Discount
Plugin URI: https://acespritech.com/
Description: Discount Particular Product.
Version: 1.4.1
Author: Acespritech
Author URI: https://acespritech.com/
*/

if(!class_exists('WP_List_Table')){
    require_once( ABSPATH . 'wp-admin/includes/class-wp-list-table.php' );
}

include('table.php');

function tt_add_menu_items()
{
    add_submenu_page('woocommerce', 'Discount','Discount', 'activate_plugins', 'discount_rule', 'tt_render_list_page');

    add_submenu_page( 'discount_rule', 'New discount', 'New discount', 'manage_options', 'new-discount', 'add_new_discount' );

    add_submenu_page( 'discount_rule', 'Edit rule', 'Edit rule', 'manage_options', 'edit-rule', 'edit_discount_rule' );

    add_submenu_page( 'discount_rule', 'Delete rule', 'Delete rule', 'manage_options', 'del-rule', 'delete_discount_rule' );

    //add_submenu_page( 'tt_list_test', 'Product List', 'Product List', 'manage_options', 'product-list', 'product_list' );

}
add_action('admin_menu', 'tt_add_menu_items');

function omnizz_options_enqueue_scripts() {
    wp_register_script( 'omnizz-upload', plugins_url('/js/demo.js', __FILE__), array('jquery','media-upload','thickbox') );

    wp_enqueue_script('jquery');

    wp_enqueue_script('media-upload');
    wp_enqueue_script('omnizz-upload');

    wp_enqueue_media();

}
add_action('admin_enqueue_scripts', 'omnizz_options_enqueue_scripts');

add_action('admin_enqueue_scripts', 'tyrehub_admin_discount_script');

function tyrehub_admin_discount_script()
{
    wp_enqueue_script('tyrehub_admin_discount_script', plugins_url('/js/discount_rule.js', __FILE__), array('jquery'));

    wp_enqueue_style('tyrehub_admin_discount_style', plugins_url('/css/discount_style.css', __FILE__));
   
    if($_GET['page'] == 'edit-rule' || $_GET['page'] == 'new-discount')
    {
       
        wp_enqueue_script('discount_datetimepicker_script', plugins_url('/js/bootstrap.min.js', __FILE__), array('jquery'));

        wp_enqueue_style('discount_datetimepicker_style', plugins_url('/css/bootstrap.min.css', __FILE__));
    }
    else{
        wp_dequeue_style('discount_datetimepicker_style');
        wp_dequeue_script( 'discount_datetimepicker_script' );
    }
    

    wp_enqueue_script('discount_datetimepicker_script1', plugins_url('/js/bootstrap-datetimepicker.js', __FILE__), array('jquery'));

    wp_enqueue_script('discount_datetimepicker_script2', plugins_url('/js/bootstrap-datetimepicker.fr.js', __FILE__), array('jquery'));

    

    wp_enqueue_style('discount_datetimepicker_style1', plugins_url('/css/bootstrap-datetimepicker.min.css', __FILE__));
}



function tt_render_list_page(){
    
    //Create an instance of our package class...
    $testListTable = new TT_Example_List_Table();
    //Fetch, prepare, sort, and filter our data...
    $testListTable->prepare_items();
    
    ?>
    <div class="wrap">
        
        <div id="icon-users" class="icon32"><br/></div>
        <h1 class="wp-heading-inline">Discount Rules</h1>
        <a href="<?php echo get_site_url(); ?>/wp-admin/admin.php?page=new-discount" class="page-title-action">Add discount</a>
       <!--  <div style="background:#ECECEC;border:1px solid #CCC;padding:0 10px;margin-top:5px;border-radius:5px;-moz-border-radius:5px;-webkit-border-radius:5px;">
            <p>This page demonstrates the use of the <tt><a href="http://codex.wordpress.org/Class_Reference/WP_List_Table" target="_blank" style="text-decoration:none;">WP_List_Table</a></tt> class in plugins.</p> 
            <p>For a detailed explanation of using the <tt><a href="http://codex.wordpress.org/Class_Reference/WP_List_Table" target="_blank" style="text-decoration:none;">WP_List_Table</a></tt>
            class in your own plugins, you can view this file <a href="<?php echo admin_url( 'plugin-editor.php?plugin='.plugin_basename(__FILE__) ); ?>" style="text-decoration:none;">in the Plugin Editor</a> or simply open <tt style="color:gray;"><?php echo __FILE__ ?></tt> in the PHP editor of your choice.</p>
            <p>Additional class details are available on the <a href="http://codex.wordpress.org/Class_Reference/WP_List_Table" target="_blank" style="text-decoration:none;">WordPress Codex</a>.</p>
        </div> -->
        
        <!-- Forms are NOT created automatically, so you need to wrap the table in one to use features like bulk actions -->
        <form id="movies-filter" method="get">
            <!-- For plugins, we also need to ensure that the form posts back to our current page -->
            <input type="hidden" name="page" value="<?php echo $_REQUEST['page'] ?>" />
            <!-- Now we can render the completed list table -->
            <?php $testListTable->display() ?>
        </form>
        
    </div>
    <?php
}

function add_new_discount()
{
    ?>
    <h2>Add New Discount Rule</h2>
    <a href="?page=discount_rule">Back</a>
    <span class="error-msg" style="color: red;"></span>
     <div class="admin-url" hidden=""><?php echo admin_url('admin-ajax.php'); ?></div>
     <?php $omnizzOption = get_option("omnizz_logo"); ?>
    <form name="post" method="post" id="post" enctype="multipart/form-data">
        <div id="poststuff" class="discount-rule">
            <div id="post-body" class="metabox-holder">
                <div id="">
                    <div id="titlediv">
                        
                        <div class="inside">
                        </div>
                        <input type="hidden" id="samplepermalinknonce" name="samplepermalinknonce" value="58eea30609">
                    </div>
                    <div id="" class="postbox-container">
                        <table class="form-table">
                            <tr>
                                <th>Enable</th>
                                <td>
                                    <input type="checkbox" name="status" >
                                </td>
                            </tr>
                            <tr>
                                <th>Rule Title</th>
                                <td>
                                    <input type="text" name="post_title" class="rule-name" size="30" value="" id="title" spellcheck="true" autocomplete="off" placeholder="Rule name">
                                </td>
                            </tr>
                            <tr>
                                <th>Rule Image</th>
                                <td>
                                    <!-- <input type="file" name="rule_img" size="30" value="" id="title" spellcheck="true" autocomplete="off" placeholder="Rule name"> -->

                                    <input type='text' id='logo_url' readonly='readonly' name='logo' size='40' value='<?php esc_url( $omnizzOption ) ?>' />
                                    <input id='upload_button' type='button' class='button' value='<?php _e( 'Upload offer image', 'omnizz' ); ?>' />
                                    <div>Image size must be 50 * 50 </div>
                                </td>
                            </tr>
                             <tr>
                                <th>Start Date:</th>
                                <td><input type="text" name="start_date" class="start-date" autocomplete="off"></td>
                            </tr>
                            <tr>
                                <th>End Date:</th>
                                <td><input type="text" name="end_date" class="end-date" autocomplete="off"></td>
                            </tr>
                            <tr>
                                <th>Product List</th>
                                <td class="meta-selection">
                                    <div class="byid selection-type">
                                        <div class="prd-search">
                                            <strong>Width</strong><input type="text" name="width" class="width">
                                            <strong>Ration</strong><input type="text" name="ratio" class="ratio">
                                            <strong>Diameter</strong><input type="text" name="diameter" class="diameter">
                                            <select class="category">
                                                <option value="">Select Category</option>
                                                <?php 
                                                $taxonomy = 'product_cat';
                                                $terms=  get_terms($taxonomy);
                
                                                $terms_select="";
                                                foreach ($terms as $term)
                                                {
                                                    echo $terms_select='<option value="'.$term->name.'">'.$term->name.'</option>';
                                                }
                                                ?>
                                            </select>
                                            <button class="search-btn">Search</button>
                                        </div>
                                                                                
                                    </div>
                                </td>
                            </tr>
                            <tr>
                                <td>
                                   
                                </td>
                            </tr>
                        </table>
                         <div class="product-container">
                            <div class="product-details">
                                <h2>Search Product List</h2>
                            </div>
                            <div class="selected-products"><h2>Selected Product List</h2>
                                <div class="header">
                                    <div class="remove"><strong>Remove</strong></div>
                                    <div class="name"><strong>Name</strong></div>
                                    <div class="price"><strong>MRP</strong></div>
                                    <div class="price"><strong>Web Price</strong></div>
                                    <div class="amount"><strong>Discount</strong></div>
                                    <div class="status"><strong>On/Off</strong></div>
                                </div>
                            </div>
                        </div>
                        <table class="form-table">
                            <tr>
                                <td>
                                     <input type="submit" name="publish" id="publish" class="button button-primary button-large save_discount" value="Publish" disabled="">
                                </td>
                            </tr>
                        </table> 
                    </div>
                </div>
            </div>
        </div>
    </form>    
    <?php
}

function edit_discount_rule()
{
    ?>
    <h2>Edit Discount Rule</h2>
    <a href="?page=discount_rule">Back</a>
          
    <?php
    $rule_id = $_GET['id'];
    global $woocommerce , $wpdb;

    $sql = "SELECT * FROM th_discount_rule where rule_id = '$rule_id'";
    $rule_data = $wpdb->get_results($sql);
    $rule_data = $rule_data[0];
   
?>
    <form name="post" method="post" id="post">
        <div id="poststuff" class="discount-rule">
             <div class="admin-url" hidden=""><?php echo admin_url('admin-ajax.php'); ?></div>
            <div id="post-body" class="metabox-holder">
                <div id="post-body-content">
                    <div id="titlediv">
                        <div id="titlewrap">
                            
                        </div>
                        <div class="inside">
                        </div>
                        <input type="hidden" id="samplepermalinknonce" name="samplepermalinknonce" value="58eea30609">
                    </div>
                    <div id="postbox-container-2" class="postbox-container">
                        <table class="form-table">
                            <tr>
                                <th>Enable</th>
                                <td>
                                    <input type="checkbox" name="status" <?php if($rule_data->status == 'on'){ echo 'checked'; }     ?>>
                                    <input type="hidden" name="rule_id" value="<?php echo $rule_id; ?>">
                                </td>
                            </tr>
                            <tr>
                                <th>Rule Title</th>
                                <td>
                                    <input type="text" name="post_title" size="30" value="<?php echo $rule_data->name; ?>" id="title" spellcheck="true" autocomplete="off" placeholder="Rule name">
                                </td>
                            </tr>
                            <tr>
                                <th>Rule Image</th>
                                <td>                        
                                    <input type='text' id='logo_url' readonly='readonly' name='logo' size='40' value="<?php echo $rule_data->rule_img; ?>" />
                                    <input id='upload_button' type='button' class='button' value='<?php _e( 'Upload offer image', 'omnizz' ); ?>' />
                                    <div>Image size must be 50 * 50 </div>
                                </td>
                            </tr>
                            <tr>
                                <th>Start Date:</th>
                                <td><input type="text" name="start_date" class="start-date" value="<?php echo $rule_data->start_date; ?>"></td>
                            </tr>
                            <tr>
                                <th>End Date:</th>
                                <td><input type="text" data-date-format="yyyy-mm-dd hh:ii" name="end_date" class="end-date" value="<?php echo $rule_data->end_date; ?>"></td>
                            </tr>
                            <tr>
                                <th>Product List</th>
                                <td class="meta-selection">
                                    <div class="byid selection-type">
                                        <div class="prd-search">
                                            <strong>Width</strong><input type="text" name="width" class="width">
                                            <strong>Ration</strong><input type="text" name="ratio" class="ratio">
                                            <strong>Diameter</strong><input type="text" name="diameter" class="diameter">
                                            <select class="category">
                                                <option value="">Select Category</option>
                                                <?php 
                                                $taxonomy = 'product_cat';
                                                $terms=  get_terms($taxonomy);
                
                                                $terms_select="";
                                                foreach ($terms as $term)
                                                {
                                                    echo $terms_select='<option value="'.$term->name.'">'.$term->name.'</option>';
                                                }
                                                ?>
                                            </select>
                                            <button class="search-btn">Search</button>
                                        </div>
                                                                               
                                    </div>
                                </td>
                            </tr>
                            <tr>
                                <td>
                                    
                                </td>
                            </tr>
                        </table>
                        <div class="product-container">
                            <div class="product-details">
                                <h2>Search Product List</h2>
                                <div class="header">                        
                                    <div class="name"><strong>Name</strong></div>
                                    <div class="price"><strong>MRP</strong></div>
                                    <div class="price"><strong>Web Price</strong></div>
                                    <div class="add"><strong>Add</strong></div>
                                </div>      

                            </div>

                            <div class="selected-products"><h2>Selected Product List</h2>
                                            <div class="header">
                                                <div class="remove"><strong>Remove</strong></div>
                                                <div class="name"><strong>Name</strong></div>
                                                <div class="price"><strong>MRP</strong></div>
                                                <div class="price"><strong>Web Price</strong></div>
                                                <div class="amount"><strong>Discount</strong></div>
                                                <div class="status"><strong>On/Off</strong></div>
                                            </div>
                                            <?php 
                                                $list_sql = "SELECT * FROM th_discount_product_list where rule_id = $rule_id";
                                                $list_data = $wpdb->get_results($list_sql);
                                                //$rule_data = $rule_data[0];
                                                foreach ($list_data as $key => $list_row) { 
                                                    $variation_ID = $list_row->product_id;
                                                    $product_variation = wc_get_product( $variation_ID );

                                                    $variation_des = $product_variation->get_description();
                                                    $variation_price = $product_variation->get_price();
                                                    $regular_price = $product_variation->get_regular_price();
                                                    $sale_price = $product_variation->get_sale_price();
                                                    $args = array(
                                                            'ex_tax_label'       => false,
                                                            'currency'           => '',
                                                            'decimal_separator'  => wc_get_price_decimal_separator(),
                                                            'thousand_separator' => wc_get_price_thousand_separator(),
                                                            'decimals'           => wc_get_price_decimals(),
                                                            'price_format'       => get_woocommerce_price_format(),
                                                          );
                                                    $variation_price = wc_price( $variation_price, $args );
                                                     $regular_price = wc_price( $regular_price, $args );
                                                     if($sale_price == ''){
                                                        $sale_price = '-';
                                                    }else{
                                                        $sale_price = wc_price( $sale_price, $args );
                                                    }                         
                                                ?>

                                            <div class="single-product" data-id="<?php echo $list_row->product_id; ?>" id='<?php echo $list_row->product_id; ?>'>
                                                <div class="remove"><span><<</span></div>               
                                                <div class="name"><?php echo $variation_des; ?></div>
                                                 <div class="price regular-price"><?php echo $regular_price; ?></div>
                                                <div class="price sale-price"><?php echo $sale_price; ?></div>
                                                <div class="amount">
                                                    <input type="text" name="discount_amount" class="discount_amount" value="<?php echo $list_row->amount; ?>">
                                                </div>  
                                                 <div class="status"><input type="checkbox" name="status" class="prd-status" <?php if($list_row->status == 'on'){ echo 'checked'; } ?>></div>                       
                                            </div>
                                        <?php } ?>
                                        </div>

                        </div>
                        <table class="form-table" style="width: 96%;">
                            <tr>
                                <td></td>
                                <td style="text-align: right;"><input type="submit" name="publish" id="publish" class="button button-primary button-large update_discount" value="Update"></td>
                            </tr>
                        </table>
                        <span class="message-block"></span>
                    </div>
                </div>
            </div>
        </div>
    </form>
    <?php
}
function delete_discount_rule()
{
    $rule_id = $_GET['id'];
    global $woocommerce , $wpdb;
    
    $sql = "DELETE FROM th_discount_rule where rule_id = $rule_id";
    $rule_del = $wpdb->get_results($sql);
    wp_redirect('?page=discount_rule');
}

add_action( 'woocommerce_before_calculate_totals', 'discount_custom_price' );
function discount_custom_price( $cart_object )
{
    date_default_timezone_set('Asia/Kolkata');
    $today_date = date('Y-m-d G:i');
    $today_date = strtotime($today_date);
    global $woocommerce , $wpdb;
  
   $sql = "SELECT * FROM th_discount_rule where status = 'on'";
    $rule_data = $wpdb->get_results($sql);
     
    // var_dump($rule_data);
    $prd_discount_arr = [];

    if($rule_data)
    {
        foreach ($rule_data as $key => $rule_row)
        {
            $start_date = strtotime($rule_row->start_date);
            $end_date = strtotime($rule_row->end_date);
            if($today_date > $start_date && $today_date < $end_date)
            {
                $rule_id = $rule_row->rule_id;
                $list_sql = "SELECT * FROM th_discount_product_list where rule_id = $rule_id and status = 'on'";

                $list_result = $wpdb->get_results($list_sql);
                
                if(!empty($list_result))
                {
                    //var_dump($list_result);
                    foreach ($list_result as $key => $list_row)
                    {
                        $list_prd_id = $list_row->product_id;
                        $list_prd_amount = $list_row->amount;
                        if(array_key_exists($list_prd_id, $prd_discount_arr))
                        {
                            $old_value = $prd_discount_arr[$list_prd_id];
                            if($list_prd_amount > $old_value)
                            {
                                $prd_discount_arr[$list_prd_id] = $list_prd_amount;
                            }
                        }   
                        else{
                            $prd_discount_arr[$list_prd_id] = $list_prd_amount;
                        }               
                    }   
                }
            }
        }
    }
   
        foreach ( $cart_object->cart_contents as $value )
        {
            $cart_prd_id = $value['variation_id'];
            $product_data = wc_get_product( $cart_prd_id );
            //var_dump($product_data);
            $sale_price = get_post_meta($cart_prd_id , '_sale_price','true');
            $regular_price = get_post_meta($cart_prd_id , '_regular_price','true');

            $price = $regular_price;
            if($sale_price != ''){
                $price = $sale_price;
            }

            if (array_key_exists($cart_prd_id, $prd_discount_arr) )
            {
                $discount_amount = $prd_discount_arr[$cart_prd_id];
                //echo $regular_price = $value['data']->get_price();
                $new_price = $price - $discount_amount;
                $value['data']->set_price($new_price);
            }
            
        }
   

}

add_filter( 'woocommerce_get_price_html', 'kd_custom_price_message' );
function kd_custom_price_message( $price )
{
    date_default_timezone_set('Asia/Kolkata');
    $today_date = date('Y-m-d G:i');
    $today_date = strtotime($today_date);
    global $woocommerce , $product ,$wpdb;
    $current_prd_id = $product->get_id();
    $current_prd_amount = $product->get_price();
    $current_regular_price = $product->get_regular_price();
    $current_sale_price = $product->get_sale_price();

    $sql = "SELECT * FROM th_discount_rule where status = 'on'";
    $rule_data = $wpdb->get_results($sql);

    $prd_discount_arr = [];
    foreach ($rule_data as $key => $rule_row)
    {
        $start_date = strtotime($rule_row->start_date);
        $end_date = strtotime($rule_row->end_date);
        if($today_date > $start_date && $today_date < $end_date)
        {
            $rule_row->name;
            $rule_id = $rule_row->rule_id;
            $rule_img = $rule_row->rule_img;
            $list_sql = "SELECT * FROM th_discount_product_list where rule_id = $rule_id and status = 'on'";

            $list_result = $wpdb->get_results($list_sql);
        

            if(!empty($list_result))
            {
                //var_dump($list_result);
                foreach ($list_result as $key => $list_row)
                {
                    $list_prd_id = $list_row->product_id;
                    $list_prd_amount = $list_row->amount;
                    if(array_key_exists($list_prd_id, $prd_discount_arr))
                    {
                        $old_value = $prd_discount_arr[$list_prd_id];
                        if($list_prd_amount > $old_value)
                        {
                            $prd_discount_arr[$list_prd_id] = $list_prd_amount;
                        }
                    }   
                    else{
                        $prd_discount_arr[$list_prd_id] = array($list_prd_amount , $rule_img);
                    }               
                }   
            }
        }
    }
   
   if(array_key_exists($current_prd_id, $prd_discount_arr))
   {

        if($current_sale_price == '')
        {
            $discount_amount = $prd_discount_arr[$current_prd_id][0]; 

                    
        }
        else
        {
            $save = $current_regular_price - $current_sale_price;
            $discount_amount = $prd_discount_arr[$current_prd_id][0] + $save;
            
        }

        $dis_per = ($discount_amount * 100 ) / $current_regular_price;

        $dis_per_html = '';
        if($dis_per >=5){
            $dis_per = number_format($dis_per,1,'.','');
            $dis_per_html = '<div class="save">('.$dis_per.'% off)</div>';

        }

        $save_html = '<div class="save">You save: '.get_woocommerce_currency_symbol(). $discount_amount .'</div>';
        $img = '<img class="discount-img" src="'.$prd_discount_arr[$current_prd_id][1].'"></img>';

        $new_price = $current_regular_price - $discount_amount;
        
        $price = '<span class="price"><ins><span class="woocommerce-Price-amount amount"><span class="woocommerce-Price-currencySymbol">₹</span>'.number_format((float)$new_price, 2, '.', '').'</span></ins><div class="old-price">M.R.P.:<del><span class="woocommerce-Price-amount amount"><span class="woocommerce-Price-currencySymbol">₹</span>'.number_format((float)$current_regular_price, 2, '.', '').'</span></del>'.$dis_per_html.'</div></span>';
   }
   else{

        if($current_sale_price != '')
        {
            $save = $current_regular_price - $current_sale_price;
            if($current_regular_price){
            	$discount_amount = $save;
            $dis_per = ($discount_amount * 100 ) / $current_regular_price;
           
            }
            
            
            $dis_per_html = '';
            if($dis_per >=5){
                $dis_per = number_format($dis_per,1,'.','');
                $dis_per_html = '<div class="save">('.$dis_per.'% off)</div>';

            }

            

            if($dis_per >=5){   
                $price = '<span class="price"><ins><span class="woocommerce-Price-amount amount"><span class="woocommerce-Price-currencySymbol">₹</span>'.number_format((float)$current_sale_price, 2, '.', '').'</span></ins> <div class="old-price">M.R.P.:<del><span class="woocommerce-Price-amount amount"><span class="woocommerce-Price-currencySymbol">₹</span>'.number_format((float)$current_regular_price, 2, '.', '').'</span></del>'.$dis_per_html.'</div></span>';             
                $save_html = '<div class="save">You save: '.get_woocommerce_currency_symbol(). $discount_amount .' </div>';
            }else{
               
                $price = '<ins><span class="woocommerce-Price-amount amount"><span class="woocommerce-Price-currencySymbol">₹</span>'.number_format((float)$current_sale_price, 2, '.', '').'</span></ins>';
                echo'<style>';
                echo '.post-'.$current_prd_id.' .onsale { display:none;}';
                echo '</style>';
                
            }
            
        }
   }
    
    return $price.$save_html.$img;
}


include_once('function.php');