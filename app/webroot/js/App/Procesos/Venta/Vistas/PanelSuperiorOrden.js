Ext.define("App.Procesos.Venta.Vistas.PanelSuperiorOrden", {
    extend: "Ext.form.FieldSet",
    alias: "widget.PanelSuperiorOrden",
    id: 'PanelSuperiorOrden',
   
    border:0,
    collapsible: false,   
    initComponent: function() {
        var comboCliente= new Ext.form.ComboBox({
            //width:          40,
            xtype:          'combo',
            fieldLabel:'CODIGO CLIENTE',
            mode:           'local',
            triggerAction:  'all',
            forceSelection: true,
            editable:       false,
            name:           'title',
            emptyText:'SELECCIONE CLIENTE..',
            displayField:   'name',
            valueField:     'value',
            queryMode: 'local',
            store:          Ext.create('Ext.data.Store', {
                fields : ['name', 'value'],
                data   : [
                {
                    name : 'CLIENTE 1',
                    value: '1'
                },{
                    name : 'CLIENTE 2',
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
          
            items:[{
                xtype:'displayfield',
                labelWidth: 50,
                fieldLabel:'NRO ORDEN',
                value:'ORD-100'
            },comboCliente,
            {
                xtype:'displayfield',
                labelWidth: 50,
                rowspan:2,
                fieldLabel:'FECHA',
                value:Ext.Date.format(new Date(),'d/m/Y')
            },
            {
                xtype:'textfield',
                id:'NombreClienteOrdenVenta',
                fieldLabel:'NOMBRE CLIENTE'
            }, {
                xtype:'textfield',
                id:'NitClienteOrdenVenta',
                bodyStyle: 'padding:20px',
                fieldLabel:'NIT'
            }

            ]
        }
        ];
        comboCliente.on('select',function(cmb,record,index){
            
            Ext.getCmp('NombreClienteOrdenVenta').setValue('NOMBRE CLIENTE '+cmb.getValue());
            Ext.getCmp('NitClienteOrdenVenta').setValue('NIT CLIENTE '+cmb.getValue());
        });
        this.callParent(arguments);
    }
});