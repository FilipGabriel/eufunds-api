<?php

//add translations in globals too

return [
    'rules' => [
        0 => [
            'name' => trans('user::accounts.rules.smis_access'),
            'value' => 'smis_access'
        ],
        1 => [
            'name' => trans('user::accounts.rules.count_companies'),
            'value' => 'count_companies'
        ],
        2 => [
            'name' => trans('user::accounts.rules.apps'),
            'value' => 'apps'
        ],
        3 => [
            'name' => trans('user::accounts.rules.spendings'),
            'value' => 'spendings'
        ],
        4 => [
            'name' => trans('user::accounts.rules.project_writing_apps'),
            'value' => 'project_writing_apps'
        ]
    ],
    'benefits' => [
        0 => [
            'name' => trans('user::accounts.benefits.discount'),
            'value' => 'discount'
        ],
        1 => [
            'name' => trans('user::accounts.benefits.free_companies'),
            'value' => 'free_companies'
        ],
        2 => [
            'name' => trans('user::accounts.benefits.vip'),
            'value' => 'vip'
        ],
        3 => [
            'name' => trans('user::accounts.benefits.dedicated_representative'),
            'value' => 'dedicated_representative'
        ],
        4 => [
            'name' => trans('user::accounts.benefits.various_promotions'),
            'value' => 'various_promotions'
        ]
    ],
];
