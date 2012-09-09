<script>
    Ext.onReady(function() {
        Ext.QuickTips.init();
        Ext.define('comboModel', {
            extend: 'Ext.data.Model',
            fields: [
                {name: 'sucursal_id', type: 'int'},
                {name: 'sucursal_nombre',  type: 'string'},
                {name: 'sucursal_dir',  type: 'string'}
            ]
        });
        var storeCombo = new Ext.data.Store({
            model: 'comboModel',
            //pageSize:5,
            proxy: {
                type: 'ajax',
                url: '../sucursals/getsucursals',
                reader: {
                    type: 'json',
                    root: 'datos',
                    totalProperty: 'total'
                }
            },
            autoLoad: true
        });
        var loginForm = Ext.widget({
            xtype: 'form',
            border: false,
            bodyBorder: false,
            bodyStyle: "background-image:url('../app/webroot/img/fondo.jpg')",
            layout: 'absolute',
            id: 'loginForm',

            defaultType: 'textfield',
            width:150,
            items: [{
                    xtype: 'label',
                    style: 'color: #FFF; font-weight: bold; font-size: 14px',
                    text: 'Usuario:',
                    x: 180,
                    y: 30
                },{
                    style: 'font-size: 20px',
                    width:180,
                    x: 180,
                    y: 48,
                    id:'userLoginForm',
                    name: 'data[Sistema][login]',
                    allowBlank:false,
                    listeners:{
                        blur: function (){
                            if(Ext.getCmp("userLoginForm").getValue()!='super'){
                                //Ext.getCmp("userLoginForm").
                            }

                        }
                    }
                },{
                    xtype: 'label',
                    style: 'color: #FFF; font-weight: bold; font-size: 14px',
                    text: 'Contrase\u00f1a:',
                    x: 180,
                    y: 80
                },{

                    style: 'color: #FFF; font-weight: bold; font-size: 14px',
                    id:'passLoginForm',
                    style: 'font-size: 20px',
                    width:180,
                    //width: 80,
                    x: 180,
                    y: 98,
                    inputType: 'password',

                    name: 'data[Sistema][pass]'
                },{
                    xtype: 'label',
                    style: 'color: #FFF; font-weight: bold; font-size: 14px',
                    text: 'Sucursal:',
                    x: 180,
                    y: 130
                },{
                    xtype:             'combo',
                    id:'userSucursal',
                    style: 'font-size: 20px',
                    width:180,
                    x: 180,
                    y: 148,
                    name:'data[Sistema][sucursal_id]',
                    style: 'color: #FFF; font-weight: bold; font-size: 14px',
                    mode:           'remote',
                    store:storeCombo,
                    valueField:       'sucursal_id',
                    displayField:   'sucursal_nombre',
                    emptyText:'Seleccione Sucursal....',
                    allowBlank: false
                }],

            buttons:
                [
                {
                    text: 'Ingresar',
                    iconCls:'lock_open',
                    handler: function() {

                        login();
                    }
                }

            ],listeners: {
                afterRender: function(thisForm, options){
                    this.keyNav = Ext.create('Ext.util.KeyNav', this.el, {
                        enter: login,
                        scope: this
                    });
                }
            }

        });
        var loginWindow = new Ext.Window(
        {
            title: 'Ingreso al sistema',
            iconCls:'key',
            width: 400,
            closable:false,
            height: 310,
            autoScroll: false,
            layout: 'fit',
            plain:true,
            border:false,
            bodyStyle:'padding:5px;',
            buttonAlign:'center',
            bbar: ['->',{
                    xtype: 'tbtext',
                    
                    text:'<b>ITALICA  ©   '+ Ext.Date.format(new Date(),'Y')+'</b>'
                }],
            //modal: true,
            items: [loginForm]
        }
    );

        loginWindow.show();
        Ext.getCmp('userLoginForm').focus("",100);

        function login(){
            if(loginForm.getForm().isValid()) {
                loginForm.getForm().submit(
                {
                    waitTitle:'Espere por favor',
                    waitMsg: 'Verificando datos...',
                    url:'login',

                    success:function(form, action) {
                        //Ext.Msg.alert('Status', 'Login successfully.');
                        loginWindow.close();
                        //pbar1.text = 'Iniciando...';
                        Ext.MessageBox.wait('Ingresando al sistema','Direccionando');
                        self.location='principal';

                    },
                    failure: function(form, action) {
                        // alert(action.result.msg);

                        Ext.MessageBox.show({
                            title: 'Error',
                            msg: action.result.msg,
                            buttons: Ext.MessageBox.OK,
                            // activeItem :0,
                            animEl: 'mb9',
                            fn: function(btn){

                                Ext.getCmp('passLoginForm').setValue("");
                                Ext.getCmp('passLoginForm').focus("",100);
                            },
                            icon: Ext.MessageBox.ERROR
                        });
                        //Ext.Msg.alert('Error',action.result.msg);
                        // Ext.Msg.alert('Error','Ud. ha sido bloqueado o su Contrase√±a incorrecta!!!');
                    }
                }
            );
            }
        }
    });
</script>