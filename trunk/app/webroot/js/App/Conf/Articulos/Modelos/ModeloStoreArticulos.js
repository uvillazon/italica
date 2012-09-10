Ext.define("App.Conf.Articulos.Modelos.ModeloStoreArticulos",{
    extend      : "Ext.data.Model",
    fields      : [
        {name:"articulo_id",type:"int"},
        {name:"articulo_imagen",type:"string"},
        {name:"articulo_descripcion",type:"string"},
        {name:"articulo_codigo",type:"string"},
        {name:"categoria_id",type:"int"},
        {name:"categoria_nombre",type:"string"}
    ]
});