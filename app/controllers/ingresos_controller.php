<?php

class IngresosController extends AppController {

    var $helpers = array('Html', 'Form', 'Javascript');
    var $components = array('RequestHandler');

    function test() {
        $this->layout = 'ajax';
         echo "<pre>";
          echo print_r($this->Ingreso->find('all'));
          echo "</pre>"; 
    }


}