Ext.define("App.Procesos.Producto.Vistas.FormDetalleProducto", {
    extend: "Ext.panel.Panel",
    alias: "widget.FormDetalleProducto",
    layout: 'border',    
    frame: true,
    resizable:true,
    vendedor:'NOMBRE VENDEDOR',
    sucursal:'NOMBRE SUCURSAL',
    bodyPadding: '5 5 5',
    width:'100%',
    height:530,
    
    buttonAlign:'left',
    title:'DETALLE DE PRODUCTO',
    titleAlign:'center',
    fieldDefaults: {
        msgTarget: 'side',
        labelWidth: 75
    },
    defaultType: 'textfield',
    initComponent: function() {
        var panelIzq=Ext.create("App.Procesos.Producto.Vistas.FormDetalleProductoIzquierda",{
            itemId:'formDatos',
            vendedor:this.vendedor,
            sucursal:this.sucursal,
            region:'west',
            width:'60%'
        });
        var panelDer=Ext.create("App.Procesos.Producto.Vistas.FormDetalleProductoDerecho",{
             itemId:'panelImagen',
            height:450,
            region:'east',
            width:'40%'
        });
        
        this.items=[panelIzq,panelDer];

        this.buttons=[{
            text: 'REPORTE INGRESOS',
            iconCls: 'report',
            handler: this.ingresos
        }, '-',{
            text: 'REPORTE EGRESOS',
            iconCls: 'report',
            handler: this.egresos
        }];
        this.callParent(arguments);
    },
    ingresos:function(){
        Ext.example.msg('REPORTE', 'REPORTE INGRESOS');
    },
    egresos:function(){
        Ext.example.msg('REPORTE', 'REPORTE EGRESOS');
    }
    ,
      addRow:function(panel,record){
       panel.down('#formDatos').bindingData(panel.down('#formDatos'),record);
         panel.down('#panelImagen').down('#imagen').setSrc(record.data['producto_imagen']);
    },
    actualizarItems:function(panel){
        panel.up('PanelPrincipalProducto').actualizarItems( panel.up('PanelPrincipalProducto'));
    }

});