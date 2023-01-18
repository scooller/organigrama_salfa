<?php /* Template Name: Pagina Registro */ 
get_header('pdf'); ?>
<?php if ( have_posts() ) : while ( have_posts() ) : the_post(); $ID=get_the_ID(); ?>
<div class="contanier" style="<?php echo isset($_GET['save'])?'margin-top: 2rem;':''?>">
	<?php the_content(); ?>
	<form action="<?php echo get_permalink() ?>" method="post" class="row g-3 <?php echo isset($_GET['save'])?'d-none':''; ?>">
		<div class="col-auto">
			<label for="inputFecha">Fecha Inicio</label>
			<input type="date" name="fecha_init" class="form-control" id="inputFecha" placeholder="dd-mm-yyyy" value="<?=$_POST['fecha_init'] ?>">
		</div>
		<div class="col-auto">
			<label for="inputFechaFin">Fecha Fin</label>
			<input type="date" name="fecha_end" class="form-control" id="inputFechaFin" placeholder="dd-mm-yyyy" value="<?PHP echo isset($_POST['fecha_end'])?$_POST['fecha_end']:date('Y-m-d'); ?>">
		</div>
		<div class="col-auto">
			<label for="unsInput">Unidad de Negocio</label>
			<!--<select class="form-select" id="unsInput" name="uns">
				  <option value="">Todos</option>
			  <?php
				  if( have_rows('unidades_de_negocio_sel', 'option') ): while( have_rows('unidades_de_negocio_sel', 'option') ): the_row();
			  ?>							  
				  <option><?php the_sub_field('nombre') ?></option>
			  <?php endwhile; endif; ?>
			</select>-->
			<input type="text" name="nom_soc" class="form-control" id="unsInput" value="<?=$_POST['nom_soc'] ?>">
		</div>
		<div class="col-auto">
			<label for="inputBuscar">Buscar</label>
			<button type="submit" class="btn btn-gris mb-3 d-block" id="inputBuscar"><i class="fa-solid fa-magnifying-glass"></i></button>
		</div>
	</form>
	<br><br>
	<?php 
	//
	$meta_query = array(
		array(
			'key'		=> 'registros',
			'compare'	=> 'EXISTS'
		),
		//'relation' => 'OR'
		'relation' => 'AND'
	);
	if( isset($_POST['nom_soc']) && !empty($_POST['nom_soc']) ){
		$nom_soc=$wpdb->_real_escape($_POST['nom_soc']);
		array_push($meta_query,
			array(
				'key'		=> 'nombre_de_la_sociedad',
				'value'		=> $nom_soc,
				'compare'	=> 'LIKE'
			)
		);
	}
	$args = array(
		'posts_per_page'=> -1,
		'post_type'		=> 'page',
		'post_status'   => 'publish',
		'order'         => 'DESC',
		'orderby'       => 'modified',
		'meta_query'	=> $meta_query
	);
	// query
	$registros_arr=array();
	$the_query = new WP_Query( $args );
	if( $the_query->have_posts() ){
		while ( $the_query->have_posts() ){
			$the_query->the_post();
			$min_reg=array();
			while( have_rows('registros') ){
				the_row();
				$usuario=get_userdata(get_sub_field('usuario'));
				$muestra=true;
				if( isset($_POST['fecha_init']) && !empty($_POST['fecha_init'])){
					$muestra=false;
				
					$calcDate = date('Y-m-d', strtotime(get_sub_field('fecha')));
					//echo $paymentDate; // echos today! 
					$dateBegin = date('Y-m-d', strtotime($_POST['fecha_init']));
					$dateEnd = date('Y-m-d', strtotime($_POST['fecha_end']));

					if (($calcDate >= $dateBegin) && ($calcDate <= $dateEnd)){
						$muestra=true;
					}
				}
				if($muestra){
					$min_reg[]=array(
						'tipo'=>get_sub_field('tipo'),
						'empresa'=>get_field('nombre_de_la_sociedad',get_sub_field('empresa')),
						'cambio'=>get_sub_field('cambio'),
						'fecha'=>date('d/m/Y g:i a', strtotime(get_sub_field('fecha'))),
						'usuario'=>$usuario->display_name,
						'archivo'=>get_sub_field('archivo'),
						'observaciones'=>get_sub_field('observaciones')
					);
				}
			}
			if(!empty($min_reg)){
				$registros_arr[]=array(
					'nombre'=>get_field('nombre_de_la_sociedad').' - '.get_field('rut'),
					'registros'=>$min_reg
				);
			}
		}
	}
	/*
	echo '<pre>';
	var_dump($registros_arr);
	echo '</pre>';*/
	if(!empty($registros_arr)):
	?>
	<div class="list-group">
	<?php foreach($registros_arr as $registro): ?>
		<div class="list-group-item list-group-item-action">
			<h5 class="title"><?php echo $registro['nombre']; ?></h5>			
			<ol class="list-group list-group-numbered list-group-flush">
				<?php
				foreach(array_reverse($registro['registros']) as $min_reg):				
				?>
				<li class="list-group-item">
					<?php 
					switch($min_reg['tipo']){
						case 'Crea':
							echo '<b class="rojo">EMPRESA CREADA</b><br><br>';
							break;
						case 'Adquiere':
							echo '<b class="rojo">EMPRESA ADQUIRIDA (TERCEROS)</b><br><br>';
							break;
						case 'Traspasa':
							echo '<b class="rojo">EMPRESA TRASPASADA (PROPIA O SOCIA)</b><br><br>';
							break;
						case 'Fusiona':
							echo '<b class="rojo">EMPRESA FUSIONADA</b><br><br>';
							break;
						case 'Cierra':
							echo '<b class="rojo">EMPRESA CERRADA Y/O CON TÉRMINO DE GIRO</b><br><br>';
							break;
						default:
							echo '<b class="rojo">'.$min_reg['tipo'].'</b><br><br>';
							break;
					}
					?>
					Se &quot;<strong class="rojo"><?php echo $min_reg['tipo'] ?></strong>&quot; a la empresa <strong class="rojo"><?php echo $min_reg['empresa'] ?></strong> 
					<?php echo $min_reg['cambio'] ?> con <strong class="rojo">fecha de <?php echo $min_reg['fecha']; ?></strong><br>
					usuario: <strong><?php echo $min_reg['usuario'] ?></strong><br>
					<?php
					$file = $min_reg['archivo'];
					if( $file ): ?>
					<a href="<?php echo $file['url']; ?>" title="<?php echo $file['filename']; ?>" target="_blank"><strong class="rojo">Archivo Relacionado</strong></a><br>
					<?php endif; ?>
					Observaciones: <?php echo $min_reg['observaciones'] ?>
				</li>
				<?php endforeach; ?>
			</ol>
			<hr>
		</div>
	<?php endforeach; ?>
	</div>
	<?php else: ?>
	<div class="alert alert-warning" role="alert">
		Aun no se registran cambios <i class="fa-solid fa-face-frown"></i>
	</div>
	<?php endif; wp_reset_query(); ?>
</div>
<?php endwhile; endif; ?>
<?php get_footer(); ?> 