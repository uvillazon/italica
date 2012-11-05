Ext.define("App.Procesos.Producto.Vistas.GridDetalleProductos",{
    extend      : "Ext.grid.Panel",
    alias: 'widget.GridDetalleProductos',
    //title:'Lista de Articulos',
    gridSecundario:'',
    autoScroll:true,
    border      : true,
    multiSelect: false,
    ocultarCosto:false,
    ocultarPrecio:false,
    store: Ext.create("App.Conf.Productos.Stores.StoreProductos",{autoLoad:true}),
    loadMask: true,
    initComponent   : function() {
        var me = this;     
        var mascara=new Ext.LoadMask(this, {
            msg:'Cargando......'
        });
        me.columns = [
        Ext.create('Ext.grid.RowNumberer'),
        {
            header:"Producto Id",
            dataIndex:"producto_id",
            width:20,
            hidden:true
        }, {
            header:"Categoria Id",
            dataIndex:"categoria_id",
            width:20,
            hidden:true
        },{
            header:"C\u00f3digo<br>Item",
            dataIndex:"producto_codigo",
            flex:1
        },{
            header:"Descripci\u00f3n",
            dataIndex:"producto_nombre",
            width:150
        },{
            header:"Precio",
            dataIndex:"producto_precio",
            hidden:this.ocultarPrecio,
            flex:1
        },{
            header:"Costo",
            dataIndex:"producto_costo",
            hidden:this.ocultarCosto,
            flex:1
        },{
            header:"Cantidad Disponible",
            
            flex:1,
            columns:[{
                header:"SM",
                dataIndex:"kardex_saldo_cantidad1",
                flex:1
            },{
                header:"A",
                dataIndex:"kardex_saldo_cantidad2",
                flex:1
            },{
                header:"F",
                dataIndex:"kardex_saldo_cantidad3",
                flex:1
            }

            ]
        },{
            header:"Unidad",
            hidden:true,
            dataIndex:"unidad_sigla",
            flex:1
        }
        ];
       
        
        me.store.on('beforeload',function(){
           
            mascara.show();
            

        });
        me.on('celldblclick',function(grid,td,cellIndex,record,tr,rowIndex,e,eOpts){
                //console.log(record.data['producto_codigo_prov']);
                me.gridSecundario.addRow(me.gridSecundario,record);
               
            });
        me.store.on('refresh',function(){
            mascara.hide();
        });
        me.store.on('load', function(store, record, options){
            // me.getSelectionModel().select(0);
            store.each(function(record){
                //console.log(record.raw['producto_id']);
                var storeKardex=Ext.create("App.Conf.Productos.Stores.StoreKardex");
                storeKardex.load({
                    params:{
                        producto_id:record.raw['producto_id']
                    }
                });
                storeKardex.on('load',function(){

                    storeKardex.each(function(record2){
                        //console.log(record2.raw['kardex_saldo_cantidad']);
                        if (record2.raw['sucursal_id']==1){
                            record.set('kardex_saldo_cantidad1',record2.raw['kardex_saldo_cantidad']);
                           
                        }else{
                            if (record2.raw['sucursal_id']==2){
                                record.set('kardex_saldo_cantidad2',record2.raw['kardex_saldo_cantidad']);
                            }else{
                                if (record2.raw['sucursal_id']==3){
                                    record.set('kardex_saldo_cantidad3',record2.raw['kardex_saldo_cantidad']);
                                }
                            }
                        }
                    });
                    me.store.commitChanges();
                });
            });
          
            mascara.hide();
        });
        
        
        me.tbar=[{
            text:'Recargar',
            itemId:'btnRecargar',
           tooltip:'Recargar Datos',
            iconCls:'arrow_refresh',
            handler:function(){
                me.store.load();
            }
        }];
      
        me.callParent();
    }
});