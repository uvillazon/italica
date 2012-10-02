Ext.define("App.Conf.Categorias.Vistas.ComboCategorias", {
    extend: "Ext.form.ComboBox",
    alias: "widget.ComboCategorias",
    name: 'data[Categoria][categoria_id]',
    fieldLabel: 'Categoria',
    valueField:'categoria_id',
    displayField:'categoria_nombre',
    typeAhead: true,
    itemTodos:false,
    store: Ext.create("App.Conf.Categorias.Stores.StoreCategorias"),
    emptyText:'SELECCIONE CATEGORIA....',
    queryMode: 'local',
    forceSelection: true,
    allowBlank:false,
    initComponent: function() {
        var me=this;
       
        me.store.load();
        me.store.on('load',function(){
            if(me.itemTodos){
                me.store.add({
                    categoria_id:0,
                    categoria_nombre:'TODOS',
                    categoria_descripcion:''
                });
                me.store.commitChanges();
            }
        });
        me.callParent(arguments);
    }
});
