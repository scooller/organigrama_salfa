<?php /* Template Name: Pagina Busqueda */ 
get_header(); ?>
<?php if ( have_posts() ) : while ( have_posts() ) : the_post(); $ID=get_the_ID(); ?>
<section id="busqueda" class="container-fluid g-0">
	<h3 class="title text-uppercase">Buscador de Empresas</h3>
	<div class="contenedor">
		<h5 class="sub-title">Ingrese los Filtros de Búsqueda <i class="fa-solid fa-filter"></i></h5>
		<form id="formulario" action="<?php echo get_permalink( $ID ); ?>" method="POST">
			<div class="row">
				<div class="col-md-6">
					<div class="mb-3 row">
					    <label for="unsInput" class="col-sm-4 col-form-label">Unidad de Negocio de la Sociedad:</label>
					    <div class="col-sm-8">
						  <select class="form-select" id="unsInput" name="uns">
							  <option value="">Todos</option>
						  <?php
							  if( have_rows('unidades_de_negocio_sel', 'option') ): while( have_rows('unidades_de_negocio_sel', 'option') ): the_row();
						  ?>							  
							  <option><?php the_sub_field('nombre') ?></option>
						  <?php endwhile; endif; ?>
						  </select>
					    </div>
					</div>					
					<div class="mb-3 row">
					    <label for="nombreInput" class="col-sm-4 col-form-label">Nombre de la Sociedad:</label>
					    <div class="col-sm-8">
					      <input type="text" class="form-control" id="nombreInput" name="nombre">
					    </div>
					</div>
					<div class="mb-3 row">
					    <label for="duenoInput" class="col-sm-4 col-form-label">Código SAP (Sociedad):</label>
					    <div class="col-sm-8">
					      <input type="text" class="form-control" id="duenoInput" name="dueno">
					    </div>
					</div>
				</div>
				<div class="col-md-6">
					<div class="mb-3 row">
					    <label for="rutInput" class="col-sm-4 col-form-label">RUT:</label>
					    <div class="col-sm-8">
					      <input type="text" class="form-control" id="rutInput" name="rut" maxlength="10">
						  <div id="rutInput" class="form-text">Sin digito verificador</div>
					    </div>
					</div>
					<div class="mb-3 row">
					    <label for="paisInput" class="col-sm-4 col-form-label">País:</label>
					    <div class="col-sm-8">
					      <select class="form-select" id="paisInput" name="pais">
							  <option value="">Todos</option>
						  <?php
							  if( have_rows('paises','option') ): while( have_rows('paises','option') ) : the_row();
						  ?>							  
							  <option><?php the_sub_field('nombre') ?></option>
						  <?php endwhile; endif; ?>
						  </select>
					    </div>
					</div>
				</div>
				<div class="col-md-6 offset-md-6 text-end">
					<input type="hidden" name="envio" value="1">
					<button type="reset" class="btn btn-gris">Limpiar <i class="fa-solid fa-eraser"></i></button>
					<button type="submit" class="btn btn-gris">Buscar <i class="fa-brands fa-searchengin"></i></button>
				</div>
			</div>
		</form>
		<div id="resultados">
			<div class="load text-center d-none"><i class="fa-solid fa-spinner fa-spin"></i></div>
			<?php if( isset($_POST['envio']) ): ?>
			<h5 class="sub-title">Resultados de la Búsqueda <i class="fa-brands fa-searchengin"></i></h5>
			<?php
			global $wpdb;

			//var_dump($_POST);

			$rut = $wpdb->_real_escape( $_POST['rut'] );
			$nom = $wpdb->_real_escape( $_POST['nombre'] );
			$dueno = $wpdb->_real_escape( $_POST['dueno'] );
			$uns = $wpdb->_real_escape( $_POST['uns'] );
			$pais = $wpdb->_real_escape( $_POST['pais'] );
			
			$meta_query = array(
				//'relation' => 'OR'
				'relation' => 'AND'
			);
			
			if( !empty($rut) ){
				array_push($meta_query,
					array(
						'key'		=> 'rut',
						'value'		=> $rut,
						'compare'	=> 'LIKE'
					)
				);
			}
			if( !empty($nom) ){
				array_push($meta_query,
					array(
						'key'		=> 'nombre_de_la_sociedad',
						'value'		=> $nom,
						'compare'	=> 'LIKE'
					)
				);
			}
			if( !empty($dueno) ){
				array_push($meta_query,
					array(
						'key'		=> 'dueno',
						'value'		=> $dueno,
						'compare'	=> 'LIKE'
					)
				);
			}
			if( !empty($uns) ){
				array_push($meta_query,
					array(
						'key'		=> 'unidad_negocio_sociedad',
						'value'		=> $uns,
						'compare'	=> 'LIKE'
					)
				);
			}
			if( !empty($pais) ){
				array_push($meta_query,
					array(
						'key'		=> 'pais',
						'value'		=> $pais,
						'compare'	=> 'LIKE'
					)
				);
			}

			//var_dump($rut);

			$args = array(
				'posts_per_page'=> -1,
				'post_type'		=> 'page',
				'post_status'   => 'publish',
    			'order'         => 'ASC',
    			'orderby'       => 'title',
				'meta_query'	=> $meta_query
			);
			// query
			//var_dump($args)
			$the_query = new WP_Query( $args );
			
			?>
			<?php if( $the_query->have_posts() ): ?>
			<table id="table" class="table table-striped table-fix" data-toggle="table" data-pagination="true" data-search="true" data-click-to-select="true" data-page-size="50" data-page-list="[50, 100, 150, 200, 250, 300]" data-show-export="true">
			  <thead>
			    <tr>
					<!--<th  data-width="40" scope="col">#</th>-->
					<th data-width="175" data-sortable="true">Unidad de Negocio<br>de la Sociedad</th>
					<th data-width="100">Código SAP<br>(Sociedad)</th>					
					<th data-width="220" data-sortable="true">Nombre de la Sociedad</th>
					<th data-width="110">RUT</th>
					<th data-width="110" data-sortable="true">País de<br>Origen</th>
					<th data-width="110" data-sortable="true">Moneda<br>de Origen</th>
					<th data-width="130" data-sortable="true">Porcentaje de<br>Participación</th>
					<th data-width="110">Clasificacion<br>de Matriz</th>
					<th data-width="100">Código SAP<br>(Matriz)</th>					
					<th data-width="110">Rut Matriz</th>					
					<th data-width="150" data-sortable="true">Empresa Matriz</th>
					
					<th data-width="100" data-sortable="true">Asociada</th>
					<th data-width="125" data-sortable="true">Contabilidad</th>
					<th data-width="145">Gerente General</th>					
					<th data-width="110">Giro</th>
					<th data-width="145">Dirección</th>					
					<th data-width="110">Acciones<br>Emitidas</th>
					<th data-width="110">Acciones<br> Invertidas</th>
					<th data-width="130" data-sortable="true">Fecha de<br>Constitución</th>
			    </tr>
			  </thead>
			  <tbody>
			  	<?php while ( $the_query->have_posts() ) : $the_query->the_post();
				 ?>
			    <tr>
					<!--<th scope="row"><?php 
					if ( current_user_can( 'administrator' ) ) {
						echo '<a href="'.home_url( '/wp-admin/post.php?post='.$post->ID.'&action=edit' ).'" target="_blank">'.$post->ID.'</a>'; 
					}else{
						echo $post->ID; 
					}					  
				  ?></th>-->
					<td><?php the_field('unidad_negocio_sociedad'); ?></td>
					<td><?php the_field('dueno'); ?></td>					
					<td><a href="<?php echo esc_url( get_permalink( $post->ID ) ); ?>" target="_blank"><?php the_field('nombre_de_la_sociedad'); ?></a></td>					
					<td><a href="<?php echo esc_url( get_permalink( $post->ID ) ); ?>" target="_blank"><?php the_field('rut'); ?></a></td>
					<td><?php the_field('pais'); ?></td>
					<td><?php the_field('moneda_funcional'); ?></td>
					<td><?php echo format_num(get_field('porcentaje_participacion')); ?>%</td>
					<td><?php the_field('clasificacion_dueno'); ?></td>
					<td><?php the_field('dueno_matriz'); ?></td>
					<td><?php the_field('rut_dueno'); ?></td>
					<td><?php the_field('nombre_dueno'); ?></td>
					
					<td><?php echo get_field('asociada')?'Si':'No'; ?></td>
					<td><?php the_field('contabilidad'); ?></td>
					<td><?php the_field('gerente_general'); ?></td>					
					<td><?php the_field('giro'); ?></td>
					<td><?php the_field('direccion'); ?></td>					
					<td><?php echo format_num(get_field('acciones_emitidas'),0); ?></td>
					<td><?php echo format_num(get_field('acciones_invertidas'),0); ?></td>
					<td><?php the_field('fecha_constitucion'); ?></td>
			    </tr>
			    <?php endwhile; ?>
			  </tbody>
			</table>
			<?php else: ?>
			<div class="alert alert-warning" role="alert">
				No hay resultados <i class="fa-solid fa-face-frown"></i>
			</div>
			<?php endif; 
			wp_reset_query();?>
			<?php endif; ?>
		</div>
	</div>
</section>
<?php endwhile; endif; ?>
<?php get_footer(); ?>
<script type="text/javascript">
$(function() {
	// bind 'myForm' and provide a simple callback function
	$('#formulario').ajaxForm({
		beforeSubmit: function(){
			$('#resultados .load').removeClass('d-none');
		},
		success: function(data){
			result=$(data).find('#resultados')[0];
			$('#resultados').html(result);
			$('#table').bootstrapTable();
		} 
	});
	$("input[name='rut']").on('keyup', function(){
		var n = parseInt($(this).val().trim().replace(/\D/g,''),10);
		var valor=n.toLocaleString("es-CL");
		if( $(this).val().trim()=="" ) valor='';
		//if( isNaN(valor) ) valor='';
		$(this).val(valor);
	});
	/*
	$("input[name='rut']").rut({
        formatOn: 'keyup blur',
        minimumLength: 7,
        useThousandsSeparator : true
    });*/
});
</script>
<link href="https://unpkg.com/bootstrap-table@1.20.2/dist/bootstrap-table.min.css" rel="stylesheet">

<script src="https://cdn.jsdelivr.net/npm/tableexport.jquery.plugin@1.10.21/tableExport.min.js"></script>
<script src="https://cdn.jsdelivr.net/npm/tableexport.jquery.plugin@1.10.21/libs/jsPDF/jspdf.min.js"></script>
<script src="https://cdn.jsdelivr.net/npm/tableexport.jquery.plugin@1.10.21/libs/jsPDF-AutoTable/jspdf.plugin.autotable.js"></script>
<script src="https://unpkg.com/bootstrap-table@1.20.2/dist/bootstrap-table.min.js"></script>
<script src="https://unpkg.com/bootstrap-table@1.20.2/dist/extensions/export/bootstrap-table-export.min.js"></script>
<script src="https://unpkg.com/bootstrap-table@1.20.2/dist/locale/bootstrap-table-es-ES.min.js"></script>