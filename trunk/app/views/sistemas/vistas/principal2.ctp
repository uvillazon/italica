<link rel="stylesheet" type="text/css" href="/libs/ext41/examples/layout-browser/layout-browser.css">

<script>
    Ext.Loader.setConfig({enabled: true});
    Ext.Loader.setPath('Ext.ux', '/libs/ext41/examples/ux');
    Ext.Loader.setPath('App', '<?php echo $html->url('/app/webroot', true); ?>/js/App');

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
            Ext.create("App.Principal.Vistas.ViewPortPrincipal",{nombres:'<?php echo $nombres;?>'});
       });
   

</script>
