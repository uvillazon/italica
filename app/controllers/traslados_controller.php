<?php
App::import('Controller', 'rols');
class TrasladosController extends AppController {

    var $helpers = array('Html', 'Form', 'Javascript');
    var $components = array('RequestHandler');

    function test() {
        $this->layout = 'ajax';
        echo "<pre>";
        echo print_r($this->Categoria->find('all'));
        echo "</pre>";
    }
      //formulario para la autenticacion
    function traslado() {
        Configure::write('debug', '0');// deshabilitamos el debug de cakephp
        /*$rols = new RolsController();
        $datosSesion=$this->tieneSesion();
        $this->loadModel('Rol');
        $permisos=$rols->verificar_permisos($_REQUEST['opcionId'],$datosSesion['Rol']['rol_id'],$this->Marca);
        //echo print_r($permisos);
        $this->set('permisos',$permisos);*/
        $this->render('vistas/traslado');
    }
    
   
}

?>