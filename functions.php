<?php

//page banner modular components
function pageBanner($args = NULL){ 

  //if there's no title provided fallback below
  if(!$args['title']){
    $args['title'] = get_the_title();
  }

  //if there's no subtitle provided fallback below
  if(!$args['subtitle']){
    $args['subtitle'] = get_field('page_banner_subtitle');
  }

  //if there's no background photo uploaded for banner fall back below
  if(!$args['photo']) {
    if(get_field('page_banner_background_image') AND !is_archive() AND !is_home() ) {
      $args['photo'] = get_field('page_banner_background_image')['sizes']['pageBanner'];
    } else {
      $args['photo'] = get_theme_file_uri('/images/ocean.jpg');
    }
  }
?>
<div class="page-banner">
    <!-- the variable $pageBanner outputs an array, we just need to echo the custom image size we specified in the function.php file and consume the data within the array -->
    <div class="page-banner__bg-image" style="background-image: url(<?php echo $args['photo'] ;?>);">
    </div>
    <div class="page-banner__content container container--narrow">
        <h1 class="page-banner__title"><?php echo $args['title'] ?></h1>
        <div class="page-banner__intro">
            <p><?php echo $args['subtitle']; ?></p>
        </div>
    </div>
</div>
<?php }

// Load styles and script files
function university_files() {
  wp_enqueue_script('googleMap', '//maps.googleapis.com/maps/api/js?key=AIzaSyCQlfylto0npuPR0DlWFU8Vt85D5eG3wOs', NULL, '1.0', true);
  wp_enqueue_script('main-university-js', get_theme_file_uri('/build/index.js'), array('jquery'), '1.0', true);
  
  wp_enqueue_style('custom-google-fonts', '//fonts.googleapis.com/css?family=Roboto+Condensed:300,300i,400,400i,700,700i|Roboto:100,300,400,400i,700,700i');
  wp_enqueue_style('font-awesome', '//maxcdn.bootstrapcdn.com/font-awesome/4.7.0/css/font-awesome.min.css');
  wp_enqueue_style('university_main_styles', get_theme_file_uri('/build/style-index.css'));
  wp_enqueue_style('university_extra_styles', get_theme_file_uri('/build/index.css'));
}

add_action('wp_enqueue_scripts', 'university_files');

//adding custom theme features
function university_features(){

  //Add control to nav menus from wordpress admin
  register_nav_menu('headerMenuLocation', 'Header Menu Location');
  register_nav_menu('footerLocationOne', 'Footer Location One');
  register_nav_menu('footerLocationTwo', 'Footer Location Two');


  //automatically generate page-title for each page
  add_theme_support('title-tag');
  add_theme_support('post-thumbnails');//enable post thumbnail images //for custom post types, add this support separately
  add_image_size('professorLandscape', 400, 260, true);// arg1 is the name, arg2 is width, arg3 is height, arg4 is to crop or not
  add_image_size('professorPortrait', 430, 600, true);
  add_image_size('pageBanner', 1920, 350, true);
}

add_action('after_setup_theme', 'university_features');


//in order to make custom post types persists even if the theme changes, all custom post types code should be place in a separate folder
//custom posts types are placed in a separate folder called "mu-plugins" just beside themes folder inside wp-content folder.


//below function is meant to tweak WP's default query on the events archive page - just tweak a bit
//pass-in WP's query object and make some changes to it
//this query object is universal an will affect both admin and client side so we need to set a condition to run it only when in a specific route
function university_adjust_queries($query){
  
  //query adjustments for events post type
  //condition means that only run this code in the event post type and don't affect the admin side and only run this for the main query object so that our custom queries made previously
  if(!is_admin()  AND is_post_type_archive('event') AND $query->is_main_query()) {
    $today = date('Ymd');
    $query->set('meta_key', 'event_date');
    $query->set('orderby', 'meta_value_num');
    $query->set('order', 'ASC');
    $query->set('meta_query', array(
      array(
        'key' => 'event_date', //event date
        'compare' => '>=', //condition symbol
        'value' => $today, //get present date variable declared above
        'type' => 'numeric' //specified type of output value
      )
      ));
  }

  //Query adjustments for programs post type
  if(!is_admin() AND is_post_type_archive('program') AND $query->is_main_query()){
    $query->set('orderby', 'title');
    $query->set('order', 'ASC');
    $query->set('posts_per_page', -1);
  }

}

add_action('pre_get_posts', 'university_adjust_queries');

//enable google map
function universityMapKey($api){
  $api['key'] = 'AIzaSyCQlfylto0npuPR0DlWFU8Vt85D5eG3wOs';
  return $api;
}

add_filter('acf/fields/google_map/api', 'universityMapKey');