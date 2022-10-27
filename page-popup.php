<?php /* Template Name: Pagina PopUps */ 
get_header('nomenu'); ?>
<?php if ( have_posts() ) : 
	$ID=$_GET['id'];
	$frontID = get_option('page_on_front'); 
	$total_porc = total_porc($ID);
	$logo = get_field('logo',$ID);
	if( is_null($logo) ) $logo=get_field('logo',$frontID);
?>
<section id="popup-<?=$ID;?>" class="container-fluid">
	<div class="row">
		<div class="col-12 text-center">
			<img src="<?=$logo; ?>" class="logo img-fluid mx-auto">
			<div class="fw-bold text-start d-block mb-2 mx-3"><?php the_field('nombre_de_la_sociedad',$ID); ?><br>
				<?php the_field('rut',$ID); ?><br></div>
		</div>
		<hr>
		<div class="col-12">
			<ul class="list-group list-group-flush list-group-no">
				<li class="list-group-item">
					<small><b><?php the_field('nombre_de_la_sociedad',$ID); ?></b><br>
					<i class="fa-solid fa-chart-pie"></i> <?=format_num($total_porc);?>%</small>
				</li>
				<?php
				if( have_rows('empresas_asociadas',$ID) ): while( have_rows('empresas_asociadas',$ID) ) : the_row();
					$sub_empresa_id=get_sub_field('empresa');
					$sub_empresa=get_field('nombre_de_la_sociedad',$sub_empresa_id);
					if(get_sub_field('t_emp')){					
						$sub_empresa=get_sub_field('empresa_externa');
					}
	            ?>
	          	<li class="list-group-item">
	          		<small><b><?php echo $sub_empresa; ?></b><br>
	          		<i class="fa-solid fa-diagram-project"></i> <?php the_sub_field('porcentaje'); ?>%</small>
	          	</li>
          		<?php endwhile; endif; ?>
      		</ul>
      		<ul class="list-group list-group-flush">
				<!--<li class="list-group-item"><strong>#:</strong> <?php echo $ID; ?></li>-->
      			<li class="list-group-item"><strong>Codigo SAP Sociedad:</strong> <?php the_field('dueno',$ID); ?></li>
      			<li class="list-group-item"><strong>RUT Dueño:</strong> <?php the_field('rut_dueno',$ID); ?></li>
      			<li class="list-group-item"><strong>Unidad de Negocio de la Sociedad:</strong> <?php echo get_field('unidad_negocio_sociedad',$ID); ?></li>
      		</ul>
      		<a href="<?php echo esc_url( get_permalink( $ID ) ); ?>" class="btn btn-link" target="_blank"><i class="fa-solid fa-circle-plus"></i> Más información</a>
		</div>
	</div>
</section>
<?php endif; ?>
<?php get_footer(); ?> 