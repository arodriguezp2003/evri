<?php
	$label  = get_theme_mod( 'header_button_label', 'Purchase Now' );
	$url    = get_theme_mod( 'header_button_url' ) ;
	$label2 = get_theme_mod( 'header_button_label_2' );
	$url2   = get_theme_mod( 'header_button_url_2' ) ;
?>

<div class="container">

	<?php the_custom_logo(); ?>
	
	<button class="navbar-toggler" type="button" data-toggle="collapse" data-target=".navbar-collapse" aria-expanded="false" aria-label="Toggle navigation">
		<svg class="icon" width="24" height="24" viewBox="0 0 24 24" fill="none" xmlns="http://www.w3.org/2000/svg">
			<path d="M3 17C3 17.5523 3.44772 18 4 18H20C20.5523 18 21 17.5523 21 17V17C21 16.4477 20.5523 16 20 16H4C3.44772 16 3 16.4477 3 17V17ZM3 12C3 12.5523 3.44772 13 4 13H20C20.5523 13 21 12.5523 21 12V12C21 11.4477 20.5523 11 20 11H4C3.44772 11 3 11.4477 3 12V12ZM4 6C3.44772 6 3 6.44772 3 7V7C3 7.55228 3.44772 8 4 8H20C20.5523 8 21 7.55228 21 7V7C21 6.44772 20.5523 6 20 6H4Z"
              fill="#212529" />
		</svg>
	</button>
	
	<div class="collapse navbar-collapse justify-content-end">
		
		<div class="py-2 py-lg-0">
			<?php
				if ( has_nav_menu( 'primary' ) ){
					
					wp_nav_menu( 
						array(
						    'theme_location'    => 'primary',
						    'depth'             => 4,
						    'container'         => false,
						    'container_class'   => false,
						    'menu_class'        => 'navbar-nav',
						    'menu_id'           => false,
						    'items_wrap'        => '<ul class="%2$s">%3$s</ul>',
						    'walker'            => new WP_Bootstrap_Navwalker()
						)
					);
					
				}
			?>
		</div>

		<?php if( $label && $url ) : ?>
			<a href="<?php echo esc_url( $url ); ?>" class="btn btn-white ml-lg-3"><?php echo wp_kses_post( $label ); ?></a>
		<?php endif; ?>
		
		<?php if( $label2 && $url2 ) : ?>
			<a href="<?php echo esc_url( $url2 ); ?>" class="btn btn-primary ml-lg-1"><?php echo wp_kses_post( $label2 ); ?></a>
		<?php endif; ?>
	
	</div>
	
</div>