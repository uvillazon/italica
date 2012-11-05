Ext.define("App.Procesos.Traslado.Modelos.ModeloStoreDetalleTraslados",{
    extend      : "Ext.data.Model",
    fields      : [
        {name:"d_traslado_id",type:"int"},
        {name:"sucursal_id",type:"int"},
        {name:"traslado_id",type:"int"},
        {name:"traslado_fecha",type:"string"},
        {name:"producto_id",type:"int"},
        {name:"d_traslado_cantidad",type:"float"},       
        {name:"d_traslado_precio",type:"float"},
        {name:"d_traslado_total",type:"float"},
        {name:"producto_codigo",type:"string"},
        {name:"producto_nombre",type:"string"},
        {name:"unidad_id",type:"int"},
        {name:"unidad_sigla",type:"string"},        
        {name:"d_traslado_costo",type:"float"},
        {name:"movimiento_hora",type:"string"},        
        {name:"d_traslado_tipo",type:"string"},
        {name:"disponibilidad",type:"string"}
    ]
});