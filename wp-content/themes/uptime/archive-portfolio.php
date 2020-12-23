<?php 
	get_header(); 
	
	$title    = get_theme_mod( 'portfolio_archive_title', 'We form lasting partnerships.' );
	$subtitle = get_theme_mod( 'portfolio_archive_subtitle', 'Sed ut perspiciatis unde omnis iste natus error sit voluptatem accusantium doloremque laudantium, totam rem aperiam, eaque ipsa.' );
?>

<section class="bg-primary-3 has-divider text-light">
	
	<div class="container pb-6">
		<div class="row justify-content-center text-center">
			<div class="col-xl-8 col-lg-9 col-md-10">
				
				<h1 class="display-3 mb-5"><?php echo wp_kses_post( $title ); ?></h1>
				<p class="lead"><?php echo wp_kses_post( $subtitle ); ?></p>
				
			</div>
		</div>
	</div>
	
	<?php echo tommusrhodus_svg_dividers_pluck( 'pipe', '' ); ?>
	
</section>
    
<section>
	<div class="container">
		<?php get_template_part( 'loop/loop-portfolio', get_theme_mod( 'portfolio_layout', '2-columns' ) ); ?>
	</div>
</section>

<section class="pt-0">
	<div class="container">
		<?php get_template_part( 'inc/content', 'pagination' ); ?>
	</div>
</section>

<?php get_footer();