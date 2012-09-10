Ext.define("App.Procesos.Producto.Vistas.FormDetalleProducto", {
    extend: "Ext.form.Panel",
    alias: "widget.FormDetalleProducto",
    layout: 'form',
    id: 'FormDetalleProducto',
    frame: false,
    resizable:false,
    vendedor:'NOMBRE VENDEDOR',
    sucursal:'NOMBRE SUCURSAL',
    bodyPadding: '5 5 5',
    width:'100%',
    height:530,
    buttonAlign:'left',
    fieldDefaults: {
        msgTarget: 'side',
        labelWidth: 75
    },
    defaultType: 'textfield',
    initComponent: function() {
        var panelIzq=Ext.create("App.Procesos.Producto.Vistas.FormDetalleProductoIzquierda",{
            vendedor:this.vendedor,
            sucursal:this.sucursal
        });
        var panelDer=Ext.create("App.Procesos.Producto.Vistas.FormDetalleProductoDerecho",{
            height:450
        });
        
        this.items=[{
            xtype:'panel',
            id:'main-panel',
            baseCls:'x-plain',
            layout: {
                type: 'table',
                columns: 2
            },
            defaults: {
                frame:true,
                width:'100%',
                height:'100%'
            },
            items:[{
                xtype:'displayfield',
                hideLabel: true,
                colspan:2,
                value:'<center><h4>DETALLE DE PRODUCTO</h4></center>'
            },panelIzq,panelDer]
        }];

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

});