<?php
/**
 * Bookio Settings Options
 */
if (!class_exists('Redux_Framework_bookio_settings')) {
    class Redux_Framework_bookio_settings {
        public $args        = array();
        public $sections    = array();
        public $theme;
        public $ReduxFramework;
        public function __construct() {
            if (!class_exists('ReduxFramework')) {
                return;
            }
            // This is needed. Bah WordPress bugs.  ;)
            if (  true == Redux_Helpers::isTheme(__FILE__) ) {
                $this->initSettings();
            } else {
                add_action('plugins_loaded', array($this, 'initSettings'), 10);
            }
        }
        public function initSettings() {
            $this->theme = wp_get_theme();
            // Set the default arguments
            $this->setArguments();
            // Set a few help tabs so you can see how it's done
            $this->setHelpTabs();
            // Create the sections and fields
            $this->setSections();
            if (!isset($this->args['opt_name'])) { // No errors please
                return;
            }
            $this->ReduxFramework = new ReduxFramework($this->sections, $this->args);
			$custom_font = bookio_get_config('custom_font',false);
			if($custom_font != 1){
				remove_action( 'wp_head', array( $this->ReduxFramework, '_output_css' ),150 );
			}
        }
        function compiler_action($options, $css, $changed_values) {
        }
        function dynamic_section($sections) {
            return $sections;
        }
        function change_arguments($args) {
            return $args;
        }
        function change_defaults($defaults) {
            return $defaults;
        }
        function remove_demo() {
        }
        public function setSections() {
            $page_layouts = bookio_options_layouts();
            $sidebars = bookio_options_sidebars();
            $bookio_header_type = bookio_options_header_types();
            $bookio_banners_effect = bookio_options_banners_effect();
            // General Settings  ------------
            $this->sections[] = array(
                'icon' => 'fa fa-home',
                'icon_class' => 'icon',
                'title' => esc_html__('General', 'bookio'),
                'fields' => array(                
                )
            );  
            // Layout Settings
            $this->sections[] = array(
                'icon' => 'icofont icofont-double-right',
                'icon_class' => 'icon',
                'subsection' => true,
                'title' => esc_html__('Layout', 'bookio'),
                'fields' => array(
                    array(
                        'id' => 'background_img',
                        'type' => 'media',
                        'title' => esc_html__('Background Image', 'bookio'),
                        'sub_desc' => '',
                        'default' => ''
                    ),
                    array(
                        'id'=>'show-newletter',
                        'type' => 'switch',
                        'title' => esc_html__('Show Newletter Form', 'bookio'),
                        'default' => false,
                        'on' => esc_html__('Show', 'bookio'),
                        'off' => esc_html__('Hide', 'bookio'),
                    ),
                    array(
                        'id' => 'background_newletter_img',
                        'type' => 'media',
                        'title' => esc_html__('Popup Newletter Image', 'bookio'),
                        'url'=> true,
                        'readonly' => false,
                        'sub_desc' => '',
                        'default' => array(
                            'url' => get_template_directory_uri() . '/images/newsletter-image.jpg'
                        )
                    ),
                    array(
                            'id' => 'back_active',
                            'type' => 'switch',
                            'title' => esc_html__('Back to top', 'bookio'),
                            'sub_desc' => '',
                            'desc' => '',
                            'default' => '1'// 1 = on | 0 = off
                            ),                          
                    array(
                            'id' => 'direction',
                            'type' => 'select',
                            'title' => esc_html__('Direction', 'bookio'),
                            'options' => array( 'ltr' => esc_html__('Left to Right', 'bookio'), 'rtl' => esc_html__('Right to Left', 'bookio') ),
                            'default' => 'ltr'
                        )        
                )
            );
            // Logo & Icons Settings
            $this->sections[] = array(
                'icon' => 'icofont icofont-double-right',
                'icon_class' => 'icon',
                'subsection' => true,
                'title' => esc_html__('Logo & Icons', 'bookio'),
                'fields' => array(
                    array(
                        'id'=>'sitelogo',
                        'type' => 'media',
                        'compiler'  => 'true',
                        'mode'      => false,
                        'title' => esc_html__('Logo', 'bookio'),
                        'desc'      => esc_html__('Upload Logo image default here.', 'bookio'),
                        'default' => array(
                            'url' => get_template_directory_uri() . '/images/logo/logo.png'
                        )
                    ),
					array(
                        'id'	=> 'size_sitelogo',
                        'type' 	=> 'text',
                        'title' => esc_html__('Logo Size', 'bookio'),
                        'desc'  => esc_html__('Enter the maximum height of the logo', 'bookio'),
                    ),
                )
            );
			//Vertical Menu
            $this->sections[] = array(
                'icon' => 'icofont icofont-double-right',
                'subsection' => true,
                'title' => esc_html__('Vertical Menu', 'bookio'),
                'fields' => array( 
                    array(
                        'id'        => 'max_number_1530',
                        'type'      => 'text',
                        'title'     => esc_html__('Max number on screen >= 1530px', 'bookio'),
                        'default'   => '12'
                    ),
                    array(
                        'id'        => 'max_number_1200',
                        'type'      => 'text',
                        'title'     => esc_html__('Max number on on screen >= 1200px', 'bookio'),
                        'default'   => '8'
                    ),
					array(
                        'id'        => 'max_number_991',
                        'type'      => 'text',
                        'title'     => esc_html__('Max number on on screen >= 991px', 'bookio'),
                        'default'   => '6'
                    )
                )
            );
            // Header Settings
            $this->sections[] = array(
                'icon' => 'icofont icofont-double-right',
                'icon_class' => 'icon',
                'subsection' => true,
                'title' => esc_html__('Header', 'bookio'),
                'fields' => array(
                    array(
                        'id'=>'header_style',
                        'type' => 'image_select',
                        'full_width' => true,
                        'title' => esc_html__('Header Type', 'bookio'),
                        'options' => $bookio_header_type,
                        'default' => '1'
                    ),
                    array(
                        'id'=>'show-header-top',
                        'type' => 'switch',
                        'title' => esc_html__('Show Header Top', 'bookio'),
                        'default' => false,
                        'on' => esc_html__('Yes', 'bookio'),
                        'off' => esc_html__('No', 'bookio'),
                    ),
                    array(
                        'id'=>'show-searchform',
                        'type' => 'switch',
                        'title' => esc_html__('Show Search Form', 'bookio'),
                        'default' => false,
                        'on' => esc_html__('Yes', 'bookio'),
                        'off' => esc_html__('No', 'bookio'),
                    ),
                    array(
                        'id'=>'show-ajax-search',
                        'type' => 'switch',
                        'title' => esc_html__('Show Ajax Search', 'bookio'),
                        'default' => false,
                        'on' => esc_html__('Yes', 'bookio'),
                        'off' => esc_html__('No', 'bookio')
                    ),
                    array(
                        'id'=>'limit-ajax-search',
                        'type' => 'text',
                        'title' => esc_html__('Limit Of Result Search', 'bookio'),
						'default' => 6,
						'required' => array('show-ajax-search','equals',true)
                    ),					
                    array(
                        'id'=>'search-cats',
                        'type' => 'switch',
                        'title' => esc_html__('Show Categories', 'bookio'),
                        'required' => array('search-type','equals',array('post', 'product')),
                        'default' => false,
                        'on' => esc_html__('Yes', 'bookio'),
                        'off' => esc_html__('No', 'bookio'),
                    ),
                    array(
                        'id'=>'show-wishlist',
                        'type' => 'switch',
                        'title' => esc_html__('Show Wishlist', 'bookio'),
                        'default' => false,
                        'on' => esc_html__('Yes', 'bookio'),
                        'off' => esc_html__('No', 'bookio'),
                    ),
					array(
                        'id'=>'show-campbar',
                        'type' => 'switch',
                        'title' => esc_html__('Show Campbar', 'bookio'),
                        'default' => false,
                        'on' => esc_html__('Yes', 'bookio'),
                        'off' => esc_html__('No', 'bookio'),
                    ),
					array(
                        'id'=>'link-campbar',
                        'type' => 'text',
                        'title' => esc_html__('Link Campbar', 'bookio'),
						'default' => '#',
						'required' => array('show-campbar','equals',true),
                    ),
					array(
                        'id'=>'content-campbar',
                        'type' => 'text',
                        'title' => esc_html__('Content Campbar', 'bookio'),
						'default' => esc_html__('20% OFF EVERYTHING – USE CODE:FLASH20 – ENDS SUNDAY', 'bookio'),
						'required' => array('show-campbar','equals',true),
                    ),
					array(
						'id' => 'img-campbar',
						'type' => 'media',
						'title' => esc_html__('Image Campbar', 'bookio'),
						'url'=> true,
						'readonly' => false,
						'required' => array('show-campbar','equals',true),
						'sub_desc' => '',
						'default' => array(
							'url' => ""
						)
					),
					 array(
                      'id' => 'color-campbar',
                      'type' => 'color',
                      'title' => esc_html__('Color Campbar', 'bookio'),
                      'subtitle' => esc_html__('Select a color for Campbar.', 'bookio'),
                      'default' => '#424cc7',
                      'transparent' => false,
					  'required' => array('show-campbar','equals',true),
                    ),
					array(
                        'id'=>'show-menutop',
                        'type' => 'switch',
                        'title' => esc_html__('Show Menu Top', 'bookio'),
                        'default' => true,
                        'on' => esc_html__('Yes', 'bookio'),
                        'off' => esc_html__('No', 'bookio'),
                    ),
					array(
                        'id'=>'show-compare',
                        'type' => 'switch',
                        'title' => esc_html__('Show Compare', 'bookio'),
                        'default' => false,
                        'on' => esc_html__('Yes', 'bookio'),
                        'off' => esc_html__('No', 'bookio'),
                    ),
					array(
                        'id'=>'show-minicart',
                        'type' => 'switch',
                        'title' => esc_html__('Show Mini Cart', 'bookio'),
                        'default' => false,
                        'on' => esc_html__('Yes', 'bookio'),
                        'off' => esc_html__('No', 'bookio'),
                    ),
					array(
                        'id'=>'cart-layout',
						'type' => 'button_set',
                        'title' => esc_html__('Cart Layout', 'bookio'),
                        'options' => array('dropdown' => esc_html__('Dropdown', 'bookio'),
											'popup' => esc_html__('Popup', 'bookio')),
						'default' => 'dropdown',
						'required' => array('show-minicart','equals',true),
                    ),
					array(
                        'id'=>'cart-style',
						'type' => 'button_set',
                        'title' => esc_html__('Cart Style', 'bookio'),
                        'options' => array('dark' => esc_html__('Dark', 'bookio'),
											'light' => esc_html__('Light', 'bookio')),
						'default' => 'light',
						'required' => array('show-minicart','equals',true),
                    ),
                    array(
                        'id'=>'enable-sticky-header',
                        'type' => 'switch',
                        'title' => esc_html__('Enable Sticky Header', 'bookio'),
                        'default' => false,
                        'on' => esc_html__('Yes', 'bookio'),
                        'off' => esc_html__('No', 'bookio'),
                    ),
					array(
                        'id'=>'address',
                        'type' => 'text',
                        'title' => esc_html__('Address', 'bookio'),
                        'default' => esc_html__('Find Store', 'bookio'),
                    ),
					array(
                        'id'=>'link_address',
                        'type' => 'text',
                        'title' => esc_html__('Link Address', 'bookio'),
                        'default' => '#'
                    ),
					array(
                        'id'=>'phone',
                        'type' => 'text',
                        'title' => esc_html__('Phone', 'bookio'),
                        'default' => '(+1)202-333-800'
                    ),
					array(
                        'id'=>'ship',
                        'type' => 'text',
                        'title' => esc_html__('Ship', 'bookio'),
                        'default' => 'Free Shipping on Orders $300'
                    )
                )
            );
            // Footer Settings
            $footers = bookio_get_footers();
            $this->sections[] = array(
                'icon' => 'icofont icofont-double-right',
                'icon_class' => 'icon',
                'subsection' => true,
                'title' => esc_html__('Footer', 'bookio'),
                'fields' => array(
                    array(
                        'id' => 'footer_style',
                        'type' => 'image_select',
                        'title' => esc_html__('Footer Style', 'bookio'),
                        'sub_desc' => esc_html__( 'Select Footer Style', 'bookio' ),
                        'desc' => '',
                        'options' => $footers,
                        'default' => '32'
                    ),
                )
            );
            // Copyright Settings
            $this->sections[] = array(
                'icon' => 'icofont icofont-double-right',
                'icon_class' => 'icon',
                'subsection' => true,
                'title' => esc_html__('Copyright', 'bookio'),
                'fields' => array(
                    array(
                        'id' => "footer-copyright",
                        'type' => 'textarea',
                        'title' => esc_html__('Copyright', 'bookio'),
                        'default' => sprintf( wp_kses('&copy; Copyright %s. All Rights Reserved.', 'bookio'), date('Y') )
                    ),
                    array(
                        'id'=>'footer-payments',
                        'type' => 'switch',
                        'title' => esc_html__('Show Payments Logos', 'bookio'),
                        'default' => false,
                        'on' => esc_html__('Yes', 'bookio'),
                        'off' => esc_html__('No', 'bookio'),
                    ),
                    array(
                        'id'=>'footer-payments-image',
                        'type' => 'media',
                        'url'=> true,
                        'readonly' => false,
                        'title' => esc_html__('Payments Image', 'bookio'),
                        'required' => array('footer-payments','equals','1'),
                        'default' => array(
                            'url' => get_template_directory_uri() . '/images/payments.png'
                        )
                    ),
                    array(
                        'id'=>'footer-payments-image-alt',
                        'type' => 'text',
                        'title' => esc_html__('Payments Image Alt', 'bookio'),
                        'required' => array('footer-payments','equals','1'),
                        'default' => ''
                    ),
                    array(
                        'id'=>'footer-payments-link',
                        'type' => 'text',
                        'title' => esc_html__('Payments Link URL', 'bookio'),
                        'required' => array('footer-payments','equals','1'),
                        'default' => ''
                    )
                )
            );
            // Page Title Settings
            $this->sections[] = array(
                'icon' => 'icofont icofont-double-right',
                'icon_class' => 'icon',
                'subsection' => true,
                'title' => esc_html__('Page Title', 'bookio'),
                'fields' => array(
					array(
                        'id'=>'show_bg_breadcrumb',
                        'type' => 'switch',
                        'title' => esc_html__('Show Background Breadcrumb', 'bookio'),
                        'default' => true,
                        'on' => esc_html__('Yes', 'bookio'),
                        'off' => esc_html__('No', 'bookio'),
                    ),
                    array(
                        'id'=>'page_title',
                        'type' => 'switch',
                        'title' => esc_html__('Show Page Title', 'bookio'),
                        'default' => true,
                        'on' => esc_html__('Yes', 'bookio'),
                        'off' => esc_html__('No', 'bookio'),
						'required' => array('show_bg_breadcrumb','equals', true),
                    ),
                    array(
                        'id'=>'page_title_bg',
                        'type' => 'media',
                        'url'=> true,
                        'readonly' => false,
                        'title' => esc_html__('Background', 'bookio'),
						'required' => array('show_bg_breadcrumb','equals', true),
	                    'default' => array(
                            'url' => "",
                        )							
                    ),
                    array(
                        'id' => 'breadcrumb',
                        'type' => 'switch',
                        'title' => esc_html__('Show Breadcrumb', 'bookio'),
                        'default' => true,
                        'on' => esc_html__('Yes', 'bookio'),
                        'off' => esc_html__('No', 'bookio'),
                        'required' => array('show_bg_breadcrumb','equals', true),
                    ),
                )
            );
            // 404 Page Settings
            $this->sections[] = array(
                'icon' => 'icofont icofont-double-right',
                'icon_class' => 'icon',
                'subsection' => true,
                'title' => esc_html__('404 Error', 'bookio'),
                'fields' => array(
                    array(
                        'id'=>'title-error',
                        'type' => 'text',
                        'title' => esc_html__('Content Page 404', 'bookio'),
                        'desc' => esc_html__('Input a block slug name', 'bookio'),
                        'default' => esc_html__('404', 'bookio'),
                    ),
					array(
                        'id'=>'sub-title',
                        'type' => 'text',
                        'title' => esc_html__('Content Page 404', 'bookio'),
                        'desc' => esc_html__('Input a block slug name', 'bookio'),
                        'default' => esc_html__("Oops! That page can't be found.", "bookio"),
                    ), 					
                    array(
                        'id'=>'sub-error',
                        'type' => 'text',
                        'title' => esc_html__('Content Page 404', 'bookio'),
                        'desc' => esc_html__('Input a block slug name', 'bookio'),
                        'default' => esc_html__("We're really sorry but we can't seem to find the page you were looking for.", "bookio"),
                    ),               
                    array(
                        'id'=>'btn-error',
                        'type' => 'text',
                        'title' => esc_html__('Button Page 404', 'bookio'),
                        'desc' => esc_html__('Input a block slug name', 'bookio'),
                        'default' => esc_html__('Input a block slug name', 'bookio'),
                    )                      
                )
            );
            // Social Share Settings
            $this->sections[] = array(
                'icon' => 'icofont icofont-double-right',
                'icon_class' => 'icon',
                'subsection' => true,
                'title' => esc_html__('Social Share', 'bookio'),
                'fields' => array(
                    array(
                        'id'=>'social-share',
                        'type' => 'switch',
                        'title' => esc_html__('Show Social Links', 'bookio'),
                        'desc' => esc_html__('Show social links in post and product, page, portfolio, etc.', 'bookio'),
                        'default' => true,
                        'on' => esc_html__('Yes', 'bookio'),
                        'off' => esc_html__('No', 'bookio'),
                    ),
                    array(
                        'id'=>'share-fb',
                        'type' => 'switch',
                        'title' => esc_html__('Enable Facebook Share', 'bookio'),
                        'default' => true,
                        'on' => esc_html__('Yes', 'bookio'),
                        'off' => esc_html__('No', 'bookio'),
                    ),
                    array(
                        'id'=>'share-tw',
                        'type' => 'switch',
                        'title' => esc_html__('Enable Twitter Share', 'bookio'),
                        'default' => true,
                        'on' => esc_html__('Yes', 'bookio'),
                        'off' => esc_html__('No', 'bookio'),
                    ),
                    array(
                        'id'=>'share-linkedin',
                        'type' => 'switch',
                        'title' => esc_html__('Enable LinkedIn Share', 'bookio'),
                        'default' => true,
                        'on' => esc_html__('Yes', 'bookio'),
                        'off' => esc_html__('No', 'bookio'),
                    ),
                    array(
                        'id'=>'share-pinterest',
                        'type' => 'switch',
                        'title' => esc_html__('Enable Pinterest Share', 'bookio'),
                        'default' => false,
                        'on' => esc_html__('Yes', 'bookio'),
                        'off' => esc_html__('No', 'bookio'),
                    ),
                )
            );
            $this->sections[] = array(
				'icon' => 'icofont icofont-double-right',
                'icon_class' => 'icon',
                'subsection' => true,
                'title' => esc_html__('Socials Link', 'bookio'),
                'fields' => array(
                    array(
                        'id'=>'socials_link',
                        'type' => 'switch',
                        'title' => esc_html__('Enable Socials link', 'bookio'),
                        'default' => true,
                        'on' => esc_html__('Yes', 'bookio'),
                        'off' => esc_html__('No', 'bookio'),
                    ),
                    array(
                        'id'=>'link-fb',
                        'type' => 'text',
                        'title' => esc_html__('Enter Facebook link', 'bookio'),
						'default' => '#'
                    ),
                    array(
                        'id'=>'link-tw',
                        'type' => 'text',
                        'title' => esc_html__('Enter Twitter link', 'bookio'),
						'default' => '#'
                    ),
                    array(
                        'id'=>'link-linkedin',
                        'type' => 'text',
                        'title' => esc_html__('Enter LinkedIn link', 'bookio'),
						'default' => '#'
                    ),
                    array(
                        'id'=>'link-youtube',
                        'type' => 'text',
                        'title' => esc_html__('Enter Youtube link', 'bookio'),
						'default' => '#'
                    ),
                    array(
                        'id'=>'link-pinterest',
                        'type' => 'text',
                        'title' => esc_html__('Enter Pinterest link', 'bookio'),
						'default' => '#'
                    ),
                    array(
                        'id'=>'link-instagram',
                        'type' => 'text',
                        'title' => esc_html__('Enter Instagram link', 'bookio'),
						'default' => '#'
                    ),
                )
            );			
            //     The end -----------
            // Styling Settings  -------------
            $this->sections[] = array(
                'icon' => 'icofont icofont-brand-appstore',
                'icon_class' => 'icon',
                'title' => esc_html__('Styling', 'bookio'),
                'fields' => array(              
                )
            );  
            // Color & Effect Settings
            $this->sections[] = array(
                'icon' => 'icofont icofont-double-right',
                'icon_class' => 'icon',
                'subsection' => true,
                'title' => esc_html__('Color & Effect', 'bookio'),
                'fields' => array(
                    array(
                        'id'=>'compile-css',
                        'type' => 'switch',
                        'title' => esc_html__('Compile Css', 'bookio'),
                        'default' => false,
                        'on' => esc_html__('Yes', 'bookio'),
                        'off' => esc_html__('No', 'bookio'),
                    ),					
                    array(
                      'id' => 'main_theme_color',
                      'type' => 'color',
                      'title' => esc_html__('Main Theme Color', 'bookio'),
                      'subtitle' => esc_html__('Select a main color for your site.', 'bookio'),
                      'default' => '#222222',
                      'transparent' => false,
					  'required' => array('compile-css','equals',array(true)),
                    ),      
                    array(
                        'id'=>'show-loading-overlay',
                        'type' => 'switch',
                        'title' => esc_html__('Loading Overlay', 'bookio'),
                        'default' => false,
                        'on' => esc_html__('Show', 'bookio'),
                        'off' => esc_html__('Hide', 'bookio'),
                    ),
					 array(
                        'id' => 'gif_loading',
                        'type' => 'media',
                        'title' => esc_html__('Gif Loading', 'bookio'),
						'required' => array('show-loading-overlay','equals',array(true)),
                    ),
					 array(
                        'id' => 'gif_loading_width',
                        'type' => 'text',
                        'title' => esc_html__('Width', 'bookio'),
						'required' => array('show-loading-overlay','equals',array(true)),
						'subtitle' => esc_html__('Width image gif Loading', 'bookio'),
                    ),
                    array(
                        'id'=>'banners_effect',
                        'type' => 'image_select',
                        'full_width' => true,
                        'title' => esc_html__('Banner Effect', 'bookio'),
                        'options' => $bookio_banners_effect,
                        'default' => 'banners-effect-1'
                    )                   
                )
            );
            // Typography Settings
            $this->sections[] = array(
                'icon' => 'icofont icofont-double-right',
                'icon_class' => 'icon',
                'subsection' => true,
                'title' => esc_html__('Typography', 'bookio'),
                'fields' => array(
                    array(
                        'id'=>'custom_font',
                        'type' => 'switch',
                        'title' => esc_html__('Custom Font', 'bookio'),
                        'default' => false,
                        'on' => esc_html__('Yes', 'bookio'),
                        'off' => esc_html__('No', 'bookio'),
                    ),				
                    array(
                        'id'=>'select-google-charset',
                        'type' => 'switch',
                        'title' => esc_html__('Select Google Font Character Sets', 'bookio'),
                        'default' => false,
                        'on' => esc_html__('Yes', 'bookio'),
                        'off' => esc_html__('No', 'bookio'),
						'required' => array('custom_font','equals',true),
                    ),
                    array(
                        'id'=>'google-charsets',
                        'type' => 'button_set',
                        'title' => esc_html__('Google Font Character Sets', 'bookio'),
                        'multi' => true,
                        'required' => array('select-google-charset','equals',true),
                        'options'=> array(
                            'cyrillic' => 'Cyrrilic',
                            'cyrillic-ext' => 'Cyrrilic Extended',
                            'greek' => 'Greek',
                            'greek-ext' => 'Greek Extended',
                            'khmer' => 'Khmer',
                            'latin' => 'Latin',
                            'latin-ext' => 'Latin Extneded',
                            'vietnamese' => 'Vietnamese'
                        ),
                        'default' => array('latin','greek-ext','cyrillic','latin-ext','greek','cyrillic-ext','vietnamese','khmer')
                    ),
                    array(
                        'id'=>'family_font_body',
                        'type' => 'typography',
                        'title' => esc_html__('Body Font', 'bookio'),
                        'google' => true,
                        'subsets' => false,
                        'font-style' => false,
                        'text-align' => false,
						'output'      => array('body'),
                        'color' => false,
                        'default'=> array(
                            'color'=>"#777777",
                            'google'=>true,
                            'font-weight'=>'400',
                            'font-family'=>'Open Sans',
                            'font-size'=>'14px',
                            'line-height' => '22px'
                        ),
						'required' => array('custom_font','equals',true)
                    ),
                    array(
                        'id'=>'h1-font',
                        'type' => 'typography',
                        'title' => esc_html__('H1 Font', 'bookio'),
                        'google' => true,
                        'subsets' => false,
                        'font-style' => false,
                        'text-align' => false,
                        'color' 	=> false,
						'output'      => array('body h1'),
                        'default'=> array(
                            'color'=>"#1d2127",
                            'google'=>true,
                            'font-weight'=>'400',
                            'font-family'=>'Open Sans',
                            'font-size'=>'36px',
                            'line-height' => '44px'
                        ),
						'required' => array('custom_font','equals',true)
                    ),
                    array(
                        'id'=>'h2-font',
                        'type' => 'typography',
                        'title' => esc_html__('H2 Font', 'bookio'),
                        'google' => true,
                        'subsets' => false,
                        'font-style' => false,
                        'text-align' => false,
                        'color' => false,
						'output'      => array('body h2'),
                        'default'=> array(
                            'color'=>"#1d2127",
                            'google'=>true,
                            'font-weight'=>'300',
                            'font-family'=>'Open Sans',
                            'font-size'=>'30px',
                            'line-height' => '40px'
                        ),
						'required' => array('custom_font','equals',true)
                    ),
                    array(
                        'id'=>'h3-font',
                        'type' => 'typography',
                        'title' => esc_html__('H3 Font', 'bookio'),
                        'google' => true,
                        'subsets' => false,
                        'font-style' => false,
                        'text-align' => false,
                        'color' => false,
						'output'      => array('body h3'),
                        'default'=> array(
                            'color'=>"#1d2127",
                            'google'=>true,
                            'font-weight'=>'400',
                            'font-family'=>'Open Sans',
                            'font-size'=>'25px',
                            'line-height' => '32px'
                        ),
						'required' => array('custom_font','equals',true)
                    ),
                    array(
                        'id'=>'h4-font',
                        'type' => 'typography',
                        'title' => esc_html__('H4 Font', 'bookio'),
                        'google' => true,
                        'subsets' => false,
                        'font-style' => false,
                        'text-align' => false,
                        'color' => false,
						'output'      => array('body h4'),
                        'default'=> array(
                            'color'=>"#1d2127",
                            'google'=>true,
                            'font-weight'=>'400',
                            'font-family'=>'Open Sans',
                            'font-size'=>'20px',
                            'line-height' => '27px'
                        ),
						'required' => array('custom_font','equals',true)
                    ),
                    array(
                        'id'=>'h5-font',
                        'type' => 'typography',
                        'title' => esc_html__('H5 Font', 'bookio'),
                        'google' => true,
                        'subsets' => false,
                        'font-style' => false,
                        'text-align' => false,
                        'color' => false,
						'output'      => array('body h5'),
                        'default'=> array(
                            'color'=>"#1d2127",
                            'google'=>true,
                            'font-weight'=>'600',
                            'font-family'=>'Open Sans',
                            'font-size'=>'14px',
                            'line-height' => '18px'
                        ),
						'required' => array('custom_font','equals',true)
                    ),
                    array(
                        'id'=>'h6-font',
                        'type' => 'typography',
                        'title' => esc_html__('H6 Font', 'bookio'),
                        'google' => true,
                        'subsets' => false,
                        'font-style' => false,
                        'text-align' => false,
                        'color' => false,
						'output'      => array('body h6'),
                        'default'=> array(
                            'color'=>"#1d2127",
                            'google'=>true,
                            'font-weight'=>'400',
                            'font-family'=>'Open Sans',
                            'font-size'=>'14px',
                            'line-height' => '18px'
                        ),
						'required' => array('custom_font','equals',true)
                    )
                )
            );
            //     The end -----------          
            if ( class_exists( 'Woocommerce' ) ) :
                $this->sections[] = array(
                    'icon' => 'icofont icofont-cart-alt',
                    'icon_class' => 'icon',
                    'title' => esc_html__('Ecommerce', 'bookio'),
                    'fields' => array(              
                    )
                );
                $this->sections[] = array(
                    'icon' => 'icofont icofont-double-right',
                    'icon_class' => 'icon',
                    'subsection' => true,
                    'title' => esc_html__('Product Archives', 'bookio'),
                    'fields' => array(
						array(
                            'id'=>'shop_paging',
							'title' => esc_html__('Shop Paging', 'bookio'),
                            'type' => 'select',
							'options' => array(
								'shop-pagination' => esc_html__('Pagination', 'bookio'),
								'shop-infinity' => esc_html__('Infinity', 'bookio'),
								'shop-loadmore' => esc_html__('Load More', 'bookio'),
                             ),
                            'default' => 'shop-pagination',
                        ),
						array(
                            'id'=>'show_background_shop',
							'title' => esc_html__('Show Background Shop', 'bookio'),
                            'type' => 'button_set',
                            'default' => 'no',
							'options' => array(
								'yes' => esc_html__('Yes', 'bookio'),
								'no' => esc_html__('No', 'bookio')
							),
                        ),
						array(
                            'id'=>'show_catagories_top',
							'title' => esc_html__('Show Categories Top', 'bookio'),
                            'type' => 'button_set',
                            'default' => 'no',
							'options' => array(
								'yes' => esc_html__('Yes', 'bookio'),
								'no' => esc_html__('No', 'bookio')
							),
                        ),
						array(
                            'id'=>'limit_catagories_top',
							'title' => esc_html__('Limit Categories Top', 'bookio'),
                            'type' => 'text',
							'required' => array('show_catagories_top','equals','yes'),
                            'default' => '9',
                        ),
						array(
                            'id'=>'limit_children_shop',
							'title' => esc_html__('Limit Children Categories Top', 'bookio'),
                            'type' => 'text',
							'required' => array('show_catagories_top','equals','yes'),
                            'default' => '4',
                        ),
						array(
                            'id'=>'layout_shop',
							'title' => esc_html__('Style Layout Shop', 'bookio'),
                            'type' => 'button_set',
							'options' => array(
								'1' => esc_html__('Style 1', 'bookio'),
								'2' => esc_html__('Style 2', 'bookio'),
								'3' => esc_html__('Style 3', 'bookio'),
								'4' => esc_html__('Style 4', 'bookio'),
                             ),
                            'default' => '1',
                        ),	
						array(
                            'id'=>'show-bestseller-category',
                            'type' => 'switch',
                            'title' => esc_html__('Show Bestseller on Page Category', 'bookio'),
                            'type' => 'button_set',
                            'default' => 'no',
							'options' => array(
								'yes' => esc_html__('Yes', 'bookio'),
								'no' => esc_html__('No', 'bookio')
							),
                        ),
						 array(
                            'id' => 'bestseller_limit',
                            'type' => 'text',
                            'title' => esc_html__('Shop product Bestseller', 'bookio'),
                            'default' => '9',
							'required' => array('show-bestseller-category','equals','yes'),
                        ),
						array(
                            'id'=>'show-featured-category',
                            'type' => 'switch',
                            'title' => esc_html__('Show Featured on Page Category', 'bookio'),
                            'type' => 'button_set',
                            'default' => 'no',
							'options' => array(
								'yes' => esc_html__('Yes', 'bookio'),
								'no' => esc_html__('No', 'bookio')
							),
                        ),
						 array(
                            'id' => 'featured_limit',
                            'type' => 'text',
                            'title' => esc_html__('Shop product Featured', 'bookio'),
                            'default' => '9',
							'required' => array('show-featured-category','equals','yes'),
                        ),
                        array(
                            'id'=>'show-banner-category',
                            'type' => 'switch',
                            'title' => esc_html__('Show Banner Category', 'bookio'),
                            'type' => 'button_set',
                            'default' => 'no',
							'options' => array(
								'yes' => esc_html__('Yes', 'bookio'),
								'no' => esc_html__('No', 'bookio')
							),
                        ),
						array(
							'id' => 'banner-shop',
							'type' => 'media',
							'title' => esc_html__('Banner Shop', 'bookio'),
							'url'=> true,
							'readonly' => false,
							'required' => array('show-banner-category','equals','yes'),
							'sub_desc' => '',
							'default' => array(
								'url' => ""
							)
						),
						array(
                            'id' => 'link-banner-shop',
                            'type' => 'text',
                            'title' => esc_html__('Url Banner Shop', 'bookio'),
                            'default' => '#',
							'required' => array('show-banner-category','equals','yes'),
                        ),
						array(
                            'id' => 'title-banner-shop',
                            'type' => 'text',
                            'title' => esc_html__('Title Banner Shop', 'bookio'),
                            'default' => esc_html__('Book Your Own Adventures', 'bookio'),
							'required' => array('show-banner-category','equals','yes'),
                        ),
						array(
                            'id' => 'desc-banner-shop',
                            'type' => 'text',
                            'title' => esc_html__('Description Banner Shop', 'bookio'),
                            'default' => esc_html__('MORE BANG FOR YOUR BOOK', 'bookio'),
							'required' => array('show-banner-category','equals','yes'),
                        ),
						array(
                            'id' => 'button-banner-shop',
                            'type' => 'text',
                            'title' => esc_html__('Button Banner Shop', 'bookio'),
                            'default' => esc_html__('Shop now', 'bookio'),
							'required' => array('show-banner-category','equals','yes'),
                        ),
                        array(
                            'id'=>'category-view-mode',
                            'type' => 'button_set',
                            'title' => esc_html__('View Mode', 'bookio'),
                            'options' => bookio_ct_category_view_mode(),
                            'default' => 'grid',
                        ),
                        array(
                            'id' => 'product_col_large',
                            'type' => 'button_set',
                            'title' => esc_html__('Product Listing column Desktop', 'bookio'),
                            'options' => array(
                                    '2' => '2',
                                    '3' => '3',
                                    '4' => '4'                        
                                ),
                            'default' => '4',
                            'sub_desc' => esc_html__( 'Select number of column on Desktop Screen', 'bookio' ),
                        ),
                        array(
                            'id' => 'product_col_medium',
                            'type' => 'button_set',
                            'title' => esc_html__('Product Listing column Medium Desktop', 'bookio'),
                            'options' => array(
                                    '2' => '2',
                                    '3' => '3',
                                    '4' => '4'                          
                                ),
                            'default' => '3',
                            'sub_desc' => esc_html__( 'Select number of column on Medium Desktop Screen', 'bookio' ),
                        ),
                        array(
                            'id' => 'product_col_sm',
                            'type' => 'button_set',
                            'title' => esc_html__('Product Listing column Ipad Screen', 'bookio'),
                            'options' => array(
                                    '2' => '2',
                                    '3' => '3',
                                    '4' => '4'                          
                                ),
                            'default' => '3',
                            'sub_desc' => esc_html__( 'Select number of column on Ipad Screen', 'bookio' ),
                        ),
						array(
                            'id' => 'product_col_xs',
                            'type' => 'button_set',
                            'title' => esc_html__('Product Listing column Mobile Screen', 'bookio'),
                            'options' => array(
									'1' => '1',
                                    '2' => '2',
                                    '3' => '3'                        
                                ),
                            'default' => '2',
                            'sub_desc' => esc_html__( 'Select number of column on Mobile Screen', 'bookio' ),
                        ),
                        array(
                            'id'=>'woo-show-rating',
                            'type' => 'switch',
                            'title' => esc_html__('Show Rating in Woocommerce Products Widget', 'bookio'),
                            'default' => true,
                            'on' => esc_html__('Yes', 'bookio'),
                            'off' => esc_html__('No', 'bookio'),
                        ),						
						array(
                            'id'=>'show-category',
                            'type' => 'switch',
                            'title' => esc_html__('Show Category', 'bookio'),
                            'default' => true,
                            'on' => esc_html__('Yes', 'bookio'),
                            'off' => esc_html__('No', 'bookio'),
                        ),
                        array(
                            'id' => 'product_count',
                            'type' => 'text',
                            'title' => esc_html__('Shop pages show at product', 'bookio'),
                            'default' => '12',
                            'sub_desc' => esc_html__( 'Type Count Product Per Shop Page', 'bookio' ),
                        ),						
                        array(
                            'id'=>'category-image-hover',
                            'type' => 'switch',
                            'title' => esc_html__('Enable Image Hover Effect', 'bookio'),
                            'default' => true,
                            'on' => esc_html__('Yes', 'bookio'),
                            'off' => esc_html__('No', 'bookio'),
                        ),
                        array(
                            'id'=>'category-hover',
                            'type' => 'switch',
                            'title' => esc_html__('Enable Hover Effect', 'bookio'),
                            'default' => true,
                            'on' => esc_html__('Yes', 'bookio'),
                            'off' => esc_html__('No', 'bookio'),
                        ),
						 array(
                            'id'=>'product-author',
                            'type' => 'switch',
                            'title' => esc_html__('Show Author', 'bookio'),
                            'default' => false,
                            'on' => esc_html__('Yes', 'bookio'),
                            'off' => esc_html__('No', 'bookio'),
                        ),	
                        array(
                            'id'=>'product-wishlist',
                            'type' => 'switch',
                            'title' => esc_html__('Show Wishlist', 'bookio'),
                            'default' => true,
                            'on' => esc_html__('Yes', 'bookio'),
                            'off' => esc_html__('No', 'bookio'),
                        ),
						array(
							'id'=>'product-compare',
							'type' => 'switch',
							'title' => esc_html__('Show Compare', 'bookio'),
							'default' => false,
							'on' => esc_html__('Yes', 'bookio'),
							'off' => esc_html__('No', 'bookio'),
						),						
                        array(
                            'id'=>'product_quickview',
                            'type' => 'switch',
                            'title' => esc_html__('Show Quick View', 'bookio'),
                            'default' => true,
                            'on' => esc_html__('Yes', 'bookio'),
                            'off' => esc_html__('No', 'bookio')
                        ),
                        array(
                            'id'=>'product-quickview-label',
                            'type' => 'text',
                            'required' => array('product-quickview','equals',true),
                            'title' => esc_html__('"Quick View" Text', 'bookio'),
                            'default' => ''
                        ),
						array(
                            'id'=>'product-countdown',
                            'type' => 'switch',
                            'title' => esc_html__('Show Product Countdown', 'bookio'),
                            'default' => true,
                            'on' => esc_html__('Yes', 'bookio'),
                            'off' => esc_html__('No', 'bookio')
                        ),
						array(
                            'id'=>'checkout_page_style',
                            'title' => esc_html__('Checkout Page Style', 'bookio'),
                            'type' => 'button_set',
                            'options' => array(
                                    'checkout-page-style-1' => 'Style 1',
                                    'checkout-page-style-2' => 'Style 2',                        
                                ),
                            'default' => 'style-1',
                        ),
                    )
                );
                $this->sections[] = array(
                    'icon' => 'icofont icofont-double-right',
                    'icon_class' => 'icon',
                    'subsection' => true,
                    'title' => esc_html__('Single Product', 'bookio'),
                    'fields' => array(
						array(
							'id'=>'layout_sigle_product',
							'type' => 'button_set',
							'title' => esc_html__('Layout Single Product', 'bookio'),
							'options' => array(
								'default' => esc_html__('Default', 'bookio'),
								'box' => esc_html__('Box', 'bookio'),
								'sidebar' => esc_html__('Sidebar', 'bookio')
							),
							'default' => 'default'
						),
                        array(
                            'id'=>'product-stock',
                            'type' => 'switch',
                            'title' => esc_html__('Show "Out of stock" Status', 'bookio'),
                            'default' => true,
                            'on' => esc_html__('Yes', 'bookio'),
                            'off' => esc_html__('No', 'bookio'),
                        ),
						array(
                            'id'=>'show-sticky-cart',
                            'type' => 'switch',
                            'title' => esc_html__('Show Sticky Cart Product', 'bookio'),
                            'default' => false,
                            'on' => esc_html__('Yes', 'bookio'),
                            'off' => esc_html__('No', 'bookio'),
                        ),
						array(
                            'id'=>'show-brands',
                            'type' => 'switch',
                            'title' => esc_html__('Show Brands', 'bookio'),
                            'default' => true,
                            'on' => esc_html__('Yes', 'bookio'),
                            'off' => esc_html__('No', 'bookio'),
                        ),
						array(
                            'id'=>'show-countdown',
                            'type' => 'switch',
                            'title' => esc_html__('Show CountDown', 'bookio'),
                            'default' => true,
                            'on' => esc_html__('Yes', 'bookio'),
                            'off' => esc_html__('No', 'bookio'),
                        ),
						array(
                            'id'=>'show-quick-buy',
                            'type' => 'switch',
                            'title' => esc_html__('Show Button Buy Now', 'bookio'),
                            'default' => true,
                            'on' => esc_html__('Yes', 'bookio'),
                            'off' => esc_html__('No', 'bookio'),
                        ),						
                        array(
                            'id'=>'product-short-desc',
                            'type' => 'switch',
                            'title' => esc_html__('Show Short Description', 'bookio'),
                            'default' => true,
                            'on' => esc_html__('Yes', 'bookio'),
                            'off' => esc_html__('No', 'bookio'),
                        ),					
                        array(
                            'id'=>'product-related',
                            'type' => 'switch',
                            'title' => esc_html__('Show Related Product', 'bookio'),
                            'default' => true,
                            'on' => esc_html__('Yes', 'bookio'),
                            'off' => esc_html__('No', 'bookio'),
                        ),
                        array(
                            'id'=>'product-related-count',
                            'type' => 'text',
                            'required' => array('product-related','equals',true),
                            'title' => esc_html__('Related Product Count', 'bookio'),
                            'default' => '10'
                        ),
                        array(
                            'id'=>'product-related-cols',
                            'type' => 'button_set',
                            'required' => array('product-related','equals',true),
                            'title' => esc_html__('Related Product Columns', 'bookio'),
                            'options' => bookio_ct_related_product_columns(),
                            'default' => '4',
                        ),
                        array(
                            'id'=>'product-upsell',
                            'type' => 'switch',
                            'title' => esc_html__('Show Upsell Products', 'bookio'),
                            'default' => true,
                            'on' => esc_html__('Yes', 'bookio'),
                            'off' => esc_html__('No', 'bookio'),
                        ),                      
                        array(
                            'id'=>'product-upsell-count',
                            'type' => 'text',
                            'required' => array('product-upsell','equals',true),
                            'title' => esc_html__('Upsell Products Count', 'bookio'),
                            'default' => '10'
                        ),
                        array(
                            'id'=>'product-upsell-cols',
                            'type' => 'button_set',
                            'required' => array('product-upsell','equals',true),
                            'title' => esc_html__('Upsell Product Columns', 'bookio'),
                            'options' => bookio_ct_related_product_columns(),
                            'default' => '3',
                        ),
                        array(
                            'id'=>'product-crosssells',
                            'type' => 'switch',
                            'title' => esc_html__('Show Crooss Sells Products', 'bookio'),
                            'default' => true,
                            'on' => esc_html__('Yes', 'bookio'),
                            'off' => esc_html__('No', 'bookio'),
                        ),                      
                        array(
                            'id'=>'product-crosssells-count',
                            'type' => 'text',
                            'required' => array('product-crosssells','equals',true),
                            'title' => esc_html__('Crooss Sells Products Count', 'bookio'),
                            'default' => '10'
                        ),
                        array(
                            'id'=>'product-crosssells-cols',
                            'type' => 'button_set',
                            'required' => array('product-crosssells','equals',true),
                            'title' => esc_html__('Crooss Sells Product Columns', 'bookio'),
                            'options' => bookio_ct_related_product_columns(),
                            'default' => '3',
                        ),						
                        array(
                            'id'=>'product-hot',
                            'type' => 'switch',
                            'title' => esc_html__('Show "Hot" Label', 'bookio'),
                            'desc' => esc_html__('Will be show in the featured product.', 'bookio'),
                            'default' => true,
                            'on' => esc_html__('Yes', 'bookio'),
                            'off' => esc_html__('No', 'bookio'),
                        ),
                        array(
                            'id'=>'product-hot-label',
                            'type' => 'text',
                            'required' => array('product-hot','equals',true),
                            'title' => esc_html__('"Hot" Text', 'bookio'),
                            'default' => ''
                        ),
                        array(
                            'id'=>'product-sale',
                            'type' => 'switch',
                            'title' => esc_html__('Show "Sale" Label', 'bookio'),
                            'default' => true,
                            'on' => esc_html__('Yes', 'bookio'),
                            'off' => esc_html__('No', 'bookio'),
                        ),
                         array(
                            'id'=>'product-sale-percent',
                            'type' => 'switch',
                            'required' => array('product-sale','equals',true),
                            'title' => esc_html__('Show Sale Price Percentage', 'bookio'),
                            'default' => true,
                            'on' => esc_html__('Yes', 'bookio'),
                            'off' => esc_html__('No', 'bookio'),
                        ),  
                        array(
                            'id'=>'product-share',
                            'type' => 'switch',
                            'title' => esc_html__('Show Social Share Links', 'bookio'),
                            'default' => true,
                            'on' => esc_html__('Yes', 'bookio'),
                            'off' => esc_html__('No', 'bookio'),
                        ),
						array(
                            'id'=>'show-author',
                            'type' => 'switch',
                            'title' => esc_html__('Show Author', 'bookio'),
                            'default' => false,
                            'on' => esc_html__('Yes', 'bookio'),
                            'off' => esc_html__('No', 'bookio'),
                        ),
						array(
							'id'=>'description-style',
							'type' => 'select',
							'title' => esc_html__('Description Style', 'bookio'),
							'options' => array(
										'full-content' => esc_html__('Full Content', 'bookio'),
										'tab' => esc_html__('Tab', 'bookio'),
										),
							'default' => 'tab',
						),
                    )
                );
                $this->sections[] = array(
                    'icon' => 'icofont icofont-double-right',
                    'icon_class' => 'icon',
                    'subsection' => true,
                    'title' => esc_html__('Image Product', 'bookio'),
                    'fields' => array(
                        array(
                            'id'=>'product-thumbs',
                            'type' => 'switch',
                            'title' => esc_html__('Show Thumbnails', 'bookio'),
                            'default' => true,
                            'on' => esc_html__('Yes', 'bookio'),
                            'off' => esc_html__('No', 'bookio'),
                        ),
						array(
                            'id'=>'layout-thumbs',
                            'type' => 'button_set',
                            'title' => esc_html__('Layouts Product', 'bookio'),
                            'options' => array('zoom' => esc_html__('Zoom', 'bookio'),
												'scroll' => esc_html__('Scroll', 'bookio'),
												'special' => esc_html__('Special', 'bookio'),
											),	
                            'default' => 'zoom',
                        ),
                        array(
                            'id'=>'position-thumbs',
                            'type' => 'button_set',
                            'title' => esc_html__('Position Thumbnails', 'bookio'),
                            'options' => array('left' => esc_html__('Left', 'bookio'),
												'right' => esc_html__('Right', 'bookio'),
												'bottom' => esc_html__('Bottom', 'bookio'),
												'outsite' => esc_html__('Outsite', 'bookio')),
                            'default' => 'bottom',
							'required' => array('product-thumbs','equals',true),
                        ),						
                        array(
                            'id' => 'product-thumbs-count',
                            'type' => 'button_set',
                            'title' => esc_html__('Thumbnails Count', 'bookio'),
                            'options' => array(
                                    '2' => '2',
                                    '3' => '3',
                                    '4' => '4', 
									'5' => '5', 									
                                    '6' => '6'                          
                                ),
							'default' => '4',
							'required' => array('product-thumbs','equals',true),
                        ),
						 array(
                            'id' => 'video-style',
                            'type' => 'button_set',
                            'title' => esc_html__('Video Style', 'bookio'),
                            'options' => array(
                                    'popup' => 'Popup',
                                    'inner' => 'Inner',                          
                                ),
							'default' => 'inner',
                        ),
                        array(
                            'id'=>'zoom-type',
                            'type' => 'button_set',
                            'title' => esc_html__('Zoom Type', 'bookio'),
                            'options' => array(
									'inner' => esc_html__('Inner', 'bookio'),
									'window' => esc_html__('Window', 'bookio'),
									'lens' => esc_html__('Lens', 'bookio')
									),
                            'default' => 'inner',
							'required' => array('layout-thumbs','equals',"zoom"),
                        ),
                        array(
                            'id'=>'zoom-scroll',
                            'type' => 'switch',
                            'title' => esc_html__('Scroll Zoom', 'bookio'),
                            'default' => true,
                            'on' => esc_html__('Yes', 'bookio'),
                            'off' => esc_html__('No', 'bookio'),
							'required' => array('layout-thumbs','equals',"zoom"),
                        ),
                        array(
                            'id'=>'zoom-border',
                            'type' => 'text',
                            'title' => esc_html__('Border Size', 'bookio'),
                            'default' => '2',
							'required' => array('layout-thumbs','equals',"zoom"),
                        ),
                        array(
                            'id'=>'zoom-border-color',
                            'type' => 'color',
                            'title' => esc_html__('Border Color', 'bookio'),
                            'default' => '#f9b61e',
							'required' => array('layout-thumbs','equals',"zoom"),
                        ),                      
                        array(
                            'id'=>'zoom-lens-size',
                            'type' => 'text',
                            'required' => array('zoom-type','equals',array('lens')),
                            'title' => esc_html__('Lens Size', 'bookio'),
                            'default' => '200',
							'required' => array('layout-thumbs','equals',"zoom"),
                        ),
                        array(
                            'id'=>'zoom-lens-shape',
                            'type' => 'button_set',
                            'required' => array('zoom-type','equals',array('lens')),
                            'title' => esc_html__('Lens Shape', 'bookio'),
                            'options' => array('round' => esc_html__('Round', 'bookio'), 'square' => esc_html__('Square', 'bookio')),
                            'default' => 'square',
							'required' => array('layout-thumbs','equals',"zoom"),
                        ),
                        array(
                            'id'=>'zoom-contain-lens',
                            'type' => 'switch',
                            'required' => array('zoom-type','equals',array('lens')),
                            'title' => esc_html__('Contain Lens Zoom', 'bookio'),
                            'default' => true,
                            'on' => esc_html__('Yes', 'bookio'),
                            'off' => esc_html__('No', 'bookio'),
							'required' => array('layout-thumbs','equals',"zoom"),
                        ),
                        array(
                            'id'=>'zoom-lens-border',
                            'type' => 'text',
                            'required' => array('zoom-type','equals',array('lens')),
                            'title' => esc_html__('Lens Border', 'bookio'),
                            'default' => true,
							'required' => array('layout-thumbs','equals',"zoom")
                        ),
                    )
                );
            endif;
            // Blog Settings  -------------
            $this->sections[] = array(
                'icon' => 'icofont icofont-ui-copy',
                'icon_class' => 'icon',
                'title' => esc_html__('Blog', 'bookio'),
                'fields' => array(              
                )
            );      
            $this->sections[] = array(
                'icon' => 'icofont icofont-double-right',
                'icon_class' => 'icon',
                'subsection' => true,
                'title' => esc_html__('Blog & Post Archives', 'bookio'),
                'fields' => array(
                    array(
                        'id'=>'post-format',
                        'type' => 'switch',
                        'title' => esc_html__('Show Post Format', 'bookio'),
                        'default' => true,
                        'on' => esc_html__('Yes', 'bookio'),
                        'off' => esc_html__('No', 'bookio'),
                    ),
                    array(
                        'id'=>'hot-label',
                        'type' => 'text',
                        'title' => esc_html__('"HOT" Text', 'bookio'),
                        'desc' => esc_html__('Hot post label', 'bookio'),
                        'default' => ''
                    ),
                    array(
                        'id'=>'sidebar_blog',
                        'type' => 'image_select',
                        'title' => esc_html__('Page Layout', 'bookio'),
                        'options' => $page_layouts,
                        'default' => 'left'
                    ),
                    array(
                        'id' => 'layout_blog',
                        'type' => 'button_set',
                        'title' => esc_html__('Layout Blog', 'bookio'),
                        'options' => array(
                                'list'  =>  esc_html__( 'List', 'bookio' ),
                                'grid' =>  esc_html__( 'Grid', 'bookio' ),
								'modern' =>  esc_html__( 'Modern', 'bookio' ),
								'standar' =>  esc_html__( 'Standar', 'bookio' )
                        ),
                        'default' => 'standar',
                        'sub_desc' => esc_html__( 'Select style layout blog', 'bookio' ),
                    ),
                    array(
                        'id' => 'blog_col_large',
                        'type' => 'button_set',
                        'title' => esc_html__('Blog Listing column Desktop', 'bookio'),
                        'required' => array('layout_blog','equals','grid'),
                        'options' => array(
                                '2' => '2',
                                '3' => '3',
                                '4' => '4',                         
                                '6' => '6'                          
                            ),
                        'default' => '4',
                        'sub_desc' => esc_html__( 'Select number of column on Desktop Screen', 'bookio' ),
                    ),
                    array(
                        'id' => 'blog_col_medium',
                        'type' => 'button_set',
                        'title' => esc_html__('Blog Listing column Medium Desktop', 'bookio'),
                        'required' => array('layout_blog','equals','grid'),
                        'options' => array(
                                '2' => '2',
                                '3' => '3',
                                '4' => '4',                         
                                '6' => '6'                          
                            ),
                        'default' => '3',
                        'sub_desc' => esc_html__( 'Select number of column on Medium Desktop Screen', 'bookio' ),
                    ),   
                    array(
                        'id' => 'blog_col_sm',
                        'type' => 'button_set',
                        'title' => esc_html__('Blog Listing column Ipad Screen', 'bookio'),
                        'required' => array('layout_blog','equals','grid'),
                        'options' => array(
                                '2' => '2',
                                '3' => '3',
                                '4' => '4',                         
                                '6' => '6'                          
                            ),
                        'default' => '3',
                        'sub_desc' => esc_html__( 'Select number of column on Ipad Screen', 'bookio' ),
                    ),   					
                    array(
                        'id'=>'archives-author',
                        'type' => 'switch',
                        'title' => esc_html__('Show Author', 'bookio'),
                        'default' => true,
                        'on' => esc_html__('Yes', 'bookio'),
                        'off' => esc_html__('No', 'bookio'),
                    ),
                    array(
                        'id'=>'archives-comments',
                        'type' => 'switch',
                        'title' => esc_html__('Show Count Comments', 'bookio'),
                        'default' => true,
                        'on' => esc_html__('Yes', 'bookio'),
                        'off' => esc_html__('No', 'bookio'),
                    ),                  
                    array(
                        'id'=>'blog-excerpt',
                        'type' => 'switch',
                        'title' => esc_html__('Show Excerpt', 'bookio'),
                        'default' => true,
                        'on' => esc_html__('Yes', 'bookio'),
                        'off' => esc_html__('No', 'bookio'),
                    ),
                    array(
                        'id'=>'list-blog-excerpt-length',
                        'type' => 'text',
                        'required' => array('blog-excerpt','equals',true),
                        'title' => esc_html__('List Excerpt Length', 'bookio'),
                        'desc' => esc_html__('The number of words', 'bookio'),
                        'default' => '50',
                    ),
                    array(
                        'id'=>'grid-blog-excerpt-length',
                        'type' => 'text',
                        'required' => array('blog-excerpt','equals',true),
                        'title' => esc_html__('Grid Excerpt Length', 'bookio'),
                        'desc' => esc_html__('The number of words', 'bookio'),
                        'default' => '12',
                    ),                  
                )
            );
            $this->sections[] = array(
                'icon' => 'icofont icofont-double-right',
                'icon_class' => 'icon',
                'subsection' => true,
                'title' => esc_html__('Single Post', 'bookio'),
                'fields' => array(
                    array(
                        'id'=>'post-single-layout',
                        'type' => 'select',
                        'title' => esc_html__('Page Layout', 'bookio'),
                        'options' => array(
								'sidebar' =>  esc_html__( 'Sidebar', 'bookio' ),
                                'one_column' =>  esc_html__( 'One Column', 'bookio' ),
								'prallax_image' =>  esc_html__( 'Prallax Image', 'bookio' ),
								'simple_title' =>  esc_html__( 'Simple Title', 'bookio' ),
								'sticky_title' =>  esc_html__( 'Sticky Title', 'bookio' )
                        ),
                        'default' => 'sidebar'
                    ),
                    array(
                        'id'=>'post-title',
                        'type' => 'switch',
                        'title' => esc_html__('Show Title', 'bookio'),
                        'default' => true,
                        'on' => esc_html__('Yes', 'bookio'),
                        'off' => esc_html__('No', 'bookio'),
                    ),
                    array(
                        'id'=>'post-author',
                        'type' => 'switch',
                        'title' => esc_html__('Show Author Info', 'bookio'),
                        'default' => true,
                        'on' => esc_html__('Yes', 'bookio'),
                        'off' => esc_html__('No', 'bookio'),
                    ),
                    array(
                        'id'=>'post-comments',
                        'type' => 'switch',
                        'title' => esc_html__('Show Comments', 'bookio'),
                        'default' => true,
                        'on' => esc_html__('Yes', 'bookio'),
                        'off' => esc_html__('No', 'bookio'),
					)
				)
			);	
            $this->sections[] = array(
				'id' => 'wbc_importer_section',
				'title'  => esc_html__( 'Demo Importer', 'bookio' ),
				'icon'   => 'fa fa-cloud-download',
				'desc'   => wp_kses( 'Increase your max execution time, try 40000 I know its high but trust me.<br>
				Increase your PHP memory limit, try 512MB.<br>
				1. The import process will work best on a clean install. You can use a plugin such as WordPress Reset to clear your data for you.<br>
				2. Ensure all plugins are installed beforehand, e.g. WooCommerce - any plugins that you add content to.<br>
				3. Be patient and wait for the import process to complete. It can take up to 3-5 minutes.<br>
				4. Enjoy','social' ),				
				'fields' => array(
					array(
						'id'   => 'wbc_demo_importer',
						'type' => 'wbc_importer'
					)
				)
            );			
        }
        public function setHelpTabs() {
        }
        public function setArguments() {
            $theme = wp_get_theme(); // For use with some settings. Not necessary.
            $this->args = array(
                'opt_name'          => 'bookio_settings',
                'display_name'      => $theme->get('Name') . ' ' . esc_html__('Theme Options', 'bookio'),
                'display_version'   => esc_html__('Theme Version: ', 'bookio') . BOOKIO_VERSION,
                'menu_type'         => 'submenu',
                'allow_sub_menu'    => true,
                'menu_title'        => esc_html__('Theme Options', 'bookio'),
                'page_title'        => esc_html__('Theme Options', 'bookio'),
                'footer_credit'     => esc_html__('Theme Options', 'bookio'),
                'google_api_key' => 'AIzaSyAX_2L_UzCDPEnAHTG7zhESRVpMPS4ssII',
                'disable_google_fonts_link' => true,
                'async_typography'  => false,
                'admin_bar'         => false,
                'admin_bar_icon'       => 'dashicons-admin-generic',
                'admin_bar_priority'   => 50,
                'global_variable'   => '',
                'dev_mode'          => false,
                'customizer'        => false,
                'compiler'          => false,
                'page_priority'     => null,
                'page_parent'       => 'themes.php',
                'page_permissions'  => 'manage_options',
                'menu_icon'         => '',
                'last_tab'          => '',
                'page_icon'         => 'icon-themes',
                'page_slug'         => 'bookio_settings',
                'save_defaults'     => true,
                'default_show'      => false,
                'default_mark'      => '',
                'show_import_export' => true,
                'show_options_object' => false,
                'transient_time'    => 60 * MINUTE_IN_SECONDS,
                'output'            => true,
                'output_tag'        => true,
                'database'              => '',
                'system_info'           => false,
                'hints' => array(
                    'icon'          => 'icon-question-sign',
                    'icon_position' => 'right',
                    'icon_color'    => 'lightgray',
                    'icon_size'     => 'normal',
                    'tip_style'     => array(
                        'color'         => 'light',
                        'shadow'        => true,
                        'rounded'       => false,
                        'style'         => '',
                    ),
                    'tip_position'  => array(
                        'my' => 'top left',
                        'at' => 'bottom right',
                    ),
                    'tip_effect'    => array(
                        'show'          => array(
                            'effect'        => 'slide',
                            'duration'      => '500',
                            'event'         => 'mouseover',
                        ),
                        'hide'      => array(
                            'effect'    => 'slide',
                            'duration'  => '500',
                            'event'     => 'click mouseleave',
                        ),
                    ),
                ),
                'ajax_save'                 => true,
                'use_cdn'                   => true,
            );
            // Panel Intro text -> before the form
            if (!isset($this->args['global_variable']) || $this->args['global_variable'] !== false) {
                if (!empty($this->args['global_variable'])) {
                    $v = $this->args['global_variable'];
                } else {
                    $v = str_replace('-', '_', $this->args['opt_name']);
                }
            }
            $this->args['intro_text'] = sprintf('<p style="color: #0088cc">'.wp_kses('Please regenerate again default css files in <strong>Skin > Compile Default CSS</strong> after <strong>update theme</strong>.', 'bookio').'</p>', $v);
        }           
    }
	if ( !function_exists( 'wbc_extended_example' ) ) {
		function wbc_extended_example( $demo_active_import , $demo_directory_path ) {
			reset( $demo_active_import );
			$current_key = key( $demo_active_import );	
			if ( isset( $demo_active_import[$current_key]['directory'] ) && !empty( $demo_active_import[$current_key]['directory'] )) {				
				// Setting Menus
				$primary = get_term_by( 'name', 'Main menu', 'nav_menu' );
				$primary_vertical   	= get_term_by( 'name', 'Vertical Menu', 'nav_menu' );
				$primary_mostsearch   	= get_term_by( 'name', 'Most Search Menu', 'nav_menu' );
				$primary_topbar   		= get_term_by( 'name', 'Topbar Menu', 'nav_menu' );
				if ( isset( $primary->term_id ) && isset( $primary_vertical->term_id ) && isset( $primary_mostsearch->term_id ) && isset( $primary_topbar->term_id ) ) {
					set_theme_mod( 'nav_menu_locations', array(
							'main_navigation' 	=> $primary->term_id,
							'vertical_menu' 	=> $primary_vertical->term_id,
							'topbar_menu' 		=> $primary_topbar->term_id	
						)
					);
				}
				// Set HomePage
				$home_page = 'Home 1';
				$page = get_page_by_title( $home_page );
				if ( isset( $page->ID ) ) {
					update_option( 'page_on_front', $page->ID );
					update_option( 'show_on_front', 'page' );
				}					
			}
		}
		// Uncomment the below
		add_action( 'wbc_importer_after_content_import', 'wbc_extended_example', 10, 2 );
	}
    global $reduxBookioSettings;
    $reduxBookioSettings = new Redux_Framework_bookio_settings();
}