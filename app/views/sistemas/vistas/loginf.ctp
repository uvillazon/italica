<script>
    Ext.onReady(function() {
        Ext.QuickTips.init();

        var loginForm = Ext.widget({
            xtype: 'form',
            layout: 'form',

            id: 'loginForm',
            frame: true,

            //bodyPadding: '0 0 0',
            //width: 350,
            fieldDefaults: {
                msgTarget: 'side',
                labelWidth: 75
            },
            defaultType: 'textfield',
            items: [{
                    fieldLabel: 'Usuario',
                    id:'userLoginForm',
                    name: 'data[Sistema][login]',
                    allowBlank:false
                },{
                    fieldLabel: 'Contraseña',
                    id:'passLoginForm',
                    inputType: 'password',
                    name: 'data[Sistema][pass]'
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
            width: 300,
            closable:false,
            height: 160,
            autoScroll: false,
            layout: 'fit',
            plain:true,
            bodyStyle:'padding:5px;',
            buttonAlign:'center',
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
                        // Ext.Msg.alert('Error','Ud. ha sido bloqueado o su ContraseÃ±a incorrecta!!!');
                    }
                }
            );
            }
        }
    });
</script>