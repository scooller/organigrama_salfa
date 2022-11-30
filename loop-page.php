<?php if ( have_posts() ) : while ( have_posts() ) : the_post(); $ID=get_the_ID();
	$frontID = get_option('page_on_front'); 
	$total_porc=total_porc($ID);
	$logo=get_field('logo',$ID);
	if( is_null($logo) ) $logo=get_field('logo',$frontID);
?>
<section id="page-<?=$ID;?>" class="container-fluid h-100 d-flex justify-content-center align-items-center pb-4">
	<div class="container">
		<div class="tabla-caja box-shadow-n14">
			<div class="row">
				<div class="col-3">
					<img src="<?=$logo; ?>" class="logo img-fluid mx-auto">
					<div class="fw-bold text-start d-block mb-2 mx-3"><?php the_field('nombre_de_la_sociedad'); ?><br>
					<?php the_field('rut'); ?><br></div>
					<hr>
					<ul class="list-group list-group-flush list-group-no">
						<li class="list-group-item">
							<small><b><?php the_field('nombre_de_la_sociedad'); ?></b><br>
							<i class="fa-solid fa-chart-pie"></i> <?=format_num($total_porc);?>%</small>
						</li>
						<?php
						if( have_rows('empresas_asociadas') ): while( have_rows('empresas_asociadas') ) : the_row();
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
				</div>
				<div class="col-9">
					<form>
						<div class="mb-3 row">
							<label class="col-sm-2 col-form-label">Código SAP (Sociedad):</label>
						    <div class="col-sm-10">
						    	<input type="text" readonly disabled class="form-control" value="<?php the_field('dueno'); ?>">
						    </div>
						</div>
						<div class="mb-3 row">
							<label class="col-sm-2 col-form-label">Nombre de la Sociedad:</label>
						    <div class="col-sm-10">
						    	<input type="text" readonly disabled class="form-control" value="<?php the_field('nombre_de_la_sociedad'); ?>">
						    </div>
						</div>						
						<div class="mb-3 row">
							<label class="col-sm-2 col-form-label">RUT:</label>
						    <div class="col-sm-10">
						    	<input type="text" readonly disabled class="form-control" value="<?php the_field('rut'); ?>">
						    </div>
						</div>
						<div class="mb-3 row">
							<label class="col-sm-2 col-form-label">País de Origen:</label>
						    <div class="col-sm-10">
						    	<input type="text" readonly disabled class="form-control" value="<?php the_field('pais'); ?>">
						    </div>
						</div>
						<div class="mb-3 row">
							<label class="col-sm-2 col-form-label">Moneda de Origen:</label>
						    <div class="col-sm-10">
						    	<input type="text" readonly disabled class="form-control" value="<?php the_field('moneda_funcional'); ?>">
						    </div>
						</div>
						<div class="mb-3 row">
							<label class="col-sm-2 col-form-label">Porcentaje de Participación:</label>
						    <div class="col-sm-10">
						    	<input type="text" readonly disabled class="form-control" value="<?php echo format_num(get_field('porcentaje_participacion')); ?>%">
						    </div>
						</div>
						<div class="mb-3 row">
							<label class="col-sm-2 col-form-label">Clasificacion de Matriz:</label>
						    <div class="col-sm-10">
						    	<input type="text" readonly disabled class="form-control" value="<?php the_field('clasificacion_dueno'); ?>">
						    </div>
						</div>
						<div class="mb-3 row">
							<label class="col-sm-2 col-form-label">Código SAP (Matriz):</label>
						    <div class="col-sm-10">
						    	<input type="text" readonly disabled class="form-control" value="<?php the_field('dueno_matriz'); ?>">
						    </div>
						</div>
						<div class="mb-3 row">
							<label class="col-sm-2 col-form-label">Rut Matriz:</label>
						    <div class="col-sm-10">
						    	<input type="text" readonly disabled class="form-control" value="<?php the_field('rut_dueno'); ?>">
						    </div>
						</div>
						<div class="mb-3 row">
							<label class="col-sm-2 col-form-label">Empresa Matriz:</label>
						    <div class="col-sm-10">
						    	<input type="text" readonly disabled class="form-control" value="<?php the_field('nombre_dueno'); ?>">
						    </div>
						</div>
						<div class="mb-3 row">
							<label class="col-sm-2 col-form-label">Unidad de Negocio de Sociedad:</label>
						    <div class="col-sm-10">
						    	<input type="text" readonly disabled class="form-control" value="<?php the_field('unidad_negocio_sociedad'); ?>">
						    </div>
						</div>						
						<div class="mb-3 row">
							<label class="col-sm-2 col-form-label">Asociada:</label>
						    <div class="col-sm-10">
								<input type="text" readonly disabled class="form-control" value="<?php echo get_field('asociada')?'Si':'No'; ?>">
								<!--
						    	<div class="form-check">
						    		<input type="checkbox" readonly disabled class="form-check-input" value="1" <?php echo get_field('asociada')?'checked':''; ?>>
						    		<label class="form-check-label" for="flexCheckDefault">Si/No</label>
						    	</div>-->
						    </div>
						</div>
						<div class="mb-3 row">
							<label class="col-sm-2 col-form-label">Contabilidad:</label>
						    <div class="col-sm-10">
						    	<input type="text" readonly disabled class="form-control" value="<?php the_field('contabilidad'); ?>">						    	
						    </div>
						</div>
						<div class="mb-3 row">
							<label class="col-sm-2 col-form-label">Gerente General:</label>
						    <div class="col-sm-10">
						    	<input type="text" readonly disabled class="form-control" value="<?php the_field('gerente_general'); ?>">
						    </div>
						</div>
						
						<div class="mb-3 row">
							<label class="col-sm-2 col-form-label">Giro:</label>
						    <div class="col-sm-10">
						    	<input type="text" readonly disabled class="form-control" value="<?php the_field('giro'); ?>">
						    </div>
						</div>
						<div class="mb-3 row">
							<label class="col-sm-2 col-form-label">Dirección:</label>
						    <div class="col-sm-10">
						    	<input type="text" readonly disabled class="form-control" value="<?php the_field('direccion'); ?>">
						    </div>
						</div>
												
						<div class="mb-3 row">
							<label class="col-sm-2 col-form-label">Acciones Emitidas:</label>
						    <div class="col-sm-10">
						    	<input type="text" readonly disabled class="form-control" value="<?php echo format_num(get_field('acciones_emitidas'),0); ?>">
						    </div>
						</div>
						<div class="mb-3 row">
							<label class="col-sm-2 col-form-label">Acciones Invertidas:</label>
						    <div class="col-sm-10">
						    	<input type="text" readonly disabled class="form-control" value="<?php echo format_num(get_field('acciones_invertidas'),0); ?>">
						    </div>
						</div>
						<div class="mb-3 row">
							<label class="col-sm-2 col-form-label">Fecha de Constitución:</label>
						    <div class="col-sm-10">
						    	<input type="text" readonly disabled class="form-control" value="<?php the_field('fecha_constitucion'); ?>">
						    </div>
						</div>
					</form>
				</div>
			</div>
		</div>
	</div>
</section>
<?php endwhile; else: ?>
<div class="alert alert-danger" role="alert">
	No hay datos
</div>
<?php endif; ?>