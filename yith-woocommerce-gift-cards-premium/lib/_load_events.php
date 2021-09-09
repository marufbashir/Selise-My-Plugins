<?php
// load all events
$events = array();
$all_lang_args = array(
    'post_type' => 'product',
    'post_status' => 'publish',
    'orderby' => 'date',
    'order' => 'ASC',
      'meta_query' => array(
          'relation' => 'OR',
        array(
          'key'     => 'event_language',
          'value'   => 'German',
          'compare' => 'LIKE',
        ),
        array(
          'key'     => 'event_language',
          'value'   => 'Deutsch',
          'compare' => 'LIKE',
        ),
        //load english lang event 
        array(
            'key'     => 'event_language',
            'value'   => 'English',
            'compare' => 'LIKE',
          ),
          array(
            'key'     => 'event_language',
            'value'   => 'Englisch',
            'compare' => 'LIKE',
          ),
      ) 

);

$args = array( 'limit' => 1, 'type' => 'booking', );

$products = wc_get_products( $args );

print_r($products);