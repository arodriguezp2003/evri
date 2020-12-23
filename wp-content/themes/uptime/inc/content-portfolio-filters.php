<?php
	// If we're viewing a taxonomy page, Exit
	if( is_tax() ){
		return false;
	}

	if( isset( $wp_query->show_filters ) && !( 'show' == $wp_query->show_filters ) ){
		return false;
	}
	
	// Get each Portfolio Category
	$terms = get_terms( array(
		'taxonomy' => 'portfolio_category'
	) );
	
	// Exit if there's no portfolio categories
	if( !$terms ){
		return false;
	}
?>

<div class="row justify-content-center mb-4">
	<div class="col col-md-auto">
		<ul data-isotope-filters data-isotope-id="projects" class="nav mb-3">
			
			<li class="nav-item">
				<a href="#" class="nav-link active" data-filter="&ast;"><?php esc_html_e( 'All', 'uptime' ); ?></a>
			</li>
			
			<?php
				if( is_array( $terms ) ){
					foreach( $terms as $term ){
						echo '<li class="nav-item"><a href="#" class="nav-link" data-filter="'. esc_attr( $term->name ) .'">'. esc_html( $term->name ) .'</a></li>';
					}
				}
			?>

		</ul>
	</div>
</div>
