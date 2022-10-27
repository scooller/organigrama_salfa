<?php
add_action( 'init', 'create_post_type' );
function create_post_type() {
	//--
	register_post_type( 'empresa',
	    array(
	    	'labels' => array(
		        'name' => 'Empresas',
		        'singular_name' => 'Empresa',
		        'add_new' => 'Agregar Empresa Madre'
		    ),
			'menu_icon' => get_template_directory_uri() ."/img/building-columns-solid.svg",
			'menu_position' => 4,
			'public' => true,
			'has_archive' => false,
			'supports' => array (
				'title',
				'author',
				'page-attributes',
				'custom-fields'
			)
	    )
	);
	//--
}
function wpse_category_set_post_types( $query ){
    if( $query->is_category() && $query->is_main_query() ){
        $query->set( 'post_type', array( 'post', 'planta' ) );
    }
}
add_action( 'pre_get_posts', 'wpse_category_set_post_types' );
//listener creacion empresa
add_action( 'save_post_empresa', 'set_post_new_empresa', 10,3 );
function set_post_new_empresa( $post_id, $post, $update ) {
    // Only want to set if this is a new post!
    /*
    var_dump($update);      
    var_dump($post);
    */
    if ( !$update ){
    	return;
    }
    if ( 'publish' !== $post->post_status) {
        return;
    }
    //crear post nuevo
    $titulo = get_the_title( $post_id );
    $new_name = sanitize_key('emp-'.$post->post_name);
    $new_name = substr($new_name,0,20);
    /*
    echo 'NAME:';
    var_dump($new_name);
    echo '<hr>';*/
    $return = register_post_type( $new_name,
	    array(
	    	'labels' => array(
		        'name' => 'Emp. '.$titulo,
		        'singular_name' => 'Emp. '.$titulo,
		        'add_new' => 'Agregar SubEmpresa'
		    ),
		    'description' => 'Empresas pertenecientes a '.$titulo,
			'menu_icon' => get_template_directory_uri() ."/img/building-circle-xmark-solid.svg",
			'menu_position' => 5,
			'public' => true,
			'has_archive' => true,
			'show_ui' => true,
			'show_in_menu' => true,
			'supports' => array (
				'title',
				'author',
				'page-attributes',
				'custom-fields'
			)
	    )
	);
	if( is_wp_error( $return ) ) {
	    echo $return->get_error_message();
	}else{
		flush_rewrite_rules();
		//var_dump($return);
	}
}