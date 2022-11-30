<?php /* Template Name: Pagina PDF */ 
get_header('pdf'); ?>
<?php if ( have_posts() ) : while ( have_posts() ) : the_post(); $ID=get_the_ID(); ?>
<div class="contanier" style="<?php echo isset($_GET['save'])?'margin-top: 2rem;':''?>">
	<?php the_content(); ?>
	<br><br>
	<?php if( have_rows('historial_pdf','option') ): ?>
	<div class="list-group" style="<?php echo isset($_GET['save'])?'margin-left: 2rem;':''?>">
	<?php while( have_rows('historial_pdf','option') ): the_row();  ?>
		<div class="list-group-item list-group-item-action">
			<div>
				PDF: <strong class="rojo"><a href="<?php the_sub_field('url_archivo'); ?>" target="_blank"><?php the_sub_field('url_archivo'); ?></a></strong><br>
				Creado por el usuario <strong class="rojo"><?php the_sub_field('usuario'); ?></strong> con fecha <strong><?php the_sub_field('fecha'); ?></strong>
			</div>
		</div>
	<?php endwhile; ?>
	</div>
	<?php else: ?>
	<div class="alert alert-warning" role="alert">
		Aun no se registran PDFs <i class="fa-solid fa-face-frown"></i>
	</div>
	<?php endif; ?>
</div>
<?php endwhile; endif; ?>
<?php get_footer(); ?> 