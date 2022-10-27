<?php
//header('Content-Type: text/html; charset=iso-8859-1');
global $detect;
$logo = get_field('logo','option');
if(empty($logo)){
    $custom_logo_id = get_theme_mod( 'custom_logo' );
    $logo = wp_get_attachment_image_src( $custom_logo_id , 'full' );
    $logo = $logo[0];
}
//--
$classes="";
if(isset($_GET['save'])) $classes="pdf-body";
?>
<!DOCTYPE html>
<html <?php language_attributes(); ?> class="no-js">
<head>
	<meta charset="<?php bloginfo( 'charset' ); ?>">
	<link rel="profile" href="http://gmpg.org/xfn/11">
	<link rel="pingback" href="<?php bloginfo( 'pingback_url' ); ?>">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <title><?php bloginfo(); ?></title>
    
    <meta name="description" content="<?php bloginfo('description'); ?>">
    <meta name="googlebot" content="index" />
    <meta name="google" content="nositelinkssearchbox" />
    
    <meta name="viewport" content="width=device-width, initial-scale=1">   
    <meta name="msapplication-TileColor" content="#EB0029">
    <meta name="theme-color" content="#EB0029">
    <?php wp_head(); ?>
    <?php the_field('codigo_header','option'); ?>
</head>

<body <?php body_class($classes); ?> style="<?PHP echo isset($_GET['save'])?'padding: 0; margin: 0':''; ?>">
	<?php the_field('codigo_body','option'); ?>
    <div id="load" style="position: fixed; width: 100%; height: 100%; background-color: rgba(0,0,0,0.96); z-index: 99999; text-align: center; top: 0; left: 0; right: 0;" class="d-flex justify-content-center align-items-center">
            <i class="fa-solid fa-group-arrows-rotate fa-spin" style="color: #fff;font-size: 4rem;display: block;margin-top: 1rem;opacity: .5;"></i>
    </div>
	<?php	
	if ( post_password_required( $post ) && !isset($_GET['save']) ){
    	echo get_the_password_form();
		get_footer();
		wp_die();
	}
?>
<section class="nav fixed-top">
    <nav id="menu" class="menu navbar navbar-expand-lg container-fluid flex-wrap justify-content-between align-items-end px-4">
        <a href="<?php echo home_url( '/' ); ?>" class="navbar-brand" title="Volver al Home"><img src="<?=$logo?>" style="height: 24px" class="img-fluid"></a>
		<h5><a href="<?php echo home_url( '/' ); ?>"><?php bloginfo('description'); ?></a></h5>
        <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarSupportedContent">
          <span class="navbar-toggler-icon"></span>
        </button>
        <div class="collapse navbar-collapse" id="navbarSupportedContent">
        <?php
        wp_nav_menu([
            'theme_location'  => 'general',
            'container'       => '',
            'container_id'    => '',
            'container_class' => '',
            'menu_id'         => false,
            'menu_class'      => 'navbar-nav mb-2 mb-lg-0',
            'depth'           => 2,
            'fallback_cb'     => 'bs4navwalker::fallback',
            'walker'          => new bs4navwalker()
        ]);
		$actual_link = home_url($wp->request);
		$link_out = home_url('/busqueda');
		
		$current = ($actual_link == $link_out) ? 'current_page' : '';
        ?>
        <div class="ms-auto d-flex">
			<div class="nav-item dropdown">
				<button class="btn btn-gris dropdown-toggle" role="button" data-bs-toggle="dropdown" aria-expanded="false">
					<i class="fa-solid fa-city"></i> Links Sociedades
				</button>
				<ul class="dropdown-menu text-center" style="padding: 0.5rem;">
					<?php $chl_menu_pages=pagina_hijo(45);
					$id_pags=array();
					//var_dump($chl_menu_pages);
					foreach($chl_menu_pages as $chl_menu_page){
						array_push($id_pags,$chl_menu_page->ID);
					}
					//--
					foreach($chl_menu_pages as $chl_menu_page):
						$arrayIds = $id_pags;
						$key = array_search($chl_menu_page->ID, $id_pags);
						unset($arrayIds[$key]);
					?>
					<a class="btn btn-gris mb-1" href="<?php echo get_site_url(); ?>/?nemp=<?php echo implode(",",$arrayIds) ?>">
						<i class="fa-solid fa-building-circle-arrow-right"></i> Ver <strong><?php the_field('nombre_de_la_sociedad',$chl_menu_page->ID); ?></strong>
					</a>
					<?php endforeach; ?>
				</ul>
				<!--
				<ul class="dropdown-menu text-center" style="padding: 0.5rem;">
					<button class="btn btn-gris current_page mb-1 screenshoot" data-id="" data-bs-placement="bottom" data-bs-toggle="tooltip" title="Descargar PDF Organigrama">
						<i class="fa-solid fa-spinner fa-spin load"></i><i class="fa-solid fa-download ok"></i> PDF Completo
					</button>
					<?php $chl_menu_pages=pagina_hijo(45);
					$id_pags=array();
					//var_dump($chl_menu_pages);
					foreach($chl_menu_pages as $chl_menu_page){
						array_push($id_pags,$chl_menu_page->ID);
					}
					//--
					foreach($chl_menu_pages as $chl_menu_page):
						$arrayIds = $id_pags;
						$key = array_search($chl_menu_page->ID, $id_pags);
						unset($arrayIds[$key]);
					?>
					<button class="btn btn-gris mb-1 screenshoot" data-id="<?php echo implode(",",$arrayIds) ?>" data-bs-placement="bottom" data-bs-toggle="tooltip" title="Descargar PDF Organigrama">
						<i class="fa-solid fa-spinner fa-spin load"></i><i class="fa-solid fa-download ok"></i> PDF <?php the_field('nombre_de_la_sociedad',$chl_menu_page->ID); ?>
					</button>
					<?php endforeach; ?>
				</ul>
				-->
			</div>
			<button class="btn btn-gris mb-1 screenshoot" data-id="" data-bs-placement="bottom" data-bs-toggle="tooltip" title="Descargar PDF Organigrama">
				<i class="fa-solid fa-spinner fa-spin load"></i><i class="fa-solid fa-download ok"></i> Descargar PDF
			</button>				
            <a href="<?php echo home_url('/busqueda') ?>" class="btn btn-gris <?=$current;?>" type="button" data-bs-placement="bottom" data-bs-toggle="tooltip" title="Abrir Buscador">
				<i class="fa-solid fa-magnifying-glass"></i> Buscar
			</a>
        </div>
        </div>
    </nav>
</section>