<section class="pb-0 pb-5">
	<div class="container">
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