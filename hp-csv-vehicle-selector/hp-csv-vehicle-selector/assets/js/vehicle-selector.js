/*
jQuery(document).ready(function ($) {
    const vehicleData = window.hpVehicleData || {};

    const $make = $('select[name="make"]');
    const $model = $('select[name="model"]');

    // Function for resetting and populating the model field
    function updateModels(selectedMake) {
        $model.empty(); // Empty  model
        $model.append(new Option('Select model', '')); // Default option

        if (vehicleData[selectedMake]) {
            const models = Object.keys(vehicleData[selectedMake]);
            $.each(models, function (_, model) {
                $model.append(new Option(model, model));
            });
        }

        $model.trigger('change'); //In case you are using Select2 or something else
    }

    //Initially only sets the default option
    $model.empty();
    $model.append(new Option('Select model', ''));

    // React to Make change
    $make.on('change', function () {
        updateModels($(this).val());
    });
});
*/
/*jQuery(document).ready(function ($) {
    const vehicleData = window.hpVehicleData || {};

    const $make = $('select[name="make"]');
    const $model = $('select[name="model"]');

    $make.select2({ placeholder: 'Select Make' });
    $model.select2({ placeholder: 'Select Model' });

    function updateModels(selectedMake) {
        const models = vehicleData[selectedMake] ? Object.keys(vehicleData[selectedMake]) : [];

        $model.empty();

        if (models.length) {
            $model.append(new Option('', '', false, false));
            models.forEach(function (model) {
                $model.append(new Option(model, model));
            });
        } else {
            $model.append(new Option('', '', false, false));
        }

        $model.val(null).trigger('change');
    }

    $make.on('change', function () {
        updateModels($(this).val());
    });

    if ($make.val()) {
        updateModels($make.val());
    }

    // Also listen for category change (manual or script)
    $(document).on('change', 'select[name="categories"]', function () {
        $make.val(null).trigger('change');
        $model.empty().append(new Option('', '', false, false)).val(null).trigger('change');
    });
});
*/
jQuery(document).ready(function ($) {
    const vehicleData = window.hpVehicleData || {};

    const $make = $('select[name="make"]');
    const $model = $('select[name="model"]');

    // Initiate Select2
    $make.select2({ placeholder: 'Select Make' });
    $model.select2({ placeholder: 'Select Model' });

    function updateModels(selectedMake) {
        const models = vehicleData[selectedMake] ? Object.keys(vehicleData[selectedMake]) : [];

        $model.empty(); // empty dropdown

        if (models.length) {
            $model.append(new Option('', '', false, false)); //  empty option
            $.each(models, function (_, model) {
                $model.append(new Option(model, model));
            });
        } else {
            $model.append(new Option('', '', false, false)); //just empty option
        }

        $model.val(null).trigger('change'); // reset and update Select2
    }

    //When selecting make, we update the models
    $make.on('change', function () {
        updateModels($(this).val());
    });

    //Initially, we do not populate the model unless there is a make already selected
    if ($make.val()) {
        updateModels($make.val());
    } else {
        $model.empty().append(new Option('', '', false, false)).val(null).trigger('change');
    }
});

