Ext.define("App.Conf.Categorias.Modelos.ModeloStoreCategorias",{
    extend      : "Ext.data.Model",
    fields      : [
        {name:"categoria_id",type:"int"},
        {name:"categoria_nombre",type:"string"},
        {name:"categoria_descripcion",type:"string"}
    ]
});