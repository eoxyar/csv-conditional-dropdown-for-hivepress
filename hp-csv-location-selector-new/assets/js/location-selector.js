////////////////////////////////////////////////////////////
jQuery(document).ready(function ($) {
    const locationData = window.hpLocationData || {};

    const $county = $('select[name="county"]');
    const $city = $('select[name="city"]');

    // Initialize Select2
    $county.select2({ placeholder: 'Please Select' });
    $city.select2({ placeholder: 'Please Select' });

    function updateCities(selectedCounty) {
        // Your existing city update logic
        const cities = locationData[selectedCounty] ? Object.keys(locationData[selectedCounty]) : [];
        $city.empty();

        if (cities.length) {
            $city.append(new Option('', '', false, false));
            $.each(cities, function (_, city) {
                $city.append(new Option(city, city));
            });
        } else {
            $city.append(new Option('', '', false, false));
        }
        $city.val(null).trigger('change');
    }

    // Intercept the change event and stop it from propagating
    $county.on('change', function (e) {
        // Stop the event from bubbling up and triggering HivePress's form update.
        e.stopPropagation();
        
        // This is a crucial line. It prevents any default action, though change events don't have a lot of default actions.
        // The main point is to prevent the event from triggering the form-level listener.
        
        updateCities($(this).val());
    });

    // Also, when the city changes, you might need to stop its propagation too.
    $city.on('change', function (e) {
        e.stopPropagation();
    });

    // Initially, populate cities if a county is already selected
    if ($county.val()) {
        updateCities($county.val());
    } else {
        $city.empty().append(new Option('', '', false, false)).val(null).trigger('change');
    }
});


