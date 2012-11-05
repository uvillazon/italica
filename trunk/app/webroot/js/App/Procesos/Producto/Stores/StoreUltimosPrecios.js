Ext.define("App.Procesos.Producto.Stores.StoreUltimosPrecios",{
    extend      : "Ext.data.Store",
    model       : "App.Procesos.Producto.Modelos.ModeloUltimosPrecios",

    proxy: {
        type: 'ajax',
        url: '../productos/get_ultimos_precios',
        reader: {
            type: 'json',
            root: 'datos',
            totalProperty: 'total'
        }
    },
    autoLoad: false,
    pageSize: 20
});