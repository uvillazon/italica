<?php

App::import('Controller', 'rols');

class TrasladosController extends AppController {

    var $helpers = array('Html', 'Form', 'Javascript');
    var $components = array('RequestHandler');

    function test() {
        $this->layout = 'ajax';
        echo "<pre>";
        echo print_r($this->Categoria->find('all'));
        echo "</pre>";
    }

    //formulario para la autenticacion
    function traslado() {
        Configure::write('debug', '0'); // deshabilitamos el debug de cakephp
        /* $rols = new RolsController();
          $datosSesion=$this->tieneSesion();
          $this->loadModel('Rol');
          $permisos=$rols->verificar_permisos($_REQUEST['opcionId'],$datosSesion['Rol']['rol_id'],$this->Marca);
          //echo print_r($permisos);
          $this->set('permisos',$permisos); */
        $this->render('vistas/traslado');
    }


    function get_traslados() {
        Configure::write('debug', '0');
        $this->layout = 'ajax';
        //$consulta=$this->Usuario->find('all');
        if (isset($_REQUEST['start']))
            $start = $_REQUEST['start'];
        else
            $start = 0;
        if (isset($_REQUEST['limit']))
            $limit = $_REQUEST['limit'];
        else
            $limit = 10000;
        if (isset($_REQUEST['traslado_id']))
            $filtro = " AND t.traslado_id=" . $_REQUEST['traslado_id'];
        else
            $filtro = "";
        $conquery = "SELECT t.*
                    FROM traslados t
                    WHERE 0=0 $filtro
                    ORDER BY t.traslado_fecha DESC
                    LIMIT $limit OFFSET $start
                ";

        $consulta = $this->Traslado->query($conquery);
        $cadena = Set::extract($consulta, '{n}.0');
        $count = $this->Traslado->find('count');
        //  echo print_r($cadena);
        $this->set('total', $count);
        $this->set('datos', $cadena);
        $this->render('respuestas/get_traslados');
    }
    function get_detalle_traslado() {
       // Configure::write('debug', '0');
        $this->layout = 'ajax';
        //$consulta=$this->Usuario->find('all');
        if (isset($_REQUEST['traslado_id']))
            $traslado = " AND d.traslado_id=" . $_REQUEST['traslado_id'];
        else
            $traslado = "";
      
        $conquery = "SELECT t.sucursal_origen, t.sucursal_destino,to_char(t.traslado_fecha,'dd/MM/yyyy') as traslado_fecha,u.unidad_id, u.unidad_sigla,p.producto_id,p.producto_nombre, p.producto_codigo,
                            d.d_traslado_id,d.d_traslado_cantidad,d.d_traslado_precio,d.d_traslado_total, d_traslado_costo
                    FROM productos p
                    INNER JOIN unidads u ON u.unidad_id=p.unidad_id
                    INNER JOIN detalletraslados d ON d.producto_id=p.producto_id
                    INNER JOIN traslados t ON t.traslado_id = d.traslado_id                    
                    WHERE 0=0 $traslado
                    ORDER BY p.producto_codigo
                ";

        $consulta = $this->Traslado->query($conquery);
        $cadena = Set::extract($consulta, '{n}.0');
        $count = $this->Traslado->find('count');
        //  echo print_r($cadena);
        $this->set('total', $count);
        $this->set('datos', $cadena);
        $this->render('respuestas/get_detalle_traslado');
    }

    function guardar_traslado() {
        Configure::write('debug', '0');
        $this->layout = 'ajax';
        //$info = array('success' => true,'msg'=> 'Se almacen&oacute; el nuevo registro correctamente');

        $datosSesion = $this->Session->read('Usuario');
        $sucursal_id_sesion = $this->Session->read('Sucursal');

        if ($datosSesion) {
            if ($this->data) {

                //$this->data["Compra"]["proveedor_id"] = 0;

                $sucursal_origen = $this->data["Traslado"]["sucursal_origen"];
                $sucursal_destino = $this->data["Traslado"]["sucursal_destino"];

                $this->data['Traslado']['traslado_usuario_resp'] = $datosSesion['Usuario']['login'];
                $this->data["Traslado"]["traslado_fecha"] = $this->data["Traslado"]["traslado_fecha"] . " " . date("H:i:s");
                $records = json_decode(stripslashes($this->data['Traslado']['records']));
                //echo print_r($records);exit;
                if ($this->Traslado->Save($this->data)) {
                    if ($this->Traslado->getInsertId() != '') {
                        $id = $this->Traslado->getInsertId();
                        $msg = "Se almacen&oacute; el nuevo registro correctamente";
                    } else {
                        $id = $this->data['Traslado']['traslado_id'];
                        $msg = "El registro fu&eacute; modificado correctamente";
                    }
                    $this->loadModel('Detalletraslado');

                    foreach ($records as $record) {

                        if ($record->d_traslado_id != -1) {
                            $this->data['Detalletraslado']['d_traslado_id'] = $record->d_traslado_id;
                        } else {
                            $this->data['Detalleventa']['d_traslado_id'] = '';
                        }
                        $this->data['Detalletraslado']['traslado_id'] = $id;
                        $this->data['Detalletraslado']['producto_id'] = $record->producto_id;
                        $this->data['Detalletraslado']['d_traslado_cantidad'] = $record->d_traslado_cantidad;
                        $this->data['Detalletraslado']['d_traslado_total'] = $record->d_traslado_cantidad * $record->d_traslado_precio;
                        $this->data['Detalletraslado']['d_traslado_precio'] = $record->d_traslado_precio;
                        $this->data['Detalletraslado']['d_traslado_costo'] = $record->d_traslado_costo;
                        $kardex_id_origen = $this->Detalletraslado->query("SELECT * FROM public.f_get_kardex_id($record->producto_id,$sucursal_origen)");
                        $kardex_id_origen = Set::extract($kardex_id_origen, '{n}.0');
                        $kardex_id_origen = $kardex_id_origen[0]["f_get_kardex_id"];
                        
                        $kardex_id_destino = $this->Detalletraslado->query("SELECT * FROM public.f_get_kardex_id($record->producto_id,$sucursal_destino)");
                        $kardex_id_destino = Set::extract($kardex_id_destino, '{n}.0');
                        $kardex_id_destino = $kardex_id_destino[0]["f_get_kardex_id"];

                        /* if ($record->movimiento_hora != "")
                          $fecha_mov = $fecha_venta . " " . $record->movimiento_hora;
                          else
                          $fecha_mov = $fecha_venta . " 00:00:00";
                          // echo print_r($this->data['Detalleventa']); */
                        $fecha_mov = $this->data["Traslado"]["traslado_fecha"];
                        if ($this->Detalletraslado->Save($this->data['Detalletraslado'])) {
                            if ($this->Detalletraslado->getInsertId() != '') {
                                $id_d = $this->Detalletraslado->getInsertId();
                                $actualizar = 'false';
                            } else {
                                $id_d = $this->data['Detalletraslado']['d_traslado_id'];
                                $actualizar = 'true';
                            }
                            $sql = "SELECT * FROM public.salida_mov_venta($kardex_id_origen,$record->d_traslado_cantidad,'$fecha_mov','TRASLADOSALIDA',$id,$id_d,$actualizar)";
                            $res = $this->Detalletraslado->query($sql);
                            $sql = "SELECT * FROM public.f_movimientos_insert($id_d,$kardex_id_destino,'$fecha_mov',$record->d_traslado_cantidad,0,'TRASLADOINGRESO',$id,$record->d_traslado_precio,0,0,$actualizar)";
                            $res = $this->Detalletraslado->query($sql);
                            
                            $msg = 'Se guardaron todos los items correctamente';
                        } else {
                            $msg = "Se almacen&oacute; el ingreso pero algunos registros del detalle no pudieron guardarse";
                        }
                    }
                    $info = array('success' => true, 'msg' => $msg, 'id' => $id);
                    $this->log("Traslado $id almacenada en, Traslados por->" . $datosSesion['Usuario']['login'] . " msg: " . $msg, LOG_DEBUG);
                } else {
                    $info = array('success' => false, 'msg' => "No se pudo almacenar. Intentelo nuevamente");
                    $this->log("no se pudo almacenar el traslado  en, Traslados por->" . $datosSesion['Usuario']['login'] . " msg: " . $msg);
                }
            } else {
                $info = array('success' => false, 'msg' => "Error en el envio de datos");
                $this->log("Error en el envio de datos, Traslados por->" . $datosSesion['Usuario']['login']);
            }
        } else {
            $info = array('success' => false, 'msg' => "No tiene una sesi&oacuten activa", 'redir' => true);
            $this->log("Error No tiene una sesi&oacuten activa, Traslados");
        }



        $this->set('info', $info);
        $this->render('respuestas/guardar_traslado');
    }
     function eliminar_traslado() {
        Configure::write('debug', '0');
        if ($_REQUEST['id']) {
            $this->loadModel('Detalletraslado');
            $this->loadModel('Movimiento');
            $id = $_REQUEST['id'];

            if ($id >= 0) {

                if ($this->Movimiento->deleteAll(array("Movimiento.movimiento_id_tipo" => $id, "Movimiento.movimiento_tipo" => array("TRASLADOINGRESO","TRASLADOSALIDA")))) {
                    if ($this->Detalletraslado->deleteAll(array("Detalletraslado.traslado_id" => $id))) {
                        if ($this->Traslado->delete($id)) {
                            $info = array('success' => true, 'msg' => 'El registro seleccionado fu&eacute; eliminado correctamente');
                            //$sql = "SELECT * FROM public.actualizar_saldo_mov($kardex_id,'$fecha_mov','C')";
                            //$res = $this->Compra->query($sql);
                        } else {
                            $info = array('success' => false, 'msg' => 'No se pudo eliminar el registro seleccionado');
                        }
                    } else {
                        $info = array('success' => false, 'msg' => 'No se pudo eliminar los registros del detalle');
                    }
                } else {
                    $info = array('success' => false, 'msg' => 'No se pudo eliminar los registros de movimientos');
                }
            } else {
                $info = array('success' => false, 'msg' => 'No se elim&ocute; el registro pq el Identificador ' . $id . ' no existe');
            }
        }
        $this->set('info', $info);
        $this->render('respuestas/eliminar_traslado');
    }

}

?>