<?php

class OpcionesController extends AppController {

    var $helpers = array('Html', 'Form', 'Javascript');
    var $components = array('RequestHandler');

    function test() {
        $this->layout = 'ajax';
        /* echo "<pre>";
          echo print_r($this->Opcione->find('all'));
          echo "</pre>"; */
    }

    function icons() {
        $this->layout = 'ajax';
        $dir = '../../../libs/icons/';
        $directorio = opendir($dir);


        $cont = 0;
        $text=".page_white_add {
                background-image: url(/libs/icons/page_white_add.png) !important;

            }";
        while ($archivo = readdir($directorio)) {
            $extension = pathinfo($archivo, PATHINFO_EXTENSION);
            $nombre_base = basename($archivo, '.' . $extension);
            if ($extension == 'png') {
                $datos[] = array(
                        'nombre_icon' => $nombre_base,
                        'archivo_icon' => $archivo,
                        'dir_icon' => $dir . $archivo
                );

            }
        }
        $datos = $this->orderMultiDimensionalArray($datos, 'nombre_icon');
        for ($i=0;$i<count($datos);$i++) {
            echo ".".$datos[$i]['nombre_icon']." {
                background-image: url(/libs/icons/".$datos[$i]['archivo_icon'].") !important;

            }<br>";
        }

        closedir($directorio);
        $this->render('test');
        // echo "{total: $cont, icons:" . json_encode($icons) . "}";
    }
    //funcion para obtener los datos del los iconos
    function geticons() {
         Configure::write('debug', '0');
        $this->layout = 'ajax';
        $dir = '../../../libs/icons/';
        $directorio = opendir($dir);


        $cont = 0;
        while ($archivo = readdir($directorio)) {
            $extension = pathinfo($archivo, PATHINFO_EXTENSION);
            $nombre_base = basename($archivo, '.' . $extension);
            if ($extension == 'png') {
                $datos[] = array(
                        'id' => $cont,
                        'nombre_icon' => $nombre_base,
                        'archivo_icon' => $archivo,
                        'dir_icon' => $dir . $archivo
                );
                $cont++;
            }
        }
        $datos = $this->orderMultiDimensionalArray($datos, 'nombre_icon');
        if ($_REQUEST) {
            $start = $_REQUEST['start'];
            $limit = $_REQUEST['limit'];
            $query = $_REQUEST['query'];

            for ($i = 0; $i < $limit; $i++) {
                $indice = $start + $i;
                $icons[$i] = $datos[$indice];
            }
        } else {
            $icons = $datos;
        }
        closedir($directorio);
        echo "{total: $cont, icons:" . json_encode($icons) . "}";
    }

    //funcion que devuelve la lsita de todos los roles
    function getopcionesxrol() {

        Configure::write('debug', '0');
        $this->layout = "ajax";
        $cadena1 = array();
        $count = 100;
        $conquery = "SELECT ro.rol_opcion_id, op.opcion_id,op.opcion_nombre,r.rol_id,ro.opcion_rol_r,ro.opcion_rol_w,ro.opcion_rol_c,ro.opcion_rol_d
                    FROM opciones op, rols_opcions ro,rols r
                    WHERE op.opcion_id=ro.opcion_id and ro.rol_id=r.rol_id
                    and ro.opcion_rol_activo=true order by op.opcion_id";
        $consulta1 = $this->Opcione->query($conquery);
        $cadena1 = Set::extract($consulta1, '{n}.0');
        $this->set('total', $count); //send total to the view
        $this->set('roles', $cadena1); //send users to the view
    }
    //funcion que recupra los datos de las opciones
    function getopcions($op = null) {
        Configure::write('debug', '0');
       $this->layout = "ajax";
        $cadena1 = array();
        //$count = $this->Opcione->findCount();
        if ($op == null) {
            $conquery = "select m2.opcion_id, m2.opcion_nombre, m1.opcion_nombre as padre, m2.opcion_padre,m2.opcion_icon,m2.opcion_url,m2.leaf,m2.opcion_descripcion
                         from opciones m1, opciones m2
                         where m1.opcion_id=m2.opcion_padre order by m2.opcion_id";
        } else {
            $conquery = "SELECT *
                         FROM
                        (select s.sistema_id as opcion_id, s.sistema_nombre as opcion_nombre from sistemas s
                        UNION
                        select o.opcion_id, o.opcion_nombre from opciones o) t
                        order by t.opcion_nombre";
        }

        $consulta1 = $this->Opcione->query($conquery);
        $cadena1 = Set::extract($consulta1, '{n}.0');
        $count=count($cadena1);
        $this->set('total', $count); //send total to the view
        $this->set('menus', $cadena1); //send users to the view
    }

    //funcion para lamacenar los permisos
    function guardar_permisos($idssel) {
        Configure::write('debug', '0');
     
        $info = array('success' => true);

        if($_REQUEST['rol_id']) {
            $ids_seleccionados = explode(",", $idssel);
            $id=$_REQUEST['rol_id'];
            $r=$_REQUEST['permiso_r'];
            $w=$_REQUEST['permiso_w'];
            $c=$_REQUEST['permiso_c'];
            $d=$_REQUEST['permiso_d'];
            for($i=0;$i<count($ids_seleccionados);$i++) {
                $opcion_id=$ids_seleccionados[$i];
                $sql_mod = "update rols_opcions set opcion_rol_r=$r,opcion_rol_w=$w,opcion_rol_c=$c,opcion_rol_d=$d where rol_id='$id' and opcion_id=$opcion_id;";
                $this->Opcione->query($sql_mod);
            }
            $info = array('success' => true,'msg'=>'Los permisos fueron actualizados');

        }else {
            $info = array('success' => false,'msg'=>'Los permisos no se pudieron actualizar. Intentelo nuevamente');
        }

        $this->set('info',$info);
        $this->render('eventos/guardar_permisos');

    }
    //funcion para lamacenar la opcion
    function guardar_opcion() {
        Configure::write('debug', '0');
        
        $datosSesion=$this->tieneSesion();

        if($this->data) {
            if(!isset($this->data['Opcione']['leaf']))
                $this->data['Opcione']['leaf']=false;
            $this->data['Opcione']['sistema_id']=1;
            if($this->Opcione->Save($this->data)) {
                if($this->Opcione->getInsertId()!='')
                    $opcion_id=$this->Opcione->getInsertId();
                else
                    $opcion_id=$this->data['Opcione']['opcion_id'];

                $info = array('success' => true,'msg'=> $opcion_id);
                $this->log("opcion $opcion_id almacenada en, Opciones por->".$datosSesion['login'], LOG_DEBUG);
            }else {
                $info = array('success' => false,'msg'=> "No se pudo almacenar. Intentelo nuevamente");
                $this->log("no se pudo almacenar la opcion $opcion_id en, Opciones por->".$datosSesion['login']);
            }
        }else {
            $info = array('success' => false,'msg'=> "Error en el envio de datos");
            $this->log("Error en el envio de datos, Opciones por->".$datosSesion['login']);
        }
        $this->set('info',$info);
        $this->render('eventos/guardar_opcion');
    }
    //funcion para eliminar la opcion
    function eliminar_opcion($idssel) {
        Configure::write('debug', '0');

            $ids=explode(",", $idssel);
            if(count($ids>1)){
                $text="los registros seleccionados fueron eliminados satisfactoriamente";
                $text2="No se pudo elimirar los registros seleccionados";
            }else{
                $text="El registro seleccionado fue eliminado correctamente";
                $text2="No se pudo eliminar el registro seleccionado";
            }
            //echo print_r($ids);
            $rol_id=$_REQUEST['rol_id'];
            for($i=0;$i<count($ids);$i++) {
                $opcion_id=$ids[$i];
                $delete="delete from rols_opcions where opcion_id=$opcion_id";
                $this->Opcione->query($delete);
                if ($this->Opcione->deleteAll(array('opcion_id'=>$opcion_id,'leaf'=>true))) {
                    $info = array('success' => true,'msg'=> $text);
                }else {
                    $info = array('success' => false,'msg'=> $text2);
                }
            }


        $this->set('info',$info);
        $this->render('eventos/info');
    }
   

}