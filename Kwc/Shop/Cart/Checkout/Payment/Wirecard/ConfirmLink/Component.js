var onReady = require('kwf/on-ready-ext2');

onReady.onRender('.kwcClass', function(el, config) {
    var form = el.child('form');
    el.child('.submit').on('click', function(e) {
        e.preventDefault();
        el.child('.process').show();
        form.hide();
        Ext2.Ajax.request({
            url: config.controllerUrl,
            params: config.params,
            callback: function(response, options) {
                form.dom.submit();
            },
            scope: this
        });
    }, this);
});
