Ext.define("App.Conf.Unidades.Vistas.FormNuevaUnidad", {
    extend: "App.Conf.General.FormGeneral",
    alias: "widget.FormNuevaUnidad",
    id: 'FormNuevaUnidad',
    itemSeleccionado:'',

    initComponent: function() {
        var sigla,descripcion,id;
        if (this.itemSeleccionado){
            sigla=Ext.String.trim(this.itemSeleccionado.data['unidad_sigla']);
            descripcion=Ext.String.trim(this.itemSeleccionado.data['unidad_descripcion']);
            id=this.itemSeleccionado.data['unidad_id'];
        }
        this.items=[
        {
            fieldLabel: 'Sigla Unidad',
            name: 'data[Unidad][unidad_sigla]',
            value:sigla,
            allowBlank:false
        },{
            fieldLabel: 'Descripci\u00f3n',
            name: 'data[Unidad][unidad_descripcion]',
            value:descripcion,
            allowBlank:false
        },{
            xtype:'hidden',
            value:id,
            name: 'data[Unidad][unidad_id]'
        }
        ];

        this.callParent(arguments);
    },   
    guardar:function(formulario){      
      
        var form =formulario.getForm();
        form.method = 'POST';
        if (form.isValid()) {
            form.submit(
            {
                waitTitle:'Espere por favor',
                waitMsg: 'Enviando datos...',
                url:'../unidads/guardar_unidad',
                success:function(form, action) {
                   
                    formulario.recargarStore(action.result.id);
                    Ext.example.msg('Unidad', action.result.msg);                   
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