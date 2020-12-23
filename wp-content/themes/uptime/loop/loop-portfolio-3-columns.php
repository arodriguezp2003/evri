<?php get_template_part( 'inc/content-portfolio', 'filters' ); ?>

<div class="row" data-isotope-collection data-isotope-id="projects">	
	<?php if ( have_posts() ) : while ( have_posts() ) : the_post(); ?>
		
		<div id="portfolio-<?php the_ID(); ?>" <?php post_class( 'col-sm-6 col-lg-4 mb-4' ); ?> data-isotope-item data-category="<?php echo esc_attr( TommusRhodus_Framework()->the_terms( $post->ID, 'portfolio_category', 'name', ' ' ) ); ?>">
			<a href="<?php the_permalink(); ?>">
				<?php if( has_post_thumbnail() ) : ?>
					<?php the_post_thumbnail( 'large', array( 'class' => 'rounded mb-3' ) ); ?>
				<?php endif; ?>
				<?php the_title( '<h4 class="mb-1">', '</h4>' ); ?>
				<div class="text-small text-muted"><?php echo esc_attr( TommusRhodus_Framework()->the_terms( $post->ID, 'portfolio_category', 'name', ' ' ) ); ?></div>
			</a>
		</div>
			
	<?php
		endwhile;	
		else : 
			
			get_template_part( 'loop/content', 'none' );
			
		endif;
	?>
</div>