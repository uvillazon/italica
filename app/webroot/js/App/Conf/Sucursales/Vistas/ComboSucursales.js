Ext.define("App.Conf.Sucursales.Vistas.ComboSucursales", {
    extend: "Ext.form.ComboBox",
    alias: "widget.ComboSucursales",
    name: 'data[Sucursal][sucursal_id]',
    fieldLabel: 'Sucursal',
    valueField:'sucursal_id',
    displayField:'sucursal_nombre',
    typeAhead: true,
    itemTodos:false,
    store: Ext.create("App.Conf.Sucursales.Stores.StoreSucursales"),
    emptyText:'SELECCIONE SUCURSAL....',
    queryMode: 'local',
    forceSelection: true,
    allowBlank:false,
    initComponent: function() {
        var me=this;
        me.store.load();
        me.store.on('load',function(){
            if(!me.itemTodos){
                me.store.filterBy(function(record,id){  
                    return record.get('sucursal_id') > 0; //mayores a 30 años  
                });  
            }
        });
        me.callParent(arguments);
    }
});
