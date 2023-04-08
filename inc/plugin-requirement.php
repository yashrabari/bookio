<?php
/***** Active Plugin ********/
add_action( 'tgmpa_register', 'bookio_register_required_plugins' );
function bookio_register_required_plugins() {
    $plugins = array(
		array(
            'name'               => esc_html__('Woocommerce', 'bookio'), 
            'slug'               => 'woocommerce', 
            'required'           => false
        ),
		array(
            'name'      		 => esc_html__('Elementor', 'bookio'),
            'slug'     			 => 'elementor',
            'required' 			 => false
        ),
		array(
            'name'               => esc_html__('Wpbingo Core', 'bookio'), 
            'slug'               => 'wpbingo', 
            'source'             => get_template_directory() . '/plugins/wpbingo.zip',
            'required'           => true, 
        ),			
		array(
            'name'               => esc_html__('Redux Framework', 'bookio'), 
            'slug'               => 'redux-framework', 
            'required'           => false
        ),			
		array(
            'name'      		 => esc_html__('Contact Form 7', 'bookio'),
            'slug'     			 => 'contact-form-7',
            'required' 			 => false
        ),	
		array(
            'name'     			 => esc_html__('WPC Smart Wishlist for WooCommerce', 'bookio'),
            'slug'      		 => 'woo-smart-wishlist',
            'required' 			 => false
        ),
		array(
            'name'     			 => esc_html__('WooCommerce Variation Swatches', 'bookio'),
            'slug'      		 => 'variation-swatches-for-woocommerce',
            'required' 			 => false
        ),
		array(
            'name'     			 => esc_html__('Dokan', 'bookio'),
            'slug'      		 => 'dokan-lite',
            'required' 			 => false
        ),		
    );
    $config = array();
    tgmpa( $plugins, $config );
}