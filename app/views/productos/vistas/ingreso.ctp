<script>

    Ext.Loader.setConfig({enabled: true});
    Ext.Loader.setPath('App', '<?php echo $html->url('/app/webroot', true); ?>/js/App');

    Ext.onReady(function() {
        Ext.QuickTips.init();
     
          var panel = Ext.create("App.Procesos.Producto.Vistas.PanelPrincipalIngreso");
        panel.render('panelPrincipalIngreso');
     

    });

</script>
<center><div id="panelPrincipalIngreso"></div></center>