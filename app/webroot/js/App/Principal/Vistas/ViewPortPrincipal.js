Ext.define("App.Principal.Vistas.ViewPortPrincipal", {
    extend: "Ext.Viewport",
    alias: "widget.TabPanel",
    layout: 'border',
    title: 'Sistema',
    nombres:'',     
    renderTo: Ext.getBody(),
    initComponent:function(){
        var  norte=Ext.create("App.Principal.Vistas.CabeceraPrincipal",{
            nombres:this.nombres
        });
        var tabs=Ext.create("App.Principal.Vistas.TabsPanelPrincipal");
        var treePanel=Ext.create("App.Principal.Vistas.TreePanel",{
              tabPanel:tabs
         });
        this.items=[{
            xtype: 'box',
            id: 'header',
            region: 'north',
            html: '<h1>ITALICA</h1>',
            height: 30,
            items:[
            {
                text: 'Cerrar sesión',
                iconCls: 'salir',
                toolTip:'Cerrar sesión',
                handler: function() {
                    window.location = 'logout';
                }
            }
            ]
        },{
            title: 'Sistemas',
            layout: 'border',
            id: 'layout-browser',
            region:'west',
            border: false,
            split:true,
            collapsible: true,
            margins: '2 0 5 5',
            width: 275,
            minSize: 100,
            maxSize: 500,
            items: [treePanel]
        },
        tabs,norte];
        this.callParent(arguments);
    }
});
