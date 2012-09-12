Ext.define("App.Conf.Productos.Vistas.PanelProductoGrid", {
    extend: "Ext.form.Panel",
    alias: "widget.PanelProductoGrid",
   
    title: '',
    layout: 'form',
    //bodyPadding: '10 10 10',
    border:0,
    
    width:800,
    height:530,
    initComponent: function() {
       
        var grid = Ext.create("App.Conf.Productos.Vistas.GridProductos",{
            width:'100%',
            height:530
        });
        this.items=[grid];
        
    
        this.callParent(arguments);
    }
});