<?php

class SucursalsController extends AppController {

    var $helpers = array('Html', 'Form', 'Javascript');
    var $components = array('RequestHandler');

    function test() {
        $this->layout = 'ajax';
        echo "<pre>";
        echo print_r($this->Sucursal->find('all'));
        echo "</pre>";
    }
  function getsucursals(){
       //Configure::write('debug', '0');
        $cadena1 = array(); //this will hold our data from the database.
        if (isset($_REQUEST['start']))
            $start=$_REQUEST['start'];
        else
            $start=0;
        if (isset($_REQUEST['limit']))
            $limit=$_REQUEST['limit'];
        else
            $limit=1000;
        if (isset($_REQUEST['query']))
            $filter=" AND sucursal_nombre ILIKE '%".trim($_REQUEST['query'])."%'";
        else
            $filter="";


        $conquery = "SELECT s.sucursal_id,s.sucursal_nombre, s.sucursal_dir
                    FROM sucursals s WHERE 0=0 $filter ORDER BY s.sucursal_nombre LIMIT $limit OFFSET $start;";
        $consulta1 = $this->Sucursal->query($conquery);
        $cadena1 = Set::extract($consulta1, '{n}.0');
        $count = count($cadena1);
        $this->set('total', $count); //send total to the view
        $this->set('datos', $cadena1); //send users to the view
        $this->render('eventos/getsucursals');
  }

}

?>