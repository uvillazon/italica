Ext.define("App.Principal.Vistas.TabsPanelPrincipal", {
    extend: "Ext.tab.Tab",
    alias: "widget.TabPanelPrincipal",
    id: 'TabsPanelPrincipal',
    region: 'center',
    layout: 'card',
    margins: '0 0 0 0',
    resizeTabs: true,
    enableTabScroll: true,
    width: '100%',
    height: 250,
    defaults: {
        autoScroll: true
    },
   
    initComponent: function() {
        this. items = [{
            title: 'Bienvenido',
            //iconCls: 'tabs',
            //html: 'Tab Body<br/><br/>',
            closable: false
        }];
        this.callParent(arguments);
    },
    addTab:function  (titleTab,icon,url,id) {
         var tabs = Ext.getCmp("TabsPanelPrincipal");
            var open=tabs.getChildByElement(url);
            if (open==null){
                tabs.add({
                    id:url,
                    closable: true,
                    //html: '<div id="topic-grid"></div>',
                    iconCls: icon,
                    title: titleTab,
                    loader: {
                        url: url,
                        //autoLoad:true,
                        contentType: 'html',
                        loadMask: true,
                        scripts:true,
                        params: {
                            opcionId: id
                        }
                    },
                    listeners: {
                        activate: function(tab) {
                            tab.loader.load();
                        }
                    }

                }).show();

            }
            tabs.setActiveTab(url);
        }

});