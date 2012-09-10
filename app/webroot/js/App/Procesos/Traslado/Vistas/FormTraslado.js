Ext.define("App.Procesos.Traslado.Vistas.FormTraslado", {
    extend: "Ext.form.Panel",
    alias: "widget.FormTraslado",
    layout: 'form',
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
       var panelSup=Ext.create("App.Procesos.Traslado.Vistas.PanelSuperiorTraslado");
         var panelCentral=Ext.create("App.Procesos.Traslado.Vistas.GridTraslado");
         var panelInf=Ext.create("App.Procesos.Traslado.Vistas.PanelInferiorTraslado",{
             vendedor:this.vendedor,
            sucursal:this.sucursal
        });
       
        
        this.items=[{
                xtype:'displayfield',
                hideLabel: true,
                value:'<center><h4>TRASLADOS</h4></center>'},
                panelSup,panelCentral,panelInf];

        this.buttons=[{
            text: 'PROFORMA',
            iconCls: 'report',
            handler: this.imprimir
        }];
        this.callParent(arguments);
    },
    imprimir:function(){
        Ext.example.msg('INPRIMIR', 'IMPRESION DE DETALLE');
    }

});