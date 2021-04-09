<?php

if ( !is_user_logged_in() )
 	{
      wp_redirect(get_permalink( get_option('woocommerce_myaccount_page_id') ).'/installer-login' );    
  	}
get_header();
?>
<div id="pageContent">
	<div class="container installer-home">
		
		<div class="woocommerce">
			<style type="text/css">
				.supplier-prd-search label { 
					width: 100%; 
				}
				.supplier-prd-search input[type="text"] { 
					max-width: 100%; 
					padding: 0px 5px; 
					height: 35px; 
				}
				.supplier-prd-search select { 
					max-width: 100%;
					padding: 0px 5px;
					height: 35px;
					width: 100%;
					border-radius: 0; 
				}
				.search-btn {
					background-color: #2f3672;
					color: #fff;
					border: 0px;
					height: 35px;
					padding: 0px 20px;
					margin-top: 28px;
				}
				.installer-home .product-container{ margin-top: 50px; }
				</style>
			
			<?php
			
			if ( ! defined( 'ABSPATH' ) ) {
				exit;
			}

			wc_print_notices();
		
			$user = wp_get_current_user();
		   	$role = ( array ) $user->roles;
		   	$current_user_role = $role[0];
		   	do_action( 'woocommerce_account_navigation' );
		//	do_action( 'woocommerce_account_content' ); ?>
			<div class="woocommerce-MyAccount-content <?php if($current_user_role == 'btobpartner'){echo 'supplier-account';} ?>">
				<div class="supplier-prd-search" style="margin-top: 20px;">
	            <div class="row">
	            	<div class="col-md-2">
	            		<label for="account_display_name"><?php esc_html_e('Width', 'woocommerce' ); ?>&nbsp;<span class="required">*</span></label>
	            		<input type="text" name="width" class="width" value="<?=$_POST['width'];?>">
	            	</div>
	            	<div class="col-md-2">
	            		<label for="account_display_name"><?php esc_html_e('Ration', 'woocommerce' ); ?>&nbsp;<span class="required">*</span></label>
	            		<input type="text" name="ratio" class="ratio" value="<?=$_POST['ratio'];?>">
	            	</div>
	            	<div class="col-md-2">
	            		<label for="account_display_name"><?php esc_html_e('Diameter', 'woocommerce' ); ?>&nbsp;<span class="required">*</span></label>
	            		<input type="text" name="diameter" class="diameter" value="<?=$_POST['diameter'];?>">
	            	</div>
	            	<div class="col-md-3">
	            		<label for="account_display_name"><?php esc_html_e('Category', 'woocommerce' ); ?>&nbsp;<span class="required">*</span></label>
	            		<select class="searchbyname" name="category">
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
	            	</div>
	            	<div class="col-md-3">
	            		<button class="search-btn searchbywidth btn btn-invert" id="supplier_prodct_search">Search</button>

	            	</div>
	           	</div>
	           	
	           </div>
				 <div class="product-container"> 
				<!-- <div class="product-container"> -->
					Please search for purchase
				</div>
					
			
			</div>
		</div>
	</div>
</div>

<?php
get_footer();
?>
