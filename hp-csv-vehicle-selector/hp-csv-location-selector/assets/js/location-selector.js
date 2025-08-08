////////////////////////////////////////////////////////////
jQuery(document).ready(function ($) {
    const locationData = window.hpLocationData || {};

    const $county = $('select[name="county"]');
    const $city = $('select[name="city"]');

    // Initialize Select2
    $county.select2({ placeholder: 'Select County' });
    $city.select2({ placeholder: 'Select City' });

    function updateCities(selectedCounty) {
        const cities = locationData[selectedCounty] ? Object.keys(locationData[selectedCounty]) : [];

        $city.empty(); // Clear the dropdown

        if (cities.length) {
            $city.append(new Option('', '', false, false)); // Empty option
            $.each(cities, function (_, city) {
                $city.append(new Option(city, city));
            });
        } else {
            $city.append(new Option('', '', false, false)); // Only empty option
        }

        $city.val(null).trigger('change'); // Reset and update Select2
    }

    // When a county is selected, update the cities
    $county.on('change', function () {
        updateCities($(this).val());
    });

    // Initially, populate cities only if a county is already selected
    if ($county.val()) {
        updateCities($county.val());
    } else {
        $city.empty().append(new Option('', '', false, false)).val(null).trigger('change');
    }
});

$county.on('change', function () {
    setTimeout(() => {
        refreshCities($(this).val());
    }, 300);
});
