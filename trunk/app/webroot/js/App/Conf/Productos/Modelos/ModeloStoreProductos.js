Ext.define("App.Conf.Productos.Modelos.ModeloStoreProductos",{
    extend      : "Ext.data.Model",
    fields      : [
        {name:"producto_id",type:"int"},
        {name:"categoria_id",type:"int"},
        {name:"marca_id",type:"int"},
        {name:"unidad_id",type:"int"},
       
        {name:"sucursal_id",type:"int"},
        {name:"producto_ubicacion",type:"string"},
        {name:"producto_cantidad_minima",type:"float"},
        {name:"producto_precio",type:"float"},
        {name:"producto_codigo_barra",type:"float"},
        {name:"producto_costo",type:"float"},
        {name:"producto_imagen",type:"string"},
        {name:"producto_codigo",type:"string"},
        {name:"producto_codigo_prov",type:"string"},
        {name:"producto_descripcion",type:"string"},
        {name:"producto_nombre",type:"string"},
        {name:"marca_nombre",type:"string"},
        {name:"categoria_nombre",type:"string"},
        {name:"kardex_saldo_cantidad1",type:"float"},
        {name:"kardex_saldo_valor1",type:"float"},
        {name:"kardex_saldo_cantidad2",type:"float"},
        {name:"kardex_saldo_valor2",type:"float"},
        {name:"kardex_saldo_cantidad3",type:"float"},
        {name:"kardex_saldo_valor3",type:"float"},
        {name:"unidad_sigla",type:"string"}
    ]
});