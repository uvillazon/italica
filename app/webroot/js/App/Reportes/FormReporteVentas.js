Ext.define("App.Reportes.FormReporteVentas", {
    extend: "Ext.form.Panel",
    alias: "widget.FormReporteVentas",
    height:600,
    bodyPadding: '5 10 10',
    collapsible: false, 
    
    initComponent: function() {
        var me=this;
        // The data for all states
        var states = [
        {
            "id_reporte":0,
            "name":"VENTAS POR DIA"
        },

        {
            "id_reporte":1,
            "name":"VENTAS POR SEMANA"
        },

        {
            "id_reporte":2,
            "name":"VENTAS POR MES"
        },

        {
            "id_reporte":3,
            "name":"VENTAS POR RANGO DE FECHAS"
        },

        {
            "id_reporte":4,
            "name":"VENTAS POR VENDEDOR"
        },

        {
            "id_reporte":5,
            "name":"VENTAS FACTURADAS"
        },

        {
            "id_reporte":6,
            "name":"VENTAS NO FACTURADAS"
        }
        ];
        var meses=[{
            'id_mes':1,
            'mes':'ENERO'
        },

        {
            'id_mes':2,
            'mes':'FEBRERO'
        },

        {
            'id_mes':3,
            'mes':'MARZO'
        },

        {
            'id_mes':4,
            'mes':'ABRIL'
        },

        {
            'id_mes':5,
            'mes':'MAYO'
        },

        {
            'id_mes':6,
            'mes':'JUNIO'
        },

        {
            'id_mes':7,
            'mes':'JULIO'
        },

        {
            'id_mes':8,
            'mes':'AGOSTO'
        },

        {
            'id_mes':9,
            'mes':'SEPTIEMBRE'
        },

        {
            'id_mes':10,
            'mes':'OCTUBRE'
        },

        {
            'id_mes':11,
            'mes':'NOVIEMBRE'
        },

        {
            'id_mes':12,
            'mes':'DICIEMBRE'
        }
                       
        ];
        // Define the model for a State
        Ext.regModel('ReporteVentas', {
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
        Ext.regModel('Meses', {
            fields: [
            {
                type: 'int', 
                name: 'id_mes'
            },                    

            {
                type: 'string', 
                name: 'mes'
            }
            ]
        });

        // The data store holding the states
        var store = Ext.create('Ext.data.Store', {
            model: 'ReporteVentas',
            data: states
        });
        var storeMeses = Ext.create('Ext.data.Store', {
            model: 'Meses',
            data: meses
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

        // Simple ComboBox using the data store
        var comboVentas = Ext.create('Ext.form.field.ComboBox', {
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
        var comboMeses= Ext.create('Ext.form.field.ComboBox', {
            fieldLabel: 'ELIJA MES',
            emptyText:'SELECCIONE MES ....',
            displayField: 'mes',
            name:'data[Venta][reporte_mes]',
            valueField:'id_mes',
            labelWidth: 130,
            allowBlank:false,
            disabled:true,
            store: storeMeses,
            queryMode: 'local',
            typeAhead: true
        });
        me.items=[comboVentas,comboMeses,comboVendedor, {
            xtype:'datefield',
            labelWidth: 130,
            name:'data[Venta][reporte_fini]',
            itemId:'fecha_inicio',
            fieldLabel:'FECHA INICIO',
            disabled:true,
            allowBlank:false,
            format:'d-m-Y',
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
            value:new Date()
        },{
            xtype:'button',
            text: 'VER REPORTE',
            iconCls: 'report',             
            handler: function(){
                me.enviarDatosReporte(me);
            }
        }];
           
        comboVentas.on('change',function(cmb,newValue,oldValue,eOpts){
           
            if (cmb.getValue()==0){
                me.down('#fecha_inicio').setDisabled(false);
                me.down('#fecha_fin').setDisabled(true);
                me.down('#fecha_inicio').setFieldLabel('ELIJA DIA');
                comboMeses.setDisabled(true);
                comboVendedor.setDisabled(true);
            }
            if (cmb.getValue()==1){
                me.down('#fecha_inicio').setDisabled(false);
                me.down('#fecha_fin').setDisabled(true);
                me.down('#fecha_inicio').setFieldLabel('ELIJA DIA DE LA SEMANA');
                comboMeses.setDisabled(true);
                comboVendedor.setDisabled(true);
            }
            if (cmb.getValue()==2){
                me.down('#fecha_inicio').setDisabled(true);
                me.down('#fecha_fin').setDisabled(true);
                comboMeses.setDisabled(false);
                comboVendedor.setDisabled(true);
            }
            if (cmb.getValue()==3){
                me.down('#fecha_inicio').setDisabled(false);
                me.down('#fecha_fin').setDisabled(false);
                me.down('#fecha_inicio').setFieldLabel('ELIJA FECHA INICIAL');
                me.down('#fecha_fin').setFieldLabel('ELIJA FECHA FINAL');
                comboMeses.setDisabled(true);
                comboVendedor.setDisabled(true);
            }
            if (cmb.getValue()==4){
                me.down('#fecha_inicio').setDisabled(false);
                me.down('#fecha_fin').setDisabled(false);
                me.down('#fecha_inicio').setFieldLabel('ELIJA FECHA INICIAL');
                me.down('#fecha_fin').setFieldLabel('ELIJA FECHA FINAL');
                comboMeses.setDisabled(true);
                comboVendedor.setDisabled(false);
            }
            if (cmb.getValue()==5){
                me.down('#fecha_inicio').setDisabled(false);
                me.down('#fecha_fin').setDisabled(false);
                me.down('#fecha_inicio').setFieldLabel('ELIJA FECHA INICIAL');
                me.down('#fecha_fin').setFieldLabel('ELIJA FECHA FINAL');
                comboMeses.setDisabled(true);
                comboVendedor.setDisabled(true);
            }
            if (cmb.getValue()==6){
                me.down('#fecha_inicio').setDisabled(false);
                me.down('#fecha_fin').setDisabled(false);
                me.down('#fecha_inicio').setFieldLabel('ELIJA FECHA INICIAL');
                me.down('#fecha_fin').setFieldLabel('ELIJA FECHA FINAL');
                comboMeses.setDisabled(true);
                comboVendedor.setDisabled(true);
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
                url:'../ventas/ver_reporte_ventas',
                success:function(form, action) {
                    window.open('../ventas/ventas_pdf');
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