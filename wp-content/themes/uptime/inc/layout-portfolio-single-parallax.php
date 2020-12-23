<?php
	$subtitle = get_post_meta( $post->ID, '_tommusrhodus_porfolio_item_subtitle', 1 );
	$header_images = get_post_meta($post->ID, '_tommusrhodus_portfolio_item_images', 1); 
?>

<section class="bg-primary-3 min-vh-100 jarallax d-flex align-items-center text-light" data-jarallax data-speed="0.7">
	<?php the_post_thumbnail( 'full', array( 'class' => 'jarallax-img' ) ); ?>
	
	<div class="container">
		<div class="row text-center">
			<div class="col">
				<?php
					the_title( '<h1 class="display-2 mb-0">', '</h1>' );
					if( $subtitle ) {
						echo '<span class="lead">'. $subtitle .'</span>';
					}
				?>
	        </div>
		</div>
	</div>
</section>

<?php 
	
if( is_array($header_images) ) :
	foreach( $header_images as $id => $content ){

		$caption = wp_get_attachment_caption( $id );
		echo '
			<section class="bg-primary-3 min-vh-100 jarallax d-flex align-items-center text-light" data-overlay data-jarallax data-speed="0.2">
				'. wp_get_attachment_image( $id, 'full', 0, array( 'class' => 'jarallax-img' ) ) .'';

				if( $caption ) {

					echo '
						<div class="container">
							<div class="row justify-content-center text-center" data-aos="fade-up">
								'. do_shortcode( $caption ) .'
							</div>
						</div>
					';

				}

				echo '
			</section>';
	} 
	endif; 
?>
