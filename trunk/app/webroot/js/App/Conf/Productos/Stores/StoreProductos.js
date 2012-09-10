Ext.define("App.Conf.Productos.Stores.StoreProductos",{
    extend      : "Ext.data.Store",
    model       : "App.Conf.Productos.Modelos.ModeloStoreProductos",

    proxy: {
        type: 'ajax',
        url: '../productos/get_productos',
        reader: {
            type: 'json',
            root: 'datos',
            totalProperty: 'total'
        }
    },
    autoLoad: false,
    pageSize: 20
});