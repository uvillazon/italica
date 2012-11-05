Ext.define("App.Conf.Productos.Vistas.FormProducto", {
    extend: "Ext.form.Panel",
    alias: "widget.FormProducto",
    bodyPadding: '35 5 5',
    title:'Detalle',
    itemSeleccionado:'',
    border:1,
    minWidth:200,
    autoScroll:true,
    minHeight:330,
    fieldDefaults: {
        msgTarget: 'under',
        labelWidth: 75
    },
    defaultType: 'textfield',
    layout: {
        type: 'table',
        columns: 2,
        tdAttrs: {
                    style: {
                        width:'20%'
                    }
                },
                tableAttrs: {
                    style: {
                        width:'98%'
                    }
                }
    },
    defaults: {
        frame:false,
        width:'90%'
    },
    initComponent: function() {
            
        var comboCategorias=Ext.create("App.Conf.Categorias.Vistas.ComboCategorias",{
            id:'comboCatProducto',
            itemId:'categoria',
            width:205,
            readOnly:true,
            name:'data[Producto][categoria_id]'
        });
        var comboMarcas=Ext.create("App.Conf.Marcas.Vistas.ComboMarcas",{
            id:'comboMarProducto',
            readOnly:true,
             width:205,
            itemId:'marca',
            name:'data[Producto][marca_id]'
        });
        var comboUnidades=Ext.create("App.Conf.Unidades.Vistas.ComboUnidades",{
            id:'comboUniProducto',
            readOnly:true,
             width:205,
            itemId:'unidad',
            name:'data[Producto][unidad_id]'
        });

        this.items=[{
            xtype: 'image',
            rowspan:12,
            itemId: 'imagen',
            src: "../app/webroot/img/fotos/unknow.jpg",
            margin: '0 20 0 0',
            width : 150,
            height: 200
        },

        {
            fieldLabel: 'C\u00f3digo',
            name:'data[Producto][producto_codigo]',
            itemId:'codigo',
            readOnly:true,
            minLength:3,
            minLengthText:"N\u00famero de caracteres m\u00ednimo es 3",
            maxLength:12,
            maxLengthText:"N\u00famero de caracteres m\u00e1ximo es 12",
            allowBlank:false
        },{
            fieldLabel: 'C\u00f3digo Prov.',
            name:'data[Producto][producto_codigo_prov]',
            itemId:'codigo_prov',
            minLength:3,
            minLengthText:"N\u00famero de caracteres m\u00ednimo es 3",
            maxLength:12,
            readOnly:true,
            maxLengthText:"N\u00famero de caracteres m\u00e1ximo es 12",
            allowBlank:false
        },
        {
            fieldLabel: 'Producto',
            itemId:'nombre',
            readOnly:true,
            name:'data[Producto][producto_nombre]',
            allowBlank:false
        },{
            xtype: 'fieldcontainer',
            layout: 'hbox',
            fieldDefaults: {
                msgTarget: 'under',
                labelWidth: 75
            },
            items:[
            comboCategorias,
            {
                xtype:'button',
                itemId:'btnCat',
                iconCls:'overlays',
                hidden:true,
                width:25,
                handler: this.AddCategoria
            }]
        },{
            xtype: 'fieldcontainer',
            layout: 'hbox',
            fieldDefaults: {
                msgTarget: 'under',
                labelWidth: 75
            },
            items:[
            comboMarcas,
            {
                xtype:'button',
                iconCls:'overlays',
                itemId:'btnMar',
                hidden:true,
                width:25,
                handler: this.AddMarca
            }]
        },{
            xtype: 'fieldcontainer',
            layout: 'hbox',
            fieldDefaults: {
                msgTarget: 'under',
                labelWidth: 75
            },
            items:[
            comboUnidades,
            {
                xtype:'button',
                iconCls:'overlays',
                itemId:'btnUni',
                hidden:true,
                width:25,
                handler: this.AddUnidad
            }]
        },
        ,
        {
            xtype:'textarea',
            name:'data[Producto][producto_ubicacion]',
            itemId:'ubicacion',
            readOnly:true,
            fieldLabel:'Ubicaci\u00f3n',
            rows:1
        },{
            xtype:'numberfield',
            itemId:'precio',
            fieldLabel:'Precio',
            decimalSeparator:'.',
            readOnly:true,
            name:'data[Producto][producto_precio]',
            hideTrigger:true
        },{
            xtype:'numberfield',
            itemId:'costo',
            fieldLabel:'Costo',
            minValue:0,
            readOnly:true,
            decimalSeparator:'.',
            name:'data[Producto][producto_costo]',
            hideTrigger:true
        },{
            xtype: 'filefield',
            itemId: 'imagenfile',
            emptyText: 'Seleccionar Imagen',
            fieldLabel: 'Imagen',
            hidden:true,
            name: 'photo-path',
            buttonText: '',
            buttonConfig: {
                iconCls: 'image_add'
            }
        },{
            xtype:'numberfield',
            itemId:'stockminimo',
            fieldLabel:'Stock Min.',
            minValue:0,
            name:'data[Producto][producto_cantidad_minima]',
            readOnly:true,
            hideTrigger:true
        },{
            xtype:'fieldset',
            border:1,
            items:[{
                xtype:'fieldcontainer',
                itemId:'stockinicial',
                fieldLabel:'Stock Inicial',
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
                        xtype:'numberfield',
                        itemId:'stockinicial1',
                        value:0,
                        readOnly:true,
                        name:'data[Kardex][1]'
                    },{
                        xtype:'displayfield',
                        value:'ALCANTARA'
                    },{
                        xtype:'numberfield',
                        itemId:'stockinicial2',
                        value:0,
                        readOnly:true,
                        name:'data[Kardex][2]'
                    },{
                        xtype:'displayfield',
                        value:'FALSURI'
                    },{
                        xtype:'numberfield',
                        itemId:'stockinicial3',
                        value:0,
                        readOnly:true,
                        name:'data[Kardex][3]'
                    }

                    ]
                }]
            }

            ]
        },
        
        {
            xtype:'hidden',
            itemId:'id',
            name: 'data[Producto][producto_id]'
        }, {
            xtype:'hidden',
            itemId:'kardex_id_1',
            name: 'data[Kardex][kardex_id_1]'
        },{
            xtype:'hidden',
            itemId:'kardex_id_2',
            name: 'data[Kardex][kardex_id_2]'
        },{
            xtype:'hidden',
            itemId:'kardex_id_3',
            name: 'data[Kardex][kardex_id_3]'
        }
        ];
        this.tbar=[{
            text: 'Guardar',
            itemId:'btnGuardar',
            iconCls: 'disk',
            disabled:true,
            hidden:this.quitarPermisoC,
            handler: this.guardar

        },{
            text: 'Cancelar',
            itemId:'btnCancelar',
            iconCls: 'cross',
            disabled:true,
            handler: this.cancelar

        }];
   

        this.callParent(arguments);
    },
    modoEdicion:function(form){
       
        form.down('#codigo').setReadOnly(false);       
        form.down('#codigo_prov').setReadOnly(false);
        form.down('#nombre').setReadOnly(false);
        form.down('#categoria').setReadOnly(false);
        form.down('#marca').setReadOnly(false);
        form.down('#unidad').setReadOnly(false);
        form.down('#ubicacion').setReadOnly(false);
        form.down('#precio').setReadOnly(false);
        form.down('#costo').setReadOnly(false);
        form.down('#imagenfile').setVisible(true);
        form.down('#stockminimo').setReadOnly(false);
        form.down('#stockinicial1').setReadOnly(false);
        form.down('#stockinicial2').setReadOnly(false);
        form.down('#stockinicial3').setReadOnly(false);
        form.down('#id').setReadOnly(false);
        form.down('#btnCat').setVisible(true);
        form.down('#btnMar').setVisible(true);
        form.down('#btnUni').setVisible(true);
        form.down('#btnGuardar').setDisabled(false);
        form.down('#btnCancelar').setDisabled(false);
         form.down('#codigo').focus("",10);
      
    },
    modoNoEdicion:function(form){
        form.down('#codigo').setReadOnly(true);
        form.down('#codigo_prov').setReadOnly(true);
        form.down('#nombre').setReadOnly(true);
        form.down('#categoria').setReadOnly(true);
        form.down('#marca').setReadOnly(true);
        form.down('#unidad').setReadOnly(true);
        form.down('#ubicacion').setReadOnly(true);
        form.down('#precio').setReadOnly(true);
        form.down('#costo').setReadOnly(true);
        form.down('#imagenfile').setVisible(false);
        form.down('#stockminimo').setReadOnly(true);
        form.down('#stockinicial1').setReadOnly(true);
        form.down('#stockinicial2').setReadOnly(true);
        form.down('#stockinicial3').setReadOnly(true);
        form.down('#id').setReadOnly(true);
        form.down('#btnCat').setVisible(false);
        form.down('#btnMar').setVisible(false);
        form.down('#btnUni').setVisible(false);
        form.down('#btnGuardar').setDisabled(true);
        form.down('#btnCancelar').setDisabled(true);
        
        if(!form.down('#id').getValue()){
            form.limpiarFormulario(form);
        }


        
    //form.down('#imagen').setReadOnly(false);
    },
    cambiarValores:function(form,selection){
        form.down('#codigo').setValue(Ext.String.trim(selection.data['producto_codigo']));
        form.down('#codigo_prov').setValue(Ext.String.trim(selection.data['producto_codigo_prov']));
        form.down('#nombre').setValue(Ext.String.trim(selection.data['producto_nombre']));
        form.down('#categoria').setValue(selection.data['categoria_id']);
        form.down('#marca').setValue(selection.data['marca_id']);
        form.down('#unidad').setValue(selection.data['unidad_id']);
        form.down('#ubicacion').setValue(Ext.String.trim(selection.data['producto_ubicacion']));
        form.down('#precio').setValue(selection.data['producto_precio']);
        form.down('#costo').setValue(selection.data['producto_costo']);
        form.down('#stockminimo').setValue(selection.data['producto_cantidad_minima']);        
        form.down('#id').setValue(selection.data['producto_id']);
        form.down('#kardex_id_1').setValue('');
        form.down('#kardex_id_2').setValue('');
        form.down('#kardex_id_3').setValue('');
        form.down('#imagen').setSrc(selection.data['producto_imagen']); 
        form.down('#stockinicial1').setValue(0);
        form.down('#stockinicial2').setValue(0);
        form.down('#stockinicial3').setValue(0);
        var storeKardex=Ext.create("App.Conf.Productos.Stores.StoreKardex");
        storeKardex.load({
            params:{
                producto_id:selection.data['producto_id']
            }
        });
        storeKardex.on('load',function(){

            storeKardex.each(function(record){
                //console.log(record.sucursal_id);
                if(record.raw['kardex_tipo_mov']=='I'){
                     form.down('#stockinicial').setFieldLabel('Stock Inicial');
                }else{
                    form.down('#stockinicial').setFieldLabel('Stock Actual');
                }
                if (record.raw['sucursal_id']==1){
                    form.down('#stockinicial1').setValue(record.raw['kardex_saldo_cantidad']);
                    form.down('#kardex_id_1').setValue(record.raw['kardex_id']);
                }else{                    
                    if (record.raw['sucursal_id']==2){
                        form.down('#stockinicial2').setValue(record.raw['kardex_saldo_cantidad']);
                        form.down('#kardex_id_2').setValue(record.raw['kardex_id']);
                    }else{                        
                        if (record.raw['sucursal_id']==3){
                            form.down('#stockinicial3').setValue(record.raw['kardex_saldo_cantidad']);
                            form.down('#kardex_id_3').setValue(record.raw['kardex_id']);
                        }
                    }
                }
            });
        });
    },
    AddCategoria:function(){
        var grid=Ext.create("App.Conf.Categorias.Vistas.gridCategorias",{
            title:''
        });
        var win=Ext.create("App.Conf.General.Ventana",{
            title:'Categorias',
            closeAction:'hide',
            items:[grid],
            height:370,
            buttons:[{
                text: 'Salir',
                iconCls: 'cross',
                handler: function(){
                    win.hide();
                    Ext.getCmp('comboCatProducto').store.load();
                }
            }]
        });
        win.show();
    },
    AddUnidad:function(){
        var grid=Ext.create("App.Conf.Unidades.Vistas.gridUnidades",{
            title:''
        });
        var win=Ext.create("App.Conf.General.Ventana",{
            title:'Unidades',
            closeAction:'hide',
            items:[grid],
            height:370,
            buttons:[{
                text: 'Salir',
                iconCls: 'cross',
                handler: function(){
                    win.hide();
                    Ext.getCmp('comboUniProducto').store.load();
                }
            }]
        });
        win.show();
    },
    AddMarca:function(){
        var grid=Ext.create("App.Conf.Marcas.Vistas.gridMarcas",{
            title:''
        });
        var win=Ext.create("App.Conf.General.Ventana",{
            title:'Marcas',
            closeAction:'hide',
            items:[grid],
            height:370,
            buttons:[{
                text: 'Salir',
                iconCls: 'cross',
                handler: function(){
                    win.hide();
                    Ext.getCmp('comboMarProducto').store.load();
                }
            }]
        });
        win.show();
    },
    guardarDatos:function(panelForm,grid){

        var form = panelForm.getForm();
        form.method = 'POST';
        if (form.isValid()) {
            form.submit(
            {
                waitTitle:'Espere por favor',
                waitMsg: 'Enviando datos...',
                url:'../productos/guardar_producto',
                success:function(form, action) {
                    Ext.example.msg('Producto', action.result.msg);
                    grid.recargarStore(action.result.id,grid);
                },
                failure: function(form, action) {

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

    },
    eliminarSeleccionado:function(form,grid){
        var id=form.down('#id').getValue();
        var kardex_id;
        try {
            kardex_id=form.down('#kardex_id').getValue();
        } catch (exception) {
            kardex_id=0;
        }

        Ext.MessageBox.confirm('Confirmar ', 'Desea eliminar el registro seleccionado ?.\n'+
            ' El registro se eliminar\u00e1 definitivamente sin opci\u00f3n a recuperarlo', function(btn){
                if(btn=='yes'){
                    Ext.Ajax.request({
                        url: '../productos/eliminar_producto',
                        params: {
                            id: id,
                            kardex_id:kardex_id
                        },
                        timeout: 3000,
                        method: 'POST',
                        success: function( response ){
                            var info = Ext.decode(response.responseText);
                            if (info.success){
                                grid.recargarStoreSelP(grid);
                                form.limpiarFormulario(form);
                            }
                            Ext.example.msg('Eliminar Producto', info.msg);
                        },

                        failure: function(result) {

                            Ext.example.msg('Eliminar Articulo', 'Error en la conexion, Intentelo nuevamente.',2000);
                        }
                    });
                }
            });


    },
    limpiarFormulario:function(form){
        form.getForm().reset();
        form.down('#imagen').setSrc(Ext.BLANK_IMAGE_URL);
    },
    guardar:function(){

    },
    cancelar:function(){

    },
    eliminar:function(){

    }
});