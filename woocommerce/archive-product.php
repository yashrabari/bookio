<?php
/**
 * The Template for displaying product archives, including the main shop page which is a post type archive
 *
 * This template can be overridden by copying it to yourtheme/woocommerce/archive-product.php.
 *
 * HOWEVER, on occasion WooCommerce will need to update template files and you
 * (the theme developer) will need to copy the new files to your theme to
 * maintain compatibility. We try to do this as little as possible, but it does
 * happen. When this occurs the version of the template file will be bumped and
 * the readme will list any important changes.
 *
 * @see https://docs.woocommerce.com/document/template-structure/
 * @package WooCommerce/Templates
 * @version 3.4.0
 */
if ( ! defined( 'ABSPATH' ) ) {
	exit; // Exit if accessed directly
}
$taxonomy =  get_query_var('taxonomy');
if($taxonomy == 'product_author'){
	wc_get_template_part( 'archive', 'product-author' );
}else{
	global $bookio_settings;
	get_header();
	do_action( 'woocommerce_before_main_content' );
	$enable_page_title 				= bookio_get_config('page_title',true);
	$show_catagories_top 			= bookio_get_config('show_catagories_top','no');
	$show_bestseller_category 		= bookio_get_config('show-bestseller-category','no');
	$show_featured_category 		= bookio_get_config('show-featured-category','no');
	$show_banner	 				= bookio_get_config('show-banner-category','no');
	$banner_shop	 				= isset($bookio_settings['banner-shop']['url']) ? $bookio_settings['banner-shop']['url'] : "";
	$link_banner_shop	 			= bookio_get_config('link-banner-shop','');
	$title_banner_shop	 			= bookio_get_config('title-banner-shop','');
	$description_banner_shop	 	= bookio_get_config('desc-banner-shop','');
	$button_banner_shop	 			= bookio_get_config('button-banner-shop','');
	$category_view_mode 			= bookio_category_view();
	if(!isset($layout_shop)){
		$layout_shop = bookio_get_config('layout_shop','1');
	}
	?>
	<div class="container">
		<?php if( $banner_shop && ($show_banner == "yes") ) : ?>
			<div class="banner-shop">
				<a href="<?php echo esc_url($link_banner_shop ) ?>">
					<img src="<?php echo esc_url($banner_shop); ?>" alt="<?php echo esc_attr__("banner category",'bookio');?>" ></a>
					<div class="content-info">
						<div class="content">
							<div class="title"><?php echo wp_kses($title_banner_shop,'social') ?></div>
							<div class="description"><?php echo wp_kses($description_banner_shop,'social') ?></div>
							<a class="button" href="<?php echo esc_html($link_banner_shop ) ?>"><?php echo esc_html($button_banner_shop ) ?></a>
						</div>
					</div>
				
			</div>
		<?php endif; ?>
		<?php if($show_catagories_top == "yes" && function_exists('is_shop') && is_shop() ) { wc_get_template_part( 'content', 'categories-top' ); } ?>
		<div class="main-archive-product row style-<?php echo esc_attr($layout_shop); ?> <?php echo esc_attr($category_view_mode); ?>">
			<div class="bwp-sidebar sidebar-product <?php echo esc_attr(bookio_get_class()->class_sidebar_left); ?>">
				<div class="button-filter-toggle hidden-lg hidden-md">
					<?php echo esc_html__("Close","bookio") ?>
				</div>
				<?php if ( ( class_exists("WCV_Vendors") && WCV_Vendors::is_vendor_page() ) || is_tax('dc_vendor_shop') ) { ?>
					<?php dynamic_sidebar( 'sidebar-vendor' ); ?>
				<?php }else{ ?>	
					<?php dynamic_sidebar( 'sidebar-product' ); ?>
				<?php } ?>
			</div>
			<div class="<?php echo esc_attr(bookio_get_class()->class_product_content); ?>" >
				<?php do_action( 'woocommerce_archive_description' ); ?>
				<?php if($show_bestseller_category == "yes" && function_exists('is_shop') && is_shop()) { wc_get_template_part( 'content', 'categories-bestseller' ); } ?>
				<?php if($show_featured_category == "yes" && function_exists('is_shop') && is_shop()) { wc_get_template_part( 'content', 'categories-featured' ); } ?>
				<?php if ( have_posts() ) : ?>
				<div class="content-shop">
					<div class="bwp-top-bar top clearfix">
						<div class="content-top">
							<?php if($enable_page_title){ bookio_page_title();  } ?>
							<?php woocommerce_result_count(); ?>
						</div>
						<div class="content-topbar-bottom">
							<?php bookio_category_top_bar(); ?>
						</div>
					</div>
					<div class="content-products-list">
						<?php woocommerce_product_loop_start(); ?>
							<?php while ( have_posts() ) : the_post(); ?>
								<?php wc_get_template_part( 'content', 'product' ); ?>
							<?php endwhile;  ?>
						<?php woocommerce_product_loop_end(); ?>
					</div>
					<div class="bwp-top-bar bottom clearfix">
						<?php do_action('woocommerce_after_shop_loop'); ?>
					</div>
				</div>	
				<?php else : ?>
					<?php wc_get_template( 'loop/no-products-found.php' ); ?>
				<?php endif; ?>
			</div>
		</div>
	</div>	
	<?php
	do_action( 'woocommerce_after_main_content' );
	get_footer( 'shop' );
	}
?>