<?php

class iniciosesion{
	
	var $permisos;
	var $tokenSesion;
	
    function __construct() {
        
    }

    function conexionBD() {
        return pg_connect("user=usrlogin password=l0g1n dbname=clientservice host=localhost port=5432");
    }
	
	function iniciarsesion($usuario, $token, $sistema){
		$conexion = $this->conexionBD();
		$query = "select codigo, descripcion from inicio_sesion('" .$usuario."', '" . $token."') 
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
					
					$query = "select crear_sesion('".$usuario."', ".$sistema."::smallint)";
					$resultado = pg_exec($conexion, $query);

					if (!$conexion) {
						$this->tokenSesion = $usuario . $token . $sistema . "hola mundo";
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
	
	function esSesionActiva($usuario, $token, $sistema){
				$conexion = $this->conexionBD();
		$query = "select verificar_sesion('".$usuario."'::text, '".$token."'::text, ".$sistema."::smallint)";		
		if (!$conexion) {
            return false;
        } else {
            $resultado = pg_exec($conexion, $query);
            $total = pg_num_rows($resultado);
            if ($total > 0) {
                $activo = pg_fetch_all($resultado);				
				if($activo[0]['verificar_sesion'] == 1){					
					return 1;
				}else{
					return 0;
				}				                
            }			
        }		
        pg_close($conexion);
		return false;
	}
}


?>