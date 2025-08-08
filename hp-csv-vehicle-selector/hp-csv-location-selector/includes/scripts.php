<?php

add_action('wp_enqueue_scripts', function () {
    wp_enqueue_script(
        'hp-csv-location-selector',
        plugins_url('../assets/js/location-selector.js', __FILE__),
        ['jquery'],
        null,
        true
    );

    wp_add_inline_script('hp-csv-location-selector', 'window.hpLocationData = ' . json_encode(hp_csv_get_location_data()) . ';');
});
