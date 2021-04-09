<?php
function tim_add_new_product(){
?>
<div class="wrap">
 	<h1 class="wp-heading-inline">Add New Product</h1>
    <span hidden="" class="admin-url"><?php echo admin_url('admin-ajax.php'); ?></span>
    <br>
  <a href="?page=product-add-new" class="page-title-action">Add New Product</a>
  <a href="?page=add-new-brand" class="page-title-action">Add New Brand</a>
  <a href="?page=product-add-new-variant" class="page-title-action">Add New Variant</a>
	<form action="" method="post" autocomplete="off">
 	<table class="form-table" >
 		<tbody> 
            <tr>
                <th>Enable</th>
                <td> <input type="checkbox" class="" name="visibility" value="publish" <?php if($_POST['visibility']) { echo 'checked';}?> /></td>
            </tr>
           
 			<tr>
 				<th>Product Segment</th>
 				<td><input type="text" name="product_name" value="<?=$_POST['product_name'];?>"></td>
 			</tr>
            <tr>
                <th>Category (Brand)</th>
                <td>
                <select name="category">
                    <option value="">--Choose Category--</option>
                    <?php 
                    $terms = get_terms(array('taxonomy'=>'product_cat','hide_empty' => false));

                    foreach ( $terms as $term ){?>
                    <option value="<?php echo $term->term_id; ?>"><?php echo $term->name; ?></option>
                    <?php }?>
                </select>

                </td>
            </tr>
           <tr>
                <th>G/W Tyre profile info</th>
                <td>
               <textarea class="short" style="" required name="guarantee" id="guarantee" placeholder="" rows="5" cols="35"></textarea>
                </td>
            </tr>
           
           <tr>
                <th>G/W Cart & Invoice info</th>
                <td>
              <textarea class="short" style="" required name="guarantee_cart" id="guarantee_cart" placeholder="" rows="5" cols="35"></textarea>

                </td>
            </tr>
           
            <tr>
                <th>Thumbnail</th>
                <td>
                   <input type="button" value="Upload Image" class="button-primary" id="upload_image"/>
                   <input type="hidden" name="attachment_id" class="wp_attachment_id" value="" /> </br>
                    <img src="" class="image" style="display:none;margin-top:10px;"/>
                            
                </td>
            </tr>
            <tr>
                <th>Gallery</th>
                <td>

                    <?php for($i=1; $i<=5; $i++){?>
                 <input type="button" class="button button-secondary upload-button<?=$i?>" value="Upload Image" data-group="<?=$i?>">
                  <input type="hidden" name="attachment[]" class="wp_attachment_id<?=$i?>" value="" />
                    <img src="" class="image<?=$i?>" style="display:none;margin-top:10px;"/>
         
                    <?php }?>
                   

                 

                
                </td>
            </tr>

 			
 		</tbody>
 		
 	</table>

    <button class="button-primary woocommerce-save-button add-installer-btn" type="submit" name="submit">
                    Save Changes</button> 
    <a href="<?=get_admin_url();?>/admin.php?page=product-manage" class="button-primary woocommerce-save-button add-installer-btn">Back</a> 
 	</form>

 	<?php

 	if(isset($_POST['submit']))
 	{
 		global $woocommerce, $wpdb; 		
 		$product_name = $_POST['product_name'];
 		$visibility = $_POST['visibility'];
        $cat_ids =array($_POST['category']);
 		$attachment_id = $_POST['attachment_id'];
        $attachment = $_POST['attachment'];
        $data=array(
              'post_title'=>$product_name, 
              'post_type'=>'product',
              'post_status' =>$visibility
            );
        $post_id = wp_insert_post($data);
    set_post_thumbnail($post_id, $attachment_id); 

    wp_set_object_terms($post_id,null, 'product_cat'); 
    //$cat_ids1 = array( 6, 8 );      
    $cat_ids = array_map( 'intval', $cat_ids);
    $term_taxonomy_ids = wp_set_object_terms($post_id, $cat_ids, 'product_cat');
    wp_set_object_terms( $post_id, 'variable', 'product_type', false );
    if($post_id){
        update_post_meta( $post_id, '_stock_status', 'instock');
        update_post_meta( $post_id, 'total_sales', '0' );
        update_post_meta( $post_id, '_downloadable', 'no' );
        update_post_meta( $post_id, '_virtual', 'no' );
        update_post_meta( $post_id, '_regular_price', '' );
        update_post_meta( $post_id, '_sale_price', '' );
        update_post_meta( $post_id, '_purchase_note', '' );
        update_post_meta( $post_id, '_featured', 'no' );
        update_post_meta( $post_id, '_guarantee',$_POST['guarantee']);
        update_post_meta( $post_id, '_guarantee_cart',$_POST['guarantee_cart']);
        update_post_meta( $post_id, '_product_attributes', array() );
        update_post_meta( $post_id, '_sale_price_dates_from', '' );
        update_post_meta( $post_id, '_sale_price_dates_to', '' );
        update_post_meta( $post_id, '_price', '' );
        update_post_meta( $post_id, '_sold_individually', '' );
        update_post_meta( $post_id, '_manage_stock', 'no' );
        update_post_meta( $post_id, '_backorders', 'no' );
        update_post_meta( $post_id, '_stock', '' );
        update_post_meta($post_id,"_product_image_gallery",implode(',',$attachment));
    }

    wp_redirect(site_url('/wp-admin/admin.php?page=product-manage'));   
 	}

 	?>
 </div>
 	<?php
}
