<?php
//automatically save from meta make and model for admin
add_action('save_post_hp_listing', function ($post_id) {
    // Avoid saving during auto-save
    if (defined('DOING_AUTOSAVE') && DOING_AUTOSAVE) {
        return;
    }

    // Check if it is a valid request
    // IMPORTANT: HivePress saves attributes to post meta with a leading underscore if not explicitly mapped
    // If you explicitly map them in hivepress/v1/models/listing/attributes without 'meta_key' => '_make',
    // then they might be saved directly without the underscore.
    // For this setup, we'll assume they come from the form with `hp_make` and `hp_model` and save to `_make` and `_model`.
    // If HivePress is handling attribute saving, you might not even need this 'save.php' file for `_make` and `_model` if they are defined as HivePress attributes.
    // However, keeping it here ensures backward compatibility or if HivePress attribute saving is bypassed for some reason.
    if (!isset($_POST['make']) || !isset($_POST['model'])) { // Changed from hp_make/hp_model to just make/model as per HivePress form fields
        return;
    }

    // Save Make and Model values as meta
    // HivePress typically saves its own attributes directly to post meta.
    // This `save.php` might be redundant if HivePress is already handling `make` and `model` attributes.
    // However, if these are *not* directly mapped HivePress attributes, but custom meta fields, then this is needed.
    // Given the `fields.php` structure, they *are* HivePress attributes, so HP should save them.
    // Let's adjust to match standard HivePress behavior for direct attribute saving.
    // HivePress attributes are usually stored directly by their name, not prefixed by `hp_`.
    // If you explicitly set meta_key for an attribute, it uses that.
    // For now, assuming they are saved by HP, but leaving this for explicit meta saving if needed.
    if (!empty($_POST['make'])) { // Changed from hp_make
        update_post_meta($post_id, 'make', sanitize_text_field($_POST['make'])); // Save as 'make' directly
    }

    if (!empty($_POST['model'])) { // Changed from hp_model
        update_post_meta($post_id, 'model', sanitize_text_field($_POST['model'])); // Save as 'model' directly
    }
});