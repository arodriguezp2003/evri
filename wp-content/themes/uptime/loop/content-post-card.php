<div id="post-<?php the_ID(); ?>" <?php post_class( 'col-md-6 col-lg-4 d-flex' ); ?> data-aos="fade-up" data-aos-delay="<?php echo esc_attr( $wp_query->current_post + 1 ); ?>00">
	<div class="card o-hidden">
	
		<?php if( has_post_thumbnail() ) : ?>
		
			<a href="<?php the_permalink(); ?>">
				<?php the_post_thumbnail( 'large', array( 'class' => 'card-img-top' ) ); ?>
			</a>
		
		<?php endif; ?>
		
		<div class="card-body d-flex flex-column">
			
			<div class="d-flex justify-content-between mb-3">
				<div class="text-small d-flex">
					<?php get_template_part( 'inc/content-post', 'meta' ); ?>
				</div>
			</div>
		
			<?php 
				the_title( '<a href="'. get_permalink() .'"><h4 class="card-title mb-3">', '</h4></a>' );  
			
				if( has_excerpt() ){
					echo '<p class="flex-grow-1">'. get_the_excerpt() .'</p>';
				} else {
					echo '<div class="flex-grow-1"></div>';
				}
			?>
		
			<div class="d-flex align-items-center mt-3">
				
				<?php echo get_avatar( get_the_author_meta( 'ID' ), 48, false, false, array( 'class' => 'avatar avatar-sm' ) ); ?>
				
				<div class="ml-1">
					<span class="text-small text-muted"><?php esc_html_e( 'by', 'uptime' ); ?></span>
					<small><?php the_author_posts_link(); ?></small>
				</div>
				
			</div>
		
		</div>
	
	</div>
</div>