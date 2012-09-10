Ext.define("App.Procesos.Venta.Vistas.FormOrdenVenta", {
    extend: "Ext.form.Panel",
    alias: "widget.FormOrdenVenta",
    layout: 'form',
    id: 'FormOrdenVenta',
    frame: false,
    resizable:false,
    vendedor:'NOMBRE VENDEDOR',
    sucursal:'NOMBRE SUCURSAL',
    bodyPadding: '5 5 5',
    width:500,
    height:600,
    buttonAlign:'left',
    fieldDefaults: {
        msgTarget: 'side',
        labelWidth: 75
    },
    defaultType: 'textfield',
    initComponent: function() {
        var panelSup=Ext.create("App.Procesos.Venta.Vistas.PanelSuperiorOrden");
         var panelCentral=Ext.create("App.Procesos.Venta.Vistas.GridOrdenVenta");
         var panelInf=Ext.create("App.Procesos.Venta.Vistas.PanelInferiorOrden",{
             vendedor:this.vendedor,
            sucursal:this.sucursal
        });
       
        
        this.items=[{
                xtype:'displayfield',
                hideLabel: true,
                value:'<center><h4>ORDEN DE VENTA</h4></center>'},
                panelSup,panelCentral,panelInf];

        this.buttons=[{
            text: 'PROFORMA',
            iconCls: 'report',
            handler: this.proforma
        }, '-',{
            text: 'VENTA',
            iconCls: 'report',
            handler: this.venta
        }];
        this.callParent(arguments);
    },
    proforma:function(){
        Ext.example.msg('PROFORMA', 'IMPRESION DE PROFORMA');
    },
    venta:function(){
        Ext.example.msg('VENTA', 'IMPRESION VENTA');
    }

});