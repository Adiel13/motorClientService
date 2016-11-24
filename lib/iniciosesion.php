<?php

class iniciosesion{
	
	var $permisos;
	var $tokenSesion;
	
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
                $permisos = pg_fetch_all($resultado);				
				if($permisos[0]['codigo'] >= 1){					
					$this->permisos = $permisos;
					
					$query = "select crear_sesion('kalajpop1191', 1::smallint)";
					$resultado = pg_exec($conexion, $query);

					if (!$conexion) {
						return false;
					}else{						
						$tokensalida= pg_fetch_all($resultado);				
						$this->tokenSesion = $tokensalida[0]['crear_sesion'];											
						pg_close($conexion);
						return true;
					}
				}				                
            }
        }
        pg_close($conexion);
		return false;
	}
}

$sesion = new iniciosesion();
$res = $sesion->iniciarsesion("kalajpop1191", "fafa");
if($res){
	echo $sesion->tokenSesion;
}
?>