Ext.define("App.Procesos.Producto.Vistas.PanelDetalleProductoGrid", {
    extend: "Ext.panel.Panel",
    alias: "widget.PanelDetalleProductoGrid",    
    title: 'SELECCIONADOR DE ITEMS',
    titleAlign:'center',
    layout: 'border',
    bodyPadding: '5 10 10',
    resizable :true,
    gridSecundario:'',
    width:'100%',
    ocultarCosto:false,
    ocultarPrecio:false,
    height:530,
    collapseDirection:'east',
    collapsible:true,
    initComponent: function() {
        var comboCategorias = Ext.create("App.Conf.Categorias.Vistas.ComboCategorias",{
            itemTodos:true,
            region:'north'
        });
        
        var me= this;
        var grid = Ext.create("App.Procesos.Producto.Vistas.GridDetalleProductos",{
            width:'100%',
            itemId:'gridItems',
            gridSecundario:me.gridSecundario,
            ocultarCosto:this.ocultarCosto,
            ocultarPrecio:this.ocultarPrecio,
            region:'center',
            height:420
        });
        me.items=[{
            xtype:'textfield',
            itemId:'palabraBusqueda',
            fieldLabel:'BUSQUEDA',
            emptyText:'INGRESAR ITEM A BUSCAR......',
            region:'north',
            enableKeyEvents: true,
            listeners:{
                'keydown': function(e,el) {
                    //console.log(el.getCharCode());
                    if(el.getCharCode()==13){//si presiona enter
                        grid.store.load({
                            params:{
                                palabra: this.getValue(),
                                categoria_id:comboCategorias.getValue()
                            }
                        });
                        me.down("#descripcionAccion").setValue('Mostando datos seg\u00fan  : <b>'+ this.getValue()+'</b>');
                    }
                },
                'change': function(e,el) {
                    //console.log(el.getCharCode());
                   
                        grid.store.load({
                            params:{
                                palabra: this.getValue(),
                                categoria_id:comboCategorias.getValue()
                            }
                        });
                        me.down("#descripcionAccion").setValue('Mostando datos seg\u00fan  : <b>'+ this.getValue()+'</b>');
                   
                }
            }
        },comboCategorias,grid
       
        ];
       
        me.bbar=[ {
            xtype:'displayfield',
            region:'south',
            itemId:'descripcionAccion',
            hideLabel: true,
            value:'DESCRIPCION DE ACCION'
        }];
        comboCategorias.on('select',function(cmb,record,index){
            //Ext.getCmp("PanelDetalleProductoGrid").modificarAccion('Mostando datos por categoria seleccionada : <b>'+ cmb.getRawValue()+'</b>');
            grid.store.load({
                params:{
                    palabra: me.down('#palabraBusqueda').getValue(),
                    categoria_id:cmb.getValue()
                }
            });
            me.down("#descripcionAccion").setValue('Mostrando datos por categoria : <b>'+ cmb.getRawValue()+'</b>');
        },this);
        me.callParent(arguments);
    },
    recargarItems:function(panel){
        panel.down('#gridItems').store.load();
    }
});