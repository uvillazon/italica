<?php
class Usuario extends AppModel {
    var $primaryKey = 'usuario_id';

    var $belongsTo = array('Rol' => array(
                            'className' => 'Rol',
                            'foreignKey' => 'rol_id'
            )
    );


}
?>