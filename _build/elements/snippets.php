<?php

return [
    'mscAddress' => [
        'file' => 'mscaddress',
        'description' => '',
        'properties' => [
            'tpl' => [
                'type' => 'textfield',
                'value' => 'tpl.mscaAddresses',
            ],
            'tplForm' => [
                'type' => 'textfield',
                'value' => 'tpl.mscaForm',
            ],
            'showLog' => [
                'type' => 'combo-boolean',
                'value' => false,
            ],
            'toPlaceholder' => [
                'type' => 'combo-boolean',
                'value' => false,
            ],
        ],
    ],
];