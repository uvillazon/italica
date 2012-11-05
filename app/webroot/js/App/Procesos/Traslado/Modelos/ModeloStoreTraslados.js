Ext.define("App.Procesos.Traslado.Modelos.ModeloStoreTraslados",{
    extend      : "Ext.data.Model",
    fields      : [
        {name:"traslado_id",type:"int"},              
        {name:"sucursal_origen",type:"int"},   
         {name:"sucursal_destino",type:"int"},   
        {name:"traslado_precio_total",type:"float"}, 
        {name:"traslado_fecha",type:"string"},
        {name:"traslado_cantidad",type:"float"},
        {name:"traslado_usuario_resp",type:"string"},
        {name:"traslado_tipo",type:"string"}
    ]
});