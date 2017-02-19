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
    
    function obtenerPromedioLlegada($fechainicio, $fechafin, $sucursal){
        $conexion = $this->conexionBD();
		$query = "select extract(hour from inicio_venta) as y, count(*) as a from venta where
        id_sucursal = '".$sucursal."' and
        inicio_venta between '".$fechainicio."' and '".$fechafin."'
        group by 1
        order by 1
        ";		
		if (!$conexion) {
            return false;
        } else {
            $resultado = pg_exec($conexion, $query);
            $total = pg_num_rows($resultado);
            if ($total > 0) {
                $datos = pg_fetch_all($resultado);				
				return $datos;
            }			
        }		
        pg_close($conexion);
		return false;
    }
    
    function obtenerTablaLlegada($fechainicio, $fechafin, $sucursal){
        $conexion = $this->conexionBD();
		$query = "select e.nombre || ' ' || e.apellido as nombre, t.total as usuario, count(*) as total from venta v inner join empleado e on 
            v.id_empleado = e.id_empleado
                inner join (select v.id_sucursal, count(*) as total from venta v 
                    where
                        inicio_venta between '".$fechainicio."' and '".$fechafin."'
                        group by 1) t on
                    t.id_sucursal = v.id_sucursal
            where
                v.inicio_venta between '".$fechainicio."' and '".$fechafin."' and
                v.id_sucursal = '".$sucursal."'
            group by 1, 2";		
		if (!$conexion) {
            return false;
        } else {
            $resultado = pg_exec($conexion, $query);
            $total = pg_num_rows($resultado);
            if ($total > 0) {
                $datos = pg_fetch_all($resultado);				
				return $datos;
            }			
        }		
        pg_close($conexion);
		return false;
    }
    
    function obtenerListadoSucursales($token){
        $conexion = $this->conexionBD();
		$query = "select u.tipo_usuario from sesion s inner join usuario u on
	       s.id_empleado = u.id_empleado
            where
    	       s.token = '".$token."'";		
		if (!$conexion) {
            return false;
        } else {
            $resultado = pg_exec($conexion, $query);
            $total = pg_num_rows($resultado);
            if ($total > 0) {
                $datos = pg_fetch_all($resultado);		                
                if($datos[0]['tipo_usuario'] == 1){
                    $query = "select su.id_sucursal || '-' || su.direccion as name, su.id_sucursal as value from sesion s inner join usuario u on 
	                   s.id_empleado = u.id_empleado
                        inner join sucursal su on 
    	               su.id_empresa = u.id_empresa
                    where
	                   s.token = '".$token."'";		
                    if (!$conexion) {
                        return false;
                    } else {
                        $resultado = pg_exec($conexion, $query);
                        $total = pg_num_rows($resultado);
                    if ($total > 0) {
                        $datos2 = pg_fetch_all($resultado);		                
                    }
				    return $datos2;
                    }			        
                }
            }			
        }		
        pg_close($conexion);
		return false;
    }
    
    function obtenerAtencionVsVenta($fechainicio, $fechafin, $sucursal){
        $conexion = $this->conexionBD();
		$query = "select e.id_empleado, e.nombre || ' ' || e.apellido as nombre, v.id_sucursal, count(v.id_venta) as clientes, count(vs.id_venta) as venta 
        from venta v inner join empleado e on
	       e.id_empleado = v.id_empleado 
            left join venta_satisfactoria vs on 
    	       vs.id_venta = v.id_venta
        where
            v.inicio_venta between '".$fechainicio."' and '".$fechafin."' and
            v.id_sucursal = '".$sucursal."'
        group by 1, 2, 3";		
		if (!$conexion) {
            return false;
        } else {
            $resultado = pg_exec($conexion, $query);
            $total = pg_num_rows($resultado);
            if ($total > 0) {
                $datos = pg_fetch_all($resultado);				
				return $datos;
            }			
        }		
        pg_close($conexion);
		return false;        
    }
    
    function obtenerNumeroAtendidos($fechainicio, $fechafin, $sucursal){
        $conexion = $this->conexionBD();
		$query = "select e.id_empleado, e.nombre || ' ' || e.apellido as nombre, v.id_sucursal, count(*) as atendidos from venta v inner join empleado e on
	           e.id_empleado = v.id_empleado 
            where
    	           v.inicio_venta between '".$fechainicio."' and '".$fechafin."' and
                    v.id_sucursal = '".$sucursal."'
            group by 1, 2, 3";		
		if (!$conexion) {
            return false;
        } else {
            $resultado = pg_exec($conexion, $query);
            $total = pg_num_rows($resultado);
            if ($total > 0) {
                $datos = pg_fetch_all($resultado);				
				return $datos;
            }			
        }		
        pg_close($conexion);
		return false;        
    }    
    
    function obtenerTiempoAtencion($fechainicio, $fechafin, $sucursal){
        $conexion = $this->conexionBD();
		$query = "select e.nombre || ' ' || e.apellido as nombre,  extract ('epoch' from  sum(fin_venta -inicio_venta)::interval)::float / 60  as tiempoventa 
	               From venta v inner join empleado e on
    	               v.id_empleado = e.id_empleado
                where
	               v.inicio_venta between '".$fechainicio."' and '".$fechafin."'  and
                   v.id_sucursal = '".$sucursal."'
                group by 1";		
		if (!$conexion) {
            return false;
        } else {
            $resultado = pg_exec($conexion, $query);
            $total = pg_num_rows($resultado);
            if ($total > 0) {
                $datos = pg_fetch_all($resultado);				
				return $datos;
            }			
        }		
        pg_close($conexion);
		return false;        
    }     
    
}
?>
