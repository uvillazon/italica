Ext.define("App.Conf.Marcas.Modelos.ModeloStoreMarcas",{
    extend      : "Ext.data.Model",
    fields      : [
        {name:"marca_id",type:"int"},
        {name:"marca_nombre",type:"string"},
        {name:"marca_codigo",type:"string"}
    ]
});