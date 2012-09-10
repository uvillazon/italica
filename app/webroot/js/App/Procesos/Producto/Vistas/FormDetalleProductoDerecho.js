Ext.define("App.Procesos.Producto.Vistas.FormDetalleProductoDerecho", {
    extend: "Ext.form.FieldSet",
    alias: "widget.FormDetalleProductoDerecho",
    id: 'FormDetalleProductoDerecho',
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
            xtype:'panel',
            width:200,          
            html:'<img src="../app/webroot/img/fotos/unknow.jpg" width=200px height=180px />',
            height:180
        },{
            xtype:'panel',
            
            width:200,
            html:'<img src="../app/webroot/img/fotos/unknow.jpg" width=200px height=180px />',
            height:180
        }
        ];
        this.callParent(arguments);
    }
});