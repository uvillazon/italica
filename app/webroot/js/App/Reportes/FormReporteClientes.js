
Ext.define("App.Reportes.FormReporteClientes", {
    extend: "Ext.form.Panel",
    alias: "widget.FormReporteClientes",
    height:600,
    bodyPadding: '5 10 10',
    collapsible: false, 
    
    initComponent: function() {
        var me=this;
        // The data for all states
        var states = [
        {
            "id_reporte":0,
            "name":"LISTADO DE CLIENTES"
        },

        {
            "id_reporte":1,
            "name":"HISTORIAL DE COMPRAS"
        },

        {
            "id_reporte":2,
            "name":"HISTORIAL DE CAMBIOS"
        }
        ];
        var orden=[{
            "id":"ASC",
            "name":"ORDEN ALFABETICO ASC"
        },{
            "id":"DESC",
            "name":"ORDEN ALFABETICO DESC"
        }];
    
        // Define the model for a State
        Ext.regModel('ReporteClientes', {
            fields: [
            {
                type: 'int', 
                name: 'id_reporte'
            },                    

            {
                type: 'string', 
                name: 'name'
            }
            ]
        });
        // Define the model for a State
        Ext.regModel('Orden', {
            fields: [
            {
                type: 'string', 
                name: 'id'
            },                    

            {
                type: 'string', 
                name: 'name'
            }
            ]
        });
      

        // The data store holding the states
        var store = Ext.create('Ext.data.Store', {
            model: 'ReporteClientes',
            data: states
        });
        // The data store holding the states
        var storeOrden = Ext.create('Ext.data.Store', {
            model: 'Orden',
            data: orden
        });
      
      
        // Simple ComboBox using the data store
        var comboTipoReporte = Ext.create('Ext.form.field.ComboBox', {
            fieldLabel: 'SELECCIONAR TIPO DE REPORTE',
            emptyText:'SELECCIONE TIPO ....',
            displayField: 'name',
            name:'data[Cliente][reporte_opcion]',
            valueField:'id_reporte',
            labelWidth: 130,
            allowBlank:false,
            store: store,
            queryMode: 'local',
            typeAhead: true
        });
        // Simple ComboBox using the data store
        var comboOrden= Ext.create('Ext.form.field.ComboBox', {
            fieldLabel: 'SELECCIONAR ORDEN DE REPORTE',
            emptyText:'SELECCIONE ORDEN ....',
            disabled:true,
            displayField: 'name',
            name:'data[Cliente][reporte_orden]',
            valueField:'id',
            labelWidth: 130,
            allowBlank:false,
            store: storeOrden,
            queryMode: 'local',
            typeAhead: true
        });
        var comboCliente= Ext.create("App.Conf.Clientes.Vistas.ComboClientes",{
            itemId:'comboClientes',
            hideTrigger:false,
            disabled:true,
            labelWidth: 130,
            fieldLabel:'CLIENTE',
            name:'data[Cliente][reporte_cliente]'
        });
        
        me.items=[comboTipoReporte,comboOrden,comboCliente, {
            xtype:'datefield',
            labelWidth: 130,
            name:'data[Cliente][reporte_fini]',
            itemId:'fecha_inicio',
            fieldLabel:'FECHA INICIO',
            disabled:true,
            allowBlank:false,
            format:'d-m-Y',
            
             endDateField: 'fecha_fin',
             vtype: 'daterange',
            value:new Date()
        },{
            xtype:'datefield',
            labelWidth: 130,
            name:'data[Cliente][reporte_ffin]',
            itemId:'fecha_fin',
            fieldLabel:'FECHA FIN',
            disabled:true,
            allowBlank:false,
            format:'d-m-Y',
           
            startDateField: 'fecha_inicio',
            vtype: 'daterange'
        },{
            xtype:'button',
            text: 'VER REPORTE',
            iconCls: 'report',             
            handler: function(){
                me.enviarDatosReporte(me);
            }
        }];
           
        comboTipoReporte.on('change',function(cmb,newValue,oldValue,eOpts){
           
            if (cmb.getValue()==0){
                me.down('#fecha_inicio').setDisabled(true);
                me.down('#fecha_fin').setDisabled(true);
                comboOrden.setDisabled(false);
                comboCliente.setDisabled(true);
               
            }
            if (cmb.getValue()==1){
                me.down('#fecha_inicio').setDisabled(false);
                me.down('#fecha_fin').setDisabled(false);
                comboOrden.setDisabled(true);
                comboCliente.setDisabled(false);
            }
            if (cmb.getValue()==2){
                me.down('#fecha_inicio').setDisabled(false);
                me.down('#fecha_fin').setDisabled(false);
                comboOrden.setDisabled(true);
                comboCliente.setDisabled(false);
            }
          
        });
              
        this.callParent(arguments);
    },
    
    enviarDatosReporte:function(formulario){
        var form= formulario.getForm();
        form.method = 'POST';
        if (form.isValid()) {

            form.submit(
            {
                waitTitle:'Espere por favor',
                waitMsg: 'Enviando datos...',
                url:'../clientes/ver_reporte_clientes',
                success:function(form, action) {
                    
                    switch(parseInt(action.result.opcion))
                    {
                        case 0:
                            window.open('../clientes/listado_pdf');
                            break;
                        case 1:
                            window.open('../clientes/compras_pdf');
                            break;
                        case 2:
                            window.open('../clientes/cambios_pdf');
                            break;
                        default:
  
                    }
                 
                /*var miVentana = new Ext.Window({  
                        title: 'Reporte Ventas',  
                        width: 500, 
                        height:600,
                       maximizable:true,
                        html: '<iframe src="ventas_pdf" style="width:100%;height:100%;border:none;"></iframe>'  
                    });                     
                 
                    miVentana.show();  */                  
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
    }
});