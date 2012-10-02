<?php
App::import('Controller', 'rols');
class ComprasController extends AppController {

    var $helpers = array('Html', 'Form', 'Javascript');
    var $components = array('RequestHandler');

    function test() {
        $this->layout = 'ajax';
        echo "<pre>";
        echo print_r($this->Compra->find('all'));
        echo "</pre>";
    }
    function detalle(){
        //Configure::write('debug', '0');// deshabilitamos el debug de cakephp
        $this->render('vistas/detalle');
    }
    function compra(){
        Configure::write('debug', '0');// deshabilitamos el debug de cakephp
        $rols = new RolsController();
        $datosSesion=$this->tieneSesion();
        $this->loadModel('Rol');
        $permisos=$rols->verificar_permisos($_REQUEST['opcionId'],$datosSesion['Rol']['rol_id'],$this->Producto);
        //echo print_r($permisos);
        $this->set('permisos',$permisos);
        $this->render('vistas/compra');
    }
    
    function get_proximo_id(){
        
    }

    //funcion que retorna todas las unidades registradas en BD

    function get_productos(){
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

        $conquery = "SELECT p.*,c.*,u.*,m.*
                    FROM productos p
                    INNER JOIN categorias c ON c.categoria_id=p.categoria_id
                    INNER JOIN marcas m ON m.marca_id=p.marca_id
                    INNER JOIN unidads u ON u.unidad_id=p.unidad_id

                    ORDER BY p.producto_nombre
                    LIMIT $limit OFFSET $start
                ";

        $consulta = $this->Producto->query($conquery);
        $cadena = Set::extract($consulta, '{n}.0');
        $count = $this->Producto->find('count');
        //  echo print_r($cadena);
        $this->set('total', $count);
        $this->set('datos', $cadena);
        $this->render('respuestas/get_productos');
    }

    function guardar_producto(){
        Configure::write('debug', '0');
        $this->layout = 'ajax';
        //$info = array('success' => true,'msg'=> 'Se almacen&oacute; el nuevo registro correctamente');

        $guardar=false;
        $datosSesion=$this->Session->read('Usuario');
        if($datosSesion){
            if($this->data) {
                // echo print_r($this->data);exit;
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
                                $this->data['Producto']['producto_imagen']="../app/webroot/img/fotos/".$nombre_archivo;
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
                    if($this->Producto->Save($this->data)) {
                        if($this->Producto->getInsertId()!=''){
                            $id=$this->Producto->getInsertId();
                            $msg="Se almacen&oacute; el nuevo registro correctamente";
                        }
                        else{
                            $id=$this->data['Producto']['producto_id'];
                            $msg="El registro fu&eacute; modificado correctamente";
                        }
                        $this->loadModel('Kardex');
                        for ($i=1; $i<4;$i++){
                            if($i==1){
                                $this->data['Kardex']['kardex_id']=$this->data['Kardex']['kardex_id_1'];
                            }
                            if($i==2){
                                $this->data['Kardex']['kardex_id']=$this->data['Kardex']['kardex_id_2'];
                            }
                            if($i==3){
                                $this->data['Kardex']['kardex_id']=$this->data['Kardex']['kardex_id_3'];
                            }
                            $this->data['Kardex']['kardex_saldo_valor']=$this->data['Kardex'][$i]*$this->data['Producto']['producto_precio'];
                            $this->data['Kardex']['kardex_saldo_cantidad']=$this->data['Kardex'][$i];
                            $this->data['Kardex']['producto_id']=$id;
                            $this->data['Kardex']['kardex_tipo_mov']='S';
                            $this->data['Kardex']['sucursal_id']=$i;
                            if($this->Kardex->save($this->data)){
                                $msg='Se guardaron todos los items correctamente';
                            }else{
                                $msg='Se guard&oacute; el producto pero hubo un error al guardar los stock inicial';
                            }

                            $info = array('success' => true,'msg'=>$msg,'id'=>$id );
                            $this->log("Producto $id almacenada en, Productos por->".$datosSesion['Usuario']['login'], LOG_DEBUG);
                        }

                    }else {
                        $info = array('success' => false,'msg'=> "No se pudo almacenar. Intentelo nuevamente");
                        $this->log("no se pudo almacenar el producto nuevo en, Productos por->".$datosSesion['Usuario']['login']);
                    }
                }

            }else {
                $info = array('success' => false,'msg'=> "Error en el envio de datos");
                $this->log("Error en el envio de datos, Productos por->".$datosSesion['Usuario']['login']);
            }
        }else{
            $info = array('success' => false,'msg'=> "No tiene una sesi&oacuten activa",'redir'=>true);
            $this->log("Error No tiene una sesi&oacuten activa, Productos");
        }

        $this->set('info',$info);
        $this->render('respuestas/guardar_producto');
    }
    function eliminar_producto(){
        Configure::write('debug', '0');
        if($_REQUEST['id']) {
            $this->loadModel('Kardex');
            $id=$_REQUEST['id'];
            $kardex_id=$_REQUEST['kardex_id'];
            if($kardex_id>0){
                if ($this->Kardex->delete($kardex_id)) {
                    if ($this->Producto->delete($id)) {
                        $info = array('success' => true,'msg'=>'El registro seleccionado fue eliminado correctamente');
                    }else {
                        $info = array('success' => false,'msg'=>'No se pudo eliminar el registro seleccionado');
                    }
                }
            }else{
               if ($this->Producto->delete($id)) {
                        $info = array('success' => true,'msg'=>'El registro seleccionado fue eliminado correctamente');
                    }else {
                        $info = array('success' => false,'msg'=>'No se pudo eliminar el registro seleccionado');
                    }
            }


        }
        $this->set('info',$info);
        $this->render('respuestas/eliminar_producto');
    }

}

?>