<?php
// admin.php

// Add settings in admin page
add_action('admin_menu', function () {
    add_options_page(
        'Location Selector Settings',
        'Location Selector',
        'manage_options',
        'location-selector-settings',
        'ls_settings_page'
    );
});

// Display setting page
function ls_settings_page() {
    ?>
    <div class="wrap">
        <h1>Location Selector Options</h1>
        <form method="post" action="options.php">
            <?php
            settings_fields('ls_settings_group');
            do_settings_sections('location-selector-settings');
            submit_button();
            ?>
        </form>
    </div>
    <?php
}

// Reg. settings
add_action('admin_init', function () {
    // Reg. options
    register_setting('ls_settings_group', 'ls_county_block_display');
    register_setting('ls_settings_group', 'ls_county_page_display');
    register_setting('ls_settings_group', 'ls_city_block_display');
    register_setting('ls_settings_group', 'ls_city_page_display');

    // New settings for labels
    register_setting('ls_settings_group', 'ls_county_label');
    register_setting('ls_settings_group', 'ls_city_label');

    // New setting for CSV filename
    register_setting('ls_settings_group', 'ls_csv_filename');

    // NEW SETTING: Searchable/Filterable toggle
    register_setting('ls_settings_group', 'ls_enable_search_filter');


    add_settings_section(
        'ls_main_section',
        'Location Display and Label Options', // Updated section title
        null,
        'location-selector-settings'
    );

    // Dropdown for each field
    add_settings_field('ls_county_block_display', 'County – View Block', function () {
        ls_display_dropdown('ls_county_block_display');
    }, 'location-selector-settings', 'ls_main_section');

    add_settings_field('ls_county_page_display', 'County – View Page', function () {
        ls_display_dropdown('ls_county_page_display');
    }, 'location-selector-settings', 'ls_main_section');

    add_settings_field('ls_city_block_display', 'City – View Block', function () {
        ls_display_dropdown('ls_city_block_display');
    }, 'location-selector-settings', 'ls_main_section');

    add_settings_field('ls_city_page_display', 'City – View Page', function () {
        ls_display_dropdown('ls_city_page_display');
    }, 'location-selector-settings', 'ls_main_section');

    // New settings fields for labels
    add_settings_field('ls_county_label', 'Label for County', function () {
        ls_text_input('ls_county_label', 'County'); // Default to 'County'
    }, 'location-selector-settings', 'ls_main_section');

    add_settings_field('ls_city_label', 'Label for City', function () {
        ls_text_input('ls_city_label', 'City'); // Default to 'City'
    }, 'location-selector-settings', 'ls_main_section');

    // New settings field for CSV filename
    add_settings_field('ls_csv_filename', 'CSV Filename (e.g., locations.csv)', function () {
        ls_text_input('ls_csv_filename', 'locations.csv'); // Default to 'locations.csv'
    }, 'location-selector-settings', 'ls_main_section');

    // NEW SETTINGS FIELD: Searchable/Filterable Checkbox
    add_settings_field('ls_enable_search_filter', 'Enable Search/Filter for County and City', function () {
        $checked = get_option('ls_enable_search_filter', false); // Default to false (unchecked)
        ?>
        <input type="checkbox" name="ls_enable_search_filter" value="1" <?php checked(1, $checked, true); ?> />
        <p class="description">Check this box to make County and City attributes searchable and filterable in HivePress.</p>
        <?php
    }, 'location-selector-settings', 'ls_main_section');
});

// Dropdown functions
function ls_display_dropdown($option_name) {
    $value = get_option($option_name, 'primary'); // Default to 'primary'
    ?>
    <select name="<?php echo esc_attr($option_name); ?>">
        <option value="primary" <?php selected($value, 'primary'); ?>>Primary</option>
        <option value="secondary" <?php selected($value, 'secondary'); ?>>Secondary</option>
        <option value="ternary" <?php selected($value, 'ternary'); ?>>Ternary</option>
        <option value="hide" <?php selected($value, 'hide'); ?>>Hide</option>
    </select>
    <?php
}

// Reusable function for text input
function ls_text_input($option_name, $default_value = '') {
    $value = get_option($option_name, $default_value);
    ?>
    <input type="text" name="<?php echo esc_attr($option_name); ?>" value="<?php echo esc_attr($value); ?>" class="regular-text" />
    <?php
}