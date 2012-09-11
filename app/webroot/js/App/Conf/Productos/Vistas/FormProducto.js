Ext.define("App.Conf.Articulos.Vistas.FormNuevoArticulo", {
    extend: "App.Conf.General.FormGeneral",
    alias: "widget.FormNuevoArticulo",
    id: 'FormNuevoArticulo',
    itemSeleccionado:'',
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
        
        if (this.itemSeleccionado){
            imagen=Ext.String.trim(this.itemSeleccionado.data['articulo_imagen']);
            
            if(imagen==''){
                imagen="unknow.jpg";
            }
           // alert(imagen);
            descripcion=Ext.String.trim(this.itemSeleccionado.data['articulo_descripcion']);
            id=this.itemSeleccionado.data['articulo_id'];
            codigo=this.itemSeleccionado.data['articulo_codigo'];
            categoria_id=this.itemSeleccionado.data['categoria_id'];
        }
         var comboCategorias=new Ext.form.ComboBox({
            id:'comboCategorias',
            name: 'data[Articulo][categoria_id]',
            fieldLabel: 'Categoria',
            valueField:'categoria_id',
            displayField:'categoria_nombre',
            typeAhead: true,
            store: Ext.create("App.Conf.Categorias.Stores.StoreCategorias").load(),
            emptyText:'SELECCIONE CATEGORIA....',
            queryMode: 'local',
            forceSelection: true,
            allowBlank:false

        });
       comboCategorias.store.on('load',function(){
           comboCategorias.setValue(categoria_id);
       });
        this.items=[{
            xtype: 'panel',
            rowspan:4,
            border:0,
            title:'',
            html:'<img src="../app/webroot/img/fotos/'+imagen+'" width=100px height=100px />',
            width:100,
            height:100
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
        },
        {
            fieldLabel: 'Articulo',
            name: 'data[Articulo][articulo_descripcion]',
            value:descripcion,
            allowBlank:false
        },
        comboCategorias,
        {
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