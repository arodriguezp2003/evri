<?php 
  if(!( 'documentation' == get_post_type() )){
    return false;
  }
  $terms = get_the_terms( $post->ID , 'documentation_category', 'string' );
  
  if(!( is_array( $terms ) )){
    return false;
  }
  
  $term_ids = array_values( wp_list_pluck( $terms, 'term_id' ) );
  
  $related_query = new WP_Query( 
    array(
      'post_type' => 'documentation',
      'tax_query' => array(
        array(
          'taxonomy' => 'documentation_category',
          'field'    => 'id',
          'terms'    => $term_ids,
          'operator' => 'IN' //Or 'AND' or 'NOT IN'
        )
      ),
      'posts_per_page'      => 2,
      'ignore_sticky_posts' => 1,
      'orderby'             => 'rand',
      'post__not_in'        => array( $post->ID )
    ) 
  );
  
  if( $related_query->found_posts == 0 ){
    return false;
  }
?> 

<section class="bg-primary-alt">
  <div class="container">
    <div class="row mb-4">
      <div class="col text-center">
        <h3 class="h2"><?php esc_html_e( 'Related Stories', 'uptime' ); ?></h3>
      </div>
    </div>
    <div class="row justify-content-center">
      <?php 
        if ( $related_query->have_posts() ) : while ( $related_query->have_posts() ) : $related_query->the_post(); ?>

        	<div class="col-lg-10 col-xl-8">
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
						
						<?php get_template_part( 'inc/content', 'like-badge' ); ?>

					</div>
				</a>
			</div>

          <?php
          
        endwhile; 
        else : 
          
          /**
           * Display no posts message if none are found.
           */
          get_template_part( 'loop/content', 'none' );
          
        endif;
        wp_reset_postdata();
      ?>
    </div>
  </div>
</section>
