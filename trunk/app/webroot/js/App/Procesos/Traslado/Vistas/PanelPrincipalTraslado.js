Ext.define("App.Procesos.Traslado.Vistas.PanelPrincipalTraslado", {
    extend: "Ext.panel.Panel",
    alias: "widget.PanelPrincipalTraslado",
    width:'100%',
    height:'100%',
    initComponent:function(){
         var panelForm=Ext.create("App.Procesos.Traslado.Vistas.FormTraslado");
        var panelGrid=Ext.create("App.Procesos.Producto.Vistas.PanelDetalleProductoGrid",{height:600});
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