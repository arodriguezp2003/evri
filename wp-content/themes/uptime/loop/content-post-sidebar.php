<?php 
	$excerpt_length = ( has_post_thumbnail() ) ? 35 : 60;
	$class          = ( is_sticky() ) ? 'bg-primary-2 text-light' : ''; 
?>

<div id="post-<?php the_ID(); ?>" <?php post_class( 'pr-lg-4' ); ?>>
	<div class="card card-article-wide flex-md-row no-gutters xxx">
	
		<?php if( has_post_thumbnail() ) : ?>
		
			<a href="<?php the_permalink(); ?>" class="col-md-4">
				<?php the_post_thumbnail( 'large', array( 'class' => 'card-img-top' ) ); ?>
			</a>
			
		<?php endif; ?>
		
		<div class="<?php echo esc_attr( $class ); ?> card-body d-flex flex-column col-auto p-4">
		
			<div class="d-flex justify-content-between mb-3">
				<div class="text-small d-flex">
					<?php get_template_part( 'inc/content-post', 'meta' ); ?>
				</div>
			</div>
			
			<?php the_title( '<a href="'. get_permalink() .'"><h3>', '</h3></a>' ); ?>

				<div class="flex-grow-1">
					<p><?php tommusrhodus_get_excerpt( $excerpt_length ); ?> <a href="<?php echo get_permalink(); ?>" class="hover-arrow"><?php echo esc_html_e( 'Continue Reading', 'uptime' ); ?></a></p>
				</div>			
			
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