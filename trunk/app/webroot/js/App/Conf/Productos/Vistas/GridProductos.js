Ext.define("App.Conf.Productos.Vistas.GridProductos",{
    extend      : "Ext.grid.Panel",
    alias: 'widget.GridProductos',
    title:'Lista de Productos',
    border      : true,
    miVentana:'',
    store: Ext.create("App.Conf.Productos.Stores.StoreProductos"),
    loadMask: true,
    quitarPermisoW:false,// quitar permisos para modificar
    quitarPermisoC:false,//quitar permisos para crear
    quitarPermisoD:false,// quitar permisos para borrar
    initComponent   : function() {
        var me = this;     
          var mascara=new Ext.LoadMask(this, {
            msg:'Cargando......'
        });
        me.columns = [
        Ext.create('Ext.grid.RowNumberer'),
        {
            header:"Producto Id",
            dataIndex:"producto_id",
            width:20,
            hidden:true
        }, {
            header:"Producto Id",
            dataIndex:"categoria_id",
            width:20,
            hidden:true
        },{
            header:"Marca Id",
            dataIndex:"marca_id",
            width:20,
            hidden:true
        },{
            header:"Unidad Id",
            dataIndex:"unidad_id",
            width:20,
            hidden:true
        },{
            header:"Producto Img",
            dataIndex:"producto_imagen",
            width:20,
            hidden:true
        },{
            header:"Producto",
            dataIndex:"producto_nombre",
            width:150
        },{
            header:"C\u00f3digo",
            dataIndex:"producto_codigo",
            flex:1
        },{
            header:"C\u00f3digo Prov.",
            dataIndex:"producto_codigo_prov",
            flex:1
        },{
            header:"Categoria",
            dataIndex:"categoria_nombre",
            flex:1
        },{
            header:"Marca",
            dataIndex:"marcanombre",
            flex:1
        },{
            header:"Unidad",
            dataIndex:"unidad_sigla",
            flex:1
        },{
            header:"Ubicac\u00f3n",
            dataIndex:"producto_ubicacion",
            flex:1
        },{
            header:"Stock Minimo",
            dataIndex:"producto_cantidad_minima",
            flex:1
        },{
            header:"Precio",
            dataIndex:"producto_precio",
            flex:1
        },{
            header:"Costo",
            dataIndex:"producto_costo",
            flex:1
        }
        ];
        me. tbar=[{
            text: 'Nuevo',
            iconCls: 'add',
            hidden:me.quitarPermisoC,
            handler: me.nuevo
        }, '-',{
            text: 'Modificar',
            itemId: 'edit',
            disabled: true,
            iconCls: 'page_edit',
            hidden:me.quitarPermisoW,
            handler: me.modificar
        },'-', {
            itemId: 'delete',
            text: 'Eliminar',
            hidden:me.quitarPermisoD,
            iconCls: 'delete',
            disabled: true,
            handler: me.eliminar
        
        }];
        me.bbar= Ext.create('Ext.PagingToolbar', {
            store: me.store,
            displayInfo: true,
            displayMsg: 'Mostrando {0} - {1} de {2}',
            emptyMsg: "No existe datos para mostrar"
        });
        me.store.loadPage(1);
        me.store.on('beforeload',function(){
            mascara.show();
        });
        me.store.on('refresh',function(){
            mascara.hide();
        });
        me.store.on('load', function(store, records, options){
           // me.getSelectionModel().select(0);
            mascara.hide();
        });
        me.getSelectionModel().on('selectionchange', function(selModel, selections,selectedRecord){
            me.down('#delete').setDisabled(selections.length === 0);
            me.down('#edit').setDisabled(selections.length === 0);

        });

        me.callParent();
    },
    //funciones para el grid
  
    recargarStore:function(id){
        var grid = Ext.getCmp("gridArticulos");
        grid.store.load();
        grid.store.on('load', function(store, records, options){
            grid.store.each(function(record){
                if (record.raw['articulo_id']==parseInt(id)){
                    grid.getSelectionModel().select(record.index);
                }
            });
        });
    },

    nuevo: function(){
        var grid= Ext.getCmp("gridArticulos");
        var Form=Ext.create("App.Conf.Articulos.Vistas.FormNuevoArticulo",{
             width:'100%',
             height:200,
            cancelar:function(){
                grid.cancelar();
            },
            recargarStore:function(id){
                grid.recargarStore(id);
            }
        });
        grid.miVentana=Ext.create("App.Conf.General.Ventana",{
            title:'Nuevo Articulo',
            width:500,
            height:250,
            items:[Form]
            });
        grid.miVentana.show();

    },
    modificar:function(){
        var grid= Ext.getCmp("gridArticulos");
        Form=Ext.create("App.Conf.Articulos.Vistas.FormNuevoArticulo",{
            itemSeleccionado:grid.getView().getSelectionModel().getSelection()[0],
            cancelar:function(){
                grid.cancelar();
            },
            recargarStore:function(id){
                grid.recargarStore(id);
            }
        });
        grid.miVentana=Ext.create("App.Conf.General.Ventana",{
            title:'Modificar Articulo',
            width:500,
            height:250,
            items:[Form]
            });
        grid.miVentana.show();

    },
    eliminar:function(){
        var grid= Ext.getCmp("gridArticulos");
        var selection = grid.getView().getSelectionModel().getSelection()[0];
        if (selection) {
            Ext.MessageBox.confirm('Confirmar ', 'Desea eliminar el registro seleccionado ?.\n'+
                ' El registro se eliminar\u00e1 definitivamente sin opci\u00f3n a recuperarlo', function(btn){
                    if(btn=='yes'){
                        Ext.Ajax.request({
                            url: '../articulos/eliminar_articulo',
                            params: {
                                id: selection.data['articulo_id']

                            },
                            timeout: 3000,
                            method: 'POST',
                            success: function( response ){
                                var info = Ext.decode(response.responseText);
                                if (info.success){
                                    grid.store.remove(selection);
                                    grid.getSelectionModel().select(0);
                                }
                                Ext.example.msg('Eliminar Articulo', info.msg);
                            },

                            failure: function(result) {

                                Ext.example.msg('Eliminar Articulo', 'Error en la conexion, Intentelo nuevamente.',2000);
                            }
                        });
                    }
                });


        }
    },
    cancelar: function (){
        var grid= Ext.getCmp("gridArticulos");
        grid.miVentana.close();
    }
});