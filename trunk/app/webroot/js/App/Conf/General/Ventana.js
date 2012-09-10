Ext.define("App.Conf.General.Ventana", {
    extend: "Ext.window.Window",
    alias: "widget.VentanaGeneral",
    title: "",
    closable: true,
    autoDestroy:true,
    //closeAction: 'hide',
    modal:true,
    autoScroll:true,
    width: 500,
    minWidth: 100,
    height: 500,
    items:[],

    initComponent: function() {

        this.callParent(arguments);
    }
});