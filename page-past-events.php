<?php get_header() ?>

<?php pageBanner(array(
  'title' => 'Past events',
  'subtitle' => 'A recap of our past events.'
)) ?>

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
        $pastEvents->the_post(); 
        get_template_part('template-parts/content-event');
    endwhile;  
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