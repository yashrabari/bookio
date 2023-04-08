<?php
    /*
    *
    *	Wpbingo Framework Menu Functions
    *	------------------------------------------------
    *	Wpbingo Framework v3.0
    * 	Copyright Wpbingo Ideas 2017 - http://wpbingosite.com/
    *
    *	bookio_setup_menus()
    *
    */
    /* CUSTOM MENU SETUP
    ================================================== */
    register_nav_menus( array(
        'main_navigation' 	=> esc_html__( 'Main Menu', 'bookio' ),
		'vertical_menu'   	=> esc_html__( 'Vertical Menu', 'bookio' ),
		'topbar_menu'   	=> esc_html__( 'Topbar Menu', 'bookio' )
    ) );
?>