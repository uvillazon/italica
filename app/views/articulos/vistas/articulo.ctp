<script>

    Ext.Loader.setConfig({enabled: true});
    Ext.Loader.setPath('App', '<?php echo $html->url('/app/webroot', true); ?>/js/App');

    Ext.onReady(function() {
        Ext.QuickTips.init();
       var grid = Ext.create("App.Conf.Articulos.Vistas.gridArticulos",{
            width:500,
            height:400
           
        });
        grid.render('articulo');
      
     

    });

</script>
<center><div id="articulo"></div></center>