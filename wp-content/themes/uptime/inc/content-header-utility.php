<section class="py-2 bg-primary-3">
	<div class="container">
		<div class="row justify-content-between align-items-center">
			
			<div class="col-auto text-small text-light">
				<span>
					<?php echo wp_kses_post( get_theme_mod( 'utility_bar_left' ) ); ?>
				</span>
			</div>
			
			<div class="col-auto text-small text-light">
				<span>
					<?php echo wp_kses_post( get_theme_mod( 'utility_bar_right' ) ); ?>
				</span>
			</div>
			
		</div>
	</div>
</section>