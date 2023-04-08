	</div><!-- #main -->
		<?php 
			global $bookio_page_id;
			$bookio_settings = bookio_global_settings();
			$footer_style = bookio_get_config('footer_style','');
			$footer_style = (get_post_meta( $bookio_page_id,'page_footer_style', true )) ? get_post_meta( $bookio_page_id, 'page_footer_style', true ) : $footer_style ;
			$header_style = bookio_get_config('header_style', ''); 
			$header_style  = (get_post_meta( $bookio_page_id, 'page_header_style', true )) ? get_post_meta($bookio_page_id, 'page_header_style', true ) : $header_style ;
		?>
		<?php if($footer_style && (get_post($footer_style)) && in_array( 'elementor/elementor.php', apply_filters('active_plugins', get_option( 'active_plugins' )))){ ?>
			<?php $elementor_instance = Elementor\Plugin::instance(); ?>
			<footer id="bwp-footer" class="bwp-footer <?php echo esc_attr( get_post($footer_style)->post_name ); ?>">
				<?php echo bookio_render_footer($footer_style);	?>
			</footer>
		<?php }else{
			bookio_copyright();
		}?>
	</div><!-- #page -->
	<div class="search-overlay">	
		<div class="container wrapper-search">
			<div class="search-top">
				<h2><?php echo esc_html__("what are you looking for?","bookio"); ?></h2>
				<div class="close-search"><?php echo esc_html__("close","bookio"); ?><i class="icon_close"></i></div>
			</div>
			<?php bookio_search_form_product(); ?>		
		</div>	
	</div>
	<div class="bwp-quick-view">
	</div>	
	<?php 
		$back_active = bookio_get_config('back_active');
		if($back_active && $back_active == 1):
	?>
	<div class="back-top">
		<i class="arrow_carrot-up"></i>
	</div>
	<?php endif;?>
	<?php if((isset($bookio_settings['show-newletter']) && $bookio_settings['show-newletter']) && is_active_sidebar('newletter-popup-form') && function_exists('bookio_popup_newsletter')) : ?>		
		<?php bookio_popup_newsletter(); ?>
	<?php endif;  ?>
	<?php if( function_exists('is_product') && is_product() ) : ?>
		<?php bookio_gallery_product(); ?>
	<?php endif;  ?>
	<?php wp_footer(); ?>
</body>
</html>