Ext.define("App.Conf.Productos.Vistas.GridProductos",{
    extend      : "Ext.grid.Panel",
    alias: 'widget.GridProductos',
   
    border      : true,
    store: Ext.create("App.Conf.Productos.Stores.StoreProductos"),
    loadMask: true,
    width:'100%',
    minWidth:400,
    minHeight:400,
   
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
            flex:1,
            hidden:true
        }, {
            header:"Producto Id",
            dataIndex:"categoria_id",
            flex:1,
            hidden:true
        },{
            header:"Marca Id",
            dataIndex:"marca_id",
            flex:1,
            hidden:true
        },{
            header:"Unidad Id",
            dataIndex:"unidad_id",
            flex:1,
            hidden:true
        },{
            header:"Producto Img",
            dataIndex:"producto_imagen",
            flex:1,
            hidden:true
        },{
            header:"C\u00f3digo",
            dataIndex:"producto_codigo",
            flex:1
        },{
            header:"C\u00f3digo Prov.",
            dataIndex:"producto_codigo_prov",
            flex:1
        },{
            header:"Producto",
            dataIndex:"producto_nombre",
            width:150
        },{
            header:"Categoria",
            dataIndex:"categoria_nombre",
            flex:1
        },{
            header:"Marca",
            dataIndex:"marca_nombre",
            flex:1
        },{
            header:"Unidad",
            dataIndex:"unidad_sigla",
            flex:1
        },{
            header:"Ubicaci\u00f3n",
            dataIndex:"kardex_ubicacion_producto",
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
            //me.getSelectionModel().select(0);
            mascara.hide();
        });
       

        me.callParent();
    },
    //funciones para el grid
  
    recargarStore:function(id,grid){
       
        grid.store.load();
        grid.store.on('load', function(store, records, options){
            grid.store.each(function(record){
                if (record.raw['producto_id']==parseInt(id)){
                    grid.getSelectionModel().select(record.index);
                }
            });
        });
    },
   recargarStoreSelP:function(grid){
        grid.store.load();
        grid.store.on('load', function(store, records, options){
             grid.getSelectionModel().select(0);
          
        });
   },
    cancelar: function (){
        var grid= Ext.getCmp("gridArticulos");
        grid.miVentana.close();
    }
});