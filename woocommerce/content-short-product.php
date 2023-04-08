<?php
	global $product, $woocommerce_loop, $post;
	$bookio_settings = bookio_global_settings();
	$stock = ( $product->is_in_stock() )? 'in-stock' : 'out-stock' ;
	add_action('woocommerce_after_shop_loop_item', 'bookio_woocommerce_template_loop_add_to_cart', 15 );
	remove_action('woocommerce_after_shop_loop_item', 'bookio_add_loop_wishlist_link', 20 );
?>
<div class="products-entry content-product4 clearfix product-wapper">
	<div class="products-thumb">
		<?php
			/**
			 * woocommerce_before_shop_loop_item_title hook
			 *
			 * @hooked woocommerce_show_product_loop_sale_flash - 10
			 * @hooked woocommerce_template_loop_product_thumbnail - 10
			 */
			do_action( 'woocommerce_before_shop_loop_item_title' );
		?>
		<?php 
			if(isset($bookio_settings['product-wishlist']) && $bookio_settings['product-wishlist'] && class_exists( 'WPCleverWoosw' ) ){
				bookio_add_loop_wishlist_link();
			}
		?>
		<div class='product-button'>
			<?php do_action('woocommerce_after_shop_loop_item'); ?>
		</div>
		<?php if($stock == "out-stock"): ?>
			<div class="product-stock">    
				<span class="stock"><?php echo esc_html__( "Out Of Stock", "bookio" ); ?></span>
			</div>
		<?php endif; ?>
	</div>
	<div class="products-content">
		<div class="contents">
			<?php woocommerce_template_loop_rating(); ?>
			<?php bookio_author_product(); ?>
			<h3 class="product-title"><a href="<?php esc_url(the_permalink()); ?>"><?php esc_html(the_title()); ?></a></h3>
			<div class="description">
				<?php echo apply_filters( 'woocommerce_short_description', wp_kses( $post->post_excerpt,'social' ) ) ?>
			</div>
			<?php do_action( 'woocommerce_after_shop_loop_item_title' ); ?>
		</div>
	</div>
</div>