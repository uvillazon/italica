Ext.define("App.Procesos.Traslado.Vistas.GridTrasladosRealizados",{
    extend      : "Ext.grid.Panel",
    alias: 'widget.GridTrasladosRealizados',
    title:'LISTA DE TRASLADOS',
    titleAlign:'center',
    border      : true,
    
    collapsible:true,
    resizable:true,
    store: Ext.create("App.Procesos.Traslado.Stores.StoreTraslados"),
    loadMask: true,
    width:'100%',
    height:250,
    initComponent   : function() {
        var me = this;     
       
        me.columns = [
        Ext.create('Ext.grid.RowNumberer'),
        {
            header:"Traslado Id",
            dataIndex:"traslado_id",
            width:20,
            hidden:true
        },{
            header:"Sucursal Origen",
            dataIndex:"sucursal_origen",
            width:20,
            hidden:true
        },{
            header:"sucursal destino",
            dataIndex:"sucursal_destino",
            width:20,
            hidden:true
        },{
            header:"Fecha",
            dataIndex:"traslado_fecha",
            field: 'datefield',
            renderer : this.renderFecha,
            flex:1
        },{
            header:"Precio Total",
            dataIndex:"traslado_precio_total",
            flex:1
        },{
            header:"Cantidad",
            dataIndex:"traslado_cantidad",
            flex:1
        },{
            header:"Responsable",
            dataIndex:"traslado_usuario_resp",
            flex:1
        }
        ]; 
        
        me.bbar= Ext.create('Ext.PagingToolbar', {
            store: me.store,
            displayInfo: true,
            displayMsg: 'Mostrando {0} - {1} de {2}',
            emptyMsg: "No existe datos para mostrar"
        });
       
        me.tbar=[{
            text:'Eliminar',
            itemId:'btnEliminar',
            disabled:true,
            iconCls:'delete',
            handler:function(){
                me.eliminar(me);
            }
        }]
        me.getSelectionModel().on('selectionchange', function(sm, selectedRecord) {
            me.down('#btnEliminar').setDisabled(selectedRecord.length === 0);
        });
        me.callParent();
    },
    renderFecha:function(value){
        return Ext.Date.format(Ext.Date.parse(value,'Y-m-d H:i:s'),'d-m-Y H:i:s');
        //return "<b>"+value+"</b>";
    },
    eliminar:function(grid){
       
        var selection = grid.getView().getSelectionModel().getSelection()[0];
        if (selection) {
            Ext.MessageBox.confirm('Confirmar ', 'Desea eliminar el registro seleccionado ?.\n'+
                ' El registro se eliminar\u00e1 definitivamente con todos los registros relacionados, sin opci\u00f3n a recuperarlo', function(btn){
                    if(btn=='yes'){
                        Ext.Ajax.request({
                            url: '../traslados/eliminar_traslado',
                            params: {
                                id: selection.data['traslado_id']

                            },
                            timeout: 3000,
                            method: 'POST',
                            success: function( response ){
                                var info = Ext.decode(response.responseText);
                                if (info.success){
                                    try{
                                        grid.store.remove(selection);
                                        grid.getSelectionModel().select(0);
                                        Ext.getCmp('gridItemsTraslados').recargarItems(Ext.getCmp('gridItemsTraslados'));
                                        Ext.example.msg('Eliminar', info.msg); 
                                    }catch(err){
                                        
                                    }                                   
                                }else{                                      
                                    Ext.MessageBox.show({
                                        title: 'Error',
                                        msg: info.msg,
                                        buttons: Ext.MessageBox.OK,
                                        // activeItem :0,
                                        animEl: 'mb9',
                                        icon: Ext.MessageBox.ERROR
                                    });
                                }
                               
                            },

                            failure: function(result) {
                                Ext.MessageBox.show({
                                    title: 'Eliminar Detalle',
                                    msg: 'Error en la conexion, Intentelo nuevamente.',
                                    buttons: Ext.MessageBox.OK,
                                    // activeItem :0,
                                    animEl: 'mb9',
                                    icon: Ext.MessageBox.ERROR
                                });
                              
                            }
                        });
                    }
                });


        }
    }
   
});