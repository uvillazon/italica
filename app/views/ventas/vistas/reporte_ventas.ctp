
<script>

    Ext.Loader.setConfig({enabled: true});
    Ext.Loader.setPath('App', '<?php echo $html->url('/app/webroot', true); ?>/js/App');

    Ext.onReady(function() {
        Ext.QuickTips.init();

          var panel = Ext.create("App.Reportes.FormReporteVentas");
        panel.render('reporteVentas');


    });

</script>
<center><div id="reporteVentas"></div></center>