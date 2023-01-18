<?php /* Template Name: Pagina Upload */ 
get_header('nomenu'); ?>
<div class="container">
	<h1 class="title">Actualizacion masiva</h1>
	<form id="formulario" action="<?php echo admin_url('admin-ajax.php'); ?>" enctype="multipart/form-data" method="post">
		<input type="hidden" name="action" value="carga-csv">
		<div class="alert alert-warning d-flex alert-dismissible fade show" role="alert">
			<i class="fa-solid fa-triangle-exclamation flex-shrink-0 me-2"></i> 
			<div>Subir el archivo no reemplaza las empresas, solo <strong>actualiza</strong></div>
			<button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
		</div>
		<div class="alert alert-warning d-flex alert-dismissible fade show" role="alert">
			<i class="fa-solid fa-triangle-exclamation flex-shrink-0 me-2"></i> 
			<div>Recuerde que el archivo debe ser formato CSV y el orden no debe alterarse, aca un <a href="<?php echo get_template_directory_uri() ?>/ejemplo.csv" target="_blank"><i class="fa-solid fa-file-csv"></i> Archivo de Ejemplo</a> para descargar.<br>
			PD: Dejar en blanco la celda para no actualizar.</div>
			<button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
		</div>
		<div>
			<label for="formFileLg" class="form-label">Archivo CSV</label>
			<input class="form-control form-control-lg" id="formFileLg" name="archivo" type="file" accept=".csv, application/vnd.openxmlformats-officedocument.spreadsheetml.sheet, application/vnd.ms-excel" required />
			<div id="emailHelp" class="form-text">separado por comas UTF8</div>
		</div>
		<button id="actualiza-btn" type="submit" class="btn btn-gris mt-3"><i class="icon fa-solid fa-pen-nib"></i> Actualizar</button>
		<div class="alert alert-danger mt-3 d-none" id="aviso-msj" role="alert">
			<i class="fa-solid fa-skull-crossbones flex-shrink-0 me-2"></i> No actualice ni cierra la pagina hasta que termine de actualizar.
		</div>
	</form>
	<div class="alert alert-primary mt-5 d-none" id="alertamsj" role="alert"></div>
</div>
<?php get_footer(); ?> 
<script type="text/javascript">
	$(function() {
		$('#formulario').ajaxForm({
			beforeSubmit: function(){
				$('#actualiza-btn').prop( "disabled", true );
				$('#actualiza-btn .icon').removeClass('fa-pen-nib').addClass('fa-circle-notch fa-spin');
				$('#aviso-msj').removeClass('d-none').addClass('d-flex');
			},
			success: function(responseText, statusText, xhr, $form){
				console.log(statusText);
				//console.log(responseText);
				$('#alertamsj').empty().html(responseText).removeClass('d-none');
				$('#actualiza-btn').prop( "disabled", false );
				$('#actualiza-btn .icon').removeClass('fa-circle-notch fa-spin').addClass('fa-pen-nib');
				$(window).scrollTop($('#table').offset().top);
				$('#aviso-msj').addClass('d-none').removeClass('d-flex');
			},
			error: function(){
				alert('Tenemos un error intente mas tarde');
			}
		});
	});
	/*
	$('#formFileLg').on("change", function(){
		$('#actualiza-btn').prop( "disabled", false );
	});
	$('#actualiza-btn').click(function(){
		console.log('carga...');
		$.ajax({
			type: "post",
			url: ajax_var.url_ajax,
			data: {action:"carga-csv",nemp:$nemp,url:$nUrl},
			dataType: 'json',
			success: function(result){
				
			}
		}).fail( function( jqXHR, textStatus, errorThrown ) {
			$.fancybox.close();
			console.log(jqXHR.responseText);
			console.log(textStatus);
			console.log(errorThrown);
			alerta(jqXHR.responseText);
		});
	})*/
</script>
<style>
:root {
	--bs-border-color: #B02629;
}
</style>