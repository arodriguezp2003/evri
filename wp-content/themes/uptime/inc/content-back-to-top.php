<?php
	if( 'no' == get_theme_mod( 'show_back_to_top', 'yes' ) ){
		return false;
	}
?>

<a href="#" class="btn back-to-top btn-primary btn-round" data-smooth-scroll data-aos="fade-up" data-aos-offset="2000" data-aos-mirror="true" data-aos-once="false">
	<?php echo tommusrhodus_svg_icons_pluck( 'Arrow Up', 'icon' ); ?>
</a>