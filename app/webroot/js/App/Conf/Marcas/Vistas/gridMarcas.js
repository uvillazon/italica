Ext.define("App.Conf.Marcas.Vistas.gridMarcas",{
    extend      : "Ext.grid.Panel",
    alias: 'widget.gridMarcas',
    title:'Lista de Marcas',
    border      : true,
    miVentana:'',
    store: Ext.create("App.Conf.Marcas.Stores.StoreMarcas"),
    loadMask: true,
    id:'gridMarcas',
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
            header:"Marca Id",
            dataIndex:"marca_id",
            width:20,
            hidden:true
        },{
            header:"Sigla",
            dataIndex:"marca_nombre",
            width:150
        },{
            header:"C\u00f3digo",
            dataIndex:"marca_codigo",
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
          //  me.getSelectionModel().select(0);
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
        var grid = Ext.getCmp("gridMarcas");
        grid.store.load();
        grid.store.on('load', function(store, records, options){
            grid.store.each(function(record){
                if (record.raw['marca_id']==parseInt(id)){
                    grid.getSelectionModel().select(record.index);
                }
            });
        });
    },

    nuevo: function(){
        var grid= Ext.getCmp("gridMarcas");
        var FormUnidad=Ext.create("App.Conf.Marcas.Vistas.FormNuevaMarca",{

            cancelar:function(){
                grid.cancelar();
            },
            recargarStore:function(id){
                grid.recargarStore(id);
            }
        });
        grid.miVentana=Ext.create("App.Conf.General.Ventana",{
            title:'Nueva Marca',
            width:400,
            height:200,
            items:[FormUnidad]
            });
        grid.miVentana.show();

    },
    modificar:function(){
        var grid= Ext.getCmp("gridMarcas");
        FormUnidad=Ext.create("App.Conf.Marcas.Vistas.FormNuevaMarca",{
            itemSeleccionado:grid.getView().getSelectionModel().getSelection()[0],
            cancelar:function(){
                grid.cancelar();
            },
            recargarStore:function(id){
                grid.recargarStore(id);
            }
        });
        grid.miVentana=Ext.create("App.Conf.General.Ventana",{
            title:'Modificar Marca',
            width:400,
            height:200,
            items:[FormUnidad]
            });
        grid.miVentana.show();

    },
    eliminar:function(){
        var grid= Ext.getCmp("gridMarcas");
        var selection = grid.getView().getSelectionModel().getSelection()[0];
        if (selection) {
            Ext.MessageBox.confirm('Confirmar ', 'Desea eliminar el registro seleccionado ?.\n'+
                ' El registro se eliminar\u00e1 definitivamente sin opci\u00f3n a recuperarlo', function(btn){
                    if(btn=='yes'){
                        Ext.Ajax.request({
                            url: '../marcas/eliminar_marca',
                            params: {
                                id: selection.data['marca_id']

                            },
                            timeout: 3000,
                            method: 'POST',
                            success: function( response ){
                                var info = Ext.decode(response.responseText);
                                if (info.success){
                                    grid.store.remove(selection);
                                    grid.getSelectionModel().select(0);
                                }
                                Ext.example.msg('Eliminar Marca', info.msg);
                            },

                            failure: function(result) {

                                Ext.example.msg('Eliminar Marca', 'Error en la conexion, Intentelo nuevamente.',2000);
                            }
                        });
                    }
                });


        }
    },
    cancelar: function (){
        var grid= Ext.getCmp("gridMarcas");
        grid.miVentana.close();
    }
});