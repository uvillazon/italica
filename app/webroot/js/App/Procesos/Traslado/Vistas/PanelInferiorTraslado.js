Ext.define("App.Procesos.Traslado.Vistas.PanelInferiorTraslado", {
    extend: "Ext.form.Panel",
    alias: "widget.PanelInferiorTraslado",
    title: '',
    vendedor:'',
    sucursal:'',
    border:0,
    bodyPadding: '5 10 10',
    collapsible: false,

    initComponent: function() {
       
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

            items:[ {
                xtype:'fieldset',
                collapsible: false,
                border:0,
                items:[{
                    xtype:'displayfield',
                    fieldLabel:'VENDEDOR',
                    value:this.vendedor
                },
                {
                    xtype:'displayfield',
                    fieldLabel:'SUCURSAL',
                    value:this.sucursal
                }]
            },
            {
                xtype:'fieldset',
                collapsible: false,
                border:0,
                items:[{
                    xtype:'textfield',
                    fieldLabel:'CANTIDAD CAJAS'
                },
                {
                    xtype:'textfield',
                    fieldLabel:'CANTIDAD AMARROS'
                }]
            }
            

            ]
        }
        ];
       
        this.callParent(arguments);
    }
});