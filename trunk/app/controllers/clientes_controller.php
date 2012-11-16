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
        Configure::write('debug', '0'); // deshabilitamos el debug de cakephp
        $rols = new RolsController();
        $datosSesion = $this->tieneSesion();
        $this->loadModel('Rol');
        $permisos = $rols->verificar_permisos($_REQUEST['opcionId'], $datosSesion['Rol']['rol_id'], $this->Cliente);
        //echo print_r($permisos);
        $this->set('permisos', $permisos);
        $this->render('vistas/cliente');
    }

    //funcion que retorna todas las unidades registradas en BD

    function get_clientes() {
        //Configure::write('debug', '0');
        //$consulta=$this->Usuario->find('all');
        $this->layout = 'ajax';
        if (isset($_REQUEST['start']))
            $start = $_REQUEST['start'];
        else
            $start = 0;
        if (isset($_REQUEST['limit']))
            $limit = $_REQUEST['limit'];
        else
            $limit = 10000;

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

    function guardar_cliente() {
        Configure::write('debug', '0');
        $this->layout = 'ajax';
        //$info = array('success' => true,'msg'=> 'Se almacen&oacute; el nuevo registro correctamente');

        $datosSesion = $this->Session->read('Usuario');
        if ($datosSesion) {
            if ($this->data) {
                //echo print_r($this->data);
                $this->loadModel('Persona');
                $this->data['Persona']['persona_doci'] = $this->data['Cliente']['cliente_nit'];
                $this->data['Persona']['persona_exp_doci'] = "CO";
                if ($this->Persona->Save($this->data)) {
                    if ($this->Persona->getInsertId() != '') {
                        $id = $this->Persona->getInsertId();
                    } else {
                        $id = $this->data['Persona']['persona_id'];
                    }
                    $this->data['Cliente']['persona_id'] = $id;
                    if ($this->Cliente->Save($this->data)) {
                        if ($this->Cliente->getInsertId() != '') {
                            $id = $this->Cliente->getInsertId();
                            $msg = "Se almacen&oacute; el nuevo registro correctamente";
                        } else {
                            $id = $this->data['Cliente']['cliente_id'];
                            $msg = "El registro fu&eacute; modificado correctamente";
                        }
                        $info = array('success' => true, 'msg' => $msg, 'id' => $id);
                        $this->log("Cliente $id almacenada en, Clientes por->" . $datosSesion['Usuario']['login'], LOG_DEBUG);
                    } else {
                        $info = array('success' => false, 'msg' => "No se pudo almacenar. Intentelo nuevamente");
                        $this->log("no se pudo almacenar el Cliente nuevo en, Clientes por->" . $datosSesion['Usuario']['login']);
                    }
                } else {
                    $info = array('success' => false, 'msg' => "No se pudo almacenar. Intentelo nuevamente");
                    $this->log("no se pudo almacenar el Cliente nuevo en, Personas por->" . $datosSesion['Usuario']['login']);
                }
            } else {
                $info = array('success' => false, 'msg' => "Error en el envio de datos");
                $this->log("Error en el envio de datos, Cliente por->" . $datosSesion['Usuario']['login']);
            }
        } else {
            $info = array('success' => false, 'msg' => "No tiene una sesi&oacuten activa", 'redir' => true);
            $this->log("Error No tiene una sesi&oacuten activa, Cliente");
        }

        $this->set('info', $info);
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

    function reporte_cliente() {
        Configure::write('debug', '0'); // deshabilitamos el debug de cakephp
        //$rols = new RolsController();
        //$datosSesion=$this->tieneSesion();
        //$this->loadModel('Rol');
        //$permisos=$rols->verificar_permisos($_REQUEST['opcionId'],$datosSesion['Rol']['rol_id'],$this->Marca);
        //echo print_r($permisos);
        //$this->set('permisos',$permisos);
        $this->render('vistas/reporte_cliente');
    }

    function ver_reporte_clientes() {
        Configure::write('debug', '0');

        $fecha_ini = $this->data['Cliente']['reporte_fini'];
        $fecha_fin = $this->data['Cliente']['reporte_ffin'];
        $cliente = trim($this->data['Cliente']['reporte_cliente']);
        $opcion_reporte = $this->data['Cliente']['reporte_opcion'];
        $ordenDir = $this->data['Cliente']['reporte_orden'];
     
        if ($opcion_reporte != '') {
          
            switch ($opcion_reporte) {
                
                case 0://listado de clientes
                    if ($ordenDir != '')
                        $filtro = $filtro . " ORDER BY p.persona_apellido1 $ordenDir, p.persona_apellido2 ASC, p.persona_nombres ASC";


                    $tituloTabla = "LISTADO DE CLIENTES (Orden Alfabetico $ordenDir)";

                    break;
                case 1://historial de compras
                    if ($fecha_ini != '' && $fecha_fin != '') {
                        if($cliente!=''){
                            $cliente_filtro=" AND c.cliente_id=$cliente";
                        }else{
                            $cliente_filtro="";
                        }
                  
                        $fecha_ini = date("d-m-Y", strtotime($fecha_ini));
                        $fecha_fin = date("d-m-Y", strtotime($fecha_fin));
                        $condiciones= " INNER JOIN ventas v ON v.cliente_id=c.cliente_id
                                        INNER JOIN sucursals s ON s.sucursal_id=v.sucursal_id";
                        $filtro = $filtro . " $cliente_filtro AND (to_char(v.venta_fecha,'dd-mm-yyyy') >='$fecha_ini' AND to_char(v.venta_fecha,'dd-mm-yyyy') <='$fecha_fin')";
                        $filtro.=" ORDER BY v.venta_fecha DESC";
                    }
                    $tituloTabla = "HISTORIAL DE COMPRAS ($fecha_ini al $fecha_fin)";
                    break;
                case 2:// historial de cambios
                   if($cliente!=''){
                            $cliente_filtro=" AND c.cliente_id=$cliente";
                        }else{
                            $cliente_filtro="";
                        }
                    if ($fecha_ini != '' && $fecha_fin != '') {
                        $fecha_ini = date("d-m-Y", strtotime($fecha_ini));
                        $fecha_fin = date("d-m-Y", strtotime($fecha_fin));
                         $condiciones= " INNER JOIN ventas v ON v.cliente_id=c.cliente_id
                                        INNER JOIN sucursals s ON s.sucursal_id=v.sucursal_id";
                        $filtro = $filtro . " $cliente_filtro AND v.cambio_autorizado_por_id >0 AND (to_char(v.venta_cambio_fecha,'dd-mm-yyyy') >='$fecha_ini' AND to_char(v.venta_cambio_fecha,'dd-mm-yyyy') <='$fecha_fin')";
                        $filtro.=" ORDER BY v.venta_cambio_fecha DESC";
                    }

                    $tituloTabla = "HISTORIAL DE CAMBIOS ($fecha_ini al $fecha_fin)";
                    break;
            }
        }


         $conquery = " SELECT *
                    FROM clientes c
                    INNER JOIN personas p ON p.persona_id=c.persona_id                    
                    $condiciones
                    WHERE 0=0 $filtro ;";
        
        $consulta1 = $this->Cliente->query($conquery);
        $cadena1 = Set::extract($consulta1, '{n}.0');
        $info = array('success' => true, 'opcion' => $opcion_reporte);
        $this->Session->write('TituloReporteClientes', $tituloTabla);
        $this->Session->write('ReporteClientes', $cadena1);
        //echo print_r($cadena1);
        $this->set('info', $info);
        $this->render('respuestas/ver_reporte_clientes');
    }

    function listado_pdf() {
        Configure::write('debug', 0);
        $datos = $this->Session->read('ReporteClientes');
        $sucursal_id = $this->Session->read('Sucursal');
        $titulo = $this->Session->read('TituloReporteClientes');
        $this->loadModel('Sucursal');
        $sucursal = $this->Sucursal->find(array("Sucursal.sucursal_id" => $sucursal_id));
        //echo print_r($datos);
        $this->layout = 'pdf';


        /* echo "<pre>";
          echo print_r($datos);
          echo "</pre>"; */
        // Operaciones que deseamos realizar y variables que pasaremos a la vista.
        $this->set('datos', $datos);
        $this->set('titulo', $titulo);
        $this->set('sucursal', strtoupper($sucursal['Sucursal']['sucursal_nombre']));
        $this->render('vistas/listado_pdf');
        //$this->render();
    }
     function compras_pdf(){
         Configure::write('debug', 0);
        $datos = $this->Session->read('ReporteClientes');
        $sucursal_id=  $this->Session->read('Sucursal');
        $titulo=$this->Session->read('TituloReporteClientes');
        $this->loadModel('Sucursal');
        $sucursal=$this->Sucursal->find(array("Sucursal.sucursal_id" => $sucursal_id));
        //echo print_r($datos);
        $this->layout = 'pdf';
       $cont=0;
       $sumatoria=0;
       $contFacturadas=0;
       $contNoFacturadas=0;
        foreach ($datos as $value) {
            $venta_id=$value['venta_id'];
          $conquery = "SELECT p.producto_codigo, p.producto_nombre, u.unidad_sigla,d.d_venta_cantidad
                    FROM productos p
                    INNER JOIN unidads u ON u.unidad_id=p.unidad_id
                    INNER JOIN detalleventas d ON d.producto_id=p.producto_id
                    WHERE d.venta_id=$venta_id ORDER BY p.producto_nombre";

        $consulta = $this->Cliente->query($conquery);
        $cadena = Set::extract($consulta, '{n}.0');
         $productos='';
            foreach($cadena as $row){
                $productos.='<br>'.$row['producto_nombre'].' ('.$row['d_venta_cantidad'].' '.$row['unidad_sigla'].')' ;
            }
            if ($value['venta_facturada'])
                $contFacturadas++;
            else
               $contNoFacturadas++;
            $datos[$cont]['productos']=$productos;
            $sumatoria+=$value['venta_precio_total'];
            $cont++;
            
        }
        $totalCompras=$cont;
        $cont++;
        
        $datos[$cont]['venta_fecha']="TOTAL<br>($totalCompras compras)";
        $datos[$cont]['venta_precio_total']=$sumatoria;
        $datos[$cont]['venta_facturada']=' Facturadas: '.$contFacturadas.'<br> No Facturadas: '.$contNoFacturadas;
         
        /*echo "<pre>";
        echo print_r($datos);
        echo "</pre>";*/
        // Operaciones que deseamos realizar y variables que pasaremos a la vista.
        $this->set('datos', $datos);
        $this->set('titulo', $titulo);
        $this->set('sucursal', strtoupper($sucursal['Sucursal']['sucursal_nombre']));
       $this->render('vistas/compras_pdf');
        //$this->render();
        
    }
     function cambios_pdf(){
         Configure::write('debug', 0);
        $datos = $this->Session->read('ReporteClientes');
        $sucursal_id=  $this->Session->read('Sucursal');
        $titulo=$this->Session->read('TituloReporteClientes');
        $this->loadModel('Sucursal');
        $sucursal=$this->Sucursal->find(array("Sucursal.sucursal_id" => $sucursal_id));
        //echo print_r($datos);
        $this->layout = 'pdf';
       $cont=0;
       $sumatoria=0;
       $contFacturadas=0;
       $contNoFacturadas=0;
        foreach ($datos as $value) {
            $venta_id=$value['venta_id'];
          $conquery = "SELECT p.producto_codigo, p.producto_nombre, u.unidad_sigla,d.d_venta_cantidad,d.d_venta_tipo_cambio
                    FROM productos p
                    INNER JOIN unidads u ON u.unidad_id=p.unidad_id
                    INNER JOIN detalleventas d ON d.producto_id=p.producto_id
                    WHERE d.venta_id=$venta_id AND d.d_venta_tipo_cambio IS NOT NULL ORDER BY p.producto_nombre";

        $consulta = $this->Cliente->query($conquery);
        $cadena = Set::extract($consulta, '{n}.0');
         $productos='';
            foreach($cadena as $row){
                if($row['d_venta_tipo_cambio']=='CAMBIOINGRESO'){
                  $productosIngreso.='<br>'.$row['producto_nombre'].' ('.$row['d_venta_cantidad'].' '.$row['unidad_sigla'].')' ;  
                }
                 if($row['d_venta_tipo_cambio']=='CAMBIOSALIDA'){
                  $productosSalida.='<br>'.$row['producto_nombre'].' ('.$row['d_venta_cantidad'].' '.$row['unidad_sigla'].')' ;  
                }
                
            }
            if ($value['venta_facturada'])
                $contFacturadas++;
            else
               $contNoFacturadas++;
            $datos[$cont]['productosIngreso']=$productosIngreso;
            $datos[$cont]['productosSalida']=$productosSalida;
            $sumatoria+=$value['venta_precio_total'];
            $cont++;
            
        }
        $totalCambios=$cont;
        $cont++;
        
        $datos[$cont]['venta_cambio_fecha']="TOTAL<br>($totalCambios cambios)";
        $datos[$cont]['venta_precio_total']=$sumatoria;
        $datos[$cont]['venta_facturada']=' Facturadas: '.$contFacturadas.'<br> No Facturadas: '.$contNoFacturadas;
         
       /* echo "<pre>";
        echo print_r($datos);
        echo "</pre>";*/
        // Operaciones que deseamos realizar y variables que pasaremos a la vista.
        $this->set('datos', $datos);
        $this->set('titulo', $titulo);
        $this->set('sucursal', strtoupper($sucursal['Sucursal']['sucursal_nombre']));
       $this->render('vistas/cambios_pdf');
        //$this->render();
        
    }

}

?>