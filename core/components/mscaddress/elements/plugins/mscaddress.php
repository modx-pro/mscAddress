<?php
/** @var modX $modx */
/** @var array $scriptProperties */
/** @var mscAddress $msca */
$msca = $modx->getService('mscAddress', 'mscAddress', MODX_CORE_PATH . 'components/mscaddress/model/');
if (!$msca) {
    return false;
}

$className = 'msca' . ucfirst($modx->event->name);
$modx->loadClass('mscaPlugin', $msca->config['modelPath'] . 'mscaddress/plugins/', true, true);
$modx->loadClass($className, $msca->config['modelPath'] . 'mscaddress/plugins/', true, true);

if (class_exists($className)) {
    $handler = new $className($modx, $scriptProperties);
    $handler->run();
}
return;