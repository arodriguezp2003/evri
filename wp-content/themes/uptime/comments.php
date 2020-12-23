<?php
	$updated_fields = array(
		'author' => '<div class="row"><div class="col-md-6 mb-1"><div class="form-group"><input type="text" class="form-control" id="author" name="author" placeholder="' . esc_attr__( 'Name', 'uptime' ) . '" value="' . esc_attr( $commenter['comment_author'] ) . '" /></div></div>',
		'email'  => '<div class="col-md-6 mb-1"><div class="form-group"><input name="email" class="form-control" type="text" id="email" placeholder="' . esc_attr__( 'Email Address', 'uptime' ) . '" value="' . esc_attr(  $commenter['comment_author_email'] ) . '" /></div></div></div>',
		'url'    => '<div class="form-group"><input name="url" class="form-control" type="text" id="url" placeholder="' . esc_attr__( 'Website', 'uptime' ) . '" value="' . esc_attr(  $commenter['comment_author_url'] ) . '" /></div>'
	);
?>

<section class="has-divider">
	
	<div class="container pt-3">
		<div class="row justify-content-center">
			<div class="col-lg-10 col-xl-8">
				
				<?php get_template_part( 'inc/content', 'sharing' ); ?>
				
				<hr>
				
				<?php if( !post_password_required() && comments_open() || !post_password_required() && get_comments_number() ) : ?>
				
					<h5 class="my-4">
						<?php 
							comments_number( 
								esc_html__( '0 Comments', 'uptime' ), 
								esc_html__( '1 Comment', 'uptime' ), 
								esc_html__( '% Comments', 'uptime' )
							); 
						?>
					</h5>
					
					<ol class="comments">
						<?php 
							$args = array(
								'type'              => 'all',
								'callback'          => 'tommusrhodus_custom_comment'
							);
							
							wp_list_comments( $args ); 
						?>
					</ol>
					
					<?php paginate_comments_links(); ?>
					
					<hr>
					
					<?php 
						comment_form( array(
							'fields'               => apply_filters( 'comment_form_default_fields', $updated_fields ),
							'title_reply_before'   => '<h5 id="reply-title" class="comment-reply-title my-4">',
							'title_reply_after'    => '</h5>',
							'comment_notes_before' => '',
							'comment_notes_after'  => '',
							'class_submit'         => 'btn btn-primary',
							'comment_field'        => '<div class="form-group"><textarea id="comment" class="form-control" name="comment" rows="10" placeholder="'. esc_attr__( 'Comment', 'uptime' ) .'" aria-required="true"></textarea></div>'
						) ); 
					?>
				
				<?php endif; ?>
				
			</div>
		</div>
	</div>
	
	<?php echo tommusrhodus_svg_dividers_pluck( 'ramp', 'bg-primary-alt' ); ?>
	
</section>