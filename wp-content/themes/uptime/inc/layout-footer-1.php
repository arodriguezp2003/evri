<footer class="pb-4 bg-primary-3 text-light" id="footer">
	<div class="container">
		
		<?php get_template_part( 'inc/content', 'footer-cta' );  ?>
		
		<div class="row mb-5">
			<?php
				if( is_active_sidebar('default_footer_1') && !( is_active_sidebar('default_footer_2') ) && !( is_active_sidebar('default_footer_3') ) ){
					echo '<div class="col">';
						dynamic_sidebar('default_footer_1');
					echo '</div>';
				}
					
				if( is_active_sidebar('default_footer_2') && !( is_active_sidebar('default_footer_3') ) && !( is_active_sidebar('default_footer_4') ) ){
					echo '<div class="col-6">';
						dynamic_sidebar('default_footer_1');
					echo '</div><div class="col-6">';
						dynamic_sidebar('default_footer_2');
					echo '</div><div class="clear"></div>';
				}
					
				if( is_active_sidebar('default_footer_3') ){
					echo '<div class="col-6 col-lg-3 col-xl-2">';
						dynamic_sidebar('default_footer_1');
					echo '</div><div class="col-6 col-lg">';
						dynamic_sidebar('default_footer_2');
					echo '</div><div class="col-lg-5 col-xl-4 mt-3 mt-lg-0">';
						dynamic_sidebar('default_footer_3');
					echo '</div><div class="clear"></div>';
				}
			?>
		</div>
		
		<div class="row justify-content-center mb-2">
			<div class="col-auto">
				<?php get_template_part( 'inc/content', 'footer-social' );  ?>
			</div>
		</div>
		
		<div class="row justify-content-center">
			<div class="col col-md-auto text-center">
				<?php get_template_part( 'inc/content', 'copyright' );  ?>
	        </div>
		</div>

	</div>
</footer>