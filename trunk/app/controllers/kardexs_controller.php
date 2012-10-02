<?php

class KardexsController extends AppController {

    var $helpers = array('Html', 'Form', 'Javascript');
    var $components = array('RequestHandler');

    function test() {
        $this->layout = 'ajax';
        echo "<pre>";
        echo print_r($this->Kardex->find('all'));
        echo "</pre>";
    }
    function get_kardex(){
        Configure::write('debug', '0');
        //$consulta=$this->Usuario->find('all');
        if(isset($_REQUEST['start']))
        $start=$_REQUEST['start'];
        else
        $start=0;
        if(isset($_REQUEST['limit']))
        $limit=$_REQUEST['limit'];
        else
        $limit=10000;

        if(isset($_REQUEST['producto_id']))
        $filtro=" AND producto_id=".$_REQUEST['producto_id'];
        else
        $filtro='';

        $conquery = "SELECT k.*
                    FROM kardexes k
                    WHERE 0=0 $filtro
                    LIMIT $limit OFFSET $start
                ";

        $consulta = $this->Kardex->query($conquery);
        $cadena = Set::extract($consulta, '{n}.0');
        $count = $this->Kardex->find('count');
        //  echo print_r($cadena);
        $this->set('total', $count);
        $this->set('datos', $cadena);
        $this->render('respuestas/get_kardex');
    }



}

?>