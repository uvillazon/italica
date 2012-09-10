Ext.define("App.Principal.Vistas.TreePanel", {
    extend: "Ext.tree.Panel",
    alias: "widget.TreePanel",
    id: 'tree-panel',
    useArrows: true,
    region:'north',
    split: true,
    height: '100%',
    minSize: 150,
    rootVisible: false,
    tabPanel:'',
    autoScroll: true,
    store: Ext.create("App.Principal.Stores.StoreMenu"),
    pathNodo:'',
    viewConfig: {
        plugins: {
            ptype: 'treeviewdragdrop'
        }
    },
    initComponent: function() {
        var parentId;// id del nodo padre
        var parentText;// texto del nodo seleccionado
     
        var id;//id del nodo seleccionado
        var contextMenu = new Ext.menu.Menu({
            items: [{
                text: 'Recargar',
                iconCls: 'arrow_refresh_small',
                handler: this.recargarStore
            },{
                text: 'Expandir',
                iconCls: 'bullet_arrow_down',
                handler: this.expandir
            },{
                text: 'Contraer Todo',
                iconCls: 'bullet_arrow_top',
                handler: this.contraerTodo
            }]
        });
        this.on('itemcontextmenu', function(view,rec, node, index, e) {
            //console.log(rec);
            parentId=rec.get('parentId');
            parentText=rec.get('text');
            id=rec.get('id');
            this.pathNodo=rec.getPath(); 
            e.stopEvent();
            contextMenu.showAt(e.getXY());
            return false;
        });

        // Assign the changeLayout function to be called on tree node click.
        this.on('itemclick', function(node, rec, item, index, e) {
            //console.log(rec);
            if (rec.get('leaf') && rec.get('parentId')!='root') {
                this.tabPanel.addTab(rec.get('text'),rec.get('icontab'),rec.get('url'),rec.get('id'));
            }

        });
        this.callParent(arguments);
    },
    recargarStore:function(item,e){
        var treePanel = Ext.getCmp("tree-panel");
        treePanel.store.load();
    },
    expandir:function(item,e){
        var treePanel = Ext.getCmp("tree-panel");
        treePanel.expandPath(treePanel.pathNodo);
    },
    contraerTodo:function(item,e){
        var treePanel = Ext.getCmp("tree-panel");
        treePanel.collapseAll();
    }
});

