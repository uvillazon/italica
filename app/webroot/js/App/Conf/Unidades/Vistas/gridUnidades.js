Ext.define("App.Conf.Unidades.Vistas.gridUnidades",{
    extend      : "Ext.grid.Panel",
    alias: 'widget.gridUnidades',
    title:'Lista de Unidades',
    border      : true,
    miVentana:'',
    store: Ext.create("App.Conf.Unidades.Stores.StoreUnidades"),
    loadMask: true,
    id:'gridUnidades',
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
            header:"Unidad Id",
            dataIndex:"unidad_id",
            width:20,
            hidden:true
        },{
            header:"Sigla",
            dataIndex:"unidad_sigla",
            width:150
        },{
            header:"Descripcion",
            dataIndex:"unidad_descripcion",
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
        var gridUnidades = Ext.getCmp("gridUnidades");
        gridUnidades.store.load();
        gridUnidades.store.on('load', function(store, records, options){
            gridUnidades.store.each(function(record){
                if (record.raw['unidad_id']==parseInt(id)){
                    gridUnidades.getSelectionModel().select(record.index);
                }
            });
        });
    },

    nuevo: function(){
        var gridUnidades= Ext.getCmp("gridUnidades");
        var FormUnidad=Ext.create("App.Conf.Unidades.Vistas.FormNuevaUnidad",{

            cancelar:function(){
                gridUnidades.cancelar();
            },
            recargarStore:function(id){
                gridUnidades.recargarStore(id);
            }
        });
        gridUnidades.miVentana=Ext.create("App.Conf.General.Ventana",{
            title:'Nueva Unidad',
            width:400,
            height:200,
            items:[FormUnidad]
            });
        gridUnidades.miVentana.show();

    },
    modificar:function(){
        var gridUnidades= Ext.getCmp("gridUnidades");
        FormUnidad=Ext.create("App.Conf.Unidades.Vistas.FormNuevaUnidad",{
            itemSeleccionado:gridUnidades.getView().getSelectionModel().getSelection()[0],
            cancelar:function(){
                gridUnidades.cancelar();
            },
            recargarStore:function(id){
                gridUnidades.recargarStore(id);
            }
        });
        gridUnidades.miVentana=Ext.create("App.Conf.General.Ventana",{
            title:'Modificar Unidad',
            width:400,
            height:200,
            items:[FormUnidad]
            });
        gridUnidades.miVentana.show();

    },
    eliminar:function(){
        var grid= Ext.getCmp("gridUnidades");
        var selection = grid.getView().getSelectionModel().getSelection()[0];
        if (selection) {
            Ext.MessageBox.confirm('Confirmar ', 'Desea eliminar el registro seleccionado ?.\n'+
                ' El registro se eliminar\u00e1 definitivamente sin opci\u00f3n a recuperarlo', function(btn){
                    if(btn=='yes'){
                        Ext.Ajax.request({
                            url: '../unidads/eliminar_unidad',
                            params: {
                                id: selection.data['unidad_id']

                            },
                            timeout: 3000,
                            method: 'POST',
                            success: function( response ){
                                var info = Ext.decode(response.responseText);
                                if (info.success){
                                    grid.store.remove(selection);
                                    grid.getSelectionModel().select(0);
                                }
                                Ext.example.msg('Eliminar Unidad', info.msg);
                            },

                            failure: function(result) {

                                Ext.example.msg('Eliminar Unidad', 'Error en la conexion, Intentelo nuevamente.',2000);
                            }
                        });
                    }
                });


        }
    },
    cancelar: function (){
        var gridUnidades= Ext.getCmp("gridUnidades");
        gridUnidades.miVentana.close();
    }
});