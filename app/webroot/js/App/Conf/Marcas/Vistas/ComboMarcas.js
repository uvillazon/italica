Ext.define("App.Conf.Marcas.Vistas.ComboMarcas", {
    extend: "Ext.form.ComboBox",
    alias: "widget.ComboMarcas",
    name: 'data[Marca][marca_id]',
    fieldLabel: 'Marca',
    valueField:'marca_id',
    displayField:'marca_nombre',
    typeAhead: true,
    store: Ext.create("App.Conf.Marcas.Stores.StoreMarcas"),
    emptyText:'SELECCIONE MARCA....',
    queryMode: 'local',
    forceSelection: true,
    allowBlank:false,
    initComponent: function() {
        this.store.load();
        this.callParent(arguments);
    }
});
