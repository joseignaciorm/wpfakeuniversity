<?php get_header() ?>
  <div class="page-banner">
    <div class="page-banner__bg-image" style="background-image: url(<?php echo get_theme_file_uri('/images/ocean.jpg'); ?>);">
    </div>
    <div class="page-banner__content container container--narrow">

      <h1 class="page-banner__title">
        Past events
        <?php 
        /* if(is_category()) {
            echo single_cat_title();
        } if(is_author()) {
            echo "Posts by "; the_author();
        } */
        ?>

      </h1>

      <div class="page-banner__intro">
        <p>A recap of our past events.</p>
      </div>
    </div>  
  </div>

  <div class="container container--narrow page-section">
  <?php 
     // Query para mostrar los posts antiguos
     $today = date('Ymd');
     $pastEvents = new WP_Query(array(
      'posts_per_page' => 1,
        //'paged' => get_query_var('paged', 1),
       'post_type' => 'event',
       'meta_key'  => 'event_date', // Nombre del metafield que nos interesa
       'orderby' => 'meta_value_num', // para texto seria meta_value_text
       'order' => 'ASC',
       'meta_query' => array(
         array(
           'key' => 'event_date', // custom field
           'compare' => '<',
           'value' => $today,
           'type'  => 'numeric'
         )
       ),
     ));

    while( $pastEvents->have_posts() ) :
        $pastEvents->the_post(); ?>
        
        <div class="event-summary">
          <a class="event-summary__date t-center" href="#">
            <span class="event-summary__month"><?php 
              $eventDate = new DateTime(get_field('event_date'));
              echo $eventDate->format('M');
             ?></span>
            <span class="event-summary__day"><?php echo $eventDate->format('d'); ?></span>  
          </a>
          <div class="event-summary__content">
            <h5 class="event-summary__title headline headline--tiny"><a href="<?php the_permalink(); ?>"><?php the_title(); ?></a></h5>
            <p><?php echo wp_trim_words(get_the_content(), 18); ?><a href="<?php the_permalink(); ?>" class="nu gray">Learn more</a></p>
          </div>
        </div>

    <?php  endwhile;  
     # En general la paginación en WP solo funciona fuera del box con la query por defecto el la current url
     # EN custom queries hay que añadir un hook de array como parámetro 'total' => $pastEvents->max_num_pages
     # 2. En la query hay que pasar el asociativo 'paged' => x 
     # get_query_var() Se obtiene toda información de la url actual
      echo paginate_links(array(
          'total' => $pastEvents->max_num_pages
      ));
    ?>
  </div>

<?php get_footer() ?>