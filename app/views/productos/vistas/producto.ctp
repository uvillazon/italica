<script>

    Ext.Loader.setConfig({enabled: true});
    Ext.Loader.setPath('App', '<?php echo $html->url('/app/webroot', true); ?>/js/App');

    Ext.onReady(function() {
        Ext.QuickTips.init();
       var grid = Ext.create("App.Conf.Productos.Vistas.PanelPrincipalProducto",{
            width:'100%',
            height:'100%',
            minHeight:400,
            quitarPermisoW:<?php echo $permisos["w"]?>,
            quitarPermisoC:<?php echo $permisos["c"]?>,
            quitarPermisoD:<?php echo $permisos["d"]?>
           
        });
        grid.render('GridProductos');
      
     

    });

</script>
<center><div id="GridProductos"></div></center>