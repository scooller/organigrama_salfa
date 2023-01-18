<?php
//header('Access-Control-Allow-Origin: *'); 
global $lang,$detect;
add_action( 'after_setup_theme', 'register_my_menu' );
add_action('init', 'cyb_session_start', 1);
function cyb_session_start() {
    if( ! session_id() ) {
        session_start();
		setcookie('wpb_pass_ok', false, time()+3600);
		if(!isset($_SESSION['wpb_pass_ok'])){
			pass_cookie();
		}
    }
}
function register_my_menu() {  
	register_nav_menu( 'general', __( 'Menu Principal' ) );
	add_theme_support( 'post-thumbnails' );
	//add_theme_support( 'html5' );
	add_theme_support( 'custom-logo' );
}
$lang = substr($_SERVER['HTTP_ACCEPT_LANGUAGE'], 0, 2);

require_once(TEMPLATEPATH.'/functions/enqueue-scripts.php');
require_once(TEMPLATEPATH.'/functions/cleanup.php');
require_once(TEMPLATEPATH.'/functions/bs4navwalker.php');
//require_once(TEMPLATEPATH.'/functions/custom-post.php');
require_once(TEMPLATEPATH.'/functions/acf-pro.php');
require_once(TEMPLATEPATH.'/functions/Mobile_Detect.php');
require_once(TEMPLATEPATH.'/functions/vendor/autoload.php');
use Ilovepdf\Ilovepdf;

$detect = new Mobile_Detect;
//--
if( function_exists('acf_add_options_page') ) {	
	acf_add_options_page(array(
		'page_title' 	=> __( 'Configuración del Sitio' ),
		'menu_title'	=> __( 'Configuración del Sitio' ),
		'menu_slug' 	=> 'theme-general-settings',
		'icon_url'		=> get_template_directory_uri() .'/img/gears-solid.svg'
	));
}
/**
 * Add a new dashboard widget.
 */
function wpdocs_add_dashboard_widgets() {
	wp_add_dashboard_widget( 'dashboard_widget_pdf', 'Historial malla', 'dashboard_widget_pdf' );
	wp_add_dashboard_widget( 'dashboard_widget_modificaciones', 'Reporte de Modificaciones', 'dashboard_widget_modificaciones' );
}
add_action( 'wp_dashboard_setup', 'wpdocs_add_dashboard_widgets' );

/**
 * Output the contents of the dashboard widget
 */
function dashboard_widget_pdf( $post, $callback_args ) {
	ob_start();
	?>
	<a href="<?php echo esc_url( get_permalink( 2476 ) ); ?>" target="_blank" class="button button-primary">Ver Historial malla PDF</a>
	<?php
	echo ob_get_clean();
}
function dashboard_widget_modificaciones( $post, $callback_args ) {
	ob_start();
	?>
	<a href="<?php echo esc_url( get_permalink( 2457 ) ); ?>" target="_blank" class="button button-primary">Ver Reporte de Modificaciones</a>
	<?php
	echo ob_get_clean();
}


function noImage($cont){	
	return preg_replace('/<img[^>]+>/i', '', $cont);
}
/*this function allows users to use the first image in their post as their thumbnail*/
function first_image($content = "") {
	global $post, $posts;
	$img = '';
	ob_start();
	ob_end_clean();
	if(empty($content)){
		$content=$post->post_content;
	}
	$output = preg_match_all('/<img.+src=[\'"]([^\'"]+)[\'"].*>/i', $content, $matches);
	$img = $matches [1] [0];

	return trim($img);
} 

/*
SVG FIX
*/
function fix_svg_thumb_display() {
  echo '<style>
    td.media-icon img[src$=".svg"], img[src$=".svg"].attachment-post-thumbnail { 
      width: 100% !important; 
      height: auto !important; 
    }
  </style>';
}
add_action('admin_head', 'fix_svg_thumb_display');
function cc_mime_types($mimes) {
  $mimes['svg'] = 'image/svg+xml';
  $mimes['mp4'] = 'video/mp4';
  $mimes['m4v'] = 'video/mp4';
  $mimes['webm'] = 'video/webm';
  $mimes['ogv'] = 'video/ogg';
  return $mimes;
}
add_filter('upload_mimes', 'cc_mime_types');
show_admin_bar( false );

function normalizeNumber($number){
    $number = str_replace(".", "", $number);
    $number = str_replace(",", ".", $number);
    return floatval($number);
}
//--
add_filter( 'the_password_form', 'customize_the_password_form', 10, 3 );
function customize_the_password_form( $default_form) {
	$custom_logo_id = get_theme_mod( 'custom_logo' );
    $logo = wp_get_attachment_image_src( $custom_logo_id , 'full' );
    $logo = $logo[0];
	$post_id=get_the_ID();
	ob_start();
	?>
	<form class="protected-post-form post-password-form" action="<?php echo get_option( 'siteurl' ); ?>/wp-login.php?action=postpass" method="post">
		<div class="stm_page_bc container">
			<div class="page-splash">
				<div class="container">	
					
					<div class="card mx-auto box-shadow-n22" style="width: 20rem;">
						<img class="card-img-top mx-auto mt-4 w-50" src="<?=$logo;?>" alt="">
						<div class="card-body">
							<h6 class="card-title"><?php bloginfo('description'); ?></h6>
							<small class="card-subtitle mb-2 d-block text-muted"><?php _e( "El contenido está protegido por clave. Para verlo por favor ingrese clave" ) ?></small>
							<div class="form-floating">
								<input name="post_password" id="floatingPassword" type="password" class="form-control" placeholder="Password" required autofocus />
								<label for="floatingPassword">Contraseña</label>
							</div>
							<input type="hidden" name="post_id" value='<?=$post_id;?>' />
							<input type="submit" name="Submit" class="btn btn-gris mt-3" value="Ingresar" />
						</div>
					</div>					
					
				</div>
			</div>
		</div>
	</form>
	<?php
		return ob_get_clean();
	}
function login_customlogo() {
  $custom_logo_id = get_theme_mod( 'custom_logo' );
  $logo_site = wp_get_attachment_image_src( $custom_logo_id , 'full' );
?> 
<style type="text/css">
.login #backtoblog a, .login #nav a {
  color: #fff!important;
}
.language-switcher label .dashicons{
  color: #fff;
}
body.login{
  background-color: #3a3d3b;
}
body.login div#login h1 a {
  background-image: url(<?=$logo_site[0]?>);
  padding-bottom: 0px;
  width: 100%;
  background-size: auto;
}
.wp-core-ui .button {
  color: #b2282f!important;
  border-color: #b2282f!important;
}
.wp-core-ui .button-primary{
  color: #fff!important;
  background-color: #b2282f!important;
}
.login #login_error, .login .message, .login .success {
  border-left: 4px solid #b2282f!important;
}
</style>
<?php 
} add_action( 'login_enqueue_scripts', 'login_customlogo' );
// Function to change "posts" to "news" in the admin side menu
function change_page_menu_label() {
    global $menu;
    global $submenu;
    //var_dump($submenu);
    $menu[20][0] = 'Empresas';
    $submenu['edit.php?post_type=page'][5][0] = 'Empresas';
    $submenu['edit.php?post_type=page'][10][0] = 'Nueva Empresa';
    echo '';
}
add_action( 'admin_menu', 'change_page_menu_label' );
// Function to change post object labels to "news"
function change_page_object_label() {
    global $wp_post_types;
    $labels = &$wp_post_types['page']->labels;
    $labels->name = 'Empresas';
    $labels->singular_name = 'Empresa';
    $labels->add_new = 'Nueva';
    $labels->add_new_item = 'Agregar Nueva Empresa';
    $labels->edit_item = 'Editar Empresa';
    $labels->new_item = 'Nueva Empresa';
    $labels->view_item = 'Ver Empresa';
    $labels->search_items = 'Buscar Empresas';
    $labels->not_found = 'No se encontraron Empresas';
    $labels->not_found_in_trash = 'No hay Empresas en la papelera';
}
add_action( 'init', 'change_page_object_label' );
//parent page
function pagina_hijo($id){
  $args = array(
    'post_type'           => 'page',
    'posts_per_page'      => -1,
    'post_status'         => 'publish',
    'order'               => 'ASC',
    'orderby'             => 'menu_order',
    'post_parent'         => $id
  );
  $wp_query = new WP_Query();
  $all_wp_pages = $wp_query->query($args);
  return $all_wp_pages;
  wp_reset_postdata();
}
function have_child($id){
  $args = array(
    'post_type'           => 'page',
    'posts_per_page'      => 1,
    'post_status'         => 'publish',
    'post_parent'         => $id
  );
  $query = new WP_Query( $args );
  if($query->have_posts()){
    return true;
  }else{
    return false;
  }
  wp_reset_postdata();
}
function sub_cajas($id,$n=1){
  $sub_chl_pages=pagina_hijo($id); 
  if(count($sub_chl_pages)):
    $n++;
	$marg_l=4;//margen izquierdo hijos
	
	$special_col='';
	if(get_field('col_s',$id)){//hijos a la derecha
		$marg_l=3;
		$special_col='special_col';
		if( get_field('col_blank',$id) ){
			$marg_l=30*get_field('col_blank',$id);
		}
	}
  ?>
  <div class="sub-cajas d-flex align-items-start <?=$special_col?>" id="scaja-<?=$id;?>" style="margin-left: <?=$marg_l?>rem"> 
    <div class="cont">
      <?php foreach($sub_chl_pages as $sub_chl_page): 
        $total_porc=total_porc($sub_chl_page->ID);
        $simb=get_field('simbologia',$sub_chl_page->ID);
        //$ley=get_field('leyenda',$sub_chl_page->ID);
        /*$b_color=color_simbolo($simb);
        $l_color=color_leyenda($ley);*/
	
		$b_color=color_simbolo($simb);
        //link popup
        $popup_id=get_field('pagina_popup','option');
        //--
        $btnlnkcss='d-none';
		$box_css='n-child';
		$brake_col=get_field('c_col',$sub_chl_page->ID);		
        $hchild=have_child($sub_chl_page->ID);
	
		$anchors=get_field('anchors',$id);
		$faces=array();
		if( have_rows('faces',$id) ){
			while( have_rows('faces',$id) ) { the_row();
				array_push($faces,get_sub_field('cara'));
			}
		}
	
		$padreId=$id;
		//if($padreId == 120) $padreId=61;//tontera de la caja sola
		$endpoints_class=get_field('endpoints_class',$id);
		if(get_field('orientacion_flecha',$id)){
			$endpoints_class.=' '.get_field('orientacion_flecha',$id);
		}
		//css
		$margenes = '0 0 3rem 1rem';
		$margens = get_field('margen',$sub_chl_page->ID);
		if( $margens ){
			$margenes = $margens['margen-top'].'rem '.$margens['margen-right'].'rem '.$margens['margen-bottom'].'rem '.$margens['margen-left'].'rem';			
		}
		//--
		if($hchild){
			$box_css='s-child';
			$btnlnkcss='';
		}
        if($brake_col):        	
        	//if($n<4):
      ?>
      </div><div class="cont">
      <?php endif; //endif; ?>
      <div class="caja sub-c<?=$n;?>" id="emp-<?=$sub_chl_page->ID;?>">
        <div class="box box-shadow-n38 position-relative <?=$box_css?> color-<?php echo sanitize_title($simb); ?>" id="box-child-<?=$sub_chl_page->ID;?>" data-boxpadre="<?=$padreId;?>" data-colorlinea="<?=$b_color;?>" data-level="<?=$n?>" data-unica="<? the_field('col_unq',$id); ?>" data-special="<? the_field('col_s',$id); ?>" data-faces="<?php echo implode(",", $faces); ?>" data-anchors="<?php echo $anchors['inicio'].','.$anchors['fin']; ?>" data-endpoints-class="<?=$endpoints_class; ?>" data-texto="<?php the_field('nombre_de_la_sociedad',$id); ?>" style="margin: <?=$margenes?>">
          <!--<div class="linea-leyenda position-absolute" style="background-color: <?=$l_color;?>"></div>-->
          <a href="javascript:void(0);" onClick="minimizeChart(this,<?=$sub_chl_page->ID;?>);" class="cerrar-link <?=$btnlnkcss;?>" data-bs-toggle="tooltip" title="Cerrar Asociados">
            <i class="fa-solid fa-circle-minus"></i>
            <i class="fa-solid fa-circle-plus d-none"></i>
          </a>
          
          <div class="row">
            <div class="col-3 d-flex align-items-start text-start">
              <a data-fancybox data-type="iframe" href="<?php echo esc_url( get_permalink( $popup_id ).'?id='.$sub_chl_page->ID ); ?>" class="link"><strong><?=format_num($total_porc);?>%</strong></a>
            </div>
            <div class="col-9 d-flex align-items-start text-start">
				<?php 
				$empresa_rut = get_field('rut',$sub_chl_page->ID);
				if($empresa_rut == ".")$empresa_rut=""; ?>
              <a data-fancybox data-type="iframe" href="<?php echo esc_url( get_permalink( $popup_id ).'?id='.$sub_chl_page->ID ); ?>" class="link"><strong class="title"><?php the_field('nombre_de_la_sociedad',$sub_chl_page->ID); ?><br><span><?php echo $empresa_rut; ?></span></strong></a>
            </div>
            <?php
            if( have_rows('empresas_asociadas',$sub_chl_page->ID) ): while( have_rows('empresas_asociadas',$sub_chl_page->ID) ) : the_row();
				$sub_empresa_id=get_sub_field('empresa');
				$sub_empresa=get_field('nombre_de_la_sociedad',$sub_empresa_id);
				$sub_empresa_rut=get_field('rut',$sub_empresa_id);
				if(get_sub_field('t_emp')){
					$sub_empresa=get_sub_field('empresa_externa');
					$sub_empresa_rut="";
				}
				if($sub_empresa_rut == ".")$sub_empresa_rut="";
            ?>
            <div class="col-3 sub-empr text-start">
              <a data-fancybox data-type="iframe" href="<?php echo esc_url( get_permalink( $popup_id ).'?id='.$sub_chl_page->ID ); ?>" class="link"><span><?php echo format_num(get_sub_field('porcentaje')); ?>%</span></a>
            </div>
            <div class="col-9 sub-empr text-start">				
				<a data-fancybox data-type="iframe" href="<?php echo esc_url( get_permalink( $popup_id ).'?id='.$sub_chl_page->ID ); ?>" class="link"><span class="sub-title"><?php echo $sub_empresa; ?><br><?php echo $sub_empresa_rut; ?></span></a>
				<?php if(!get_sub_field('t_emp')): ?>
				<button type="button" class="buscar" onClick="buscarEmpresa(<?=$sub_empresa_id?>,<?=$sub_chl_page->ID;?>)" data-bs-toggle="tooltip" class="list-group-item list-group-item-action" data-bs-placement="left" title="Buscar Empresa"><i class="fa-solid fa-binoculars"></i></button>
				<?php endif; ?>
            </div>
            <?php endwhile; endif; ?>
          </div>
			
        </div>
        <!-- sub-cajas -->
        <?php sub_cajas($sub_chl_page->ID,$n) ?>
      </div>
      <?php endforeach; ?>
    </div>
  </div>
  <!-- // -->
  <?php 
  else:
    return false;
  endif;
}
function deleteRows(string $acfRepeaterFieldKey, int $postID) {
	$rr=false;
	reset_rows();
	$fieldValue = get_field($acfRepeaterFieldKey, $postID);
	if (is_array($fieldValue)){
		$remainingRows = count($fieldValue);
		while (have_rows($acfRepeaterFieldKey, $postID)) :
			the_row();
			delete_row($acfRepeaterFieldKey, $remainingRows--, $postID);
			$rr=true;
		endwhile;
	}
	return $rr;
}
add_shortcode( 'delporc', 'borrar_porc' );//[delporc]
function borrar_porc(){
	$args = array(
		'post_type'           => 'page',
		'posts_per_page'      => -1,
		'post_status'         => 'publish',
		'order'               => 'ASC',
		'orderby'             => 'menu_order'
  	);
	$wp_query = new WP_Query($args);
	$pt=0;
	$borrados=0;
	if ( $wp_query->have_posts() ) {
		// Start looping over the query results. 
		while ( $wp_query->have_posts() ) {
			$wp_query->the_post();
			$id = get_the_ID();
			$dr=deleteRows('empresas_asociadas',$id);
			if($dr) $borrados++;
			$pt++;
		}
	}
	echo '<div class="alert alert-danger mt-3 d-flex" id="aviso-msj" role="alert">';
	echo '<i class="fa-solid fa-skull-crossbones flex-shrink-0 me-2"></i><div>';
	echo "<p>Total paginas/empresas es de <strong><i class=\"fa-solid fa-city\"></i> {$pt}</strong></p>";
	echo "<p>Total de empresas minoritarias Borrados es de <strong><i class=\"fa-solid fa-building-circle-xmark\"></i> {$borrados}</strong></p>";
	echo '</div></div>';
	wp_reset_postdata();
}
function color_simbolo($simbolo){
	if($simbolo=='Asociados') $simbolo="Asociaciones";
  $color = false;
  if( have_rows('simbologia','option') ){ while( have_rows('simbologia','option') ){ the_row();
    //echo $simbolo.'=='.get_sub_field('nombre').'<br>';
    if( trim( $simbolo ) == trim( get_sub_field('nombre') ) ){
      $color = get_sub_field('color_borde');
      break;
    }
  }}
  reset_rows();
  return $color;
}
function pass_cookie($val=false){
	$_SESSION['wpb_pass_ok']=$val;
	if($_GET['dev']) var_dump($_SESSION);
}
/*
function color_leyenda($leyenda){
  $color = false;
  if( have_rows('leyendas','option') ){ while( have_rows('leyendas','option') ){ the_row();    
    if( trim( $leyenda ) == trim( get_sub_field('nombre') ) ){
      $color = get_sub_field('color');
      break;
    }
  }}
  reset_rows();
  return $color;
}*/
add_action( 'wp_ajax_nopriv_pass_check', 'passCheck' );
add_action( 'wp_ajax_pass_check', 'passCheck' );
function passCheck(){
	if(isset($_POST['pass'])){
		if($_POST['pass']==get_field('contrasena_paginas','option')){
			pass_cookie(true);
			echo 'Ok';
		}else{
			echo 'Contraseña Erronea';
		}
	}
	wp_die();
}
add_action( 'wp_ajax_nopriv_save-pdf', 'savePDF' );
add_action( 'wp_ajax_save-pdf', 'savePDF' );
function savePDF(){
	$nemp=$_POST['nemp'];
	$PUBLIC_KEY=get_field('PUBLIC_KEY_ILOVEPDF','option');
	$SECRET_KEY=get_field('SECRET_KEY_ILOVEPDF','option');
	$msj['mensaje']='No Error';
	$upload_dir = wp_upload_dir();
	try {
		$ilovepdf = new Ilovepdf($PUBLIC_KEY,$SECRET_KEY);
		// Create a new task
		$myTaskHtmlpdf = $ilovepdf->newTask('htmlpdf');
		// Add url to task for process
		$url = home_url();
		if(!empty($_POST['url'])){
			$url = $_POST['url'];
		}		
		$chl_menu_pages=pagina_hijo(45);
		$id_pags=array();
		//var_dump($chl_menu_pages);
		foreach($chl_menu_pages as $chl_menu_page){
			array_push($id_pags,$chl_menu_page->ID);
		}
		//--
		$nomPDF='todos';
		foreach($chl_menu_pages as $chl_menu_page){
			$arrayIds = $id_pags;
			$key = array_search($chl_menu_page->ID, $id_pags);
			unset($arrayIds[$key]);
			$arrayIds=implode(",",$arrayIds);
			if($nemp == $arrayIds){
				$nomPDF=get_the_title($chl_menu_page->ID);
			}
		}
		if($nemp){
			$PageSize = get_field('hoja_pdf_info','option');
			$file = $myTaskHtmlpdf->addUrl($url.'?zoom='.get_field('zoom_pdf','option').'&save=1&nemp='.$nemp);
		}else{
			$PageSize = get_field('hoja_pdf','option');
			$file = $myTaskHtmlpdf->addUrl($url.'?zoom='.get_field('zoom_pdf','option').'&save=1');
		}
		$current_user = wp_get_current_user();
		$fecha = date("d-m-Y_H:i:s");
		$nomFile = $nomPDF.' ('.$current_user->display_name.') ('.$fecha.').pdf';
		$myTaskHtmlpdf->setSinglePage(get_field('pagina_larga','option'));		
		
		$myTaskHtmlpdf->setPageSize($PageSize);
		
		$myTaskHtmlpdf->setViewWidth(get_field('ancho_pdf','option'));
		$myTaskHtmlpdf->setOutputFilename('organigrama_{n}_{date}');

		$myTaskHtmlpdf->execute();
		$splitTask = $myTaskHtmlpdf->next('split');
		$splitTask->setRanges('1');
		// Execute the task
		$splitTask->execute();
		// Download the pdf file
		$splitTask->download($upload_dir['path']);
		$filename=strval($splitTask->result->download_filename);
		//add to media
		//*******************************************************************************		
		rename( $upload_dir['path'].'/'.$filename, $upload_dir['path'].'/'.$nomFile);
		$wp_filetype = wp_check_filetype( $upload_dir['path'].'/'.$nomFile, null );

		$attachment = array(
		  'post_mime_type' => $wp_filetype['type'],
		  'post_title' => sanitize_file_name( $nomFile ),
		  'post_content' => '',
		  'post_status' => 'inherit'
		);

		$attach_id = wp_insert_attachment( $attachment, $upload_dir['path'].'/'.$nomFile );
		require_once(ABSPATH . 'wp-admin/includes/media.php');
		require_once(ABSPATH . 'wp-admin/includes/file.php');
		require_once( ABSPATH . 'wp-admin/includes/image.php' );
		$attach_data = wp_generate_attachment_metadata( $attach_id, $upload_dir['path'].'/'.$nomFile );
		wp_update_attachment_metadata( $attach_id, $attach_data );
		$msj['url']=wp_get_attachment_url( $attach_id );
		//*******************************************************************************
		$val = array(
			'usuario'		=>	'<ul><li><b>ID:<b>'.$current_user->ID.'</li><li><b>Name:</b>'.$current_user->display_name.'</li><li><b>Email:</b>'.$current_user->user_email.'</li></ul>',
			'url_archivo'	=>	$msj['url'],
			'empresa'		=>	$nomPDF,
			'fecha'			=>	date('d/m/Y g:i a')
		);
		add_row('historial_pdf', $val, 'option');
		//unlink($upload_dir['path'].'/'.$filename);//borrar archivo
		//$msj['url']=$upload_dir['path'].'/'.$filename;
	} catch (\Ilovepdf\Exceptions\StartException $e) {
		$msj['mensaje']= "An error occured on start: " . $e->getMessage() . " ";
		// Authentication errors
	} catch (\Ilovepdf\Exceptions\AuthException $e) {
		$msj['mensaje']= "An error occured on auth: " . $e->getMessage() . " ";
		$msj['mensaje'].= implode(', ', $e->getErrors());
		// Uploading files errors
	} catch (\Ilovepdf\Exceptions\UploadException $e) {
		$msj['mensaje']= "An error occured on upload: " . $e->getMessage() . " ";
		$msj['mensaje'].= implode(', ', $e->getErrors());
		// Processing files errors
	} catch (\Ilovepdf\Exceptions\ProcessException $e) {
		$msj['mensaje']= "An error occured on process: " . $e->getMessage() . " ";
		$msj['mensaje'].= implode(', ', $e->getErrors());
		// Downloading files errors
	} catch (\Ilovepdf\Exceptions\DownloadException $e) {
		$msj['mensaje']= "An error occured on process: " . $e->getMessage() . " ";
		$msj['mensaje'].= implode(', ', $e->getErrors());
		// Other errors (as connexion errors and other)
	} catch (\Exception $e) {
		$msj['mensaje']= "An error occurred: " . $e->getMessage();
	}
	wp_die(json_encode($msj));
}
//savePDF();
function format_num($num,$dec=4){
	$num=number_format($num, $dec, ",", ".");
	return $num;
}
function rut( $rut ) {
    return number_format( substr ( $rut, 0 , -1 ) , 0, "", ".") . '-' . substr ( $rut, strlen($rut) -1 , 1 );
}
function total_porc($id){
	$total_porc=100;	
	if( get_field('porcentaje_participacion',$id)){
		return get_field('porcentaje_participacion',$id);
		wp_die();
	}else{
		if( have_rows('empresas_asociadas',$id) ){ while( have_rows('empresas_asociadas',$id) ){ the_row();
			$s_porc=get_sub_field('porcentaje');
			$total_porc-=$s_porc;
		}}
	}
	return $total_porc;
}
//funcion cambios
function check_meta_values($meta_id, $post_id, $meta_key, $meta_value){
	
	$current_user = wp_get_current_user();
	$pos = strpos($meta_key, "modificaciones");
	
	if ($pos === false) {
		if( $meta_key !== "_edit_lock"){
			//echo '<p>Modifica Registro</p>';
			$val = array(
				'usuario'		=>	'<ul><li><b>ID:<b>'.$current_user->ID.'</li><li><b>Name:</b>'.$current_user->display_name.'</li><li><b>Email:</b>'.$current_user->user_email.'</li></ul>',
				'empresa'		=>	'ID:'.$post_id.' Nombre:'.get_field('nombre_de_la_sociedad',$post_id),
				'modificacion'	=>	'<ul><li><b>'.$meta_key.'<b>:'.$meta_value.'</li></ul>',
				'fecha'			=>	date('d/m/Y g:i a')
			);
			add_row('modificaciones', $val, 'option');
		}
	}
}
//add_action( 'updated_post_meta', 'check_meta_values', 10, 4 );
function check_values($post_ID, $post_after, $post_before){
	$current_user = wp_get_current_user();
	$val = array(
		'usuario'		=>	'<ul><li><b>ID:<b>'.$current_user->ID.'</li><li><b>Name:</b>'.$current_user->display_name.'</li><li><b>Email:</b>'.$current_user->user_email.'</li></ul>',
		'fecha'			=>	date('d/m/Y g:i a')
	);
	add_row('registro_modificaciones', $val, $post_ID);
}
//add_action( 'post_updated', 'check_values', 20, 3 );
function btn_pdf($atts, $content = "Descargar PDF"){
	global $wp;
	$actual_link=home_url( $wp->request );
	ob_start();	
?>
	<button class="btn btn-gris mb-1 screenshoot <?=$atts['class']?> <?php echo isset($_GET['save'])?'d-none':''; ?>" data-id="" data-url="<?php echo $actual_link ?>" data-bs-placement="bottom" data-bs-toggle="tooltip" title="Descargar PDF Actual">
		<i class="fa-solid fa-spinner fa-spin load"></i><i class="fa-solid fa-download ok"></i> <?=$content;?>
	</button>
<?php 
	return ob_get_clean();
}
add_shortcode( 'btnpdf', 'btn_pdf' );//[btnpdf]
function buscarRut($rut){
	global $post,$wpdb;
	$rut = $wpdb->_real_escape( $rut );
	$meta_query = array(
		//'relation' => 'OR'
		'relation' => 'AND'
	);
	array_push($meta_query,
		array(
			'key'		=> 'rut',
			'value'		=> $rut,
			'compare'	=> 'LIKE'
		)
	);
	$args = array(
		'posts_per_page'=> -1,
		'post_type'		=> 'page',
		'post_status'   => 'publish',
		'order'         => 'ASC',
		'orderby'       => 'title',
		'meta_query'	=> $meta_query
	);
	$the_query = new WP_Query($args);
	//--
	$return=false;
	//$the_query = new WP_Query( $args );
	if( $the_query->have_posts() ): while ( $the_query->have_posts() ) : $the_query->the_post();
		$return = $post->ID;
	endwhile; endif;
	wp_reset_query();
	//--
	return $return;
}
function format_rut( $rut ) {
	if(empty($rut)) return false;
	$rut = str_replace(".", "", $rut);
	return number_format( substr ( $rut, 0 , -1 ) , 0, "", ".") . '-' . substr ( $rut, strlen($rut) -1 , 1 );
}
//Cargar CSV
add_action( 'wp_ajax_nopriv_carga-csv', 'cargaCSV' );
add_action( 'wp_ajax_carga-csv', 'cargaCSV' );
function cargaCSV(){
	global $wpdb;
	$upload_dir = wp_upload_dir();
	$supported_file = array('csv');
	$ext = strtolower(pathinfo($_FILES['archivo']['name'], PATHINFO_EXTENSION));
	$filename = strtolower($_FILES['archivo']['name']);
	//--
	if (in_array($ext, $supported_file)==false) wp_die('Error al Subir Archivo (Extensión de archivo no válido)');
	//$_FILES[ 'archivo' ][ 'name' ]
	$archivo_srv=$upload_dir['path'].'/'.$filename;
	if (move_uploaded_file($_FILES['archivo']['tmp_name'], $archivo_srv)) {
		//echo "Archivo subido";
		$fila = 1;
		$extranjeras = 0;
		$externo = 0;
		$errores = 0;
		if (($gestor = fopen($archivo_srv, "r")) !== FALSE) {
			ob_start();
			?>
			<h4>Resumen de Cambios a continuación:</h4>
			<div class="table-responsive" style="height: 90vh;">
			<table id="table" class="table table-bordered align-middle">
			  <thead class="table-dark">
			    <tr>
					<th scope="col">ID</th>
					<th class="col-A">RUT Matriz</th>
					<th class="col-B">Nombre de la Sociedad</th>
					<th class="col-C">Código SAP (Matriz)</th>
					<th class="col-D">RUT dueño</th>
					<th class="col-E">Nombre Dueño</th>
					<th class="col-F">Clasificación de Dueño</th>
					<th class="col-G">Unidad de Negocio de la sociedad</th>
					<th class="col-H">Asociada</th>
					<th class="col-I">Contabilidad</th>
					<th class="col-J">Gerente General</th>
					<th class="col-K">Moneda Funcional</th>
					<th class="col-L">Giro</th>
					<th class="col-M">Dirección</th>
					<th class="col-N">País</th>
					<th class="col-O">Fecha de Constitución</th>
					<th class="col-P">Acciones Emitidas</th>
					<th class="col-Q">Acciones Invertidas</th>
					<th class="col-R">RUT</th>
					<th class="col-S">Porcentaje</th>
					<th class="col-T">Clasificacion de Matriz</th>
					<th scope="col-U">Empresa</th>
					<th scope="col">ID</th>
					<th class="col-Z">EXTRA</th>
			    </tr>
			  </thead>
			  <tbody class="table-group-divider">
			<?php
			$nc=1;
			$tID=0;
			while (($datos = fgetcsv($gestor, 1000, ",")) !== FALSE) {
				if($fila > 1){
					//$numero = count($datos);
					//echo "<p> $numero de campos en la línea $fila: <br /></p>\n";
					/*
					for ($c=0; $c < $numero; $c++) {
						echo $datos[$c] . "<br />\n";
					}*/
					if( $datos[0] === "(Extranjera)" ){
						$extranjeras++;
					}else if( $datos[0] === "Externo" ){
						$externo++;
					}else{
						$rut=format_rut($datos[0]);
						$ID=buscarRut($rut);
						if($ID){
							$rut_dueno = format_rut($datos[3]);
							$rut_emp = format_rut($datos[17]);
							if($rut_emp=='Sin información'||$rut_emp=='N/A'||$rut_emp=='Revisar'||$rut_emp='-o')$rut_emp=false;
							$sap = (is_numeric($datos[2]))?'':intval($datos[2]);							
							$asociada = ($datos[7]=='Si')?true:false;
							$asociada = ($datos[7]=='Revisar')?'':$asociada;
							$conta = ($datos[8]=='Revisar')?'':ucfirst($datos[8]);
							$giro = (trim($datos[11])=='-')?'':$datos[11];
							if($giro=='Sin Información'||$dire=='N/A'||$giro=='Revisar'||$giro==0) $giro=='';
							$dire = (trim($datos[12])=='-')?'':$datos[12];
							if($dire=='Sin Información'||$dire=='N/A'||$dire=='Revisar'||$dire==0) $dire=='';
							$acc_em = (trim($datos[15])=='N/A')?'':intval($datos[15]);
							$acc_inv = (trim($datos[16])=='N/A')?'':intval($datos[16]);
							$dueno = $datos[4];
							if($dueno=='Sin información'||$dueno=='N/A'||$dueno=='Revisar') $dueno=='';
							$acc = (trim($datos[18])=='FALTAN %')?0:trim($datos[18]);
							$acc = normalizeNumber($acc);
							$fecha = date("d/m/Y", strtotime($datos[14]));
							$gerente=($datos[9]=='N/A')?'':$datos[9];
							if($gerente=='Sin información'||$gerente=='N/A'||gerente=='Revisar')$gerente=='';
							$UDN=($datos[6]=='Revisar')?'':$datos[6];
						?>
						<tr>
							<th scope="col"><a href="<?php echo esc_url( get_permalink( $ID ) ); ?>" target="_blank"><?=$ID;?></a></th>
							<th class="col-A"><?=$rut;?></th>
							<th class="col-B"><a href="<?php echo esc_url( get_permalink( $ID ) ); ?>" target="_blank"><?=$datos[1];?></a></th>
							<th class="col-C"><?=$sap;?></th>
							<th class="col-D"><?=$rut_dueno;?></th>
							<th class="col-E"><?=$dueno;?></th>
							<th class="col-F"><?=$datos[5];?></th>
							<th class="col-G"><?=$UDN;?></th>
							<th class="col-H"><?=$asociada;?></th>
							<th class="col-I"><?=$conta;?></th>
							<th class="col-J"><?=$gerente;?></th>
							<th class="col-K"><?=$datos[10];?></th>
							<th class="col-L"><?=$giro;?></th>
							<th class="col-M"><?=$dire;?></th>
							<th class="col-N"><?=$datos[13];?></th>
							<th class="col-O"><?=$fecha;?></th>
							<th class="col-P"><?=$acc_em;?></th>
							<th class="col-Q"><?=$acc_inv;?></th>
							<th class="col-R"><?PHP echo $rut_emp===false?$datos[17]:$rut_emp;?></th>
							<th class="col-S"><?=$acc;?></th>
							<th class="col-T"><?=$datos[19];?></th>
							<th class="col-U"><?=$datos[20];?></th>
							<th scope="col"><a href="<?php echo esc_url( get_permalink( $ID ) ); ?>" target="_blank"><?=$ID;?></a></th>
							<th class="col-Z">								
								<small style="font-size: 70%">
						<?php
							if($tID!=$ID){
								$tID=$ID;
								$nc=1;
							}else{
								$nc++;
							}
							//Agregar Cambios
							/*
							if(!empty($datos[1])){
								update_field('nombre_de_la_sociedad',$datos[1],$ID);
							}*/
							if(!empty($sap)){
								update_field('dueno_matriz',$sap,$ID);
							}
							if(!empty($rut_dueno)){
								update_field('rut_dueno',$rut_dueno,$ID);
							}
							if(!empty($datos[4])){
								update_field('nombre_dueno',$datos[4],$ID);
							}
							if(!empty($datos[5])){
								update_field('clasificacion_dueno',$datos[5],$ID);
							}
							/*if(!empty($UDN)){
								update_field('unidad_negocio_sociedad',$UDN,$ID);
							}*/
							if(!empty($asociada)){
								update_field('asociada',$asociada,$ID);
							}
							if(!empty($conta)){
								update_field('contabilidad',$conta,$ID);
							}
							if(!empty($gerente)){
								update_field('gerente_general',$gerente,$ID);
							}
							if(!empty($datos[10])){
								update_field('moneda_funcional',$datos[10],$ID);
							}
							if(!empty($giro)){
								update_field('giro',$giro,$ID);
							}
							if(!empty($dire)){
								update_field('direccion',$dire,$ID);
							}
							if(!empty($datos[13])){
								update_field('pais',$datos[13],$ID);
							}
							if(!empty($fecha)){
								update_field('fecha_constitucion',$fecha,$ID);
							}
							//--Acciones
							$subEmp=false;
							if( have_rows('empresas_asociadas',$ID) ) {
								while( have_rows('empresas_asociadas',$ID) ) {
									the_row();
									$rr=get_row_index();
									//--
									$eID = get_sub_field('empresa');									
									$eRUT = get_field('rut',$eID);
									if($rut_emp == $eRUT){
										$subEmp=true;
										$nID=buscarRut($rut_emp);
										$row = array(
											't_emp'				=> false,
											'empresa'			=> $nID,
											'porcentaje'		=> $acc,
											'clasificacion'		=> $datos[19]
										);
										if($rut_emp==$rut){
											update_field('porcentaje_participacion',$acc,$ID);
											echo '<span class="badge text-bg-light">Actualizado Empresa</span><br>';
										}else{
											update_row('empresas_asociadas', $rr, $row, $ID);
											echo '<span class="badge text-bg-info">Actualizado</span><br>';											
										}
									}
									if( empty(get_sub_field('porcentaje')) || get_sub_field('porcentaje')=="." ){
										delete_row('empresas_asociadas', $rr, $ID);
									}
								}
							}
							var_dump($subEmp);
							echo '<br>';
							if(!$subEmp){
								$nID=buscarRut($rut_emp);
								if($rut_emp===false){
									$nID=false;
								}
								var_dump($nID);
								echo '<br>';
								if($nID){
									$row = array(
										't_emp'				=> false,
										'empresa'			=> $nID,
										'porcentaje'		=> $acc,
										'clasificacion'		=> $datos[19]
									);
									if($rut_emp==$rut){
										update_field('porcentaje_participacion',$acc,$ID);
										echo '<span class="badge text-bg-light">Actualizado Empresa</span><br>';
									}else{
										add_row('empresas_asociadas', $row, $ID);
									}
								}else{
									$row = array(
										't_emp'				=> true,
										'empresa_externa'	=> $datos[20],
										'porcentaje'		=> $acc,
										'clasificacion'		=> $datos[19]
									);
									add_row('empresas_asociadas', $row, $ID);
								}
								var_dump($row);
								echo '<br>';								
								echo '<span class="badge text-bg-success">Agregado</span><br>';
							}
							if(!empty($acc_em)){
								update_field('acciones_emitidas',$acc_em,$ID);
							}
							if(!empty($acc_em)){
								update_field('acciones_invertidas',$acc_inv,$ID);
							}
							?>	
									</small>
									<p><a href="<?php echo site_url().'/wp-admin/post.php?post='.$ID.'&action=edit'; ?>" class="btn position-relative" target="_blank">
										<i class="fa-solid fa-up-right-from-square"></i> Internal Link
										<span class="position-absolute top-0 start-100 translate-middle badge rounded-pill bg-danger"><?=$nc;?></span>
									</a></p>
								</th>
							</tr>
							<?php
						}else{
						?>
				  		<tr class="alert alert-danger">
							<th colspan="23" class="text-center"><i class="fa-regular fa-eye-slash"></i> RUT no encontrado: <strong><?=$rut;?></strong>, <small>se necesita crear empresa primero</small></th>
						</tr>
				  		<?php
							//echo 'ID:'.$ID.' RUT:'.$datos[0].'<br>';
							//var_dump($ID);
							$errores++;
						}
					}					
				}
				$fila++;
			}
			fclose($gestor);
			unlink($archivo_srv);//borra archivo
			?>
			  </tbody>
			</table>
			</div>
			<hr>
			<p><i class="fa-regular fa-eye-slash"></i> Empresas <strong>No encontradas</strong>: <?=$errores;?>, <em>se necesita crear empresas con RUT primero</em></p>
			<h3><i class="fa-solid fa-triangle-exclamation"></i> Empresas Extranjeras que no se pueden cambiar (<?=$extranjeras;?>), <em><strong><i class="fa-solid fa-circle-exclamation"></i> se deben subir a mano, no hay RUT para buscar y comparar</strong></em></h2>
			<h3><i class="fa-solid fa-triangle-exclamation"></i> Empresas Externas que no se pueden cambiar (<?=$externo;?>), <em><strong><i class="fa-solid fa-circle-exclamation"></i> se deben subir a mano, no hay RUT para buscar y comparar</strong></em></h2>
			<?php
			$return = ob_get_clean();
       		ob_end_flush();
		}else{
			$return="Error al leer archivo";
		}
	}else{
		$return='Error al Subir Archivo ('.$_FILES["archivo"]["error"].')';
	}
	//rename( $upload_dir['path'].'/'.$filename, $upload_dir['path'].'/'.$nomFile);
	wp_die($return);
}