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
get_header();
do_action( 'woocommerce_before_main_content' );
$category_view_mode 			= bookio_category_view();
?>
	<div class="container"> 
		<div class="main-archive-product row <?php echo esc_attr($category_view_mode); ?>">
			<div class="col-lg-12 col-sm-12 col-xs-12" >
				<div class="bwp-author-heading clearfix">
					<?php 
					$term = get_term_by( 'slug', get_query_var('term'), get_query_var('taxonomy') ); 
					$thumb 	= ( get_term_meta( $term->term_id, 'image_bid', true ) );
					$thubnail = ( !empty($thumb) && getimagesize($thumb) !== false ) ? $thumb : "http://placehold.it/64x64";
					$author_facebook 	= ( get_term_meta( $term->term_id, 'author_facebook', true ) );
					$author_twitter 	= ( get_term_meta( $term->term_id, 'author_twitter', true ) );
					$author_google 		= ( get_term_meta( $term->term_id, 'author_google', true ) );
					$author_instagram 		= ( get_term_meta( $term->term_id, 'author_instagram', true ) );
					$author_style 		= ( get_term_meta( $term->term_id, 'author_style', true ) );
					?>
					<div class="author-image">
						<div class="images">
							<?php echo '<img src="'.esc_url($thubnail).'" alt="'.esc_html($term->name).'">'; ?>
						</div>
					</div>
					<div class="author-content">
						<div class="author-title">
							<h2><?php echo '<span>'. esc_html( $term->name ); ?></h2>
						</div>
						<div class="author-description">	
						<?php do_action( 'woocommerce_archive_description' ); ?>
						</div>
						<?php	
							$author_address  = get_term_meta( $term->term_id, 'author_address',true) ? get_term_meta( $term->term_id, 'author_address',true) : '';
							$author_birth  = get_term_meta( $term->term_id, 'author_birth',true) ? get_term_meta( $term->term_id, 'author_birth',true) : '';
							$author_gender  = get_term_meta( $term->term_id, 'author_gender',true) ? get_term_meta( $term->term_id, 'author_gender',true) : '';
						?>
						<div class="author-infomation">
							<ul class="author-info">
								<?php if($author_style){ ?>
								<li><label><?php echo esc_html__("Style:","bookio") ?></label> <?php echo esc_html($author_style) ?></li>
								<?php } ?>
								<?php if($author_address){ ?>
								<li><label><?php echo esc_html__("Address:","bookio") ?></label> <?php echo esc_html($author_address) ?></li>
								<?php } ?>
								<?php if($author_birth){ ?>
								<li><label><?php echo esc_html__("Year of Birth:","bookio") ?></label> <?php echo esc_html($author_birth) ?></li>
								<?php } ?>
								<?php if($author_gender){ ?>
									<?php if( $author_gender == 'male'){?>
									<li><label><?php echo esc_html__("Gender:","bookio") ?></label><?php echo esc_html__("Male","bookio") ?></li>
									<?php }else{ ?>
									<li><label><?php echo esc_html__("Gender:","bookio") ?></label><?php echo esc_html__("Female","bookio") ?></li>
									<?php } ?>
								<?php } ?>
								<li><label><?php echo esc_html__("Published Book:","bookio") ?></label><?php echo esc_attr($term->count); ?></li>
							</ul>
						</div>	
						<ul class="social-link">
							<?php if($author_facebook){ ?>
							<li><a href="<?php echo esc_url($author_facebook); ?>"><i class="social_facebook"></i><?php echo esc_html__("Facebook","bookio"); ?></a></li>
							<?php } ?>
							<?php if($author_twitter){ ?>
							<li><a href="<?php echo esc_url($author_twitter); ?>"><i class="social_twitter"></i><?php echo esc_html__("Twitter","bookio"); ?></a></li>
							<?php } ?>
							<?php if($author_google){ ?>
							<li><a href="<?php echo esc_url($author_google); ?>"><i class="social_googleplus"></i><?php echo esc_html__("Google","bookio"); ?></a></li>
							<?php } ?>
							<?php if($author_instagram){ ?>
							<li><a href="<?php echo esc_url($author_instagram); ?>"><i class="social_instagram"></i><?php echo esc_html__("Instagram","bookio"); ?></a></li>
							<?php } ?>
						</ul>
					</div>
				</div>
				<?php if ( have_posts() ) : ?>
					<div class="bwp-top-bar top clearfix">				
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
				<?php else : ?>
					<?php wc_get_template( 'loop/no-products-found.php' ); ?>
				<?php endif; ?>
			</div>
		</div>
	</div>
<?php
do_action( 'woocommerce_after_main_content' );
get_footer( 'shop' ); 
?>