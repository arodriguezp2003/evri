<?php 
	get_header(); 
	
	$term = get_queried_object();
	
	// Documentation Specific Hero Area
	get_template_part( 'inc/content-documentation', 'hero' );
?>

<section>
	<div class="container">
		
		<div class="row justify-content-center">
			<div class="col-lg-10 col-xl-8">
				
				<?php echo get_tommusrhodus_breadcrumbs(); ?>
				
				<h2 class="h1 mt-3 mb-4"><?php echo esc_html( $term->name ); ?></h2>
				
				<?php if ( have_posts() ) : while ( have_posts() ) : the_post(); ?>
					
					<a href="<?php the_permalink(); ?>" class="card card-body hover-shadow-sm">
						
						<?php 
							the_title( '<h4 class="mb-2">', '</h4>' ); 
							
							if( has_excerpt() ){
								echo '<span>'. get_the_excerpt() .'</span>';
							}
						?>
					
						<div class="d-flex justify-content-between align-items-center mt-3">
							
							<div class="d-flex align-items-center">
								
								<?php echo get_avatar( get_the_author_meta( 'ID' ), 48, false, false, array( 'class' => 'avatar' ) ); ?>
						
								<div class="text-small ml-2">
									
									<span class="d-block">
										<?php esc_html_e( 'Written by', 'uptime' ); ?> 
										<?php echo get_the_author_meta( 'display_name' ); ?>
									</span>
									
									<span class="text-muted">
										<?php echo esc_html__( 'Updated ', 'uptime' ) . human_time_diff( get_the_modified_date( 'U', get_the_id() ), current_time( 'timestamp' ) ) . esc_html__( ' ago', 'uptime' ); ?>
									</span>
									
								</div>
								
							</div>

						</div>
						
					</a>
						
				<?php
					endwhile;	
					else : 
						
						get_template_part( 'loop/content', 'none' );
						
					endif;
				?>
				
	        </div>
		</div>
		
		<?php get_template_part( 'inc/content', 'pagination' ); ?>
		
	</div>
</section>
	
<?php get_footer();