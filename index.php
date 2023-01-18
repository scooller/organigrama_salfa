<?php get_header('nomenu'); ?>
<script type="text/javascript">
	//window.location.href="<?php bloginfo('url'); ?>";
</script>
<section class="single">
<?php if ( have_posts() ) : while ( have_posts() ) : the_post(); $ID=get_the_ID();
?>
	<div class="container">
		<h1 class="title"><?php the_title(); ?></h1>
		<div class="content">
		<?php the_content(); ?>
		</div>
	</div>
<?php endwhile; else: ?>
	<div class="alert alert-danger" role="alert">
		No hay datos
	</div>
<?php endif; ?>
</section>
<?php get_footer(); ?> 