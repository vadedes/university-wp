<?php 
get_header();
pageBanner(array(
    'title' => 'Our Campuses',
    'subtitle' => 'We have serveral conviniently located campuses'
)); 
?>

<div class="container container--narrow page-section">
    <div class='acf-map'>
        <?php while(have_posts()){
        the_post(); 
        $mapLocation = get_field('map_location')
        ?>
        <!-- <li><a href="<?php the_permalink(); ?>"><?php 
        the_title(); 
        $mapLocation = get_field('map_location');
        //check data contained in the variable print_r(variable-name) 
        // print_r($mapLocation);
        $mapLocation['lat']
        ?></a></li> -->

        <div class='marker' data-lat='<?php echo $mapLocation['lat'] ?>' data-lng='<?php echo $mapLocation['lng'] ?>'>
        </div>

        <?php  } 
        echo paginate_links();
        ?>
    </div>
</div>

<?php get_footer(); ?>