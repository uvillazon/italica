Ext.define("App.Procesos.Venta.Vistas.PanelInferiorCambio", {
    extend: "Ext.form.FieldSet",
    alias: "widget.PanelInferiorCambio",
    title: '',
    vendedor:'',
    sucursal:'',
    border:0,    
    collapsible: false,
    initComponent: function() {
       var comboEncargado= new Ext.form.ComboBox({
            //width:          40,
            xtype:          'combo',
            fieldLabel:'AUTORIZADO POR',
            mode:           'local',
            triggerAction:  'all',
            forceSelection: true,
            editable:       false,
            name:           'title',
            emptyText:'SELECCIONE ENCARGADO..',
            displayField:   'name',
            valueField:     'value',
            queryMode: 'local',
            store:          Ext.create('Ext.data.Store', {
                fields : ['name', 'value'],
                data   : [
                {
                    name : 'RESPONSABLE 1',
                    value: '1'
                },{
                    name : 'RESPONSABLE 2',
                    value: '2'
                }
                ]
            })
        });
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

            items:[
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
            },
            {
                xtype:'fieldset',
                collapsible: false,
                border:0,
                items:[comboEncargado]
            }
            

            ]
        }
        ];
       
        this.callParent(arguments);
    }
});