<?php

class MovimientosController extends AppController {

    var $helpers = array('Html', 'Form', 'Javascript');
    var $components = array('RequestHandler');

    function test() {
        $this->layout = 'ajax';
        echo "<pre>";
        echo print_r($this->Movimiento->find('all'));
        echo "</pre>";
    }
   


}

?>