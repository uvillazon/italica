Ext.define("App.Procesos.Traslado.Vistas.GridTraslado",{
    extend      : "Ext.grid.Panel",
    alias: 'widget.GridTraslado',
    //title:'Lista de Articulos',
    border      : true,
    store: '',
    loadMask: true,
    width:'100%',
    height:250,
    initComponent   : function() {
        var me = this;     
       
        me.columns = [
        Ext.create('Ext.grid.RowNumberer'),
        {
            header:"Producto Id",
            dataIndex:"producto_id",
            width:20,
            hidden:true
        }, {
            header:"Categoria Id",
            dataIndex:"categoria_id",
            width:20,
            hidden:true
        },{
            header:"C\u00f3digo<br>Item",
            dataIndex:"producto_codigo",
            flex:1
        },{
            header:"Descripci\u00f3n",
            dataIndex:"producto_descripcion",
            width:150
        },{
            header:"Unidad",
            dataIndex:"unidad",
            flex:1
        },{
            header:"Cantidad",
            dataIndex:"cantidad",
            flex:1
        }
        ];       

        me.callParent();
    }
   
});