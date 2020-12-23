<?php 
  if(!( 'post' == get_post_type() )){
    return false;
  }
  $terms = get_the_terms( $post->ID , 'category', 'string' );
  
  if(!( is_array( $terms ) )){
    return false;
  }
  
  $term_ids = array_values( wp_list_pluck( $terms, 'term_id' ) );
  
  $related_query = new WP_Query( 
    array(
      'post_type' => 'post',
      'tax_query' => array(
        array(
          'taxonomy' => 'category',
          'field'    => 'id',
          'terms'    => $term_ids,
          'operator' => 'IN' //Or 'AND' or 'NOT IN'
        )
      ),
      'posts_per_page'      => 3,
      'ignore_sticky_posts' => 1,
      'orderby'             => 'rand',
      'post__not_in'        => array( get_the_id() )
    ) 
  );
  
  if( $related_query->found_posts == 0 ){
    return false;
  }
?> 

<section class="bg-primary-alt">
  	<div class="container">
    	<div class="row mb-4">
      		<div class="col">
        		<h3 class="h2"><?php esc_html_e( 'Related Stories', 'uptime' ); ?></h3>
      		</div>
		</div>

    	<div class="row related-post-items">
	      	<?php 

	        if ( $related_query->have_posts() ) : while ( $related_query->have_posts() ) : $related_query->the_post();
	          
	          	get_template_part( 'loop/content-post-card', get_post_format() );
	          
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