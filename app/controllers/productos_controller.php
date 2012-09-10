<?php
App::import('Controller', 'rols');
class ProductosController extends AppController {

    var $helpers = array('Html', 'Form', 'Javascript');
    var $components = array('RequestHandler');

    function test() {
        $this->layout = 'ajax';
        echo "<pre>";
        echo print_r($this->Producto->find('all'));
        echo "</pre>";
    }
    function detalle(){
        //Configure::write('debug', '0');// deshabilitamos el debug de cakephp
        $this->render('vistas/detalle');
    }
     function producto(){
        Configure::write('debug', '0');// deshabilitamos el debug de cakephp
        $rols = new RolsController();
        $datosSesion=$this->tieneSesion();
        $this->loadModel('Rol');
        $permisos=$rols->verificar_permisos($_REQUEST['opcionId'],$datosSesion['Rol']['rol_id'],$this->Articulo);
        //echo print_r($permisos);
        $this->set('permisos',$permisos);
        $this->render('vistas/producto');
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

        $conquery = "SELECT p.*
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
        $this->set('total', $count);
        $this->set('datos', $cadena);
        $this->render('respuestas/get_productos');
    }

}

?>