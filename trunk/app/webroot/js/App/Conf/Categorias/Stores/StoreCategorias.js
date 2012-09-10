Ext.define("App.Conf.Categorias.Stores.StoreCategorias",{
    extend      : "Ext.data.Store",
    model       : "App.Conf.Categorias.Modelos.ModeloStoreCategorias",

    proxy: {
        type: 'ajax',
        url: '../categorias/get_categorias',
        reader: {
            type: 'json',
            root: 'datos',
            totalProperty: 'total'
        }
    },
    autoLoad: false,
    pageSize: 20
});