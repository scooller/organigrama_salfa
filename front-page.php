<?php get_header(); 
global $detect;
$custom_logo_id = get_theme_mod( 'custom_logo' );
$logo = wp_get_attachment_image_src( $custom_logo_id , 'full' );
?>
<section id="pron" class="container-fluid text-center" style="<?php echo isset($_GET['save'])?'margin-top: 50rem;':''; ?>">
<?php if ( have_posts() ) : ?>	
	<?php while ( have_posts() ) : the_post(); $ID=get_the_ID(); ?>
	<div id="organigrama" class="caja caja-madre mx-auto text-center">
		<!--<div id="svgContainer"></div>-->
		<?php //echo basename( get_page_template() ); 
		$anchors=array(
			'inicio'=>'Bottom',
			'fin'=>'Top'
		);
		if(get_field('anchors',$ID)){
			$anchors=get_field('anchors',$ID);
		}		
		?>
		<div id="box-child-<?=$ID;?>" class="box box-madre mx-auto" data-level="0" data-anchors="<?php echo $anchors['inicio'].','.$anchors['fin']; ?>">
			<img src="<?php the_field('logo'); ?>" class="logo img-fluid">
			<div class="caja sub-mother" id="emp-<?=$ID;?>">
			  <div class="box box-shadow-n38 position-relative d-flex justify-content-center align-items-center" id="box-nchild-<?=$ID;?>" style="border: 0.25rem solid;">				
				<div class="row">
				  <div class="col-12 d-flex align-items-center text-start">
					<strong class="sub-title mx-auto"><?php the_field('nombre_de_la_sociedad',$ID); ?><br><?php the_field('rut',$ID); ?></strong>
				  </div>				  
				</div>
			  </div>
			</div>
		</div>
		<?php $chl_pages=pagina_hijo($ID); ?>
		<div class="cajas d-flex justify-content-center mx-auto">
			<?php foreach($chl_pages as $chl_page): 
			$total_porc=total_porc($chl_page->ID);
			$simb=get_field('simbologia',$chl_page->ID);
			//$ley=get_field('leyenda',$chl_page->ID);
			/*$b_color=color_simbolo($simb);
			$l_color=color_leyenda($ley);*/
			//css
			$margenes = '0 0 3rem 1rem';
			$margens = get_field('margen',$chl_page->ID);
			if( $margens ){
				$margenes = $margens['margen-top'].'rem '.$margens['margen-right'].'rem '.$margens['margen-bottom'].'rem '.$margens['margen-left'].'rem';			
			}

			$n=0;
			$anchors=array(
				'inicio'=>'Bottom',
				'fin'=>'Top'
			);
			if(get_field('anchors',$ID)){
				$anchors=get_field('anchors',$ID);
			}
			$faces=array();
			if( have_rows('faces',$chl_page->ID) ){
				while( have_rows('faces',$chl_page->ID) ) { the_row();
					array_push($faces,get_sub_field('cara'));
				}
			}
			$marg_l=0;
			if( get_field('col_blank',$chl_page->ID) ){
				$marg_l=30*get_field('col_blank',$chl_page->ID);
			}
			$endpoints_class=get_field('endpoints_class',$ID);
			if(get_field('orientacion_flecha',$ID)){
				$endpoints_class.=' '.get_field('orientacion_flecha',$ID);
			}
			//link popup
			$popup_id=get_field('pagina_popup','option');
			$n++;

			$mostrar=true;
			if( isset($_GET['nemp']) ){
				$emps=explode(",", urldecode($_GET['nemp']));
				//var_dump($emps);
				//var_dump($chl_page->ID);
				if( array_search($chl_page->ID, $emps) !== false ){
					$mostrar=false;
				}
			}
			if($mostrar):
			?>
			<div class="caja caja-madre" id="emp-<?=$chl_page->ID;?>" style="margin-left: <?=$marg_l?>rem">
				<div class="box" id="box-<?=$chl_page->ID;?>" data-unica="<? the_field('col_unq',$chl_page->ID); ?>" data-boxpadre="<?=$ID;?>" data-colorlinea="<?php the_field('color'); ?>" data-faces="<?php echo implode(",", $faces); ?>" data-anchors="<?php echo $anchors['inicio'].','.$anchors['fin']; ?>" data-endpoints-class="<?=$endpoints_class; ?>" data-level="<?=$n?>">					
					<a data-type="iframe" data-fancybox href="<?php echo esc_url( get_permalink( $popup_id ).'?id='.$chl_page->ID ); ?>" class="link"><img src="<?php the_field('logo',$chl_page->ID); ?>" class="logo img-fluid"></a>					
				</div>
				<div class="caja sub-c<?=$n;?>">
				  <div class="box box-shadow-n38 position-relative color-<?php echo sanitize_title($simb); ?>" id="box-child-<?=$chl_page->ID;?>" style="border: 0.25rem solid;">
					<!--<div class="linea-leyenda position-absolute" style="background-color: <?=$l_color;?>"></div>-->
					<a href="javascript:void(0);" onClick="minimizeChart(this,<?=$chl_page->ID;?>,true);" class="cerrar-link <?=$btnlnkcss;?>">
						<i class="fa-solid fa-circle-minus"></i>
						<i class="fa-solid fa-circle-plus d-none"></i>
					</a>

					<div class="row">
					  <div class="col-3 text-start">
						<a data-fancybox data-type="iframe" href="<?php echo esc_url( get_permalink( $popup_id ).'?id='.$chl_page->ID ); ?>" class="link"><strong><?=format_num($total_porc);?>%</strong></a>
					  </div>
					  <div class="col-9 text-start">
						<a data-fancybox data-type="iframe" href="<?php echo esc_url( get_permalink( $popup_id ).'?id='.$chl_page->ID ); ?>" class="link"><strong class="title mx-auto"><?php the_field('nombre_de_la_sociedad',$chl_page->ID); ?><br><span><?php the_field('rut',$chl_page->ID); ?></span></strong></a>
					  </div>
					  <?php
					  if( have_rows('empresas_asociadas',$chl_page->ID) ): while( have_rows('empresas_asociadas',$chl_page->ID) ) : the_row();
						$sub_empresa_id=get_sub_field('empresa');
						$sub_empresa=get_field('nombre_de_la_sociedad',$sub_empresa_id);
						$sub_empresa_rut=get_field('rut',$sub_empresa_id);
						if(get_sub_field('t_emp')){					
							$sub_empresa=get_sub_field('empresa_externa');
							$sub_empresa_rut="";
						}
					  ?>
					  <div class="col-3 sub-empr text-start">
						<a data-fancybox data-type="iframe" href="<?php echo esc_url( get_permalink( $popup_id ).'?id='.$chl_page->ID ); ?>" class="link"><span><?php echo format_num(get_sub_field('porcentaje')); ?>%</span></a>
					  </div>
					  <div class="col-9 sub-empr text-start">						  
						  <a data-fancybox data-type="iframe" href="<?php echo esc_url( get_permalink( $popup_id ).'?id='.$chl_page->ID ); ?>" class="link"><span class="sub-title"><?php echo $sub_empresa; ?><br><?php echo $sub_empresa_rut; ?></span></a>
						  <?php if(!get_sub_field('t_emp')): ?>
						  <button type="button" class="buscar" onClick="buscarEmpresa(<?=$sub_empresa_id?>,<?=$chl_page->ID;?>)" data-bs-toggle="tooltip" class="list-group-item list-group-item-action" data-bs-placement="left" title="Buscar Empresa"><i class="fa-solid fa-binoculars"></i></button>
						  <?php endif; ?>
					  </div>
					  <?php endwhile; endif; ?>
					</div>

				  </div>
				</div>
				<!-- // -->
				<?php sub_cajas($chl_page->ID,$n); ?>
			</div>
			<?php endif; ?>
			<?php endforeach; ?>
		</div>
		<div class="bg-all"></div>		
	</div>
	<div class="tools zoom">
		<div class="list-group btn-zoom">
			<button type="button" data-bs-toggle="tooltip" class="list-group-item list-group-item-action" data-bs-placement="left" title="Zoom In"><i class="fa-solid fa-square-plus"></i></button>
			<div class="list-group-item text-center p-0" id="num-zoom"><small>00</small></div>
			<button type="button" data-bs-toggle="tooltip" class="list-group-item list-group-item-action" data-bs-placement="left" title="Zoom Out"><i class="fa-solid fa-square-minus"></i></button>
			<button type="button" data-bs-toggle="tooltip" class="list-group-item list-group-item-action" data-bs-placement="left" title="Resetear Vista"><i class="fa-solid fa-arrows-to-circle"></i></button>
			<button type="button" data-bs-toggle="tooltip" class="list-group-item list-group-item-action d-none" data-bs-placement="left" title="Enfocar Familia"><i class="fa-solid fa-arrows-to-eye"></i></button>
			<button type="button" data-bs-toggle="tooltip" class="list-group-item list-group-item-action d-none" data-bs-placement="left" title="Mostrar Textos"><i class="fa-solid fa-text-width"></i></button>
		</div>
	</div>
	<div class="tools simbologia">
		<b>Simbología</b>
		<ul class="list-unstyled">
		<?php if( have_rows('Simbologia','option') ):
		while( have_rows('Simbologia','option') ) : the_row(); ?>
			<li><span style="background-color: <?php the_sub_field('color') ?>; border: 1px solid <?php the_sub_field('color_borde') ?>"></span> <?php the_sub_field('nombre') ?></li>
		<?php endwhile; endif; ?>
		</ul>
		<small class="ver"><b>Actualización:</b> <?php echo ( current_user_can( 'administrator' ) )?'Admin_':''; ?><?php the_field('version','option'); ?><?php echo wp_date("d.m.y\_H:i"); ?></small>
		
	</div>
	<!--
	<div class="tools leyendas">
		<ul class="list-unstyled">
		<?php if( have_rows('leyendas','option') ):
		while( have_rows('leyendas','option') ) : the_row(); ?>
			<li><span style="background-color: <?php the_sub_field('color') ?>"></span> <?php the_sub_field('nombre') ?></li>
		<?php endwhile; endif; ?>
		</ul>
	</div>
	-->	
<?php endwhile; endif; ?>
	
</section>
<style type="text/css">
<?php if( have_rows('Simbologia','option') ):
while( have_rows('Simbologia','option') ) : the_row(); ?>
	.caja .box.color-<?php echo sanitize_title(get_sub_field('nombre')); ?>{
		background-color: <?php the_sub_field('color') ?>;
		border-color: <?php the_sub_field('color_borde') ?>!important;
		color: <?php the_sub_field('color_texto') ?>;		
	}	
	.caja .box.color-<?php echo sanitize_title(get_sub_field('nombre')); ?> .cerrar-link{
		color: <?php the_sub_field('color_texto') ?>;
		-webkit-text-stroke: 2px <?php the_sub_field('color') ?>;
	}
	.caja .box.color-<?php echo sanitize_title(get_sub_field('nombre')); ?> a.link, .caja .box.color-<?php echo sanitize_title(get_sub_field('nombre')); ?> .buscar{
		color: <?php the_sub_field('color_texto') ?>;
	}
	.caja .box.color-<?php echo sanitize_title(get_sub_field('nombre')); ?> .sub-cajas .caja .box .sub-empr{
		color: <?php the_sub_field('color_texto') ?>;
	}
<?php endwhile; endif; ?>
</style>
<?php get_footer(); ?> 