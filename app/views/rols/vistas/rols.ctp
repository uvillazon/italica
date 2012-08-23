<script>
    /* Ext.Loader.setConfig({enabled: true});
    Ext.Loader.setPath('Ext.ux', '/libs/ext41/examples/ux');
    Ext.require([
        'Ext.grid.*',
        'Ext.selection.CellModel',
        'Ext.data.*',
        'Ext.util.*',
        'Ext.state.*',
        'Ext.toolbar.Paging',
        'Ext.ux.CheckColumn',
        'Ext.ModelManager',

    ]);*/



    Ext.onReady(function(){
        var winRol;// ventana para formulario
        var selectedKeysasig;//para almacenar los ids coorrespondientes a cada rol
        var id_rol;// id del rolseleccionado
        var mascara;
        Ext.define('Roles', {
            extend: 'Ext.data.Model',
            fields: [

                {name: 'rol_id', type: 'int'},
                {name: 'rol_nombre', type: 'string'},
                {name: 'rol_descripcion',type: 'string'}

            ]
        });
        var store = new Ext.data.JsonStore({
            // store configs
            autoDestroy: true,
            autoLoad:true,
            storeId: 'myStore',
            model: 'Roles',
            proxy: {
                type: 'ajax',
                url: '../rols/getroles',
                reader: {
                    type: 'json',
                    root: 'roles',
                    idProperty: 'rol_id'
                }
            }
        });

        var grid = Ext.create('Ext.grid.Panel', {
            width: '60%',
            height: 500,
            stateful: true,
            title: 'Listado de Roles',
            store: store,
            forceFit: true,
            region: 'west',

            //columnLines: true,
            // grid columns
            columns:[Ext.create('Ext.grid.RowNumberer'),{

                    id: 'rol_id',
                    text: "Rol Id",
                    dataIndex: 'rol_id',
                    width: 50,
                    hidden:true,
                    sortable: true
                },{
                    text: "Nombre de Rol",
                    dataIndex: 'rol_nombre',
                    width: 200,

                    sortable: true
                },{
                    text: "Descripci\u00f3n",
                    dataIndex: 'rol_descripcion',
                    width: 300,
                    //align: 'right',

                    sortable: false
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
                            disabled: true,
                            hidden:<?php echo $permisos["w"]?>,
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


        ///////////////////////////////////////////
        //CODIGO PARA EL GRID DONDE SE MUESTRA LAS OPCIONES DEL MENU


        Ext.define('Opciones', {
            extend: 'Ext.data.Model',
            fields: [

                {name: 'opcion_id', type: 'int'},
                {name: 'opcion_nombre', type: 'string'},
                {name: 'padre',type: 'string'},
                {name: 'opcion_padre',type: 'int'},
                {name: 'opcion_url',type: 'string'},
                {name: 'opcion_icon',type: 'string'},
                {name: 'leaf',type: 'boolean'},
                {name: 'opcion_descripcion',type: 'string'}

            ]
        });
        var storeMenu = new Ext.data.JsonStore({
            // store configs
            autoDestroy: true,
            autoLoad:true,
            storeId: 'myStoreMenu',
            model: 'Opciones',
            proxy: {
                type: 'ajax',
                url: '../opciones/getopcions',
                reader: {
                    type: 'json',
                    root: 'menus',
                    idProperty: 'opcion_id'
                }
            }
        });

        //para manejar las opciones segun el rol
        Ext.define('OpcionesxRol', {
            extend: 'Ext.data.Model',
            fields: [
                {name:'rol_opcion_id'},
                {name:'opcion_id'},
                {name:'opcion_nombre'},
                {name:'opcion_rol_r'},
                {name:'opcion_rol_w'},
                {name:'opcion_rol_c'},
                {name:'opcion_rol_d'},
                {name:'rol_id'}

            ]
        });
        var storeOpcionesxRol = new Ext.data.JsonStore({
            // store configs
            autoDestroy: true,
            autoLoad:true,
            storeId: 'myStoreMenu',
            model: 'OpcionesxRol',
            proxy: {
                type: 'ajax',
                url: '../opciones/getopcionesxrol',
                reader: {
                    type: 'json',
                    root: 'roles',
                    idProperty: 'rol_opcion_id'
                }
            }
        });

        var smm = Ext.create('Ext.selection.CheckboxModel');

        var gridMenu = Ext.create('Ext.grid.Panel', {
            store: storeMenu,
            selModel: smm,

            title:'Listado de Opciones de Men\u00fa',
            columns: [
                {id:'id',header: "Id", width: 30, hidden:true,sortable: true, locked:false, dataIndex: 'opcion_id'},
                {header: "Men\u00fa", width: 150,sortable: true, dataIndex: 'padre'},
                {header: "Nombre", width: 200, sortable: true, dataIndex: 'opcion_nombre'},
                {header: "Opcion Padre", width: 50, sortable: true, dataIndex: 'opcion_padre', hidden:true},
                {header: "Url", width: 50, sortable: true, dataIndex: 'opcion_url', hidden:true},
                {header: "Icon", width: 50, sortable: true, dataIndex: 'opcion_icon', hidden:true},
                {header: "Leaf", width: 50, sortable: true, dataIndex: 'leaf', hidden:true},
                {header: "Descripcion", width: 50, sortable: false, dataIndex: 'opcion_descripcion', hidden:true}


            ],
            //columnLines: true,
            forceFit: true,
            region: 'east',
            width: '40%',
            height: 500,
            frame: true,
            dockedItems: [{
                    xtype: 'toolbar',
                    items: ['-',{
                            //text: 'Guardar',
                            disabled:true,
                            itemId: 'guardar',
                            tooltip:'Almacena las opciones seleccionadas en el rol seleccionado',
                            iconCls: 'disk_multiple',
                            handler: function(){

                                var selectedKeys=[];
                                selectedKeys=obtenerSeleccionados();
                                //console.log(selectedKeys);
                                if(selectedKeys.length>0){
                                    Ext.Ajax.request({
                                        url: '../rols/guardar_opcionesxrol/'+selectedKeys+'/'+selectedKeysasig,
                                        params: {
                                            rol_id:id_rol
                                        },
                                        timeout: 3000,
                                        method: 'POST',
                                        success: function( response ){
                                            var info = Ext.decode(response.responseText);
                                            if (info.success){
                                                storeOpcionesxRol.load();


                                                //grid.store.remove(selection);
                                            }
                                            Ext.example.msg('Guardar Opciones', info.msg);
                                        },

                                        failure: function(result) {

                                            Ext.example.msg('Guardar Opciones', 'Error en la conexion, Intentelo nuevamente.');
                                        }
                                    });
                                }else{
                                    Ext.Msg.alert('Error', 'Debe seleccionar por lo menos una opcion para asignar al rol');
                                }

                            }
                        },'-',{
                            // text: 'Permisos',
                            disabled:true,
                            itemId: 'permisos',
                            hidden:<?php echo $permisos["w"]?>,
                            iconCls: 'application_key',
                            tooltip:'Edita los permisos de la opci\u00f3n seleccionada',
                            handler: function(){
                                permisos();
                            }
                        },'-','->','-',{
                            //nueva opcion,
                            iconCls: 'add',
                            tooltip:'Crea una nueva opci\u00f3n ',
                            hidden:<?php echo $permisos["c"]?>,
                            handler: function(){
                                nuevaOpcion(false);
                            }
                        },'-',{
                            //editar opcion
                            disabled:true,
                            iconCls: 'page_edit',
                            hidden:<?php echo $permisos["w"]?>,
                            itemId: 'edit',
                            tooltip:'Edita  la opci\u00f3n seleccionada',
                            handler: function(){
                                nuevaOpcion(true);
                            }
                        },'-',{
                            //eliminar opcion
                            disabled:true,
                            itemId: 'delete',
                            hidden:<?php echo $permisos["d"]?>,
                            iconCls: 'delete',
                            tooltip:'Elimina de la opci\u00f3n seleccionada',
                            handler: function(){
                                eliminarOpcion();
                            }
                        },'-']
                }]
            //title: 'Framed with Checkbox Selection and Horizontal Scrolling',


        });
        grid.store.on('refresh', function(){
            showMask(false);
            grid.getSelectionModel().select(0);
            var selection = grid.getView().getSelectionModel().getSelection()[0];
            if (selection.length) {
                id_rol=selection[0].data['rol_id'];
                getDataStore(id_rol);

                //alert(selections[0].data['rol_id']);
            }

        });
        grid.store.on('beforeload', function(store, records, options){
            showMask(true,panel,'Cargando....');
        });
        grid.store.on('load', function(store, records, options){
            gridMenu.store.on('load',function(){
                grid.getSelectionModel().select(0);
                showMask(false);
            });

            //getDataStore(store.);
        });


        grid.getSelectionModel().on('selectionchange', function(selModel, selections,selectedRecord){
            grid.down('#delete').setDisabled(selections.length === 0);
            grid.down('#edit').setDisabled(selections.length === 0);
            if (selections.length) {
                id_rol=selections[0].data['rol_id'];
                getDataStore(id_rol);
                //alert(selections[0].data['rol_id']);
            }

        });
        gridMenu.getSelectionModel().on('selectionchange', function(selModel, selections,selectedRecord){
            gridMenu.down('#delete').setDisabled(selections.length === 0);
            gridMenu.down('#edit').setDisabled(selections.length === 0);
            gridMenu.down('#permisos').setDisabled(selections.length === 0);
            gridMenu.down('#guardar').setDisabled(selections.length === 0);

        });

        /****************************************
         *CODIGO FUENTE PARA EL PANEL PRINCIPAL DE LA VISTA
         *
         */
        var panel=Ext.create('Ext.Panel', {
            renderTo: 'panel_rols',
            frame: true,
            // title: '',
            width: '98%',
            height: 600,
            layout: 'border',
            items: [
                grid,gridMenu ]
        });
        function getForm(){
            var formPanel = Ext.widget({
                xtype: 'form',
                layout: 'form',
                // renderTo:'form',

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
                        id:'data-rol_nombre',
                        fieldLabel: 'Nombre Rol',
                        value:'',
                        //readOnly: true,
                        name: 'data[Rol][rol_nombre]'
                    },{
                        id:'data-rol_descripcion',
                        fieldLabel: 'Descripci\u00f3n',
                        value:'',
                        name: 'data[Rol][rol_descripcion]',
                        xtype: 'textarea'
                    },
                    {////////////////////hidden
                        xtype:'hidden',
                        id: 'data-rol_id',
                        name:'data[Rol][rol_id]'
                    }],

                buttons: [{
                        text: 'Guardar',
                        id:'btnguardar',
                        iconCls:'disk',
                        handler: function() {
                            var form = formPanel.getForm();

                            form.method = 'POST';
                            if (form.isValid()) {

                                form.submit(
                                {
                                    waitTitle:'Espere por favor',
                                    waitMsg: 'Enviando datos...',
                                    url:'../rols/guardar_rol',

                                    success:function(form, action) {

                                        winRol.close();
                                        grid.store.load();
                                        grid.store.on('load', function(store, records, options){
                                            grid.store.each(function(record){
                                                //console.log(record.raw['rol_nombre']+record.raw['rol_id']+'='+ action.result.msg);
                                                if (record.raw['rol_id']==parseInt(action.result.msg)){
                                                    // console.log();
                                                    grid.getSelectionModel().select(record.index);
                                                }


                                            });
                                        });


                                        //
                                        Ext.example.msg('Rol', 'Se guardo el registro satisfactoriamente ');

                                    },
                                    failure: function(form, action) {
                                        winRol.close();
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
                        }
                    },{
                        text: 'Cancelar',
                        id:'btncancelar',
                        iconCls:'cross',
                        handler: function() {
                            winRol.close();
                        }
                    }]
            });
            return formPanel;
        }
        //funciones para interactuar con la base de datos
        function nuevo(){
            // empty record
            var form=getForm();
            form.getForm().reset();
            winRol=Ext.create("Funciones.Ventana",{
                title:'Nuevo Rol',
                width:350,
                height:250,
                items:[form]

            }).show();
        }
        function modificar(){
            var selection = grid.getView().getSelectionModel().getSelection()[0];
            if (selection) {
                var form=getForm();
                winRol=Ext.create("Funciones.Ventana",{
                    title:'Nuevo Rol',
                    width:350,
                    height:250,
                    items:[form]

                }).show();

                Ext.getCmp("data-rol_id").setValue(selection.data['rol_id']);
                Ext.getCmp("data-rol_nombre").setValue(Ext.String.trim(selection.data['rol_nombre']));
                Ext.getCmp("data-rol_descripcion").setValue(Ext.String.trim(selection.data['rol_descripcion']));

            }

        }
        function eliminar(){
            var selection = grid.getView().getSelectionModel().getSelection()[0];
            if (selection) {
                Ext.MessageBox.confirm('Confirmar ', 'Desea eliminar el registro seleccionado ?.\n'+
                    ' El registro se eliminara definitivamente sin opción a recuperarlo', function(btn){
                    if(btn=='yes'){
                        Ext.Ajax.request({
                            url: '../rols/eliminar_rol',
                            params: {
                                rol_id: selection.data['rol_id']
                            },
                            timeout: 3000,
                            method: 'POST',
                            success: function( response ){
                                var info = Ext.decode(response.responseText);
                                if (info.success){
                                    grid.store.remove(selection);
                                    grid.getSelectionModel().select(0);
                                }
                                Ext.example.msg('Eliminar Rol', info.msg);


                            },

                            failure: function(result) {

                                Ext.example.msg('Eliminar Rol', 'Error en la conexion, Intentelo nuevamente.');
                            }
                        });
                    }
                });


            }
        }
        function permisos(){
            var selectionGrid = grid.getView().getSelectionModel().getSelection()[0];
            var selectionGridMenu = gridMenu.getView().getSelectionModel().getSelection()[0];
            var selectionCountGridMenu=gridMenu.getView().getSelectionModel().getCount();
            //console.log(selectionCountGridMenu);
            if (selectionGrid && selectionGridMenu) {

                var form=Ext.create("Funciones.FormPanel",{
                    items:[{
                            id:'data-permiso_r',
                            fieldLabel: 'Ver',
                            xtype: 'checkboxfield',
                            //checked:true,
                            disabled:true,
                            // name:'usuario_activo'
                            name: 'data[Rol][opcion_rol_r]'
                        },{
                            id:'data-permiso_w',
                            fieldLabel: 'Modificar',
                            xtype: 'checkboxfield',
                            //checked:true,
                            // name:'usuario_activo'
                            name: 'data[Rol][opcion_rol_w]'
                        },{
                            id:'data-permiso_c',
                            fieldLabel: 'Crear',
                            xtype: 'checkboxfield',
                            //checked:true,
                            // name:'usuario_activo'
                            name: 'data[Rol][opcion_rol_c]'
                        },{
                            id:'data-permiso_d',
                            fieldLabel: 'Eliminar',
                            xtype: 'checkboxfield',
                            //checked:true,
                            // name:'usuario_activo'
                            name: 'data[Rol][opcion_rol_d]'
                        }],
                    buttons: [{
                            text: 'Guardar',
                            id:'btnguardar',
                            iconCls:'disk',
                            handler: function (){
                                guardarPermisos();
                                winRol.close();
                            }
                        },{
                            text: 'Cancelar',
                            id:'btncancelar',
                            iconCls:'cross',
                            handler: function(){
                                winRol.close();
                            }
                        }]

                });

                winRol=Ext.create("Funciones.Ventana",{
                    title:'Permisos',
                    width:200,
                    height:200,
                    items:[form]

                }).show();
                //cambiamos los permisos de la opcion seleccionada
                if(selectionCountGridMenu==1){//si se elijio una opcion se muestra sus permisos en caso contrario se muestra los permisos en blanco
                    storeOpcionesxRol.clearFilter();
                    storeOpcionesxRol.filterBy(function(record,id){
                        return record.raw['rol_id'] == selectionGrid.data['rol_id'] && record.raw['opcion_id'] == selectionGridMenu.data['opcion_id'] ; //mayores a 30 años
                    });

                    storeOpcionesxRol.each(function(record){
                        //console.log(record);
                        Ext.getCmp("data-permiso_r").setValue(record.raw['opcion_rol_r']);
                        Ext.getCmp("data-permiso_w").setValue(record.raw['opcion_rol_w']);
                        Ext.getCmp("data-permiso_c").setValue(record.raw['opcion_rol_c']);
                        Ext.getCmp("data-permiso_d").setValue(record.raw['opcion_rol_d']);
                    });
                }
            }else{
                Ext.MessageBox.show({
                    title: 'Permisos',
                    msg: 'Debe seleccionar el Rol y la Opci\u00f3n que desea modificar permisos',
                    buttons: Ext.MessageBox.OK,
                    // activeItem :0,
                    animEl: 'mb9',
                    icon: Ext.MessageBox.INFO
                });
            }

            ///Ext.example.msg('permisos', 'Asignacion de permisos');
        }
        //funcion para almacenar los permisos
        function guardarPermisos(){
            var selectedKeys=[];
            var r,w,c,d;
            r= Ext.getCmp("data-permiso_r").getValue();
            w= Ext.getCmp("data-permiso_w").getValue();
            c= Ext.getCmp("data-permiso_c").getValue();
            d= Ext.getCmp("data-permiso_d").getValue();
            selectedKeys=obtenerSeleccionados();
            //console.log(selectedKeys);
            if(selectedKeys.length>0){
                Ext.Ajax.request({
                    url: '../opciones/guardar_permisos/'+selectedKeys,
                    params: {
                        rol_id:id_rol,
                        permiso_r:r,
                        permiso_w:w,
                        permiso_c:c,
                        permiso_d:d
                    },
                    timeout: 3000,
                    method: 'POST',
                    success: function( response ){
                        var info = Ext.decode(response.responseText);
                        if (info.success){
                            storeOpcionesxRol.load();
                            //grid.store.remove(selection);
                        }
                        Ext.example.msg('Guardar Permisos', info.msg);
                    },

                    failure: function(result) {

                        Ext.example.msg('Guardar Permisos', 'Error en la conexion, Intentelo nuevamente.');
                    }
                });
            }else{
                Ext.Msg.alert('Error', 'Debe seleccionar por lo menos una opcion para asignar los permisos');
            }

        }
        var formNuevaOpcion;
        function nuevaOpcion(editar){
            Ext.define('comboModel', {
                extend: 'Ext.data.Model',
                fields: [
                    {name: 'id', type: 'string'},
                    {name: 'nombre_icon',  type: 'string'},
                    {name: 'archivo_icon',  type: 'string'},
                    {name: 'dir_icon',  type: 'string'}
                ]
            });

            var storeCombo = new Ext.data.Store({
                model: 'comboModel',
                pageSize:5,
                proxy: {
                    type: 'ajax',
                    url: '../opciones/geticons',
                    reader: {
                        type: 'json',
                        root: 'icons',
                        totalProperty: 'total'
                    }
                },
                autoLoad: false // set autoloading to false
            });
            Ext.define('comboPadre', {
                extend: 'Ext.data.Model',
                fields: [
                    {name: 'opcion_id', type: 'int'},
                    {name: 'opcion_nombre',  type: 'string'}]

            });

            var storePadre = new Ext.data.Store({
                model: 'comboPadre',

                proxy: {
                    type: 'ajax',
                    url: '../opciones/getopcions/true',
                    reader: {
                        type: 'json',
                        root: 'menus',
                        totalProperty: 'total'
                    }
                },
                autoLoad: false // set autoloading to false
            });

            formNuevaOpcion=Ext.create("Funciones.FormPanel",{
                items:[{
                        xtype:             'combo',
                        name:'data[Opcione][opcion_padre]',
                        fieldLabel:       'Nodo Padre',
                        mode:           'remote',
                        triggerAction:     'all',
                        store:storePadre,
                        valueField:       'opcion_id',
                        displayField:   'opcion_nombre',                       
                        typeAhead:true,
                        resizable:true,
                        emptyText:'Seleccione Nodo Padre....',
                        lazyRender:true,
                        minChars:1,
                        id:'data-opcion_padre',
                        allowBlank: false 
                    },{
                        fieldLabel: 'Nombre',
                        name: 'data[Opcione][opcion_nombre]',
                        allowBlank: false,
                        id:'data-opcion_nombre'
                    },{
                        fieldLabel: 'Url',
                        name: 'data[Opcione][opcion_url]',
                        value:'../[controlador]/[vista]',
                        allowBlank: true,
                        id:'data-opcion_url'
                    },{
                        xtype:             'combo',
                        name:'data[Opcione][opcion_icon]',
                        fieldLabel:       'Icono',
                        mode:           'remote',
                        triggerAction:     'all',
                        store:storeCombo,
                        valueField:       'nombre_icon',
                        displayField:   'nombre_icon',
                        //forceSelection:true,
                        typeAhead:true,
                        pageSize:5,
                        selectOnFocus: true,
                        //width: 570,
                        resizable:true,
                        emptyText:'Seleccione Icono....',
                        id:'data-opcion_icon',
                        lazyRender:true,
                        minChars:1,
                        allowBlank: true,
                        // renderer:renderUsuario,

                        itemSelector: 'div.search-item',

                        listConfig: {
                            loadingText: 'Buscando...',
                            emptyText: 'No existe coincidencias.',

                            // Custom rendering template for each item
                            getInnerTpl: function() {
                                return '<div class="search-item"><h3>{nombre_icon}</h3>' +
                                    '<span><img src={dir_icon} /></span> {archivo_icon}</div>';
                            }
                        }
                    },{
                        id:'data-leaf',
                        fieldLabel: 'Nodo Hijo',
                        xtype: 'checkboxfield',
                        checked:true,
                        // name:'usuario_activo'
                        name: 'data[Opcione][leaf]'
                    },{
                        fieldLabel: 'Descripci\u00f3n',
                        name: 'data[Opcione][opcion_descripcion]',
                        xtype: 'textarea',
                        id:'data-opcion_descripcion'
                    },{

                        name: 'data[Opcione][opcion_id]',
                        xtype: 'hidden',
                        id:'data-opcion_id'
                    }],
                buttons: [{
                        text: 'Guardar',
                        id:'btnguardar',
                        iconCls:'disk',
                        handler: function (){
                            guardarNuevaOpcion();
                            winRol.close();
                        }
                    },{
                        text: 'Cancelar',
                        id:'btncancelar',
                        iconCls:'cross',
                        handler: function(){
                            winRol.close();
                        }
                    }]
            });

            winRol=Ext.create("Funciones.Ventana",{
                title:'Nueva Opci\u00f3n',
                width:450,
                height:350,
                items:[formNuevaOpcion]

            }).show();
            if (editar){
                var selection = gridMenu.getView().getSelectionModel().getSelection()[0];
                if (selection) {
                    Ext.getCmp("data-opcion_id").setValue(selection.data['opcion_id']);
                    Ext.getCmp("data-opcion_nombre").setValue(selection.data['opcion_nombre']);
                    Ext.getCmp("data-opcion_padre").setValue(selection.data['opcion_padre']);
                    Ext.getCmp("data-opcion_url").setValue(selection.data['opcion_url']);
                    Ext.getCmp("data-opcion_icon").setValue(selection.data['opcion_icon']);
                    Ext.getCmp("data-leaf").setValue(selection.data['leaf']);
                    Ext.getCmp("data-opcion_descripcion").setValue(selection.data['opcion_descripcion']);
                }
            }
        }
        function guardarNuevaOpcion(){
            var form = formNuevaOpcion.getForm();

            form.method = 'POST';
            if (form.isValid()) {

                form.submit(
                {
                    waitTitle:'Espere por favor',
                    waitMsg: 'Enviando datos...',
                    url:'../opciones/guardar_opcion',
                    success:function(form, action) {
                        winRol.close();
                        gridMenu.store.load();
                        gridMenu.store.on('load', function(store, records, options){
                            gridMenu.store.each(function(record){
                                if (record.raw['opcion_id']==parseInt(action.result.msg)){
                                    gridMenu.getSelectionModel().select(record.index);
                                }
                            });
                        });
                        Ext.example.msg('Opci\u00f3n', 'Se guardo el registro satisfactoriamente ');

                    },
                    failure: function(form, action) {
                        winRol.close();
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
        }
        function eliminarOpcion(){
            var selection = gridMenu.getView().getSelectionModel().getSelection();
            var selectionCount=gridMenu.getView().getSelectionModel().getCount();
            var texto;
            var selectedKeys;
            selectedKeys=obtenerSeleccionados();
            if (selection) {
                if(selectionCount==1){
                    texto='el registro seleccionado';
                }else{
                    selectedKeys=obtenerSeleccionados();
                    texto='los registros seleccionados'
                }
                Ext.MessageBox.confirm('Confirmar ', 'Desea eliminar '+texto+'?.\n'+
                    ' Se eliminar\u00e1 definitivamente sin opci\u00f3n a recuperarlo', function(btn){
                    if(btn=='yes'){
                        Ext.Ajax.request({
                            url: '../opciones/eliminar_opcion/'+selectedKeys,
                            params: {                                
                                rol_id:id_rol
                            },
                            timeout: 3000,
                            method: 'POST',
                            success: function( response ){
                                var info = Ext.decode(response.responseText);
                                if (info.success){                                   
                                    gridMenu.store.load();

                                }
                                Ext.example.msg('Eliminar Opci\u00f3n', info.msg);
                            },
                            failure: function(result) {
                                Ext.example.msg('Eliminar Opci\u00f3n', 'Error en la conexi\u00f3n, Intentelo nuevamente.');
                            }
                        });
                    }
                });


            }
        }
        function getDataStore(filter){

            selectedKeysasig=null;
            selectedKeysasig=[];
            smm.deselectAll();
            // alert(storeOpcionesxRol.count());
            storeOpcionesxRol.clearFilter();
            storeOpcionesxRol.filterBy(function(record,id){
                return record.raw['rol_id'] == filter; //mayores a 30 años
            }); 

            storeOpcionesxRol.each(function(record){
                //console.log(record);

                marcarOpcion(record.raw['opcion_id']);
                selectedKeysasig.push(record.raw['opcion_id']);



            });
            selectedKeysasig.sort();
        }
        function marcarOpcion(val){

            gridMenu.store.each(function(record,row) {

                if(record.get('opcion_id')==val){

                    smm.select(row,true,false);
                }
            });
        }

        function obtenerSeleccionados() {

            var selectedKeys = [];
            var padres="";
            var rec=smm.getSelection();
            //console.log(rec);
            for(var i=0;i<smm.getCount();i++){
                selectedKeys.push(rec[i].data['opcion_id']);

                if(padres==""){
                    padres=padres + rec[i].data['padre'];
                    selectedKeys.push(rec[i].data['opcion_padre']);
                }else{
                    if(padres.indexOf(rec[i].data['padre'], 0)==-1){
                        padres=padres +":"+ rec[i].data['padre'];
                        selectedKeys.push(rec[i].data['opcion_padre']);

                    }

                }
            }


            selectedKeys.sort();
            return selectedKeys;
            /*var win = new Ext.Window({
                title: 'Seleccionados'
                , closable: true
                , resizable: false
                , html: 'Los seleccionados son:<br>'+selectedKeys
                , width: 400
                , height: 200
            });
            win.show();*/
        };

    });



</script>
<div id="panel_rols"></div>
