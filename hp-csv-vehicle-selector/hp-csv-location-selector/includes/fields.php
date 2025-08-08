<?php

add_filter('hivepress/v1/forms/listing_submit', function ($form) {
    $data = hp_csv_get_location_data();

    $counties = array_keys($data);
    $cities = [];

    // Populate all possible cities, as the initial options for the 'carmodel' select field.
    // The JS will then dynamically filter these.
    foreach ($data as $county => $mods) {
        foreach ($mods as $city => $_) {
            $cities[$city] = $city; // Key and value are the same for simple select options
        }
    }

    $form['fields']['county'] = [
        'type' => 'select',
        'label' => 'County',
        'options' => array_combine($counties, $counties),
        'required' => true,
        '_order' =>10,
    ];
    $form['fields']['city'] = [
        'type' => 'select',
        'label' => 'City',
        'options' => $cities, // All cities initially, JS will filter
        'required' => true,
        '_order' => 11,
    ];

    return $form;
});
//  Modifications for make them filterable/searchable
add_filter(
    'hivepress/v1/models/listing/attributes',
    function ($attributes) {
		
        $location_data = hp_csv_get_location_data(); // Get all location data

        // Prepare county options for backend filtering/searching
        $county_options = ['' => '']; // Add an empty option
        foreach (array_keys($location_data) as $county) {
            $county_options[$county] = $county;
        }

        // Prepare city options for backend filtering/searching
        // Include all cities from all counties initially for backend filtering
        $city_options = ['' => '']; // Add an empty option
        foreach ($location_data as $county => $cities) {
            foreach ($cities as $city_name => $_) {
                $city_options[$city_name] = $city_name;
            }
        }



        $attributes['county'] = [
            'label'         => 'County',
            'type'          => 'select', // Set type to 'select' for backend filtering
            'editable'      => true,
            'searchable'    => true,
           'filterable'    => true, // County it filterable in admin
           // 'sortable'      => true,
            'indexable'     => true,
            'display_areas' => ['view_block_secondary', 'view_page_primary'],
            'options'       => $county_options, // Provide options for backend select
            'edit_field'    => [
                'label'     => 'County',
                'type'      => 'select', // Keep as 'select' here as well, HivePress handles this for the frontend
                'source'    => [],
                '_external' => true,
                '_order'    => 1,
                'options'   => $county_options, // These options will be overwritten by JS on the frontend
                'required'  => true,
				
            ],
						'search_field'    => [
				'label'     => 'County',
				'type'      => 'select',
				'options'   => $county_options,
				'_external' => true,
				'_order'    => 30,
			],
        ];

        $attributes['city'] = [
            'label'         => 'City',
            'type'          => 'select', // Set type to 'select' for backend filtering
            'editable'      => true,
          //  'searchable'    => true, // wont work reload the page??? why??
          //  'filterable'    => true, // wont work reload the page??? why??
            //'sortable'      => true,
            'indexable'     => true,
            'display_areas' => ['view_block_secondary', 'view_page_primary'],
            'options'       => $city_options, // Provide all possible cities for backend select		
            'edit_field'    => [
                'label'     => 'City',
                'type'      => 'select', // Keep as 'select' here, JS handles dynamic options
                'source'    => [],
                '_external' => true,
                '_order'    => 2,
                'options'   => $city_options, // These will be overwritten by JS on frontend
                'required'  => true,				
            ],
		'search_field'    => [
				'label'     => 'City',
				'type'      => 'select',
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

    $csv = plugin_dir_path(__FILE__) . '/../locations.csv';
    $data = [];

    if (file_exists($csv) && ($handle = fopen($csv, 'r')) !== false) {
        fgetcsv($handle);
        while (($row = fgetcsv($handle)) !== false) {
            list($county, $city) = $row;
            // Trim whitespace from county and city
            $county = trim($county);
            $city = trim($city);
            if (!empty($county) && !empty($city)) {
                $data[$county][$city] = true;
            }
        }
        fclose($handle);
    }
    // Sort counties alphabetically
    ksort($data);
    // Sort cities within each county alphabetically
    foreach ($data as $county => $cities) {
        ksort($data[$county]);
    }
    return $data;
}


