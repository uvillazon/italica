<script>

    Ext.Loader.setConfig({enabled: true});
    Ext.Loader.setPath('App', '<?php echo $html->url('/app/webroot', true); ?>/js/App');

    Ext.onReady(function() {
        Ext.QuickTips.init();

          var panel = Ext.create("App.Procesos.Venta.Vistas.PanelPrincipalCambio");
        panel.render('panelPrincipalCambio');


    });

</script>
<center><div id="panelPrincipalCambio"></div></center>