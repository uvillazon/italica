<?php
class DetalleventasController extends AppController {

    var $helpers = array('Html', 'Form', 'Javascript');
    var $components = array('RequestHandler');

    function test() {
        $this->layout = 'ajax';
        echo "<pre>";
        echo print_r($this->Detalleventa->find('all'));
        echo "</pre>";
    }


}

?>