Ext.define("App.Principal.Vistas.CabeceraPrincipal", {
    extend: "Ext.Panel",
    alias: "widget.CabeceraPrincipal",
    id: 'CabceraPrincipal',
    region: 'north',
    border: true,
    nombres:'Prueba de nombnres',
    margins: '0 0 1 0',
    split:false,

    initComponent: function() {
        this.bbar=[
        {
            xtype:'label',
            autoHeight:true,
            iconCls:'user_suit',
            // text:'<div align="right"><b>Usuario: </b><font color="black"><b><?php echo $nombres;?></b></font></div>'
            html:'<b>USUARIO: </b>'+this.nombres
        },'->',
        {
            text: 'Cerrar sesión',
            iconCls: 'logout',
            toolTip:'Cerrar sesión',
            handler: function() {
                window.location = 'logout';
            }
        }
        ];
        this.callParent(arguments);
    }

});