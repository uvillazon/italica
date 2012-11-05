Ext.define("App.Procesos.Traslado.Stores.StoreDetalleTraslados",{
    extend      : "Ext.data.Store",
    model       : "App.Procesos.Traslado.Modelos.ModeloStoreDetalleTraslados",
    proxy: {
        type: 'ajax',
        url: '../traslados/get_detalle_traslado',
        reader: {
            type: 'json',
            root: 'datos',
            totalProperty: 'total'
        }
    },
    autoLoad: false
});