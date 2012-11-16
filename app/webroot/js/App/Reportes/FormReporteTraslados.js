
Ext.define("App.Reportes.FormReporteTraslados", {
    extend: "Ext.form.Panel",
    alias: "widget.FormReporteTraslados",
    height:600,
    bodyPadding: '5 10 10',
    collapsible: false, 
    
    initComponent: function() {
        var me=this;
        // The data for all states
        var states = [
        {
            "id_reporte":0,
            "name":"HISTORIAL DE CAMBIOS (RANGO DE FECHAS)"
        },

        {
            "id_reporte":1,
            "name":"HISTORIAL DE CAMBIOS POR VENDEDOR(RANGO DE FECHAS)"
        }
        ];
      
        // Define the model for a State
        Ext.regModel('ReporteCambios', {
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
       
        // The data store holding the states
        var store = Ext.create('Ext.data.Store', {
            model: 'ReporteCambios',
            data: states
        });
        
        // Simple ComboBox using the data store
        var comboTipoReporte = Ext.create('Ext.form.field.ComboBox', {
            fieldLabel: 'SELECCIONAR TIPO DE REPORTE',
            emptyText:'SELECCIONE TIPO ....',
            displayField: 'name',
            name:'data[Venta][reporte_opcion]',
            valueField:'id_reporte',
            labelWidth: 130,
            allowBlank:false,
            store: store,
            queryMode: 'local',
            typeAhead: true
        });
       
        var comboVendedor= Ext.create("App.Conf.Usuarios.Vistas.ComboUsuarios",{
            itemId:'comboVendedor',
            disabled:true,
            name:'data[Venta][reporte_vendedor]',            
            valueField:'login',
            labelWidth: 130,
            allowBlank:false,
            fieldLabel:'ELIJA VENDEDOR',
            emptyText:'VENDEDOR....'
        });
        
        me.items=[comboTipoReporte,comboVendedor, {
            xtype:'datefield',
            labelWidth: 130,
            name:'data[Venta][reporte_fini]',
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
            name:'data[Venta][reporte_ffin]',
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
                me.down('#fecha_inicio').setDisabled(false);
                me.down('#fecha_fin').setDisabled(false);
               
                comboVendedor.setDisabled(true);
               
            }
            if (cmb.getValue()==1){
                me.down('#fecha_inicio').setDisabled(false);
                me.down('#fecha_fin').setDisabled(false);
               
                comboVendedor.setDisabled(false);
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
                url:'../ventas/ver_reporte_cambios',
                success:function(form, action) {
                        window.open('../ventas/cambios_pdf');
                  
                 
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