<?php
function brand_update(){

    global $woocommerce,$wpdb;
    $term_id=$_GET['term_id'];
    $term = get_term_by('id', $term_id, 'product_cat');
    
    $thumbnail_id = get_woocommerce_term_meta($term_id, 'thumbnail_id', true );
    $image = wp_get_attachment_url( $thumbnail_id );

    if($image){
      $display='';  
      }else{
       $display='display:none;';  
      }
?>
<div class="wrap">
 	<h1 class="wp-heading-inline">Update Brand</h1>
    <span hidden="" class="admin-url"><?php echo admin_url('admin-ajax.php'); ?></span>
    <br>
  <a href="?page=product-add-new" class="page-title-action">Add New Product</a>
  <a href="?page=add-new-brand" class="page-title-action">Add New Brand</a>
  <a href="?page=product-add-new-variant" class="page-title-action">Add New Variant</a>
	<form action="" method="post" autocomplete="off">
        <input type="hidden" name="term_id" value="<?=$term->term_id;?>">
 	<table class="form-table" >
 		<tbody>
            
 			<tr>
 				<th>Name</th>
 				<td><input type="text" name="cate_name" value="<?=$term->name;?>"></td>
 			</tr>
            <tr>
                <th>Slug</th>
                <td><input type="text" name="slug" value="<?=$term->slug;?>"></td>
            </tr>
            <tr>
                <th>Description</th>
                <td>
                    <textarea name="description" rows="5" cols="35"><?=$term->description;?></textarea>
                </td>
            </tr>
           
       
            <tr>
                <th>Thumbnail</th>
                <td>
                   <input type="button" value="Upload Image" class="button-primary" id="upload_image"/>
                    <input type="hidden" name="attachment_id" class="wp_attachment_id" value="" /> </br>
                    <img src="" class="image" style="display:none;margin-top:10px;"/>
                    <img src="<?=$image;?>" class="image-shiv" style="<?=$display;?> margin-top:10px;"/>
                            
                </td>
            </tr>
 			
 		</tbody>
 		
 	</table>
 
    <button class="button-primary woocommerce-save-button add-installer-btn" type="submit" name="submit">
                    Save Changes</button> 
    <a href="<?=get_admin_url();?>/admin.php?page=brands-list" class="button-primary woocommerce-save-button add-installer-btn">Back</a> 
 	</form>
 	<?php

 	if(isset($_POST['submit']))
 	{
 		global $woocommerce, $wpdb;
 		
 		$term_id = $_POST['term_id'];
        $name = $_POST['cate_name'];
        $slug = $_POST['slug'];
        $description = $_POST['description'];
        $attachment_id = $_POST['attachment_id'];

       $update = wp_update_term($term_id, 'product_cat', array(
                        'name'=> $name,
                        'description'=> $description,
                        'slug' => $slug,
                        'parent' =>0
                    ) );



                if($attachment_id){
                    update_woocommerce_term_meta($term_id, 'thumbnail_id', absint($attachment_id ) );                    
                }
             ?>

            <div class="notice notice-success is-dismissible"> 
                <p><strong>Brand updated successfuly.</strong></p>
            </div>
            <?php
 	}
 	?>
 </div>
 	<?php
}
