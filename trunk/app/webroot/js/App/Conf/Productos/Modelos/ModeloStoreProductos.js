Ext.define("App.Conf.Productos.Modelos.ModeloStoreProductos",{
    extend      : "Ext.data.Model",
    fields      : [
        {name:"producto_id",type:"int"},
        {name:"categoria_id",type:"int"},
        {name:"marca_id",type:"int"},
        {name:"unidad_id",type:"int"},
        {name:"kardex_ubicacion_producto",type:"string"},
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
        {name:"unidad_sigla",type:"string"}
    ]
});