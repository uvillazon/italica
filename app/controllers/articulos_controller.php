<?php
App::import('Controller', 'rols');
class ArticulosController extends AppController {

    var $helpers = array('Html', 'Form', 'Javascript');
    var $components = array('RequestHandler');

    function test() {
        $this->layout = 'ajax';
        echo "<pre>";
        echo print_r($this->Articulo->find('all'));
        echo "</pre>";
    }
    //formulario para la autenticacion
    function articulo() {
        Configure::write('debug', '0');// deshabilitamos el debug de cakephp
        $rols = new RolsController();
        $datosSesion=$this->tieneSesion();
        $this->loadModel('Rol');
        $permisos=$rols->verificar_permisos($_REQUEST['opcionId'],$datosSesion['Rol']['rol_id'],$this->Articulo);
        //echo print_r($permisos);
        $this->set('permisos',$permisos);
        $this->render('vistas/articulo');
    }
    //funcion que retorna todas las unidades registradas en BD

    function get_articulos(){
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

        $conquery = "SELECT a.articulo_id,a.articulo_descripcion,a.articulo_imagen,a.articulo_codigo,c.categoria_nombre,c.categoria_id
                    FROM articulos a
                    INNER JOIN categorias c ON c.categoria_id=a.categoria_id
                    ORDER BY a.articulo_descripcion
                    LIMIT $limit OFFSET $start
                ";
        $consulta = $this->Articulo->query($conquery);
        $cadena = Set::extract($consulta, '{n}.0');
        $count = $this->Articulo->find('count');
        $this->set('total', $count);
        $this->set('datos', $cadena);
        $this->render('respuestas/get_articulos');
    }
    function guardar_articulo(){
        Configure::write('debug', '0');
        $this->layout = 'ajax';
        //$info = array('success' => true,'msg'=> 'Se almacen&oacute; el nuevo registro correctamente');
       
        $guardar=false;
        $datosSesion=$this->Session->read('Usuario');
        if($datosSesion){
            if($this->data) {
                sleep(1);
                if($_FILES['photo-path']['name']!=''){//si existe archivo para subir
                    $nombre_archivo = substr(md5(uniqid(rand())),0,8).$_FILES['photo-path']['name'];
                    $tipo_archivo = $_FILES['photo-path']['type'];
                    $tamano_archivo = $_FILES['photo-path']['size']/1024;
                    $destino="../../app/webroot/img/fotos/".$nombre_archivo;
                    if ($_FILES["file"]["error"] > 0){
                        $info = array('success' => false,'msg'=>$_FILES["file"]["error"]);
                    }else{
                        if (!((strpos($tipo_archivo, "gif") || strpos($tipo_archivo, "jpeg")|| strpos($tipo_archivo, "png")) && ($tamano_archivo < 2048000))) {
                            $info = array('success' => false,'msg'=> "La extensi&oacute;n o el tama&ntilde;o de los archivos no es correcta.Se permiten archivos .gif o .jpg y de 2M m&aacute;ximo.");

                        }else{
                            if (move_uploaded_file($_FILES['photo-path']['tmp_name'], $destino)){
                                $this->data['Articulo']['articulo_imagen']=$nombre_archivo;
                                $guardar=true;
                            }else{
                                $info = array('success' => false,'msg'=> "Hubo un error al subir el archivo en: ".$destino);

                            }
                        }
                    }
                }else{
                    $guardar=true;
                }
                if($guardar){
                    if($this->Articulo->Save($this->data)) {
                        if($this->Articulo->getInsertId()!=''){
                            $id=$this->Articulo->getInsertId();
                            $msg="Se almacen&oacute; el nuevo registro correctamente";
                        }
                        else{
                            $id=$this->data['Articulo']['articulo_id'];
                            $msg="El registro fu&eacute; modificado correctamente";
                        }
                        $info = array('success' => true,'msg'=>$msg,'id'=>$id );
                        $this->log("Articulo $id almacenada en, Articulos por->".$datosSesion['Usuario']['login'], LOG_DEBUG);
                    }else {
                        $info = array('success' => false,'msg'=> "No se pudo almacenar. Intentelo nuevamente");
                        $this->log("no se pudo almacenar la Articulo nueva en, Articulos por->".$datosSesion['Usuario']['login']);
                    }
                }

            }else {
                $info = array('success' => false,'msg'=> "Error en el envio de datos");
                $this->log("Error en el envio de datos, Articulos por->".$datosSesion['Usuario']['login']);
            }
        }else{
            $info = array('success' => false,'msg'=> "No tiene una sesi&oacuten activa",'redir'=>true);
            $this->log("Error No tiene una sesi&oacuten activa, Articulo");
        }

        $this->set('info',$info);
        $this->render('respuestas/guardar_articulo');
    }
    


}

?>