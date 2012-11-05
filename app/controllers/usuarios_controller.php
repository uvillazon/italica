<?php
App::import('Controller', 'rols');
class UsuariosController extends AppController {

    var $helpers = array('Html', 'Form', 'Javascript');
    var $components = array('RequestHandler');

    function test() {
        $this->layout = 'ajax';
        echo "<pre>";
        echo print_r($this->Usuario->find('all'));
        echo "</pre>";
    }
    //funcion del foremularios principal de usuarios
    function usuarios() {
        Configure::write('debug', '0');
        $this->layout = 'blanco';
        $rols = new RolsController();
        $datosSesion=$this->tieneSesion();
        $this->loadModel('Rol');
        $permisos=$rols->verificar_permisos($_REQUEST['opcionId'],$datosSesion['rol_id'],$this->Usuario);
        //echo print_r($permisos);
        $this->set('permisos',$permisos);
        $this->render('vistas/usuarios');
    }
    //funcion para obtener los usuarios
    function getusers() {
       Configure::write('debug', '0');
        //$consulta=$this->Usuario->find('all');
          $this->layout = 'ajax';
        if($_REQUEST['start']!='')
            $start=$_REQUEST['start'];
        else
            $start=0;
        if($_REQUEST['limit']!='')
            $limit=$_REQUEST['limit'];
        else
            $limit=10000;

        $conquery = "SELECT p.*,r.rol_nombre,u.*,s.sucursal_nombre,s.sucursal_id, p.persona_apellido1 ||' '|| p.persona_apellido2||' '|| p.persona_nombres as nombre_completo
                    FROM usuarios u
                    INNER JOIN personas p ON p.persona_id=u.persona_id
                    INNER JOIN rols r ON r.rol_id=u.rol_id
                    INNER JOIN sucursals s ON s.sucursal_id=u.sucursal_id
                    ORDER BY p.persona_nombres
                    LIMIT $limit OFFSET $start
                ";
        $consulta = $this->Usuario->query($conquery);
        $cadena = Set::extract($consulta, '{n}.0');
        /*echo "<pre>";
         echo print_r($consulta);
          echo "<pre>";*/
        $count = $this->Usuario->find('count');
        $this->set('total', $count);
        $this->set('usuarios', $cadena);
        $this->render('eventos/getusers');
    }
    //function para verificar si existe el login
    function existe_login() {
        Configure::write('debug', '0');
        $info="";
        if($_REQUEST['login']) {
            $login=$_REQUEST['login'];
            $userCount = $this->Usuario->find('count', array('conditions' => array("Usuario.login" => $login)));

            if ($userCount>0) {
                //existe el login
                $info = array('success' => false,'msg'=>'El usuario ya esta registrado en el sistema');
            }else {
                //no existe el login
                $info = array('success' => true,'msg'=>'');
            }
        }
        $this->set('info',$info);
        $this->render('eventos/existe_login');
    }
    //funcion para guardar los datos del usuario
    function guardar_usuario() {
        Configure::write('debug', '0');
        $this->loadModel('Persona');
        $datosSesion=$this->tieneSesion();
        if($datosSesion!=null) {// verificamos la sesion si esta activa
            
            if ($this->data) {
                //echo print_r($this->data);exit;
                $this->data['Usuario']['password']=md5( $this->data['Usuario']['password']);
                if(!isset($this->data['Usuario']['activo']))
                    $this->data['Usuario']['activo']=false;
                if($this->Persona->save($this->data)) {
                     if($this->Persona->getInsertId()!='')
                        $user_id=$this->Persona->getInsertId();
                    else
                        $user_id=$this->data['Persona']['persona_id'];                        
                    $this->data['Usuario']['persona_id']=$user_id;
                    if($this->Usuario->save($this->data)){
                       $this->log('Datos almacenados en la tabla Personas-Usuarios, Usuario->'.$datosSesion['Usuario']['login'], LOG_DEBUG);
                    $info = array('success' => true,'msg'=> $user_id);
                    }                   
                }else {
                    $this->log('no se pudo almacenar los datos en la tabla Usuarios, Usuario->'.$datosSesion['Usuario']['login'], LOG_DEBUG);
                    $info = array('success' => true,'msg'=> 'No se pudo almacenar los datos');
                }
            }
        }else {
            $this->log('Error,no tiene sesion Modificando o creando  usuarios, Usuario->'.$this->getRealIP());
            $info = array('success' => false,'msg'=> 'Error, no tiene una sesion activa');
        }

        $this->set('info',$info);
        $this->render('eventos/guardar_usuario');
    }
    //funcion para eliminar usuario
    function eliminar_usuario() {
        Configure::write('debug', '0');
         $this->loadModel('Persona');
       
        if($_REQUEST['usuario_id']) {
            $usuario_id=$_REQUEST['usuario_id'];
             $persona_id=$_REQUEST['persona_id'];

            if ($this->Usuario->delete($usuario_id)) {
                if($this->Persona->delete($persona_id)) {
                     $info = array('success' => true,'msg'=>'El registro seleccionado fue eliminado correctamente');
                }else{
                   $info = array('success' => false,'msg'=>'No se pudo eliminar el registro seleccionado (Persona)');
                }
               
            }else {
                $info = array('success' => false,'msg'=>'No se pudo eliminar el registro seleccionado (Usuario)');
            }
        }
        $this->set('info',$info);
        $this->render('eventos/eliminar_usuario');

    }

}