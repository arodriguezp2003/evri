<?php 
  $categories = get_the_category(); 
?>

<a href="<?php the_permalink(); ?>" class="card card-body overlay border-0 text-light mb-md-0 justify-content-end">
  <div class="position-relative">
    <?php
        if( is_array( $categories ) ){
          foreach( $categories as $cat ){
              echo '<span class="badge badge-primary">'. $cat->name . '</span>';
          }
        }
    ?>
    <div class="my-3">
      <?php if( has_excerpt() ){
        echo '<p>'. get_the_excerpt() .'</p>';
      } ?>
    </div>
    <div class="d-flex align-items-center">
      <?php echo get_avatar( get_the_author_meta( 'ID' ), 48, false, false, array( 'class' => 'avatar avatar-sm mr-2' ) ); ?>        
      <div>
        <div class="flex-shrink-0"><?php esc_html_e( 'by', 'uptime' ); ?> <?php echo get_the_author_meta( 'display_name' ); ?></div>
      </div>
    </div>
  </div>      
  <?php if( has_post_thumbnail() ) : ?>
    <?php the_post_thumbnail( 'large', array( 'class' => 'rounded bg-image' ) ); ?>
  <?php endif; ?>
</a>