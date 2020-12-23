<div class="article-progress" data-sticky="below-nav">
	
	<progress class="reading-position" value="0"></progress>
	
	<div class="article-progress-wrapper">
		<div class="container">
			<div class="row">
				<div class="col py-2">
					<div class="d-flex justify-content-between align-items-center">
						
						<div class="d-flex">
							<div class="text-small text-muted mr-1"><?php esc_html_e( 'Reading:', 'uptime' ); ?></div>
							<?php the_title( '<div class="text-small">', '</div>' ); ?>
						</div>
						
						<?php get_template_part( 'inc/content', 'sharing' ); ?>
						
					</div>
				</div>
			</div>
		</div>
	</div>
	
</div>