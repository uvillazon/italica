Ext.define("App.Procesos.Traslado.Vistas.FormTraslado", {
    extend: "Ext.panel.Panel",
    alias: "widget.FormTraslado",
   
    layout: 'border',
    title:'DETALLE TRASLADO',
    titleAlign:'center',
    vendedor:'NOMBRE VENDEDOR',
    sucursal:'NOMBRE SUCURSAL',
    bodyPadding: '5 5 5',
    
    minWidth:200,
    minHeight:400,
    buttonAlign:'left',
    gridItems:'',
    gridForm:'',
    fieldDefaults: {
        msgTarget: 'side',
        labelWidth: 75
        
    },
    
    initComponent: function() {
        var me=this;
        var panelSup=Ext.create("App.Procesos.Traslado.Vistas.PanelSuperiorTraslado",{
            id:'panelSuperiorTraslado',
            itemId:'panelSuperiorTraslado',
            grid:this.gridForm,
            region:'north'
        });
        var panelCentral=this.gridForm;
        var panelInf=Ext.create("App.Procesos.Traslado.Vistas.PanelInferiorTraslado",{
            id:'panelInferiorTraslado',
            vendedor:this.vendedor,
            sucursal:this.sucursal,
            region:'south'
        });
       
        
        this.items=[
        panelSup,panelCentral,panelInf];

        this.buttons=[{
            text: 'REGISTRAR TRASLADO',
            iconCls: 'disk',             
            handler: function(){
                 var gridTraslados=Ext.getCmp('GridTrasladosRealizados');
                panelSup.guardar(panelSup,panelCentral,gridTraslados,me.gridItems);  
            }
        },{
            text: 'NUEVO TRASLADO',
            iconCls: 'page_white',             
            handler: function(){
                panelSup.getForm().reset();
                panelInf.getForm().reset();
                panelCentral.store.removeAll();
            }
        }
    ];
        this.callParent(arguments);
    },
    bindingForm:function(data){
        //console.log(data);
        var panelSuperior=Ext.getCmp('panelSuperiorTraslado');
       
        var grid=Ext.getCmp('gridDetalleTraslado');
     
        panelSuperior.down('#comboOrigen').setValue(data.sucursal_origen);  
        panelSuperior.down('#comboDestino').setValue(data.sucursal_destino);
      
        
        panelSuperior.down('#traslado_fecha').setValue( Ext.Date.format(Ext.Date.parse(data.traslado_fecha,'Y-m-d H:i:s'),'d-m-Y'));
       
        panelSuperior.down('#nro_registro').setValue(data.traslado_id);
        panelSuperior.down('#val-traslado_cantidad').setValue(data.traslado_cantidad);
        
         panelSuperior.down('#val-traslado_cantidad').setValue(data.traslado_cantidad);
         panelSuperior.down('#val-traslado_precio_total').setValue(data.traslado_precio_total+data.traslado_descuento);
       
        
        grid.store.load({
            params:{
                traslado_id:data.traslado_id
            }
        });
        
    }

});