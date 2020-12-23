<footer class="pb-5 bg-primary text-light">
	<div class="container">
	
		<div class="row mb-4 justify-content-center centered-footer">
			<div class="col-auto">
				<?php
					if( is_active_sidebar('centered_footer') ){
						dynamic_sidebar('centered_footer');
					}
				?>
			</div>
		</div>
			
		<div class="row justify-content-center mt-5 mb-5">
			<div class="col-auto">
				<?php get_template_part( 'inc/content', 'footer-social' );  ?>
			</div>
		</div>
			
		<div class="row justify-content-center text-center">
			<div class="col-xl-10">
				<?php get_template_part( 'inc/content', 'copyright' );  ?>
			</div>
		</div>
	
	</div>
</footer>