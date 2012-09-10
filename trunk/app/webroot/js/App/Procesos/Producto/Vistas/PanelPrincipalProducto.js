Ext.define("App.Procesos.Producto.Vistas.PanelPrincipalProducto", {
    extend: "Ext.panel.Panel",
    alias: "widget.PanelPrincipalProducto",    
    width:'100%',
    height:'100%',
    initComponent:function(){
         var panelForm=Ext.create("App.Procesos.Producto.Vistas.FormDetalleProducto");
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