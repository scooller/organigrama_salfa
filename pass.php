<?php
if ( !$_SESSION['wpb_pass_ok'] ):
	$custom_logo_id = get_theme_mod( 'custom_logo' );
    $logo = wp_get_attachment_image_src( $custom_logo_id , 'full' );
    $logo = $logo[0];
?>
<div class="card mx-auto box-shadow-n22" style="width: 20rem;">
	<img class="card-img-top mx-auto mt-4 w-50" src="<?=$logo;?>" alt="">
	<div class="card-body">
		<h6 class="card-title"><i class="fa-solid fa-triangle-exclamation"></i> Atención</h6>
		<form id="pass" enctype="application/x-www-form-urlencoded" method="post" action="<?=admin_url( 'admin-ajax.php' );?>" class="text-center g-3 p-3">
			<input type="hidden" name="action" value="pass_check">
			<div class="form-floating mb-3">
				<input type="password" class="form-control" name="pass" id="floatingInput" placeholder="Contraseña">
				<label for="floatingInput">Contraseña</label>
				<div class="form-text"><?php the_field('pass_info','option') ?></div>
			</div>
			<button type="submit" class="btn btn-gris mb-3"><i class="fa-solid fa-key"></i> Ingresar</button>
		</form>
	</div>
</div>
<?php 
	get_footer();
?>
<script>
$('#pass').submit(function(){
	$.ajax({
		type: "post",
		url: ajax_var.url_ajax,
		data: {action:"pass_check",pass:$('input[name="pass"]').val()},
		success: function(result){
			if(result=='Ok'){
				location.reload();
			}else{
				alerta(result);
			}
			console.log(result);
		}
	}).fail( function( jqXHR, textStatus, errorThrown ) {
		console.warn('ERROR SITIO');
		console.log(jqXHR.responseText);
		console.log(textStatus);
		console.log(errorThrown);
		alerta(jqXHR.responseText);
	});
	return false;
});
</script>
<?php
	wp_die();
endif;
?>