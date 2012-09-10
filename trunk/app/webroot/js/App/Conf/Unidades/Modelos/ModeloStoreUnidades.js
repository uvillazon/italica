Ext.define("App.Conf.Unidades.Modelos.ModeloStoreUnidades",{
    extend      : "Ext.data.Model",
    fields      : [
        {name:"unidad_id",type:"int"},
        {name:"unidad_descripcion",type:"string"},
        {name:"unidad_sigla",type:"string"}
    ]
});