Ext.define("App.Conf.Articulos.Stores.StoreArticulos",{
    extend      : "Ext.data.Store",
    model       : "App.Conf.Articulos.Modelos.ModeloStoreArticulos",

    proxy: {
        type: 'ajax',
        url: '../articulos/get_articulos',
        reader: {
            type: 'json',
            root: 'datos',
            totalProperty: 'total'
        }
    },
    autoLoad: false,
    pageSize: 20
});