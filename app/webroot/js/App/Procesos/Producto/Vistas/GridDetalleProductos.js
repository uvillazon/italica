Ext.define("App.Procesos.Producto.Vistas.GridDetalleProductos",{
    extend      : "Ext.grid.Panel",
    alias: 'widget.GridDetalleProductos',
    //title:'Lista de Articulos',
    border      : true,
    store: Ext.create("App.Conf.Articulos.Stores.StoreArticulos"),
    loadMask: true,
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
            header:"Precio",
            dataIndex:"producto_precio",
            flex:1
        },{
            header:"Cantidad Disponible",
            
            flex:1,
            columns:[{
                header:"SM",
                dataIndex:"cantidad1",
                flex:1
            },{
                header:"A",
                dataIndex:"cantidad2",
                flex:1
            },{
                header:"F",
                dataIndex:"cantidad3",
                flex:1
            }

            ]
        }
        ];
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
           // me.down('#delete').setDisabled(selections.length === 0);
           // me.down('#edit').setDisabled(selections.length === 0);

        });

        me.callParent();
    }
});