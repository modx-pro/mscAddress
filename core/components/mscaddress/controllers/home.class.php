<?php

/**
 * The home manager controller for mscAddress.
 *
 */
class mscAddressHomeManagerController extends modExtraManagerController
{
    /** @var mscAddress $mscAddress */
    public $mscAddress;


    /**
     *
     */
    public function initialize()
    {
        $this->mscAddress = $this->modx->getService('mscAddress', 'mscAddress', MODX_CORE_PATH . 'components/mscaddress/model/');
        parent::initialize();
    }


    /**
     * @return array
     */
    public function getLanguageTopics()
    {
        return ['mscaddress:default'];
    }


    /**
     * @return bool
     */
    public function checkPermissions()
    {
        return true;
    }


    /**
     * @return null|string
     */
    public function getPageTitle()
    {
        return $this->modx->lexicon('mscaddress');
    }


    /**
     * @return void
     */
    public function loadCustomCssJs()
    {
        $this->addCss($this->mscAddress->config['cssUrl'] . 'mgr/main.css');
        $this->addJavascript($this->mscAddress->config['jsUrl'] . 'mgr/mscaddress.js');
        $this->addJavascript($this->mscAddress->config['jsUrl'] . 'mgr/misc/utils.js');
        $this->addJavascript($this->mscAddress->config['jsUrl'] . 'mgr/misc/combo.js');
        $this->addJavascript($this->mscAddress->config['jsUrl'] . 'mgr/widgets/items.grid.js');
        $this->addJavascript($this->mscAddress->config['jsUrl'] . 'mgr/widgets/items.windows.js');
        $this->addJavascript($this->mscAddress->config['jsUrl'] . 'mgr/widgets/home.panel.js');
        $this->addJavascript($this->mscAddress->config['jsUrl'] . 'mgr/sections/home.js');

        $this->addHtml('<script type="text/javascript">
        mscAddress.config = ' . json_encode($this->mscAddress->config) . ';
        mscAddress.config.connector_url = "' . $this->mscAddress->config['connectorUrl'] . '";
        Ext.onReady(function() {MODx.load({ xtype: "mscaddress-page-home"});});
        </script>');
    }


    /**
     * @return string
     */
    public function getTemplateFile()
    {
        $this->content .= '<div id="mscaddress-panel-home-div"></div>';

        return '';
    }
}