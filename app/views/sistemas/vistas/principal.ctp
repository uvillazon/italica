<link rel="stylesheet" type="text/css" href="/libs/ext41/examples/layout-browser/layout-browser.css">
<script>
    Ext.Loader.setConfig({enabled: true});

    Ext.Loader.setPath('Ext.ux', '/libs/ext41/examples/ux');

    Ext.require([
        'Ext.tip.QuickTipManager',
        'Ext.container.Viewport',
        'Ext.layout.*',
        'Ext.form.Panel',
        'Ext.form.Label',
        'Ext.grid.*',
        'Ext.data.*',
        'Ext.tree.*',
        'Ext.window.*',
        'Ext.tip.*',
        'Ext.layout.container.Border',
        'Ext.selection.*',
        'Ext.tab.Panel',
        'Ext.ux.layout.Center',
        'Ext.tab.*',
        'Ext.ux.TabCloseMenu'
    ]);

    Ext.onReady(function(){

        Ext.tip.QuickTipManager.init();


        var index=0;

        var tabs = Ext.widget('tabpanel', {
            id: 'content-panel',
            region: 'center', // this is what makes this panel into a region
            // within the containing layout
            layout: 'card',
            margins: '0 0 0 0',
            resizeTabs: true,
            enableTabScroll: true,
            width: '100%',
            height: 250,
            defaults: {
                autoScroll: true
            },
            items: [{
                    title: 'Bienvenido',
                    //iconCls: 'tabs',
                    // html: 'Tab Body<br/><br/>',
                    closable: false
                }]
        });
        function addTab (titleTab,icon,url,id) {
            ++index;
            //alert(icon);
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

        Ext.define('Menu', { 
            extend: 'Ext.data.Model', 
            fields: ['id', 'text', 'leaf', 'parentId','icon','icontab','url','r','w','c','d']
        });

        var store = Ext.create('Ext.data.TreeStore', { 
            model: 'Menu', 
            idProperty: 'id', 
            autoLoad: true, 
            proxy: { 
                type: 'ajax', 
                url: 'getmenu', 

                reader: { 
                    type: 'json', 
                    root: 'modulos' 
                } 
            } 
        }); 
        var treePanel = Ext.create('Ext.tree.Panel', {

            id: 'tree-panel',
            useArrows: true,
            region:'north',
            split: true,
            height: '100%',
            minSize: 150,
            rootVisible: false,
            autoScroll: true,
            store: store,
            viewConfig: {
                plugins: {
                    ptype: 'treeviewdragdrop'
                }
            }
        }); 



        // Assign the changeLayout function to be called on tree node click.
        treePanel.on('itemclick', function(node, rec, item, index, e) {
            //console.log(rec);
            if (rec.get('leaf') && rec.get('parentId')!='root') {

                addTab(rec.get('text'),rec.get('icontab'),rec.get('url'),rec.get('id'));
            }

        });
        var parentId;// id del nodo padre
        var parentText;// texto del nodo seleccionado
        var pathNodo;// nodo seleccionado
        var id;//id del nodo seleccionado
        var contextMenu = new Ext.menu.Menu({
            items: [{
                    text: 'Recargar',
                    iconCls: 'arrow_refresh_small',
                    handler: function(item,e){
                        treePanel.store.load();  
                    }
                },{
                    text: 'Expandir',
                    iconCls: 'bullet_arrow_down',
                    handler: function(item,e){
                        treePanel.expandPath(pathNodo);  
                    }
                },{
                    text: 'Contraer Todo',
                    iconCls: 'bullet_arrow_top',
                    handler: function(item,e){
                        treePanel.collapseAll();  
                    }
                }]
        });
        treePanel.on('itemcontextmenu', function(view,rec, node, index, e) {
            //console.log(rec);
            parentId=rec.get('parentId');
            parentText=rec.get('text');
            id=rec.get('id');
            pathNodo=rec.getPath();
            e.stopEvent();
            contextMenu.showAt(e.getXY());
            return false;
        });

        var norte=new Ext.Panel({
            region: 'north',
            border: true,
            margins: '0 0 1 0',
            split:false,
            bbar:[
                {xtype:'label',
                    autoHeight:true,
                    iconCls:'user_suit',
                    //text:nombre
                    html:'<div align="right"><b>Usuario: </b><font color="black"><b><?php echo $nombres ?></b></font></div>'
                },'->',
                {
                    text: 'Cerrar sesión',
                    iconCls: 'logout',
                    toolTip:'Cerrar sesión',

                    handler: function() {
                        window.location = 'logout';
                    }
                }
            ]
        });

        // Finally, build the main layout once all the pieces are ready. This is
        // also a good
        // example of putting together a full-screen BorderLayout within a Viewport.
        Ext.create('Ext.Viewport', {
            layout: 'border',
            title: 'Sistema',
            items: [{
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
                tabs,norte
            ],
            renderTo: Ext.getBody()
        });
    });

</script>
