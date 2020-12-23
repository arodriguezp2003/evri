<?php
	$subtitle = get_post_meta( $post->ID, '_tommusrhodus_porfolio_item_subtitle', 1 );
	$header_images = get_post_meta($post->ID, '_tommusrhodus_portfolio_item_images', 1); 
?>

<section class="bg-primary-3 min-vh-80 overlay text-light d-flex align-items-end py-5 jarallax" data-overlay data-jarallax data-speed="0.2">
	
	<?php the_post_thumbnail( 'large', array( 'class' => 'jarallax-img' ) ); ?>
	
	<div class="container">
		<div class="row">
			<div class="col">
				<?php 
					the_title( '<h1 class="display-4 mb-1">', '</h1>' ); 
					if( $subtitle ) {
						echo '<span class="lead">'. $subtitle .'</span>';
					}
				?>
			</div>
		</div>
	</div>
	
</section>

<section>
	<div class="container">
		<div class="row">
			
			<div class="col-md-4 mb-4 mb-md-0">
				<?php 
					get_template_part( 'inc/content-portfolio', 'meta' ); 
					get_template_part( 'inc/content', 'sharing' ); 
				?>
			</div>
			
			<div class="col">
				<div class="row justify-content-center">
					<div class="col-lg-11">
						<?php
							the_content();
							wp_link_pages();
						?>
					</div>
				</div>
			</div>
			
		</div>
	</div>
</section>

<section class="pt-0">
	<div class="container">
		<div class="row">
			<div class="col">
				<?php 
					
					if( is_array($header_images) ) : ?>

						<div data-flickity='{ "imagesLoaded": true, "wrapAround": true }' class="mb-5">

							<?php foreach( $header_images as $id => $content ){
								echo '<div class="carousel-cell">'. wp_get_attachment_image($id, 'full') .'</div>';
							} ?>

						</div>
					
					<?php
					endif; 
				?>
			</div>
		</div>
	</div>
</section>