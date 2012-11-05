<?php

//App::import('Controller', 'rols');
class VentasController extends AppController {

    var $helpers = array('Html', 'Form', 'Javascript');
    var $components = array('RequestHandler');

    function test() {
        $this->layout = 'ajax';
        echo "<pre>";
        echo print_r($this->Venta->find('all'));
        echo "</pre>";
    }

    //formulario para la autenticacion
    function venta() {
        Configure::write('debug', '0'); // deshabilitamos el debug de cakephp
        //$rols = new RolsController();
        //$datosSesion=$this->tieneSesion();
        //$this->loadModel('Rol');
        //$permisos=$rols->verificar_permisos($_REQUEST['opcionId'],$datosSesion['Rol']['rol_id'],$this->Marca);
        //echo print_r($permisos);
        //$this->set('permisos',$permisos);
        $this->render('vistas/venta');
    }

    function cambio() {
        Configure::write('debug', '0'); // deshabilitamos el debug de cakephp
        //$rols = new RolsController();
        //$datosSesion=$this->tieneSesion();
        //$this->loadModel('Rol');
        //$permisos=$rols->verificar_permisos($_REQUEST['opcionId'],$datosSesion['Rol']['rol_id'],$this->Marca);
        //echo print_r($permisos);
        //$this->set('permisos',$permisos);
        $this->render('vistas/cambio');
    }

    //funcion que retorna todas las unidades registradas en BD

    function get_ventas() {
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
        if (isset($_REQUEST['venta_id']))
            $filtro = " AND v.venta_id=" . $_REQUEST['venta_id'];
        else
            $filtro = "";
        $conquery = "SELECT v.*
                    FROM ventas v
                    WHERE 0=0 $filtro
                    ORDER BY v.venta_fecha DESC
                    LIMIT $limit OFFSET $start
                ";

        $consulta = $this->Venta->query($conquery);
        $cadena = Set::extract($consulta, '{n}.0');
        $count = $this->Venta->find('count');
        //  echo print_r($cadena);
        $this->set('total', $count);
        $this->set('datos', $cadena);
        $this->render('respuestas/get_ventas');
    }

    function guardar_venta() {
        Configure::write('debug', '0');
        $this->layout = 'ajax';
        //$info = array('success' => true,'msg'=> 'Se almacen&oacute; el nuevo registro correctamente');

        $datosSesion = $this->Session->read('Usuario');
        $sucursal_id_sesion = $this->Session->read('Sucursal');
        if ($sucursal_id_sesion > 0) {
            if ($datosSesion) {
                if ($this->data) {
                    if ($this->data["Venta"]["venta_facturada"] != 'on') {
                        $this->data["Venta"]["venta_facturada"] = false;
                        $this->data["Venta"]["venta_nro_factura"] = "";
                    }


                    //$this->data["Compra"]["proveedor_id"] = 0;
                    $this->data["Venta"]["sucursal_id"] = $sucursal_id_sesion;
                    $sucursal_id = $this->data["Venta"]["sucursal_id"];
                    $fecha_venta = $this->data["Venta"]["venta_fecha"];
                    $this->data['Venta']['venta_usuario_resp'] = $datosSesion['Usuario']['login'];
                    $this->data["Venta"]["venta_fecha"] = $this->data["Venta"]["venta_fecha"] . " " . date("H:i:s");
                    $records = json_decode(stripslashes($this->data['Venta']['records']));
                    //echo print_r($records);exit;
                    if ($this->Venta->Save($this->data)) {
                        if ($this->Venta->getInsertId() != '') {
                            $id = $this->Venta->getInsertId();
                            $msg = "Se almacen&oacute; el nuevo registro correctamente";
                        } else {
                            $id = $this->data['Venta']['venta_id'];
                            $msg = "El registro fu&eacute; modificado correctamente";
                        }
                        $this->loadModel('Detalleventa');

                        foreach ($records as $record) {

                            if ($record->d_venta_id != -1) {
                                $this->data['Detalleventa']['d_venta_id'] = $record->d_venta_id;
                            } else {
                                $this->data['Detalleventa']['d_venta_id'] = '';
                            }
                            $this->data['Detalleventa']['venta_id'] = $id;
                            $this->data['Detalleventa']['producto_id'] = $record->producto_id;
                            $this->data['Detalleventa']['d_venta_cantidad'] = $record->d_venta_cantidad;
                            $this->data['Detalleventa']['d_venta_total'] = $record->d_venta_cantidad * $record->d_venta_precio;
                            $this->data['Detalleventa']['d_venta_precio'] = $record->d_venta_precio;
                            $this->data['Detalleventa']['d_venta_costo'] = $record->d_venta_costo;
                            $kardex_id = $this->Detalleventa->query("SELECT * FROM public.f_get_kardex_id($record->producto_id,$sucursal_id)");
                            $kardex_id = Set::extract($kardex_id, '{n}.0');
                            $kardex_id = $kardex_id[0]["f_get_kardex_id"];


                            /* if ($record->movimiento_hora != "")
                              $fecha_mov = $fecha_venta . " " . $record->movimiento_hora;
                              else
                              $fecha_mov = $fecha_venta . " 00:00:00";
                              // echo print_r($this->data['Detalleventa']); */
                            $fecha_mov = $this->data["Venta"]["venta_fecha"];
                            if ($this->Detalleventa->Save($this->data['Detalleventa'])) {
                                if ($this->Detalleventa->getInsertId() != '') {
                                    $id_d = $this->Detalleventa->getInsertId();
                                    $actualizar = 'false';
                                } else {
                                    $id_d = $this->data['Detalleventa']['d_venta_id'];
                                    $actualizar = 'true';
                                }
                                $sql = "SELECT * FROM public.salida_mov_venta($kardex_id,$record->d_venta_cantidad,'$fecha_mov','VENTA',$id,$id_d,$actualizar)";
                                $res = $this->Detalleventa->query($sql);
                                $msg = 'Se guardaron todos los items correctamente' . $res['actualizar_saldo_mov'];
                            } else {
                                $msg = "Se almacen&oacute; el ingreso pero algunos registros del detalle no pudieron guardarse";
                            }
                        }
                        $info = array('success' => true, 'msg' => $msg, 'id' => $id);
                        $this->log("Venta $id almacenada en, Ventas por->" . $datosSesion['Usuario']['login'] . " msg: " . $msg, LOG_DEBUG);
                    } else {
                        $info = array('success' => false, 'msg' => "No se pudo almacenar. Intentelo nuevamente");
                        $this->log("no se pudo almacenar la venta  en, Ventas por->" . $datosSesion['Usuario']['login'] . " msg: " . $msg);
                    }
                } else {
                    $info = array('success' => false, 'msg' => "Error en el envio de datos");
                    $this->log("Error en el envio de datos, Ventas por->" . $datosSesion['Usuario']['login']);
                }
            } else {
                $info = array('success' => false, 'msg' => "No tiene una sesi&oacuten activa", 'redir' => true);
                $this->log("Error No tiene una sesi&oacuten activa, Ventas");
            }
        } else {
            $info = array('success' => false, 'msg' => "No puede realizar Ventas con esta sesi&oacute;n. Por favor cierre sesi&oacuten; y vuelva a ingresas escogiendo alguna sucusal distinta de la opci&oacute;n TODAS", 'redir' => true);
            $this->log("Error session no valida, Ventas");
        }


        $this->set('info', $info);
        $this->render('respuestas/guardar_venta');
    }

    //funcion para registrar los cambios relacionados a una venta
    function registrar_cambio() {
        Configure::write('debug', '0');
        $this->layout = 'ajax';
        //$info = array('success' => true,'msg'=> 'Se almacen&oacute; el nuevo registro correctamente');

        $datosSesion = $this->Session->read('Usuario');
        $sucursal_id_sesion = $this->Session->read('Sucursal');
        if ($sucursal_id_sesion > 0) {
            if ($datosSesion) {
                if ($this->data) {
                    if ($this->Venta->Save($this->data)) {
                        $id = $this->data['Venta']['venta_id'];
                        $sucursal_id = $this->data['Venta']['sucursal_id'];
                        $fecha_venta = $this->data['Venta']['fecha_completa'];
                        $recordsIngreso = json_decode(stripslashes($this->data['Venta']['recordsIngreso']));
                        $recordsSalida = json_decode(stripslashes($this->data['Venta']['recordsSalida']));

                        //echo print_r($records);exit;

                        $this->loadModel('Detalleventa');
                        //registrar los items que estan ingresando
                        foreach ($recordsIngreso as $record) {

                            if ($record->d_venta_id != -1) {
                                $this->data['Detalleventa']['d_venta_id'] = $record->d_venta_id;
                            } else {
                                $this->data['Detalleventa']['d_venta_id'] = '';
                            }
                            $this->data['Detalleventa']['venta_id'] = $id;
                            $this->data['Detalleventa']['producto_id'] = $record->producto_id;
                            $this->data['Detalleventa']['d_venta_cantidad'] = $record->d_venta_cantidad;
                            $this->data['Detalleventa']['d_venta_total'] = $record->d_venta_cantidad * $record->d_venta_precio;
                            $this->data['Detalleventa']['d_venta_precio'] = $record->d_venta_precio;
                            $this->data['Detalleventa']['d_venta_cambio'] = 'true';
                            $this->data['Detalleventa']['d_venta_tipo_cambio'] = 'CAMBIOINGRESO';
                            $kardex_id = $this->Detalleventa->query("SELECT * FROM public.f_get_kardex_id($record->producto_id,$sucursal_id)");
                            $kardex_id = Set::extract($kardex_id, '{n}.0');
                            $kardex_id = $kardex_id[0]["f_get_kardex_id"];



                            if ($this->Detalleventa->Save($this->data['Detalleventa'])) {
                                if ($this->Detalleventa->getInsertId() != '') {
                                    $id_d = $this->Detalleventa->getInsertId();
                                } else {
                                    $id_d = $this->data['Detalleventa']['d_venta_id'];
                                }
                                if ($record->d_venta_cambio) {
                                    $actualizar = 'true';
                                } else {
                                    $actualizar = 'false';
                                }
                                $sql = "SELECT * FROM public.f_movimientos_insert($id_d,$kardex_id,'$fecha_venta',$record->d_venta_cantidad,0,'CAMBIOINGRESO',$id,$record->d_venta_costo,0,0,$actualizar)";
                                $res = $this->Detalleventa->query($sql);

                                $msg = 'Se guardaron todos los items correctamente';
                            } else {
                                $msg = "Se almacen&oacute; el ingreso pero algunos registros del detalle no pudieron guardarse";
                            }
                        }
                        //registrar los items que estan saliendo
                        foreach ($recordsSalida as $record) {

                            if ($record->d_venta_id != -1) {
                                $this->data['Detalleventa']['d_venta_id'] = $record->d_venta_id;
                            } else {
                                $this->data['Detalleventa']['d_venta_id'] = '';
                            }
                            $this->data['Detalleventa']['venta_id'] = $id;
                            $this->data['Detalleventa']['producto_id'] = $record->producto_id;
                            $this->data['Detalleventa']['d_venta_cantidad'] = $record->d_venta_cantidad;
                            $this->data['Detalleventa']['d_venta_total'] = $record->d_venta_cantidad * $record->d_venta_precio;
                            $this->data['Detalleventa']['d_venta_precio'] = $record->d_venta_precio;
                            $this->data['Detalleventa']['d_venta_costo'] = $record->d_venta_costo;
                            $this->data['Detalleventa']['d_venta_cambio'] = 'true';
                            $this->data['Detalleventa']['d_venta_tipo_cambio'] = 'CAMBIOSALIDA';
                            $kardex_id = $this->Detalleventa->query("SELECT * FROM public.f_get_kardex_id($record->producto_id,$sucursal_id)");
                            $kardex_id = Set::extract($kardex_id, '{n}.0');
                            $kardex_id = $kardex_id[0]["f_get_kardex_id"];



                            // echo print_r($this->data['Detalleventa']);
                            if ($this->Detalleventa->Save($this->data['Detalleventa'])) {
                                if ($this->Detalleventa->getInsertId() != '') {
                                    $id_d = $this->Detalleventa->getInsertId();
                                    $actualizar = 'false';
                                } else {
                                    $id_d = $this->data['Detalleventa']['d_venta_id'];
                                    $actualizar = 'true';
                                }
                                $sql = "SELECT * FROM public.salida_mov_venta($kardex_id,$record->d_venta_cantidad,'$fecha_venta','CAMBIOSALIDA',$id,$id_d,$actualizar)";
                                $res = $this->Detalleventa->query($sql);
                                $msg = 'Se guardaron todos los items correctamente' . $res['actualizar_saldo_mov'];
                            } else {
                                $msg = "Se almacen&oacute; el ingreso pero algunos registros del detalle no pudieron guardarse";
                            }
                        }
                        $info = array('success' => true, 'msg' => $msg, 'id' => $id);
                        $this->log("Cambio $id almacenada en, Ventas por->" . $datosSesion['Usuario']['login'] . " msg: " . $msg, LOG_DEBUG);
                    }
                } else {
                    $info = array('success' => false, 'msg' => "Error en el envio de datos");
                    $this->log("Error en el envio de datos, Ventas por->" . $datosSesion['Usuario']['login']);
                }
            } else {
                $info = array('success' => false, 'msg' => "No tiene una sesi&oacuten activa", 'redir' => true);
                $this->log("Error No tiene una sesi&oacuten activa, Cambios");
            }
        } else {
            $info = array('success' => false, 'msg' => "No puede realizar Cambios con esta sesi&oacute;n. Por favor cierre sesi&oacuten; y vuelva a ingresas escogiendo alguna sucusal distinta de la opci&oacute;n TODAS", 'redir' => true);
            $this->log("Error session no valida, Cambios");
        }


        $this->set('info', $info);
        $this->render('respuestas/registrar_cambio');
    }

    //funcion par aobtener los datos del detalle de una compra
    function get_detalle_venta() {
        Configure::write('debug', '0');
        $this->layout = 'ajax';
        //$consulta=$this->Usuario->find('all');
        if (isset($_REQUEST['venta_id']))
            $venta = " AND d.venta_id=" . $_REQUEST['venta_id'];
        else
            $venta = "";
        if (isset($_REQUEST['cambio']))
            $cambio = " AND ( d.d_venta_tipo_cambio IS NULL OR d_venta_tipo_cambio='CAMBIOSALIDA')";
        else
            $cambio = "";

        $conquery = "SELECT v.sucursal_id,to_char(v.venta_fecha,'dd/MM/yyyy') as venta_fecha,u.unidad_id, u.unidad_sigla,p.producto_id,p.producto_nombre, p.producto_codigo,
                            d.d_venta_id,d.d_venta_cantidad,d.d_venta_precio,d.d_venta_total, d_venta_costo,d_venta_tipo_cambio
                    FROM productos p
                    INNER JOIN unidads u ON u.unidad_id=p.unidad_id
                    INNER JOIN detalleventas d ON d.producto_id=p.producto_id
                    INNER JOIN ventas v ON v.venta_id = d.venta_id
                    
                    WHERE 0=0 $venta $cambio
                    ORDER BY p.producto_codigo
                ";

        $consulta = $this->Venta->query($conquery);
        $cadena = Set::extract($consulta, '{n}.0');
        $count = $this->Venta->find('count');
        //  echo print_r($cadena);
        $this->set('total', $count);
        $this->set('datos', $cadena);
        $this->render('respuestas/get_detalle_venta');
    }

    function eliminar_venta() {
        Configure::write('debug', '0');
        if ($_REQUEST['id']) {
            $this->loadModel('Detalleventa');
            $this->loadModel('Movimiento');
            $id = $_REQUEST['id'];

            if ($id >= 0) {

                if ($this->Movimiento->deleteAll(array("Movimiento.movimiento_id_tipo" => $id, "Movimiento.movimiento_tipo" => "VENTA"))) {
                    if ($this->Detalleventa->deleteAll(array("Detalleventa.venta_id" => $id))) {
                        if ($this->Venta->delete($id)) {
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
        $this->render('respuestas/eliminar_venta');
    }
    function eliminar_cambios() {
        Configure::write('debug', '0');
        if ($_REQUEST['venta_id']) {
            $this->loadModel('Detalleventa');
            $this->loadModel('Movimiento');
            $id = $_REQUEST['venta_id'];

            if ($id >= 0) {

                if ($this->Movimiento->deleteAll(array("Movimiento.movimiento_id_tipo" => $id, "Movimiento.movimiento_tipo" => array("CAMBIOINGRESO","CAMBIOSALIDA")))) {
                    if ($this->Detalleventa->deleteAll(array("Detalleventa.venta_id" => $id,"Detalleventa.d_venta_tipo_cambio" => "CAMBIOSALIDA"))) {
                       $sql="UPDATE detalleventas SET d_venta_tipo_cambio=NULL WHERE d_venta_tipo_cambio='CAMBIOINGRESO' AND venta_id=$id";
                       $res = $this->Detalleventa->query($sql);
                       $this->data['Venta']['venta_id']=$id;
                       $this->data['Venta']['cambio_autorizado_por']='';
                       $this->data['Venta']['cambio_autorizado_por_id']='';
                        if ($this->Venta->Save($this->data['Venta'])) {
                            $info = array('success' => true, 'msg' => 'Los cambios relacionados a esta venta fueron eliminados');
                            //$sql = "SELECT * FROM public.actualizar_saldo_mov($kardex_id,'$fecha_mov','C')";
                            //$res = $this->Compra->query($sql);
                        } else {
                            $info = array('success' => false, 'msg' => 'No se pudo eliminar los cambios relacionados a esta venta');
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
        $this->render('respuestas/eliminar_cambios');
    }

    function verificar_disponibilidad() {
        Configure::write('debug', '0');
        if (isset($_REQUEST['id'])) {
            $this->loadModel('Kardex');
            $producto_id = $_REQUEST['id'];
            $cantidad = $_REQUEST['cantidad'];
            if($_REQUEST['sucursal_id']>0){
               $sucursal_id = $_REQUEST['sucursal_id']; 
            }else{
              $sucursal_id = $this->Session->read('Sucursal');  
            }
            
            if ($producto_id >= 0) {
                $kardex = $this->Kardex->find(array("Kardex.producto_id" => $producto_id, "Kardex.sucursal_id" => $sucursal_id));
                if ($kardex['Kardex']['kardex_saldo_cantidad'] >= $cantidad) {
                    $info = array('success' => true, 'msg' => 'OK');
                } else {
                    $info = array('success' => false, 'msg' => 'La cantidad disponible de este producto es ' . $kardex['Kardex']['kardex_saldo_cantidad']);
                }
            } else {
                $info = array('success' => false, 'msg' => 'El Identificador ' . $producto_id . ' no existe');
            }
        }
        $this->set('info', $info);
        $this->render('respuestas/verificar_disponibilidad');
    }

    function eliminar_detalle() {
        Configure::write('debug', '0');
        if ($_REQUEST['id']) {
            $this->loadModel('Detalleventa');
            $this->loadModel('Movimiento');
            $id = $_REQUEST['id'];

            if ($id >= 0) {
                if ($this->Movimiento->deleteAll(array("Movimiento.movimiento_detalle_id" => $id, "Movimiento.movimiento_tipo" => "VENTA"))) {
                    if ($this->Detalleventa->delete($id)) {
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