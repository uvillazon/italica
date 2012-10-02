<script>

    Ext.Loader.setConfig({enabled: true});
    Ext.Loader.setPath('App', '<?php echo $html->url('/app/webroot', true); ?>/js/App');

    Ext.onReady(function() {
        Ext.QuickTips.init();
       var panel = Ext.create("App.Procesos.Compra.Vistas.PanelPrincipalCompra",{
           
            minHeight:400,
            quitarPermisoW:<?php echo $permisos["w"]?>,
            quitarPermisoC:<?php echo $permisos["c"]?>,
            quitarPermisoD:<?php echo $permisos["d"]?>
           
        });
        panel.render('PanelCompra');
      
     

    });

</script>
<div id="PanelCompra"></div>