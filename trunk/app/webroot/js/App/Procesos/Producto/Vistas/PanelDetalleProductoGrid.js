Ext.define("App.Procesos.Producto.Vistas.PanelDetalleProductoGrid", {
    extend: "Ext.form.Panel",
    alias: "widget.PanelDetalleProductoGrid",    
    title: '',
    layout: 'form',
    bodyPadding: '10 10 10',
    width:450,
    height:530,
    initComponent: function() {
        var comboCategorias = Ext.create("App.Conf.Categorias.Vistas.ComboCategorias",{
            itemTodos:true
        });
        
        var me= this;
        var grid = Ext.create("App.Procesos.Producto.Vistas.GridDetalleProductos",{
            height:420
        });
        me.items=[{
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
                        grid.store.load({
                            params:{
                                palabra: this.getValue()
                            }
                        });
                        me.down("#descripcionAccion").setValue('Mostando datos seg\u00fan  : <b>'+ this.getValue()+'</b>');
                    }
                }
            }
        },comboCategorias,grid
       
        ];
        me.bbar=[ {
            xtype:'displayfield',
            itemId:'descripcionAccion',
            hideLabel: true,
            value:'DESCRIPCION DE ACCION'
        }];
        comboCategorias.on('select',function(cmb,record,index){
            //Ext.getCmp("PanelDetalleProductoGrid").modificarAccion('Mostando datos por categoria seleccionada : <b>'+ cmb.getRawValue()+'</b>');
            grid.store.load({
                params:{
                    categoria_id:cmb.getValue()
                }
            });
            me.down("#descripcionAccion").setValue('Mostrando datos por categoria : <b>'+ cmb.getRawValue()+'</b>');
        },this);
        me.callParent(arguments);
    }
});