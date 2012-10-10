 Ext.define("App.Conf.General.FormGeneral", {
    extend: "Ext.form.Panel",
    alias: "widget.FormGeneral",
    layout: 'form',
    id: 'FormGeneral',
    frame: false,
    resizable:false,
    bodyPadding: '15 35 35',
    width: '98%',
    height:'98%',
    fieldDefaults: {
        msgTarget: 'side',
        labelWidth: 75
    },
    defaultType: 'textfield',
    initComponent: function() {
        this.buttons=[{
            text: 'Guardar',
            iconCls: 'disk',
            handler: this.guardar
        }, '-',{
            text: 'Salir',
            iconCls: 'cross',
            handler: this.cancelar
        }];
        this.callParent(arguments);
    },
    guardar:function(){
         Ext.example.msg('Guardar', 'se presiono el boton guardar');
    },
    cancelar:function(){
         Ext.example.msg('Cancelar', 'se presiono el boton cancelar');
    },
    recargarStore:function(){
       Ext.example.msg('Recargar','Los datos fuer&oacute;n cargados');
    }

});