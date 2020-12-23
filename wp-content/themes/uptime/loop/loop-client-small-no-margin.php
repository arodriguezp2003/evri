<ul class="d-flex flex-wrap justify-content-center list-unstyled">
	<?php if ( have_posts() ) : while ( have_posts() ) : the_post(); ?>
		
		<?php
			$url    = get_post_meta( $post->ID, '_tommusrhodus_client_url', 1 );
			$before = ( $url ) ? '<a href="'.  esc_url( $url ) .'" target="_blank" rel="nofollow">' : false;
			$after  = ( $url ) ? '</a>' : false;
		?>
		
		<li class="mx-3" data-aos="fade-up" data-aos-delay="<?php echo esc_attr( $wp_query->current_post + 1 ); ?>00">
			<?php 
				echo $before;
				the_post_thumbnail( 'large', array( 'class' => 'icon icon-md opacity-20' ) ); 
				echo $after;
			?>
		</li>
			
	<?php
		endwhile;	
		else : 
			
			get_template_part( 'loop/content', 'none' );
			
		endif;
	?>
</ul>