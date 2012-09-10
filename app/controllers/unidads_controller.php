<?php
App::import('Controller', 'rols');
class UnidadsController extends AppController {

    var $helpers = array('Html', 'Form', 'Javascript');
    var $components = array('RequestHandler');

    function test() {
        //Configure::write('debug', '0');// deshabilitamos el debug de cakephp
        //$this->render('vistas/loginf');
        $this->layout = 'ajax';
        echo "<pre>";
        echo print_r($this->Unidad->find('all'));
        echo "</pre>";
    }
    // formmulario para agregar/editar/eliminar unidades
    function unidad(){
        Configure::write('debug', '0');// deshabilitamos el debug de cakephp
        $rols = new RolsController();
        $datosSesion=$this->tieneSesion();
        $this->loadModel('Rol');
        $permisos=$rols->verificar_permisos($_REQUEST['opcionId'],$datosSesion['Rol']['rol_id'],$this->Unidad);
        //echo print_r($permisos);
        $this->set('permisos',$permisos);
        $this->render('vistas/unidad');
    }
    //funcion que retorna todas las unidades registradas en BD

    function get_unidades(){
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

        $conquery = "SELECT u.unidad_id,u.unidad_descripcion,u.unidad_sigla
                    FROM unidads u
                    ORDER BY u.unidad_sigla
                    LIMIT $limit OFFSET $start
                ";
        $consulta = $this->Unidad->query($conquery);
        $cadena = Set::extract($consulta, '{n}.0');
        /*echo "<pre>";
         echo print_r($consulta);
          echo "<pre>";*/
        $count = $this->Unidad->find('count');
        $this->set('total', $count);
        $this->set('datos', $cadena);
        $this->render('respuestas/get_unidades');
    }
    function guardar_unidad(){
        Configure::write('debug', '0');
        //$info = array('success' => true,'msg'=> 'Se almacen&oacute; el nuevo registro correctamente');

        $datosSesion=$this->Session->read('Usuario');
        if($datosSesion){
            if($this->data) {
                if($this->Unidad->Save($this->data)) {
                    if($this->Unidad->getInsertId()!=''){
                       $unidad_id=$this->Unidad->getInsertId(); 
                       $msg="Se almacen&oacute; el nuevo registro correctamente";
                    }
                    else{
                         $unidad_id=$this->data['Unidad']['unidad_id'];
                         $msg="El registro fu&eacute; modificado correctamente";
                    } 
                    $info = array('success' => true,'msg'=>$msg,'id'=>$unidad_id );
                    $this->log("Unidad $unidad_id almacenada en, Unidads por->".$datosSesion['Usuario']['login'], LOG_DEBUG);
                }else {
                    $info = array('success' => false,'msg'=> "No se pudo almacenar. Intentelo nuevamente");
                    $this->log("no se pudo almacenar la unidad nueva en, Unidades por->".$datosSesion['Usuario']['login']);
                }
            }else {
                $info = array('success' => false,'msg'=> "Error en el envio de datos");
                $this->log("Error en el envio de datos, Unidads por->".$datosSesion['Usuario']['login']);
            }
        }else{
            $info = array('success' => false,'msg'=> "No tiene una sesi&oacuten activa",'redir'=>true);
            $this->log("Error No tiene una sesi&oacuten activa, Unidads");
        }

        $this->set('info',$info);
        $this->render('respuestas/guardar_unidad');
    }
    function eliminar_unidad(){
        Configure::write('debug', '0');
        if($_REQUEST['id']) {
            $id=$_REQUEST['id'];
            if ($this->Unidad->delete($id)) {
                $info = array('success' => true,'msg'=>'El registro seleccionado fue eliminado correctamente');
            }else {
                $info = array('success' => false,'msg'=>'No se pudo eliminar el registro seleccionado');
            }
        }
        $this->set('info',$info);
        $this->render('respuestas/eliminar_unidad');
    }


}

?>