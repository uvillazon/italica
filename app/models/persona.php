<?php
class Persona extends AppModel {
    var $primaryKey = 'persona_id';

    var $hasOne = array(
        'Usuario' => array(
            'className'     => 'Usuario',
            'foreignKey'    => 'persona_id'
        )
    );
}
?>