Ext.define("App.Procesos.Venta.Vistas.PanelCentralCambio", {
    extend: "Ext.form.FieldSet",
    alias: "widget.PanelCentralCambio",
    //border:0,
    collapsible: false,   
    initComponent: function() {
       var gridIngreso=Ext.create("App.Procesos.Venta.Vistas.GridCambioVenta");
       var gridSalida=Ext.create("App.Procesos.Venta.Vistas.GridCambioVenta");
        this.items=[
            {xtype:'displayfield',
             value:'ITEM DE INGRESO'
            },gridIngreso,
            {xtype:'displayfield',
             value:'ITEM DE SALIDA'
            },gridSalida
        ];
        
        this.callParent(arguments);
    }
});