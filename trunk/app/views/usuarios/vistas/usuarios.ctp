<script>

    Ext.onReady(function(){
        var win;// ventana para formulario
        var logindisponible=false;
        var modoEdicion=false;
        var mascara;
        Ext.define('Usuarios', {
            extend: 'Ext.data.Model',
            fields: [

                {name: 'persona_id', type: 'int'},
                {name: 'usuario_id', type: 'int'},
                {name: 'rol_id', type: 'int'},
                {name: 'rol_nombre',type: 'string'},
                 {name: 'sucursal_id',type: 'int'},
                 {name: 'sucursal_nombre',type: 'string'},
                {name: 'persona_nombres',type: 'string'},

                {name: 'persona_apellido1',type: 'string'},
                {name: 'persona_apellido2',type: 'string'},
                {name: 'persona_fecha_nac',type: 'date'},
                {name: 'persona_doci',type: 'int'},
                 {name: 'persona_exp_doci',type: 'string'},
                {name: 'persona_dir',type: 'string'},
                {name: 'login',type: 'string'},
                {name: 'password',type: 'string'},
                {name: 'activo',type: 'boolean'}

            ]
        });
        var store = new Ext.data.JsonStore({
            // store configs
            autoDestroy: true,
            //autoLoad:true,
            pageSize: 20,
            storeId: 'myStore',
            model: 'Usuarios',
            proxy: {
                type: 'ajax',
                url: '../usuarios/getusers',
                reader: {
                    type: 'json',
                    root: 'usuarios',
                    totalProperty: 'total',
                    idProperty: 'usuario_id'
                }
            }
        });

        var grid = Ext.create('Ext.grid.Panel', {
            width: '100%',
            height: '100%',
            minHeight:500,
            minWidth:600,
            stateful: true,
            title: 'Listado de Usuarios',
            store: store,
            forceFit: true,
            region: 'west',
            renderTo: 'panel_usuarios',
            //stateId: 'stateGrid',
            //disableSelection: true,
            loadMask: true,
            //columnLines: true,
            // grid columns
            columns:[Ext.create('Ext.grid.RowNumberer'),{

                    id: 'persona_id',
                    text: "Usuario Id",
                    dataIndex: 'persona_id',
                    width: 50,

                    hidden:true,
                    sortable: true
                },{
                    text: "Nombres",
                    dataIndex: 'persona_nombres',

                    width: 100,

                    sortable: true
                },{
                    text: "Apellido Paterno",
                    dataIndex: 'persona_apellido1',
                    width: 100,

                    sortable: true
                },{
                    text: "Apellido Materno",
                    dataIndex: 'persona_apellido2',
                    width: 100,
                    sortable: true
                },{
                    text: "Fecha Nac.",
                    dataIndex: 'persona_fecha_nac',
                    width: 100,
                    renderer : Ext.util.Format.dateRenderer('m/d/Y'),
                    sortable: true
                },{
                    text: "C.I.",
                    dataIndex: 'persona_doci',
                    width: 50,

                    sortable: true
                },{
                    text: "Direcci\u00f3n",
                    dataIndex: 'persona_dir',
                    width: 150,
                    //align: 'right',

                    sortable: false
                },{
                    text: "Login",
                    dataIndex: 'login',
                    width: 70,
                    sortable: true
                },{
                    text: "Estado",
                    dataIndex: 'activo',
                    width: 50,
                    renderer:renderEstado,
                    sortable: true
                },{
                    text: "Rol",
                    dataIndex: 'rol_nombre',
                    width: 100,
                    sortable: true
                },{
                    text: "Sucursal",
                    dataIndex: 'sucursal_nombre',
                    width: 100,
                    sortable: true
                }],
            dockedItems: [{
                    xtype: 'toolbar',
                    items: [{
                            text: 'Nuevo',
                            iconCls: 'add',
                            hidden:<?php echo $permisos["c"]?>,
                            handler: nuevo
                        }, '-',{
                            text: 'Modificar',
                            itemId: 'edit',
                            hidden:<?php echo $permisos["w"]?>,
                            disabled: true,
                            iconCls: 'page_edit',
                            handler: modificar
                        },'-', {
                            itemId: 'delete',
                            text: 'Eliminar',
                            hidden:<?php echo $permisos["d"]?>,
                            iconCls: 'delete',
                            disabled: true,
                            handler: eliminar
                        }]
                }],
            // paging bar on the bottom
            bbar: Ext.create('Ext.PagingToolbar', {
                store: store,
                displayInfo: true,
                displayMsg: 'Mostrando {0} - {1} de {2}',
                emptyMsg: "No hay Roles para mostrar"

            })
        });

        store.loadPage(1);
        grid.store.on('beforeload',function(){
            showMask(true,grid,'Cargando....');
        });
        grid.store.on('refresh',function(){
            showMask(false);
        });
        grid.store.on('load', function(store, records, options){
            grid.getSelectionModel().select(0);
            showMask(false);
            //getDataStore(store.);
        });

        grid.getSelectionModel().on('selectionchange', function(selModel, selections,selectedRecord){
            grid.down('#delete').setDisabled(selections.length === 0);
            grid.down('#edit').setDisabled(selections.length === 0);
            /* if (selections[0]) {
formPanel.getForm().loadRecord(selections[0]);
}*/

        });

        // codigo acerca del form

        Ext.define('comboModel', {
            extend: 'Ext.data.Model',
            fields: [
                {name: 'rol_id', type: 'string'},
                {name: 'rol_nombre',  type: 'string'},
                {name: 'rol_descripcion',  type: 'string'}
            ]
        });
        var storeCombo = new Ext.data.Store({
            model: 'comboModel',
            pageSize:10,
            proxy: {
                type: 'ajax',
                url: '../rols/getroles',
                reader: {
                    type: 'json',
                    root: 'roles',
                    totalProperty: 'total'
                }
            },
            autoLoad: false
        });

        Ext.define('comboModelSucursal', {
            extend: 'Ext.data.Model',
            fields: [
                {name: 'sucursal_id', type: 'int'},
                {name: 'sucursal_nombre',  type: 'string'},
                {name: 'sucursal_dir',  type: 'string'}
            ]
        });
        var storeComboSucursal = new Ext.data.Store({
            model: 'comboModelSucursal',
            pageSize:5,
            proxy: {
                type: 'ajax',
                url: '../sucursals/getsucursals',
                reader: {
                    type: 'json',
                    root: 'datos',
                    totalProperty: 'total'
                }
            },
            autoLoad: false
        });
        function formPanel(){
            var formPanelUsuario = Ext.widget({
                xtype: 'form',
                layout: 'form',
                //renderTo:'panel_form',

                id: 'simpleForm',

                frame: false,
                resizable:false,
                bodyPadding: '15 35 35',
                width: '98%',
                height:'98%',
                fieldDefaults: {
                    msgTarget: 'side',
                    labelWidth: 75
                },
                defaultType: 'textfield',
                items: [{
                        id:'data-usuario_nombres',
                        fieldLabel: 'Nombres',
                        allowBlank:false,
                        name: 'data[Persona][persona_nombres]'
                        // name:'usuario_nombres'
                    },{
                        id:'data-usuario_apellido1',
                        fieldLabel: 'Apellido Paterno',
                        allowBlank:false,
                        name: 'data[Persona][persona_apellido1]'
                        // name:'usuario_apellido1'
                    },{
                        id:'data-usuario_apellido2',
                        fieldLabel: 'Apellido Materno',
                        allowBlank:false,
                        name: 'data[Persona][persona_apellido2]'
                        //name:'usuario_apellido2'
                    },{
                        id:'data-usuario_fecha_nac',
                        fieldLabel: 'Fecha Nac.',
                        allowBlank:false,
                        xtype: 'datefield',
                        name: 'data[Persona][persona_fecha_nac]'
                        //name:'usuario_fecha_nac'
                    },{
                        id:'data-usuario_doci',
                        fieldLabel: 'C.I.',
                        allowBlank:false,
                        name: 'data[Persona][persona_doci]',
                        // name:'usuario_fecha_nac',
                        xtype: 'numberfield'
                    },{
                        id:'data-persona_exp_doci',
                        fieldLabel: 'Exp. en',
                        allowBlank:false,
                        name: 'data[Persona][persona_exp_doci]'
                        // name:'usuario_dir'
                    },{
                        id:'data-usuario_dir',
                        fieldLabel: 'Direcci\u00f3n',
                        allowBlank:false,
                        name: 'data[Persona][persona_dir]'
                        // name:'usuario_dir'
                    },{
                        id:'data-login',
                        fieldLabel: 'Usuario',
                        allowBlank:false,
                        name: 'data[Usuario][login]',
                        // name:'login',
                        listeners:{
                            change: function(){
                                Ext.getCmp("data-password").setValue( Ext.getCmp("data-login").getValue());
                            },
                            blur: function (){
                                verificarLogin();
                            }
                        }
                    },{
                        hidden:true,
                        xtype:'label',
                        html: '<font color="red" size=1><b>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;LOGIN NO DISPONIBLE</b></font><br>',
                        id:'lbllogino',
                        anchor:'90%'
                    },
                    {
                        id:'data-password',
                        fieldLabel: 'Contrase\u00f1a',
                        inputType: 'password',
                        allowBlank:false,

                        name: 'data[Usuario][password]'

                    }, {
                        id:'data-activo',
                        fieldLabel: 'Estado',
                        xtype: 'checkboxfield',
                        checked:true,
                        // name:'usuario_activo'
                        name: 'data[Usuario][activo]'
                    },
                    {
                        xtype:             'combo',
                        id:'data-rol_id',
                        name:'data[Usuario][rol_id]',

                        fieldLabel:       'Rol',
                        mode:           'remote',
                        triggerAction:     'all',
                        store:storeCombo,
                        valueField:       'rol_id',
                        displayField:   'rol_nombre',
                        //forceSelection:true,
                        typeAhead:true,
                        selectOnFocus: true,
                        //width: 570,
                        pageSize:10,
                        resizable:true,
                        emptyText:'Seleccione Rol....',

                        lazyRender:true,
                        minChars:1,
                        allowBlank: false,

                        itemSelector: 'div.search-item',

                        listConfig: {
                            loadingText: 'Buscando...',
                            emptyText: 'No se ha encontrado coincidencias.',

                            // Custom rendering template for each item
                            getInnerTpl: function() {
                                return '<div class="search-item"><div class="name">{rol_nombre}</div>' +
                                    '<div class="desc">{rol_descripcion}</div></div>';
                            }
                        }
                    },{
                        xtype:             'combo',
                        id:'data-sucursal_id',
                        name:'data[Usuario][sucursal_id]',
                        fieldLabel:       'Sucursal:',
                        mode:           'remote',
                        triggerAction:     'all',
                        store:storeComboSucursal,
                        valueField:       'sucursal_id',
                        displayField:   'sucursal_nombre',
                        //forceSelection:true,
                        typeAhead:true,
                        selectOnFocus: true,
                        //width: 570,
                        pageSize:5,
                        //resizable:true,
                        emptyText:'Seleccione Sucursal....',
                        lazyRender:true,
                        minChars:1,
                        allowBlank: false,
                        itemSelector: 'div.search-item',
                        listConfig: {
                            loadingText: 'Buscando...',
                            emptyText: 'No se ha encontrado sucursales.',

                            // Custom rendering template for each item
                            getInnerTpl: function() {
                                return '<div class="search-item"><div class="name">{sucursal_nombre}</div>' +
                                    '<div class="desc">{sucursal_dir}</div></div>';
                            }
                        }
                    }, {////////////////////hidden
                        xtype:'hidden',
                        id: 'data-persona_id',

                        name:'data[Persona][persona_id]'
                    }, {////////////////////hidden
                        xtype:'hidden',
                        id: 'data-usuario_id',

                        name:'data[Usuario][usuario_id]'
                    }],

                buttons: [{
                        text: 'Guardar',
                        id:'btnguardar',
                        iconCls:'disk',
                        handler: function() {
                            var form = formPanelUsuario.getForm();

                            form.method = 'POST';
                            if(logindisponible){
                                if (form.isValid()) {

                                    form.submit(
                                    {
                                        waitTitle:'Espere por favor',
                                        waitMsg: 'Enviando datos...',
                                        url:'../usuarios/guardar_usuario',
                                        success:function(form, action) {
                                            win.close();
                                            grid.store.load();
                                            grid.store.on('load', function(store, records, options){
                                                grid.store.each(function(record){
                                                    if (record.raw['usuario_id']==parseInt(action.result.msg)){
                                                        grid.getSelectionModel().select(record.index);
                                                    }
                                                });
                                            });
                                            Ext.example.msg('Usuario', 'Se guardo el registro satisfactoriamente ');

                                        },
                                        failure: function(form, action) {
                                            win.close();
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
                                    //
                                }
                            }else{
                                Ext.MessageBox.show({
                                    title: 'Error',
                                    msg: 'El nombre de usuario que eligio ya esta registrado en el sistema',
                                    buttons: Ext.MessageBox.OK,
                                    // activeItem :0,
                                    animEl: 'mb9',
                                    icon: Ext.MessageBox.ERROR
                                });
                            }
                        }
                    },{
                        text: 'Cancelar',
                        id:'btncancelar',
                        iconCls:'cancel',
                        handler: function() {
                            win.close();
                        }
                    }]
            });

            return formPanelUsuario;
        }

        //funciones render
        function renderEstado(value){
            if(value)
                return '<font color="green">Activo</font>';
            else
                return '<font color="red">Bloqueado</font>';
        }

        //funciones para interactuar con la base de datos
        function nuevo(){
            // empty record
            var form=formPanel();
            form.getForm().reset();
            modoEdicion=false;
            logindisponible=false;
            Ext.getCmp('lbllogino').setVisible(false);
            win=Ext.create("Funciones.Ventana",{
                title:'Nuevo Usuario',
                width:400,
                height:450,
                items:[form]
            }).show();

        }
        function modificar(){
            var selection = grid.getView().getSelectionModel().getSelection()[0];
            if (selection) {
                var form=formPanel();
                modoEdicion=true;
                logindisponible=true;
                Ext.getCmp('lbllogino').setVisible(false);

                win=Ext.create("Funciones.Ventana",{
                    title:'Nuevo Usuario',
                    width:400,
                    height:450,
                    items:[form]
                }).show();

                Ext.getCmp("data-usuario_id").setValue(selection.data['usuario_id']);
                 Ext.getCmp("data-persona_id").setValue(selection.data['persona_id']);
                Ext.getCmp("data-usuario_nombres").setValue(Ext.String.trim(selection.data['persona_nombres']));
                Ext.getCmp("data-usuario_apellido1").setValue(Ext.String.trim(selection.data['persona_apellido1']));
                Ext.getCmp("data-usuario_apellido2").setValue(Ext.String.trim(selection.data['persona_apellido2']));
                Ext.getCmp("data-usuario_fecha_nac").setValue(selection.data['persona_fecha_nac']);
                Ext.getCmp("data-usuario_doci").setValue(selection.data['persona_doci']);
                Ext.getCmp("data-usuario_dir").setValue(Ext.String.trim(selection.data['persona_dir']));
                Ext.getCmp("data-login").setValue(Ext.String.trim(selection.data['login']));
                Ext.getCmp("data-activo").setValue(selection.data['activo']);
                Ext.getCmp("data-rol_id").setValue(selection.data['rol_id']);
                Ext.getCmp("data-persona_exp_doci").setValue(selection.data['persona_exp_doci']);
                Ext.getCmp("data-sucursal_id").setValue(selection.data['sucursal_id']);
                // Ext.getCmp("data-rol_id").setRawValue(selection.data['rol_nombre']);

            }

        }
        function eliminar(){
            var selection = grid.getView().getSelectionModel().getSelection()[0];
            if (selection) {
                Ext.MessageBox.confirm('Confirmar ', 'Desea eliminar el registro seleccionado ?.\n'+
                    ' El registro se eliminara definitivamente sin opci\u00f3n a recuperarlo', function(btn){
                    if(btn=='yes'){
                        Ext.Ajax.request({
                            url: '../usuarios/eliminar_usuario',
                            params: {
                                usuario_id: selection.data['usuario_id'],
                                persona_id:selection.data['persona_id']
                            },
                            timeout: 3000,
                            method: 'POST',
                            success: function( response ){
                                var info = Ext.decode(response.responseText);
                                if (info.success){
                                    grid.store.remove(selection);
                                    grid.getSelectionModel().select(0);
                                }
                                Ext.example.msg('Eliminar Usuario', info.msg);
                            },

                            failure: function(result) {

                                Ext.example.msg('Eliminar Usuario', 'Error en la conexion, Intentelo nuevamente.');
                            }
                        });
                    }
                });


            }
        }
        function verificarLogin(){
            if(modoEdicion==false){
                Ext.Ajax.request({
                    url: '../usuarios/existe_login',
                    params: {
                        login:Ext.getCmp("data-login").getValue()
                    },
                    timeout: 3000,
                    method: 'POST',
                    success: function( response ){
                        var info = Ext.decode(response.responseText);
                        if (info.success){
                            Ext.getCmp('lbllogino').setVisible(false);
                            logindisponible=true;
                        }else{
                            Ext.getCmp('lbllogino').setVisible(true);
                            logindisponible=false;
                        }



                    },

                    failure: function(result) {

                        Ext.example.msg('Verificar Nombre de Usuario', 'Error en la conexion, Intentelo nuevamente.');
                    }
                });
            }

        }



    });



</script>

<div id="panel_usuarios"></div>
