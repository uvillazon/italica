Ext.define("App.Procesos.Traslado.Vistas.PanelPrincipalTraslado", {
    extend: "Ext.panel.Panel",
    alias: "widget.PanelPrincipalTraslado",
    width: '100%',
    resizable:true,
    height: 600,
    vendedor:'',
    frame:true,
    layout: 'border',
    defaults: {
        split: true
    },
    initComponent:function(){
        var me=this;
        
        var panelGridTrasladosRealizados=Ext.create("App.Procesos.Traslado.Vistas.GridTrasladosRealizados",{
            region: 'west',
            id:'GridTrasladosRealizados',
            width:'40%',
            height:600
        });
        var gridCentral=Ext.create("App.Procesos.Traslado.Vistas.GridTraslado",{
            id:'gridDetalleTraslado',
            region:'center'
        });
        var panelGrid=Ext.create("App.Procesos.Producto.Vistas.PanelDetalleProductoGrid",{
            itemId:'gridItemsTraslado',
            region: 'east',           
            gridSecundario:gridCentral,
            ocultarCosto:true,
            ocultarPrecio:false,
            collapsed:true,
            width:'40%',
            height:600
        });
        var panelForm=Ext.create("App.Procesos.Traslado.Vistas.FormTraslado",{
            vendedor:me.vendedor,
            width:'50%',
            gridItems:panelGrid,
            gridForm:gridCentral,
            region: 'center', 
            height:600
        });
       
      
       
   
        panelGrid.on('collapse',function(p,eOpts){
            if(panelGridTrasladosRealizados.getState().collapsed!=false){
                panelGridTrasladosRealizados.expand(true);
            }
          
        });
        panelGridTrasladosRealizados.on('collapse',function(p,eOpts){
            //alert(panelGrid.getState().collapsed);
            if(panelGrid.getState().collapsed!=false){
                panelGrid.expand(true);
            }
           
        });
        panelGrid.on('expand',function(p,eOpts){
            if(panelGridTrasladosRealizados.getState().collapsed==false){
                panelGridTrasladosRealizados.collapse(); 
            }
        });
        panelGridTrasladosRealizados.on('expand',function(p,eOpts){
            if(panelGrid.getState().collapsed==false){
                panelGrid.collapse();
            }
           
        });
        panelGridTrasladosRealizados.getSelectionModel().on('selectionchange', function(sm, selectedRecord) {
            if (selectedRecord.length) {
                panelForm.bindingForm(selectedRecord[0].data);            
            }
        });
        
        this.items=[panelGridTrasladosRealizados,panelForm,panelGrid];

        this.callParent(arguments);
    },
    recargarItemsProductos:function(panel){
        panel.down('#gridItemsTraslado').recargarItems(panel.down('#gridItemsTraslado'));
    }
});