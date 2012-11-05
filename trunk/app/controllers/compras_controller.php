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

    function detalle() {
        //Configure::write('debug', '0');// deshabilitamos el debug de cakephp
        $this->render('vistas/detalle');
    }

    function compra() {
        Configure::write('debug', '0'); // deshabilitamos el debug de cakephp
        $rols = new RolsController();
        $datosSesion = $this->tieneSesion();
        $this->loadModel('Rol');
        $permisos = $rols->verificar_permisos($_REQUEST['opcionId'], $datosSesion['Rol']['rol_id'], $this->Compra);
        $nombres = $datosSesion['Persona']['persona_nombres'] . ' ' . $datos['Persona']['persona_apellido1'] . ' ' . $datos['Persona']['persona_apellido2'];
        //echo $nombres;exit;
        $this->set('permisos', $permisos);
        $this->set('nombres', strtoupper($nombres));
        $this->render('vistas/compra');
    }

    //funcion que retorna todas las unidades registradas en BD

    function get_compras() {
        Configure::write('debug', '0');
        //$consulta=$this->Usuario->find('all');
        if (isset($_REQUEST['start']))
            $start = $_REQUEST['start'];
        else
            $start = 0;
        if (isset($_REQUEST['limit']))
            $limit = $_REQUEST['limit'];
        else
            $limit = 10000;

        $conquery = "SELECT c.*
                    FROM compras c
                    ORDER BY c.compra_fecha DESC
                    LIMIT $limit OFFSET $start
                ";

        $consulta = $this->Compra->query($conquery);
        $cadena = Set::extract($consulta, '{n}.0');
        $count = $this->Compra->find('count');
        //  echo print_r($cadena);
        $this->set('total', $count);
        $this->set('datos', $cadena);
        $this->render('respuestas/get_compras');
    }

    function guardar_compra() {
        Configure::write('debug', '0');
        $this->layout = 'ajax';
        //$info = array('success' => true,'msg'=> 'Se almacen&oacute; el nuevo registro correctamente');

        $datosSesion = $this->Session->read('Usuario');

        if ($datosSesion) {
            if ($this->data) {
                if ($this->data["Compra"]["compra_facturada"] != 'on'){
                    $this->data["Compra"]["compra_facturada"] = false;
                    $this->data["Compra"]["compra_nro_factura"] ="";
                }
                    

                //$this->data["Compra"]["proveedor_id"] = 0;
                $sucursal_id = $this->data["Compra"]["sucursal_id"];
                $fecha_compra = $this->data["Compra"]["compra_fecha"];
                $this->data['Compra']['compra_usuario_resp'] = $datosSesion['Usuario']['login'];
                $this->data["Compra"]["compra_fecha"]=$this->data["Compra"]["compra_fecha"]." ".date("H:i:s");
                $records = json_decode(stripslashes($this->data['Compra']['records']));
                //echo print_r($records);exit;
                if ($this->Compra->Save($this->data)) {
                    if ($this->Compra->getInsertId() != '') {
                        $id = $this->Compra->getInsertId();
                        $msg = "Se almacen&oacute; el nuevo registro correctamente";
                    } else {
                        $id = $this->data['Compra']['compra_id'];
                        $msg = "El registro fu&eacute; modificado correctamente";
                    }
                    $this->loadModel('Detallecompra');

                    foreach ($records as $record) { 

                        if ($record->d_compra_id != -1) {
                            $this->data['Detallecompra']['d_compra_id'] = $record->d_compra_id;
                        } else {
                            $this->data['Detallecompra']['d_compra_id'] = '';
                        }
                        $this->data['Detallecompra']['compra_id'] = $id;
                        $this->data['Detallecompra']['producto_id'] = $record->producto_id;
                        $this->data['Detallecompra']['d_compra_cantidad'] = $record->d_compra_cantidad;
                        $this->data['Detallecompra']['d_compra_total'] = $record->d_compra_cantidad*$record->d_compra_precio;
                        $this->data['Detallecompra']['d_compra_precio'] = $record->d_compra_precio;
                        $kardex_id = $this->Detallecompra->query("SELECT * FROM public.f_get_kardex_id($record->producto_id,$sucursal_id)");
                        $kardex_id = Set::extract($kardex_id, '{n}.0');
                        $kardex_id = $kardex_id[0]["f_get_kardex_id"];
                        if ($record->movimiento_hora != "")
                            $fecha_mov = $fecha_compra . " " . $record->movimiento_hora;
                        else
                            $fecha_mov = $fecha_compra. " 00:00:00";
                        // echo print_r($this->data['Detallecompra']);
                        if ($this->Detallecompra->Save($this->data['Detallecompra'])) {
                            if ($this->Detallecompra->getInsertId() != '') {
                                $id_d = $this->Detallecompra->getInsertId();
                                $actualizar = 'false';
                            } else {
                                $id_d = $this->data['Detallecompra']['d_compra_id'];
                                $actualizar = 'true';
                            }
                            $sql = "SELECT * FROM public.f_movimientos_insert($id_d,$kardex_id,'$fecha_mov',$record->d_compra_cantidad,0,'COMPRA',$id,$record->d_compra_precio,0,0,$actualizar)";
                            $res = $this->Detallecompra->query($sql);
                            if ($res) {
                                //$sql = "SELECT * FROM public.actualizar_saldo_mov($kardex_id,'$fecha_mov','C')";
                                $res = $this->Detallecompra->query($sql);
                                $res = Set::extract($res, '{n}.0');
                                //echo print_r($res);
                                $msg = 'Se guardaron todos los items correctamente' . $res['actualizar_saldo_mov'];
                            } else {
                                $msg = 'Se guardaron todos los items correctamente.Pero no se pudo guardar en la tabla movimientos';
                                $this->log("no se pudo almacenar en la tabla movimientos, Productos por->" . $datosSesion['Usuario']['login']);
                            }
                        } else {
                            $msg = "Se almacen&oacute; el ingreso pero algunos registros del detalle no pudieron guardarse";
                        }
                    }
                    $info = array('success' => true, 'msg' => $msg, 'id' => $id);
                    $this->log("Compra $id almacenada en, Compras por->" . $datosSesion['Usuario']['login'] . " msg: " . $msg, LOG_DEBUG);
                } else {
                    $info = array('success' => false, 'msg' => "No se pudo almacenar. Intentelo nuevamente");
                    $this->log("no se pudo almacenar el producto nuevo en, Productos por->" . $datosSesion['Usuario']['login'] . " msg: " . $msg);
                }
            } else {
                $info = array('success' => false, 'msg' => "Error en el envio de datos");
                $this->log("Error en el envio de datos, Productos por->" . $datosSesion['Usuario']['login']);
            }
        } else {
            $info = array('success' => false, 'msg' => "No tiene una sesi&oacuten activa", 'redir' => true);
            $this->log("Error No tiene una sesi&oacuten activa, Compras");
        }

        $this->set('info', $info);
        $this->render('respuestas/guardar_compra');
    }

    //funcion par aobtener los datos del detalle de una compra
    function get_detalle_compra() {
        Configure::write('debug', '0');
        $this->layout = 'ajax';
        //$consulta=$this->Usuario->find('all');
        if (isset($_REQUEST['compra_id']))
            $compra = " AND d.compra_id=" . $_REQUEST['compra_id'];
        else
            $compra = "";

        $conquery = "SELECT c.sucursal_id,to_char(c.compra_fecha,'dd/MM/yyyy') as compra_fecha,u.unidad_id, u.unidad_sigla,p.producto_id,p.producto_nombre, p.producto_codigo,
                            d.d_compra_id,d.d_compra_cantidad,d.d_compra_precio,d.d_compra_total
                    FROM productos p
                    INNER JOIN unidads u ON u.unidad_id=p.unidad_id
                    INNER JOIN detallecompras d ON d.producto_id=p.producto_id
                    INNER JOIN compras c ON c.compra_id = d.compra_id
                    
                    WHERE 0=0 $compra
                    ORDER BY p.producto_codigo
                ";

        $consulta = $this->Compra->query($conquery);
        $cadena = Set::extract($consulta, '{n}.0');
        $count = $this->Compra->find('count');
        //  echo print_r($cadena);
        $this->set('total', $count);
        $this->set('datos', $cadena);
        $this->render('respuestas/get_detalle_compra');
    }

    function eliminar_compra() {
        Configure::write('debug', '0');
        if ($_REQUEST['id']) {
            $this->loadModel('Detallecompra');
            $this->loadModel('Movimiento');
            $id = $_REQUEST['id'];

            if ($id >= 0) {
                 
                if ($this->Movimiento->deleteAll(array("Movimiento.movimiento_id_tipo" => $id, "Movimiento.movimiento_tipo" => "C"))) {
                    if ($this->Detallecompra->deleteAll(array("Detallecompra.compra_id" => $id))) {
                        if ($this->Compra->delete($id)) {
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
        $this->render('respuestas/eliminar_compra');
    }
    function eliminar_detalle() {
        Configure::write('debug', '0');
        if ($_REQUEST['id']) {
            $this->loadModel('Detallecompra');
            $this->loadModel('Movimiento');
            $id = $_REQUEST['id'];

            if ($id >= 0) {                 
                if ($this->Movimiento->deleteAll(array("Movimiento.movimiento_detalle_id" => $id, "Movimiento.movimiento_tipo" => "C"))) {
                    if ($this->Detallecompra->delete( $id)) {
                        $info = array('success' => true, 'msg' => 'El registro seleccionado fu&eacute; eliminado correctamente');                       
                    } else {
                         $info = array('success' => false, 'msg' => 'No se pudo eliminar el registro seleccionado');
                    }
                } else {
                    $info = array('success' => false, 'msg' => 'No se pudo eliminar los registros de movimientos');
                }
            } else {
                $info = array('success' => false, 'msg' => 'No se elim&ocute; el registro pq el Identificador ' . $id . ' no existe');
            }
        }
        $this->set('info', $info);
        $this->render('respuestas/eliminar_detalle');
    }

}

?>