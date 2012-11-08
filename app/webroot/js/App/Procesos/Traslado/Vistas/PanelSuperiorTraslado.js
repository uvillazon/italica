Ext.define("App.Procesos.Traslado.Vistas.PanelSuperiorTraslado", {
    extend: "Ext.form.Panel",
    alias: "widget.PanelSuperiorTraslado",
    bodyPadding: '5 10 10',
    grid:'',
    collapsible: false,   
    initComponent: function() {
        var me=this;
        var origen= Ext.create("App.Conf.Sucursales.Vistas.ComboSucursales",{
            itemId:'comboOrigen',
            fieldLabel:'PUNTO ORIGEN',
            name:'data[Traslado][sucursal_origen]'
        });
        var destino= Ext.create("App.Conf.Sucursales.Vistas.ComboSucursales",{
            itemId:'comboDestino',
            fieldLabel:'PUNTO DESTINO',
            name:'data[Traslado][sucursal_destino]'
        });
      
        this.items=[{
            xtype:'panel',
            baseCls:'x-plain',

            layout: {
                type: 'table',
                columns: 2,
                tableAttrs: {
                    style: {
                        width: '100%',
                        bodyStyle: 'padding:5px'
                    }
                }
            },
          
            items:[{
                xtype:'textfield',
                labelWidth: 50,
                readOnly:true,
                id:'traslado_id',
                itemId:'nro_registro',
                fieldLabel:'NRO',
                name:'data[Traslado][traslado_id]',
                value:''
            },origen,
            {
                xtype:'datefield',
                labelWidth: 50,
                name:'data[Traslado][traslado_fecha]',
                itemId:'traslado_fecha',
                fieldLabel:'FECHA',
                rowspan:2,
                allowBlank:false,
                format:'d/m/Y',
                value:new Date(),
                listeners:{
                    change:function(field,newValue,oldValue,eOpts){
                        me.grid.store.each(function(record){
                            //console.log(record.raw['sucursal_id']);
                            // Ext.Date.format(,'d-m-Y H:i:s')
                            //record.set('compra_fecha', newValue);
                            record.set('traslado_fecha', Ext.Date.format(newValue,'d/m/Y'));
                        });
                    }
                }
            },destino,  
           
            {
                xtype:'hidden',
                id:'val-traslado_precio_total',
                itemId:'val-traslado_precio_total',
                value:0,
                name:'data[Traslado][traslado_precio_total]'
            },
            {
                xtype:'hidden',
                id:'val-traslado_cantidad',
                itemId:'val-traslado_cantidad',
                value:0,
                name:'data[Traslado][traslado_cantidad]'
            },
            {
                xtype:'hidden',
                itemId:'val-records',               
                name:'data[Traslado][records]'
            }
            ]
        }
        ];
        origen.on('change',function(cmb,newValue,oldValue,eOpts){
            me.grid.store.each(function(record){
              
                record.set('sucursal_origen', cmb.getValue());
                if(record.data.d_traslado_id==-1)
                    record.set('d_traslado_cantidad', 0);
            });
           
        });
        destino.on('change',function(cmb,newValue,oldValue,eOpts){
            me.grid.store.each(function(record){               
                record.set('sucursal_destino', cmb.getValue());
                if(record.data.d_traslado_id==-1)
                    record.set('d_traslado_cantidad', 0);
            });
           
        });
        me.callParent(arguments);
    }, 
   
    guardar:function(formulario,grid,gridTraslados,gridItems){
        var form= formulario.getForm();
        var modificado = grid.getStore().getModifiedRecords();
        var guardar=true;
        var mensaje="";
        var recordsToSend = [];
       
        grid.store.each(function(record){
            //console.log(record.data.venta_fecha);
            if (record.data.disponibilidad=='red'){
                guardar=false;
                mensaje+=" - No existe la cantidad necesaria para el producto"+ record.data.producto_codigo+". <br>";
            // console.log(guardar);
            }
            if(record.data.d_venta_cantidad==0){
                mensaje+=" - No puede registrar  una cantidad igual a cero del producto"+record.data.producto_codigo+".<br>";
            }
          
        });
        mensaje+="<br><b>Corrija las catidades en rojo y vuelva a intentarlo.</b>"; 
        form.method = 'POST';
        if (guardar){
            if(!Ext.isEmpty(modificado)){
                Ext.each(modificado, function(record) {
                
                    // alert(guardar);
                    recordsToSend.push(
                        Ext.apply({
                            id:record.id
                        },record.data));
                });
            //console.log(recordsToSend);
            }
            //grid.stopEditing();

            recordsToSend = Ext.encode(recordsToSend);
            formulario.down('#val-records').setValue(recordsToSend);
            if (form.isValid()) {

                form.submit(
                {
                    waitTitle:'Espere por favor',
                    waitMsg: 'Enviando datos...',
                    url:'../traslados/guardar_traslado',
                    success:function(form, action) {

                        Ext.example.msg('Guardar', action.result.msg);
                        formulario.down('#nro_registro').setValue(action.result.id);
                        grid.getStore().commitChanges();
                        grid.store.load({
                            params:{
                                traslado_id:action.result.id
                            }
                        });
                        gridTraslados.store.load();
                        gridTraslados.store.on('load', function(store, records, options){
                            //console.log('cargando...');
                            store.each(function(record){
                                //console.log(record.raw['compra_id']);
                                if (record.raw['traslado_id']==parseInt(action.result.id)){
                                    gridTraslados.getSelectionModel().select(record.index);
                                }
                            });
                        });
                        gridItems.recargarItems(gridItems);
                    // Ext.getCmp('idordenproduccion').setValue(action.result.idordenproduccion);
                   

                    },
                    failure: function(form, action) {
                    
                        Ext.MessageBox.show({
                            title: 'Error',
                            msg: action.result.msg,
                            buttons: Ext.MessageBox.OK,
                            // activeItem :0,
                            animEl: 'mb9',
                            icon: Ext.MessageBox.ERROR
                        });

                    }
                }
                );


            }
        }else{
            Ext.MessageBox.show({
                title: 'Error',
                msg: mensaje,
                buttons: Ext.MessageBox.OK,
                // activeItem :0,
                animEl: 'mb9',
                icon: Ext.MessageBox.ERROR
            }); 
        }
        
    }
});