
<html>
<head>
    <title>.::: Sistema Inventarios Italica:::.</title>
<meta http-equiv="Content-Type" content="text/html; charset=iso-8859-1" />
<!-- ExtJS -->
<link rel="stylesheet" type="text/css" href="/libs/ext41/resources/css/ext-all.css" />
<link rel="stylesheet" type="text/css" href="/libs/ext41/examples/shared/example.css" />
<link rel="stylesheet" type="text/css" href="<?php echo $html->url('/app/webroot/css/', true); ?>icons.css" />
<script type="text/javascript" src="/libs/ext41/ext-all.js"></script>
<script type="text/javascript" src="/libs/ext41/examples/shared/examples.js"></script>
<script type="text/javascript" src="/libs/ext41/locale/ext-lang-es.js"></script>

<script type="text/javascript" src="/libs/js/funciones.js"></script>
<!-- page specific -->
<style type="text/css">
    /* style rows on mouseover */
		
    /*.x-grid-row .x-grid-cell-inner {
    		white-space: normal;
    }*/
    .x-grid-row-over .x-grid-cell-inner {
    		font-weight: bold;
    		/*white-space: normal;*/
    	}
    
     .search-item{
        border:1px solid #fff;
        padding:3px;
        background-position:rightright bottombottom;
        background-repeat:no-repeat;
    }
    .desc{
        padding-right:10px;
        font-size:12px !important;
    }
    .name{
        font-size:14px !important;
        font-weight: bold;
        color:#000022;
    }

</style>

</head>
    <body background="<?php echo $html->url('/app/webroot', true); ?>/img/fondoTrans.png">
        <div id="txtAPP">
            <?php  echo $content_for_layout; ?>
        </div>
    </body>
</html>