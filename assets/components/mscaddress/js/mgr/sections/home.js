mscAddress.page.Home = function (config) {
    config = config || {};
    Ext.applyIf(config, {
        components: [{
            xtype: 'mscaddress-panel-home',
            renderTo: 'mscaddress-panel-home-div'
        }]
    });
    mscAddress.page.Home.superclass.constructor.call(this, config);
};
Ext.extend(mscAddress.page.Home, MODx.Component);
Ext.reg('mscaddress-page-home', mscAddress.page.Home);