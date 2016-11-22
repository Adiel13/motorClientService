<?php

class iniciosesion{
	
    function __construct() {
        
    }

    function conexionBD() {
        return pg_connect("user=usrlogin password=l0g1n dbname=clientservice host=localhost port=5432");
    }
	
	function iniciarsesion($usuario, $token){
		$conexion = $this->conexionBD();
		$query = "select codigo, descripcion from inicio_sesion('kalajpop1191', md5('kevin313')) 
					AS (codigo smallint, descripcion varchar(200))";		
		if (!$conexion) {
            return false;
        } else {
            $resultado = pg_exec($conexion, $query);
            $total = pg_num_rows($resultado);
            if ($total > 0) {
                pg_close($conexion);
                $permisos = pg_fetch_all($resultado);				
				if($permisos[0]['codigo'] >= 1){
					print_r($permisos);
				}				
                return $permisos;
            }
        }
        pg_close($conexion);
	}
}

$sesion = new iniciosesion();
$sesion->iniciarsesion("kalajpop1191", "fafa");
?>