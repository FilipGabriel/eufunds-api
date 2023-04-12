<?php

return [
    'name' => 'Nume',
    'type' => 'Tip',
    'is_required' => 'Required',
    'label' => 'Label',
    'price' => 'Pret',
    'price_type' => 'Tip pret',

    // Validations
    'values.*.label' => 'Label',
    'values.*.price' => 'Pret',
    'values.*.price_type' => 'Tip pret',

    'options.*.name' => 'Nume',
    'options.*.type' => 'Tip',
    'options.*.values.*.label' => 'Label',
    'options.*.values.*.price' => 'Pret',
    'options.*.values.*.price_type' => 'Tip pret',
];
