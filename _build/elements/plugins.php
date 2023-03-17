<?php

return [
    'mscAddress' => [
        'file' => 'mscaddress',
        'description' => '',
        'events' => [
            //'OnManagerPageInit' => [],
            'OnHandleRequest' => ['priority' => 0],
            'OnLoadWebDocument' => ['priority' => 0],
            'msOnAddToOrder' => ['priority' => 10],
        ],
    ],
];