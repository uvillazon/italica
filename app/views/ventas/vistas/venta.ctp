<script>

    Ext.Loader.setConfig({enabled: true});
    Ext.Loader.setPath('App', '<?php echo $html->url('/app/webroot', true); ?>/js/App');

    Ext.onReady(function() {
        Ext.QuickTips.init();

          var panel = Ext.create("App.Procesos.Venta.Vistas.PanelPrincipalOrden");
        panel.render('panelPrincipalVenta');


    });

</script>
<center><div id="panelPrincipalVenta"></div></center>