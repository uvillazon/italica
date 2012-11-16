Ext.define("App.Procesos.Compra.Modelos.ModeloStoreDetalleCompraProducto",{
    extend      : "Ext.data.Model",
    fields      : [
        {name:"d_compra_id",type:"int"},
        {name:"sucursal_id",type:"int"},
        {name:"compra_id",type:"int"},
        {name:"compra_fecha",type:"string"},
        {name:"producto_id",type:"int"},
        {name:"d_compra_cantidad",type:"float"},       
        {name:"d_compra_precio",type:"float"},
        {name:"d_compra_total",type:"float"},
        {name:"producto_codigo",type:"string"},
        {name:"producto_nombre",type:"string"},
        {name:"unidad_id",type:"int"},
        {name:"unidad_sigla",type:"string"},
        {name:"movimiento_hora",type:"string"}
    ]
});