<?php
// fields.php

add_filter('hivepress/v1/forms/listing_submit', function ($form) {
    $data = hp_csv_get_location_data();

    $counties = array_keys($data);
    $cities = [];

    foreach ($data as $county => $mods) {
        foreach ($mods as $city => $_) {
            $cities[$city] = $city;
        }
    }

    // Get custom labels from settings, defaulting to 'County' and 'City'
    $county_label = get_option('ls_county_label', 'County');
    $city_label = get_option('ls_city_label', 'City');

    $form['fields']['county'] = [
        'type' => 'select',
        'label' => $county_label, // Use the custom label
        'options' => array_combine($counties, $counties),
        'required' => true,
        '_order' => 10,
    ];
    $form['fields']['city'] = [
        'type' => 'select',
        'label' => $city_label, // Use the custom label
        'options' => $cities,
        'required' => true,
        '_order' => 11,
    ];

    return $form;
});

add_filter(
    'hivepress/v1/models/listing/attributes',
    function ($attributes) {

        $location_data = hp_csv_get_location_data();

        // Get custom labels from settings
        $county_label = get_option('ls_county_label', 'County');
        $city_label = get_option('ls_city_label', 'City');

        // NEW: Get the searchable/filterable setting
        $enable_search_filter = (bool) get_option('ls_enable_search_filter', false);

        $county_options = ['' => ''];
        foreach (array_keys($location_data) as $county) {
            $county_options[$county] = $county;
        }

        $city_options = ['' => ''];
        foreach ($location_data as $county => $cities) {
            foreach ($cities as $city_name => $_) {
                $city_options[$city_name] = $city_name;
            }
        }

        // Get display settings for County
        $county_block_display = get_option('ls_county_block_display', 'primary');
        $county_page_display = get_option('ls_county_page_display', 'primary');

        // Prepare display areas for County
        $county_display_areas = [];
        if ($county_block_display === 'hide') {
            // If 'hide' is selected, don't add any display area for block view
        } elseif ($county_block_display === 'none') {
            // For true "show nothing", the attribute itself would ideally not render.
            // For now, we'll treat 'none' similar to 'hide' for simplicity in display_areas.
        } else {
            $county_display_areas[] = 'view_block_' . $county_block_display;
        }

        if ($county_page_display === 'hide') {
            // If 'hide' is selected, don't add any display area for page view
        } elseif ($county_page_display === 'none') {
            // Similar to above, handle 'none' if needed
        } else {
            $county_display_areas[] = 'view_page_' . $county_page_display;
        }


        // Get display settings for City
        $city_block_display = get_option('ls_city_block_display', 'primary');
        $city_page_display = get_option('ls_city_page_display', 'primary');

        // Prepare display areas for City
        $city_display_areas = [];
        if ($city_block_display === 'hide') {
            // If 'hide' is selected, don't add any display area for block view
        } elseif ($city_block_display === 'none') {
            // Handle 'none' as described for county
        } else {
            $city_display_areas[] = 'view_block_' . $city_block_display;
        }

        if ($city_page_display === 'hide') {
            // If 'hide' is selected, don't add any display area for page view
        } elseif ($city_page_display === 'none') {
            // Handle 'none' as described for county
        } else {
            $city_display_areas[] = 'view_page_' . $city_page_display;
        }


        $attributes['county'] = [
            'label' => $county_label,
            'type'            => 'select',
            'editable'        => true,
            'searchable'      => $enable_search_filter, // Dynamically set
            'filterable'      => $enable_search_filter, // Dynamically set
            'indexable'       => true,
            'display_areas' => $county_display_areas, // Use the dynamically built array
            'options'         => $county_options,
            'edit_field'      => [
                'label' => $county_label,
                'type'      => 'select',
                'source'    => [],
                '_external' => true,
                '_order'    => 1,
                'options'   => $county_options,
                'required'  => true,
            ],
            'search_field'  => [
                'label' => $county_label,
                'type'      => 'select',
                'options'   => $county_options,
                '_external' => true,
                '_order'    => 30,
            ],
        ];

        $attributes['city'] = [
            'label' => $city_label,
            'type'            => 'select',
            'editable'        => true,
          //  'searchable'      => $enable_search_filter, // Dynamically set
            'filterable'      => $enable_search_filter, // Dynamically set
            'indexable'       => true,
            'display_areas' => $city_display_areas, // Use the dynamically built array
            'options'         => $city_options,
            'edit_field'    => [
                'label' => $city_label,
                'type'      => 'select',
                'source'    => [],
                '_external' => true,
                '_order'    => 2,
                'options'   => $city_options,
                'required'  => true,
            ],
           
            // Stick to 'select' type in search_field .
            'search_field'  => [
                'label' => $city_label,
                'type'      => 'select', // Changed to 'select' for consistency with filterable
                'options'   => $city_options,
                '_external' => true,
                '_order'    => 30,
            ],
        ];

        return $attributes;
    }
);

function hp_csv_get_location_data() {
    static $data = null;
    if ($data !== null) return $data;

    // Get the CSV filename from settings, default to 'locations.csv'
    $csv_filename = get_option('ls_csv_filename', 'locations.csv');
    $csv = plugin_dir_path(__FILE__) . '/../' . sanitize_file_name($csv_filename); // Sanitize input

    $data = [];

    if (file_exists($csv) && ($handle = fopen($csv, 'r')) !== false) {
        fgetcsv($handle); // Skip header row
        while (($row = fgetcsv($handle)) !== false) {
            // Ensure row has at least two elements before accessing
            if (isset($row[0]) && isset($row[1])) {
                list($county, $city) = $row;
                $county = trim($county);
                $city = trim($city);
                if (!empty($county) && !empty($city)) {
                    $data[$county][$city] = true;
                }
            }
        }
        fclose($handle);
    }
    ksort($data);
    foreach ($data as $county => $cities) {
        ksort($data[$county]);
    }
    return $data;
}
