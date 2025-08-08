<?php

add_filter('hivepress/v1/forms/listing_submit', function ($form) {
    $data = hp_csv_get_vehicle_data();

    $makes = array_keys($data);
    $models = [];

    // Populate all possible models, as the initial options for the 'model' select field.
    // The JS will then dynamically filter these.
    foreach ($data as $make => $mods) {
        foreach ($mods as $model => $_) {
            $models[$model] = $model; // Key and value are the same for simple select options
        }
    }

    $form['fields']['make'] = [
        'type' => 'select',
        'label' => 'Make',
        'options' => array_combine($makes, $makes),
        'required' => true,
        '_order' => 2,
    ];
    $form['fields']['model'] = [
        'type' => 'select',
        'label' => 'Model',
        'options' => $models, // All models initially, JS will filter
        'required' => true,
        '_order' => 3,
    ];

    return $form;
});

// Modificare aici pentru a face atributele filtrabile/searchable în admin
add_filter(
    'hivepress/v1/models/listing/attributes',
    function ($attributes) {
        $vehicle_data = hp_csv_get_vehicle_data(); // Get all vehicle data

        // Prepare make options for backend filtering/searching
        $make_options = ['' => '']; // Add an empty option
        foreach (array_keys($vehicle_data) as $make) {
            $make_options[$make] = $make;
        }

        // Prepare model options for backend filtering/searching
        // Include all models from all makes initially for backend filtering
        $model_options = ['' => '']; // Add an empty option
        foreach ($vehicle_data as $make => $models) {
            foreach ($models as $model_name => $_) {
                $model_options[$model_name] = $model_name;
            }
        }


        $attributes['make'] = [
            'label'         => 'Make',
            'type'          => 'select', // Set type to 'select' for backend filtering
            'editable'      => true,
           // 'searchable'    => true,
           // 'filterable'    => true, // Make it filterable in admin
           // 'sortable'      => true,
            'indexable'     => true,
            'display_areas' => ['view_block_secondary', 'view_page_primary'],
            'options'       => $make_options, // Provide options for backend select
			'categories'    => [],
            'edit_field'    => [
                'label'     => 'Make',
                'type'      => 'select', // Keep as 'select' here as well, HivePress handles this for the frontend
                'source'    => [],
                '_external' => true,
                '_order'    => 1,
                'options'   => $make_options, // These options will be overwritten by JS on the frontend
                'required'  => true,
            ],
        ];

        $attributes['model'] = [
            'label'         => 'Model',
            'type'          => 'select', // Set type to 'select' for backend filtering
             'editable'      => true,
           //'searchable'    => true,
           //'filterable'    => true, // Make it filterable in admin
           //'sortable'      => true,
            'indexable'     => true,
            'display_areas' => ['view_block_secondary', 'view_page_primary'],
            'options'       => $model_options, // Provide all possible models for backend select
			'categories'    => [],
            'edit_field'    => [
                'label'     => 'Model',
                'type'      => 'select', // Keep as 'select' here, JS handles dynamic options
                'source'    => [],
                '_external' => true,
                '_order'    => 2,
                'options'   => $model_options, // These will be overwritten by JS on frontend
                'required'  => true,
            ],
        ];
        return $attributes;
    }
);

function hp_csv_get_vehicle_data() {
    static $data = null;
    if ($data !== null) return $data;

    $csv = plugin_dir_path(__FILE__) . '/../vehicles.csv';
    $data = [];

    if (file_exists($csv) && ($handle = fopen($csv, 'r')) !== false) {
        fgetcsv($handle);
        while (($row = fgetcsv($handle)) !== false) {
            list($make, $model) = $row;
            // Trim whitespace from make and model
            $make = trim($make);
            $model = trim($model);
            if (!empty($make) && !empty($model)) {
                $data[$make][$model] = true;
            }
        }
        fclose($handle);
    }
    // Sort makes alphabetically
    ksort($data);
    // Sort models within each make alphabetically
    foreach ($data as $make => $models) {
        ksort($data[$make]);
    }
    return $data;
}