<?php 
	$additional 	= get_post_meta( $post->ID, '_tommusrhodus_meta_repeat_group', true ); 
	$website_label	= get_post_meta( $post->ID, '_tommusrhodus_porfolio_item_website_label', true ); 
	$website_url 	= get_post_meta( $post->ID, '_tommusrhodus_porfolio_item_website_url', true ); 
?>

<dl class="row">
	
	<?php 
		if( $additional ){
			foreach( $additional as $index => $item ){
				echo '<dt class="col-3 mb-2">';
				if( isset ( $item['_tommusrhodus_the_additional_title'] ) ) {
					echo wp_kses_post($item['_tommusrhodus_the_additional_title']);
				}
				echo '</dt><dd class="col-9 mb-2">';
				if( isset ( $item['_tommusrhodus_the_additional_detail'] ) ) {
					echo wp_kses_post($item['_tommusrhodus_the_additional_detail']);
				}
				echo '</dd>';
			}
		}
		if( $website_label && $website_url ) {
			echo '<dt class="col-3 mb-2">';
			if( isset ( $website_label ) ) {
				echo wp_kses_post( $website_label );
			}
			echo '</dt><dd class="col-9 mb-2">';
			if( isset ( $website_url ) ) {
				$remove = array( 'http://', 'https://', 'mailto:');
				echo '<a href="'. esc_url( $website_url ) .'">'. wp_kses_post( str_replace( $remove,'', $website_url ) ) .'</a>';
			}
			echo '</dd>';			
		}
	?>

</dl>
