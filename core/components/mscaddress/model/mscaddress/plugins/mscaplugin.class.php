<?php

abstract class mscaPlugin
{
    /** @var modX $modx */
    protected $modx;
    /** @var mscAddress $msca */
    protected $msca;
    /** @var array $scriptProperties */
    protected $scriptProperties;

    public function __construct(modX $modx, &$scriptProperties)
    {
        $this->modx = &$modx;
        $this->scriptProperties =& $scriptProperties;

		$this->msca = $modx->getService('mscAddress', 'mscAddress', MODX_CORE_PATH . 'components/mscaddress/model/');
		if (!$this->msca) {
			return false;
		}
    }

    abstract public function run();
}