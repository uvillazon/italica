Ext.define("App.Procesos.Producto.Vistas.PanelDetalleProductoGrid", {
    extend: "Ext.form.Panel",
    alias: "widget.PanelDetalleProductoGrid",
    id: 'PanelDetalleProductoGrid',
    title: '',
    layout: 'form',
    bodyPadding: '10 10 10',
    width:400,
    height:530,
    initComponent: function() {
        var comboCategorias = Ext.create("App.Conf.Categorias.Vistas.ComboCategorias");
        var grid = Ext.create("App.Procesos.Producto.Vistas.GridDetalleProductos",{
            height:420
        });
        this.items=[{
            xtype:'displayfield',
            hideLabel: true,
            value:'<center><h4>SELECCIONADOR DE ITEMS</h4></center>'
        },{
            xtype:'textfield',
            fieldLabel:'BUSQUEDA',
            emptyText:'INGRESAR ITEM A BUSCAR......',
            enableKeyEvents: true,
            listeners:{
                'keydown': function(e,el) {
                    //console.log(el.getCharCode());
                    if(el.getCharCode()==13){//si presiona enter
                        Ext.getCmp("PanelDetalleProductoGrid").modificarAccion('Mostando datos seg\u00fan palabra ingresada : <b>'+ this.getValue()+'</b>');
                    }
                }
            }
        },comboCategorias,grid
       
        ];
        this.bbar=[ {
            xtype:'displayfield',
            id:'descripcionAccion',
            hideLabel: true,
            value:'DESCRIPCION DE ACCION'
        }];
     comboCategorias.on('select',function(cmb,record,index){
             Ext.getCmp("PanelDetalleProductoGrid").modificarAccion('Mostando datos por categoria seleccionada : <b>'+ cmb.getRawValue()+'</b>');
        },this);
        this.callParent(arguments);
    },
    modificarAccion:function(accion){
        Ext.getCmp("descripcionAccion").setValue(accion);
    }
});