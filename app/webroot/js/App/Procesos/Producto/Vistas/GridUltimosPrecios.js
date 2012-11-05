Ext.define("App.Procesos.Producto.Vistas.GridUltimosPrecios",{
    extend      : "Ext.grid.Panel",
    alias: 'widget.GridUltimosPrecios',
    title:'Lista de Ultimos Precios',
    border      : true,   
    store: Ext.create("App.Procesos.Producto.Stores.StoreUltimosPrecios"),
    loadMask: true,  
    height:150,
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
            header:"Compra Id",
            dataIndex:"compra_id",
            width:20,
            hidden:true
        },{
            header:"Proveedor Id",
            dataIndex:"proveedor_id",
            width:20,
            hidden:true
        },{
            header:"Fecha",
            dataIndex:"compra_fecha",
            flex:1,
            renderer:this.renderFecha
        },{
            header:"Costo Unitario",
            dataIndex:"d_compra_precio",
            width:150
        },{
            header:"Proveedor",
            dataIndex:"proveedor_razon_social",
            flex:1
        }
        ];
        
        
        me.store.on('beforeload',function(){           
            mascara.show();
        });
         me.store.on('refresh',function(){
            mascara.hide();
        });
         me.store.on('load',function(){
            mascara.hide();
        });
        me.callParent();
    },
     renderFecha:function(value){
        return Ext.Date.format(Ext.Date.parse(value,'Y-m-d H:i:s'),'d-m-Y');
        //return "<b>"+value+"</b>";
    }
});