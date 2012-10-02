Ext.define("App.Conf.Categorias.Vistas.gridCategorias",{
    extend      : "Ext.grid.Panel",
    alias: 'widget.gridCategorias',
    title:'Lista de Categorias',
    border      : true,
    miVentana:'',
    store: Ext.create("App.Conf.Categorias.Stores.StoreCategorias"),
    loadMask: true,
    id:'gridCategorias',
    itemId:'prueba',
    minHeight:300,
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
            header:"Categoria Id",
            dataIndex:"categoria_id",
            width:20,
            hidden:true
        },{
            header:"Categoria",
            dataIndex:"categoria_nombre",
            width:150
        },{
            header:"Descripci\u00f3n",
            dataIndex:"categoria_descripcion",
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
        var grid = Ext.getCmp("gridCategorias");
        grid.store.load();
        grid.store.on('load', function(store, records, options){
            grid.store.each(function(record){
                if (record.raw['categoria_id']==parseInt(id)){
                    grid.getSelectionModel().select(record.index);
                }
            });
        });
    },

    nuevo: function(){
        //alert(this.getComponent('name').getId());
        var grid= Ext.getCmp("gridCategorias");
        var Form=Ext.create("App.Conf.Categorias.Vistas.FormNuevaCategoria",{

            cancelar:function(){
                grid.cancelar();
            },
            recargarStore:function(id){
                grid.recargarStore(id);
            }
        });
        grid.miVentana=Ext.create("App.Conf.General.Ventana",{
            title:'Nueva Categoria',
            width:400,
            height:200,
            items:[Form]
            });
        grid.miVentana.show();

    },
    modificar:function(){
        var grid= Ext.getCmp("gridCategorias");
        Form=Ext.create("App.Conf.Categorias.Vistas.FormNuevaCategoria",{
            itemSeleccionado:grid.getView().getSelectionModel().getSelection()[0],
            cancelar:function(){
                grid.cancelar();
            },
            recargarStore:function(id){
                grid.recargarStore(id);
            }
        });
        grid.miVentana=Ext.create("App.Conf.General.Ventana",{
            title:'Modificar Categoria',
            width:400,
            height:200,
            items:[Form]
            });
        grid.miVentana.show();

    },
    eliminar:function(){
        var grid= Ext.getCmp("gridCategorias");
        var selection = grid.getView().getSelectionModel().getSelection()[0];
        if (selection) {
            Ext.MessageBox.confirm('Confirmar ', 'Desea eliminar el registro seleccionado ?.\n'+
                ' El registro se eliminar\u00e1 definitivamente sin opci\u00f3n a recuperarlo', function(btn){
                    if(btn=='yes'){
                        Ext.Ajax.request({
                            url: '../categorias/eliminar_categoria',
                            params: {
                                id: selection.data['categoria_id']

                            },
                            timeout: 3000,
                            method: 'POST',
                            success: function( response ){
                                var info = Ext.decode(response.responseText);
                                if (info.success){
                                    grid.store.remove(selection);
                                    grid.getSelectionModel().select(0);
                                }
                                Ext.example.msg('Eliminar Categoria', info.msg);
                            },

                            failure: function(result) {

                                Ext.example.msg('Eliminar Categoria', 'Error en la conexion, Intentelo nuevamente.',2000);
                            }
                        });
                    }
                });


        }
    },
    cancelar: function (){
        var grid= Ext.getCmp("gridCategorias");
        grid.miVentana.close();
    }
});