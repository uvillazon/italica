Ext.define("App.Conf.Categorias.Vistas.FormNuevaCategoria", {
    extend: "App.Conf.General.FormGeneral",
    alias: "widget.FormNuevaCategoria",
    id: 'FormNuevaCategoria',
    itemSeleccionado:'',

    initComponent: function() {
        var categoria,descripcion,id;
        if (this.itemSeleccionado){
            categoria=Ext.String.trim(this.itemSeleccionado.data['categoria_nombre']);
            descripcion=Ext.String.trim(this.itemSeleccionado.data['categoria_descripcion']);
            id=this.itemSeleccionado.data['categoria_id'];
        }
        this.items=[
        {
            fieldLabel: 'Categoria',
            name: 'data[Categoria][categoria_nombre]',
            value:categoria,
            allowBlank:false
        },{
            fieldLabel: 'Descripci\u00f3n',
            name: 'data[Categoria][categoria_descripcion]',
            value:descripcion,
            allowBlank:false
        },{
            xtype:'hidden',
            value:id,
            name: 'data[Categoria][categoria_id]'
        }
        ];

        this.callParent(arguments);
    },   
    guardar:function(formulario){      
      
        var form = formulario.getForm();
        form.method = 'POST';
        if (form.isValid()) {
            form.submit(
            {
                waitTitle:'Espere por favor',
                waitMsg: 'Enviando datos...',
                url:'../categorias/guardar_categoria',
                success:function(form, action) {
                   
                    formulario.recargarStore(action.result.id);
                    Ext.example.msg('Categoria', action.result.msg);
                    formulario.cancelar();
                },
                failure: function(form, action) {
                    formulario.cancelar();
                    Ext.MessageBox.show({
                        title: 'Error',
                        msg: action.result.msg,
                        buttons: Ext.MessageBox.OK,
                        // activeItem :0,
                        animEl: 'mb9',
                        icon: Ext.MessageBox.ERROR,
                        fn: function(btn){
                            if(action.result.redir){
                                 Ext.MessageBox.wait('Direccionado  al formulario de Ingreso','Direccionando');
                                self.location='../';
                            }
                        }
                    });
                }
            }
            );
        //
        }

    }
});