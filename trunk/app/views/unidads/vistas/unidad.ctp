<script>

    Ext.Loader.setConfig({enabled: true});
    Ext.Loader.setPath('App', '<?php echo $html->url('/app/webroot', true); ?>/js/App');

    Ext.onReady(function() {
        Ext.QuickTips.init();
       var gridUnidades = Ext.create("App.Conf.Unidades.Vistas.gridUnidades",{
            width:500,
            height:400,
            quitarPermisoW:<?php echo $permisos["w"]?>,
            quitarPermisoC:<?php echo $permisos["c"]?>,
            quitarPermisoD:<?php echo $permisos["d"]?>
           
        });
        gridUnidades.render('unidad');
      
     

    });

</script>
<center><div id="unidad"></div></center>