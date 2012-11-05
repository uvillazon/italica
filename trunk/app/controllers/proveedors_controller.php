<?php
App::import('Controller', 'rols');
class ProveedorsController extends AppController {

    var $helpers = array('Html', 'Form', 'Javascript');
    var $components = array('RequestHandler');

    function test() {
        $this->layout = 'ajax';
        echo "<pre>";
        echo print_r($this->Proveedor->find('all'));
        echo "</pre>";
    }
    //formulario para la autenticacion
    function proveedor() {
        Configure::write('debug', '0');// deshabilitamos el debug de cakephp
        $rols = new RolsController();
        $datosSesion=$this->tieneSesion();
        $this->loadModel('Rol');
        $permisos=$rols->verificar_permisos($_REQUEST['opcionId'],$datosSesion['Rol']['rol_id'],$this->Proveedor);
        //echo print_r($permisos);
        $this->set('permisos',$permisos);
        $this->render('vistas/proveedor');
    }
    //funcion que retorna todas las unidades registradas en BD

    function get_proveedores(){
        Configure::write('debug', '0');
        //$consulta=$this->Usuario->find('all');
        $this->layout = 'ajax';
        if(isset($_REQUEST['start']))
        $start=$_REQUEST['start'];
        else
        $start=0;
        if(isset($_REQUEST['limit']))
        $limit=$_REQUEST['limit'];
        else
        $limit=10000;

       $conquery = "SELECT *
                    FROM proveedors p
                     WHERE p.proveedor_id > 0
                    ORDER BY p.proveedor_razon_social
                   
                    LIMIT $limit OFFSET $start
                ";
        $consulta = $this->Proveedor->query($conquery);
        $cadena = Set::extract($consulta, '{n}.0');
        $count = $this->Proveedor->find('count');
        $this->set('total', $count);
        $this->set('datos', $cadena);
        $this->render('respuestas/get_proveedores');
    }
    function guardar_proveedor(){
        Configure::write('debug', '0');
        $this->layout = 'ajax';
        //$info = array('success' => true,'msg'=> 'Se almacen&oacute; el nuevo registro correctamente');
       
        $datosSesion=$this->Session->read('Usuario');
        if($datosSesion){
            if($this->data) { 
                //echo print_r($this->data);
                    if($this->Proveedor->Save($this->data)) {
                        if($this->Proveedor->getInsertId()!=''){
                            $id=$this->Proveedor->getInsertId();
                            $msg="Se almacen&oacute; el nuevo registro correctamente";
                        }
                        else{
                            $id=$this->data['Proveedor']['proveedor_id'];
                            $msg="El registro fu&eacute; modificado correctamente";
                        }
                        $info = array('success' => true,'msg'=>$msg,'id'=>$id );
                        $this->log("Proveedor $id almacenada en, Proveedors por->".$datosSesion['Usuario']['login'], LOG_DEBUG);
                    }else {
                        $info = array('success' => false,'msg'=> "No se pudo almacenar. Intentelo nuevamente");
                        $this->log("no se pudo almacenar el Proveedor nuevo en, Proveedors por->".$datosSesion['Usuario']['login']);
                    }
             
            }else {
                $info = array('success' => false,'msg'=> "Error en el envio de datos");
                $this->log("Error en el envio de datos, Proveedors por->".$datosSesion['Usuario']['login']);
            }
        }else{
            $info = array('success' => false,'msg'=> "No tiene una sesi&oacuten activa",'redir'=>true);
            $this->log("Error No tiene una sesi&oacuten activa, Articulo");
        }

        $this->set('info',$info);
        $this->render('respuestas/guardar_proveedor');
    }
    function eliminar_proveedor() {
        Configure::write('debug', '0');
         $this->layout = 'ajax';
        if ($_REQUEST['id']) {
           
            $id = $_REQUEST['id'];

            if ($id > 0) {
                if ($this->Proveedor->delete($id)) {
                            $info = array('success' => true, 'msg' => 'El registro seleccionado fu&eacute; eliminado correctamente');
                            //$sql = "SELECT * FROM public.actualizar_saldo_mov($kardex_id,'$fecha_mov','C')";
                            //$res = $this->Compra->query($sql);
                        } else {
                            $info = array('success' => false, 'msg' => 'No se pudo eliminar el registro seleccionado');
                        }
            } else {
                $info = array('success' => false, 'msg' => 'No se elim&ocute; el registro pq el Identificador ' . $id . ' no existe');
            }
        }
        $this->set('info', $info);
        $this->render('respuestas/eliminar_proveedor');
    }
    


}

?>