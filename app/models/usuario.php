<?php
class Usuario extends AppModel {
    var $primaryKey = 'usuario_id';

    var $belongsTo = array('Rol' => array(
                            'className' => 'Rol',
                            'foreignKey' => 'rol_id'
        ),
            'Persona' => array(
                            'className' => 'Persona',
                            'foreignKey' => 'persona_id'
        ),
            'Sucursal' => array(
                            'className' => 'Sucursal',
                            'foreignKey' => 'sucursal_id'
        )
    );



}
?>