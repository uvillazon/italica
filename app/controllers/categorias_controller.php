<?php
App::import('Controller', 'rols');
class CategoriasController extends AppController {

    var $helpers = array('Html', 'Form', 'Javascript');
    var $components = array('RequestHandler');

    function test() {
        $this->layout = 'ajax';
        echo "<pre>";
        echo print_r($this->Categoria->find('all'));
        echo "</pre>";
    }
      //formulario para la autenticacion
    function categoria() {
        Configure::write('debug', '0');// deshabilitamos el debug de cakephp
        $rols = new RolsController();
        $datosSesion=$this->tieneSesion();
        $this->loadModel('Rol');
        $permisos=$rols->verificar_permisos($_REQUEST['opcionId'],$datosSesion['Rol']['rol_id'],$this->Marca);
        //echo print_r($permisos);
        $this->set('permisos',$permisos);
        $this->render('vistas/categoria');
    }
     //funcion que retorna todas las unidades registradas en BD

    function get_categorias(){
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

        $conquery = "SELECT c.categoria_id,c.categoria_nombre,c.categoria_descripcion
                    FROM categorias c
                    ORDER BY c.categoria_nombre
                    LIMIT $limit OFFSET $start
                ";
        $consulta = $this->Categoria->query($conquery);
        $cadena = Set::extract($consulta, '{n}.0');

        $count = $this->Categoria->find('count');
        $this->set('total', $count);
        $this->set('datos', $cadena);
        $this->render('respuestas/get_categorias');
    }
    function guardar_categoria(){
        Configure::write('debug', '0');
        //$info = array('success' => true,'msg'=> 'Se almacen&oacute; el nuevo registro correctamente');

        $datosSesion=$this->Session->read('Usuario');
        if($datosSesion){
            if($this->data) {
                if($this->Categoria->Save($this->data)) {
                    if($this->Categoria->getInsertId()!=''){
                       $id=$this->Categoria->getInsertId();
                       $msg="Se almacen&oacute; el nuevo registro correctamente";
                    }
                    else{
                         $id=$this->data['Categoria']['categoria_id'];
                         $msg="El registro fu&eacute; modificado correctamente";
                    }
                    $info = array('success' => true,'msg'=>$msg,'id'=>$id );
                    $this->log("Categoria $unidad_id almacenada en, Categorias por->".$datosSesion['Usuario']['login'], LOG_DEBUG);
                }else {
                    $info = array('success' => false,'msg'=> "No se pudo almacenar. Intentelo nuevamente");
                    $this->log("no se pudo almacenar la Categoria nueva en, Categorias por->".$datosSesion['Usuario']['login']);
                }
            }else {
                $info = array('success' => false,'msg'=> "Error en el envio de datos");
                $this->log("Error en el envio de datos, Categorias por->".$datosSesion['Usuario']['login']);
            }
        }else{
            $info = array('success' => false,'msg'=> "No tiene una sesi&oacuten activa",'redir'=>true);
            $this->log("Error No tiene una sesi&oacuten activa, Categoria");
        }

        $this->set('info',$info);
        $this->render('respuestas/guardar_categoria');
    }
    function eliminar_categoria(){
        Configure::write('debug', '0');
        if($_REQUEST['id']) {
            $id=$_REQUEST['id'];
            if ($this->Categoria->delete($id)) {
                $info = array('success' => true,'msg'=>'El registro seleccionado fue eliminado correctamente');
            }else {
                $info = array('success' => false,'msg'=>'No se pudo eliminar el registro seleccionado');
            }
        }
        $this->set('info',$info);
        $this->render('respuestas/eliminar_categoria');
    }

   
}

?>