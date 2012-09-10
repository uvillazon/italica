Ext.define("App.Conf.Sucursales.Stores.StoreSucursales",{
    extend      : "Ext.data.Store",
    model       : "App.Conf.Sucursales.Modelos.ModeloSucursal",

    proxy: {
        type: 'ajax',
        url: '../sucursals/getsucursals',
        reader: {
            type: 'json',
            root: 'datos',
            totalProperty: 'total'
        }
    },
    autoLoad: true
});