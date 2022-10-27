<?php
function my_acf_init() {
	$key = get_field('google_map','option');
	//acf_update_setting('google_api_key', $key);
}
add_action('acf/init', 'my_acf_init');

function acf_readonly_field( $field ) {
	$field['disabled'] = 1;
	return $field;
}
add_filter('acf/load_field/key=field_6359955d65b93', 'acf_readonly_field');
add_filter('acf/load_field/key=field_628187626bfe4', 'acf_readonly_field');
add_filter('acf/load_field/key=field_6281874e6bfe2', 'acf_readonly_field');

add_filter('acf/load_field/key=field_6359bb3baa342', 'acf_readonly_field');
add_filter('acf/load_field/key=field_6359bb4eaa343', 'acf_readonly_field');
add_filter('acf/load_field/key=field_6359bb76aa344', 'acf_readonly_field');
add_filter('acf/load_field/key=field_6359bb88aa345', 'acf_readonly_field');
add_filter('acf/load_field/key=field_6359bba4aa346', 'acf_readonly_field');

add_filter('acf/load_field/key=field_6359bba4aa346', 'field_635a8a253ead8');
add_filter('acf/load_field/key=field_6359bba4aa346', 'field_635a8a393ead9');
add_filter('acf/load_field/key=field_6359bba4aa346', 'field_635a8a453eada');
add_filter('acf/load_field/key=field_6359bba4aa346', 'field_635a8a5f3eadc');
add_filter('acf/load_field/key=field_6359bba4aa346', 'field_635a8a513eadb');
//--
function acf_load_simbologia_choices( $field ) {    
    // reset choices
    $field['choices'] = array();
    // if has rows
    if( have_rows('simbologia', 'option') ) {
        // while has rows
        while( have_rows('simbologia', 'option') ) {
            // instantiate row
            the_row();
            // vars
            $value = get_sub_field('nombre');
            $label = get_sub_field('nombre');
            // append to choices
            $field['choices'][ $value ] = $label;
        }
    }
    // return the field
    return $field;
}
add_filter('acf/load_field/key=field_627448b40931c', 'acf_load_simbologia_choices');
//--
function acf_load_paises_choices( $field ) {    
    // reset choices
    $field['choices'] = array();
    // if has rows
    if( have_rows('paises', 'option') ) {
        // while has rows
        while( have_rows('paises', 'option') ) {
            // instantiate row
            the_row();
            // vars
            $value = get_sub_field('nombre');
            $label = get_sub_field('nombre');
            // append to choices
            $field['choices'][ $value ] = $label;
        }
    }
    // return the field
    return $field;
}
add_filter('acf/load_field/key=field_628187026bfdc', 'acf_load_paises_choices');
//--
function acf_load_udnegocio_choices( $field ) {    
    // reset choices
    $field['choices'] = array();
    // if has rows
    if( have_rows('unidades_de_negocio_sel', 'option') ) {
        // while has rows
        while( have_rows('unidades_de_negocio_sel', 'option') ) {
            // instantiate row
            the_row();
            // vars
            $value = get_sub_field('nombre');
            $label = get_sub_field('nombre');
            // append to choices
            $field['choices'][ $value ] = $label;
        }
    }
    // return the field
    return $field;
}
add_filter('acf/load_field/key=field_628186a96bfd5', 'acf_load_udnegocio_choices');
//--
function acf_load_udnegocio_choices_legacy( $field ) {    
    // reset choices
    $field['choices'] = array();
    // if has rows
    $unidades = get_field('unidades_de_negocio','option');
	 if( get_field('unidades_de_negocio', 'option') ) {
		foreach( $unidades as $postID ){
            // vars
            $value = $postID;
            $label = get_field('nombre_de_la_sociedad',$postID);
            // append to choices
            $field['choices'][ $value ] = $label;
        }
	 }
    // return the field
    return $field;
}
//add_filter('acf/load_field/key=field_628186a96bfd5', 'acf_load_udnegocio_choices_legacy');