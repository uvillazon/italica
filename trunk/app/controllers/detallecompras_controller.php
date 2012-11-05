<?php

App::import('Controller', 'rols');

class DetallecomprasController extends AppController {

    var $helpers = array('Html', 'Form', 'Javascript');
    var $components = array('RequestHandler');

    function test() {
        $this->layout = 'ajax';
        echo "<pre>";
        echo print_r($this->Detallecompra->find('all'));
        echo "</pre>";
    }


}

?>