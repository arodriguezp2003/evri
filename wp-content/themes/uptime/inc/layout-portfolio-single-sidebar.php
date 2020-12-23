<?php $header_images = get_post_meta($post->ID, '_tommusrhodus_portfolio_item_images', 1); ?>

<section class="pt-0 pb-0">
	<div class="container-fluid px-lg-0">
		<div class="row no-gutters">
			
			<div class="col-xl-4 pt-5 order-lg-2 mb-5">
				<div class="row justify-content-center sticky-top no-gutters">
					<div class="col-xl-10">
						
						<div class="mb-4">
							<?php 
								the_title( '<h1>', '</h1>' ); 
								the_content();
								wp_link_pages();
							?>
						</div>

						<?php 
							get_template_part( 'inc/content-portfolio', 'meta' ); 
							get_template_part( 'inc/content', 'sharing' ); 
						?>
						
					</div>
				</div>
			</div>
			
			<div class="col-xl-8 order-lg-1">
				<?php 
					if( is_array( $header_images ) ){
						foreach( $header_images as $id => $content ){
							echo wp_get_attachment_image( $id, 'full' );
						}
					}
				?>
			</div>
			
		</div>
	</div>
</section>