Ext.define("App.Conf.Productos.Vistas.FormProducto", {
    extend: "Ext.form.Panel",
    alias: "widget.FormProducto",
    bodyPadding: '15 35 35',
    title:'Detalle',
    itemSeleccionado:'',
    border:0,
    height:530,
    fieldDefaults: {
        msgTarget: 'under',
        labelWidth: 75
    },
    defaultType: 'textfield',
    layout: {
        type: 'table',
        columns: 2
    },
    defaults: {
        frame:false,
        width:'90%'
    },
    initComponent: function() {
        var imagen,descripcion,id,codigo,categoria_id;
      
        var comboCategorias=Ext.create("App.Conf.Categorias.Vistas.ComboCategorias");
        var comboMarcas=Ext.create("App.Conf.Marcas.Vistas.ComboMarcas");
        var comboUnidades=Ext.create("App.Conf.Unidades.Vistas.ComboUnidades");

        this.items=[{
            xtype:'fieldset',
            rowspan:10,
            collapsible:false,
            bodyPadding: '10 10 10',
            border:0,
            items:[{
                xtype: 'panel',               
                border:0,
                title:'',
                html:'<img src="../app/webroot/img/fotos/'+imagen+'" width=200px height=200px />',
                width:200,
                height:200
            }]
        },

        {
            fieldLabel: 'C\u00f3digo',
            name: 'data[Articulo][articulo_codigo]',
            
            value:codigo,
            minLength:3,
            minLengthText:"N\u00famero de caracteres m\u00ednimo es 3",
            maxLength:6,
            maxLengthText:"N\u00famero de caracteres m\u00e1ximo es 6",
            allowBlank:false
        },{
            fieldLabel: 'C\u00f3digo Prov.',
            name: 'data[Articulo][articulo_codigo]',
            
            value:codigo,
            minLength:3,
            minLengthText:"N\u00famero de caracteres m\u00ednimo es 3",
            maxLength:6,
            maxLengthText:"N\u00famero de caracteres m\u00e1ximo es 6",
            allowBlank:false
        },
        {
            fieldLabel: 'Producto',
         
            name: 'data[Articulo][articulo_descripcion]',
            value:descripcion,
            allowBlank:false
        },{
            xtype: 'fieldcontainer',
            layout: 'hbox',
            fieldDefaults: {
                msgTarget: 'under',
                labelWidth: 90
            },
            items:[
            comboCategorias,
            {
                xtype:'button',
                iconCls:'add',
                width:30
            }]
        },{
            xtype: 'fieldcontainer',
            layout: 'hbox',
            fieldDefaults: {
                msgTarget: 'under',
                labelWidth: 90
            },
            items:[
            comboMarcas,
            {
                xtype:'button',
                iconCls:'add',
                width:30
            }]
        },{
            xtype: 'fieldcontainer',
            layout: 'hbox',
            fieldDefaults: {
                msgTarget: 'under',
                labelWidth: 90
            },
            items:[
            comboUnidades,
            {
                xtype:'button',
                iconCls:'add',
                width:30
            }]
        },
        ,
        {
            xtype:'textarea',
            name:'data[Producto][ubicacion]',
            fieldLabel:'Ubicaci\u00f3n',
            rows:2
        },{
            fieldLabel:'Stock Inicial',
            name:'stockinicial'

        },{
            fieldLabel:'Stock Min.',
            name:'stockinicial'
        },{
            xtype: 'filefield',
            id: 'imagenArticulo',
            emptyText: 'Seleccionar Imagen',
            fieldLabel: 'Imagen',
            name: 'photo-path',
            buttonText: '',
            
            buttonConfig: {
                iconCls: 'image_add'
            }
        },
        
        {
            xtype:'hidden',
            value:id,
            name: 'data[Articulo][articulo_id]'
        }
        ];
        this.bbar=[{
            text: 'Guardar',
            iconCls: 'disk',
            hidden:this.quitarPermisoC,
            handler: this.guardar
        }];

        this.callParent(arguments);
    },   
    guardar:function(){      
      
        var form = Ext.getCmp("FormNuevoArticulo").getForm();
        form.method = 'POST';
        if (form.isValid()) {
            form.submit(
            {
                waitTitle:'Espere por favor',
                waitMsg: 'Enviando datos...',
                url:'../articulos/guardar_articulo',
                success:function(form, action) {
                   
                    Ext.getCmp("FormNuevoArticulo").recargarStore(action.result.id);
                    Ext.example.msg('Articulo', action.result.msg);
                    Ext.getCmp("FormNuevoArticulo").cancelar();
                },
                failure: function(form, action) {
                    Ext.getCmp("FormNuevoArticulo").cancelar();
                    Ext.MessageBox.show({
                        title: 'Error',
                        msg: action.result.msg,
                        buttons: Ext.MessageBox.OK,
                        // activeItem :0,
                        animEl: 'mb9',
                        icon: Ext.MessageBox.ERROR,
                        fn: function(btn){
                            if(action.result.redir){
                                Ext.MessageBox.wait('Direccionado  al formulario de Ingreso','Direccionando');
                                self.location='../';
                            }
                        }
                    });
                }
            }
            );
        //
        }

    }
});