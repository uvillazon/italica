Ext.define("App.Conf.Productos.Vistas.PanelPrincipalProducto", {
    extend: "Ext.panel.Panel",
    alias: "widget.PanelProducto",
    width:'100%',
    height:'100%',
    layout: 'fit',
    border:0,
    title:'Productos',
    quitarPermisoW:false,// quitar permisos para modificar
    quitarPermisoC:false,//quitar permisos para crear
    quitarPermisoD:false,// quitar permisos para borrar
    initComponent:function(){
        var me=this;
         var grid=Ext.create("App.Conf.Productos.Vistas.GridProductos",{
            width:'100%',
            height:530

        });
        var panelForm=Ext.create("App.Conf.Productos.Vistas.FormProducto",
        {
            cancelar:function(){
                panelForm.modoNoEdicion(panelForm);
            },
            guardar:function(){
                 panelForm.guardarDatos(panelForm,grid);
            }
        });
       
        var panelGrid=Ext.create("App.Conf.Productos.Vistas.PanelProductoGrid",{
            items:[grid]
        });

        grid.on('selectionchange',function(model, records) {
            if (records[0]) {
                panelForm.cambiarValores(panelForm,records[0]);
            }
        });

        grid.getSelectionModel().on('selectionchange', function(selModel, selections,selectedRecord){
            //console.log(me);
            me.down('#delete').setDisabled(selections.length === 0);
            me.down('#edit').setDisabled(selections.length === 0);

        });


        me.items=[{
            xtype:'panel',
            //id:'main-panel',
            baseCls:'x-plain',
           
            layout: {
                type: 'table',
                columns: 2
            },
            items:[panelGrid,panelForm]
        }];
        me.tbar=['-','-','-',
            {
            text: 'Nuevo',
            iconCls: 'add',
            hidden:me.quitarPermisoC,
            handler: function(){
                panelForm.modoEdicion(panelForm);
               panelForm.limpiarFormulario(panelForm);
                grid.getSelectionModel().deselectAll();
            }
        }, '-',{
            text: 'Modificar',
            itemId: 'edit',
            disabled: true,
            iconCls: 'page_edit',
            hidden:me.quitarPermisoW,
            handler: function(){
                 panelForm.modoEdicion(panelForm);
            }
        },'-', {
            itemId: 'delete',
            text: 'Eliminar',
            hidden:me.quitarPermisoD,
            iconCls: 'delete',
            disabled: true,
            handler: function(){
                 panelForm.eliminarSeleccionado(panelForm,grid);
            }

        }];
        this.callParent(arguments);
    }

});