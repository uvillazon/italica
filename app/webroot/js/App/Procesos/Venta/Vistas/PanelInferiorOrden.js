Ext.define("App.Procesos.Venta.Vistas.PanelInferiorOrden", {
    extend: "Ext.form.FieldSet",
    alias: "widget.PanelInferiorOrden",
    id: 'PanelInferiorOrden',
    title: '',
    vendedor:'',
    sucursal:'',
    border:0,
    
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

            items:[{
                xtype:'displayfield'
            },{
                xtype:'fieldset',
                collapsible: false,
                rowspan:2,
                defaults: {
                    labelWidth: 89,
                    hideTrigger:true,
                    //anchor: '100%',
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
                items:[{
                    xtype:'numberfield',
                    id:'totalParcial',
                    fieldLabel:'TOTAL PARCIAL',
                    value:'0',
                    listeners:{
                        'change':function(){
                            Ext.getCmp('valorDescuento').setValue(Ext.getCmp('procentajeDescuento').getValue()*this.getValue()/100);
                            Ext.getCmp('valorTotalFinal').setValue(this.getValue()+Ext.getCmp('valorRecargo').getValue()-Ext.getCmp('valorDescuento').getValue());

                        }
                    }

                },{
                    xtype: 'fieldcontainer',
                    fieldLabel: 'DESCUENTO',
                    combineErrors: true,

                    
                    msgTarget: 'under',
                    defaults: {
                        hideLabel: true,
                         hideTrigger:true
                    },
                    items: [

                    {
                        xtype: 'numberfield',
                        id:'procentajeDescuento',
                        fieldLabel: '%',
                        name: 'pocentaje',
                        width: 29,
                        value:'0',
                        allowBlank: false,
                        listeners:{
                            'change':function(){
                                Ext.getCmp('valorDescuento').setValue(Ext.getCmp('totalParcial').getValue()*this.getValue()/100);
                                Ext.getCmp('valorTotalFinal').setValue(Ext.getCmp('totalParcial').getValue()+Ext.getCmp('valorRecargo').getValue()-Ext.getCmp('valorDescuento').getValue());
                            }
                        }
                    },

                    {
                        xtype: 'displayfield',
                        value: '%'
                    },

                    {
                        xtype: 'numberfield',
                        id:'valorDescuento',
                        fieldLabel: 'Descuento',
                        name: 'descuento',
                        value:0,
                        width: 100,
                        margins: '0 5 0 0'
                    }
                    ]
                },
                {
                    xtype:'numberfield',
                    id:'valorRecargo',
                    fieldLabel:'RECARGOS',
                    value:0,
                     listeners:{
                        'change':function(){
                            Ext.getCmp('valorTotalFinal').setValue(Ext.getCmp('totalParcial').getValue()+this.getValue()-Ext.getCmp('valorDescuento').getValue());
                        }
                    }

                },{
                    xtype:'numberfield',
                    id:'valorTotalFinal',
                    value:0,
                    fieldLabel:'TOTAL FINAL'

                }]
            },
            {
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
            }
            

            ]
        }
        ];
       
        this.callParent(arguments);
    }
});