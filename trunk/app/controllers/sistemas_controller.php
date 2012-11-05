<?php

class SistemasController extends AppController {

    var $helpers = array('Html', 'Form', 'Javascript');
    var $components = array('RequestHandler');

    function test() {
        $this->layout = 'ajax';
        $this->loadModel('Persona');
        $this->loadModel('Usuario');
        echo "<pre>";
        echo print_r($this->Usuario->find('all'));
        echo "</pre>";
    }
    //formulario para la autenticacion
    function loginf() {
        Configure::write('debug', '0');// deshabilitamos el debug de cakephp
        $this->render('vistas/loginf');
    }
    //funcion para autenticacion
    function login() {
        Configure::write('debug', '0');// deshabilitamos el debug de cakephp
        $this->layout = 'ajax';
        $this->loadModel('Usuario');
        $this->loadModel('Ingreso');

        //$info = array('success' => false, 'msg' => 'por defecto');
        if (!empty($this->data)) {
            $user = $this->Usuario->find(array("Usuario.login" => $this->data ['Sistema'] ['login']));
            $pass = md5($this->data ['Sistema'] ['pass']);
            $sucursal_id=$this->data ['Sistema'] ['sucursal_id'];
            $sucursal_ok=false;
            $ip = $this->getRealIP();// obtenemos la ip de donde se esta ingresando

           //echo print_r($user);
            //si la contraseña es correcta
            if ($user ["Usuario"] ["sucursal_id"] == $sucursal_id) {
               $sucursal_ok=true;
            }else{
                if($sucursal_id==0){
                    if($user ["Rol"] ["rol_id"]==0 || $user ["Rol"] ["rol_id"]==1){
                        $sucursal_ok=true;
                    }else{
                       $sucursal_ok=false;
                    }
                }else{
                    if($user ["Rol"] ["rol_id"]==0 || $user ["Rol"] ["rol_id"]==1){
                         $sucursal_ok=true;
                    }else{
                        $sucursal_ok=false; 
                    }
                    
                }
            }
                   if ($user ["Usuario"] ["password"] == $pass && $sucursal_ok) {
                    //si el usuario esta activo
                    if ($user ["Usuario"] ["activo"] == 1) {
                        //le damos acceso
                        $this->data['Ingreso']['usuario_id'] = $user['Usuario']['usuario_id'];
                        $this->data['Ingreso']['ingreso_fecha_hora'] = 'NOW';

                        $this->data['Ingreso']['ingreso_aceptado'] = true;
                        $this->data['Ingreso']['ingreso_descripcion'] = 'El usuario ingreso al sistema satisfactoriamente';
                        $this->data['Ingreso']['ingreso_ip'] = $ip;
                        if ($this->Ingreso->save($this->data)) {
                            $this->log('Datos almacenados en la tabla Ingreso, Usuario->' . $user['Usuario'] ['login'], LOG_DEBUG);
                            $ingreso = $this->Ingreso->find('first', array('order' => array('ingreso_id DESC')));
                            $user['Usuario']['ingreso_id'] = $ingreso['Ingreso']['ingreso_id'];
                        }

                        if ($this->Session->write('Usuario', $user)) {
                            $this->Session->write('Sucursal',$sucursal_id);
                            $info = array('success' => true);
                            $this->log('Ingreso, Usuario->' . $user['Usuario'] ['login'], LOG_DEBUG);
                        } else {
                            $info = array('success' => false, 'msg' => 'Existio un error al crear la sesion');
                            $this->log('Ingreso->no se pudo crear la sesion, Usuario->' . $user['Usuario'] ['login']);
                        }

                    }else {// usuario esta bloqueado

                        $this->data['Ingreso']['usuario_id'] = $user['Usuario']['usuario_id'];
                        $this->data['Ingreso']['ingreso_fecha_hora'] = 'NOW';

                        $this->data['Ingreso']['ingreso_aceptado'] = false;
                        $this->data['Ingreso']['ingreso_descripcion'] = 'El usuario trató de ingresar al sistema.Usuario Bloqueado';
                        $this->data['Ingreso']['ingreso_ip'] = $ip;
                        if ($this->Ingreso->save($this->data)) {
                            $this->log('Datos almacenados en la tabla Ingreso, Usuario->' . $user['Usuario'] ['login'], LOG_DEBUG);
                        }
                        $info = array('success' => false, 'msg' => 'El Usuario fu&eacute; Bloqueado contactese con el Administrador');
                        $this->log('Ingreso->El Usuario fu&eacute; Bloqueado Contactese con el Administrador, Usuario->' . $this->data ['Usuario'] ['login']);
                    }
                }else {// contraseña incorrecta
                    $this->data['Ingreso']['usuario_id'] = $user['Usuario']['usuario_id'];
                    $this->data['Ingreso']['ingreso_fecha_hora'] = 'NOW';

                    $this->data['Ingreso']['ingreso_aceptado'] = false;
                    $this->data['Ingreso']['ingreso_descripcion'] = 'El usuario trato de ingresar al sistema. Contrasenia Incorrecta';
                    $this->data['Ingreso']['ingreso_ip'] = $ip;
                    //echo print_r( $this->data['Ingreso']);
                    if ($this->Ingreso->save($this->data)) {
                        $this->log('Datos almacenados en la tabla Ingreso, Usuario->' . $this->data ['Usuario'] ['login'], LOG_DEBUG);
                    }

                    $info = array('success' => false, 'msg' => 'Contrase&ntilde;a es incorrecta o no tiene acceso a la Sucursal seleccionada');
                    $this->log('Ingreso->Contrasenia incorrecta, Usuario->' . $this->data ['Usuario'] ['login']);
                }

        }
        //$info = array('success' => false, 'msg' => 'por defecto');
        $this->set('info', $info);
        $this->render('eventos/login');
    }
    //funcion para cerrar sesion

    //funcion para desloguearse del sistema y matar la sesion respectiva
    function logout($ajax = null) {
        Configure::write('debug', 0);

        $datos = $this->Session->read('Usuario');
        if ($datos != null) {
            $this->Session->delete('Usuario');
            $this->log('Salida->Cierre de sesion, Usuario->' . $datos ['login'], LOG_DEBUG);
            $sql_update="update ingresos set fecha_hora_salida='NOW' where ingreso_id=".$datos['ingreso_id'];
            $this->Sistema->query($sql_update);
        } else {
            $this->log('Salida sin sesión abierta');
        }
        $this->redirect("loginf", null, true);
    }


    function principal() {
        Configure::write('debug', '0');
        $datos=$this->tieneSesion();
        $sucursal_id=  $this->Session->read('Sucursal');
        $this->loadModel('Sucursal');
        $sucursal=$this->Sucursal->find(array("Sucursal.sucursal_id" => $sucursal_id));
        $sucursal_nombre=strtoupper($sucursal['Sucursal']['sucursal_nombre']);
         //echo print_r($datos);exit;
        $nombres=$datos['Persona']['persona_nombres'].' '.$datos['Persona']['persona_apellido1'].' '.$datos['Persona']['persona_apellido2'];
        $this->set('nombres',strtoupper($nombres));
         $this->set('sucursal',$sucursal_nombre);
        $this->render('vistas/principal');
    }

    //funcion para obtener el menu de la barra de navegacion
    function getmenu() {
        Configure::write('debug', '0');
        // Se recibe el ID del nodo que se desea expandir

        $nodo = $_REQUEST["node"];

        //Si el nodo a cargar es el principal, el id que se recibe es = root asi que lo cambio para acomodarlo a mi base de datos

        if ($nodo == 'root') {

            //buscamos todos los sistemas

            $sql = "SELECT sis.sistema_id as id, sis.sistema_nombre as text, 'false' as leaf, '0' as parentId
                FROM sistemas sis ORDER BY sis.sistema_nombre";

        }else {
            $datos=$this->tieneSesion();
            //buscamos las opciones del sistema
            if($datos['rol_id']!=0)
            $cons_rol=" INNER JOIN rols_opcions rp ON rp.opcion_id=op.opcion_id AND rp.rol_id=".$datos['rol_id'];
            else
            $cons_rol="";

            $sql = "SELECT op.opcion_id as id, op.opcion_nombre as text, op.leaf as leaf, op.opcion_padre as parentId,
                    '../app/webroot/img/icons/' || op.opcion_icon || '.png' as icon, op.opcion_url as url, op.opcion_icon as icontab
                    FROM sistemas sis
                    INNER JOIN opciones op ON op.sistema_id=sis.sistema_id
            $cons_rol
                    WHERE  op.leaf=false  AND op.opcion_padre=$nodo ORDER BY op.opcion_nombre";
            $consulta = $this->Sistema->query($sql);
            $cadena = Set::extract($consulta, '{n}.0');
            if(count($cadena)==0) {



                $sql="SELECT op.opcion_id as id, op.opcion_nombre as text, op.leaf as leaf, op.opcion_padre as parentId,
                            '../app/webroot/img/icons/' || op.opcion_icon || '.png' as icon, op.opcion_url as url, op.opcion_icon as icontab

                                FROM opciones op
                $cons_rol
                                WHERE  op.leaf=true AND op.opcion_padre=$nodo ORDER BY op.opcion_nombre";
            }


        }


        // echo $sql;exit;
        $consulta = $this->Sistema->query($sql);
        $cadena = Set::extract($consulta, '{n}.0');
        echo '{modulos:' . json_encode($cadena) . '}';
        $this->render('eventos/getmenu');
    }
    function principal2(){
        //Configure::write('debug', '0');
        $datos=$this->tieneSesion();
        // echo print_r($datos['Persona']['persona_nombres']);exit;
        $nombres=$datos['Persona']['persona_nombres'].' '.$datos['Persona']['persona_apellido1'].' '.$datos['Persona']['persona_apellido2'];
        $this->set('nombres',strtoupper($nombres));
        $this->render('vistas/principal2');
    }

}

?>