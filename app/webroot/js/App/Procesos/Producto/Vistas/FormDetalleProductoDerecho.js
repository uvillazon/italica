Ext.define("App.Procesos.Producto.Vistas.FormDetalleProductoDerecho", {
    extend: "Ext.form.Panel",
    alias: "widget.FormDetalleProductoDerecho",
   bodyPadding: '5 10 10',
    title: '',
    collapsible: false,
    defaults: {
        labelWidth: 89,
        anchor: '100%',
        layout: {
            type: 'hbox',
            defaultMargins: {
                top: 0,
                right: 5,
                bottom: 0,
                left: 0
            }
        }
    },
    initComponent: function() {
        this.items=[{
            xtype:'displayfield',
            hideLabel: true,
            value:'<center><h4>IMAGEN DEL ITEM</h4></center>'
        },{
            xtype: 'image',
            rowspan:12,
            itemId: 'imagen',
            src: "../app/webroot/img/fotos/unknow.jpg",
            margin: '0 20 0 0',
            width : 150,
            height: 400
        }
        ];
        this.callParent(arguments);
    }
});