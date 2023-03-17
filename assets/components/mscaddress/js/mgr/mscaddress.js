var mscAddress = function (config) {
    config = config || {};
    mscAddress.superclass.constructor.call(this, config);
};
Ext.extend(mscAddress, Ext.Component, {
    page: {}, window: {}, grid: {}, tree: {}, panel: {}, combo: {}, config: {}, view: {}, utils: {}
});
Ext.reg('mscaddress', mscAddress);

mscAddress = new mscAddress();