mscAddress.panel.Home = function (config) {
    config = config || {};
    Ext.apply(config, {
        baseCls: 'modx-formpanel',
        layout: 'anchor',
        /*
         stateful: true,
         stateId: 'mscaddress-panel-home',
         stateEvents: ['tabchange'],
         getState:function() {return {activeTab:this.items.indexOf(this.getActiveTab())};},
         */
        hideMode: 'offsets',
        items: [{
            html: '<h2>' + _('mscaddress') + '</h2>',
            cls: '',
            style: {margin: '15px 0'}
        }, {
            xtype: 'modx-tabs',
            defaults: {border: false, autoHeight: true},
            border: true,
            hideMode: 'offsets',
            items: [{
                title: _('mscaddress_items'),
                layout: 'anchor',
                items: [{
                    html: _('mscaddress_intro_msg'),
                    cls: 'panel-desc',
                }, {
                    xtype: 'mscaddress-grid-items',
                    cls: 'main-wrapper',
                }]
            }]
        }]
    });
    mscAddress.panel.Home.superclass.constructor.call(this, config);
};
Ext.extend(mscAddress.panel.Home, MODx.Panel);
Ext.reg('mscaddress-panel-home', mscAddress.panel.Home);
