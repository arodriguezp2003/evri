<?php 
	$title    = get_theme_mod( 'documentation_archive_title', 'Search the Knowledgebase' ); 
	$searches = get_theme_mod( 'documentation_archive_popular_searches' ); 
?>

<section class="bg-primary-3 text-light has-divider" data-overlay>
	
	<?php if( $image = get_theme_mod( 'documentation_archive_background_image' ) ) : ?>
		<img src="<?php echo esc_url( $image ); ?>" alt="Image" class="bg-image opacity-50">
	<?php endif; ?>
	
	<div class="container">
		<div class="row justify-content-center">
			<div class="col-lg-10 col-xl-8">
				
				<h1 class="h2 mb-3"><?php echo wp_kses_post( $title ); ?></h1>
				
				<form class="search-form" method="get" id="searchform" action="<?php echo esc_url( home_url( '/' ) ); ?>">
					<div class="input-group input-group-lg mb-3">
						<div class="input-group-prepend">
							<span class="input-group-text" id="basic-addon-1">
								<?php echo tommusrhodus_svg_icons_pluck( 'Search' ); ?>
							</span>
						</div>						
       					<input type="hidden" name="post_type" value="documentation" />
						<input type="text" name="s" class="form-control" placeholder="<?php esc_attr_e( 'Search for articles', 'uptime' ); ?>" aria-label="Search" aria-describedby="basic-addon-1">
					</div>
				</form>

				<?php if( $searches ) : ?>
				
					<?php $popular_searches = explode( ',', $searches ); ?>	
					
					<div class="d-flex text-small">
						
						<span class="text-muted"><?php esc_html_e( 'Popular:', 'uptime' ); ?></span>
						
						<ul class="list-unstyled d-flex ml-2 flex-wrap">
							<?php 
								if( is_array( $popular_searches ) ){
									foreach( $popular_searches as $popular_search ) {
										echo '<li class="mx-1"><a href="' . esc_url( home_url( '/?post_type=documentation&s=' ) ) . str_replace( ' ', '+', trim( $popular_search ) ) . '" class="text-white">'. $popular_search .'</a></li>';
									} 
								}
							?>
						</ul>
						
					</div>
					
				<?php endif; ?>
				
			</div>
		</div>
	</div>
	
	<?php echo tommusrhodus_svg_dividers_pluck( 'pipe', '' ); ?>

</section>