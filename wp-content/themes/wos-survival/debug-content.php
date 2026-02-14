<?php
define( 'WP_USE_THEMES', false );
require( 'i:\04_develop\howasaba-lab\wp-load.php' );

echo "Checking Post Counts...\n";

$post_types = ['gift_code', 'wos_hero', 'wos_event'];

foreach ($post_types as $pt) {
    $count = wp_count_posts($pt);
    echo "Post Type: $pt\n";
    print_r($count);
    echo "\n";
    
    // Get one post to check meta
    $args = [
        'post_type' => $pt,
        'posts_per_page' => 1,
    ];
    $query = new WP_Query($args);
    if ($query->have_posts()) {
        while ($query->have_posts()) {
            $query->the_post();
            echo "Sample Post ID: " . get_the_ID() . "\n";
            echo "Title: " . get_the_title() . "\n";
            $meta = get_post_meta(get_the_ID());
            echo "Meta Keys:\n";
            print_r(array_keys($meta));
        }
    } else {
        echo "No posts found for $pt\n";
    }
    echo "-------------------\n";
}
