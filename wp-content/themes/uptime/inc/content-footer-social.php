<ul class="nav">
	<?php 
		for ( $i = 0; $i < 6; $i++ ) {
			if( $icon = get_theme_mod( 'footer_social_icon_' . $i ) ){
				echo '<li class="nav-item"><a class="nav-link" href="'. esc_url( get_theme_mod( 'footer_social_url_' . $i ) ) .'" target="_blank">'. tommusrhodus_svg_icons_pluck( $icon, 'icon' ) .'</a></li>';
			}
		}
	?>
</ul>