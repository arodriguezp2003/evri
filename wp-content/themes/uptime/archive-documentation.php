<?php 
	get_header(); 
	
	// Documentation Specific Hero Area
	get_template_part( 'inc/content-documentation', 'hero' );
	
	// Get each Documentation Category
	$terms = get_terms( array(
		'taxonomy'   => 'documentation_category',
		'hide_empty' => false
	) );
	
	if( is_array( $terms ) ) :
?>

<section>
	<div class="container">
		<div class="row justify-content-center">
			<div class="col-lg-10 col-xl-8">
				
				<?php foreach( $terms as $term ) : ?>
					
					<?php
						$date    = false;
						$authors = array();
						
						if( $term->count > 0 ){
						
							$term_posts = get_posts( array(
								'posts_per_page' => '-1',
								'post_type'      => 'documentation',
								'tax_query'      => array(
									array(
										'taxonomy' => 'documentation_category',
										'field'    => 'ID',
										'terms'    => $term->term_id
									)
								)
							) );
							
							if( is_array( $term_posts ) ){
								
								$date = $term_posts[0];
								
								// Create an array of post authors in this category
								foreach( $term_posts as $term_post ){
									$authors[$term_post->post_author] = $term_post->post_author;
								}
								
							}
						
						}

						$category_icon = get_term_meta( $term->term_id, '_tommusrhodus_documentation_category_icon', true );
						
					?>
					
					<a href="<?php echo get_term_link( $term ); ?>" class="card card-body flex-row align-items-center hover-shadow-sm">
						
						<div class="icon-round icon-round-lg bg-primary mx-md-4">
							<?php
								if( array_key_exists( $category_icon, tommusrhodus_get_svg_icons() ) ) {
									echo tommusrhodus_svg_icons_pluck( $category_icon, "icon bg-primary" );
								} else {
									echo tommusrhodus_svg_icons_pluck( 'Thunder-move', "icon bg-primary" );
								}
							?>
						</div>
						
						<div class="pl-4">
							
							<h3 class="mb-1"><?php echo esc_html( $term->name ); ?></h3>
							<span><?php echo esc_html( $term->description ); ?></span>
							
							<div class="d-flex align-items-center mt-3">
								
								<ul class="avatars mr-2">
									<?php
										foreach( $authors as $id => $author ){
											echo '<li>'. get_avatar( $id, 48, false, false, array( 'class' => 'avatar' ) ) .'</li>';
										}
									?>
								</ul>
								
								<div class="text-small">
									
									<span class="d-block">
										<?php echo esc_html( $term->count ); ?> 
										<?php esc_html_e( 'Articles', 'uptime' ); ?>
									</span>
									
									<?php
										if( $date ){
											echo '<span class="text-muted">';
											printf( esc_html_x( '%s ago', '%s = human-readable time difference', 'uptime' ), human_time_diff( get_the_modified_date( 'U', $date ), current_time( 'timestamp' ) ) );
											echo '</span>';
										}
									?>
									
								</div>
								
							</div>
							
						</div>
						
					</a>
				
				<?php endforeach; ?>
			
			</div>
		</div>
	</div>
</section>

<?php 
	endif;
	
	get_footer();