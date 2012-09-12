Ext.define("App.Conf.Productos.Vistas.PanelPrincipalProducto", {
    extend: "Ext.panel.Panel",
    alias: "widget.PanelProducto",
    width:'100%',
    height:'100%',
    quitarPermisoW:false,// quitar permisos para modificar
    quitarPermisoC:false,//quitar permisos para crear
    quitarPermisoD:false,// quitar permisos para borrar
    initComponent:function(){
        var panelForm=Ext.create("App.Conf.Productos.Vistas.FormProducto");
        var panelGrid=Ext.create("App.Conf.Productos.Vistas.PanelProductoGrid");
     
       this.items=[{
            xtype:'panel',
            //id:'main-panel',
            //baseCls:'x-plain',
            layout: {
                type: 'table',
                columns: 2
            },
            
            items:[panelGrid,panelForm]
        }];
        this.callParent(arguments);
    }

});