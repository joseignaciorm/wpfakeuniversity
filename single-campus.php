<?php get_header(); ?>

<?php while(have_posts()) : 
    the_post(); 
    pageBanner(); ?>


  <div class="container container--narrow page-section">
    <div class="metabox metabox--position-up metabox--with-home-link">
        <p><a class="metabox__blog-home-link" href="<?php echo get_post_type_archive_link('campus'); ?>"><i class="fa fa-home" aria-hidden="true"></i> All campuses</a> <span class="metabox__main"><?php the_title(); ?></span></p>
    </div>

    <div class="generic-content"><?php the_content(); ?></div>
    <?php $mapLocation = get_field('map_location'); ?>

    <div class="acf-map">
          <div class="marker" data-lat="<?php echo $mapLocation['lat'] ?>" data-lng="<?php echo $mapLocation['lng']; ?>">
            <h3><?php the_title(); ?></h3>
            <?php echo $mapLocation['address']; ?>
          </div>
    </div>
    
    <?php

    // Query para mostrar los posts mas recientes y relacion entre programas y professor
    $relatedPrograms = new WP_Query(array(
      'posts_per_page'  => -1, // Para mostrar todos los posts
      'post_type' => 'program',
      'orderby' => 'title', // para texto seria meta_value_text
      'order' => 'ASC',
      'meta_query' => array(
        // A filter
        array(
          'key' => 'related_campus', // The custom field
          'compare' => 'LIKE', // LIKE es si contiene 
          'value' => '"' . get_the_ID() . '"' // Id del current program ejemplo 12 pero hay que ponerlo entre doble comillas
        )
      ),
    ));

    if($relatedPrograms->have_posts()) :
      echo '<hr class="section-break">';
      echo '<h2 class="headline headline--medium">Programs available At this campus</h2>';
      echo '<ul class="min-list link-list">';
      while($relatedPrograms->have_posts()) : $relatedPrograms->the_post(); ?>
        <li>
          <a href="<?php the_permalink(); ?>"><?php the_title(); ?></a>
        </li>
      <?php endwhile;
      echo '</ul>';
          endif;
          wp_reset_postdata();

         ?>

  </div>
  
<?php endwhile; ?>


<?php get_footer(); ?>