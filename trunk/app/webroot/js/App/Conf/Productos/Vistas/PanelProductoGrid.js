Ext.define("App.Conf.Productos.Vistas.PanelProductoGrid", {
    extend: "Ext.form.Panel",
    alias: "widget.PanelProductoGrid",
   
    title: '',
    layout: 'fit',
    //bodyPadding: '10 10 10',
    border:0,
    
    width:600,
    
    height:530,
    initComponent: function() {
        this.callParent(arguments);
    }
    
});