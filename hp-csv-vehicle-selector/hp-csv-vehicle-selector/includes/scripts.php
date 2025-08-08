<?php

add_action('wp_enqueue_scripts', function () {
    wp_enqueue_script(
        'hp-csv-vehicle-selector',
        plugins_url('../assets/js/vehicle-selector.js', __FILE__),
        ['jquery'],
        null,
        true
    );

    wp_add_inline_script('hp-csv-vehicle-selector', 'window.hpVehicleData = ' . json_encode(hp_csv_get_vehicle_data()) . ';');
});
