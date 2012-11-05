Ext.define("App.Procesos.Producto.Vistas.FormDetalleProductoIzquierda", {
    extend: "Ext.form.Panel",
    alias: "widget.FormDetalleProductoIzquierda",
    bodyPadding: '5 10 10',
    title: '',
    vendedor:'',
    sucursal:'',
    collapsible: false,
    defaults: {
        labelWidth: 89,
        anchor: '85%',
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
        var gridUltimosPrecios=Ext.create("App.Procesos.Producto.Vistas.GridUltimosPrecios",{
            itemId:'gridUltimosPrecios'
           
        });
        var me=this;
        this.items=[
        {
            xtype:'displayfield',
            fieldLabel:'Fecha',
            value:Ext.Date.format(new Date(),'d/m/Y')
        },{
            xtype:'textfield',
            itemId:'producto_codigo',
            fieldLabel:'C\u00f3digo Item'
        },
        {
            xtype:'textarea',
            fieldLabel:'Descripci\u00f3n Item',
            itemId:'producto_nombre',
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
                    xtype:'textfield',
                    itemId:'cantidad_sm'
                },{
                    xtype:'displayfield',
                    value:'ALCANTARA'
                },{
                    xtype:'textfield',
                    itemId:'cantidad_a'
                },{
                    xtype:'displayfield',
                    value:'FALSURI'
                },{
                    xtype:'textfield',
                    itemId:'cantidad_f'
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
                xtype:'numberfield',
                itemId:'producto_precio',
                name:'data[Producto][producto_precio]',
                decimalSeparator:'.',
                minValue:0,
                hideTrigger:true,
                width:120
            },{
                xtype:'displayfield',
                value:'Bs.'
            },{
                xtype:'button',
                iconCls:'disk',
                tooltip:'Guardar Precio de Venta',
                itemId:'btnSave',               
                width:25,
                handler:function(){
                    me.GuardarPrecio(me);
                }
            }
            ]
        },gridUltimosPrecios,
        {
            xtype:'hidden',
            itemId:'producto_id',
            name:'data[Producto][producto_id]'
        }
        ];
        this.callParent(arguments);
    },
    bindingData:function(form,record){
        
         form.down('#producto_id').setValue(record.data['producto_id']);
        form.down('#producto_codigo').setValue(record.data['producto_codigo']);
        form.down('#producto_nombre').setValue(record.data['producto_nombre']);
        form.down('#producto_precio').setValue(record.data['producto_precio']);
        form.down('#cantidad_sm').setValue(record.data['kardex_saldo_cantidad1']);
        form.down('#cantidad_a').setValue(record.data['kardex_saldo_cantidad2']);
        form.down('#cantidad_f').setValue(record.data['kardex_saldo_cantidad3']);
        form.down('#gridUltimosPrecios').store.load({
            params:{
                producto_id:record.data['producto_id']
            }
        });       
    },
    actualizarItems:function(panel){
        panel.up('FormDetalleProducto').actualizarItems( panel.up('FormDetalleProducto'));
    },
    GuardarPrecio:function(formulario){
        
        var form= formulario.getForm();
       
        form.method = 'POST';
       
            if (form.isValid()) {

                form.submit(
                {
                    waitTitle:'Espere por favor',
                    waitMsg: 'Enviando datos...',
                    url:'../productos/guardar_precio',
                    success:function(form, action) {
                        Ext.example.msg('Guardar', action.result.msg);                       
                     
                        formulario.actualizarItems(formulario);
                    // Ext.getCmp('idordenproduccion').setValue(action.result.idordenproduccion);
                   

                    },
                    failure: function(form, action) {
                    
                        Ext.MessageBox.show({
                            title: 'Error',
                            msg: action.result.msg,
                            buttons: Ext.MessageBox.OK,
                            // activeItem :0,
                            animEl: 'mb9',
                            icon: Ext.MessageBox.ERROR
                        });

                    }
                }
                );


            }
        
        
      
    }
});