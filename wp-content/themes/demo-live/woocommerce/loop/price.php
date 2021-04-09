<?php
/**
 * Loop Price
 *
 * This template can be overridden by copying it to yourtheme/woocommerce/loop/price.php.
 *
 * HOWEVER, on occasion WooCommerce will need to update template files and you
 * (the theme developer) will need to copy the new files to your theme to
 * maintain compatibility. We try to do this as little as possible, but it does
 * happen. When this occurs the version of the template file will be bumped and
 * the readme will list any important changes.
 *
 * @see 	    https://docs.woocommerce.com/document/template-structure/
 * @author 		WooThemes
 * @package 	WooCommerce/Templates
 * @version     1.6.4
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit; // Exit if accessed directly
}

global $product;

    global $woocommerce ,$wpdb;
    $user_id = get_current_user_id(); 
    $SQL="SELECT * FROM th_installer_data WHERE user_id='".$user_id."' AND is_franchise='yes'";
    $franchise=$wpdb->get_row($SQL);
    if($franchise){
            $rowcount=$wpdb->get_row("SELECT SUM(qty) as qty FROM  th_franchise_wishlist WHERE product_id = '".$product->get_id()."' and franchise_id = '$franchise->installer_data_id'");
    //echo $rowcount->qty;
    $wishqty= ($rowcount->qty>0)? $rowcount->qty : 0 ;  
    }

?>

<?php if ( $price_html = $product->get_price_html() ) : ?>
	<span class="price"><?php echo $price_html; ?></span>
	<div class="price-text">(Inclusive of all taxes)</div>
	<?php 
	 	$min = 1;
        $max = 5;
        $step = 1;
        $options = '';
           
        for ( $count = $min; $count <= $max; $count = $count+$step ) {
            $options .= '<option value="' . $count . '">' . $count . '</option>';
        }
    ?>
        <div class="qty-wish">
         <div class="quantity"><strong>Qty</strong>
            <select name="quantity" class="product-qty"><?php echo $options; ?></select>
         </div>
         <?php if($franchise){?>
        <a href="javascript:void(0);" title="Add To Admin Cart" id="wish<?=$product->get_id();?>" data-product_id="<?=$product->parent_id;?>" data-variation_id="<?=$product->get_id();?>" data-quantity="1" data-wish="1" data-type="offline" class="pro-wishlist offline-wishlist" rel="nofollow">
        <span class="icon"><img src="<?php echo get_template_directory_uri(); ?>/assest/images/favorites_icon.png" alt=""></span><span class="count" id="wishcount"><?=$wishqty;?></span>
        </a>
        <?php }?>
        </div>


        
      
<?php endif; ?>
</div>