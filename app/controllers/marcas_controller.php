<?php
App::import('Controller', 'rols');
class MarcasController extends AppController {

    var $helpers = array('Html', 'Form', 'Javascript');
    var $components = array('RequestHandler');

    function test() {
        //$this->layout = 'ajax';
        echo "<pre>";
        echo print_r($this->Marca->find('all'));
        echo "</pre>";
    }
    //formulario para la autenticacion
    function marca() {
        Configure::write('debug', '0');// deshabilitamos el debug de cakephp
        $rols = new RolsController();
        $datosSesion=$this->tieneSesion();
        $this->loadModel('Rol');
        $permisos=$rols->verificar_permisos($_REQUEST['opcionId'],$datosSesion['Rol']['rol_id'],$this->Marca);
        //echo print_r($permisos);
        $this->set('permisos',$permisos);
        $this->render('vistas/marca');
    }
     //funcion que retorna todas las unidades registradas en BD

    function get_marcas(){
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

        $conquery = "SELECT m.marca_id,m.marca_codigo,m.marca_nombre
                    FROM marcas m
                    ORDER BY m.marca_nombre
                    LIMIT $limit OFFSET $start
                ";
        $consulta = $this->Marca->query($conquery);
        $cadena = Set::extract($consulta, '{n}.0');

        $count = $this->Marca->find('count');
        $this->set('total', $count);
        $this->set('datos', $cadena);
        $this->render('respuestas/get_marcas');
    }
    function guardar_marca(){
        Configure::write('debug', '0');
        //$info = array('success' => true,'msg'=> 'Se almacen&oacute; el nuevo registro correctamente');

        $datosSesion=$this->Session->read('Usuario');
        if($datosSesion){
            if($this->data) {
                if($this->Marca->Save($this->data)) {
                    if($this->Marca->getInsertId()!=''){
                       $unidad_id=$this->Marca->getInsertId();
                       $msg="Se almacen&oacute; el nuevo registro correctamente";
                    }
                    else{
                         $unidad_id=$this->data['Marca']['marca_id'];
                         $msg="El registro fu&eacute; modificado correctamente";
                    }
                    $info = array('success' => true,'msg'=>$msg,'id'=>$unidad_id );
                    $this->log("MArca $unidad_id almacenada en, Marcas por->".$datosSesion['Usuario']['login'], LOG_DEBUG);
                }else {
                    $info = array('success' => false,'msg'=> "No se pudo almacenar. Intentelo nuevamente");
                    $this->log("no se pudo almacenar la unidad nueva en, Marcas por->".$datosSesion['Usuario']['login']);
                }
            }else {
                $info = array('success' => false,'msg'=> "Error en el envio de datos");
                $this->log("Error en el envio de datos, MArcas por->".$datosSesion['Usuario']['login']);
            }
        }else{
            $info = array('success' => false,'msg'=> "No tiene una sesi&oacuten activa",'redir'=>true);
            $this->log("Error No tiene una sesi&oacuten activa, Marcas");
        }

        $this->set('info',$info);
        $this->render('respuestas/guardar_marca');
    }
    function eliminar_marca(){
        Configure::write('debug', '0');
        if($_REQUEST['id']) {
            $id=$_REQUEST['id'];
            if ($this->Marca->delete($id)) {
                $info = array('success' => true,'msg'=>'El registro seleccionado fue eliminado correctamente');
            }else {
                $info = array('success' => false,'msg'=>'No se pudo eliminar el registro seleccionado');
            }
        }
        $this->set('info',$info);
        $this->render('respuestas/eliminar_marca');
    }
   
}

?>