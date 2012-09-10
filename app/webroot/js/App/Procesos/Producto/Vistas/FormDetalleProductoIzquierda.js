Ext.define("App.Procesos.Producto.Vistas.FormDetalleProductoIzquierda", {
    extend: "Ext.form.FieldSet",
    alias: "widget.FormDetalleProductoIzquierda",
    id: 'FormDetalleProductoIzquierda',
    title: '',
    vendedor:'',
    sucursal:'',
    collapsible: false,
    defaults: {
        labelWidth: 89,
        anchor: '100%',
        layout: {
            type: 'hbox',
            defaultMargins: {
                top: 0,
                right: 5,
                bottom: 0,
                left: 0
            }
        }
    },
    initComponent: function() {
        var comboMoneda= {
            width:          40,
            xtype:          'combo',
            mode:           'local',
            value:          '1',
            triggerAction:  'all',
            forceSelection: true,
            editable:       false,
            name:           'title',
            displayField:   'name',
            valueField:     'value',
            queryMode: 'local',
            store:          Ext.create('Ext.data.Store', {
                fields : ['name', 'value'],
                data   : [
                {
                    name : 'Bs',
                    value: '1'
                },
                {
                    name : 'Dolar',
                    value: '2'
                }
                ]
            })
        };
        this.items=[
        {
            xtype:'displayfield',
            fieldLabel:'Fecha',
            value:Ext.Date.format(new Date(),'d/m/Y')
        },{
            xtype:'textfield',
            fieldLabel:'C\u00f3digo Item'
        },
        {
            xtype:'textarea',
            fieldLabel:'Descripci\u00f3n Item',
            rows:2
        },{
            xtype:'fieldcontainer',
            fieldLabel:'Cantidad Disponible',
            items:[{
                xtype:'panel',
                layout:{
                    type:'table',
                    columns:2,
                    tableAttrs: {
                        style: {
                            width: '100%'
                        }
                    }
                },
                baseCls:'x-plain',
                defaults: {
                    frame:true,
                    hideLabel: true,
                    width:73,
                    height:'100%'
                },
                items:[
                {
                    xtype:'displayfield',
                    value:'SAN MARTIN'
                },{
                    xtype:'textfield'
                },{
                    xtype:'displayfield',
                    value:'ALCANTARA'
                },{
                    xtype:'textfield'
                },{
                    xtype:'displayfield',
                    value:'FALSURI'
                },{
                    xtype:'textfield'
                }

                ]
            }]
        },{
            xtype:'fieldcontainer',
            fieldLabel:'Precio Venta',
            defaults: {
                hideLabel: true
            },
            items:[{
                xtype:'textfield',
                width:120
            },{
                xtype:'displayfield',
                value:'Bs.'
            }
            ]
        },{
            xtype:'fieldcontainer',
            fieldLabel:'Ultimo Precio Lista Proveedor',
            defaults: {
                hideLabel: true
            },
            items:[{
                xtype:'textfield',
                width:100
            },comboMoneda
            ]
        },{
            xtype:'textfield',
            fieldLabel:'Fecha Ultimo Precio'

        },{
            xtype:'fieldcontainer',
            fieldLabel:'Penultimo Precio Lista Proveedor',
            defaults: {
                hideLabel: true
            },
            items:[{
                xtype:'textfield',
                width:100
            },comboMoneda
            ]
        },{
            xtype:'textfield',
            fieldLabel:'Fecha Penultimo Precio'

        },{
            xtype:'displayfield',
            fieldLabel:'Vendedor',
            value:this.vendedor
        },
        {
            xtype:'displayfield',
            fieldLabel:'Sucursal',
            value:this.sucursal
        }
        ];
        this.callParent(arguments);
    }
});