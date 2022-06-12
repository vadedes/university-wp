<?php 
get_header();
pageBanner(array(
  'title' => 'Past Events',
  'subtitle' => 'A recap of our past events'
)); 
?>



<div class="container container--narrow page-section">
    <?php 
  $today = date('Ymd'); //variable to get present date
  $pastEvents = new WP_Query(array(
    'paged' => get_query_var('paged', 1), //1st arg means go out and get that number at the end of page url, and if there's none, fallback to page 1(2nd)
    'post_type' => 'event',
    //below line is needed when querying custom fields
    'meta_key' => 'event_date',
    'orderby' => 'meta_value_num',
    'order' => 'DESC',
    // purpose of below parameter is to only show content that have not yet passed
    'meta_query' => array(
      array(
        'key' => 'event_date', //event date
        'compare' => '<', //condition symbol
        'value' => $today, //get present date variable declared above
        'type' => 'numeric' //specified type of output value
      )
    )
  ));
  
  while($pastEvents->have_posts()){
    $pastEvents->the_post(); 
    get_template_part('template-parts/content-event');
    } 
    echo paginate_links(array(
        'total' => $pastEvents->max_num_pages,

    ));
  ?>

</div>

<?php get_footer(); ?>