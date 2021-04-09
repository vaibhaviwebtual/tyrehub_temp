<?php
function tim_add_new_brand(){
?>
<div class="wrap">
 	<h1 class="wp-heading-inline">Add New Brand</h1>
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
                <td> <input type="checkbox" class="" name="visibility" <?php if($_POST['visibility']) { echo 'checked';}?> /></td>
            </tr>
            
 			<tr>
 				<th>Name</th>
 				<td><input type="text" name="cate_name" value="<?=$_POST['cate_name'];?>"></td>
 			</tr>
            <tr>
                <th>Slug</th>
                <td><input type="text" name="slug" value="<?=$_POST['slug'];?>"></td>
            </tr>
            <tr>
                <th>Description</th>
                <td>
                    <textarea name="description" rows="5" cols="35"><?=$_POST['description'];?></textarea>
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
 		
 		$name = $_POST['cate_name'];
        $slug = $_POST['slug'];
        $description = $_POST['description'];
        $attachment_id = $_POST['attachment_id'];

       
                $cid = wp_insert_term($name, // the term 
                    'product_cat', // the taxonomy
                    array(
                        'description'=> $description,
                        'slug' => $slug,
                        'parent' =>0
                    )
                );

                if(!is_wp_error($cid)){
                    $cat_id = isset( $cid['term_id'] ) ? $cid['term_id'] : 0;
                    update_woocommerce_term_meta( $cat_id, 'thumbnail_id', absint($attachment_id ) );
                }
        add_term_meta( $term_data['term_id'], 'thumbnail_id', 444 );
 	}
 	?>
 </div>
 	<?php
}
