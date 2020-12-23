<?php
	$image    = get_theme_mod( 'post_archive_background_image' );
	$total_results = $wp_query->found_posts;	
?>

<?php if( $image ) : ?>

<section class="has-divider text-light jarallax bg-dark" data-jarallax data-speed="0.5" data-overlay>
	
	<img src="<?php echo esc_url( $image ); ?> "alt="Image" class="jarallax-img opacity-50">

<?php else : ?>

<section class="has-divider text-light bg-dark" data-overlay>
	
<?php endif; ?>

	<div class="container">
		<div class="row">
			<div class="col-xl-5 col-lg-6 col-md-8">
				<h1 class="display-4"><?php esc_html_e( 'Showing results for: ', 'uptime' ); echo ucfirst( get_search_query() ); ?></h1>
				<?php
					echo '<span class="lead mb-0">'. esc_html( $total_results ) . esc_html__( ' results found', 'uptime' ) .'</span>';
				?>
			</div>
		</div>
	</div>
	
	<?php echo tommusrhodus_svg_dividers_pluck( 'pipe', '' ); ?>
	
</section>

