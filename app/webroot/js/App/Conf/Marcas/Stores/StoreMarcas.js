Ext.define("App.Conf.Marcas.Stores.StoreMarcas",{
    extend      : "Ext.data.Store",
    model       : "App.Conf.Marcas.Modelos.ModeloStoreMarcas",

    proxy: {
        type: 'ajax',
        url: '../marcas/get_marcas',
        reader: {
            type: 'json',
            root: 'datos',
            totalProperty: 'total'
        }
    },
    autoLoad: false,
    pageSize: 20
});