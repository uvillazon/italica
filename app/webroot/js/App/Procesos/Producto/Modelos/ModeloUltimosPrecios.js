Ext.define("App.Procesos.Producto.Modelos.ModeloUltimosPrecios",{
    extend      : "Ext.data.Model",
    fields      : [
        {name:"compra_id",type:"int"},
        {name:"producto_id",type:"int"},
        {name:"proveedor_id",type:"int"},
        {name:"compra_fecha",type:"string"},
        {name:"compra_cantidad",type:"float"},
        {name:"d_compra_cantidad",type:"float"},
        {name:"d_compra_precio",type:"float"},
        {name:"proveedor_razon_social",type:"string"}
    ]
});