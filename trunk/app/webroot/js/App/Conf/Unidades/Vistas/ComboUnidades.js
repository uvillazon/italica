Ext.define("App.Conf.Unidades.Vistas.ComboUnidades", {
    extend: "Ext.form.ComboBox",
    alias: "widget.ComboUnidades",
    name: 'data[Unidad][unidad_id]',
    fieldLabel: 'Unidad',
    valueField:'unidad_id',
    displayField:'unidad_sigla',
    typeAhead: true,
    store: Ext.create("App.Conf.Unidades.Stores.StoreUnidades"),
    emptyText:'SELECCIONE UNIDAD....',
    queryMode: 'local',
    forceSelection: true,
    allowBlank:false,
    initComponent: function() {
        this.store.load();
        this.callParent(arguments);
    }
});
