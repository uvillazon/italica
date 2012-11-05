<?php
App::import('Controller', 'rols');
class ClientesController extends AppController {

    var $helpers = array('Html', 'Form', 'Javascript');
    var $components = array('RequestHandler');

    function test() {
        $this->layout = 'ajax';
        echo "<pre>";
        echo print_r($this->Proveedor->find('all'));
        echo "</pre>";
    }
    //formulario para la autenticacion
    function cliente() {
        Configure::write('debug', '0');// deshabilitamos el debug de cakephp
        $rols = new RolsController();
        $datosSesion=$this->tieneSesion();
        $this->loadModel('Rol');
        $permisos=$rols->verificar_permisos($_REQUEST['opcionId'],$datosSesion['Rol']['rol_id'],$this->Cliente);
        //echo print_r($permisos);
        $this->set('permisos',$permisos);
        $this->render('vistas/cliente');
    }
    //funcion que retorna todas las unidades registradas en BD

    function get_clientes(){
        //Configure::write('debug', '0');
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

       $conquery = "SELECT *, p.persona_apellido1 ||' '|| p.persona_apellido2 ||' '||p.persona_nombres as cliente_nombre_completo
                    FROM clientes c
                    INNER JOIN personas p ON c.persona_id=p.persona_id
                    
                    ORDER BY p.persona_apellido1 
                   
                    LIMIT $limit OFFSET $start
                ";
        $consulta = $this->Cliente->query($conquery);
        $cadena = Set::extract($consulta, '{n}.0');
        $count = $this->Cliente->find('count');
        $this->set('total', $count);
        $this->set('datos', $cadena);
        $this->render('respuestas/get_clientes');
    }
    function guardar_cliente(){
        Configure::write('debug', '0');
        $this->layout = 'ajax';
        //$info = array('success' => true,'msg'=> 'Se almacen&oacute; el nuevo registro correctamente');
       
        $datosSesion=$this->Session->read('Usuario');
        if($datosSesion){
            if($this->data) { 
                //echo print_r($this->data);
                 $this->loadModel('Persona');
                 $this->data['Persona']['persona_doci']= $this->data['Cliente']['cliente_nit'];
                 $this->data['Persona']['persona_exp_doci']="CO";
                 if($this->Persona->Save($this->data)) {
                     if($this->Persona->getInsertId()!=''){
                            $id=$this->Persona->getInsertId();                           
                        }
                        else{
                            $id=$this->data['Persona']['persona_id'];                           
                        }
                        $this->data['Cliente']['persona_id']=$id;
                     if($this->Cliente->Save($this->data)) {
                        if($this->Cliente->getInsertId()!=''){
                            $id=$this->Cliente->getInsertId();
                            $msg="Se almacen&oacute; el nuevo registro correctamente";
                        }
                        else{
                            $id=$this->data['Cliente']['cliente_id'];
                            $msg="El registro fu&eacute; modificado correctamente";
                        }
                        $info = array('success' => true,'msg'=>$msg,'id'=>$id );
                        $this->log("Cliente $id almacenada en, Clientes por->".$datosSesion['Usuario']['login'], LOG_DEBUG);
                    }else {
                        $info = array('success' => false,'msg'=> "No se pudo almacenar. Intentelo nuevamente");
                        $this->log("no se pudo almacenar el Cliente nuevo en, Clientes por->".$datosSesion['Usuario']['login']);
                    } 
                 }else{
                    $info = array('success' => false,'msg'=> "No se pudo almacenar. Intentelo nuevamente");
                        $this->log("no se pudo almacenar el Cliente nuevo en, Personas por->".$datosSesion['Usuario']['login']); 
                 }
                   
             
            }else {
                $info = array('success' => false,'msg'=> "Error en el envio de datos");
                $this->log("Error en el envio de datos, Cliente por->".$datosSesion['Usuario']['login']);
            }
        }else{
            $info = array('success' => false,'msg'=> "No tiene una sesi&oacuten activa",'redir'=>true);
            $this->log("Error No tiene una sesi&oacuten activa, Cliente");
        }

        $this->set('info',$info);
        $this->render('respuestas/guardar_cliente');
    }
    function eliminar_cliente() {
        Configure::write('debug', '0');
         $this->layout = 'ajax';
        if ($_REQUEST['id']) {
           
            $id = $_REQUEST['id'];

            if ($id >= 0) {
                if ($this->Cliente->delete($id)) {
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
        $this->render('respuestas/eliminar_cliente');
    }
    


}

?>