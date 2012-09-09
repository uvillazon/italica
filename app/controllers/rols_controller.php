<?php
class RolsController extends AppController {

    var $helpers = array('Html', 'Form', 'Javascript');
    var $components = array('RequestHandler');

    function test() {
        echo "<pre>";
        echo print_r ($this->tieneSesion());
        echo "</pre>";
    }
    //pantalla principal de la administracion de los roles de usuario

    function rols() {
        Configure::write('debug', '0');       
        $this->layout = "blanco";
        $datosSesion=$this->tieneSesion();
        $permisos=$this->verificar_permisos($_REQUEST['opcionId'],$datosSesion['rol_id'],$this->Rol);
        //echo print_r($permisos);
        $this->set('permisos',$permisos);
        $this->render('vistas/rols');
    }

    //funcion para almacenar el rol
    function guardar_rol() {
        Configure::write('debug', '0');
        $datosSesion=$this->tieneSesion();
        if($datosSesion!=null) {// verificamos la sesion si esta activa
           
                if ($this->data) {
                    // echo print_r($this->data);
                    if($this->Rol->save($this->data)) {
                        if($this->Rol->getInsertId()!='')
                            $rol_id=$this->Rol->getInsertId();
                        else
                            $rol_id=$this->data['Rol']['rol_id'];
                        $this->log('Datos almacenados en la tabla Rols, Usuario->'.$datosSesion['login'], LOG_DEBUG);
                        $info = array('success' => true,'msg'=> $rol_id);
                    }

                }
           

        }else {
            $this->log('Error,no tiene sesion Modificando o creando  Rols, Usuario->'.$this->getRealIP());
            $info = array('success' => false,'msg'=> 'Error, no tiene una sesi\u00f3n activa');
        }
        $this->set('info',$info);
        $this->render('eventos/guardar_rol');

    }
    //funcion para eliminar el rol
    function eliminar_rol() {
        Configure::write('debug', '0');
        $info = array('success' => true);
        if($_REQUEST['rol_id']) {
            $id=$_REQUEST['rol_id'];
            $sql_mod = "delete from rols_opcions where rol_id='$id';";

            $this->Rol->query($sql_mod);
            if ($this->Rol->delete($id)) {
                $info = array('success' => true,'msg'=>'El registro seleccionado fue eliminado correctamente');
            }else {
                $info = array('success' => false,'msg'=>'No se pudo eliminar el registro seleccionado');
            }




        }

        $this->set('info',$info);
        $this->render('eventos/eliminar_rol');
    }

    //funcion que devuelve la lsita de todos los roles
    function getroles() {
        //$this->layout = "blanco";
        Configure::write('debug', '0'); //set debug to 0 for this function because debugging info breaks the XMLHttpRequest
        //$this->layout = "ajax"; //this tells the controller to use the Ajax layout instead of the default layout (since we're using ajax . . .)
        $cadena1 = array(); //this will hold our data from the database.
        if ($_REQUEST['start']!='')
            $start=$_REQUEST['start'];
        else
            $start=0;
        if ($_REQUEST['limit']!='')
            $limit=$_REQUEST['limit'];
        else
            $limit=1000;

        if ($_REQUEST['query']!='')
            $filter=" AND rol_nombre ILIKE '%".trim($_REQUEST['query'])."%'";
        else
            $filter="";


        $conquery = "SELECT * FROM rols WHERE rol_id>0 $filter ORDER BY rol_nombre LIMIT $limit OFFSET $start;";
        $consulta1 = $this->Rol->query($conquery);
        $cadena1 = Set::extract($consulta1, '{n}.0');
        $count = count($cadena1);
        $this->set('total', $count); //send total to the view
        $this->set('roles', $cadena1); //send users to the view
        $this->render('eventos/getroles');

    }

    //almacenamos las opciones asignadas a un determinado rol
    function guardar_opcionesxrol($idssel=null,$idsasig=null) {
        Configure::write('debug', '0');
        $datos = $this->Session->read('Usuario');
        if($_REQUEST['rol_id']) {
            $id_rol=$_REQUEST['rol_id'];
            $ids_seleccionados = explode(",", $idssel);
            $ids_asig=explode(",", $idsasig);
            if($idssel==$idsasig) {
                $info = array('success' => true,'msg'=>'iguales');
            }else {
                for($i=0;$i<count($ids_seleccionados);$i++) {
                    for($j=0;$j<count($ids_asig);$j++) {
                        if($ids_seleccionados[$i]==$ids_asig[$j]) {
                            $existe=true;
                            break;
                        } else {
                            $existe = false;
                        }
                    }
                    if ($existe == false) {
                        //array_push($idsinsert,$ids[$i]);
                        $sql_insertid = "insert into rols_opcions(opcion_id,rol_id,opcion_rol_activo) values ('$ids_seleccionados[$i]','$id_rol','true');";
                        $this->Rol->query($sql_insertid);
                        $this->log('Añadir opciones a rol->id_rol->' . $id_rol . ' opcion_id->' . $ids_seleccionados [$i] . ' usuario->' . $datos ['login'], LOG_DEBUG);

                    }
                }
                //quitar opciones existentes
                for ($i = 0; $i < count($ids_asig); $i++) {
                    for ($j = 0; $j < count($ids_seleccionados); $j++) {
                        if ($ids_asig [$i] == $ids_seleccionados [$j]) {
                            $existe = true;
                            break;
                        } else {
                            $existe = false;
                        }
                    }
                    if ($existe == false) {
                        $sql_mod = "delete from rols_opcions where opcion_rol_activo=true and rol_id='$id_rol' and opcion_id='$ids_asig[$i]';";
                        $this->Rol->query($sql_mod);
                        $this->log('Quitar opciones a rol->id_rol->' . $id_rol . ' id_op->' . $ids_asig[$i] . ' usuario->' . $datos ['login'], LOG_DEBUG);
                        //echo "quitar:".$idsasig[$i];
                    }

                }
                $info = array('success' => true,'msg'=>'Opciones Guardadas');
            }

        }

        $this->set('info',$info);
        $this->render('eventos/guardar_opcionesxrol');
    }
    //funcion para ver los permisos de una determinada opcion para un determinado rol
    function verificar_permisos($opcion_id,$rol_id,$controller) {

        Configure::write('debug', '0');
        if($rol_id==0) {
            $permisos['r']='false';
            $permisos['w']='false';
            $permisos['c']='false';
            $permisos['d']='false';
        }else {
            $query="select ro.opcion_rol_r as r,ro.opcion_rol_w as w,ro.opcion_rol_c as c,ro.opcion_rol_d as d
                    from rols_opcions ro where opcion_id=$opcion_id and rol_id=$rol_id;";
            $consulta=$controller->query($query);
            $perm = Set::extract($consulta, '{n}.0');
            if($perm[0]['r']==1)
                $permisos['r']='false';
            else
                $permisos['r']='true';
            if($perm[0]['w']==1)
                $permisos['w']='false';
            else
                $permisos['w']='true';
            if($perm[0]['c']==1)
                $permisos['c']='false';
            else
                $permisos['c']='true';
            if($perm[0]['d']==1)
                $permisos['d']='false';
            else
                $permisos['d']='true';
        }


        return $permisos;
        /*echo print_r($cadena);
           $this->render('test');*/
    }


}