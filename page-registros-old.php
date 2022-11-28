<?php /* Template Name: Pagina Registro */ 
get_header('pdf'); ?>
<?php if ( have_posts() ) : while ( have_posts() ) : the_post(); $ID=get_the_ID(); ?>
<div class="contanier">
	<?php the_content(); ?>
	<form action="<?php echo get_permalink() ?>" method="post" class="row g-3">
		<div class="col-auto">
			<label for="inputFecha">Fecha</label>
			<input type="date" name="fecha" class="form-control" id="inputFecha">
		</div>
		<div class="col-auto">
			<label for="unsInput">Unidad de Negocio</label>
			<select class="form-select" id="unsInput" name="uns">
				  <option value="">Todos</option>
			  <?php
				  if( have_rows('unidades_de_negocio_sel', 'option') ): while( have_rows('unidades_de_negocio_sel', 'option') ): the_row();
			  ?>							  
				  <option><?php the_sub_field('nombre') ?></option>
			  <?php endwhile; endif; ?>
			</select>
		</div>
		<div class="col-auto">
			<label for="inputBuscar">Buscar</label>
			<button type="submit" class="btn btn-gris mb-3 d-block" id="inputBuscar"><i class="fa-solid fa-magnifying-glass"></i></button>
		</div>
	</form>
	<br><br>
	<?php 
	var_dump($_POST);
	//
	$meta_query = array(
		//'relation' => 'OR'
		'relation' => 'AND'
	);
	if( isset($_POST['uns']) && !empty($_POST['uns']) ){
		array_push($meta_query,
			array(
				'key'		=> 'unidad_negocio_sociedad',
				'value'		=> $_POST['uns'],
				'compare'	=> 'LIKE'
			)
		);
	}
	$args = array(
		'posts_per_page'=> -1,
		'post_type'		=> 'page',
		'post_status'   => 'publish',
		'order'         => 'ASC',
		'orderby'       => 'modified',
		'meta_key'		=> 'registros',
		'meta_compare'	=> 'EXISTS',
		'meta_query'	=> $meta_query
	);
	// query
	$the_query = new WP_Query( $args );
	if( $the_query->have_posts() ): ?>
	<div class="list-group">
	<?php while ( $the_query->have_posts() ) : $the_query->the_post(); ?>
		<div class="list-group-item list-group-item-action">
			<h5 class="title"><?php the_field('nombre_de_la_sociedad'); ?> - <?php the_field('rut'); ?></h5>
			<?php if( have_rows('registros') ): ?>
			<ol class="list-group list-group-numbered list-group-flush">
				<?php while( have_rows('registros') ) : the_row(); 
				$usuario=get_userdata(get_sub_field('usuario'));
				?>
				<li class="list-group-item">
					Se &quot;<strong class="rojo"><?php the_sub_field('tipo') ?></strong>&quot; a la empresa <strong class="rojo"><?php echo get_field('nombre_de_la_sociedad',get_sub_field('empresa')) ?></strong> 
					<?php the_sub_field('cambio') ?> con <strong class="rojo">fecha de <?php the_sub_field('fecha') ?></strong><br>
					usuario: <?php echo $usuario->display_name ?><br>
					<?php
					$file = get_sub_field('archivo');
					if( $file ): ?>
					<a href="<?php echo $file['url']; ?>" title="<?php echo $file['filename']; ?>" target="_blank"><strong class="rojo">Archivo Relacionado</strong></a><br>
					<?php endif; ?>
					Observaciones: <?php the_sub_field('observaciones') ?>
				</li>
				<?php endwhile; ?>
			</ol>
			<?php endif; ?>
			<hr>
		</div>
	<?php endwhile; ?>
	</div>
	<?php else: ?>
	<div class="alert alert-warning" role="alert">
		Aun no se registran cambios <i class="fa-solid fa-face-frown"></i>
	</div>
	<?php endif; wp_reset_query(); ?>
</div>
<?php endwhile; endif; ?>
<?php get_footer(); ?> 