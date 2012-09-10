Ext.define("App.Conf.Marcas.Vistas.FormNuevaMarca", {
    extend: "App.Conf.General.FormGeneral",
    alias: "widget.FormNuevaMarca",
    id: 'FormNuevaMarca',
    itemSeleccionado:'',

    initComponent: function() {
        var sigla,descripcion,id;
        if (this.itemSeleccionado){
            sigla=Ext.String.trim(this.itemSeleccionado.data['marca_nombre']);
            descripcion=Ext.String.trim(this.itemSeleccionado.data['marca_codigo']);
            id=this.itemSeleccionado.data['marca_id'];
        }
        this.items=[
        {
            fieldLabel: 'Marca',
            name: 'data[Marca][marca_nombre]',
            value:sigla,
            allowBlank:false
        },{
            fieldLabel: 'C\u00f3digo',
            name: 'data[Marca][marca_codigo]',
            value:descripcion,
            allowBlank:false
        },{
            xtype:'hidden',
            value:id,
            name: 'data[Marca][marca_id]'
        }
        ];

        this.callParent(arguments);
    },   
    guardar:function(){      
      
        var form = Ext.getCmp("FormNuevaMarca").getForm();
        form.method = 'POST';
        if (form.isValid()) {
            form.submit(
            {
                waitTitle:'Espere por favor',
                waitMsg: 'Enviando datos...',
                url:'../marcas/guardar_marca',
                success:function(form, action) {
                   
                    Ext.getCmp("FormNuevaMarca").recargarStore(action.result.id);
                    Ext.example.msg('Marca', action.result.msg);
                    Ext.getCmp("FormNuevaMarca").cancelar();
                },
                failure: function(form, action) {
                    Ext.getCmp("FormNuevaMarca").cancelar();
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