<?php
function product_update(){

    global $woocommerce,$wpdb;
    $product_id=$_GET['post_id'];

    $product = wc_get_product( $product_id );
    $attachment_ids = $product->get_gallery_image_ids();

    
    //$post = get_post($product_id);
    // let's get a post title by ID
    //get_post_meta($product_id,'_guarantee',true);
    $guarantee=get_post_meta($product_id,'_guarantee',true);
    $guarantee_cart =get_post_meta($product_id,'_guarantee_cart',true);
    $cate =  wp_get_post_terms($product_id,'product_cat', array('fields' => 'all') );
    $image = wp_get_attachment_image_src( get_post_thumbnail_id($product_id), 'single-post-thumbnail' );
    if($image){
      $display='';  
      }else{
       $display='display:none;';  
      }
    
?>
<div class="wrap">
    <h1 class="wp-heading-inline">Update Product</h1>
    <span hidden="" class="admin-url"><?php echo admin_url('admin-ajax.php'); ?></span>
    <br>
 
    <a href="?page=add-new-brand" class="page-title-action">Add New Brand</a>    
   <a href="?page=product-add-new" class="page-title-action">Add New Product</a>
   <a href="?page=product-add-new-variant" class="page-title-action">Add New Variant</a>
    <form action="" method="post" autocomplete="off">
        <input type="hidden" name="product_id" value="<?=$product_id?>">
    <table class="form-table" >
        <tbody> 
            <tr>
                <th>Enable</th>
                <td> <input type="checkbox" class="" name="visibility" value="publish" <?php if($product->post->post_status=='publish') { echo 'checked';}?> /></td>
            </tr>
           
            <tr>
                <th>Product Segment</th>
                <td><input type="text" name="product_name" value="<?=$product->get_title();?>"></td>
            </tr>
            <tr>
                <th>Category (Brand)</th>
                <td>
                <select name="category">
                    <option value="">--Choose Category--</option>
                    <?php 
                    $terms = get_terms(array('taxonomy'=>'product_cat','hide_empty' => false));

                    foreach ( $terms as $term ){?>
                    <option value="<?php echo $term->term_id; ?>" <?php if($cate[0]->term_id==$term->term_id){ echo 'selected';} ?>><?php echo $term->name; ?></option>
                    <?php }?>
                </select>

                </td>
            </tr>
           <tr>
                <th>G/W Tyre profile info</th>
                <td>
               <textarea class="short" style="" required name="guarantee" id="guarantee" placeholder="" rows="5" cols="35"><?=$guarantee;?></textarea>
                </td>
            </tr>
           
           <tr>
                <th>G/W Cart & Invoice info</th>
                <td>
              <textarea class="short" style="" required name="guarantee_cart" id="guarantee_cart" placeholder="" rows="5" cols="35"><?=$guarantee_cart;?></textarea>

                </td>
            </tr>
           
            <tr>
                <th>Thumbnail</th>
                <td>
                   <input type="button" value="Upload Image" class="button-primary" id="upload_image"/>
                   <input type="hidden" name="attachment_id" class="wp_attachment_id" value="" /> </br>
                    <img src="<?php  echo $image[0]; ?>" width="100" class="image" style="<?=$display;?> margin-top:10px;"/>
                            
                </td>
            </tr>
            <tr>
                <th>Gallery</th>
                <td>

                    <?php for($i=1; $i<=5; $i++){?>
                 <input type="button" class="button button-secondary upload-button<?=$i?>" value="Upload Image" data-group="<?=$i?>">
                  <input type="hidden" name="attachment[]" class="wp_attachment_id<?=$i?>" value="" />
                    <img src="" class="image<?=$i?>" width="100" style="display:none;margin-top:10px;"/>
                    
                    <?php }?>
                   <br>
                    <?php 
                    if($attachment_ids){
                        $i=1;
                      foreach( $attachment_ids as $attachment_id ) {
                        $image_link = wp_get_attachment_url( $attachment_id );
                        echo '<img src="'.$image_link.'" width="100" class="image<?=$i?>" style="margin-top:10px;"/>';
                        $i++;
                      }  
                    }
                    
                    ?>
                 

                
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

        $post_id = $_POST['product_id'];
        $product_name = $_POST['product_name'];
        $visibility = $_POST['visibility'];
        $cat_ids =array($_POST['category']);
        $attachment_id = $_POST['attachment_id'];
        $attachment = $_POST['attachment'];
        $data=array(
               'ID' =>  $post_id,
              'post_title'=>$product_name, 
              'post_type'=>'product',
              'post_status' =>$visibility
            );
    
        wp_update_post($data);
    if($attachment_id){
      set_post_thumbnail($post_id, $attachment_id);  
    }
     

    wp_set_object_terms($post_id,null, 'product_cat'); 
    //$cat_ids1 = array( 6, 8 );      
    $cat_ids = array_map( 'intval', $cat_ids);
    $term_taxonomy_ids = wp_set_object_terms($post_id, $cat_ids, 'product_cat');
    wp_set_object_terms( $post_id, 'variable', 'product_type', false );
    if($post_id){
        
        update_post_meta( $post_id, '_guarantee',$_POST['guarantee']);
        update_post_meta( $post_id, '_guarantee_cart',$_POST['guarantee_cart']);
        if(count($attachment)>0){
            update_post_meta($post_id,"_product_image_gallery",implode(',',$attachment));
        }        
        
    }

    wp_redirect(site_url('/wp-admin/admin.php?page=product-manage'));   
    }

    ?>
 </div>
 	<?php
}
