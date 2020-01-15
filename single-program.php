<?php get_header(); ?>

<?php while(have_posts()) : 
    the_post(); 
    pageBanner(); ?>


  <div class="container container--narrow page-section">
    <div class="metabox metabox--position-up metabox--with-home-link">
        <p><a class="metabox__blog-home-link" href="<?php echo get_post_type_archive_link('program'); ?>"><i class="fa fa-home" aria-hidden="true"></i> All programs</a> <span class="metabox__main"><?php the_title(); ?></span></p>
    </div>

    <div class="generic-content"><?php the_field('main_body_content'); ?></div>
    
    <?php

    // Query para mostrar los posts mas recientes y relacion entre programas y professor
    $relatedProfessor = new WP_Query(array(
      'posts_per_page'  => -1, // Para mostrar todos los posts
      'post_type' => 'professor',
      'orderby' => 'title', // para texto seria meta_value_text
      'order' => 'ASC',
      'meta_query' => array(
        // A filter
        array(
          'key' => 'related_programs', // The custom field
          'compare' => 'LIKE', // LIKE es si contiene 
          'value' => '"' . get_the_ID() . '"' // Id del current program ejemplo 12 pero hay que ponerlo entre doble comillas
        )
      ),
    ));

    if($relatedProfessor->have_posts()) :
      echo '<hr class="section-break">';
      echo '<h2 class="headline headline--medium">' . get_the_title() . ' Professors</h2>';
     echo '<ul class="professor-cards">';
      while($relatedProfessor->have_posts()) : $relatedProfessor->the_post(); ?>
        <li class="professor-card__list-item">
          <a class="professor-card" href="<?php the_permalink(); ?>">
            <img class="professor-card__image" src="<?php the_post_thumbnail_url('professorLandscape'); ?>" alt="<?php the_title(); ?>">
            <span class="professor-card__name"><?php the_title(); ?></span>
          </a>
        </li>
      <?php endwhile;
      echo '</ul>';
          endif;
          wp_reset_postdata();

        // Query para mostrar los posts mas recientes y relacion entre programas y eventos
          $today = date('Ymd');
          $homepageEvent = new WP_Query(array(
            'posts_per_page'  => 2,
            'post_type' => 'event',
            'meta_key'  => 'event_date', // Nombre del metafield que nos interesa
            'orderby' => 'meta_value_num', // para texto seria meta_value_text
            'order' => 'ASC',
            'meta_query' => array(
              // A filter
              array(
                'key' => 'event_date', // custom field
                'compare' => '>=',
                'value' => $today,
                'type'  => 'numeric'
              ),
              array(
                'key' => 'related_programs', // The custom field
                'compare' => 'LIKE', // LIKE es si contiene 
                'value' => '"' . get_the_ID() . '"' // Id del current program ejemplo 12 pero hay que ponerlo entre doble comillas
              )
            ),
          ));

          if($homepageEvent->have_posts()) {
            echo '<hr class="section-break">';
            echo '<h2 class="headline headline--medium">Upcoming ' . get_the_title() . ' Events</h2>';

         while($homepageEvent->have_posts()) : $homepageEvent->the_post();
            get_template_part('template-parts/content-event');
         endwhile; 
         } 

         wp_reset_postdata();

         $relatedCampus = get_field('related_campus');
         echo '<hr class="section-break">';
         if($relatedCampus) {
          echo '<h2 class="headline headline--medium">'. get_the_title() .' is available At this campuses:</h2>';
          echo '<ul class="min-list link-list">';
          foreach($relatedCampus as $campus) {
            ?>
            <li><a href="<?php echo get_the_permalink($campus); ?>"><?php echo get_the_title($campus); ?></a></li>
            <?php
          }
          echo '</ul>';
         }
         ?>

  </div>
  
<?php endwhile; ?>


<?php get_footer(); ?>