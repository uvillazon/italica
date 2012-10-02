Ext.define("App.Conf.Productos.Stores.StoreKardex",{
    extend      : "Ext.data.Store",
    model       : "App.Conf.Productos.Modelos.ModeloStoreKardex",

    proxy: {
        type: 'ajax',
        url: '../kardexs/get_kardex',
        reader: {
            type: 'json',
            root: 'datos',
            totalProperty: 'total'
        }
    },
    autoLoad: false
});