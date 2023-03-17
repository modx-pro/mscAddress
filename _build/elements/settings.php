<?php

return [
    'address_handler' => [
        'xtype' => 'textfield',
        'value' => 'mscaAddressHandler',
        'area' => 'msca_main',
		'name'	=> 'setting_msca_address_handler',
		'description'=> 'setting_msca_address_handler_desc',
    ],
    'requires' => [
        'xtype' => 'textfield',
        'value' => 'city,street,building',
        'area' => 'msca_main',
		'name'	=> 'setting_msca_requires',
		'description'=> 'setting_msca_requires_desc',
    ],
    'frontend_css' => [
        'xtype' => 'textfield',
        'value' => '[[+cssUrl]]web/default.css',
        'area' => 'msca_main',
		'name'	=> 'setting_msca_frontend_css',
		'description'=> 'setting_msca_frontend_css_desc',
    ],
    'frontend_js' => [
        'xtype' => 'textfield',
        'value' => '[[+jsUrl]]web/default.js',
        'area' => 'msca_main',
		'name'	=> 'setting_msca_frontend_js',
		'description'=> 'setting_msca_frontend_js_desc',
    ],
];