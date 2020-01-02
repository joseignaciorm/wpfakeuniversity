<?php

add_action('rest_api_init', 'universityRegisterSearch');
function universityRegisterSearch() {
    // register_rest_route(a, b, c) / http://localhost:3000/wp-json/university/v1/search
    // Primer parámetro: Namespace => wp-json/wp/v2/ wp es el core de wordpress por defecto
    // Segundo: The route is la última parte de la url. Para esta ruta vamos a poner search
    // Tercer parámetro: Array que describe que va a pasar cuando la url sea visitada
    register_rest_route('university/v1', 'search', array(
        // Queremos leer o cargar data. method GET lo sustituimos por la constante de wp
        'methods' => WP_REST_SERVER::READABLE, // Constante de wp 
        'callback' => 'universitySearchResult', // Función que retorna la data JSON que queremos mostrar en el front
    ));
}

function universitySearchResult($data) {
    $mainQuery = new WP_Query(array(
        'post_type' => array('post', 'page', 'professor', 'program', 'campus', 'event'),
        's' => sanitize_text_field($data['term']) // s almacena en un array los datos enviados desde la ruta
    ));

    $results = array(
        'generalInfo' => array(), // Para los posts y pages
        'professors' => array(),
        'programs' => array(),
        'events' => array(),
        'campuses' => array()
    );

    while( $mainQuery->have_posts() ) {
        $mainQuery->the_post();
        if( get_post_type() == 'post' OR get_post_type() == 'page' ) {
            array_push($results['generalInfo'], array(
                'title' => get_the_title(),
                'permalink' => get_the_permalink(),
                'postType'  => get_post_type(),
                'authorName' => get_the_author()
            ));
        }
        if( get_post_type() == 'professor' ) {
            array_push($results['professors'], array(
                'title' => get_the_title(),
                'permalink' => get_the_permalink(),
                'image' => get_the_post_thumbnail_url(0, 'professorLandscape') 
            ));
        }
        if( get_post_type() == 'program' ) {
            array_push($results['programs'], array(
                'title' => get_the_title(),
                'permalink' => get_the_permalink(),
                'id' => get_the_ID()
            ));
        }
        if( get_post_type() == 'campus' ) {
            array_push($results['campuses'], array(
                'title' => get_the_title(),
                'permalink' => get_the_permalink()
            ));
        }
        if( get_post_type() == 'event' ) {
            $eventDate = new DateTime(get_field('event_date'));
            $description = null;
            if(has_excerpt()) {
                $description = get_the_excerpt();
            } else {
                $description = wp_trim_words(get_the_content(), 18);
            } 

            array_push($results['events'], array(
                'title' => get_the_title(),
                'permalink' => get_the_permalink(),
                'month' => $eventDate->format('M'),
                'day'   => $eventDate->format('d'),
                'desription' => $description
            ));
        }
    } // Final loop while

    if($results['programs']) :
        $programsMetaQuery = array('relation' => 'OR');

    foreach($results['programs'] as $item) {
        array_push($programsMetaQuery, array(
            'key' => 'related_programs',
            'compare' => 'like',
            'value' => '"' . $item['id'] . '"'
        ));
    }

    $programRelationshipQuery = new WP_Query(array(
        'post_type' => array('professor', 'event'),
        'meta_query' => $programsMetaQuery
    ));
    while($programRelationshipQuery->have_posts()) {
        $programRelationshipQuery->the_post();

        if( get_post_type() == 'event' ) {
            $eventDate = new DateTime(get_field('event_date'));
            $description = null;
            if(has_excerpt()) {
                $description = get_the_excerpt();
            } else {
                $description = wp_trim_words(get_the_content(), 18);
            } 

            array_push($results['events'], array(
                'title' => get_the_title(),
                'permalink' => get_the_permalink(),
                'month' => $eventDate->format('M'),
                'day'   => $eventDate->format('d'),
                'desription' => $description
            ));
        }

        if( get_post_type() == 'professor' ) {
            array_push($results['professors'], array(
                'title' => get_the_title(),
                'permalink' => get_the_permalink(),
                'image' => get_the_post_thumbnail_url(0, 'professorLandscape') 
            ));
        }

    }
    // array_unique(); para detectar y eliminar arrays duplicados
    $results['professors'] = array_values( array_unique($results['professors'], SORT_REGULAR) );
    $results['events'] = array_values( array_unique($results['events'], SORT_REGULAR) );

    endif;

    return $results;
}