Ext.define("App.Conf.Productos.Modelos.ModeloStoreKardex",{
    extend      : "Ext.data.Model",
    fields      : [
        {name:"kardex_id",type:"int"},
        {name:"producto_id",type:"int"},
        {name:"sucursal_id",type:"int"},
        {name:"kardex_saldo_cantidad",type:"float"},
        {name:"kardex_saldo_valor",type:"float"},
        {name:"kardex_inicial",type:"boolean"}
    ]
});