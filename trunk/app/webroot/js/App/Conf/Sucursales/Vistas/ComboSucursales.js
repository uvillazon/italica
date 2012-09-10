Ext.define("App.Conf.Sucursales.Vistas.ComboSucursales", {
    extend: "Ext.form.ComboBox",
    alias: "widget.ComboSucursales",
    name: 'data[Sucursal][sucursal_id]',
    fieldLabel: 'Sucursal',
    valueField:'sucursal_id',
    displayField:'sucursal_nombre',
    typeAhead: true,
    store: Ext.create("App.Conf.Sucursales.Stores.StoreSucursales"),
    emptyText:'SELECCIONE SUCURSAL....',
    queryMode: 'local',
    forceSelection: true,
    allowBlank:false,
    initComponent: function() {
        this.store.load();
        this.callParent(arguments);
    }
});
