<?php

  require get_theme_file_path('/inc/search-route.php');

// Inicializando WordPress Rest API
function university_custom_rest() {
  // Registro de JSON datos custom field named author.
  // register_rest_field('the custom type name', 'Wherever name of custom field we want', 'array that describehow we want to manage that field')
  register_rest_field('post', 'authorName', array(
    'get_callback' => function () {return get_the_author();}
  ));
}
add_action('rest_api_init', 'university_custom_rest');

// Función para el page banner dinámico
function pageBanner($args = null) {
  if(!$args['title']) {
    $args['title'] = get_the_title();
  }

  if(!$args['subtitle']) {
    $args['subtitle'] = get_field('page_banner_subtitle');
  }

  if (!is_wp_error($args['photo']) && !isset($args['photo'])) {
    if(get_field('page_banner_background_image')) {
      $args['photo'] = get_field('page_banner_background_image')['sizes']['pageBanner'];
    } else {
      $args['photo'] = get_theme_file_uri('/images/ocean.jpg');
    }
 }

  ?>
  <div class="page-banner">
    <div class="page-banner__bg-image" style="background-image: url(<?php 
      //echo get_theme_file_uri('/images/ocean.jpg');
      //$pageBannerImage = get_field('page_banner_background_image'); 
      //echo $pageBannerImage['url'] // get_field() retorna un array
      // echo $pageBannerImage['sizes']['pageBanner']; // Este es el tamaño definido en add_image_size('pageBanner', 1500, 350, true); del function
      echo $args['photo'];?>);">
    </div>
    <div class="page-banner__content container container--narrow">
      <h1 class="page-banner__title"><?php echo $args['title'] ?></h1>
      <div class="page-banner__intro">
        <p><?php echo $args['subtitle']; ?></p>
      </div>
    </div>
  </div>
  <?php 
}


function university_files() {
  # Función microtime() para asegurarnos que el navegador cargue la mas reciente actualizaciòn o modificación del file. Solo se recomiendo colocarlo en desarrollo. 
  wp_enqueue_script('google-map', 'https://maps.googleapis.com/maps/api/js?key=AIzaSyB-sXN3XlnVFt3335bKi0OlN9WuLECZkuE', null, microtime(), true);
  wp_enqueue_script('main-university-js', get_theme_file_uri('/js/scripts-bundled.js'), NULL, microtime(), true);
  wp_enqueue_style('custom-google-fonts', '//fonts.googleapis.com/css?family=Roboto+Condensed:300,300i,400,400i,700,700i|Roboto:100,300,400,400i,700,700i');
  wp_enqueue_style('font-awesome', '//maxcdn.bootstrapcdn.com/font-awesome/4.7.0/css/font-awesome.min.css');
  wp_enqueue_style('university_main_styles', get_stylesheet_uri(), null, microtime());

  # Permite poner JS data en html page. Recibe tres argumentos
  # Primer parámetro: Queremos incluir el name o handle del main JS file; es decir, JS file que queremos que sea flexible.
  # Segundo parámetro: Creamos nombre de la variable.
  # Tercer parámetro: Array de datos que queremos que este disponible en JavaScript
  wp_localize_script('main-university-js', 'universityData', [
    'root_url' => get_site_url() // Return current url of the current WordPress instalation 
  ]);
}
add_action('wp_enqueue_scripts', 'university_files');


function university_features() {

  //register_nav_menu('headerMenuLocation', 'Header Menu Location');
  //register_nav_menu('footerLocationOne', 'Footer Location One');
  //register_nav_menu('footerLocationTwo', 'Footer Location Two');

  add_theme_support('title-tag');
  add_theme_support('post-thumbnails');
  add_image_size('professorLandscape', 400, 260, true); // true para cortar image. Default fals
  add_image_size('professorPortrait', 480, 650, true);
  add_image_size('pageBanner', 1500, 350, true);
  

}
add_action('after_setup_theme', 'university_features');



function university_adjust_queries($query) {
  if( !is_admin() AND is_post_type_archive('campus') AND $query->is_main_query() ) {
    $query->set('posts_per_page', -1);
  }

  if( !is_admin() AND is_post_type_archive('program') AND $query->is_main_query() ) {
    $query->set('orderby', 'title');
    $query->set('order', 'ASC');
    $query->set('posts_per_page', -1);
  }

  if ( !is_admin() && is_post_type_archive('event')  AND $query->is_main_query() ) {
    $today = date('Ydm');
    $query->set('meta_key', 'event_date');
    $query->set('posts_per_page', 3);
    $query->set('orderby', 'meta_value_num');
    $query->set('order', 'ASC');
    $query->set('meta_query', array(
      array(
        'key' => 'event_date',
        'compare' => '>=',
        'value' => $today,
        'type'  => 'numeric'
      )
    ));
  }
}
add_action('pre_get_posts', 'university_adjust_queries');


// Method 1: Setting.
function universityMapKey($api) {
  $api['key'] = 'AIzaSyB-sXN3XlnVFt3335bKi0OlN9WuLECZkuE';
  return $api;
}
add_filter('acf/fields/google_map/api', 'universityMapKey');

/*
// Method 2: Setting.
function my_acf_init() {
  acf_update_setting('google_api_key', 'AIzaSyB-sXN3XlnVFt3335bKi0OlN9WuLECZkuE');
}
add_action('acf/init', 'my_acf_init');
*/

// Redirect subscriber accounts out of admin and onto homepage
add_action('admin_init', 'redirectSubsToFrontEnd');

function redirectSubsToFrontEnd () {
  $ourCurrentUser = wp_get_current_user();
  if (count($ourCurrentUser->roles) == 1 AND $ourCurrentUser->roles[0] == 'subscriber') {
    wp_redirect(site_url('/'));
    exit;
  }
}

//Quitar admin bar si es solo subscriptor
add_action('wp_loaded', 'noSubsAdminBar');

function noSubsAdminBar () {
  $ourCurrentUser = wp_get_current_user();
  if (count($ourCurrentUser->roles) == 1 AND $ourCurrentUser->roles[0] == 'subscriber') {
    show_admin_bar(false);
  }
}

// Customize Login Screen
add_filter('login_headerurl', 'ourHeaderUrl');

function ourHeaderUrl () {
  return esc_html(site_url('/'));
}

add_action('login_enqueue_scripts', 'ourLogingCSS');

function ourLogingCSS () {
  wp_enqueue_style('university_main_styles', get_stylesheet_uri(), null, microtime());
  wp_enqueue_style('custom-google-fonts', '//fonts.googleapis.com/css?family=Roboto+Condensed:300,300i,400,400i,700,700i|Roboto:100,300,400,400i,700,700i');
}

add_filter('login_headertitle', 'ourLoginTitle');

function ourLoginTitle () {
  return get_bloginfo('name');
}