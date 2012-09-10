  Ext.define('App.Conf.Sucursales.Modelos.ModeloSucursal', {
            extend: 'Ext.data.Model',
            fields: [
                {name: 'sucursal_id', type: 'int'},
                {name: 'sucursal_nombre',  type: 'string'},
                {name: 'sucursal_dir',  type: 'string'}
            ]
        });