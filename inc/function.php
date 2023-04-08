<?php
	function bookio_get_config($option,$default='1'){
		$bookio_settings = bookio_global_settings();
		$query_string = bookio_get_query_string();
		parse_str($query_string, $params);
		if(isset($params[$option]) && $params[$option]){
			return $params[$option];
		}else{
			$value = isset($bookio_settings[$option]) ? $bookio_settings[$option] : $default;
			return $value;
		}
	}
	function bookio_get_query_string(){
		global $wp_rewrite;
		$request = remove_query_arg( 'paged' );
		$home_root = esc_url(home_url());
		$home_root = parse_url($home_root);
		$home_root = ( isset($home_root['path']) ) ? $home_root['path'] : '';
		$home_root = preg_quote( $home_root, '|' );
		$request = preg_replace('|^'. $home_root . '|i', '', $request);
		$request = preg_replace('|^/+|', '', $request);
		$request = preg_replace( "|$wp_rewrite->pagination_base/\d+/?$|", '', $request);
		$request = preg_replace( '|^' . preg_quote( $wp_rewrite->index, '|' ) . '|i', '', $request);
		$request = ltrim($request, '/');
		$qs_regex = '|\?.*?$|';
		preg_match( $qs_regex, $request, $qs_match );
		if ( !empty( $qs_match[0] ) ) {
			$query_string = $qs_match[0];
			$query_string = str_replace("?","",$query_string);
		} else {
			$query_string = '';
		}
		return 	$query_string;
	}
	function bookio_global_settings(){
		global $bookio_settings;
		return $bookio_settings;
	}
	function bookio_limit_verticalmenu(){
		global $bookio_page_id;
		$vertical = new stdClass;
		$max_number_1530	= bookio_get_config('max_number_1530',12);
		$vertical->max_number_1530 	= (get_post_meta( $bookio_page_id, 'max_number_1530', true )) ? get_post_meta($bookio_page_id, 'max_number_1530', true ) : $max_number_1530;
		
		$max_number_1200	= bookio_get_config('max_number_1200',8);
		$vertical->max_number_1200  	= (get_post_meta( $bookio_page_id, 'max_number_1200', true )) ? get_post_meta($bookio_page_id, 'max_number_1200', true ) : $max_number_1200;
		
		$max_number_991		= bookio_get_config('max_number_991',6);
		$vertical->max_number_991  	= (get_post_meta( $bookio_page_id, 'max_number_991', true )) ? get_post_meta($bookio_page_id, 'max_number_991', true ) : $max_number_991;
		
		return $vertical;
	}
	if ( ! function_exists( 'bookio_popup_newsletter' ) ) {
		function bookio_popup_newsletter() {
			$bookio_settings = bookio_global_settings(); 
			echo '<div class="popupshadow"></div>';
			echo '<div id="newsletterpopup" class="bingo-modal newsletterpopup">';
			echo '<span class="close-popup"></span>';
			echo '<div class="wp-newletter">';
				if(isset($bookio_settings['background_newletter_img']['url']) && !empty($bookio_settings['background_newletter_img']['url'])){
					echo '<div class="image"> <img src='.esc_url($bookio_settings['background_newletter_img']['url']).' alt="'.esc_attr__( 'Image Newletter','bookio' ).'"></div>';
				}
				dynamic_sidebar('newletter-popup-form');
			echo '</div>';
			echo '</div>';
		}
	}
	function bookio_config_font(){
		$config_fonts = array();
		$text_fonts = array(
			'family_font_body',
			'family_font_custom',
			'h1-font',
			'h2-font',
			'h3-font',
			'h4-font',
			'h5-font',
			'h6-font',
			'class_font_custom'
		);
		foreach ($text_fonts as $text) {
			if(bookio_get_config($text))
				$config_fonts[$text] = bookio_get_config($text);
		}
		return $config_fonts;
	}
	function bookio_get_class(){
		$class = new stdClass;
		$sidebar_left_expand 		= bookio_get_config('sidebar_left_expand',3);
		$sidebar_left_expand_md 	= bookio_get_config('sidebar_left_expand_md',3);
		$class->class_sidebar_left  = 'col-xl-'.$sidebar_left_expand.' col-lg-'.$sidebar_left_expand_md.' col-md-12 col-12';
		$sidebar_right_expand 		= bookio_get_config('sidebar_right_expand',3);
		$sidebar_right_expand_md 	= bookio_get_config('sidebar_right_expand_md',3);
		$class->class_sidebar_right  = 'col-xl-'.$sidebar_right_expand.' col-lg-'.$sidebar_right_expand_md.' col-md-12 col-12';
		$sidebar_blog = bookio_blog_sidebar();
		if($sidebar_blog == 'left' && is_active_sidebar('sidebar-blog')){
			$blog_content_expand = 12- $sidebar_left_expand;
			$blog_content_expand_md = 12- $sidebar_left_expand_md;
		}elseif($sidebar_blog == 'right' && is_active_sidebar('sidebar-blog')){
			$blog_content_expand = 12- $sidebar_right_expand;
			$blog_content_expand_md = 12- $sidebar_right_expand_md;
		}else{
			$blog_content_expand = 12;
			$blog_content_expand_md = 12;
		}
		$class->class_blog_content  = 'col-xl-'.$blog_content_expand.' col-lg-'.$blog_content_expand_md.' col-md-12 col-12';		
		$post_single_layout = bookio_post_sidebar();
		if($post_single_layout == 'sidebar' && is_active_sidebar('sidebar-blog')){
			$blog_single_expand = 12- $sidebar_left_expand;
			$blog_single_expand_md = 12- $sidebar_left_expand_md;
		}else{
			$blog_single_expand = 12;
			$blog_single_expand_md = 12;
		}
		$class->class_single_content  = 'col-xl-'.$blog_single_expand.' col-lg-'.$blog_single_expand_md.' col-md-12 col-12';		
		if( is_active_sidebar('sidebar-product')){
			$product_content_expand = 12- $sidebar_left_expand;
			$product_content_expand_md = 12- $sidebar_left_expand_md;
		}else{
			$product_content_expand = 12;
			$product_content_expand_md = 12;
		}
		$class->class_product_content  = 'col-xl-'.$product_content_expand.' col-lg-'.$product_content_expand_md.' col-md-12 col-12';		
		$layout_sigle_product = bookio_get_config("layout_sigle_product","default");
		if($layout_sigle_product == 'sidebar' && is_active_sidebar('sidebar-single-product')){
			$product_content_expand = 12- $sidebar_left_expand;
			$product_content_expand_md = 12- $sidebar_left_expand_md;
		}else{
			$product_content_expand = 12;
			$product_content_expand_md = 12;
		}
		$class->class_detail_product_content  = 'col-xl-'.$product_content_expand.' col-lg-'.$product_content_expand_md.' col-md-12 col-12';	
		$blog_col_large 	= 12/(bookio_get_config('blog_col_large',3));
		$blog_col_medium = 12/(bookio_get_config('blog_col_medium',3));
		$blog_col_sm 	= 12/(bookio_get_config('blog_col_sm',3));
		$class->class_item_blog = 'col-xl-'.$blog_col_large.' col-lg-'.$blog_col_medium.' col-md-'.$blog_col_sm.' col-sm-12 col-12';
		return $class;
	}
	function bookio_post_sidebar(){
		$post_single_layout = bookio_get_config('post-single-layout','sidebar');
		return 	$post_single_layout;
	}
	function bookio_blog_view(){
		$blog_view = bookio_get_config('layout_blog','standar');
		return 	$blog_view;
	}
	function bookio_blog_sidebar(){
		$sidebar 		= bookio_get_config('sidebar_blog','left');
		return 	$sidebar;
	}	
	function bookio_is_customize(){
		return isset($_POST['customized']) && ( isset($_POST['customize_messenger_chanel']) || isset($_POST['wp_customize']) );
	}	
	function bookio_search_form( $form ) {
		$form = '<form role="search" method="get" id="searchform" class="search-from" action="' . esc_url(home_url( '/' )) . '" >
					<div class="container">
						<div class="form-content">
							<input type="text" value="' . esc_attr(get_search_query()) . '" name="s"  class="s" placeholder="' . esc_attr__( 'Search...', 'bookio' ) . '" />
							<button id="searchsubmit" class="btn" type="submit">
								<i class="icon_search"></i>
								<span>' . esc_html__( "Search", "bookio" ) . '</span>
							</button>
						</div>
					</div>
				  </form>';
		return $form;
	}
	add_filter( 'get_search_form', 'bookio_search_form' );
	// Remove each style one by one
	add_filter( 'woocommerce_enqueue_styles', 'bookio_jk_dequeue_styles' );
	function bookio_jk_dequeue_styles( $enqueue_styles ) {
		unset( $enqueue_styles['woocommerce-general'] );	// Remove the gloss
		unset( $enqueue_styles['woocommerce-layout'] );		// Remove the layout
		unset( $enqueue_styles['woocommerce-smallscreen'] );	// Remove the smallscreen optimisation
		return $enqueue_styles;
	}
	// Or just remove them all in one line
	add_filter( 'woocommerce_enqueue_styles', '__return_false' );					
	function bookio_woocommerce_breadcrumb( $args = array() ) {
		$args = wp_parse_args( $args, apply_filters( 'woocommerce_breadcrumb_defaults', array(
			'delimiter'   => '<span class="delimiter"></span>',
			'wrap_before' => '<div class="breadcrumb" ' . ( is_single() ? 'itemprop="breadcrumb"' : '' ) . '>',
			'wrap_after'  => '</div>',
			'before'      => '',
			'after'       => '',
			'home'        => _x( 'Home', 'breadcrumb', 'bookio' )
		) ) );
		$breadcrumbs = new WC_Breadcrumb();
		if ( $args['home'] ) {
			$breadcrumbs->add_crumb( $args['home'], apply_filters( 'woocommerce_breadcrumb_home_url', home_url() ) );
		}
		$args['breadcrumb'] = $breadcrumbs->generate();
		wc_get_template( 'global/breadcrumb.php', $args );
	}
	add_filter('woocommerce_add_to_cart_fragments', 'bookio_woocommerce_header_add_to_cart_fragment');
	function bookio_woocommerce_header_add_to_cart_fragment( $fragments )
	{
	    global $woocommerce;
	    ob_start(); 
	    get_template_part( 'woocommerce/minicart-ajax' );
	    $fragments['.mini-cart'] = ob_get_clean();
	    return $fragments;
	}
	function bookio_display_view(){
		echo bookio_grid_list();
    }
	function bookio_grid_list(){
		$active_list = '';
		$product_col_large = bookio_get_config('product_col_large',4);
		$category_view_mode = bookio_category_view();
		$query_string = '?'.bookio_get_query_string();
		$active_grid = ($category_view_mode == 'grid') ? 'active' : '';
		$active_short = ($category_view_mode == 'short') ? 'active' : '';
		$active_list = ($category_view_mode == 'list') ? 'active' : '';
		$html = '<ul class="display hidden-xs">
				<li>
					<a data-view="grid" class="view-grid four '.esc_attr($active_grid).'" href="'. add_query_arg('category-view-mode', 'grid', $query_string).'"><span class="icon-column"><span class="layer first"><span></span><span></span><span></span><span></span></span><span class="layer middle"><span></span><span></span><span></span><span></span></span><span class="layer last"><span></span><span></span><span></span><span></span></span></span></a>
				</li>
				<li>
					<a data-view="short" class="view-grid short '.esc_attr($active_short).'" href="'. add_query_arg('category-view-mode', 'short', $query_string).'"><span class="icon-column"><span></span><span></span><span></span><span></span></span></a>
				</li>
				<li>
					<a data-view="list" class="view-list '.esc_html($active_list).'" href="'. add_query_arg('category-view-mode', 'list', $query_string).'"><span class="icon-column"><span class="layer first"><span></span><span></span></span><span class="layer middle"><span></span><span></span></span><span class="layer last"><span></span><span></span></span></span></a>
				</li>
			</ul>';
		return $html;
	}
	function bookio_category_view(){
		$category_view_mode 		= bookio_get_config('category-view-mode','grid');	
		return 	$category_view_mode;
	}	
	function bookio_main_menu($id,$name,$layout = "") {
		global $bookio_settings, $post;
		$show_cart = $show_wishlist = false;
		if ( isset($bookio_settings['show_cart']) ) {
		$show_cart            = $bookio_settings['show_cart'];
		}
		if ( isset($bookio_settings['show_wishlist']) ) {
		$show_wishlist            = $bookio_settings['show_wishlist'];
		}
		$vertical_header_text = (isset($bookio_settings['vertical_header_text']) && $bookio_settings['vertical_header_text']) ? $bookio_settings['vertical_header_text'] : '';
		$page_menu = $menu_output = $menu_full_output = $menu_with_search_output = $menu_float_output = $menu_vert_output = "";
		$main_menu_args = array(
			'echo'            => false,
			'theme_location' => $name,
			'walker' => new bookio_mega_menu_walker,
		);
		$menu_output .= '<nav id="'.$id.'" class="std-menu clearfix">'. "\n";
		if(function_exists('wp_nav_menu')) {
			if (has_nav_menu('main_navigation')) {
				$menu_output .= wp_nav_menu( $main_menu_args );
			}
			else {
				if(is_user_logged_in()){
					$menu_output .= '<div class="no-menu">'. esc_html__("Please assign a menu to the Main Menu in Appearance > Menus", "bookio").'</div>';
				}
			}
		}
		$menu_output .= '</nav>'. "\n";
		switch ($layout) {
			case 'full':
					$menu_full_output .= '<div class="container">'. "\n";
					$menu_full_output .= '<div class="row">'. "\n";
					$menu_full_output .= '<div class="menu-left">'. "\n";
					$menu_full_output .= $menu_output . "\n";
					$menu_full_output .= '</div>'. "\n";
					$menu_full_output .= '<div class="menu-right">'. "\n";
					$menu_full_output .= '</div>'. "\n";
					$menu_full_output .= '</div>'. "\n";
					$menu_full_output .= '</div>'. "\n";
					$menu_output = $menu_full_output;
				break;
			case 'float':
					$menu_float_output .= '<div class="float-menu">'. "\n";
					$menu_float_output .= $menu_output . "\n";
					$menu_float_output .= '</div>'. "\n";
					$menu_output = $menu_float_output;
				break;
			case 'float-2':
					$menu_float_output .= '<div class="float-menu container">'. "\n";
					$menu_float_output .= $menu_output . "\n";
					$menu_float_output .= '</div>'. "\n";
					$menu_output = $menu_float_output;
				break;				
			case 'vertical':
				$menu_vertical_output .= $menu_output . "\n";
				$menu_vertical_output .= '<div class="vertical-menu-bottom">'. "\n";
				if($vertical_header_text)
				$menu_vertical_output .= '<div class="copyright">'.do_shortcode(stripslashes($vertical_header_text)).'</div>'. "\n";
				$menu_vertical_output .= '</div>'. "\n";
				$menu_output = $menu_vertical_output;
				break;
		}	
		return $menu_output;
	}				
	add_action('admin_enqueue_scripts','bookio_upload_scripts');	
	function bookio_upload_scripts()
    {
        wp_enqueue_script('media-upload');
        wp_enqueue_script('thickbox');
        wp_enqueue_style('thickbox');
    }		
	function bookio_body_classes( $classes ) {
		if (is_single() || is_page() && !is_front_page()) {
			$classes[] = basename(get_permalink());
		}			
		$type_banner 					= 	bookio_get_config('banners_effect');
		$show_background_shop 			= 	bookio_get_config('show_background_shop');
		$post_single_layout 			= bookio_post_sidebar();
		$classes[] 						= 	$type_banner;		
		$direction 						= 	bookio_get_direction(); 
		if($direction && $direction == 'rtl'){
			$classes[] = 'rtl';
		}
		if(( function_exists('is_shop') && is_shop() ) || ( function_exists('is_product_category') && is_product_category() )){
			$classes[] = 'show-background-'.$show_background_shop;
		}
		if(is_single() && is_singular( 'post' )){
			$classes[] = 'single-post-'.$post_single_layout;
		}
		return $classes;
	}
	add_filter( 'body_class', 'bookio_body_classes' );
	function bookio_post_classes( $classes ) {
		if ( ! post_password_required() && ! is_attachment() && has_post_thumbnail() ) {
			$classes[] = 'has-post-thumbnail';
		}
		return $classes;
	}
	add_filter( 'post_class', 'bookio_post_classes' );
	function bookio_get_excerpt($limit = 45, $more_link = true, $more_style_block = false) {
		$bookio_settings = bookio_global_settings();
		if (!$limit) {
			$limit = 45;
		}
		if (has_excerpt()) {
			$content = get_the_excerpt();
		} else {
			$content = get_the_content();
		}
		if($content)
		{
			$check_readmore = false;
			$content = bookio_strip_tags( apply_filters( 'the_content', $content ) );
			$content = explode(' ', $content, $limit);
			if (count($content) >= $limit) {
				$check_readmore = true;
				array_pop($content);
				$content = implode(" ",$content).'... ';
			} else {
				$content = implode(" ",$content);
			}
			$content = '<p class="post-excerpt">'.wp_kses($content,'social').'</p>';
			if ($more_link && $check_readmore) {
				if ($more_style_block) {
					$content .= ' <a class="read-more read-more-block" href="'.esc_url( apply_filters( 'the_permalink', get_permalink() ) ).'">'.esc_html__("Read more", "bookio").'</a>';
				} else {
					$content .= ' <a class="read-more" href="'.esc_url( apply_filters( 'the_permalink', get_permalink() ) ).'">'.esc_html__("Read more", "bookio").'</a>';
				}
			}
		}
		return $content;
	}
	function bookio_strip_tags( $content ) {
		$content = str_replace( ']]>', ']]&gt;', $content );
		$content = preg_replace("/<script.*?\/script>/s", "", $content);
		$content = preg_replace("/<style.*?\/style>/s", "", $content);
		$content = strip_tags( $content );
		return $content;
	}
	if( !function_exists( 'bookio_get_direction' ) ) :
	function bookio_get_direction(){
		$direction = bookio_get_config('direction','ltr');		
		if (isset($_COOKIE['bookio_direction_cookie']))
			$direction = $_COOKIE['bookio_direction_cookie'];
		if(isset($_GET['direction']) && $_GET['direction'])
			$direction = $_GET['direction'];
		return 	$direction;
	}
	endif;	
	function bookio_get_entry_content_asset( $post_id ){
		$post = get_post( $post_id );
		$content = apply_filters ("the_content", $post->post_content);
		$video = get_media_embedded_in_content( $content, array( 'video', 'object', 'embed', 'iframe' ) );
		if ( ! empty( $video ) ) {
			$html = '';
			foreach ( $video as $video_html ) {
				$html .=   '<div class="video-wrapper">';
					$html .= $video_html;
				$html .= '</div>';
			}
			return $html;
		}
	}
	function bookio_loading_overlay(){
		$bookio_settings 		= bookio_global_settings();
		$gif_loading 			= isset($bookio_settings['gif_loading']['url']) && !empty($bookio_settings['gif_loading']['url']);
		$gif_loading_width	 	= bookio_get_config('gif_loading_width','');
		if(isset($bookio_settings['show-loading-overlay']) && $bookio_settings['show-loading-overlay'] ){ ?>
			<div class="loading-gif">
				<div id="loader-gif" <?php if($gif_loading){ ?> style="background:url('<?php echo esc_url($bookio_settings['gif_loading']['url']); ?>') no-repeat;width:<?php echo esc_attr($gif_loading_width); ?>px;background-size: contain;background-position: center;"<?php } ?>>
				</div>
			</div>
		<?php }
	}
	function bookio_header_logo(){
		$bookio_settings = bookio_global_settings();
		$size_sitelogo	 = bookio_get_config('size_sitelogo','');
		$sitelogo = (isset($bookio_settings['sitelogo']['url']) && $bookio_settings['sitelogo']['url']) ? $bookio_settings['sitelogo']['url'] : "";
		$page_logo_url = get_post_meta( get_the_ID(), 'page_logo', true );
		$page_logo_url = ($page_logo_url) ? $page_logo_url : $sitelogo; ?>
		<div class="wpbingoLogo">
			<a  href="<?php echo esc_url( home_url( '/' ) ); ?>">
				<?php if($page_logo_url){ ?>
					<img <?php if($size_sitelogo){ ?>style="max-height:<?php echo esc_attr($size_sitelogo); ?>px"<?php } ?> src="<?php echo esc_url($page_logo_url); ?>" alt="<?php bloginfo('name'); ?>"/>
				<?php }else{
					$logo = get_template_directory_uri().'/images/logo/logo.png'; ?>
					<img <?php if($size_sitelogo){ ?>style="max-height:<?php echo esc_attr($size_sitelogo); ?>px"<?php } ?> src="<?php echo esc_attr( $logo ); ?>" alt="<?php bloginfo('name'); ?>"/>
				<?php } ?>
			</a>
		</div> 
	<?php }
	function bookio_top_menu(){
		$bookio_settings = bookio_global_settings();
		echo '<div class="wpbingo-menu-wrapper">
			<div class="megamenu">
				<nav class="navbar-default">
					<div  class="bwp-navigation primary-navigation navbar-mega" data-text_close = "'.esc_html__("Close","bookio").'">
						'.bookio_main_menu( 'main-navigation','main_navigation', 'float' ).'
					</div>
				</nav> 
			</div>       
		</div>';
	}
	
	function bookio_sticky_menu(){
		$bookio_settings = bookio_global_settings();
		echo '<div class="wpbingo-menu-wrapper">
			<div class="megamenu">
				<nav class="navbar-default">
					<div  class="bwp-navigation primary-navigation navbar-mega" data-text_close = "'.esc_html__("Close","bookio").'">
						'.bookio_main_menu( 'main-navigations','main_navigation', 'float' ).'
					</div>
				</nav> 
			</div>       
		</div>';
	}
	function bookio_navbar_vertical_menu(){
		echo '<div class="wpbingo-verticalmenu-mobile">
			<div class="navbar-header">
				<button type="button" id="show-verticalmenu"  class="navbar-toggle">
					<span>'. esc_html__("Vertical","bookio") .'</span>
				</button>
			</div>
		</div>';
	}
	
	function bookio_vertical_menu() {
		global $bookio_settings;
		$menu_output = "";
		$vertical_menu_args = array(
			'echo'            => false,
			'theme_location' => 'vertical_menu',
			'walker' => new bookio_mega_menu_walker,
		);	
		if(function_exists('wp_nav_menu')) {
			if (has_nav_menu('vertical_menu')) {
				$menu_output .=	'<h3 class="widget-title"><span class="icon-cate"></span>'.esc_html__("All Departments","bookio").'</h3>';
				$menu_output .='<div class="verticalmenu">
					<div  class="bwp-vertical-navigation primary-navigation navbar-mega">
						'.wp_nav_menu( $vertical_menu_args ).'
					</div> 
				</div>';
			}
		}
		
		return $menu_output;
	}
	
	function bookio_dropdown_vertical_menu(){
		global $bookio_page_id;
		$show_vertical_menu  = (get_post_meta( $bookio_page_id, 'show_vertical_menu', true )) ? get_post_meta($bookio_page_id, 'show_vertical_menu', true ) : 'accordion';
		return $show_vertical_menu;
	}	
	
	function bookio_category_post(){
		global $post;
		$obj_category = new stdClass;
		$term_list = wp_get_post_terms($post->ID,'category',array('fields'=>'ids'));
		$cat_id = (int)$term_list[0];
		$category = get_term( $cat_id, 'category' );
		$obj_category->name = $category->name;
		$obj_category->cat_link = get_term_link ($cat_id, 'category');	
		return $obj_category;
	}
	function bookio_copyright(){
		$bookio_settings = bookio_global_settings();?>
		<div class="bwp-copyright">
			<div class="container">		
			    <div class="row">
					<?php if(isset($bookio_settings['footer-copyright']) && $bookio_settings['footer-copyright']) : ?>		
						<div class="site-info col-sm-6 col-xs-12">
							<?php echo esc_html($bookio_settings['footer-copyright']); ?>
						</div><!-- .site-info -->
					<?php else: ?>					
						<div class="site-info col-sm-6 col-xs-12">
							<?php echo esc_html__( "Copyright 2021 ","bookio"); ?>					
							<a href="<?php echo esc_url( home_url( '/' ) ); ?>"><?php esc_html__("bookio", "bookio"); ?></a>
							<?php echo esc_html__( ". All Rights Reserved.","bookio"); ?>
						</div><!-- .site-info -->		
					<?php endif; ?>
					<?php if(isset($bookio_settings['footer-payments']) && $bookio_settings['footer-payments']) : ?>
						<div class="payment col-sm-6 col-xs-12">
							<a href="<?php echo isset($bookio_settings['footer-payments-link']) ? esc_url($bookio_settings['footer-payments-link']) : "#"; ?>">
								<img src="<?php echo isset($bookio_settings['footer-payments-image']['url']) ? esc_url($bookio_settings['footer-payments-image']['url']) : ""; ?>" alt="<?php echo isset($bookio_settings['footer-payments-image-alt']) ? esc_attr($bookio_settings['footer-payments-image-alt']) : ""; ?>" />
							</a>
						</div>		
					<?php endif; ?>	
				</div>
			</div>
		</div>	
		<?php	
	}
	function bookio_render_footer($footer_style){
		$elementor_instance = Elementor\Plugin::instance();
		return $elementor_instance->frontend->get_builder_content_for_display( $footer_style );
	}
	if( !is_admin() ){
		add_filter( 'language_attributes', 'bookio_direction', 20 );
		function bookio_direction( $doctype = 'html' ){
	   		$direction = bookio_get_direction();
	   		if ( ( function_exists( 'is_rtl' ) && is_rtl() ) || $direction == 'rtl' ){
	    		$attribute[] = 'direction="rtl"';
				$attribute[] = 'dir="rtl"';
	    		$attribute[] = 'class="rtl"';
	   		}
	   		( $direction === 'rtl' ) ? $lang = 'ar' : $lang = get_bloginfo('language');
	   		if ( $lang ) {
	   			if ( get_option('html_type') == 'text/html' || $doctype == 'html' )
	    			$attribute[] = "lang=\"$lang\"";
	   			if ( get_option('html_type') != 'text/html' || $doctype == 'xhtml' )
	    			$attribute[] = "xml:lang=\"$lang\"";
	   		}
	   		$bookio_output = implode(' ', $attribute);
	   		return $bookio_output;
		}
	}	
	function bookio_comment( $comment, $args, $depth ) {
		if ( 'div' == $args['style'] ) {
			$tag = 'div';
			$add_below = 'comment';
		} else {
			$tag = 'li';
			$add_below = 'div-comment';
		}
		?>
		<div class="media">
			<div class="media-left">
				<?php echo get_avatar( $comment, 70 ); ?>
			</div>
			<div class="media-body">
				<div class="comment-meta media-content commentmetadata">
					<div class="comment-author vcard">
					<?php printf( wp_kses_post( '<h2 class="media-heading">%s</h2>', 'bookio' ), get_comment_author_link() ); ?>
					</div>
					<?php if ( '0' == $comment->comment_approved ) : ?>
					<em class="comment-awaiting-moderation"><?php echo esc_html__( "Your comment is awaiting moderation.", "bookio" ); ?></em>
					<?php endif; ?>
					<div class="media-silver">
						<a href="<?php echo esc_url( htmlspecialchars( get_comment_link( $comment->comment_ID ) ) ); ?>" class="comment-date">
							<?php echo '<time datetime="' . get_comment_date( 'c' ) . '">' . get_comment_date() . '</time>'; ?>
						</a>
						<?php edit_comment_link( __( 'Edit', 'bookio' ), '  ', '' ); ?>
					</div>
					<div id="div-comment-<?php comment_ID() ?>" class="comment-content">
						<div class="comment-text">
						<?php comment_text(); ?>
						</div>
					</div>
					<?php comment_reply_link( array_merge( $args, array( 'add_below' => $add_below, 'depth' => $depth, 'max_depth' => $args['max_depth'] ) ) ); ?>
				</div>
			</div>
		</div>
	<?php
	}
	function bookio_prefix_kses_allowed_html($allowed_tags, $context) {
		switch($context) {
			case 'social': 
			$allowed_tags = array(
				'a' => array(
					'class' => array(),
					'href'  => array(),
					'rel'   => array(),
					'title' => array(),
				),
				'abbr' => array(
					'title' => array(),
				),
				'b' => array(),
				'blockquote' => array(
					'cite'  => array(),
				),
				'cite' => array(
					'title' => array(),
				),
				'code' => array(),
				'br' => array(),
				'del' => array(
					'datetime' => array(),
					'title' => array(),
				),
				'dd' => array(),
				'div' => array(
					'class' => array(),
					'title' => array(),
					'style' => array(),
					'data-title' => array(),
				),
				'dl' => array(),
				'dt' => array(),
				'em' => array(),
				'h1' => array(),
				'h2' => array(),
				'h3' => array(),
				'h4' => array(),
				'h5' => array(),
				'h6' => array(),
				'i' => array(
					'class'  => array(),
				),
				'img' => array(
					'alt'    => array(),
					'class'  => array(),
					'height' => array(),
					'src'    => array(),
					'width'  => array(),
				),
				'li' => array(
					'class' => array(),
				),
				'ol' => array(
					'class' => array(),
				),
				'p' => array(
					'class' => array(),
				),
				'q' => array(
					'cite' => array(),
					'title' => array(),
				),
				'span' => array(
					'class' => array(),
					'title' => array(),
					'style' => array(),
				),
				'strike' => array(),
				'strong' => array(),
				'ul' => array(
					'class' => array(),
				),
				'button' => array(
					'class' => array(),
					'type' => array(),
					'data-id' => array(),
				),				
			);
			return $allowed_tags;
			default:
			return $allowed_tags;
		}
	}
	add_filter( 'wp_kses_allowed_html', 'bookio_prefix_kses_allowed_html', 10, 2);
	if ( ! function_exists( 'wp_body_open' ) ) {
		function wp_body_open() {
			do_action( 'wp_body_open' );
		}
	}
	function bookio_menu_mobile($vertical = false){
	$bookio_settings = bookio_global_settings();
	$cart_layout = bookio_get_config('cart-layout','dropdown');
	$show_searchform = (isset($bookio_settings['show-searchform']) && $bookio_settings['show-searchform']) ? ($bookio_settings['show-searchform']) : false;
	$show_wishlist = (isset($bookio_settings['show-wishlist']) && $bookio_settings['show-wishlist']) ? ($bookio_settings['show-wishlist']) : false;
	$show_minicart = (isset($bookio_settings['show-minicart']) && $bookio_settings['show-minicart']) ? ($bookio_settings['show-minicart']) : false;
	?>
	<div class="header-mobile">
		<div class="container">
			<div class="row">
				<?php if( $vertical || ($show_minicart && class_exists( 'WooCommerce' )) ){ ?>
				<div class="col-xl-4 col-lg-4 col-md-4 col-sm-3 col-3 header-left">
					<div class="navbar-header">
						<button type="button" id="show-megamenu"  class="navbar-toggle">
							<span><?php echo esc_html__("Menu","bookio"); ?></span>
						</button>
					</div>
				</div>
				<div class="col-xl-4 col-lg-4 col-md-4 col-sm-6 col-6 header-center ">
					<?php bookio_header_logo(); ?>
				</div>
				<div class="col-xl-4 col-lg-4 col-md-4 col-sm-3 col-3 header-right">
					<?php if($vertical){?>
						<?php bookio_navbar_vertical_menu(); ?>
					<?php } ?>
					<?php if($show_minicart && class_exists( 'WooCommerce' )){ ?>
					<div class="bookio-topcart <?php echo esc_attr($cart_layout); ?>">
						<?php get_template_part( 'woocommerce/minicart-ajax' ); ?>
					</div>
					<?php } ?>
				</div>
				<?php }else{ ?>
					<div class="col-xl-8 col-lg-8 col-md-8 col-sm-8 col-8 header-left header-left-default ">
						<?php bookio_header_logo(); ?>
					</div>
					<div class="col-xl-4 col-lg-4 col-md-4 col-sm-4 col-4 header-right header-right-default">
						<div class="navbar-header">
							<button type="button" id="show-megamenu"  class="navbar-toggle">
								<span><?php echo esc_html__("Menu","bookio"); ?></span>
							</button>
						</div>
					</div>
				<?php } ?>
			</div>
		</div>
		<?php if(class_exists( 'WooCommerce' )){ ?>
		<div class="header-mobile-fixed">
			<div class="shop-page">
				<a href="<?php echo get_permalink( wc_get_page_id( 'shop' ) ); ?>"><i class="wpb-icon-shop"></i></a>
			</div>
			<div class="my-account">
				<div class="login-header">
					<a href="<?php echo get_permalink( wc_get_page_id( 'myaccount' ) ); ?>"><i class="wpb-icon-user"></i></a>
				</div>
			</div>		
			<!-- Begin Search -->
			<?php if($show_searchform){ ?>
			<div class="search-box">
				<div class="search-toggle"><i class="wpb-icon-magnifying-glass"></i></div>
			</div>
			<?php } ?>
			<!-- End Search -->
			<?php if($show_wishlist && class_exists( 'WPCleverWoosw' )){ ?>
			<div class="wishlist-box">
				<a href="<?php echo WPcleverWoosw::get_url(); ?>"><i class="wpb-icon-heart"></i></a>
			</div>
			<?php } ?>
		</div>
		<?php } ?>
	</div>
	<?php }
	function bookio_menu_mobile2($vertical = false){
	$bookio_settings = bookio_global_settings();
	$cart_layout = bookio_get_config('cart-layout','dropdown');
	$show_searchform = (isset($bookio_settings['show-searchform']) && $bookio_settings['show-searchform']) ? ($bookio_settings['show-searchform']) : false;
	$show_wishlist = (isset($bookio_settings['show-wishlist']) && $bookio_settings['show-wishlist']) ? ($bookio_settings['show-wishlist']) : false;
	$show_minicart = (isset($bookio_settings['show-minicart']) && $bookio_settings['show-minicart']) ? ($bookio_settings['show-minicart']) : false;
	?>
	<div class="header-mobile">
		<div class="container">
			<div class="row">
				<?php if( $vertical || ($show_minicart && class_exists( 'WooCommerce' )) ){ ?>
				<div class="col-xl-4 col-lg-4 col-md-4 col-sm-3 col-3 header-left">
					<?php if($vertical){?>
						<?php bookio_navbar_vertical_menu(); ?>
					<?php } ?>
				</div>
				<div class="col-xl-4 col-lg-4 col-md-4 col-sm-6 col-6 header-center ">
					<?php bookio_header_logo(); ?>
				</div>
				<div class="col-xl-4 col-lg-4 col-md-4 col-sm-3 col-3 header-right">
					<?php if($show_minicart && class_exists( 'WooCommerce' )){ ?>
					<div class="bookio-topcart <?php echo esc_attr($cart_layout); ?>">
						<?php get_template_part( 'woocommerce/minicart-ajax' ); ?>
					</div>
					<?php } ?>
				</div>
				<?php }else{ ?>
					<div class="col-xl-8 col-lg-8 col-md-8 col-sm-8 col-8 header-left header-left-default ">
						<?php bookio_header_logo(); ?>
					</div>
					<div class="col-xl-4 col-lg-4 col-md-4 col-sm-4 col-4 header-right header-right-default">
						<div class="navbar-header">
							<button type="button" id="show-megamenu"  class="navbar-toggle">
								<span><?php echo esc_html__("Menu","bookio"); ?></span>
							</button>
						</div>
					</div>
				<?php } ?>
			</div>
		</div>
		<?php if(class_exists( 'WooCommerce' )){ ?>
		<div class="header-mobile-fixed">
			<div class="shop-page">
				<a href="<?php echo get_permalink( wc_get_page_id( 'shop' ) ); ?>"><i class="wpb-icon-shop"></i></a>
			</div>
			<div class="my-account">
				<div class="login-header">
					<a href="<?php echo get_permalink( wc_get_page_id( 'myaccount' ) ); ?>"><i class="wpb-icon-user"></i></a>
				</div>
			</div>		
			<!-- Begin Search -->
			<?php if($show_searchform){ ?>
			<div class="search-box">
				<div class="search-toggle"><i class="wpb-icon-magnifying-glass"></i></div>
			</div>
			<?php } ?>
			<!-- End Search -->
			<?php if($show_wishlist && class_exists( 'WPCleverWoosw' )){ ?>
			<div class="wishlist-box">
				<a href="<?php echo WPcleverWoosw::get_url(); ?>"><i class="wpb-icon-heart"></i></a>
			</div>
			<?php } ?>
		</div>
		<?php } ?>
	</div>
	<?php }
	
	function bookio_menu_stcky(){
	$bookio_settings = bookio_global_settings();
	$cart_layout = bookio_get_config('cart-layout','dropdown');
	$cart_style = bookio_get_config('cart-style','light');
	$show_searchform = (isset($bookio_settings['show-searchform']) && $bookio_settings['show-searchform']) ? ($bookio_settings['show-searchform']) : false;
	$show_wishlist = (isset($bookio_settings['show-wishlist']) && $bookio_settings['show-wishlist']) ? ($bookio_settings['show-wishlist']) : false;
	$show_minicart = (isset($bookio_settings['show-minicart']) && $bookio_settings['show-minicart']) ? ($bookio_settings['show-minicart']) : false;
	$show_compare = (isset($bookio_settings['show-compare']) && $bookio_settings['show-compare']) ? ($bookio_settings['show-compare']) : false;
	?>
	<div class="header-sticky">
		<?php if(($show_minicart || $show_wishlist || $show_searchform|| $show_compare || is_active_sidebar('top-link')) && class_exists( 'WooCommerce' ) ){ ?>
		<div class='header-content-sticky'>
			<div class="container">
				<div class="row">
					<div class="col-xl-9 col-lg-9 col-md-12 col-sm-12 col-12 header-center content-header">
						<?php bookio_header_logo(); ?>
						<div class="content-header-main">
							<div class="wpbingo-menu-mobile header-menu">
								<div class="header-menu-bg">
									<?php bookio_sticky_menu(); ?>
								</div>
							</div>
						</div>
					</div>
					<div class="col-xl-3 col-lg-3 col-md-12 col-sm-12 col-12 header-right">
						<div class="header-page-link">
							<div class="login-header">
								<?php if (is_user_logged_in()) { ?>
									<?php if(is_active_sidebar('top-link')){ ?>
										<div class="block-top-link">
											<?php dynamic_sidebar( 'top-link' ); ?>
										</div>
									<?php } ?>
								<?php }else{ ?>
									<a class="active-login" href="#" ><i class="icon-user"></i></a>
									<?php bookio_login_form(); ?>
								<?php } ?>
							</div>
							<?php if($show_wishlist && class_exists( 'WPCleverWoosw' )){ ?>
							<div class="wishlist-box">
								<a href="<?php echo WPcleverWoosw::get_url(); ?>"><i class="icon-love"></i></a>
							</div>
							<?php } ?>
							<?php if($show_minicart && class_exists( 'WooCommerce' )){ ?>
							<div class="bookio-topcart <?php echo esc_attr($cart_layout); ?> <?php echo esc_attr($cart_style); ?>">
								<?php get_template_part( 'woocommerce/minicart-ajax' ); ?>
							</div>
							<?php } ?>
						</div>
					</div>
				</div>
			</div>				
		</div><!-- End header-wrapper -->
		<?php }else{ ?>
			<div class="header-normal">
				<div class='header-wrapper' data-sticky_header="<?php echo esc_attr($bookio_settings['enable-sticky-header']); ?>">
					<div class="container">
						<div class="row">
							<div class="col-xl-3 col-lg-3 col-md-6 col-sm-6 col-6 header-left">
								<?php bookio_header_logo(); ?>
							</div>
							<div class="col-xl-9 col-lg-9 col-md-6 col-sm-6 col-6 wpbingo-menu-mobile header-main">
								<div class="header-menu-bg">
									<?php bookio_top_menu(); ?>
								</div>
							</div>
						</div>
					</div>
				</div>
			</div>
		<?php } ?>
	</div>
	<?php }
	
	function bookio_campbar(){
	$bookio_settings = bookio_global_settings();
	$show_campbar 			= bookio_get_config('show-campbar',false);
	$img_campbar 			= isset($bookio_settings['img-campbar']['url']) && !empty($bookio_settings['img-campbar']['url']);
	$color_campbar 			= bookio_get_config('color-campbar','424cc7');
	$link_campbar 			= bookio_get_config('link-campbar','#');
	$content_campbar 		= bookio_get_config('content-campbar','20% OFF EVERYTHING – USE CODE:FLASH20 – ENDS SUNDAY');
	if($show_campbar) {
	?>
	<div class="header-campbar" style="<?php if($show_campbar) { ?>background-color:<?php echo esc_attr($color_campbar); ?>;<?php if($img_campbar){ ?>background:url('<?php echo esc_url($bookio_settings['img-campbar']['url']); ?>')<?php } } ?>">
		<div class="content-campbar">
			<a href="<?php echo esc_attr($link_campbar); ?>">
				<div class="content">
					<?php echo esc_html($content_campbar); ?>
				</div>
			</a>
			<div class="close-campbar">
				<i class="icon_close"></i>
			</div>
		</div>
	</div>
	<?php } }
	function bookio_login_form() { ?>
	<?php do_action( 'woocommerce_after_customer_login_form' ); ?>
		<div class="form-login-register">
			<div class="remove-form-login-register"></div>
			<div class="box-form-login">
				<div class="box-content">
					<div class="form-login active">
						<form method="post" class="login">
							<div class="login-top">
								<h2><?php echo esc_html__("Sign in","bookio") ?></h2>
								<div class="button-next-reregister" ><a href="<?php echo get_permalink( wc_get_page_id( 'myaccount' ) ); ?>"><?php echo esc_html__("Create An Account","bookio") ?></a></div>
							</div>
							<div class="content">
								<?php do_action( 'woocommerce_login_form_start' ); ?>
								<div class="username">
									<label><?php echo esc_html__("Uesrname or email","bookio") ?></label>
									<input type="text" required="required" class="input-text" name="username" id="username" placeholder="<?php echo esc_attr__("Your name","bookio") ?>" />
								</div>
								<div class="password">
									<label><?php echo esc_html__("Password","bookio") ?></label>
									<input class="input-text" required="required" type="password" name="password" id="password" placeholder="<?php echo esc_attr__("Password","bookio") ?>" />
								</div>
								<div class="rememberme-lost">
									<div class="rememberme">
										<input name="rememberme" type="checkbox" id="rememberme" value="forever" />
										<label for="rememberme" class="inline"><?php echo esc_html__( "Remember me", "bookio" ); ?></label>
									</div>
									<div class="lost_password">
										<a href="<?php echo esc_url( wc_lostpassword_url() ); ?>"><?php echo esc_html__( "Lost your password?", "bookio" ); ?></a>
									</div>
								</div>
								<div class="button-login">
									<?php wp_nonce_field( 'woocommerce-login', 'woocommerce-login-nonce' ); ?>
									<input type="submit" class="button" name="login" value="<?php echo esc_attr__( "Login", "bookio" ); ?>" /> 
								</div>
							</div>
						</form>
					</div>
				</div>
			</div>
		</div>
	<?php }
?>