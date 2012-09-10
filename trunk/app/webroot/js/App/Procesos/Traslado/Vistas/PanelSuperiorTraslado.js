Ext.define("App.Procesos.Traslado.Vistas.PanelSuperiorTraslado", {
    extend: "Ext.form.FieldSet",
    alias: "widget.PanelSuperiorTraslado",
    border:0,
    collapsible: false,   
    initComponent: function() {
        var origen= Ext.create("App.Conf.Sucursales.Vistas.ComboSucursales",{fieldLabel:'PUNTO ORIGEN'});
        var destino= Ext.create("App.Conf.Sucursales.Vistas.ComboSucursales",{fieldLabel:'PUNTO DESTINO'});
        this.items=[{
            xtype:'panel',
            baseCls:'x-plain',

            layout: {
                type: 'table',
                columns: 2,
                tableAttrs: {
                    style: {
                        width: '100%',
                        bodyStyle: 'padding:5px'
                    }
                }
            },
          
            items:[{
                xtype:'displayfield',
                labelWidth: 50,
                fieldLabel:'NRO ORDEN',
                value:'ORD-100'
            },origen,
            {
                xtype:'displayfield',
                labelWidth: 50,
                rowspan:2,
                fieldLabel:'FECHA',
                value:Ext.Date.format(new Date(),'d/m/Y')
            },
            destino

            ]
        }
        ];
        this.callParent(arguments);
    }
});