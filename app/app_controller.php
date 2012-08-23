<?php

class AppController extends Controller {

    public $helpers = array('javascript', 'html');

    function getRealIP() {
        if (!empty($_SERVER['HTTP_CLIENT_IP']))
            return $_SERVER['HTTP_CLIENT_IP'];

        if (!empty($_SERVER['HTTP_X_FORWARDED_FOR']))
            return $_SERVER['HTTP_X_FORWARDED_FOR'];

        return $_SERVER['REMOTE_ADDR'];
    }

    function orderMultiDimensionalArray($toOrderArray, $field, $inverse = false) {
        $position = array();
        $newRow = array();
        foreach ($toOrderArray as $key => $row) {
            $position[$key] = $row[$field];
            $newRow[$key] = $row;
        }
        if ($inverse) {
            arsort($position);
        } else {
            asort($position);
        }
        $returnArray = array();
        foreach ($position as $key => $pos) {
            $returnArray[] = $newRow[$key];
        }
        return $returnArray;
    }
    //verifica la sesion actual si no existe redirecciona a la pagina de login
     function tieneSesion(){
         $datos = $this->Session->read('Usuario');
        if ($datos!=null) {

        }else {
            $this->redirect("../../italica/sistemas/loginf", null, true);
        }
        return $datos;
    }

}

?>