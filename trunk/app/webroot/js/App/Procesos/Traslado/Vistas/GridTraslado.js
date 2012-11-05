Ext.define("App.Procesos.Traslado.Vistas.GridTraslado",{
    extend      : "Ext.grid.Panel",
    alias: 'widget.GridVenta',
    //title:'Lista de Articulos',
    border      : true,
    multiSelect: false,
    plugins:[Ext.create('Ext.grid.plugin.CellEditing', {
        clicksToEdit: 1
    })],
   
    store: Ext.create("App.Procesos.Traslado.Stores.StoreDetalleTraslados"),
    loadMask: true,
    width:'100%',
    height:250,
    viewConfig: {
        getRowClass: function(record, rowIndex, rowParams, store){
            //console.log();
            if (record.get('d_traslado_id')==-1){
                return 'nuevo';  
            }
          
        }
    },
    initComponent   : function() {
        
        var me = this;     
        var mascara=new Ext.LoadMask(this, {
            msg:'Cargando......'
        });
        me.columns = [
        // Ext.create('Ext.grid.RowNumberer'),
        {
            header:"Detalle Id",
            dataIndex:"d_traslado_id",
            width:20,
            hidden:true
        },{
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
            header:"Sucursal Id",
           
            dataIndex:"sucursal_id",
            width:20,
            hidden:true,
            editor:{
                xtype: 'numberfield',
                //id:'sucursalOrigen',
                readOnly:true,
                allowBlank: false,
                minValue: 0,
                maxValue: 100000,
                listeners:{
                    change:function(field,newValue,oldValue,eOpts){
                        
                    }
                }
            }
        },{
            header:"Fecha",
            dataIndex:"traslado_fecha",
            width:20,
            hidden:true,
            editor:{
                xtype: 'datefield',
                readOnly:true,
                allowBlank: false,
                format:'d/m/Y'
            }
        },{
            header:"C\u00f3digo<br>Item",
            dataIndex:"producto_codigo",
            flex:1
        },{
            header:"Descripci\u00f3n",
            dataIndex:"producto_nombre",
            width:150
        },{
            header:"Unidad",
            dataIndex:"unidad_sigla",
            flex:1
        },{
            header:"Cantidad",
            dataIndex:"d_traslado_cantidad",
            
            flex:1,
            editor:{
                xtype: 'numberfield',
               
                allowBlank: false,
                // disabled:true,
                minValue: 0,
                maxValue: 100000,
                decimalSeparator:'.',
                listeners:{
                    change:function(field,value,eOpts){
                        me.verificarDisponibilidad(me,value);
                    }
                },
                selectOnFocus: true
            },
            
            renderer: function(value, metaData, record, rowIdx, colIdx, store, view) {
                if (record.get('d_venta_cantidad')==0){
                    return '<font color=red>'+   value +'</font>'; 
                }else{
                    return '<b><font color='+record.get('disponibilidad')+'>'+   value +'</font></b>'; 
                }
                
            }
            
        },{
            header:"Precio",
            dataIndex:"d_traslado_precio",
            flex:1,
            hidden:true
        },{
            header:"Costo",
            dataIndex:"d_traslado_costo",
            flex:1,
            hidden:true
          
            
        },{
            header:"Total",
            dataIndex:"d_traslado_total",
            flex:1,
            renderer: function(value, metaData, record, rowIdx, colIdx, store, view) {
                me.cambiarTotal(store);
                return '<b>'+   Ext.util.Format.usMoney(record.get('d_traslado_precio') * record.get('d_traslado_cantidad'))+'</b>';
            },
            hidden:true
        },{
            header:"Hora",
            dataIndex:"movimiento_hora",
            hidden:true,
            flex:1
        },
        {
            header:"Disponibilidad",
            dataIndex:"disponibilidad",
            hidden:true,
            flex:1
        },{
            header:"Traslado",
            dataIndex:"d_traslado_tipo",
            hidden:true,
            flex:1
        }
        ]; 
        me.store.on('beforeload',function(){           
            mascara.show();
        });
        me.store.on('refresh',function(){
            mascara.hide();
        });
        me.store.on('load', function(store, record, options){
            mascara.hide();
        });
        me.tbar=[{
            text:'Eliminar',
            itemId:'btnEliminar',
            disabled:true,
            iconCls:'delete',
            handler:function(){
                me.eliminar(me);
            }
        }];
        me.getSelectionModel().on('selectionchange', function(sm, selectedRecord) {
            try{
                me.down('#btnEliminar').setDisabled(selectedRecord.length === 0);
            }catch(err){
               
            }
           
        });
        me.on('beforeedit',function(editor,event,eOpts){
            //console.log(event.record.data.sucursal_id);
            if (event.record.data.sucursal_id == 0) {
                //me.store.rejectChanges();
                Ext.MessageBox.show({
                    title: 'Error',
                    msg: 'Seleccione la Sucursal Origen antes de editar este campo.',
                    buttons: Ext.MessageBox.OK,
                    // activeItem :0,
                    animEl: 'mb9',
                    icon: Ext.MessageBox.WARNING
                });
                event.cancel = true;
            }
        });
        me.callParent();
    },
   
    verificarDisponibilidad:function(grid,cant){
        var record = grid.getView().getSelectionModel().getSelection()[0];
    
        if (record) {
          
            Ext.Ajax.request({
                url: '../ventas/verificar_disponibilidad',
                params: {
                    id: record.data.producto_id,
                    sucursal_id:record.data.sucursal_id,
                    cantidad:cant
                },
                timeout: 3000,
                method: 'POST',
                success: function( response ){
                    var info = Ext.decode(response.responseText);
                    if (info.success){
                        //form.down('#guardar').setValue(true);
                        record.set('disponibilidad','black');
                    //return true;
                    }else{
                        //form.down('#guardar').setValue(false);
                        record.set('disponibilidad','red');
                 
                    }
                               
                },

                failure: function(result) {
                    //form.down('#guardar').setValue(false);
                    record.set('venta_fecha','red');
                    Ext.MessageBox.show({
                        title: 'Error',
                        msg: 'Error en la conexion, Intentelo nuevamente.',
                        buttons: Ext.MessageBox.OK,
                        // activeItem :0,
                        animEl: 'mb9',
                        icon: Ext.MessageBox.ERROR
                    });
                              
                }
            });
             
          

        }
      
    },
    addRow:function(grid,record){
        var now=new Date()
        var rec = Ext.create('App.Procesos.Traslado.Modelos.ModeloStoreDetalleTraslados',{
            producto_id:record.data['producto_id'],
            producto_codigo:record.data['producto_codigo'],
            producto_nombre:record.data['producto_nombre'],
            d_traslado_id:-1,
            d_traslado_cantidad:0,
            d_traslado_precio:record.data['producto_precio'],
            d_traslado_costo:record.data['producto_costo'],
            d_venta_total:0,
            traslado_id:record.data['venta_id'],
            unidad_id:record.data['unidad_id'],
            unidad_sigla:record.data['unidad_sigla'],
            movimiento_hora:now.getHours()+":"+now.getMinutes()+":"+now.getSeconds()            
               
        });
        grid.store.insert(0,rec);
    },
    cambiarTotal:function(store){
        var total=0;
        var totalCantidad=0;
        store.each(function(record){
            
            total+=record.get('d_traslado_precio') * record.get('d_traslado_cantidad');                   
            totalCantidad+=record.get('d_traslado_cantidad');  
           
            
        },this);
        //console.log(total);
        //Ext.getCmp('totalParcialVenta').setValue(total);
                 
        Ext.getCmp('val-traslado_precio_total').setValue(totalCantidad);
        Ext.getCmp('val-traslado_cantidad').setValue(totalCantidad);
    },
    eliminar:function(grid){
       
        var selection = grid.getView().getSelectionModel().getSelection()[0];
        
        if (selection) {
            if (selection.data.d_venta_id>=0){
                Ext.MessageBox.confirm('Confirmar ', 'Desea eliminar el registro seleccionado ?.\n'+
                    ' El registro se eliminar\u00e1 definitivamente, sin opci\u00f3n a recuperarlo', function(btn){
                        if(btn=='yes'){
                            Ext.Ajax.request({
                                url: '../ventas/eliminar_detalle',
                                params: {
                                    id: selection.data['d_venta_id']

                                },
                                timeout: 3000,
                                method: 'POST',
                                success: function( response ){
                                    var info = Ext.decode(response.responseText);
                                    if (info.success){
                                        try{
                                            grid.store.remove(selection);
                                            grid.getSelectionModel().select(0);
                                            Ext.example.msg('Eliminar Detalle', info.msg);
                                            Ext.getCmp('GridVentasRealizadas').store.load();
                                            grid.up('PanelPrincipalVenta').recargarItemsProductos( grid.up('PanelPrincipalVenta'));
                                        }catch(err){
                                            alert(err);
                                        }                                   
                                    }else{                                      
                                        Ext.MessageBox.show({
                                            title: 'Error',
                                            msg: info.msg,
                                            buttons: Ext.MessageBox.OK,
                                            // activeItem :0,
                                            animEl: 'mb9',
                                            icon: Ext.MessageBox.ERROR
                                        });
                                    }
                               
                                },

                                failure: function(result) {
                                    Ext.MessageBox.show({
                                        title: 'Eliminar Detalle',
                                        msg: 'Error en la conexion, Intentelo nuevamente.',
                                        buttons: Ext.MessageBox.OK,
                                        // activeItem :0,
                                        animEl: 'mb9',
                                        icon: Ext.MessageBox.ERROR
                                    });
                              
                                }
                            });
                        }
                    });

            }else{
                 
                try{
                    grid.store.remove(selection);
                    grid.getSelectionModel().select(0);  
                }catch(err){
                     
                }
                  
            }
         

        }
    
    }
   
   
});