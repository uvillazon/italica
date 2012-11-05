Ext.define("App.Procesos.Traslado.Stores.StoreTraslados",{
    extend      : "Ext.data.Store",
    model       : "App.Procesos.Traslado.Modelos.ModeloStoreTraslados",
    proxy: {
        type: 'ajax',
        url: '../traslados/get_traslados',
        reader: {
            type: 'json',
            root: 'datos',
            totalProperty: 'total'
        }
    },
    autoLoad: true,
    pageSize:20
});