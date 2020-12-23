<section class="bg-dark text-light overlay" data-overlay>
	
	<img src="<?php the_post_thumbnail_url( 'full' ); ?>" alt="Image" class="bg-image opacity-50">
	
	<div class="container pt-6">
		<div class="row justify-content-center">
			<div class="col-lg-10 col-xl-8">
				<div class="d-flex justify-content-between align-items-center mb-3">
					<?php 
						echo get_tommusrhodus_breadcrumbs();
					?>
				</div>
				
				<?php 
					the_title( '<h1>', '</h1>' ); 
					get_template_part( 'inc/content-post', 'author' );
				?>
			</div>
		</div>
	</div>
	
</section>