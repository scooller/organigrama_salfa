<?php
function theme_enqueue() {	
	remove_action( 'wp_head', 'wp_print_scripts' );
    remove_action( 'wp_head', 'wp_print_head_scripts', 9 );
    remove_action( 'wp_head', 'wp_enqueue_scripts', 1 );
    remove_action( 'wp_head', 'wp_enqueue_style', 1 );

    add_action( 'wp_footer', 'wp_print_scripts', 5);
    add_action( 'wp_footer', 'wp_enqueue_scripts', 5);
    add_action( 'wp_footer', 'wp_enqueue_style', 5);
    add_action( 'wp_footer', 'wp_print_head_scripts', 5);

	wp_deregister_script('jquery');
	wp_register_script('jquery', "//cdnjs.cloudflare.com/ajax/libs/jquery/3.6.0/jquery.min.js", false, '3.6.0');
	wp_enqueue_script('jquery');	
	
    wp_enqueue_script( 'popper', '//cdnjs.cloudflare.com/ajax/libs/popper.js/2.11.5/umd/popper.min.js', array( 'jquery' ), '2.11.5', true );
	wp_enqueue_script( 'bootstrap', '//cdnjs.cloudflare.com/ajax/libs/twitter-bootstrap/5.2.0-beta1/js/bootstrap.bundle.min.js', array( 'jquery' ), '5.2.0-beta1', true );
	wp_enqueue_script( 'imagesloaded', '//cdnjs.cloudflare.com/ajax/libs/jquery.imagesloaded/4.1.4/imagesloaded.pkgd.min.js', array( 'jquery' ), '4.1.4', true );	
	wp_enqueue_script( 'OwlCarousel2', '//cdnjs.cloudflare.com/ajax/libs/OwlCarousel2/2.3.4/owl.carousel.min.js', array( 'jquery' ), '2.3.4', true );	
	wp_enqueue_script( 'isotope', '//cdnjs.cloudflare.com/ajax/libs/jquery.isotope/3.0.6/isotope.pkgd.min.js', array( 'jquery' ), '3.0.6', true );
	wp_enqueue_script( 'jquery-ui', '//cdnjs.cloudflare.com/ajax/libs/jqueryui/1.12.1/jquery-ui.min.js', array( 'jquery' ), '1.12.1', true );
	wp_enqueue_script( 'fancybox', '//cdnjs.cloudflare.com/ajax/libs/fancybox/3.5.7/jquery.fancybox.min.js', array( 'jquery' ), '3.5.7', true );
	wp_enqueue_script( 'svg-injector', '//cdnjs.cloudflare.com/ajax/libs/svg-injector/1.1.3/svg-injector.min.js', array( 'jquery' ), '1.1.3', true );
	wp_enqueue_script( 'smooth-scroll', '//cdnjs.cloudflare.com/ajax/libs/smooth-scroll/16.1.3/smooth-scroll.min.js', array( 'jquery' ), '16.1.3', true );
	wp_enqueue_script( 'mobile-detect', '//cdnjs.cloudflare.com/ajax/libs/mobile-detect/1.4.5/mobile-detect.min.js', array( 'jquery' ), '1.4.5', true );
	wp_enqueue_script( 'rut', get_template_directory_uri() .'/js/jquery.rut.min.js', array( 'jquery' ), false, true );
	wp_enqueue_script( 'jquery-form', '//cdnjs.cloudflare.com/ajax/libs/jquery.form/4.3.0/jquery.form.min.js', array( 'jquery' ), '4.3.0', true );
	
	wp_enqueue_script( 'jsPlumb', '//cdnjs.cloudflare.com/ajax/libs/jsPlumb/2.15.6/js/jsplumb.min.js', array( 'jquery' ), '2.15.6', true );
	//wp_enqueue_script( 'html2canvas', '//cdnjs.cloudflare.com/ajax/libs/html2canvas/1.4.1/html2canvas.min.js', array( 'jquery' ), '1.4.1', true );
	//wp_enqueue_script( 'jspdf-umd', '//cdnjs.cloudflare.com/ajax/libs/jspdf/2.5.1/jspdf.umd.min.js', array( 'jquery' ), '2.5.1', true );
	//wp_enqueue_script( 'jspdf-es', '//cdnjs.cloudflare.com/ajax/libs/jspdf/2.5.1/jspdf.es.min.js', array( 'jquery' ), '2.5.1', true );
	//wp_enqueue_script( 'jspdf-polyfills', '//cdnjs.cloudflare.com/ajax/libs/jspdf/2.5.1/polyfills.umd.min.js', array( 'jquery' ), '2.5.1', true );
	//wp_enqueue_script( 'FileSaver', '//cdnjs.cloudflare.com/ajax/libs/FileSaver.js/2.0.5/FileSaver.min.js', array( 'jquery' ), '2.0.5', true );

	wp_enqueue_script( 'custom', get_template_directory_uri() .'/js/actions.js', array( 'jquery' ), false, true );
	wp_localize_script( 'custom', 'ajax_var', array(
        'url_ajax'    	=> admin_url( 'admin-ajax.php' ),
		'url_theme'		=> get_template_directory_uri(),
		'zoom_inicial'	=> get_field('zoom_init','option'),
		'zoom_completo'	=> get_field('zoom_completo','option')
    ) );
}
add_action( 'wp_enqueue_scripts', 'theme_enqueue' );
function prefix_add_footer_styles() {
	wp_enqueue_style( 'bootstrap-style', '//cdnjs.cloudflare.com/ajax/libs/twitter-bootstrap/5.2.0-beta1/css/bootstrap.min.css', array( ), '5.2.0-beta1' );
	wp_enqueue_style( 'fontawesome-style', '//cdnjs.cloudflare.com/ajax/libs/font-awesome/6.2.0/css/all.min.css', array( ), '6.2.0' );
	wp_enqueue_style( 'jquery-ui-style', '//cdnjs.cloudflare.com/ajax/libs/jqueryui/1.12.1/jquery-ui.structure.min.css', array( ), '1.12.1' );
	wp_enqueue_style( 'google-fonts', '//fonts.googleapis.com/css2?family=Roboto:ital,wght@0,100;0,300;0,400;0,500;0,700;0,900;1,100;1,300;1,400;1,500;1,700;1,900&display=swap', array( ), null );
	wp_enqueue_style( 'fancybox-style', '//cdnjs.cloudflare.com/ajax/libs/fancybox/3.5.7/jquery.fancybox.css', array( ), '3.5.7' );
	wp_enqueue_style( 'animate-style', '//cdnjs.cloudflare.com/ajax/libs/animate.css/4.1.1/animate.min.css', array( ), '4.1.1' );
	
	wp_enqueue_style( 'jsPlumb-style', '//cdnjs.cloudflare.com/ajax/libs/jsPlumb/2.15.6/css/jsplumbtoolkit-defaults.css', array( ), '2.15.6' );
	//wp_enqueue_script( 'svg-connect', get_template_directory_uri() .'/js/jquery.html-svg-connect.js', array( 'jquery' ), false, true );
	wp_enqueue_style( 'general-style', get_template_directory_uri() .'/css/custom.css', array( ) );
}
add_action( 'get_footer', 'prefix_add_footer_styles' );

function admin_style() {
	wp_enqueue_style( 'fontawesome-style', '//cdnjs.cloudflare.com/ajax/libs/font-awesome/6.1.1/css/all.min.css', array( ), '6.1.1' );
	wp_enqueue_style('admin-styles', get_stylesheet_directory_uri().'/css/admin.css');
}
add_action('admin_enqueue_scripts', 'admin_style');