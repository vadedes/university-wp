<?php get_header(); ?>

<div class="page-banner">
    <div class="page-banner__bg-image"
        style="background-image: url(<?php echo get_theme_file_uri('images/ocean.jpg') ?>)">
    </div>
    <div class="page-banner__content container container--narrow">

        <!-- Option 2 is to use the recently released function of WP called the_archive_title() -->
        <h1 class="page-banner__title">Past Events</h1>
        <div class="page-banner__intro">
            <!-- this function will use biographical info of author OR the category description provided -->
            <p>A recap of our past events</p>
        </div>
    </div>
</div>

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
    $pastEvents->the_post(); ?>

    <div class="event-summary">
        <a class="event-summary__date t-center" href="<?php the_permalink(); ?>">
            <!-- can use the_time('M') M for month, d for day Y for year or use ACF -->
            <span class="event-summary__month"><?php 
                      $eventDate = new DateTime(get_field('event_date'));
                      echo $eventDate->format('M');
                    ?></span>
            <span class="event-summary__day"><?php echo $eventDate->format('d'); ?></span>
        </a>
        <div class="event-summary__content">
            <h5 class="event-summary__title headline headline--tiny"><a
                    href="<?php the_permalink(); ?>"><?php the_title(); ?></a></h5>
            <p><?php echo wp_trim_words(get_the_content(), 18) ?> <a href="<?php the_permalink(); ?>"
                    class="nu gray">Learn more
                </a>
            </p>
        </div>
    </div>

    <?php  } 
    echo paginate_links(array(
        'total' => $pastEvents->max_num_pages,

    ));
  ?>

</div>

<?php get_footer(); ?>