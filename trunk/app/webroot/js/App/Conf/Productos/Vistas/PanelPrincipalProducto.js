Ext.define("App.Conf.Productos.Vistas.PanelProducto", {
    extend: "Ext.panel.Panel",
    alias: "widget.PanelProducto",
    width:'100%',
    height:'100%',
    initComponent:function(){
         var panelForm=Ext.create("App.Conf.Productos.Vistas.FormDetalleProducto");
    var panelGrid=Ext.create("App.Procesos.Producto.Vistas.PanelDetalleProductoGrid");
        this.items=[{
            xtype:'panel',
            //id:'main-panel',
            //baseCls:'x-plain',
            layout: {
                type: 'table',
                columns: 2
            },
            
            items:[panelForm,panelGrid]
        }];
        this.callParent(arguments);
    }

});