<?php
/** @var xPDOTransport $transport */
/** @var array $options */
/** @var modX $modx */
if ($transport->xpdo) {
    $modx =& $transport->xpdo;

    $dev = MODX_BASE_PATH . 'Extras/mscAddress/';
    /** @var xPDOCacheManager $cache */
    $cache = $modx->getCacheManager();
    if (file_exists($dev) && $cache) {
        if (!is_link($dev . 'assets/components/mscaddress')) {
            $cache->deleteTree(
                $dev . 'assets/components/mscaddress/',
                ['deleteTop' => true, 'skipDirs' => false, 'extensions' => []]
            );
            symlink(MODX_ASSETS_PATH . 'components/mscaddress/', $dev . 'assets/components/mscaddress');
        }
        if (!is_link($dev . 'core/components/mscaddress')) {
            $cache->deleteTree(
                $dev . 'core/components/mscaddress/',
                ['deleteTop' => true, 'skipDirs' => false, 'extensions' => []]
            );
            symlink(MODX_CORE_PATH . 'components/mscaddress/', $dev . 'core/components/mscaddress');
        }
    }
}

return true;