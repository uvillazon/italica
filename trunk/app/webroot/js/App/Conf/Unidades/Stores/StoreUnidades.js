Ext.define("App.Conf.Unidades.Stores.StoreUnidades",{
    extend      : "Ext.data.Store",
    model       : "App.Conf.Unidades.Modelos.ModeloStoreUnidades",

    proxy: {
        type: 'ajax',
        url: '../unidads/get_unidades',
        reader: {
            type: 'json',
            root: 'datos',
            totalProperty: 'total'
        }
    },
    autoLoad: false,
    pageSize: 20
});