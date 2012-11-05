Ext.define("App.Procesos.Producto.Vistas.PanelPrincipalProducto", {
    extend: "Ext.panel.Panel",
    alias: "widget.PanelPrincipalProducto",    
    width:'100%',
    height:600,
    layout: 'border',
     defaults: {
        split: true
    },
    initComponent:function(){
        var panelForm=Ext.create("App.Procesos.Producto.Vistas.FormDetalleProducto",{
          region: 'center',
           width:'60%'
        });
        var panelGrid=Ext.create("App.Procesos.Producto.Vistas.PanelDetalleProductoGrid",{
            itemId:'gridItems',
            region: 'east',
            ocultarCosto:false,
            ocultarPrecio:false,
            gridSecundario:panelForm,
            width:'40%'
        });
        this.items=[panelForm,panelGrid];
        this.callParent(arguments);
    },
    actualizarItems:function(panel){
        panel.down('#gridItems').recargarItems(panel.down('#gridItems'));
    }

});