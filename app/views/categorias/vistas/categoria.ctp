<script>

    Ext.Loader.setConfig({enabled: true});
    Ext.Loader.setPath('App', '<?php echo $html->url('/app/webroot', true); ?>/js/App');

    Ext.onReady(function() {
        Ext.QuickTips.init();
       var grid = Ext.create("App.Conf.Categorias.Vistas.gridCategorias",{
            width:500,
            height:400,
            quitarPermisoW:<?php echo $permisos["w"]?>,
            quitarPermisoC:<?php echo $permisos["c"]?>,
            quitarPermisoD:<?php echo $permisos["d"]?>
           
        });
        grid.render('categoria');
      
     

    });

</script>
<center><div id="categoria"></div></center>