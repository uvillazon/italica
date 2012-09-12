Ext.define("App.Conf.Categorias.Vistas.ComboCategorias", {
    extend: "Ext.form.ComboBox",
    alias: "widget.ComboCategorias",
    name: 'data[Categoria][categoria_id]',
    fieldLabel: 'Categoria',
    valueField:'categoria_id',
    displayField:'categoria_nombre',
    typeAhead: true,
    store: Ext.create("App.Conf.Categorias.Stores.StoreCategorias"),
    emptyText:'SELECCIONE CATEGORIA....',
    queryMode: 'local',
    forceSelection: true,
    allowBlank:false,
    initComponent: function() {
        this.store.load();
        this.callParent(arguments);
    }
});
