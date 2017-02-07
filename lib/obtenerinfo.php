<?php

class obtenerinfo{

    function __construct() {
        
    }

    function conexionBD() {
        return pg_connect("user=usrconsulta password=c0nsult4 dbname=clientservice host=localhost port=5432");
    }

    function obtenerDatos($idempleado){

		$conexion = $this->conexionBD();
		$query = "select e.*, tu.*  from empleado e inner join usuario u on 
	e.id_empleado = u.id_empleado 
    inner join tipo_usuario tu on
    	tu.tipo_usuario = u.tipo_usuario where e.id_empleado = '" . $idempleado . "'";		
		if (!$conexion) {
            return false;
        } else {
            $resultado = pg_exec($conexion, $query);
            $total = pg_num_rows($resultado);
            if ($total > 0) {
                $datos = pg_fetch_all($resultado);				
				return json_encode($datos);
            }			
        }		
        pg_close($conexion);
		return false;
    }
}
?>