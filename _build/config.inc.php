<?php

if (!defined('MODX_CORE_PATH')) {
    define('MODX_CORE_PATH', dirname(dirname(dirname(__FILE__))) . '/core/');
}

return [
    'name' => 'mscAddress',
    'name_lower' => 'mscaddress',
    'name_prefix' => 'msca',
    'version' => '1.1.0',
    'release' => 'pl',
    // Install package to site right after build
    'install' => true,
    // Which elements should be updated on package upgrade
    'update' => [
        'chunks' => true,
        'menus' => false,
        'plugins' => true,
        'resources' => false,
        'settings' => true,
        'snippets' => true,
        'templates' => false,
        'widgets' => false,
    ],
    // Which elements should be static by default
    'static' => [
        'plugins' => false,
        'snippets' => false,
        'chunks' => false,
    ],
    // Log settings
    'log_level' => !empty($_REQUEST['download']) ? 0 : 3,
    'log_target' => php_sapi_name() == 'cli' ? 'ECHO' : 'HTML',
    // Download transport.zip after build
    'download' => !empty($_REQUEST['download']),
];