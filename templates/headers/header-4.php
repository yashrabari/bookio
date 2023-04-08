	<?php 
		$bookio_settings = bookio_global_settings();
		$cart_layout = bookio_get_config('cart-layout','dropdown');
		$cart_style = bookio_get_config('cart-style','light');
		$show_minicart = (isset($bookio_settings['show-minicart']) && $bookio_settings['show-minicart']) ? ($bookio_settings['show-minicart']) : false;
		$enable_sticky_header = ( isset($bookio_settings['enable-sticky-header']) && $bookio_settings['enable-sticky-header'] ) ? ($bookio_settings['enable-sticky-header']) : false;
		$show_searchform = (isset($bookio_settings['show-searchform']) && $bookio_settings['show-searchform']) ? ($bookio_settings['show-searchform']) : false;
		$show_wishlist = (isset($bookio_settings['show-wishlist']) && $bookio_settings['show-wishlist']) ? ($bookio_settings['show-wishlist']) : false;
		$show_currency = (isset($bookio_settings['show-currency']) && $bookio_settings['show-currency']) ? ($bookio_settings['show-currency']) : false;
		$show_menutop = (isset($bookio_settings['show-menutop']) && $bookio_settings['show-menutop']) ? ($bookio_settings['show-menutop']) : false;
		$show_mostsearch = (isset($bookio_settings['show-mostsearch']) && $bookio_settings['show-mostsearch']) ? ($bookio_settings['show-mostsearch']) : false;
		$sticky_header = (isset($bookio_settings['enable-sticky-header']) && $bookio_settings['enable-sticky-header']) ? ($bookio_settings['enable-sticky-header']) : false;
	?>
	<h1 class="bwp-title hide"><a href="<?php echo esc_url( home_url( '/' ) ); ?>" rel="home"><?php bloginfo( 'name' ); ?></a></h1>
	<header id='bwp-header' class="bwp-header header-v4">
		<?php bookio_campbar(); ?>
		<?php if($sticky_header) { bookio_menu_stcky(); } ?>
		<?php if(isset($bookio_settings['show-header-top']) && $bookio_settings['show-header-top']){ ?>
		<div id="bwp-topbar" class="topbar-v1 hidden-sm hidden-xs">
			<div class="topbar-inner">
				<div class="container">
					<div class="row">
						<div class="col-xl-6 col-lg-6 col-md-6 col-sm-6 topbar-left hidden-sm hidden-xs">
							<?php if($show_menutop){ ?>
								<?php wp_nav_menu( 
								  array( 
									  'theme_location' => 'topbar_menu', 
									  'container' => 'false', 
									  'menu_id' => 'topbar_menu', 
									  'menu_class' => 'menu'
								   ) 
								); ?>
							<?php } ?>
						</div>
						<div class="col-xl-6 col-lg-6 col-md-12 col-sm-12 col-12 topbar-right">
							<?php if( isset($bookio_settings['ship']) && $bookio_settings['ship'] ) : ?>
							<div class="ship hidden-xs hidden-sm">
								<div class="content">
									<label><?php echo esc_html__("NOTICE !","bookio") ?></label><?php echo esc_html($bookio_settings['ship']); ?>
								</div>
							</div>
							<?php endif; ?>
						</div>
					</div>
				</div>
			</div>
		</div>
		<?php } ?>
		<?php bookio_menu_mobile(); ?>
		<div class="header-desktop">
			<?php if(($show_minicart || $show_wishlist || $show_searchform || is_active_sidebar('top-link')) && class_exists( 'WooCommerce' ) ){ ?>
			<div class='header-wrapper' data-sticky_header="<?php echo esc_attr($sticky_header); ?>">
				<div class="container">
					<div class="row">
						<div class="col-xl-3 col-lg-3 col-md-12 col-sm-12 col-12 header-left">
							<?php bookio_header_logo(); ?>
						</div>
						<div class="col-xl-6 col-lg-6 col-md-12 col-sm-12 col-12 header-center content-header">
							<div class="content-header-main">
								<div class="wpbingo-menu-mobile header-menu">
									<div class="header-menu-bg">
										<?php bookio_top_menu(); ?>
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
	</header><!-- End #bwp-header -->