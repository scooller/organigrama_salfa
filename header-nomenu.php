<!DOCTYPE html>
<html <?php language_attributes(); ?> class="no-js">
<head>
	<meta charset="<?php bloginfo( 'charset' ); ?>">
	<link rel="profile" href="http://gmpg.org/xfn/11">
	<link rel="pingback" href="<?php bloginfo( 'pingback_url' ); ?>">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <title><?php bloginfo(); ?> <?php bloginfo('description'); ?></title>
    
    <meta name="description" content="<?php bloginfo('description'); ?>">
    <meta name="googlebot" content="no-index" />
    <meta name="google" content="nositelinkssearchbox" />
    
    <meta name="viewport" content="width=device-width, initial-scale=1">  
    <?php wp_head(); ?>
    <?php the_field('codigo_header','option'); ?>
</head>

<body <?php body_class(); ?>>