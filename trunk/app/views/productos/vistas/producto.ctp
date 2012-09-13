<script>

    Ext.Loader.setConfig({enabled: true});
    Ext.Loader.setPath('App', '<?php echo $html->url('/app/webroot', true); ?>/js/App');

    Ext.onReady(function() {
        Ext.QuickTips.init();
       var panel = Ext.create("App.Conf.Productos.Vistas.PanelPrincipalProducto",{
           
            minHeight:400,
            quitarPermisoW:<?php echo $permisos["w"]?>,
            quitarPermisoC:<?php echo $permisos["c"]?>,
            quitarPermisoD:<?php echo $permisos["d"]?>
           
        });
        panel.render('PanelProductos');
      
     

    });

</script>
<div id="PanelProductos"></div>