Ext.define("App.Principal.Stores.StoreMenu",{
    extend      : "Ext.data.TreeStore",
    model       : "App.Principal.Modelos.MenuModel",
    proxy: {
        type: 'ajax',
        url: '../sistemas/getmenu',

        reader: {
            type: 'json',
            root: 'modulos'
        }
    } ,
    idProperty: 'id',
    autoLoad: true
});
