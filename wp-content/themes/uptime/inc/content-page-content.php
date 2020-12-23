<section class="pt-0" data-reading-position>
	<div class="container">
		<div class="row justify-content-center">
			<div class="col-lg-10 col-xl-8">
				
				<span class="text-muted">
					<?php 
						printf( 
							esc_html_x( '%s ago', '%s = human-readable time difference', 'uptime' ), 
							human_time_diff( 
								get_the_time( 'U' ), 
								current_time( 'timestamp' ) 
							) 
						); 
					?>
				</span>
				
				<?php 
					the_title( '<h1 class="my-2">', '</h1>' ); 
					
					if( has_excerpt() ){
						echo '<div>';
						the_excerpt();
						echo '</div>';
					}
				?>
				
				<hr>
				
				<article class="article">
					<?php
						the_content();
						wp_link_pages();
					?>
				</article>
				
			</div>
		</div>
	</div>
</section>