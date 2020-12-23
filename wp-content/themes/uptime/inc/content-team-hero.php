<?php
	$image    	= get_theme_mod( 'team_archive_background_image' );
	$title    	= get_theme_mod( 'team_archive_title', 'Our Team' );
	$subtitle 	= get_theme_mod( 'team_archive_subtitle', 'Introduce your company on a more personal level with these attractive team pages.' );
	$text_color = get_theme_mod( 'team_archive_text_colour', 'text-light' );
?>

<?php if( $image ) : ?>

<section class="has-divider text-light jarallax bg-dark" data-jarallax data-speed="0.5" data-overlay>
	
	<img src="<?php echo esc_url( $image ); ?> "alt="Image" class="jarallax-img opacity-50">

<?php else : ?>

<section class="has-divider text-light bg-dark <?php echo esc_attr( $text_color ); ?>" data-overlay>
	
<?php endif; ?>

	<div class="container">
		<div class="row justify-content-center text-center">
			<div class="col-xl-5 col-lg-6 col-md-8 text-center">
				<h1 class="display-4"><?php echo wp_kses_post( $title ); ?></h1>
				<p class="lead mb-0"><?php echo wp_kses_post( $subtitle ); ?></p>
			</div>
		</div>
	</div>
	
	<?php echo tommusrhodus_svg_dividers_pluck( 'pipe', '' ); ?>
	
</section>

