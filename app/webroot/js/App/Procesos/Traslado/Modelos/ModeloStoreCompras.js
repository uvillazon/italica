Ext.define("App.Procesos.Compra.Modelos.ModeloStoreCompras",{
    extend      : "Ext.data.Model",
    fields      : [
        {name:"compra_id",type:"int"},
        {name:"proveedor_id",type:"int"},      
        {name:"sucursal_id",type:"int"},     
        {name:"compra_precio_total",type:"float"}, 
         {name:"compra_fecha",type:"string"},
        {name:"compra_cantidad",type:"float"},
         {name:"compra_facturada",type:"boolean"},
        {name:"compra_usuario_resp",type:"string"},
        {name:"compra_nro_factura",type:"string"},
        {name:"compra_descuento",type:"float"},
        {name:"compra_descuento_porcentaje",type:"float"}
    ]
});